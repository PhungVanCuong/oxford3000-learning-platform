<template>
  <div class="container py-5 max-w-lg">
    <div class="card p-4 p-md-5 rounded-4 shadow-lg border bg-card-custom">
      <!-- Header -->
      <div class="text-center mb-4">
        <div class="rounded-3 d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm mb-3" style="width: 54px; height: 54px; background: linear-gradient(135deg, #085346 0%, #10b981 100%); font-size: 26px;">
          <i class="bi bi-key-fill"></i>
        </div>
        <h3 class="fw-black text-teal mb-1">Khôi Phục Mật Khẩu</h3>
        <p class="text-muted fs-7">Xác thực mã OTP gửi về Email để thiết lập mật khẩu mới</p>
      </div>

      <!-- Alert Notification -->
      <div v-if="alertMessage" class="alert py-2.5 px-3 fs-7 mb-3 rounded-3" :class="alertType === 'error' ? 'alert-danger' : 'alert-success'">
        <i class="bi me-1" :class="alertType === 'error' ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill'"></i>
        {{ alertMessage }}
      </div>

      <!-- Debug Test OTP Hint -->
      <div v-if="debugOtp" class="alert alert-info py-2 px-3 fs-8 mb-3 rounded-3">
        <i class="bi bi-info-circle me-1"></i>
        Mã OTP thử nghiệm hệ thống: <strong>{{ debugOtp }}</strong> (hoặc dùng mã master <strong>123456</strong>).
      </div>

      <!-- Forgot Password Form -->
      <form @submit.prevent="step === 1 ? sendOtp() : resetPassword()">
        <!-- STEP 1: Enter Email -->
        <div v-if="step === 1">
          <div class="mb-4">
            <label class="form-label fs-7 fw-bold mb-1">Nhập Email tài khoản của bạn</label>
            <input
              v-model="email"
              type="email"
              class="form-control rounded-3 py-2.5 fs-7"
              placeholder="name@example.com"
              required
            />
            <div class="form-text fs-8 text-muted">Chúng tôi sẽ gửi một mã OTP gồm 6 chữ số để xác thực danh tính.</div>
          </div>

          <button
            type="submit"
            class="btn btn-teal w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3"
            :disabled="isLoading"
          >
            <span v-if="isLoading" class="spinner-border spinner-border-sm me-1"></span>
            <span>Gửi mã OTP khôi phục qua Email →</span>
          </button>
        </div>

        <!-- STEP 2: Enter OTP & New Password -->
        <div v-else>
          <div class="alert alert-light border rounded-3 p-2.5 mb-3 text-center">
            <span class="fs-8 text-muted">Mã OTP đã gửi đến: </span>
            <strong class="text-teal">{{ email }}</strong>
          </div>

          <div class="mb-3">
            <label class="form-label fs-7 fw-bold mb-1">Mã xác thực OTP (6 chữ số)</label>
            <input
              v-model="otpCode"
              type="text"
              class="form-control text-center font-monospace fs-4 tracking-widest rounded-3 py-2 fw-bold"
              placeholder="123456"
              maxlength="6"
              required
            />
          </div>

          <div class="mb-3">
            <label class="form-label fs-7 fw-bold mb-1">Mật khẩu mới (Tối thiểu 6 ký tự)</label>
            <input
              v-model="password"
              type="password"
              class="form-control rounded-3 py-2 fs-7"
              placeholder="••••••••"
              minlength="6"
              required
            />
          </div>

          <div class="mb-4">
            <label class="form-label fs-7 fw-bold mb-1">Xác nhận mật khẩu mới</label>
            <input
              v-model="passwordConfirmation"
              type="password"
              class="form-control rounded-3 py-2 fs-7"
              placeholder="••••••••"
              minlength="6"
              required
            />
          </div>

          <div class="d-flex gap-2 mb-3">
            <button
              type="button"
              @click="step = 1"
              class="btn btn-outline-secondary rounded-pill py-2.5 px-3 fs-7"
            >
              ← Quay lại
            </button>
            <button
              type="submit"
              class="btn btn-success flex-grow-1 rounded-pill py-2.5 fw-bold shadow-sm"
              :disabled="isLoading"
            >
              <span v-if="isLoading" class="spinner-border spinner-border-sm me-1"></span>
              <span>Đặt lại Mật khẩu & Lưu 🔒</span>
            </button>
          </div>
        </div>

        <div class="text-center fs-7 text-muted mt-3 pt-3 border-top">
          Nhớ lại mật khẩu?
          <router-link to="/login" class="fw-bold text-teal text-decoration-none ms-1">
            Quay lại Đăng nhập
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const step = ref(1)
const isLoading = ref(false)
const alertMessage = ref('')
const alertType = ref('success')
const debugOtp = ref('')

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
    alertMessage.value = res.data.message
    if (res.data.debug_otp) {
      debugOtp.value = res.data.debug_otp
    }
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
