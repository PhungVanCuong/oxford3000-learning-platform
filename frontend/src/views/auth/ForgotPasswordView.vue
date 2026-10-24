<template>
  <div class="forgot-page-wrapper min-vh-100 d-flex flex-column justify-content-between" :class="authStore.themeMode === 'dark' ? 'bg-dark-fb text-white' : 'bg-light-fb text-dark'">
    <!-- Top Bar with Brand Link & Theme Toggle -->
    <header class="py-2.5 px-3 px-md-4">
      <div class="container-fluid d-flex justify-content-between align-items-center max-w-6xl">
        <router-link to="/" class="d-flex align-items-center gap-2 text-decoration-none">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-black shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%); font-size: 18px;">
            Ox
          </div>
          <span class="fw-black fs-5 tracking-tight text-teal text-uppercase" style="letter-spacing: 0.5px;">OxVocab™</span>
        </router-link>

        <div class="d-flex align-items-center gap-2">
          <!-- Dark / Light Theme Toggle -->
          <button
            @click="authStore.toggleTheme"
            class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
            style="width: 36px; height: 36px;"
            :title="authStore.themeMode === 'light' ? 'Chế độ Tối' : 'Chế độ Sáng'"
            tabindex="-1"
            type="button"
          >
            <i class="bi" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
          </button>

          <router-link to="/" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 shadow-sm d-flex align-items-center gap-1.5 fs-7" tabindex="-1">
            <i class="bi bi-house-door"></i>
            <span>Về Trang chủ</span>
          </router-link>
        </div>
      </div>
    </header>

    <!-- Main Section: Modern Split Viewport Layout -->
    <main class="flex-grow-1 d-flex align-items-center py-3 py-lg-4">
      <div class="container max-w-6xl">
        <div class="row align-items-center justify-content-center g-4 g-lg-5">
          <!-- LEFT COLUMN: Security & Account Recovery Hero -->
          <div class="col-12 col-lg-6 col-xl-6 text-center text-lg-start mb-2 mb-lg-0">
            <div class="d-inline-flex align-items-center gap-2 mb-3">
              <div class="rounded-4 d-inline-flex align-items-center justify-content-center text-white fw-black shadow-sm" style="width: 52px; height: 52px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%); font-size: 26px;">
                Ox
              </div>
              <span class="fw-black text-teal display-6 text-uppercase" style="letter-spacing: -0.5px;">OxVocab<span class="text-success fs-4 align-top">™</span></span>
            </div>

            <h1 class="fw-black mb-3 lh-sm" :class="authStore.themeMode === 'dark' ? 'text-light' : 'text-dark-emphasis'" style="font-size: clamp(1.45rem, 2.3vw, 1.85rem);">
              Bảo mật tài khoản & <span class="text-teal">khôi phục mật khẩu</span> nhanh chóng.
            </h1>

            <p class="text-secondary fs-7 mb-4 d-none d-md-block" style="line-height: 1.6; max-width: 520px;">
              Hệ thống xác thực mã OTP 2 lớp an toàn tuyệt đối. Mọi dữ liệu học tập, số từ vựng đã nhớ và điểm tích lũy XP của bạn luôn được bảo toàn nguyên vẹn.
            </p>

            <!-- HIGH-TECH SECURITY CARD -->
            <div class="card border-0 rounded-4 p-3.5 p-md-4 shadow-sm mb-4 text-start position-relative overflow-hidden text-white" style="background: linear-gradient(145deg, #032b24 0%, #064e40 60%, #087F75 100%);">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(94, 234, 212, 0.18); color: #5eead4; font-size: 11px;">
                  <i class="bi bi-shield-lock-fill me-1"></i> BẢO MẬT & XÁC THỰC OTP
                </span>
                <span class="fs-8 fw-semibold text-white-50">
                  Mã hóa SHA-256
                </span>
              </div>

              <!-- 2-Step Process Visual -->
              <div class="d-flex flex-column gap-2.5 mb-3">
                <div class="p-2.5 rounded-3 d-flex align-items-center gap-3" style="background: rgba(0, 0, 0, 0.28); border: 1px solid rgba(255, 255, 255, 0.12);">
                  <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width: 34px; height: 34px; background-color: #00b074;">
                    1
                  </div>
                  <div>
                    <div class="fw-bold fs-7 text-white">Gửi mã OTP qua Email đăng ký</div>
                    <div class="text-white-50" style="font-size: 11.5px;">Mã xác thực có hiệu lực trong vòng 10 phút</div>
                  </div>
                </div>

                <div class="p-2.5 rounded-3 d-flex align-items-center gap-3" style="background: rgba(0, 0, 0, 0.28); border: 1px solid rgba(255, 255, 255, 0.12);">
                  <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width: 34px; height: 34px; background-color: #087F75;">
                    2
                  </div>
                  <div>
                    <div class="fw-bold fs-7 text-white">Thiết lập mật khẩu mới & Đăng nhập</div>
                    <div class="text-white-50" style="font-size: 11.5px;">Tự động cập nhật mật khẩu mới trên toàn hệ thống</div>
                  </div>
                </div>
              </div>

              <!-- Footer Guarantee -->
              <div class="d-flex align-items-center justify-content-between fs-8 text-white-50 pt-1 border-top border-white-10">
                <span>Dữ liệu học tập được bảo toàn 100%</span>
                <span class="text-teal-light fw-bold"><i class="bi bi-check2-all me-1"></i> Không mất streak</span>
              </div>
            </div>

            <!-- Feature Badges -->
            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
              <span class="badge rounded-pill px-3 py-1.5 fs-8 fw-semibold border" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary text-light' : 'bg-white border-light-subtle text-dark-emphasis shadow-2xs'">
                <i class="bi bi-envelope-check-fill text-success me-1"></i> Email kích hoạt tức thời
              </span>
              <span class="badge rounded-pill px-3 py-1.5 fs-8 fw-semibold border" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary text-light' : 'bg-white border-light-subtle text-dark-emphasis shadow-2xs'">
                <i class="bi bi-key-fill text-warning me-1"></i> Mã hóa an toàn tuyệt đối
              </span>
            </div>
          </div>

          <!-- RIGHT COLUMN: Forgot Password Card -->
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-sm-4.5" :class="authStore.themeMode === 'dark' ? 'bg-card-dark border border-secondary' : 'bg-white'">
              
              <!-- Header -->
              <div class="mb-3.5 pb-2 border-bottom">
                <h4 class="fw-black mb-1 text-teal">Khôi Phục Mật Khẩu</h4>
                <span class="text-muted fs-8">
                  {{ step === 1 ? 'Nhập Email tài khoản để nhận mã OTP xác thực' : 'Nhập mã OTP và mật khẩu mới' }}
                </span>
              </div>

              <!-- Alert Notification -->
              <div v-if="alertMessage" class="alert py-2.5 px-3 fs-7 mb-3 d-flex align-items-center gap-2 rounded-3" :class="alertType === 'error' ? 'alert-danger' : 'alert-success'">
                <i class="bi fs-6 flex-shrink-0" :class="alertType === 'error' ? 'bi-exclamation-triangle-fill text-danger' : 'bi-check-circle-fill text-success'"></i>
                <div>{{ alertMessage }}</div>
              </div>

              <form @submit.prevent="step === 1 ? sendOtp() : resetPassword()" novalidate>
                <!-- STEP 1: Enter Email -->
                <div v-if="step === 1">
                  <div class="mb-3">
                    <div class="form-floating">
                      <input
                        v-model="email"
                        type="email"
                        class="form-control rounded-3 fs-6"
                        id="forgotEmail"
                        placeholder="name@example.com"
                        required
                      />
                      <label for="forgotEmail" class="text-muted">
                        <i class="bi bi-envelope me-1.5 text-teal"></i>Email tài khoản của bạn
                      </label>
                    </div>
                    <div class="form-text fs-8 text-muted mt-1.5">
                      Hệ thống sẽ gửi một mã OTP gồm 6 chữ số đến hộp thư của bạn.
                    </div>
                  </div>

                  <button
                    type="submit"
                    class="btn btn-teal w-100 py-2.5 fw-bold fs-6 shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2 rounded-3"
                    :disabled="isLoading || !email"
                  >
                    <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
                    <span v-else>Gửi mã OTP khôi phục qua Email →</span>
                  </button>
                </div>

                <!-- STEP 2: Enter OTP & New Password -->
                <div v-else>
                  <div class="alert alert-info py-2.5 px-3 fs-7 mb-3 rounded-3 d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle-fill fs-6 text-info flex-shrink-0 mt-0.5"></i>
                    <div>
                      Mã OTP đã gửi đến: <strong class="text-dark-emphasis">{{ email }}</strong>. Vui lòng kiểm tra hộp thư đến (hoặc hòm thư Spam).
                    </div>
                  </div>

                  <!-- OTP Input -->
                  <div class="mb-3 text-center">
                    <label class="form-label fs-7 fw-bold mb-1.5">Nhập mã xác nhận OTP (6 số):</label>
                    <input
                      v-model="otpCode"
                      type="text"
                      class="form-control form-control-lg text-center font-monospace fw-black tracking-widest rounded-3 py-2"
                      placeholder="••••••"
                      maxlength="6"
                      required
                      style="font-size: 24px; letter-spacing: 6px;"
                    />
                  </div>

                  <!-- New Password Input -->
                  <div class="mb-3">
                    <div class="position-relative">
                      <div class="form-floating">
                        <input
                          v-model="password"
                          :type="showPassword ? 'text' : 'password'"
                          class="form-control rounded-3 fs-6 pe-5"
                          id="forgotNewPassword"
                          placeholder="Mật khẩu mới"
                          minlength="6"
                          required
                        />
                        <label for="forgotNewPassword" class="text-muted">
                          <i class="bi bi-shield-lock me-1.5 text-teal"></i>Mật khẩu mới (tối thiểu 6 ký tự)
                        </label>
                      </div>

                      <button
                        type="button"
                        tabindex="-1"
                        @click="showPassword = !showPassword"
                        class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-1.5 border-0 text-secondary password-eye-btn"
                        :title="showPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                      >
                        <i class="bi fs-5" :class="showPassword ? 'bi-eye-slash-fill text-teal' : 'bi-eye-fill'"></i>
                      </button>
                    </div>
                  </div>

                  <!-- Confirm New Password Input -->
                  <div class="mb-3">
                    <div class="form-floating">
                      <input
                        v-model="passwordConfirmation"
                        :type="showPassword ? 'text' : 'password'"
                        class="form-control rounded-3 fs-6 pe-5"
                        id="forgotConfirmPassword"
                        placeholder="Xác nhận mật khẩu mới"
                        required
                      />
                      <label for="forgotConfirmPassword" class="text-muted">
                        <i class="bi bi-shield-check me-1.5 text-teal"></i>Xác nhận lại mật khẩu mới
                      </label>
                    </div>
                    <div v-if="passwordConfirmation && passwordConfirmation === password" class="text-success fs-8 mt-1 d-flex align-items-center gap-1">
                      <i class="bi bi-check-circle-fill"></i> Mật khẩu xác nhận hoàn toàn khớp.
                    </div>
                    <div v-else-if="passwordConfirmation && passwordConfirmation !== password" class="text-danger fs-8 mt-1 d-flex align-items-center gap-1">
                      <i class="bi bi-x-circle-fill"></i> Mật khẩu xác nhận chưa khớp.
                    </div>
                  </div>

                  <button
                    type="submit"
                    class="btn btn-success w-100 py-2.5 fw-bold fs-6 shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2 rounded-3"
                    :disabled="isLoading || !otpCode || otpCode.length < 6 || !password || (passwordConfirmation && passwordConfirmation !== password)"
                  >
                    <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
                    <span v-else>Đặt lại Mật khẩu & Lưu 🔒</span>
                  </button>

                  <div class="text-center">
                    <button @click="step = 1" type="button" class="btn btn-link btn-sm text-secondary text-decoration-none">
                      ← Nhập lại địa chỉ Email khác
                    </button>
                  </div>
                </div>

                <!-- Divider -->
                <div class="position-relative text-center my-3">
                  <hr class="text-secondary-subtle m-0">
                  <span class="position-absolute top-50 start-50 translate-middle px-3 fs-8 text-muted" :class="authStore.themeMode === 'dark' ? 'bg-card-dark' : 'bg-white'">
                    hoặc
                  </span>
                </div>

                <!-- Back to Login Link -->
                <div class="text-center pt-1 fs-7 text-muted">
                  Bạn đã nhớ lại mật khẩu?
                  <router-link to="/login" class="fw-bold text-teal text-decoration-none ms-1">
                    Quay lại Đăng nhập
                  </router-link>
                </div>
              </form>
            </div>

            <!-- Under-card Caption -->
            <div class="text-center mt-3 fs-8 text-muted">
              <span>Hỗ trợ kỹ thuật 24/7 • </span>
              <router-link to="/" class="text-decoration-none text-teal fw-semibold">Trung tâm trợ giúp</router-link>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Clean Footer -->
    <footer class="py-3 px-3 border-top mt-auto fs-8 text-muted" :class="authStore.themeMode === 'dark' ? 'border-secondary bg-dark-fb' : 'border-light-subtle bg-white'">
      <div class="container max-w-6xl d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="d-flex flex-wrap gap-3 justify-content-center">
          <span class="fw-semibold text-secondary">Tiếng Việt</span>
          <span>•</span>
          <span class="text-muted">English (US)</span>
          <span>•</span>
          <router-link to="/dictionary" class="text-muted text-decoration-none hover-teal">Tra từ vựng</router-link>
          <span>•</span>
          <router-link to="/curriculum" class="text-muted text-decoration-none hover-teal">Lộ trình 25 Chủ đề</router-link>
        </div>

        <div>
          <span>OxVocab™ © 2026 • Nền tảng học thông minh</span>
        </div>
      </div>
    </footer>
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
const alertType = ref('success')
const showPassword = ref(false)

