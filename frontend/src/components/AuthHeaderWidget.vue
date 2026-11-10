<template>
  <div class="auth-header-widget d-flex align-items-center gap-2">
    <!-- TRẠNG THÁI 1: CHƯA ĐĂNG NHẬP (GUEST) -->
    <template v-if="!authStore.isAuthenticated">
      <!-- Nút Đăng nhập -->
      <router-link
        to="/login"
        class="btn btn-sm d-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill shadow-xs transition-all border"
        :class="authStore.themeMode === 'dark' ? 'bg-dark text-white border-secondary' : 'bg-white text-dark-emphasis border-light-subtle'"
        title="Đăng nhập tài khoản"
      >
        <i class="bi bi-box-arrow-in-right fs-7 text-teal"></i>
        <span class="fs-7 fw-semibold">Đăng nhập</span>
      </router-link>

      <!-- Nút Đăng ký -->
      <router-link
        to="/register"
        class="btn btn-sm d-flex align-items-center gap-1.5 px-3.5 py-1.5 rounded-pill shadow-sm transition-all text-white border-0 fw-bold"
        style="background: linear-gradient(135deg, #00b074 0%, #064e40 100%);"
        title="Đăng ký tài khoản mới"
      >
        <span class="fs-7">Đăng ký</span>
        <i class="bi bi-arrow-right fs-8"></i>
      </router-link>
    </template>

    <!-- TRẠNG THÁI 2: ĐÃ ĐĂNG NHẬP (AUTHENTICATED) -->
    <template v-else>
      <!-- Điểm XP tích lũy (Chỉ hiện ở giao diện học viên) -->
      <div 
        v-if="!isAdminView"
        class="badge bg-primary-subtle text-primary border border-primary-subtle py-1.5 px-2.5 px-md-3 rounded-pill fs-7 d-flex align-items-center gap-1 shadow-xs"
      >
        <span>⚡</span>
        <span class="fw-bold">{{ authStore.user?.xp_points || 0 }} XP</span>
      </div>

      <!-- Huy hiệu chức vụ (Nếu là Admin view) -->
      <div
        v-else-if="authStore.isAdmin || authStore.isStaff"
        class="badge rounded-pill py-1.5 px-2.5 fs-8 d-none d-sm-inline-block"
        :class="authStore.isAdmin ? 'bg-danger text-white' : 'bg-primary text-white'"
      >
        {{ authStore.isAdmin ? 'Admin' : 'Staff' }}
      </div>

      <!-- Avatar & Profile Dropdown Combobox -->
      <div class="position-relative flex-shrink-0" ref="dropdownRef">
        <button
          @click="toggleDropdown"
          class="btn btn-sm btn-teal rounded-pill d-flex align-items-center gap-2 px-2.5 px-md-3 py-1.5 shadow-sm border-0 text-nowrap profile-pill-btn"
          type="button"
          :aria-expanded="isDropdownOpen"
        >
          <!-- Avatar kèm badge Google nếu đăng nhập qua Google -->
          <div class="position-relative flex-shrink-0">
            <img
              :src="userAvatarUrl"
              class="rounded-circle border border-white flex-shrink-0"
              style="width: 28px; height: 28px; object-fit: cover; background-color: #fff;"
              :alt="authStore.userName"
              @error="handleAvatarError"
            />
            <!-- Chấm xanh Online hoặc logo Google nhỏ góc avatar -->
            <span 
              v-if="authStore.user?.google_id"
              class="position-absolute bottom-0 end-0 bg-white rounded-circle p-0.5 shadow-2xs d-flex align-items-center justify-content-center"
              style="width: 12px; height: 12px; transform: translate(2px, 2px);"
              title="Đã liên kết Google"
            >
              <svg width="8" height="8" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
              </svg>
            </span>
          </div>

          <span class="d-none d-md-inline fw-bold text-white text-nowrap max-w-140 text-truncate">
            {{ authStore.userName }}
          </span>

          <i class="bi bi-chevron-down text-white fs-8 flex-shrink-0 transition-transform" :class="{ 'rotate-180': isDropdownOpen }"></i>
        </button>

        <!-- Dropdown Menu Người Dùng -->
        <Transition name="fade-menu">
          <div
            v-if="isDropdownOpen"
            class="dropdown-menu dropdown-menu-end show position-absolute end-0 mt-2 shadow-xl border rounded-4 py-2 z-3"
            :class="authStore.themeMode === 'dark' ? 'dropdown-menu-dark bg-dark border-secondary text-white' : 'bg-white border-light-subtle text-dark'"
            style="min-width: 260px;"
          >
            <!-- Header thông tin tài khoản -->
            <div class="px-3 py-2.5 border-bottom" :class="authStore.themeMode === 'dark' ? 'border-secondary' : 'border-light-subtle'">
              <div class="d-flex align-items-center gap-2 mb-1">
                <div class="fw-black fs-6 text-truncate flex-grow-1">{{ authStore.userName }}</div>
                <span v-if="authStore.user?.google_id" class="badge bg-primary-subtle text-primary border border-primary-subtle py-0.5 px-1.5 rounded-pill fs-8">
                  Google
                </span>
              </div>
              <div class="text-secondary text-truncate fs-8 mb-1.5">{{ authStore.userEmail }}</div>
              <div>
                <span
                  class="badge px-2.5 py-1 rounded-pill fw-semibold"
                  :style="authStore.isAdmin ? 'background-color: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;' : 'background-color: #d1fae5; color: #059669; border: 1px solid #6ee7b7;'"
                  style="font-size: 11px;"
                >
                  {{ authStore.isAdmin ? 'Quản trị viên (Admin)' : 'Học viên Oxford' }}
                </span>
              </div>
            </div>

            <!-- Các mục liên kết nhanh -->
            <div class="py-1">
              <router-link
                to="/profile"
                @click="isDropdownOpen = false"
                class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2"
                :class="authStore.themeMode === 'dark' ? 'text-white' : 'text-dark-emphasis'"
              >
                <i class="bi bi-person-circle text-primary fs-6"></i>
                <span>Trang cá nhân & Cài đặt</span>
              </router-link>

              <router-link
                to="/roadmap"
                @click="isDropdownOpen = false"
                class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2"
                :class="authStore.themeMode === 'dark' ? 'text-white' : 'text-dark-emphasis'"
              >
                <i class="bi bi-map-fill text-teal fs-6"></i>
                <span>Lộ trình Từ Vựng CEFR</span>
              </router-link>

              <router-link
                to="/review"
                @click="isDropdownOpen = false"
                class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2"
                :class="authStore.themeMode === 'dark' ? 'text-white' : 'text-dark-emphasis'"
              >
                <i class="bi bi-repeat text-warning fs-6"></i>
                <span>Bộ Thẻ Cần Ôn Tập</span>
              </router-link>

              <router-link
                to="/history"
                @click="isDropdownOpen = false"
                class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2"
                :class="authStore.themeMode === 'dark' ? 'text-white' : 'text-dark-emphasis'"
              >
                <i class="bi bi-clock-history text-info fs-6"></i>
                <span>Lịch Sử Bài Học</span>
              </router-link>

              <!-- Phân quyền Admin -->
              <router-link
                v-if="!isAdminView && (authStore.isStaff || authStore.isAdmin)"
                to="/admin"
                @click="isDropdownOpen = false"
                class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-danger fw-semibold"
              >
                <i class="bi bi-shield-lock-fill text-danger fs-6"></i>
                <span>Khu vực Quản trị Admin</span>
              </router-link>

              <!-- Nếu đang ở Admin thì có link quay về trang học -->
              <router-link
                v-if="isAdminView"
                to="/"
                @click="isDropdownOpen = false"
                class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-teal fw-semibold"
              >
                <i class="bi bi-box-arrow-up-right text-teal fs-6"></i>
                <span>Về Giao diện Học tập</span>
              </router-link>
            </div>

            <div class="dropdown-divider my-1" :class="authStore.themeMode === 'dark' ? 'border-secondary' : 'border-light-subtle'"></div>

            <!-- Nút Đăng xuất tại chỗ (Không load lại trang, Toast thông báo) -->
            <div class="py-1">
              <button
                @click="handleLogout"
                class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-danger border-0 bg-transparent w-100 text-start"
              >
                <i class="bi bi-box-arrow-right text-danger fs-6"></i>
                <span class="fw-semibold">Đăng xuất tài khoản</span>
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useGoogleAuth } from '@/composables/useGoogleAuth'
import { useToast } from '@/composables/useToast'

