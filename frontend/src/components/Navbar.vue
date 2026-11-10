<template>
  <nav class="navbar navbar-expand-lg border-bottom sticky-top shadow-sm py-2" :class="authStore.themeMode === 'dark' ? 'bg-dark navbar-dark border-secondary' : 'bg-white navbar-light border-light-subtle'">
    <div class="container-fluid px-3 px-lg-4">
      <!-- Brand Logo -->
      <router-link to="/" class="navbar-brand d-flex align-items-center gap-2 text-decoration-none">
        <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #085346 0%, #10b981 100%);">
          Ox
        </div>
        <div>
          <span class="fw-black fs-5 tracking-tight text-teal">Oxford 3000™</span>
          <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 10px;">PRO</span>
        </div>
      </router-link>

      <!-- Toggle Button for Mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Nav Links -->
      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold fs-7 gap-1">
          <li class="nav-item">
            <router-link to="/" class="nav-link px-2.5 py-1.5 rounded-2" active-class="active fw-bold text-teal bg-teal-subtle">
              <i class="bi bi-house-door me-1"></i> Trang chủ
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/daily" class="nav-link px-2.5 py-1.5 rounded-2" active-class="active fw-bold text-teal bg-teal-subtle">
              <i class="bi bi-calendar3 me-1"></i> Học theo Ngày (3000 từ)
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/topics" class="nav-link px-2.5 py-1.5 rounded-2" active-class="active fw-bold text-teal bg-teal-subtle">
              <i class="bi bi-diagram-3 me-1"></i> 25 Chủ đề
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/grammar" class="nav-link px-2.5 py-1.5 rounded-2" active-class="active fw-bold text-teal bg-teal-subtle">
              <i class="bi bi-journal-bookmark me-1"></i> 12 Thì & Ngữ pháp
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/ipa" class="nav-link px-2.5 py-1.5 rounded-2" active-class="active fw-bold text-teal bg-teal-subtle">
              <i class="bi bi-soundwave me-1"></i> 44 Âm IPA
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/dictionary" class="nav-link px-2.5 py-1.5 rounded-2" active-class="active fw-bold text-teal bg-teal-subtle">
              <i class="bi bi-search me-1"></i> Tra cứu 3000 từ
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/tips" class="nav-link px-2.5 py-1.5 rounded-2" active-class="active fw-bold text-teal bg-teal-subtle">
              <i class="bi bi-lightbulb me-1"></i> Mẹo học
            </router-link>
          </li>
        </ul>

        <!-- Right Side: Theme, Badges & User Combobox -->
        <div class="d-flex align-items-center gap-2 gap-lg-3 mt-3 mt-lg-0">
          <!-- Dark / Light Mode Toggle Button -->
          <button
            @click="authStore.toggleTheme"
            class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
            style="width: 36px; height: 36px;"
            :title="authStore.themeMode === 'light' ? 'Chuyển sang Chế độ Tối' : 'Chuyển sang Chế độ Sáng'"
          >
            <i class="bi" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
          </button>

          <!-- Logged in State -->
          <template v-if="authStore.isAuthenticated">
            <!-- Streak Badge -->
            <div class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1.5 px-2.5 rounded-pill fs-7 d-flex align-items-center gap-1 shadow-sm">
              <span>🔥</span>
              <span>{{ authStore.user?.streak_days || 1 }} ngày</span>
            </div>

            <!-- XP Badge -->
            <div class="badge bg-primary-subtle text-primary border border-primary-subtle py-1.5 px-2.5 rounded-pill fs-7 d-flex align-items-center gap-1 shadow-sm">
              <span>⚡</span>
              <span>{{ authStore.user?.xp_points || 0 }} XP</span>
            </div>

            <!-- Profile Combobox Dropdown -->
            <div class="position-relative" ref="dropdownRef">
              <button
                @click="toggleDropdown"
                class="btn btn-sm btn-teal rounded-pill d-flex align-items-center gap-2 px-3 py-1.5 shadow-sm"
                type="button"
              >
                <img :src="authStore.user?.avatar_url || 'https://api.dicebear.com/7.x/bottts/svg?seed=CuongPhung'" class="rounded-circle border border-white" style="width: 24px; height: 24px; object-fit: cover;" />
                <span class="d-none d-md-inline fw-semibold">{{ authStore.userName }}</span>
                <i class="bi bi-chevron-down fs-8"></i>
              </button>

              <!-- Custom Dropdown Menu -->
              <div
                v-if="isDropdownOpen"
                class="dropdown-menu dropdown-menu-end show position-absolute end-0 mt-2 shadow-lg border rounded-3 py-2 z-3"
                :class="authStore.themeMode === 'dark' ? 'dropdown-menu-dark bg-dark border-secondary' : 'bg-white border-light-subtle'"
                style="min-width: 230px;"
              >
                <div class="px-3 py-2 border-bottom">
                  <div class="fw-bold fs-7 text-truncate">{{ authStore.userName }}</div>
                  <div class="text-muted text-truncate" style="font-size: 11px;">{{ authStore.userEmail }}</div>
                  <span class="badge mt-1" :class="authStore.isAdmin ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-success-subtle text-success border border-success-subtle'" style="font-size: 10px;">
                    {{ authStore.isAdmin ? 'Quản trị viên (Admin)' : 'Học viên' }}
                  </span>
                </div>

                <router-link to="/profile" @click="isDropdownOpen = false" class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2">
                  <i class="bi bi-person-circle text-primary"></i> Trang cá nhân
                </router-link>

                <router-link v-if="authStore.isAdmin" to="/admin" @click="isDropdownOpen = false" class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-success">
                  <i class="bi bi-shield-lock-fill text-success"></i> Quản trị hệ thống (Admin)
                </router-link>

                <div class="dropdown-divider my-1"></div>

                <button @click="handleLogout" class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-danger border-0 bg-transparent w-100 text-start">
                  <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </button>
              </div>
            </div>
          </template>

          <!-- Guest / Not Logged In State -->
          <template v-else>
            <router-link to="/login" class="btn btn-sm btn-outline-teal px-3 py-1.5 rounded-pill shadow-sm">
              <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
            </router-link>
            <router-link to="/register" class="btn btn-sm btn-teal px-3 py-1.5 rounded-pill shadow-sm">
              <i class="bi bi-person-plus me-1"></i> Đăng ký
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const router = useRouter()
const isDropdownOpen = ref(false)
const dropdownRef = ref(null)

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isDropdownOpen.value = false
  }
}

const handleLogout = async () => {
  isDropdownOpen.value = false
  await authStore.logout()
  router.push('/login')
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
