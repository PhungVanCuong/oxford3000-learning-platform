<template>
  <router-view />
  <!-- Hệ thống Toast Toàn Cục (Global Toast Notification System) -->
  <ToastContainer />
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useToast } from '@/composables/useToast'
import ToastContainer from '@/components/toast/ToastContainer.vue'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const handleGlobalAuthSync = async (payload) => {
  if (!payload || payload.type !== 'GOOGLE_AUTH_SUCCESS') return

  const { token, user } = payload
  if (token) {
    const isNewLogin = !authStore.isAuthenticated
    authStore.token = token
    localStorage.setItem('oxford_token', token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    if (user) {
      authStore.user = user
      localStorage.setItem('oxford_user', typeof user === 'string' ? user : JSON.stringify(user))
    }

    if (isNewLogin) {
      await authStore.fetchMe()
      toast.success(`Chào mừng ${authStore.userName || user?.name || 'bạn'} đã đăng nhập thành công!`, 'Google OAuth 2.0')
      
      // Nếu đang ở màn hình đăng nhập hoặc đăng ký, tự động chuyển về trang chủ
      if (window.location.pathname === '/login' || window.location.pathname === '/register') {
        router.push('/')
      }
    }
  }
}

onMounted(() => {
  authStore.initAuth()

  // 1. Toàn cục: Lắng nghe qua BroadcastChannel giữa các tab
  try {
    const channel = new BroadcastChannel('oxvocab_google_auth')
    channel.onmessage = (event) => {
      handleGlobalAuthSync(event.data)
    }
  } catch (e) {}

  // 2. Toàn cục: Lắng nghe qua sự kiện storage của trình duyệt
  window.addEventListener('storage', (e) => {
    if (e.key === 'oxford_oauth_sync' && e.newValue) {
      try {
        const data = JSON.parse(e.newValue)
        handleGlobalAuthSync(data)
      } catch (err) {}
    }
  })
})
</script>