const props = defineProps({
  isAdminView: {
    type: Boolean,
    default: false
  }
})

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()
const { isLoggingIn, openGoogleLoginPopup } = useGoogleAuth()

const isDropdownOpen = ref(false)
const dropdownRef = ref(null)

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const handleClickOutside = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isDropdownOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

const userAvatarUrl = computed(() => {
  return authStore.userAvatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(authStore.userName || 'U')}&background=00b074&color=fff`
})

const handleAvatarError = (e) => {
  e.target.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(authStore.userName || 'U')}&background=00b074&color=fff`
}

/**
 * Đăng nhập qua Popup Google không tải lại trang
 */
const handleGoogleLogin = () => {
  openGoogleLoginPopup({
    onSuccess: (user) => {
      // Toast thành công đã được useGoogleAuth bắn ra
    }
  })
}

/**
 * Đăng xuất tại chỗ mượt mà
 */
const handleLogout = async () => {
  isDropdownOpen.value = false
  const prevName = authStore.userName || 'bạn'
  await authStore.logout()
  toast.info(`Tài khoản "${prevName}" đã đăng xuất an toàn.`, 'Đăng xuất thành công')
  router.push('/login')
}
</script>

<style scoped>
.auth-header-widget {
  user-select: none;
}

.btn-google-compact {
  font-size: 13px;
  font-weight: 600;
  border-width: 1.5px !important;
  transition: all 0.2s ease;
}

.btn-google-compact:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(66, 133, 244, 0.2) !important;
  border-color: #4285F4 !important;
}

.btn-google-mobile {
  border-width: 1.5px !important;
  transition: transform 0.2s ease;
}

.btn-google-mobile:active {
  transform: scale(0.92);
}

.max-w-140 {
  max-width: 140px;
}

.transition-transform {
  transition: transform 0.2s ease;
}

.rotate-180 {
  transform: rotate(180deg);
}

/* Hiệu ứng mượt mà cho dropdown */
.fade-menu-enter-active,
.fade-menu-leave-active {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.fade-menu-enter-from,
.fade-menu-leave-to {
  opacity: 0;
  transform: translateY(-8px) scale(0.97);
}
</style>
