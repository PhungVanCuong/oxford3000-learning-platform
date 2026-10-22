<template>
  <div class="container py-5 max-w-lg">
    <div class="card p-4 p-md-5 rounded-4 shadow-lg border bg-card-custom">
      <!-- Brand & Header -->
      <div class="text-center mb-4">
        <div class="rounded-3 d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm mb-3" style="width: 54px; height: 54px; background: linear-gradient(135deg, #085346 0%, #10b981 100%); font-size: 26px;">
          Ox
        </div>
        <h3 class="fw-black text-teal mb-1">Cổng Đăng Nhập Hệ Thống</h3>
        <p class="text-muted fs-7">Nền tảng học thông minh 3000 từ vựng Oxford & Ngữ pháp</p>
      </div>

      <!-- 2 Portal Tabs: Học Viên vs Quản Trị Viên -->
      <div class="d-flex rounded-pill p-1 bg-body-tertiary border mb-4">
        <button
          @click="selectPortal('user')"
          class="btn btn-sm flex-grow-1 rounded-pill py-2 fw-bold fs-7 transition-all"
          :class="activePortal === 'user' ? 'btn-teal shadow-sm' : 'btn-light border-0 text-muted'"
          type="button"
        >
          <i class="bi bi-mortarboard-fill me-1"></i> Cổng Học Viên
        </button>
        <button
          @click="selectPortal('admin')"
          class="btn btn-sm flex-grow-1 rounded-pill py-2 fw-bold fs-7 transition-all"
          :class="activePortal === 'admin' ? 'btn-danger shadow-sm text-white' : 'btn-light border-0 text-muted'"
          type="button"
        >
          <i class="bi bi-shield-lock-fill me-1"></i> Cổng Quản Trị (Admin)
        </button>
      </div>

      <!-- Quick 1-Click Demo Logins for Current Portal -->
      <div class="alert border rounded-3 p-3 mb-4" :class="activePortal === 'admin' ? 'alert-danger bg-danger-subtle border-danger-subtle' : 'alert-success bg-success-subtle border-success-subtle'">
        <div class="fw-bold fs-8 mb-2 text-uppercase d-flex align-items-center justify-content-between">
          <span>{{ activePortal === 'admin' ? 'Tài khoản Quản trị viên (1-Click):' : 'Tài khoản Học viên mẫu (1-Click):' }}</span>
          <span class="badge" :class="activePortal === 'admin' ? 'bg-danger' : 'bg-teal'">MK: 123456</span>
        </div>

        <div v-if="activePortal === 'admin'" class="d-flex flex-column gap-2">
          <button
            @click="fillLogin('cuongphung1810@gmail.com', '123456')"
            type="button"
            class="btn btn-sm btn-outline-danger text-start d-flex justify-content-between align-items-center rounded-pill px-3 py-1.5"
          >
            <span><strong>Cường Phùng</strong> (cuongphung1810@gmail.com)</span>
            <span class="badge bg-danger">Chính</span>
          </button>
          <button
            @click="fillLogin('admin@quizlet3000.com', '123456')"
            type="button"
            class="btn btn-sm btn-outline-dark text-start d-flex justify-content-between align-items-center rounded-pill px-3 py-1.5"
          >
            <span><strong>Quản Trị Viên Hệ Thống</strong></span>
            <span class="badge bg-secondary">System Admin</span>
          </button>
        </div>

        <div v-else class="d-flex flex-column gap-2">
          <button
            @click="fillLogin('user1@gmail.com', '123456')"
            type="button"
            class="btn btn-sm btn-outline-success text-start d-flex justify-content-between align-items-center rounded-pill px-3 py-1.5"
          >
            <span><strong>Nguyễn Văn An</strong> (user1@gmail.com)</span>
            <span class="badge bg-success">Học viên</span>
          </button>
          <button
            @click="fillLogin('user2@gmail.com', '123456')"
            type="button"
            class="btn btn-sm btn-outline-teal text-start d-flex justify-content-between align-items-center rounded-pill px-3 py-1.5"
          >
            <span><strong>Trần Thị Bích</strong> (user2@gmail.com)</span>
            <span class="badge bg-teal">Học viên</span>
          </button>
        </div>
      </div>

      <!-- Error Alert -->
      <div v-if="authStore.error" class="alert alert-danger py-2 fs-7 mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ authStore.error }}</span>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label class="form-label fs-7 fw-bold mb-1">Email đăng nhập</label>
          <input
            v-model="email"
            type="email"
            class="form-control rounded-3 py-2.5 fs-7"
            placeholder="name@example.com"
            required
          />
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label fs-7 fw-bold mb-0">Mật khẩu</label>
            <router-link to="/forgot-password" class="fs-8 text-teal text-decoration-none fw-semibold">
              Quên mật khẩu?
            </router-link>
          </div>
          <input
            v-model="password"
            type="password"
            class="form-control rounded-3 py-2.5 fs-7"
            placeholder="••••••••"
            required
          />
        </div>

        <button
          type="submit"
          class="btn w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3"
          :class="activePortal === 'admin' ? 'btn-danger' : 'btn-teal'"
          :disabled="authStore.isLoading"
        >
          <span v-if="authStore.isLoading" class="spinner-border spinner-border-sm me-1"></span>
          <span>Đăng nhập vào {{ activePortal === 'admin' ? 'Bảng Quản Trị' : 'Hệ Thống Học Tập' }}</span>
        </button>

        <!-- Register Link -->
        <div class="text-center fs-7 text-muted">
          Chưa có tài khoản?
          <router-link to="/register" class="fw-bold text-teal text-decoration-none ms-1">
            Đăng ký tài khoản mới ngay
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const router = useRouter()

const activePortal = ref('admin') // 'user' or 'admin'
const email = ref('cuongphung1810@gmail.com')
const password = ref('123456')

const selectPortal = (portal) => {
  activePortal.value = portal
  if (portal === 'admin') {
    email.value = 'cuongphung1810@gmail.com'
    password.value = '123456'
  } else {
    email.value = 'user1@gmail.com'
    password.value = '123456'
  }
}

const fillLogin = (em, pw) => {
  email.value = em
  password.value = pw
}

const handleLogin = async () => {
  const success = await authStore.login(email.value, password.value)
  if (success) {
    if (activePortal.value === 'admin' && authStore.isAdmin) {
      router.push('/admin')
    } else {
      router.push('/daily')
    }
  }
}
</script>
