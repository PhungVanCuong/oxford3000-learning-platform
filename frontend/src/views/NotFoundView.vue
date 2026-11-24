<template>
  <div class="container py-5 min-vh-75 d-flex align-items-center justify-content-center">
    <div class="card p-4 p-md-5 rounded-4 shadow-lg border text-center max-w-2xl w-100 not-found-card"
         :class="isDarkMode ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'">
      
      <!-- Icon Biển Báo Lỗi 404 Cảnh Báo -->
      <div class="mx-auto mb-3 position-relative d-inline-block">
        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
             style="width: 100px; height: 100px; background: rgba(239, 68, 68, 0.1); border: 2px dashed rgba(239, 68, 68, 0.4);">
          <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
        </div>
        <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-black fs-8 position-absolute bottom-0 start-50 translate-middle-x shadow-xs">
          404 ERROR
        </span>
      </div>

      <!-- Tiêu đề & Thông báo lỗi đường dẫn -->
      <h1 class="display-6 fw-black mb-2 text-danger">
        Đường Dẫn Bị Lỗi Hoặc Không Tồn Tại!
      </h1>
      <p class="text-muted fs-6 mb-3">
        Trang bạn đang tìm kiếm có thể đã bị đổi tên, xóa bỏ hoặc đường dẫn bạn nhập chưa chính xác trên hệ thống <strong>OxVocab</strong>.
      </p>

      <!-- Đường dẫn đã nhập -->
      <div class="p-3 rounded-3 mb-4 text-start font-monospace fs-8 border"
           :class="isDarkMode ? 'bg-black/30 border-secondary text-warning' : 'bg-body-tertiary border-light-subtle text-danger'">
        <div class="d-flex align-items-center gap-2 mb-1">
          <i class="bi bi-link-45deg fs-6"></i>
          <span class="fw-bold text-muted text-uppercase fs-9">Đường dẫn không hợp lệ:</span>
        </div>
        <div class="text-break fw-semibold">{{ currentUrl }}</div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-2.5">
        <!-- Nút chính: Quay lại trang chủ theo yêu cầu của bạn -->
        <router-link
          to="/"
          class="btn btn-teal text-white rounded-pill px-4 py-2.5 fw-bold fs-7 shadow-sm d-inline-flex align-items-center gap-2 w-100 w-sm-auto justify-content-center transition-all hover-scale"
        >
          <i class="bi bi-house-door-fill fs-6"></i>
          <span>Quay lại trang chủ</span>
        </router-link>

        <!-- Nút phụ: Quay lại trang trước -->
        <button
          @click="goBack"
          class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-semibold fs-7 d-inline-flex align-items-center gap-2 w-100 w-sm-auto justify-content-center"
          type="button"
        >
          <i class="bi bi-arrow-left"></i>
          <span>Trang trước đó</span>
        </button>
      </div>

      <!-- Gợi ý nhanh các chuyên mục phổ biến -->
      <div class="mt-4 pt-3 border-top" :class="isDarkMode ? 'border-secondary' : 'border-light-subtle'">
        <div class="fs-9 text-muted fw-bold text-uppercase mb-2">Hoặc truy cập nhanh các mục học tập:</div>
        <div class="d-flex flex-wrap justify-content-center gap-1.5">
          <router-link to="/curriculum" class="btn btn-xs rounded-pill px-2.5 py-1 border text-decoration-none"
                       :class="isDarkMode ? 'bg-dark text-light border-secondary' : 'bg-light text-dark border-light-subtle'">
            📖 Lộ trình 25 chủ đề
          </router-link>
          <router-link to="/vocabulary-notebook" class="btn btn-xs rounded-pill px-2.5 py-1 border text-decoration-none"
                       :class="isDarkMode ? 'bg-dark text-light border-secondary' : 'bg-light text-dark border-light-subtle'">
            🔖 Sổ tay từ vựng
          </router-link>
          <router-link to="/phrasal-verbs" class="btn btn-xs rounded-pill px-2.5 py-1 border text-decoration-none"
                       :class="isDarkMode ? 'bg-dark text-light border-secondary' : 'bg-light text-dark border-light-subtle'">
            💬 Cụm từ
          </router-link>
          <router-link to="/review" class="btn btn-xs rounded-pill px-2.5 py-1 border text-decoration-none"
                       :class="isDarkMode ? 'bg-dark text-light border-secondary' : 'bg-light text-dark border-light-subtle'">
            🔄 Bộ thẻ ôn tập
          </router-link>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const isDarkMode = computed(() => authStore.themeMode === 'dark')
const currentUrl = computed(() => typeof window !== 'undefined' ? window.location.href : route.fullPath)

const goBack = () => {
  if (window.history.length > 1) {
    router.back()
  } else {
    router.push('/')
  }
}
</script>

<style scoped>
.not-found-card {
  max-width: 600px;
  animation: fadeIn 0.3s ease-out;
}

.hover-scale:hover {
  transform: translateY(-1.5px);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
