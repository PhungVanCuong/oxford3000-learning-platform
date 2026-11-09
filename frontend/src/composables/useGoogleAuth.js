import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useToast } from '@/composables/useToast'

export function useGoogleAuth() {
  const router = useRouter()
  const authStore = useAuthStore()
  const toast = useToast()
  const isLoggingIn = ref(false)

  /**
   * Mở cửa sổ popup Google OAuth 2.0 độc lập (500x620)
   * Khi đăng nhập thành công:
   * 1. Tự động đóng tab / cửa sổ Google.
   * 2. Tự động quay về trang chủ ('/') ở trình duyệt ban đầu.
   * @param {Object} options
   * @param {Function} [options.onSuccess]
   * @param {Function} [options.onError]
   * @param {Function} [options.onCancel]
   */
  const openGoogleLoginPopup = async (options = {}) => {
    if (isLoggingIn.value) return
    isLoggingIn.value = true

    let popup = null
    let checkTimer = null
    let hasResolved = false
    let broadcastChannel = null

    const cleanup = () => {
      isLoggingIn.value = false
      if (checkTimer) {
        clearInterval(checkTimer)
        checkTimer = null
      }
      window.removeEventListener('message', handleMessage)
      window.removeEventListener('storage', handleStorage)
      if (broadcastChannel) {
        try {
          broadcastChannel.close()
        } catch (e) {}
        broadcastChannel = null
      }
    }

    // Xử lý khi nhận được tín hiệu đăng nhập thành công từ tab Google
    const handleAuthSuccess = async (data) => {
      if (hasResolved) return
      hasResolved = true
      cleanup()

      // 1. Tự động đóng tab / cửa sổ popup Google từ phía trình duyệt ban đầu nếu popup còn mở
      if (popup && !popup.closed) {
        try {
          popup.close()
        } catch (e) {}
      }

      try {
        const { token, user } = data
        if (token) {
          authStore.token = token
          localStorage.setItem('oxford_token', token)
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

          if (user) {
            authStore.user = user
            localStorage.setItem('oxford_user', typeof user === 'string' ? user : JSON.stringify(user))
          }

          // Cập nhật thông tin chi tiết người dùng
          await authStore.fetchMe()

          toast.success(
            `Chào mừng ${authStore.userName || user?.name || 'bạn'} đã đăng nhập thành công!`,
            'Google OAuth 2.0'
          )

          if (typeof options.onSuccess === 'function') {
            options.onSuccess(authStore.user)
          }

          // 2. TỰ ĐỘNG QUAY VỀ TRANG CHỦ Ở TRÌNH DUYỆT BAN ĐẦU
          router.push('/')
        }
      } catch (err) {
        console.error('Lỗi sau khi nhận Google token:', err)
        toast.error('Đăng nhập thành công nhưng xảy ra lỗi đồng bộ hồ sơ.', 'Lỗi hệ thống')
        if (typeof options.onError === 'function') {
          options.onError(err)
        }
      }
    }

    // KÊNH 1: Lắng nghe qua BroadcastChannel (Hoạt động hoàn hảo xuyên tab/cửa sổ không phụ thuộc opener)
    try {
      broadcastChannel = new BroadcastChannel('oxvocab_google_auth')
      broadcastChannel.onmessage = (event) => {
        if (event.data && event.data.type === 'GOOGLE_AUTH_SUCCESS') {
          handleAuthSuccess(event.data)
        }
      }
    } catch (e) {
      console.warn('BroadcastChannel not supported:', e)
    }

    // KÊNH 2: Lắng nghe qua Storage Event (Fallback liên tab cho 100% trình duyệt)
    const handleStorage = (event) => {
      if (event.key === 'oxford_oauth_sync' && event.newValue) {
        try {
          const parsed = JSON.parse(event.newValue)
          if (parsed && parsed.type === 'GOOGLE_AUTH_SUCCESS') {
            handleAuthSuccess(parsed)
          }
        } catch (e) {}
      }
    }
    window.addEventListener('storage', handleStorage)

    // KÊNH 3: Lắng nghe qua window.postMessage (Cho cửa sổ popup chuẩn)
    const handleMessage = (event) => {
      if (event.data && event.data.type === 'GOOGLE_AUTH_SUCCESS') {
        handleAuthSuccess(event.data)
      }
    }
    window.addEventListener('message', handleMessage)

    try {
      // 1. Lấy URL Google OAuth từ backend
      const res = await axios.get('/api/auth/google/url?popup=1')
      
      if (!res.data || res.data.status !== 'success' || !res.data.data?.url) {
        throw new Error(res.data?.message || 'Không thể khởi tạo đường dẫn xác thực Google.')
      }

      const authUrl = res.data.data.url

      // 2. Tính toán kích thước và vị trí căn giữa màn hình
      const width = 500
      const height = 620
      const left = Math.max(0, Math.round(window.screenX + (window.outerWidth - width) / 2))
      const top = Math.max(0, Math.round(window.screenY + (window.outerHeight - height) / 2.5))

      // 3. Mở popup window với tên cửa sổ cố định
      popup = window.open(
        authUrl,
        'OxVocabGoogleAuthPopup',
        `width=${width},height=${height},top=${top},left=${left},status=no,resizable=yes,scrollbars=yes`
      )

      // 4. Nếu trình duyệt chặn Popup, chuyển hướng tab trực tiếp
      if (!popup || popup.closed || typeof popup.closed === 'undefined') {
        cleanup()
        toast.info('Trình duyệt đang chặn cửa sổ Popup. Chuyển hướng tab trực tiếp...', 'Thông báo')
        window.location.href = authUrl
        return
      }

      if (window.focus && popup) {
        popup.focus()
      }

      // 5. Theo dõi nếu người dùng tự tay tắt popup mà chưa hoàn tất
      checkTimer = setInterval(() => {
        if (popup && popup.closed) {
          // Cho độ trễ 300ms kiểm tra xem có event thành công vừa bắn sang không
          setTimeout(() => {
            if (!hasResolved) {
              // Kiểm tra trong localStorage xem vừa có sync thành công trong 4 giây qua không
              const syncData = localStorage.getItem('oxford_oauth_sync')
              if (syncData) {
                try {
                  const parsed = JSON.parse(syncData)
                  if (parsed.type === 'GOOGLE_AUTH_SUCCESS' && (Date.now() - parsed.timestamp) < 5000) {
                    handleAuthSuccess(parsed)
                    return
                  }
                } catch (e) {}
              }

              cleanup()
              toast.info('Đã đóng cửa sổ đăng nhập Google.', 'Đã hủy')
              if (typeof options.onCancel === 'function') {
                options.onCancel()
              }
            }
          }, 300)
        }
      }, 500)

    } catch (err) {
      cleanup()
      console.error('Google Auth Popup Error:', err)
      const errorMsg = err.response?.data?.message || err.message || 'Không thể kết nối máy chủ xác thực.'
      toast.warning(errorMsg, 'Google Sign-in')
      if (typeof options.onError === 'function') {
        options.onError(err)
      }
    }
  }

  return {
    isLoggingIn,
    openGoogleLoginPopup,
  }
}
