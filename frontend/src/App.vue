<template>
  <router-view />
  <!-- Hệ thống Toast Toàn Cục (Global Toast Notification System) -->
  <ToastContainer />
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import ToastContainer from '@/components/toast/ToastContainer.vue'

const authStore = useAuthStore()

onMounted(() => {
  authStore.initAuth()
})
</script>
