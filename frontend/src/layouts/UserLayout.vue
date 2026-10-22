<template>
  <div class="user-layout min-vh-100 d-flex flex-column">
    <!-- Navbar matching Image 1 (media_1792672183705_bc785297.png) -->
    <nav class="navbar navbar-expand-lg border-bottom sticky-top py-2.5 shadow-sm" :class="authStore.themeMode === 'dark' ? 'bg-dark navbar-dark border-secondary' : 'bg-white navbar-light border-light-subtle'">
      <div class="container-fluid px-3 px-lg-4 max-w-7xl">
        <!-- Brand Logo -->
        <router-link to="/" class="navbar-brand d-flex align-items-center gap-2.5 text-decoration-none">
          <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: #00b074;">
            <span class="fs-5">Ox</span>
          </div>
          <div class="d-flex flex-column">
            <span class="fw-black fs-6 tracking-tight text-teal text-uppercase" style="letter-spacing: 0.5px;">OXFORD 3000™</span>
            <span class="text-muted d-none d-sm-inline" style="font-size: 10px;">TIẾNG ANH TOÀN DIỆN</span>
          </div>
        </router-link>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar" aria-controls="userNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="userNavbar">
          <!-- GUEST MENU: When NOT Logged In (Matches image media_1792672183705_bc785297.png) -->
          <ul v-if="!authStore.isAuthenticated" class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold fs-7 gap-1 gap-lg-3">
            <li class="nav-item">
              <a href="#tinh-nang" class="nav-link px-2.5 py-1.5 text-dark-emphasis">
                Tính năng
              </a>
            </li>
            <li class="nav-item">
              <a href="#quy-trinh" class="nav-link px-2.5 py-1.5 text-dark-emphasis">
                Quy trình
              </a>
            </li>
            <li class="nav-item">
              <a href="#loi-ich" class="nav-link px-2.5 py-1.5 text-dark-emphasis">
                Lợi ích
              </a>
            </li>
            <li class="nav-item">
              <router-link to="/dictionary" class="nav-link px-2.5 py-1.5 text-dark-emphasis">
                Tra cứu 3000 từ
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/ipa" class="nav-link px-2.5 py-1.5 text-dark-emphasis">
                44 Âm IPA
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/grammar" class="nav-link px-2.5 py-1.5 text-dark-emphasis">
                12 Thì Ngữ pháp
              </router-link>
            </li>
          </ul>

          <!-- AUTHENTICATED USER MENU -->
          <ul v-else class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold fs-7 gap-1">
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
              <router-link to="/phrasal-verbs" class="nav-link px-2.5 py-1.5 rounded-2" active-class="active fw-bold text-teal bg-teal-subtle">
                <i class="bi bi-puzzle me-1"></i> Học Cụm từ
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
                <i class="bi bi-search me-1"></i> Tra cứu
              </router-link>
            </li>
          </ul>

          <!-- Right Side: Dark toggle + Auth state -->
          <div class="d-flex align-items-center gap-2 gap-lg-3 mt-3 mt-lg-0">
            <!-- Theme Toggle -->
            <button
              @click="authStore.toggleTheme"
              class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
              style="width: 36px; height: 36px;"
              :title="authStore.themeMode === 'light' ? 'Chế độ Tối' : 'Chế độ Sáng'"
            >
              <i class="bi" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
            </button>

            <!-- GUEST BUTTONS (Matches image media_1792672183705_bc785297.png) -->
            <template v-if="!authStore.isAuthenticated">
              <router-link
                to="/login"
                class="btn btn-sm btn-outline-secondary bg-white text-dark border px-3.5 py-2 rounded-pill shadow-sm d-flex align-items-center gap-1.5 fs-7 fw-semibold"
              >
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Đăng nhập</span>
              </router-link>

              <router-link
                to="/register"
                class="btn btn-sm btn-teal text-white border-0 px-4 py-2 rounded-pill shadow d-flex align-items-center gap-2 fs-7 fw-bold"
                style="background-color: #00b074;"
              >
                <span>Bắt đầu ngay</span>
                <i class="bi bi-arrow-right"></i>
              </router-link>
            </template>

            <!-- AUTHENTICATED USER DROPDOWN (Matches screenshot media_1792669101971_c92089a6.png) -->
            <template v-else>
              <!-- XP Badge -->
              <div class="badge bg-primary-subtle text-primary border border-primary-subtle py-1.5 px-3 rounded-pill fs-7 d-flex align-items-center gap-1 shadow-sm">
                <span>⚡</span>
                <span class="fw-bold">{{ authStore.user?.xp_points || 0 }} XP</span>
              </div>

              <!-- Profile Dropdown Combobox -->
              <div class="position-relative" ref="dropdownRef">
                <button
                  @click="toggleDropdown"
                  class="btn btn-sm btn-teal rounded-pill d-flex align-items-center gap-2 px-3 py-1.5 shadow-sm border-0"
                  type="button"
                >
                  <img
                    :src="authStore.userAvatar"
                    class="rounded-circle border border-white"
                    style="width: 26px; height: 26px; object-fit: cover; background-color: #fff;"
                  />
                  <span class="d-none d-md-inline fw-bold text-white">{{ authStore.userName }}</span>
                  <i class="bi bi-chevron-down text-white fs-8"></i>
                </button>

                <!-- Profile Dropdown Card -->
                <div
                  v-if="isDropdownOpen"
                  class="dropdown-menu dropdown-menu-end show position-absolute end-0 mt-2 shadow-lg border rounded-4 py-2 z-3"
                  :class="authStore.themeMode === 'dark' ? 'dropdown-menu-dark bg-dark border-secondary' : 'bg-white border-light-subtle'"
                  style="min-width: 250px;"
                >
                  <div class="px-3 py-2 border-bottom">
                    <div class="fw-black fs-6 text-dark-emphasis text-truncate">{{ authStore.userName }}</div>
                    <div class="text-muted text-truncate fs-8">{{ authStore.userEmail }}</div>
                    <div class="mt-1.5">
                      <span
                        class="badge px-2.5 py-1 rounded-pill fw-semibold"
                        :style="authStore.isAdmin ? 'background-color: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;' : 'background-color: #d1fae5; color: #059669; border: 1px solid #6ee7b7;'"
                        style="font-size: 11px;"
                      >
                        {{ authStore.isAdmin ? 'Quản trị viên (Admin)' : 'Học viên' }}
                      </span>
                    </div>
                  </div>

                  <div class="py-1">
                    <router-link
                      to="/profile"
                      @click="isDropdownOpen = false"
                      class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-dark-emphasis"
                    >
                      <i class="bi bi-person-circle text-primary fs-6"></i>
                      <span>Trang cá nhân</span>
                    </router-link>

                    <router-link
                      v-if="authStore.isAdmin"
                      to="/admin"
                      @click="isDropdownOpen = false"
                      class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-danger fw-semibold"
                    >
                      <i class="bi bi-shield-lock-fill text-danger fs-6"></i>
                      <span>Quản trị hệ thống (Admin)</span>
                    </router-link>
                  </div>

                  <div class="dropdown-divider my-1"></div>

                  <div class="py-1">
                    <button
                      @click="handleLogout"
                      class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-danger border-0 bg-transparent w-100 text-start"
                    >
                      <i class="bi bi-box-arrow-right text-danger fs-6"></i>
                      <span>Đăng xuất</span>
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main View Outlet -->
    <main class="flex-grow-1">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="border-top py-4 text-center text-muted fs-8 mt-5" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'">
      <div class="container">
        <div><strong>Hệ Thống Học Tiếng Anh Toàn Diện Oxford 3000™</strong> • Full-Stack Vue 3 + Laravel API</div>
        <div class="text-secondary mt-1">Lộ trình 3000 từ vựng • 25 Chủ đề • Học Cụm từ • 12 Thì Ngữ pháp • 44 Âm IPA • Trắc nghiệm thông minh</div>
      </div>
    </footer>

    <!-- Global Login Required Gate Modal -->
    <LoginRequiredModal />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import LoginRequiredModal from '@/components/LoginRequiredModal.vue'

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
