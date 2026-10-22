<template>
  <div class="min-vh-100 d-flex flex-column" :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-body-tertiary text-dark'">
    <!-- Top Compact Header -->
    <header class="border-bottom py-2.5 px-3 px-md-4 shadow-sm" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'">
      <div class="container-fluid d-flex justify-content-between align-items-center max-w-5xl">
        <!-- Brand Logo -->
        <router-link to="/" class="d-flex align-items-center gap-2 text-decoration-none">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 36px; height: 36px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%);">
            Ox
          </div>
          <div class="d-flex flex-column">
            <span class="fw-black fs-6 tracking-tight text-teal text-uppercase" style="letter-spacing: 0.5px;">OXFORD 3000™</span>
            <span class="text-muted" style="font-size: 10px;">Hệ thống học tiếng Anh toàn diện</span>
          </div>
        </router-link>

        <div class="d-flex align-items-center gap-2">
          <!-- Dark / Light Theme Toggle -->
          <button
            @click="authStore.toggleTheme"
            class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
            style="width: 34px; height: 34px;"
            :title="authStore.themeMode === 'light' ? 'Chế độ Tối' : 'Chế độ Sáng'"
          >
            <i class="bi" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
          </button>

          <router-link to="/" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 shadow-sm d-flex align-items-center gap-1.5 fs-7">
            <i class="bi bi-house-door"></i>
            <span>Về Trang chủ</span>
          </router-link>
        </div>
      </div>
    </header>

    <!-- Main Content: Compact Card -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center p-3 p-md-4">
      <div class="card rounded-4 border-0 shadow-lg overflow-hidden w-100" style="max-width: 820px;">
        <div class="row g-0 align-items-stretch">
          <!-- LEFT COLUMN: Sleek Dark Forest Green Banner -->
          <div class="col-12 col-md-5 p-4 d-flex flex-column justify-content-between text-white" style="background: linear-gradient(160deg, #042f2e 0%, #064e40 60%, #087F75 100%);">
            <div>
              <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill mb-3 border" style="background-color: rgba(255, 255, 255, 0.12); border-color: rgba(255, 255, 255, 0.2); font-size: 11px; color: #5eead4;">
                <i class="bi bi-stars"></i>
                <span class="fw-bold">{{ isAdminPortal ? 'CỔNG QUẢN TRỊ ADMIN' : 'HỌC TẬP CÓ ĐỊNH HƯỚNG' }}</span>
              </div>

              <h3 class="fw-black mb-2" style="line-height: 1.25;">
                <template v-if="isAdminPortal">
                  Điều phối hệ thống,<br/>
                  <span style="color: #5eead4;">quản trị toàn diện.</span>
                </template>
                <template v-else>
                  Mỗi ngày học,<br/>
                  <span style="color: #5eead4;">một bước vươn tầm.</span>
                </template>
              </h3>

              <p class="fs-8 text-white-75 mb-3" style="line-height: 1.5;">
                {{ isAdminPortal
                  ? 'Quản trị viên toàn quyền giám sát dữ liệu từ vựng, quản lý 25 chủ đề và cấp quyền học viên.'
                  : 'Lộ trình chuẩn 3000 từ vựng Oxford, 25 chủ đề học thuật và ngữ pháp 12 thì mở khóa 100%.'
                }}
              </p>
            </div>

            <!-- Compact Rating Pill Card -->
            <div class="p-3 rounded-3 border" style="background-color: rgba(0, 0, 0, 0.22); border-color: rgba(255, 255, 255, 0.12);">
              <div class="d-flex align-items-center gap-1 text-warning mb-1 fs-8">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                <span class="text-white fw-bold ms-1">4.9/5.0</span>
              </div>
              <p class="fs-8 text-white-75 mb-2 fst-italic">
                "Học qua thẻ Flashcard và làm bài trắc nghiệm nhớ rất lâu, giao diện hiện đại và mượt mà."
              </p>
              <div class="d-flex align-items-center justify-content-between">
                <span class="badge bg-white text-dark rounded-pill px-2 py-0.5 fw-bold" style="font-size: 10px;">
                  Học viên tiêu biểu
                </span>
                <span class="text-white-50" style="font-size: 10px;">100% Mở khóa</span>
              </div>
            </div>
          </div>

          <!-- RIGHT COLUMN: Compact Login Form -->
          <div class="col-12 col-md-7 p-4 p-lg-4 d-flex flex-column justify-content-between" :class="authStore.themeMode === 'dark' ? 'bg-dark' : 'bg-white'">
            <div>
              <!-- Header & Switch Portal Button -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <h4 class="fw-black text-teal mb-0">
                    {{ isAdminPortal ? 'Đăng Nhập Admin' : 'Chào Mừng Trở Lại' }}
                  </h4>
                  <span class="text-muted fs-8">
                    {{ isAdminPortal ? 'Truy cập trung tâm điều hành' : 'Đăng nhập để lưu giữ hồ sơ học tập' }}
                  </span>
                </div>

                <!-- Toggle Portal Button -->
                <button
                  type="button"
                  @click="togglePortal"
                  class="btn btn-sm rounded-pill px-3 py-1 shadow-xs fw-semibold fs-8"
                  :class="isAdminPortal ? 'btn-outline-primary' : 'btn-outline-danger'"
                >
                  <i class="bi bi-arrow-left-right me-1"></i>
                  {{ isAdminPortal ? 'Về Cổng Học Viên' : 'Chuyển Cổng Admin' }}
                </button>
              </div>

              <!-- 1-Click Fast Fill Account Pill -->
              <div class="p-2.5 rounded-3 border mb-3 d-flex align-items-center justify-content-between" :class="isAdminPortal ? 'bg-danger-subtle border-danger-subtle' : 'bg-success-subtle border-success-subtle'">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge" :class="isAdminPortal ? 'bg-danger' : 'bg-teal'" style="font-size: 10px;">
                    1-CLICK
                  </span>
                  <span class="fs-8 fw-bold" :class="isAdminPortal ? 'text-danger-emphasis' : 'text-success-emphasis'">
                    {{ isAdminPortal ? 'Cường Phùng (Admin)' : 'Nguyễn Minh Anh (Học viên)' }}
                  </span>
                </div>
                <button
                  type="button"
                  @click="isAdminPortal ? fillCredentials('cuongphung1810@gmail.com', '123456') : fillCredentials('user1@gmail.com', '123456')"
                  class="btn btn-sm btn-light border py-0.5 px-2 fs-8 fw-semibold"
                >
                  Điền mẫu
                </button>
              </div>

              <!-- Error Alert -->
              <div v-if="authStore.error" class="alert alert-danger py-2 px-3 fs-8 mb-3 d-flex align-items-center gap-2 rounded-3">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                <div>{{ authStore.error }}</div>
              </div>

              <!-- Form -->
              <form @submit.prevent="handleLogin">
                <div class="mb-2.5">
                  <label class="form-label fs-8 fw-bold mb-1">
                    Địa chỉ Email <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-end-0 py-2">
                      <i class="bi bi-envelope text-muted fs-7"></i>
                    </span>
                    <input
                      v-model="email"
                      type="email"
                      class="form-control border-start-0 py-2 fs-7"
                      placeholder="vidu@oxford3000.com"
                      required
                    />
                  </div>
                </div>

                <div class="mb-3">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label fs-8 fw-bold mb-0">
                      Mật khẩu <span class="text-danger">*</span>
                    </label>
                    <router-link to="/forgot-password" class="fs-8 text-teal text-decoration-none fw-semibold">
                      Quên mật khẩu?
                    </router-link>
                  </div>
                  <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-end-0 py-2">
                      <i class="bi bi-lock text-muted fs-7"></i>
                    </span>
                    <input
                      v-model="password"
                      :type="showPassword ? 'text' : 'password'"
                      class="form-control border-start-0 border-end-0 py-2 fs-7"
                      placeholder="••••••••"
                      required
                    />
                    <button
                      type="button"
                      @click="showPassword = !showPassword"
                      class="input-group-text bg-body-tertiary border-start-0 text-muted"
                      title="Hiện hoặc ẩn mật khẩu"
                    >
                      <i class="bi" :class="showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                  </div>
                </div>

                <!-- Submit Button -->
                <button
                  type="submit"
                  class="btn w-100 py-2 fw-bold shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2 fs-7"
                  :class="isAdminPortal ? 'btn-danger' : 'btn-teal'"
                  :disabled="authStore.isLoading"
                >
                  <span v-if="authStore.isLoading" class="spinner-border spinner-border-sm"></span>
                  <span v-else>
                    {{ isAdminPortal ? 'Đăng Nhập Cổng Admin →' : 'Đăng Nhập Vào Học Ngay →' }}
                  </span>
                </button>

                <div class="text-center fs-8 text-muted">
                  Chưa có tài khoản học viên?
                  <router-link to="/register" class="fw-bold text-teal text-decoration-none ms-1">
                    Đăng ký tài khoản ngay
                  </router-link>
                </div>
              </form>
            </div>

            <!-- Footer Notes -->
            <div class="pt-2 border-top mt-3 text-center text-muted fs-8">
              Bảo mật mã hóa token • Điều hướng chính xác phân quyền
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

const isAdminPortal = ref(false)
const email = ref('user1@gmail.com')
const password = ref('123456')
const showPassword = ref(false)

const togglePortal = () => {
  isAdminPortal.value = !isAdminPortal.value
  if (isAdminPortal.value) {
    email.value = 'cuongphung1810@gmail.com'
    password.value = '123456'
  } else {
    email.value = 'user1@gmail.com'
    password.value = '123456'
  }
}

const fillCredentials = (em, pw) => {
  email.value = em
  password.value = pw
}

const handleLogin = async () => {
  const success = await authStore.login(email.value, password.value)
  if (success) {
    if (authStore.isAdmin || authStore.isModerator || authStore.user?.role === 'admin' || authStore.user?.email === 'cuongphung1810@gmail.com') {
      router.push('/admin')
    } else {
      router.push('/daily')
    }
  }
}

onMounted(() => {
  if (route.query.portal === 'admin') {
    isAdminPortal.value = true
    email.value = 'cuongphung1810@gmail.com'
    password.value = '123456'
  }
})
</script>

<style scoped>
.shadow-xs {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
</style>
