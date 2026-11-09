import { ref } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useToast } from '@/composables/useToast'

export function useGoogleAuth() {
  const authStore = useAuthStore()
  const toast = useToast()
  const isLoggingIn = ref(false)

  /**
   * Mở cửa sổ popup Google OAuth 2.0 độc lập (500x620)
   * Không làm tải lại trang chính, giữ nguyên trạng thái học tập hiện tại.
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

    const cleanup = () => {
      isLoggingIn.value = false
      if (checkTimer) {
        clearInterval(checkTimer)
        checkTimer = null
      }
      window.removeEventListener('message', handlePostMessage)
    }

    const handlePostMessage = async (event) => {
      // Nhận tín hiệu xác thực thành công từ popup bridge hoặc callback view
      if (!event.data || event.data.type !== 'GOOGLE_AUTH_SUCCESS') {
        return
      }

      hasResolved = true
      cleanup()

      try {
        const { token, user } = event.data
        if (token) {
          authStore.token = token
          localStorage.setItem('oxford_token', token)
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

          if (user) {
            authStore.user = user
            localStorage.setItem('oxford_user', JSON.stringify(user))
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
        }
      } catch (err) {
        console.error('Lỗi sau khi nhận Google token:', err)
        toast.error('Đăng nhập thành công nhưng xảy ra lỗi đồng bộ hồ sơ.', 'Lỗi hệ thống')
        if (typeof options.onError === 'function') {
          options.onError(err)
        }
      }
    }

    // Đăng ký nhận tin nhắn từ popup
    window.addEventListener('message', handlePostMessage)

    try {
      // 1. Gọi backend để lấy URL Google Auth có CSRF State bảo mật
      const res = await axios.get('/api/auth/google/url?popup=1')
      
      if (!res.data || res.data.status !== 'success' || !res.data.data?.url) {
        throw new Error(res.data?.message || 'Không thể khởi tạo đường dẫn xác thực Google.')
      }

      const authUrl = res.data.data.url

      // 2. Tính toán vị trí căn giữa màn hình Desktop/Laptop
      const width = 500
      const height = 620
      const left = Math.max(0, Math.round(window.screenX + (window.outerWidth - width) / 2))
      const top = Math.max(0, Math.round(window.screenY + (window.outerHeight - height) / 2.5))

      // 3. Mở popup window
      popup = window.open(
        authUrl,
        'OxVocabGoogleAuthPopup',
        `width=${width},height=${height},top=${top},left=${left},status=no,resizable=yes,scrollbars=yes`
      )

      // 4. Nếu trình duyệt chặn Popup (Popup Blocker)
      if (!popup || popup.closed || typeof popup.closed === 'undefined') {
        cleanup()
        toast.info('Trình duyệt đang chặn cửa sổ Popup. Hệ thống chuyển hướng tab trực tiếp...', 'Thông báo')
        window.location.href = authUrl
        return
      }

      // Đưa popup lên trên cùng
      if (window.focus && popup) {
        popup.focus()
      }

      // 5. Theo dõi khi người dùng chủ động tắt popup mà chưa hoàn tất đăng nhập
      checkTimer = setInterval(() => {
        if (popup && popup.closed) {
          if (!hasResolved) {
            cleanup()
            toast.info('Đã đóng cửa sổ đăng nhập Google.', 'Đã hủy')
            if (typeof options.onCancel === 'function') {
              options.onCancel()
            }
          }
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
