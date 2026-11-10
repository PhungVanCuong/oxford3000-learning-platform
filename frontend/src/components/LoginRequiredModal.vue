<template>
  <div
    v-if="authStore.showLoginModal"
    class="modal fade show d-block"
    style="background-color: rgba(0, 0, 0, 0.65); z-index: 1060;"
    tabindex="-1"
    @click.self="authStore.closeLoginModal"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 border-0 shadow-lg bg-card-custom overflow-hidden">
        <div class="modal-header border-bottom py-3" style="background: linear-gradient(135deg, #064e40 0%, #0d7362 100%); color: #fff;">
          <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
            <i class="bi bi-shield-lock-fill text-warning"></i> Yêu Cầu Đăng Nhập
          </h5>
          <button @click="authStore.closeLoginModal" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4 text-center">
          <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3 bg-success-subtle text-success fs-1">
            <i class="bi bi-person-lock"></i>
          </div>

          <h5 class="fw-bold text-teal mb-2">Đăng nhập để lưu lại kết quả!</h5>
          <p class="text-muted fs-7 mb-4 leading-relaxed">
            {{ authStore.loginModalMessage || 'Bạn cần đăng nhập để hệ thống ghi nhận từ vựng đã nhớ, tính điểm kinh nghiệm XP, đồng bộ chuỗi ngày học và lưu kết quả bài kiểm tra!' }}
          </p>

          <div class="d-flex flex-column gap-2 mb-3">
            <GoogleLoginButton text="Đăng nhập nhanh bằng Google" customClass="rounded-pill" />

            <div class="position-relative text-center my-1">
              <hr class="text-secondary-subtle m-0">
              <span class="position-absolute top-50 start-50 translate-middle px-2 fs-8 text-muted bg-card-custom">
                hoặc tài khoản mật khẩu
              </span>
            </div>

            <router-link
              to="/login"
              @click="authStore.closeLoginModal"
              class="btn btn-teal rounded-pill py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
            >
              <i class="bi bi-box-arrow-in-right"></i>
              <span>Đăng nhập mật khẩu</span>
            </router-link>

            <router-link
              to="/register"
              @click="authStore.closeLoginModal"
              class="btn btn-outline-teal rounded-pill py-2 fw-semibold fs-7 d-flex align-items-center justify-content-center gap-2"
            >
              <i class="bi bi-person-plus"></i>
              <span>Tạo tài khoản mới</span>
            </router-link>
          </div>
        </div>

        <div class="modal-footer border-top py-2 bg-body-tertiary justify-content-center">
          <button @click="authStore.closeLoginModal" class="btn btn-link text-muted fs-8 text-decoration-none">
            Tiếp tục xem thử mà không lưu tiến độ
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/authStore'
import GoogleLoginButton from '@/components/GoogleLoginButton.vue'

const authStore = useAuthStore()
</script>
