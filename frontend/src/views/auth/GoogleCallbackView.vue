<template>
  <div class="google-callback-view min-vh-100 d-flex flex-column align-items-center justify-content-center p-3" :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-light text-dark'">
    <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 text-center max-w-md w-100" :class="authStore.themeMode === 'dark' ? 'bg-card-dark border border-secondary' : 'bg-white'">
      
      <!-- Logo Google hoặc Spinner -->
      <div class="mb-4">
        <div v-if="loading" class="position-relative d-inline-block">
          <div class="spinner-border text-teal" style="width: 4rem; height: 4rem;" role="status"></div>
          <div class="position-absolute top-50 start-50 translate-middle">
            <svg width="24" height="24" viewBox="0 0 24 24">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
          </div>
        </div>

        <div v-else-if="success" class="text-success mb-2">
          <i class="bi bi-check-circle-fill display-3"></i>
        </div>

        <div v-else-if="errorMessage" class="text-danger mb-2">
          <i class="bi bi-x-circle-fill display-3"></i>
        </div>
      </div>

      <!-- Trạng thái: Đang xử lý -->
      <div v-if="loading">
        <h4 class="fw-bold mb-2">Đang xác thực Google...</h4>
        <p class="text-secondary fs-7 mb-0">
          Hệ thống đang kiểm tra thông tin và đồng bộ tài khoản học tập của bạn. Vui lòng đợi trong giây lát!
        </p>
      </div>

      <!-- Trạng thái: Thành công -->
      <div v-else-if="success">
        <h4 class="fw-black text-success mb-2">Đăng Nhập Thành Công!</h4>
        <p class="text-secondary fs-7 mb-3">
          Chào mừng <strong>{{ loggedUserName }}</strong> quay trở lại với Oxford 3000™. Đang chuyển hướng...
        </p>
        <div class="progress" style="height: 6px;">
          <div class="progress-bar bg-success progress-bar-striped progress-bar-animated w-100"></div>
        </div>
      </div>

      <!-- Trạng thái: Lỗi -->
      <div v-else-if="errorMessage">
        <h4 class="fw-bold text-danger mb-2">Đăng Nhập Thất Bại</h4>
        <div class="alert alert-danger py-2.5 px-3 fs-7 text-start mb-3 rounded-3">
          <i class="bi bi-exclamation-triangle-fill me-1.5"></i>
          <span>{{ errorMessage }}</span>
        </div>

        <div class="d-flex flex-column gap-2">
          <router-link to="/login" class="btn btn-teal py-2 rounded-3 fw-bold fs-7 shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại trang Đăng nhập
          </router-link>
          <router-link to="/" class="btn btn-outline-secondary py-2 rounded-3 fs-7">
            Về Trang chủ
          </router-link>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const loading = ref(true)
const success = ref(false)
const errorMessage = ref('')
const loggedUserName = ref('')

onMounted(async () => {
  try {
    // 1. Kiểm tra nếu Google trả về lỗi trên URL
    if (route.query.error) {
      loading.value = false
      const err = route.query.error
      if (err === 'access_denied') {
        errorMessage.value = 'Bạn đã hủy yêu cầu đăng nhập bằng tài khoản Google.'
      } else if (err === 'invalid_state') {
        errorMessage.value = 'Phiên xác thực đã hết hạn hoặc không hợp lệ để đảm bảo bảo mật (CSRF). Vui lòng thử lại.'
      } else {
        errorMessage.value = `Lỗi từ Google: ${decodeURIComponent(err)}`
      }
      return
    }

    // 2. Trường hợp A: Backend redirect về kèm token và user (Web redirect flow)
    if (route.query.token) {
      const token = route.query.token
      let user = null

      if (route.query.user) {
        try {
          user = JSON.parse(atob(route.query.user))
        } catch (e) {
          console.warn('Không giải mã được user payload từ query:', e)
        }
      }

      // Lưu trữ phiên đăng nhập
      authStore.token = token
      localStorage.setItem('oxford_token', token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

      if (user) {
        authStore.user = user
        localStorage.setItem('oxford_user', JSON.stringify(user))
        loggedUserName.value = user.name || 'Học viên'
      }

      // Nạp thông tin tài khoản đầy đủ từ /api/auth/me
      await authStore.fetchMe()

      loading.value = false
      success.value = true

      setTimeout(() => {
        router.push('/')
      }, 1200)
      return
    }

    // 3. Trường hợp B: Frontend trực tiếp tiếp nhận code và state từ Google
    if (route.query.code) {
      const code = route.query.code
      const state = route.query.state || ''

      const res = await axios.post('/api/auth/google/callback', {
        code,
        state,
        redirect_uri: window.location.origin + '/auth/google/callback',
      })

      if (res.data.status === 'success' && res.data.data) {
        const { user, token } = res.data.data
        authStore.token = token
        authStore.user = user
        localStorage.setItem('oxford_token', token)
        localStorage.setItem('oxford_user', JSON.stringify(user))
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

        loggedUserName.value = user.name || 'Học viên'
        await authStore.fetchMe()

        loading.value = false
        success.value = true

        setTimeout(() => {
          router.push('/')
        }, 1200)
        return
      } else {
        loading.value = false
        errorMessage.value = res.data.message || 'Xác thực tài khoản Google không thành công.'
        return
      }
    }

    // 4. Nếu không có token hay code hợp lệ
    loading.value = false
    errorMessage.value = 'Không tìm thấy dữ liệu xác thực hợp lệ từ Google.'
  } catch (err) {
    loading.value = false
    console.error('Google Callback Error:', err)
    errorMessage.value = err.response?.data?.message || 'Có lỗi xảy ra trong quá trình xác thực với Google. Vui lòng thử lại.'
  }
})
</script>

<style scoped>
.max-w-md {
  max-w: 460px;
}
.bg-card-dark {
  background-color: #1e293b;
}
</style>
