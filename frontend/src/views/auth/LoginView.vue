<template>
  <div class="login-page-wrapper min-vh-100 d-flex flex-column justify-content-between" :class="authStore.themeMode === 'dark' ? 'bg-dark-fb text-white' : 'bg-light-fb text-dark'">
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
          <!-- LEFT COLUMN: High-Tech Brand Showcase (No fake testimonials) -->
          <div class="col-12 col-lg-6 col-xl-6 text-center text-lg-start mb-2 mb-lg-0">
            <div class="d-inline-flex align-items-center gap-2 mb-3">
              <div class="rounded-4 d-inline-flex align-items-center justify-content-center text-white fw-black shadow-sm" style="width: 52px; height: 52px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%); font-size: 26px;">
                Ox
              </div>
              <span class="fw-black text-teal display-6 text-uppercase" style="letter-spacing: -0.5px;">OxVocab<span class="text-success fs-4 align-top">™</span></span>
            </div>

            <h1 class="fw-black mb-3 lh-sm" :class="authStore.themeMode === 'dark' ? 'text-light' : 'text-dark-emphasis'" style="font-size: clamp(1.45rem, 2.3vw, 1.85rem);">
              Bứt phá vốn từ & làm chủ giao tiếp với <span class="text-teal">3.000 từ vựng Oxford</span> thông minh.
            </h1>

            <p class="text-secondary fs-7 mb-4 d-none d-md-block" style="line-height: 1.6; max-width: 520px;">
              Đồng bộ dữ liệu học tập cá nhân hóa, luyện nói chấm điểm phát âm AI chuẩn bản xứ, 25 chủ đề lộ trình thực chiến và thuật toán ghi nhớ dài hạn SRS.
            </p>

            <!-- HIGH-TECH TECH SHOWCASE CARD -->
            <div class="card border-0 rounded-4 p-3.5 p-md-4 shadow-sm mb-4 text-start position-relative overflow-hidden text-white" style="background: linear-gradient(145deg, #032b24 0%, #064e40 60%, #087F75 100%);">
              <!-- Card Header -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(94, 234, 212, 0.18); color: #5eead4; font-size: 11px;">
                  <i class="bi bi-cpu-fill me-1"></i> AI SPEECH ENGINE 2.0
                </span>
                <span class="fs-8 fw-semibold text-white-50 d-flex align-items-center gap-1.5">
                  <span class="rounded-circle bg-success d-inline-block" style="width: 7px; height: 7px;"></span>
                  Hệ thống sẵn sàng
                </span>
              </div>

              <!-- Audio Wave Simulation & Target Word -->
              <div class="p-3 rounded-3 mb-3" style="background: rgba(0, 0, 0, 0.28); border: 1px solid rgba(255, 255, 255, 0.12);">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                  <div>
                    <span class="fs-8 text-white-50 text-uppercase fw-semibold">Từ vựng mục tiêu:</span>
                    <div class="fs-5 fw-black text-white">comprehensive <span class="fs-8 fw-normal text-white-50">/ˌkɑːm.prɪˈhen.sɪv/</span></div>
                  </div>
                  <div class="text-end">
                    <span class="badge rounded-pill px-2.5 py-1 fw-black" style="background: #00b074; color: #ffffff; font-size: 11px;">
                      <i class="bi bi-patch-check-fill me-1"></i> 98.5% Native Match
                    </span>
                  </div>
                </div>

                <!-- Animated-like Sound Bars -->
                <div class="d-flex align-items-end gap-1" style="height: 28px;">
                  <span class="rounded-pill bg-success flex-grow-1" style="height: 35%; opacity: 0.6;"></span>
                  <span class="rounded-pill bg-success flex-grow-1" style="height: 65%; opacity: 0.85;"></span>
                  <span class="rounded-pill bg-success flex-grow-1" style="height: 90%; opacity: 1;"></span>
                  <span class="rounded-pill bg-teal flex-grow-1" style="height: 100%; opacity: 1;"></span>
                  <span class="rounded-pill bg-teal flex-grow-1" style="height: 80%; opacity: 0.9;"></span>
                  <span class="rounded-pill bg-success flex-grow-1" style="height: 95%; opacity: 1;"></span>
                  <span class="rounded-pill bg-success flex-grow-1" style="height: 50%; opacity: 0.7;"></span>
                  <span class="rounded-pill bg-success flex-grow-1" style="height: 75%; opacity: 0.85;"></span>
                  <span class="rounded-pill bg-teal flex-grow-1" style="height: 40%; opacity: 0.65;"></span>
                </div>
              </div>

              <!-- 4 Platform Highlights Grid -->
              <div class="row g-2 fs-8">
                <div class="col-6">
                  <div class="p-2 rounded-2" style="background: rgba(255, 255, 255, 0.08);">
                    <div class="fw-bold text-teal-light">3.000 Mục từ Oxford</div>
                    <div class="text-white-50" style="font-size: 11px;">Chuẩn CEFR A1 - C1</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-2 rounded-2" style="background: rgba(255, 255, 255, 0.08);">
                    <div class="fw-bold text-teal-light">25 Chủ đề Thực chiến</div>
                    <div class="text-white-50" style="font-size: 11px;">10 Chặng trắc nghiệm mỗi bài</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-2 rounded-2" style="background: rgba(255, 255, 255, 0.08);">
                    <div class="fw-bold text-teal-light">Lặp lại ngắt quãng (SRS)</div>
                    <div class="text-white-50" style="font-size: 11px;">Tối ưu hóa phản xạ dài hạn</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-2 rounded-2" style="background: rgba(255, 255, 255, 0.08);">
                    <div class="fw-bold text-teal-light">Tự động nhận diện quyền</div>
                    <div class="text-white-50" style="font-size: 11px;">1 Cổng đăng nhập duy nhất</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Feature Pills -->
            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
              <span class="badge rounded-pill px-3 py-1.5 fs-8 fw-semibold border" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary text-light' : 'bg-white border-light-subtle text-dark-emphasis shadow-2xs'">
                <i class="bi bi-shield-check text-success me-1"></i> Bảo mật đa lớp
              </span>
              <span class="badge rounded-pill px-3 py-1.5 fs-8 fw-semibold border" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary text-light' : 'bg-white border-light-subtle text-dark-emphasis shadow-2xs'">
                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Đồng bộ đám mây
              </span>
              <span class="badge rounded-pill px-3 py-1.5 fs-8 fw-semibold border" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary text-light' : 'bg-white border-light-subtle text-dark-emphasis shadow-2xs'">
                <i class="bi bi-mic-fill text-teal me-1"></i> Kiểm tra giọng nói AI
              </span>
            </div>
          </div>

          <!-- RIGHT COLUMN: Single Unified Login Card -->
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-sm-4.5" :class="authStore.themeMode === 'dark' ? 'bg-card-dark border border-secondary' : 'bg-white'">
              
              <!-- Unified Header (Only 1 Single Portal) -->
              <div class="mb-3.5 pb-2 border-bottom">
                <h4 class="fw-black mb-1 text-teal">Đăng Nhập Tài Khoản</h4>
                <span class="text-muted fs-8">
                  Truy cập tài khoản học tập và khu vực làm việc của bạn
                </span>
              </div>

              <!-- Error Alert -->
              <div v-if="authStore.error" class="alert alert-danger py-2.5 px-3 fs-7 mb-3 d-flex align-items-center gap-2 rounded-3">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-6 flex-shrink-0"></i>
                <div>{{ authStore.error }}</div>
              </div>

              <!-- Form -->
              <form @submit.prevent="handleLogin" action="#" method="post" novalidate>
                <!-- Email / Account Input -->
                <div class="mb-3">
                  <div class="form-floating">
                    <input
                      v-model="email"
                      type="email"
                      name="email"
                      class="form-control rounded-3 fs-6"
                      id="floatingEmail"
                      placeholder="name@example.com"
                      autocomplete="username email"
                      required
                    />
                    <label for="floatingEmail" class="text-muted">
                      <i class="bi bi-envelope me-1.5 text-teal"></i>Email hoặc tài khoản
                    </label>
                  </div>
                </div>

                <!-- Password Input with Custom Sleek Eye -->
                <div class="mb-2">
                  <div class="position-relative">
                    <div class="form-floating">
                      <input
                        v-model="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        class="form-control rounded-3 fs-6 pe-5"
                        id="floatingPassword"
                        placeholder="Mật khẩu"
                        autocomplete="current-password"
                        required
                      />
                      <label for="floatingPassword" class="text-muted">
                        <i class="bi bi-shield-lock me-1.5 text-teal"></i>Mật khẩu
                      </label>
                    </div>

                    <!-- Sleek Eye Toggle Button - TABINDEX="-1" so Tab skips directly to Next! -->
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

                <!-- ROW UNDER PASSWORD: Save Password & Forgot Password Link -->
                <div class="d-flex justify-content-between align-items-center mb-3 pt-1">
                  <!-- Remember Password Checkbox -->
                  <div class="form-check fs-8">
                    <input
                      class="form-check-input custom-checkbox"
                      type="checkbox"
                      id="rememberMeCheckbox"
                      v-model="rememberMe"
                    />
                    <label class="form-check-label user-select-none fw-medium text-secondary" for="rememberMeCheckbox">
                      Lưu mật khẩu & ghi nhớ
                    </label>
                  </div>

                  <!-- Forgot Password Link -->
                  <router-link to="/forgot-password" class="fs-8 text-teal text-decoration-none fw-semibold forgot-link">
                    Quên mật khẩu?
                  </router-link>
                </div>

                <!-- Submit Button -->
                <button
                  type="submit"
                  class="btn btn-teal w-100 py-2.5 fw-bold fs-6 shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2 rounded-3"
                  :disabled="authStore.isLoading || !email || !password"
                >
                  <span v-if="authStore.isLoading" class="spinner-border spinner-border-sm"></span>
                  <span v-else>Đăng Nhập</span>
                </button>

                <!-- Facebook-style Divider -->
                <div class="position-relative text-center my-3">
                  <hr class="text-secondary-subtle m-0">
                  <span class="position-absolute top-50 start-50 translate-middle px-3 fs-8 text-muted" :class="authStore.themeMode === 'dark' ? 'bg-card-dark' : 'bg-white'">
                    hoặc
                  </span>
                </div>

                <!-- Facebook-style Green Create Account Button -->
                <div class="text-center pt-1">
                  <router-link
                    to="/register"
                    class="btn btn-success px-4 py-2.5 rounded-3 fw-bold fs-6 shadow-sm d-inline-flex align-items-center gap-1.5 btn-facebook-register"
                  >
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tạo tài khoản mới</span>
                  </router-link>
                </div>
              </form>
            </div>

            <!-- Under-card Caption -->
            <div class="text-center mt-3 fs-8 text-muted">
              <span>Trang học tiếng Anh chuẩn quốc tế • </span>
              <router-link to="/tips" class="text-decoration-none text-teal fw-semibold">Bí quyết học 3000 từ</router-link>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Facebook-Style Clean Footer -->
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
          <span>•</span>
          <router-link to="/grammar" class="text-muted text-decoration-none hover-teal">Ngữ pháp</router-link>
        </div>

        <div>
          <span>OxVocab™ © 2026 • Nền tảng học thông minh</span>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const rememberMe = ref(false)