const email = ref('')
const otpCode = ref('')
const password = ref('')
const passwordConfirmation = ref('')

const sendOtp = async () => {
  isLoading.value = true
  alertMessage.value = ''
  try {
    const res = await axios.post('/api/auth/forgot-password-send-otp', {
      email: email.value,
    })
    step.value = 2
    alertType.value = 'success'
    alertMessage.value = res.data.message || 'Mã OTP đã được gửi đến email!'
  } catch (err) {
    alertType.value = 'error'
    alertMessage.value = err.response?.data?.message || 'Email này không tồn tại trong hệ thống.'
  } finally {
    isLoading.value = false
  }
}

const resetPassword = async () => {
  if (password.value !== passwordConfirmation.value) {
    alertType.value = 'error'
    alertMessage.value = 'Xác nhận mật khẩu không khớp. Vui lòng nhập lại.'
    return
  }

  isLoading.value = true
  alertMessage.value = ''
  try {
    const res = await axios.post('/api/auth/reset-password-with-otp', {
      email: email.value,
      otp_code: otpCode.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    })
    alert('Đặt lại mật khẩu thành công! Hãy đăng nhập bằng mật khẩu mới.')
    router.push('/login')
  } catch (err) {
    alertType.value = 'error'
    alertMessage.value = err.response?.data?.message || 'Mã OTP không chính xác hoặc đã hết hạn.'
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
.bg-light-fb {
  background-color: #f0f2f5;
}

.bg-dark-fb {
  background-color: #18191a;
}

.bg-card-dark {
  background-color: #242526;
}

.text-teal-light {
  color: #5eead4;
}

.shadow-2xs {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.password-eye-btn {
  background: transparent;
  outline: none;
  box-shadow: none;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.password-eye-btn:hover {
  background-color: rgba(0, 0, 0, 0.05);
  color: #087F75 !important;
}

.hover-teal:hover {
  color: #087F75 !important;
}
</style>
