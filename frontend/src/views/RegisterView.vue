<template>
  <div class="container py-5 max-w-lg">
    <div class="card p-4 p-md-5 rounded-4 shadow-lg border bg-card-custom">
      <!-- Header -->
      <div class="text-center mb-4">
        <div class="rounded-3 d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm mb-3" style="width: 54px; height: 54px; background: linear-gradient(135deg, #085346 0%, #10b981 100%); font-size: 26px;">
          Ox
        </div>
        <h3 class="fw-black text-teal mb-1">Đăng Ký Tài Khoản Học Viên</h3>
        <p class="text-muted fs-7">Nhận mã OTP xác thực qua Email và bắt đầu lộ trình 3000 từ vựng</p>
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

      <!-- Registration Form -->
      <form @submit.prevent="step === 1 ? sendOtp() : submitRegister()">
        <!-- STEP 1: Basic Information -->
        <div v-if="step === 1">
          <!-- Full Name -->
          <div class="mb-3">
            <label class="form-label fs-7 fw-bold mb-1">Họ và tên học viên</label>
            <input
              v-model="form.name"
              type="text"
              class="form-control rounded-3 py-2.5 fs-7"
              placeholder="VD: Nguyễn Văn Hoàng"
              required
            />
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label class="form-label fs-7 fw-bold mb-1">Email (Nhận mã OTP)</label>
            <input
              v-model="form.email"
              type="email"
              class="form-control rounded-3 py-2.5 fs-7"
              placeholder="name@example.com"
              required
            />
            <div class="form-text fs-8 text-muted">Hệ thống sẽ gửi mã xác thực gồm 6 chữ số đến email này.</div>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label class="form-label fs-7 fw-bold mb-1">Mật khẩu (Tối thiểu 6 ký tự)</label>
            <input
              v-model="form.password"
              type="password"
              class="form-control rounded-3 py-2.5 fs-7"
              placeholder="••••••••"
              minlength="6"
              required
            />
          </div>

          <!-- Birth Date -->
          <div class="mb-4">
            <label class="form-label fs-7 fw-bold mb-1">Ngày sinh</label>
            <input
              v-model="form.birth_date"
              type="date"
              class="form-control rounded-3 py-2.5 fs-7"
            />
          </div>

          <button
            type="submit"
            class="btn btn-teal w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3"
            :disabled="isLoading"
          >
            <span v-if="isLoading" class="spinner-border spinner-border-sm me-1"></span>
            <span>Tiếp tục: Gửi mã xác nhận OTP qua Email →</span>
          </button>
        </div>

        <!-- STEP 2: Enter OTP Code -->
        <div v-else>
          <div class="alert alert-light border rounded-3 p-3 mb-3 text-center">
            <span class="fs-8 text-muted">Mã OTP đã được gửi đến:</span>
            <div class="fw-bold text-teal fs-6">{{ form.email }}</div>
          </div>

          <div class="mb-4">
            <label class="form-label fs-7 fw-bold mb-1">Nhập mã xác thực OTP (6 số)</label>
            <input
              v-model="form.otp_code"
              type="text"
              class="form-control text-center font-monospace fs-4 tracking-widest rounded-3 py-2.5 fw-bold"
              placeholder="123456"
              maxlength="6"
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
              <span>Xác nhận & Hoàn tất Đăng ký 🎉</span>
            </button>
          </div>

          <div class="text-center">
            <button
              @click="sendOtp"
              type="button"
              class="btn btn-link text-teal fs-8 text-decoration-none"
              :disabled="isLoading"
            >
              Chưa nhận được mã? Gửi lại OTP
            </button>
          </div>
        </div>

        <div class="text-center fs-7 text-muted mt-3 pt-3 border-top">
          Đã có tài khoản rồi?
          <router-link to="/login" class="fw-bold text-teal text-decoration-none ms-1">
            Đăng nhập ngay
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
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const step = ref(1)
const isLoading = ref(false)
const alertMessage = ref('')
const alertType = ref('success')
const debugOtp = ref('')

const form = ref({
  name: '',
  email: '',
  password: '',
  birth_date: '2005-01-01',
  otp_code: '',
})

const sendOtp = async () => {
  isLoading.value = true
  alertMessage.value = ''
  try {
    const res = await axios.post('/api/auth/register-send-otp', {
      email: form.value.email,
      name: form.value.name,
    })
    step.value = 2
    alertType.value = 'success'
    alertMessage.value = res.data.message
    if (res.data.debug_otp) {
      debugOtp.value = res.data.debug_otp
    }
  } catch (err) {
    alertType.value = 'error'
    alertMessage.value = err.response?.data?.message || 'Không thể gửi mã OTP. Vui lòng kiểm tra lại email.'
  } finally {
    isLoading.value = false
  }
}

const submitRegister = async () => {
  isLoading.value = true
  alertMessage.value = ''
  try {
    const res = await axios.post('/api/auth/register-with-otp', form.value)
    authStore.token = res.data.data.token
    authStore.user = res.data.data.user
    localStorage.setItem('oxford_token', res.data.data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.data.token}`
    await authStore.fetchMe()
    alert('Đăng ký tài khoản thành công!')
    router.push('/daily')
  } catch (err) {
    alertType.value = 'error'
    alertMessage.value = err.response?.data?.message || 'Mã OTP không chính xác hoặc đã hết hạn.'
  } finally {
    isLoading.value = false
  }
}
</script>
