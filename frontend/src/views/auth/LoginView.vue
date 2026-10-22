<template>
  <div class="min-vh-100 d-flex flex-column" :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-body-secondary text-dark'">
    <!-- Top Navigation Bar for Auth -->
    <header class="border-bottom py-2.5 px-3 px-md-4 shadow-sm" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'">
      <div class="container-fluid d-flex justify-content-between align-items-center max-w-6xl">
        <!-- Logo - Clicking returns to Home -->
        <router-link to="/" class="d-flex align-items-center gap-2 text-decoration-none">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%);">
            Ox
          </div>
          <div class="d-flex flex-column">
            <span class="fw-black fs-6 tracking-tight text-teal">OXFORD 3000™</span>
            <span class="text-muted" style="font-size: 10px;">Hệ thống học tiếng Anh toàn diện</span>
          </div>
        </router-link>

        <div class="d-flex align-items-center gap-2">
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

    <!-- Main Content: Split Panel matching Image 2 (media_1792672234439_45c90ecf.png) -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center p-3 p-md-4 p-lg-5">
      <div class="card rounded-4 border-0 shadow-lg overflow-hidden w-100 max-w-5xl" style="min-height: 560px;">
        <div class="row g-0 h-100">
          <!-- LEFT COLUMN: Dark Forest Green Banner (#064e40) -->
          <div class="col-12 col-md-5 p-4 p-md-5 d-flex flex-column justify-content-between text-white position-relative" style="background: linear-gradient(160deg, #042f2e 0%, #064e40 60%, #087F75 100%);">
            <div>
              <!-- Pill Badge -->
              <div class="inline-flex items-center mb-4">
                <span class="badge rounded-pill px-3 py-1.5 fw-semibold border" style="background-color: rgba(255, 255, 255, 0.12); border-color: rgba(255, 255, 255, 0.2); font-size: 11px; color: #5eead4;">
                  ✨ {{ isAdminPortal ? 'CỔNG VẬN HÀNH QUẢN TRỊ' : 'HỌC TẬP CÓ ĐỊNH HƯỚNG' }}
                </span>
              </div>

              <!-- Main Big Heading with Teal Highlight -->
              <h2 class="display-6 fw-black leading-tight mb-3">
                <template v-if="isAdminPortal">
                  Điều phối hệ thống,<br/>
                  <span style="color: #5eead4;">chuẩn hóa lộ trình</span><br/>
                  học viên.
                </template>
                <template v-else>
                  Mỗi ngày học,<br/>
                  <span style="color: #5eead4;">một bước vươn tầm</span><br/>
                  thế giới.
                </template>
              </h2>

              <p class="fs-7 text-white-50 leading-relaxed mb-4">
                {{ isAdminPortal
                  ? 'Quản trị viên toàn quyền giám sát dữ liệu 3.351 từ vựng, cấp quyền học viên và theo dõi chỉ số rèn luyện.'
                  : 'Đồng hành cùng lộ trình chuẩn Oxford 3000 từ vựng, theo dõi sát sao chế độ luyện tập và ngữ pháp khoa học.'
                }}
              </p>
            </div>

            <!-- Testimonial / System Card in Left Banner -->
            <div class="p-3.5 rounded-3 border mt-4" style="background-color: rgba(0, 0, 0, 0.25); border-color: rgba(255, 255, 255, 0.12);">
              <div class="d-flex align-items-center gap-1 text-warning mb-2 fs-7">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                <span class="text-white fw-bold ms-1">4.9/5.0</span>
              </div>
              <p class="fs-8 text-white-75 mb-2 fst-italic">
                "Hệ thống học theo ngày và bài kiểm tra trắc nghiệm giúp tôi ghi nhớ 3000 từ vựng cực nhanh, tính năng AI tạo câu ví dụ rất tự nhiên."
              </p>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-white text-dark rounded-pill px-2.5 py-1 fw-bold fs-8">
                  <i class="bi bi-person-check-fill text-success me-1"></i> Học viên khóa 3 tháng
                </span>
                <span class="text-white-50 fs-8">• 100% Mở khóa</span>
              </div>
            </div>
          </div>

          <!-- RIGHT COLUMN: Clean Login Form -->
          <div class="col-12 col-md-7 p-4 p-md-5 d-flex flex-column justify-content-between" :class="authStore.themeMode === 'dark' ? 'bg-dark' : 'bg-white'">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                <div>
                  <h3 class="fw-black text-teal mb-1">
                    {{ isAdminPortal ? 'Đăng Nhập Quản Trị Viên' : 'Chào Mừng Trở Lại' }}
                  </h3>
                  <p class="text-muted fs-7 mb-0">
                    {{ isAdminPortal
                      ? 'Nhập tài khoản Quản trị để truy cập Bảng điều khiển Admin'
                      : 'Đăng nhập tài khoản học viên để tiếp tục lưu giữ hồ sơ & tiến độ'
                    }}
                  </p>
                </div>

                <!-- Span Toggle Portal Button (Requested by user!) -->
                <span
                  @click="togglePortal"
                  class="badge rounded-pill px-3 py-2 cursor-pointer shadow-sm transition-all text-decoration-none"
                  :class="isAdminPortal ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'"
                  style="cursor: pointer; font-size: 11px;"
                >
                  <i class="bi bi-arrow-left-right me-1"></i>
                  {{ isAdminPortal ? 'Chuyển sang Cổng Người học' : 'Chuyển sang Cổng Admin' }}
                </span>
              </div>

              <!-- 1-Click Fast Fill Accounts -->
              <div class="p-3 rounded-3 border mb-3" :class="isAdminPortal ? 'bg-danger-subtle border-danger-subtle' : 'bg-success-subtle border-success-subtle'">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-bold fs-8 text-uppercase" :class="isAdminPortal ? 'text-danger' : 'text-success'">
                    {{ isAdminPortal ? '⚡ Tài khoản Admin mẫu (1-Click):' : '⚡ Tài khoản Học viên mẫu (1-Click):' }}
                  </span>
                  <span class="badge" :class="isAdminPortal ? 'bg-danger' : 'bg-teal'">MK: 123456</span>
                </div>

                <div v-if="isAdminPortal" class="d-flex flex-column gap-1.5">
                  <button
                    @click="fillCredentials('cuongphung1810@gmail.com', '123456')"
                    type="button"
                    class="btn btn-sm btn-outline-danger text-start d-flex justify-content-between align-items-center rounded-3 px-3 py-1.5 fs-8"
                  >
                    <span><strong>Cường Phùng</strong> (cuongphung1810@gmail.com)</span>
                    <span class="badge bg-danger">Vào thẳng Admin</span>
                  </button>
                </div>

                <div v-else class="d-flex flex-column gap-1.5">
                  <button
                    @click="fillCredentials('user1@gmail.com', '123456')"
                    type="button"
                    class="btn btn-sm btn-outline-success text-start d-flex justify-content-between align-items-center rounded-3 px-3 py-1.5 fs-8"
                  >
                    <span><strong>Nguyễn Minh Anh</strong> (user1@gmail.com)</span>
                    <span class="badge bg-success">Học viên 1</span>
                  </button>
                </div>
              </div>

              <!-- Error Alert -->
              <div v-if="authStore.error" class="alert alert-danger py-2.5 px-3 fs-7 mb-3 d-flex align-items-center gap-2 rounded-3">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-6"></i>
                <div>{{ authStore.error }}</div>
              </div>

              <!-- Form -->
              <form @submit.prevent="handleLogin">
                <div class="mb-3">
                  <label class="form-label fs-7 fw-bold mb-1">
                    Địa chỉ Email <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-end-0">
                      <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input
                      v-model="email"
                      type="email"
                      class="form-control border-start-0 py-2.5 fs-7"
                      placeholder="vidu@oxford3000.com"
                      required
                    />
                  </div>
                </div>

                <div class="mb-3">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label fs-7 fw-bold mb-0">
                      Mật khẩu <span class="text-danger">*</span>
                    </label>
                    <router-link to="/forgot-password" class="fs-8 text-teal text-decoration-none fw-semibold">
                      Quên mật khẩu?
                    </router-link>
                  </div>
                  <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-end-0">
                      <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input
                      v-model="password"
                      :type="showPassword ? 'text' : 'password'"
                      class="form-control border-start-0 border-end-0 py-2.5 fs-7"
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
                  class="btn w-100 py-2.5 fw-bold shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2"
                  :class="isAdminPortal ? 'btn-danger' : 'btn-teal'"
                  :disabled="authStore.isLoading"
                  style="font-size: 15px;"
                >
                  <span v-if="authStore.isLoading" class="spinner-border spinner-border-sm"></span>
                  <span v-else>
                    {{ isAdminPortal ? 'Đăng Nhập Cổng Admin →' : 'Đăng Nhập Vào Học Ngay →' }}
                  </span>
                </button>

                <div class="text-center fs-7 text-muted">
                  Chưa có tài khoản học viên?
                  <router-link to="/register" class="fw-bold text-teal text-decoration-none ms-1">
                    Đăng ký tài khoản ngay
                  </router-link>
                </div>
              </form>
            </div>

            <!-- Footer Notes -->
            <div class="pt-3 border-top mt-4 text-center text-muted fs-8">
              Hệ thống mã hóa bảo mật token • Tự động điều hướng đúng vai trò tài khoản
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
    // Check if the authenticated account has admin privileges
    if (authStore.isAdmin || authStore.isModerator || authStore.user?.role === 'admin' || authStore.user?.email === 'cuongphung1810@gmail.com') {
      // ADMIN ENTERS ADMIN INTERFACE, NEVER THE LEARNER INTERFACE!
      router.push('/admin')
    } else {
      // Standard learner enters daily study or home
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