const persistCredentials = () => {
  if (rememberMe.value) {
    localStorage.setItem('oxvocab_remember_login', 'true')
    if (email.value) localStorage.setItem('oxvocab_saved_email', email.value)
    if (password.value) localStorage.setItem('oxvocab_saved_password', password.value)
  } else {
    localStorage.removeItem('oxvocab_remember_login')
    localStorage.removeItem('oxvocab_saved_email')
    localStorage.removeItem('oxvocab_saved_password')
  }
}

watch(rememberMe, () => {
  persistCredentials()
})

const handleLogin = async () => {
  if (!email.value || !password.value) return

  persistCredentials()

  const success = await authStore.login(email.value, password.value)
  if (success) {
    // Auto-detect Role: Staff / Admin automatically jump to /admin, Learners go to Home or Daily
    if (authStore.isStaff || authStore.isAdmin) {
      router.push('/admin')
    } else {
      router.push('/daily')
    }
  }
}

onMounted(() => {
  // Restore saved login if rememberMe was previously checked
  const savedRemember = localStorage.getItem('oxvocab_remember_login')
  if (savedRemember === 'true') {
    rememberMe.value = true
    const savedEmail = localStorage.getItem('oxvocab_saved_email')
    const savedPassword = localStorage.getItem('oxvocab_saved_password')
    if (savedEmail) {
      email.value = savedEmail
    }
    if (savedPassword) {
      password.value = savedPassword
    }
  }
})
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

.btn-facebook-register {
  background-color: #42b72a;
  border-color: #42b72a;
  color: #ffffff;
  padding-left: 28px;
  padding-right: 28px;
  font-size: 16px;
  letter-spacing: 0.2px;
}

.btn-facebook-register:hover {
  background-color: #36a420;
  border-color: #36a420;
  color: #ffffff;
}

.custom-checkbox:checked {
  background-color: #087F75;
  border-color: #087F75;
}

.forgot-link:hover {
  text-decoration: underline !important;
}

.hover-teal:hover {
  color: #087F75 !important;
}
</style>
