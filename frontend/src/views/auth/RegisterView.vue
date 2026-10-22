<template>
  <div class="min-vh-100 d-flex flex-column" :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-body-secondary text-dark'">
    <!-- Top Navigation Bar -->
    <header class="border-bottom py-2.5 px-3 px-md-4 shadow-sm" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'">
      <div class="container-fluid d-flex justify-content-between align-items-center max-w-6xl">
        <router-link to="/" class="d-flex align-items-center gap-2 text-decoration-none">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%);">
            Ox
          </div>
          <div class="d-flex flex-column">
            <span class="fw-black fs-6 tracking-tight text-teal">OXFORD 3000™</span>
            <span class="text-muted" style="font-size: 10px;">Hệ thống học tiếng Anh toàn diện</span>
          </div>
        </router-link>

        <router-link to="/" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 shadow-sm d-flex align-items-center gap-1.5 fs-7">
          <i class="bi bi-house-door"></i>
          <span>Về Trang chủ</span>
        </router-link>
      </div>
    </header>

    <!-- Main Content: Split Panel matching Image 2 (media_1792672234439_45c90ecf.png) -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center p-3 p-md-4 p-lg-5">
      <div class="card rounded-4 border-0 shadow-lg overflow-hidden w-100 max-w-5xl" style="min-height: 600px;">
        <div class="row g-0 h-100">
          <!-- LEFT COLUMN: Dark Green (#064e40) matching screenshot -->
          <div class="col-12 col-md-5 p-4 p-md-5 d-flex flex-column justify-content-between text-white position-relative" style="background: linear-gradient(160deg, #042f2e 0%, #064e40 60%, #087F75 100%);">
            <div>
              <div class="inline-flex items-center mb-4">
                <span class="badge rounded-pill px-3 py-1.5 fw-semibold border" style="background-color: rgba(255, 255, 255, 0.12); border-color: rgba(255, 255, 255, 0.2); font-size: 11px; color: #5eead4;">
                  ✨ HỌC TẬP CÓ ĐỊNH HƯỚNG
                </span>
              </div>

              <h2 class="display-6 fw-black leading-tight mb-3">
                Mỗi buổi học,<br/>
                <span style="color: #5eead4;">một bước chuyển mình.</span>
              </h2>

              <p class="fs-7 text-white-50 leading-relaxed mb-4">
                Đồng hành cùng lộ trình chuẩn Oxford 3000 từ vựng, theo dõi sát sao chế độ luyện tập và ngữ pháp khoa học.
              </p>
            </div>

            <!-- Testimonial card in left column -->
            <div class="p-3.5 rounded-3 border mt-4" style="background-color: rgba(0, 0, 0, 0.25); border-color: rgba(255, 255, 255, 0.12);">
              <div class="d-flex align-items-center gap-1 text-warning mb-2 fs-7">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                <span class="text-white fw-bold ms-1">4.9/5.0</span>
              </div>
              <p class="fs-8 text-white-75 mb-2 fst-italic">
                "Có lộ trình học chi tiết giúp tôi nắm chắc 3000 từ vựng và 12 thì ngữ pháp, làm bài kiểm tra trắc nghiệm phản xạ rất nhanh."
              </p>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-white text-dark rounded-pill px-2.5 py-1 fw-bold fs-8">
                  <i class="bi bi-person-check-fill text-success me-1"></i> Học viên khóa 3 tháng
                </span>
              </div>
            </div>
          </div>

          <!-- RIGHT COLUMN: Registration Form matching Image 2 -->
          <div class="col-12 col-md-7 p-4 p-md-5 d-flex flex-column justify-content-between" :class="authStore.themeMode === 'dark' ? 'bg-dark' : 'bg-white'">
            <div>
              <div class="mb-4">
                <h3 class="fw-black text-teal mb-1">Bắt đầu hành trình</h3>
                <p class="text-muted fs-7 mb-0">
                  Đăng ký tài khoản học viên để lưu giữ hồ sơ và bắt đầu lộ trình học tập.
                </p>
              </div>

              <!-- Alert Notification -->
              <div v-if="alertMessage" class="alert py-2.5 px-3 fs-7 mb-3 d-flex align-items-center gap-2 rounded-3" :class="alertType === 'error' ? 'alert-danger' : 'alert-success'">
                <i class="bi fs-6" :class="alertType === 'error' ? 'bi-exclamation-triangle-fill text-danger' : 'bi-check-circle-fill text-success'"></i>
                <div>{{ alertMessage }}</div>
              </div>

              <!-- Debug OTP Hint -->
              <div v-if="debugOtp" class="alert alert-info py-2 px-3 fs-8 mb-3 rounded-3">
                <i class="bi bi-info-circle me-1"></i>
                Mã OTP thử nghiệm: <strong>{{ debugOtp }}</strong> (hoặc nhập mã master <strong>123456</strong>).
              </div>

              <form @submit.prevent="step === 1 ? sendOtp() : submitRegister()">
                <!-- STEP 1: Basic Info -->
                <div v-if="step === 1">
                  <!-- Name -->
                  <div class="mb-3">
                    <label class="form-label fs-7 fw-bold mb-1">
                      Họ và tên học viên <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-body-tertiary border-end-0">
                        <i class="bi bi-person text-muted"></i>
                      </span>
                      <input
                        v-model="form.name"
                        type="text"
                        class="form-control border-start-0 py-2.5 fs-7"
                        placeholder="VD: Cường Phùng"
                        required
                      />
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="mb-3">
                    <label class="form-label fs-7 fw-bold mb-1">
                      Địa chỉ Email <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-body-tertiary border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                      </span>
                      <input
                        v-model="form.email"
                        type="email"
                        class="form-control border-start-0 py-2.5 fs-7"
                        placeholder="cuongphung1810@gmail.com"
                        required
                      />
                    </div>
                  </div>

                  <!-- Password -->
                  <div class="mb-2">
                    <label class="form-label fs-7 fw-bold mb-1">
                      Mật khẩu <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-body-tertiary border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                      </span>
                      <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="form-control border-start-0 border-end-0 py-2.5 fs-7"
                        placeholder="••••••••"
                        minlength="6"
                        required
                      />
                      <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="input-group-text bg-body-tertiary border-start-0 text-muted"
                      >
                        <i class="bi" :class="showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                      </button>
                    </div>
                  </div>

                  <!-- Password Strength Indicator -->
                  <div class="mb-3" v-if="form.password">
                    <div class="d-flex justify-content-between align-items-center fs-8 text-muted mb-1">
                      <span>Độ mạnh mật khẩu:</span>
                      <span class="fw-bold text-success">Khá an toàn</span>
                    </div>
                    <div class="progress" style="height: 4px;">
                      <div class="progress-bar bg-success" style="width: 80%;"></div>
                    </div>
                  </div>

                  <!-- Password Confirm -->
                  <div class="mb-2">
                    <label class="form-label fs-7 fw-bold mb-1">
                      Xác nhận lại mật khẩu <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-body-tertiary border-end-0">
                        <i class="bi bi-shield-check text-muted"></i>
                      </span>
                      <input
                        v-model="confirmPassword"
                        :type="showPassword ? 'text' : 'password'"
                        class="form-control border-start-0 py-2.5 fs-7"
                        placeholder="••••••••"
                        required
                      />
                    </div>
                    <div v-if="confirmPassword && confirmPassword === form.password" class="text-success fs-8 mt-1 d-flex align-items-center gap-1">
                      <i class="bi bi-check-circle-fill"></i> Mật khẩu xác nhận hoàn toàn khớp.
                    </div>
                    <div v-else-if="confirmPassword && confirmPassword !== form.password" class="text-danger fs-8 mt-1">
                      Mật khẩu xác nhận chưa khớp.
                    </div>
                  </div>

                  <!-- Show password toggle checkbox -->
                  <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" v-model="showPassword" id="showPwCheck" />
                    <label class="form-check-label fs-8 text-muted" for="showPwCheck">
                      {{ showPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu' }}
                    </label>
                  </div>

                  <button
                    type="submit"
                    class="btn btn-teal w-100 py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 mb-3"
                    :disabled="isLoading || (confirmPassword && confirmPassword !== form.password)"
                  >
                    <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
                    <span>Tạo tài khoản học viên →</span>
                  </button>
                </div>

                <!-- STEP 2: Enter OTP -->
                <div v-else>
                  <div class="alert alert-info py-2.5 px-3 fs-7 mb-3 rounded-3">
                    Mã OTP 6 số đã được gửi tới <strong>{{ form.email }}</strong>. Vui lòng nhập mã để hoàn tất.
                  </div>

                  <div class="mb-4 text-center">
                    <label class="form-label fs-7 fw-bold mb-2">Nhập mã xác nhận OTP:</label>
                    <input
                      v-model="form.otp_code"
                      type="text"
                      class="form-control form-control-lg text-center font-monospace fw-bold tracking-widest rounded-3"
                      placeholder="123456"
                      maxlength="6"
                      required
                      style="font-size: 24px; letter-spacing: 6px;"
                    />
                  </div>

                  <button
                    type="submit"
                    class="btn btn-teal w-100 py-2.5 fw-bold shadow-sm mb-3"
                    :disabled="isLoading || !form.otp_code"
                  >
                    <span v-if="isLoading" class="spinner-border spinner-border-sm me-1"></span>
                    Xác nhận & Kích hoạt tài khoản
                  </button>

                  <div class="text-center">
                    <button @click="step = 1" type="button" class="btn btn-link btn-sm text-secondary text-decoration-none">
                      ← Quay lại chỉnh sửa thông tin
                    </button>
                  </div>
                </div>

                <div class="text-center fs-7 text-muted mt-3">
                  Bạn đã có tài khoản?
                  <router-link to="/login" class="fw-bold text-teal text-decoration-none ms-1">
                    Đăng nhập ngay
                  </router-link>
                </div>
              </form>
            </div>

            <div class="pt-3 border-top mt-4 text-center text-muted fs-8">
              Oxford 3000™ • Đăng ký tài khoản an toàn & bảo mật
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import axios from 'axios'

const authStore = useAuthStore()
const router = useRouter()

const step = ref(1)
const isLoading = ref(false)
const alertMessage = ref('')
const alertType = ref('error')
const debugOtp = ref('')
const showPassword = ref(false)
const confirmPassword = ref('')

const form = ref({
  name: '',
  email: '',
  password: '',
  otp_code: '',
  avatar_url: 'https://api.dicebear.com/7.x/bottts/svg?seed=CuongPhung',
})

const sendOtp = async () => {
  if (confirmPassword.value && confirmPassword.value !== form.value.password) {
    alertMessage.value = 'Mật khẩu xác nhận không khớp!'
    alertType.value = 'error'
    return
  }

  isLoading.value = true
  alertMessage.value = ''
  try {
    const res = await axios.post('/api/auth/send-register-otp', { email: form.value.email })
    step.value = 2
    debugOtp.value = res.data.debug_otp || '123456'
    alertMessage.value = res.data.message || 'Mã xác nhận OTP đã được gửi!'
    alertType.value = 'success'
  } catch (err) {
    alertMessage.value = err.response?.data?.message || 'Không thể gửi mã OTP. Vui lòng kiểm tra lại email.'
    alertType.value = 'error'
  } finally {
    isLoading.value = false
  }
}

const submitRegister = async () => {
  isLoading.value = true
  alertMessage.value = ''
  const success = await authStore.register(form.value)
  isLoading.value = false
  if (success) {
    router.push('/daily')
  } else {
    alertMessage.value = authStore.error || 'Đăng ký thất bại. Mã OTP không đúng.'
    alertType.value = 'error'
  }
}
</script>
