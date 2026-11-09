<template>
  <div class="register-page-wrapper min-vh-100 d-flex flex-column justify-content-between" :class="authStore.themeMode === 'dark' ? 'bg-dark-fb text-white' : 'bg-light-fb text-dark'">
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
          <!-- LEFT COLUMN: High-Tech Educational Ecosystem Showcase (No fake testimonials) -->
          <div class="col-12 col-lg-6 col-xl-6 text-center text-lg-start mb-2 mb-lg-0">
            <div class="d-inline-flex align-items-center gap-2 mb-3">
              <div class="rounded-4 d-inline-flex align-items-center justify-content-center text-white fw-black shadow-sm" style="width: 52px; height: 52px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%); font-size: 26px;">
                Ox
              </div>
              <span class="fw-black text-teal display-6 text-uppercase" style="letter-spacing: -0.5px;">OxVocab<span class="text-success fs-4 align-top">™</span></span>
            </div>

            <h1 class="fw-black mb-3 lh-sm" :class="authStore.themeMode === 'dark' ? 'text-light' : 'text-dark-emphasis'" style="font-size: clamp(1.45rem, 2.3vw, 1.85rem);">
              Bắt đầu hành trình làm chủ <span class="text-teal">tiếng Anh thực chiến</span> ngay hôm nay.
            </h1>

            <p class="text-secondary fs-7 mb-4 d-none d-md-block" style="line-height: 1.6; max-width: 520px;">
              Tạo tài khoản miễn phí để lưu giữ tiến độ trọn đời, mở khóa bộ công cụ chấm điểm phát âm AI, 25 chủ đề lộ trình và kho từ vựng Oxford 3000.
            </p>

            <!-- HIGH-TECH TECH SHOWCASE CARD -->
            <div class="card border-0 rounded-4 p-3.5 p-md-4 shadow-sm mb-4 text-start position-relative overflow-hidden text-white" style="background: linear-gradient(145deg, #032b24 0%, #064e40 60%, #087F75 100%);">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(94, 234, 212, 0.18); color: #5eead4; font-size: 11px;">
                  <i class="bi bi-mortarboard-fill me-1"></i> ĐẶC QUYỀN TÀI KHOẢN MỚI
                </span>
                <span class="fs-8 fw-semibold text-white-50">
                  Kích hoạt tức thì
                </span>
              </div>

              <!-- 3 Core Features List -->
              <div class="d-flex flex-column gap-2.5 mb-3">
                <div class="p-2.5 rounded-3 d-flex align-items-center gap-3" style="background: rgba(0, 0, 0, 0.28); border: 1px solid rgba(255, 255, 255, 0.12);">
                  <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 36px; height: 36px; background-color: #00b074;">
                    <i class="bi bi-mic-fill fs-6"></i>
                  </div>
                  <div>
                    <div class="fw-bold fs-7 text-white">Chấm điểm phát âm AI qua Microphone</div>
                    <div class="text-white-50" style="font-size: 11.5px;">Phản hồi độ chính xác bản xứ, chỉnh từng âm vị IPA</div>
                  </div>
                </div>

                <div class="p-2.5 rounded-3 d-flex align-items-center gap-3" style="background: rgba(0, 0, 0, 0.28); border: 1px solid rgba(255, 255, 255, 0.12);">
                  <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 36px; height: 36px; background-color: #087F75;">
                    <i class="bi bi-diagram-3-fill fs-6"></i>
                  </div>
                  <div>
                    <div class="fw-bold fs-7 text-white">25 Chủ đề Thực chiến & 10 Chặng S-Curve</div>
                    <div class="text-white-50" style="font-size: 11.5px;">200 mục từ & 10 bài test phản xạ cho từng lĩnh vực</div>
                  </div>
                </div>

                <div class="p-2.5 rounded-3 d-flex align-items-center gap-3" style="background: rgba(0, 0, 0, 0.28); border: 1px solid rgba(255, 255, 255, 0.12);">
                  <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 36px; height: 36px; background-color: #0284c7;">
                    <i class="bi bi-cloud-arrow-up-fill fs-6"></i>
                  </div>
                  <div>
                    <div class="fw-bold fs-7 text-white">Đồng bộ đám mây & Theo dõi Streak</div>
                    <div class="text-white-50" style="font-size: 11.5px;">Không bao giờ mất chuỗi ngày học, bảng xếp hạng XP</div>
                  </div>
                </div>
              </div>

              <!-- Footer note -->
              <div class="d-flex align-items-center justify-content-between fs-8 text-white-50 pt-1 border-top border-white-10">
                <span>Miễn phí trọn đời tài khoản cơ bản</span>
                <span class="text-teal-light fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Không cần thẻ tín dụng</span>
              </div>
            </div>

            <!-- Feature Pills -->
            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
              <span class="badge rounded-pill px-3 py-1.5 fs-8 fw-semibold border" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary text-light' : 'bg-white border-light-subtle text-dark-emphasis shadow-2xs'">
                <i class="bi bi-lock-fill text-success me-1"></i> Bảo mật chuẩn SHA-256
              </span>
              <span class="badge rounded-pill px-3 py-1.5 fs-8 fw-semibold border" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary text-light' : 'bg-white border-light-subtle text-dark-emphasis shadow-2xs'">
                <i class="bi bi-check2-circle text-primary me-1"></i> Xác thực OTP bảo đảm
              </span>
            </div>
          </div>

          <!-- RIGHT COLUMN: Registration Form Card -->
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-sm-4.5" :class="authStore.themeMode === 'dark' ? 'bg-card-dark border border-secondary' : 'bg-white'">
              
              <!-- Header -->
              <div class="mb-3.5 pb-2 border-bottom">
                <h4 class="fw-black mb-1 text-teal">Đăng Ký Tài Khoản</h4>
                <span class="text-muted fs-8">
                  {{ step === 1 ? 'Điền thông tin để nhận mã xác thực OTP qua Email' : 'Nhập mã xác thực để kích hoạt tài khoản' }}
                </span>
              </div>

              <!-- Alert Notification -->
              <div v-if="alertMessage" class="alert py-2.5 px-3 fs-7 mb-3 d-flex align-items-center gap-2 rounded-3" :class="alertType === 'error' ? 'alert-danger' : 'alert-success'">
                <i class="bi fs-6 flex-shrink-0" :class="alertType === 'error' ? 'bi-exclamation-triangle-fill text-danger' : 'bi-check-circle-fill text-success'"></i>
                <div>{{ alertMessage }}</div>
              </div>

              <form @submit.prevent="step === 1 ? sendOtp() : submitRegister()" novalidate>
                <!-- STEP 1: Basic Information -->
                <div v-if="step === 1">
                  <!-- Name Input -->
                  <div class="mb-3">
                    <div class="form-floating">
                      <input
                        v-model="form.name"
                        type="text"
                        class="form-control rounded-3 fs-6"
                        id="regName"
                        placeholder="Nguyễn Văn A"
                        required
                      />
                      <label for="regName" class="text-muted">
                        <i class="bi bi-person me-1.5 text-teal"></i>Họ và tên của bạn
                      </label>
                    </div>
                  </div>

                  <!-- Email Input -->
                  <div class="mb-3">
                    <div class="form-floating">
                      <input
                        v-model="form.email"
                        type="email"
                        class="form-control rounded-3 fs-6"
                        id="regEmail"
                        placeholder="name@example.com"
                        required
                      />
                      <label for="regEmail" class="text-muted">
                        <i class="bi bi-envelope me-1.5 text-teal"></i>Địa chỉ Email
                      </label>
                    </div>
                  </div>

                  <!-- Password Input with Eye Toggle -->
                  <div class="mb-3">
                    <div class="position-relative">
                      <div class="form-floating">
                        <input
                          v-model="form.password"
                          :type="showPassword ? 'text' : 'password'"
                          class="form-control rounded-3 fs-6 pe-5"
                          id="regPassword"
                          placeholder="Mật khẩu"
                          minlength="6"
                          required
                        />
                        <label for="regPassword" class="text-muted">
                          <i class="bi bi-shield-lock me-1.5 text-teal"></i>Mật khẩu (tối thiểu 6 ký tự)
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

                  <!-- Confirm Password Input -->
                  <div class="mb-3">
                    <div class="position-relative">
                      <div class="form-floating">
                        <input
                          v-model="confirmPassword"
                          :type="showPassword ? 'text' : 'password'"
                          class="form-control rounded-3 fs-6 pe-5"
                          id="regConfirmPassword"
                          placeholder="Xác nhận mật khẩu"
                          required
                        />
                        <label for="regConfirmPassword" class="text-muted">
                          <i class="bi bi-shield-check me-1.5 text-teal"></i>Xác nhận lại mật khẩu
                        </label>
                      </div>
                    </div>
                    <div v-if="confirmPassword && confirmPassword === form.password" class="text-success fs-8 mt-1 d-flex align-items-center gap-1">
                      <i class="bi bi-check-circle-fill"></i> Mật khẩu xác nhận hoàn toàn khớp.
                    </div>
                    <div v-else-if="confirmPassword && confirmPassword !== form.password" class="text-danger fs-8 mt-1 d-flex align-items-center gap-1">
                      <i class="bi bi-x-circle-fill"></i> Mật khẩu xác nhận chưa khớp.
                    </div>
                  </div>

                  <!-- Submit Step 1 Button -->
                  <button
                    type="submit"
                    class="btn btn-teal w-100 py-2.5 fw-bold fs-6 shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2 rounded-3"
                    :disabled="isLoading || !form.name || !form.email || !form.password || (confirmPassword && confirmPassword !== form.password)"
                  >
                    <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
                    <span v-else>Tiếp tục nhận mã OTP xác thực →</span>
                  </button>

                  <!-- Google Sign Up Button -->
                  <div class="mb-3">
                    <GoogleLoginButton text="Đăng ký nhanh bằng Google" />
                  </div>
                </div>

                <!-- STEP 2: OTP Verification -->
                <div v-else>
                  <div class="alert alert-info py-2.5 px-3 fs-7 mb-3 rounded-3 d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle-fill fs-6 text-info flex-shrink-0 mt-0.5"></i>
                    <div>
                      Mã OTP gồm 6 chữ số đã được gửi đến: <strong class="text-dark-emphasis">{{ form.email }}</strong>. Vui lòng kiểm tra hộp thư đến (hoặc hòm thư Spam).
                    </div>
                  </div>

                  <div class="mb-4 text-center">
                    <label class="form-label fs-7 fw-bold mb-2">Nhập mã xác nhận OTP (6 số):</label>
                    <input
                      v-model="form.otp_code"
                      type="text"
                      class="form-control form-control-lg text-center font-monospace fw-black tracking-widest rounded-3 py-2.5"
                      placeholder="••••••"
                      maxlength="6"
                      required
                      style="font-size: 26px; letter-spacing: 8px;"
                    />
                  </div>

                  <button
                    type="submit"
                    class="btn btn-success w-100 py-2.5 fw-bold fs-6 shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2 rounded-3"
                    :disabled="isLoading || !form.otp_code || form.otp_code.length < 6"
                  >
                    <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
                    <span v-else>Kích hoạt & Bắt đầu học ngay 🚀</span>
                  </button>

                  <div class="text-center">
                    <button @click="step = 1" type="button" class="btn btn-link btn-sm text-secondary text-decoration-none">
                      ← Quay lại chỉnh sửa thông tin
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

                <!-- Link to Login -->
                <div class="text-center pt-1 fs-7 text-muted">
                  Bạn đã có tài khoản?
                  <router-link to="/login" class="fw-bold text-teal text-decoration-none ms-1">
                    Đăng nhập ngay
                  </router-link>
                </div>
              </form>
            </div>

            <!-- Under-card Caption -->
            <div class="text-center mt-3 fs-8 text-muted">
              <span>Bảo vệ quyền riêng tư 100% • </span>
              <router-link to="/" class="text-decoration-none text-teal fw-semibold">Điều khoản sử dụng</router-link>
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
import GoogleLoginButton from '@/components/GoogleLoginButton.vue'

const authStore = useAuthStore()
const router = useRouter()

const step = ref(1)
const isLoading = ref(false)
const alertMessage = ref('')
const alertType = ref('error')
const showPassword = ref(false)
const confirmPassword = ref('')

const form = ref({
  name: '',
  email: '',
  password: '',
  otp_code: '',
  avatar_url: 'https://api.dicebear.com/7.x/bottts/svg?seed=Learner',
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
    const res = await axios.post('/api/auth/register-send-otp', {
      name: form.value.name,
      email: form.value.email,
    })
    step.value = 2
    alertMessage.value = res.data.message || 'Mã xác nhận OTP đã được gửi đến email!'
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
