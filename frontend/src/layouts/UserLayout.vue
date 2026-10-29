<template>
  <div class="user-layout min-vh-100 d-flex flex-column">
    <!-- Navbar matching Image 1 (media_1792672183705_bc785297.png) -->
    <nav class="navbar navbar-expand-lg border-bottom sticky-top py-2.5 shadow-sm" :class="authStore.themeMode === 'dark' ? 'bg-dark navbar-dark border-secondary' : 'bg-white navbar-light border-light-subtle'" style="z-index: 1040 !important;">
      <div class="container-fluid px-3 px-md-4 px-xl-5">
        <!-- Brand Logo -->
        <router-link to="/" class="navbar-brand d-flex align-items-center gap-2.5 text-decoration-none">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-black shadow-sm" style="width: 42px; height: 42px; background: linear-gradient(135deg, #00b074 0%, #064e40 100%);">
            <span class="fs-5" style="letter-spacing: -0.5px;">Ox</span>
          </div>
          <div class="d-flex flex-column">
            <span class="fw-black fs-5 tracking-tight text-teal text-uppercase leading-tight" style="letter-spacing: 0.5px;">
              OXVOCAB<span class="text-success" style="font-size: 11px; vertical-align: top;">™</span>
            </span>
            <span class="text-muted d-none d-sm-inline fw-semibold" style="font-size: 10px; letter-spacing: 0.3px;">HỌC TỪ VỰNG & LỘ TRÌNH THÔNG MINH</span>
          </div>
        </router-link>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar" aria-controls="userNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="userNavbar">
          <!-- UNIFIED 6-SLOT NAVIGATION BAR (Slot 1: Trang chủ, Slots 2-5: Customizable 4 items, Slot 6: Xem thêm) -->
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold fs-7 gap-1.5 gap-lg-2 align-items-center flex-nowrap">
            <!-- Slot 1: Trang chủ (Fixed) -->
            <li class="nav-item">
              <router-link to="/" class="nav-link px-2.5 py-1.5 rounded-pill nav-custom-link text-nowrap" exact-active-class="nav-item-active">
                <i class="bi bi-house-door-fill me-1.5 icon-home"></i>
                <span>Trang chủ</span>
              </router-link>
            </li>

            <!-- Slots 2-5: The 4 Customized Navigation Items -->
            <li v-for="item in visibleNavItems" :key="item.id" class="nav-item">
              <router-link :to="item.to" class="nav-link px-2.5 py-1.5 rounded-pill nav-custom-link d-flex align-items-center gap-1.5 text-nowrap" active-class="nav-item-active">
                <i :class="[item.icon, item.iconClass, 'me-1']"></i>
                <span>{{ item.label }}</span>
                <span v-if="item.badge" class="badge bg-warning text-dark px-1.5 py-1 rounded-pill shadow-xs" style="font-size: 8px;">{{ item.badge }}</span>
              </router-link>
            </li>

            <!-- Slot 6: Xem thêm Dropdown (Containing all other items) -->
            <li class="nav-item position-relative" ref="moreMenuRef">
              <button
                @click="toggleMoreMenu"
                class="nav-link px-2.5 py-1.5 rounded-pill nav-custom-link d-flex align-items-center gap-1 border-0 bg-transparent text-nowrap"
                :class="{ 'nav-item-active': isMoreMenuOpen }"
                type="button"
              >
                <i class="bi bi-grid-fill me-1 text-teal"></i>
                <span>Xem thêm</span>
                <i class="bi bi-chevron-down fs-8 ms-0.5"></i>
              </button>

              <div
                v-if="isMoreMenuOpen"
                class="dropdown-menu show position-absolute start-0 mt-2 shadow-lg border rounded-4 py-2 z-3"
                :class="authStore.themeMode === 'dark' ? 'dropdown-menu-dark bg-dark border-secondary' : 'bg-white border-light-subtle'"
                style="min-width: 250px;"
              >
                <router-link
                  v-for="item in moreNavItems"
                  :key="item.id"
                  :to="item.to"
                  @click="isMoreMenuOpen = false"
                  class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2"
                >
                  <i :class="[item.icon, item.iconClass, 'fs-6']"></i>
                  <span>{{ item.fullLabel || item.label }}</span>
                </router-link>
              </div>
            </li>
          </ul>

          <!-- Right Side: Arena Icon + Dark toggle + Auth state -->
          <div class="d-flex align-items-center gap-2 gap-lg-2.5 mt-3 mt-lg-0 flex-nowrap">
            <!-- Arena Circular Icon Button (Left of Theme Toggle) -->
            <router-link
              to="/arena"
              class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm position-relative text-decoration-none"
              style="width: 36px; height: 36px;"
              :class="authStore.themeMode === 'dark' ? 'border-secondary' : 'border-light-subtle'"
              title="Đấu trường Quiz & Luyện thi"
            >
              <i class="bi bi-trophy-fill text-warning fs-6"></i>
              <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="transform: translate(-30%, 25%) !important;">
                <span class="visually-hidden">Live</span>
              </span>
            </router-link>

            <!-- Theme Toggle Button -->
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
              <div class="position-relative flex-shrink-0" ref="dropdownRef">
                <button
                  @click="toggleDropdown"
                  class="btn btn-sm btn-teal rounded-pill d-flex align-items-center gap-2 px-3 py-1.5 shadow-sm border-0 text-nowrap"
                  style="white-space: nowrap !important; flex-shrink: 0;"
                  type="button"
                >
                  <img
                    :src="authStore.userAvatar"
                    class="rounded-circle border border-white flex-shrink-0"
                    style="width: 26px; height: 26px; object-fit: cover; background-color: #fff;"
                  />
                  <span class="d-none d-md-inline fw-bold text-white text-nowrap" style="white-space: nowrap !important;">{{ authStore.userName }}</span>
                  <i class="bi bi-chevron-down text-white fs-8 flex-shrink-0"></i>
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
                      to="/tree"
                      @click="isDropdownOpen = false"
                      class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-dark-emphasis"
                    >
                      <i class="bi bi-tree-fill text-success fs-6"></i>
                      <span>Vườn Cây Tri Thức</span>
                    </router-link>

                    <router-link
                      to="/stats"
                      @click="isDropdownOpen = false"
                      class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-dark-emphasis"
                    >
                      <i class="bi bi-bar-chart-fill text-teal fs-6"></i>
                      <span>Báo Cáo Năng Suất</span>
                    </router-link>

                    <router-link
                      to="/arena"
                      @click="isDropdownOpen = false"
                      class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-dark-emphasis"
                    >
                      <i class="bi bi-trophy-fill text-warning fs-6"></i>
                      <span>Đấu Trường Quiz</span>
                    </router-link>

                    <router-link
                      v-if="authStore.isStaff || authStore.isAdmin"
                      to="/admin"
                      @click="isDropdownOpen = false"
                      class="dropdown-item fs-7 py-2 d-flex align-items-center gap-2 text-danger fw-semibold"
                    >
                      <i class="bi bi-shield-lock-fill text-danger fs-6"></i>
                      <span>Khu vực Quản trị & Điều phối</span>
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
    <main class="flex-grow-1 user-main-content">
      <router-view />
    </main>

    <!-- Footer (Ẩn trên mobile để nhường không gian cho bottom nav) -->
    <footer class="border-top py-4 text-center text-muted fs-8 mt-5 d-none d-md-block" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'">
      <div class="container">
        <div><strong>OXVOCAB™ - Nền Tảng Học Từ Vựng & Lộ Trình Tiếng Anh Toàn Diện</strong> • Full-Stack Vue 3 + Laravel API</div>
        <div class="text-secondary mt-1">Lộ trình 3000 từ vựng • 25 Chủ đề IELTS • Học Cụm từ • 12 Thì Ngữ pháp • 44 Âm IPA • Trắc nghiệm & Luyện nói AI</div>
      </div>
    </footer>

    <!-- MOBILE BOTTOM NAVIGATION (Phong cách Ứng dụng Di Động) -->
    <nav class="mobile-bottom-nav d-flex d-md-none border-top position-fixed bottom-0 start-0 w-100 z-3 align-items-center justify-content-around">
      <router-link to="/" class="mobile-nav-item" exact-active-class="active">
        <i class="bi bi-house-door-fill"></i>
        <span>Trang chủ</span>
        <span v-if="$route.path === '/'" class="mobile-nav-active-dot"></span>
      </router-link>

      <router-link to="/curriculum" class="mobile-nav-item" active-class="active">
        <i class="bi bi-signpost-split-fill"></i>
        <span>Lộ trình</span>
        <span v-if="$route.path.startsWith('/curriculum')" class="mobile-nav-active-dot"></span>
      </router-link>

      <router-link to="/daily" class="mobile-nav-item" active-class="active">
        <i class="bi bi-calendar-check-fill"></i>
        <span>Học ngày</span>
        <span v-if="$route.path.startsWith('/daily')" class="mobile-nav-active-dot"></span>
      </router-link>

      <router-link to="/arena" class="mobile-nav-item" active-class="active">
        <i class="bi bi-trophy-fill"></i>
        <span>Đấu trường</span>
        <span v-if="$route.path.startsWith('/arena')" class="mobile-nav-active-dot"></span>
      </router-link>

      <button @click="toggleMobileDrawer" class="mobile-nav-item border-0 bg-transparent" :class="{ 'active': isMobileDrawerOpen }">
        <i class="bi bi-grid-fill"></i>
        <span>Menu</span>
        <span v-if="isMobileDrawerOpen" class="mobile-nav-active-dot"></span>
      </button>
    </nav>

    <!-- MOBILE DRAWER / OFFCANVAS (Menu trượt toàn diện trên điện thoại) -->
    <div v-if="isMobileDrawerOpen" class="mobile-drawer-overlay d-md-none" @click="closeMobileDrawer"></div>
    <div
      v-if="isMobileDrawerOpen"
      class="mobile-drawer-content d-md-none p-3 d-flex flex-column"
      :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark'"
    >
      <!-- Drawer Header -->
      <div class="d-flex justify-content-between align-items-center pb-2.5 border-bottom border-light-subtle mb-3">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-black shadow-xs" style="width: 32px; height: 32px; background: linear-gradient(135deg, #00b074 0%, #064e40 100%);">
            <span class="fs-6">Ox</span>
          </div>
          <span class="fw-black fs-6 text-teal text-uppercase">OxVocab Menu</span>
        </div>
        <button @click="closeMobileDrawer" class="btn btn-sm btn-outline-secondary rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; line-height: 1;">
          <i class="bi bi-x-lg fs-8"></i>
        </button>
      </div>

      <!-- User Profile Card in Drawer (if logged in) -->
      <div v-if="authStore.isAuthenticated" class="card p-2.5 rounded-3 mb-3 bg-card-custom border shadow-2xs">
        <div class="d-flex align-items-center gap-2 mb-2">
          <img :src="authStore.userAvatar" class="rounded-circle border border-2 border-teal" style="width: 38px; height: 38px; object-fit: cover;" />
          <div class="min-w-0 flex-grow-1">
            <div class="fw-bold fs-7 text-truncate">{{ authStore.userName }}</div>
            <div class="text-muted fs-8 text-truncate">{{ authStore.userEmail }}</div>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-1.5 border-top border-light-subtle">
          <span class="badge bg-warning text-dark fs-8 rounded-pill">⚡ {{ authStore.user?.xp_points || 0 }} XP</span>
          <span class="badge bg-danger text-white fs-8 rounded-pill">🔥 {{ authStore.user?.streak_days || 0 }} ngày</span>
          <span class="badge bg-teal text-white fs-8 rounded-pill">{{ authStore.isAdmin ? 'Admin' : 'Học viên' }}</span>
        </div>
      </div>

      <!-- Guest Login / Register CTA in Drawer -->
      <div v-else class="d-flex gap-2 mb-3">
        <router-link to="/login" @click="closeMobileDrawer" class="btn btn-sm btn-outline-secondary flex-fill rounded-pill py-1.5 fs-8 fw-bold">
          Đăng nhập
        </router-link>
        <router-link to="/register" @click="closeMobileDrawer" class="btn btn-sm btn-teal text-white flex-fill rounded-pill py-1.5 fs-8 fw-bold">
          Bắt đầu ngay
        </router-link>
      </div>

      <!-- All Features List -->
      <div class="flex-grow-1 overflow-y-auto no-scrollbar py-1">
        <div class="fs-8 fw-bold text-muted text-uppercase mb-2">Chức năng học tập</div>
        <div class="d-flex flex-column gap-1 mb-3">
          <router-link
            v-for="item in NAV_CATALOG"
            :key="item.id"
            :to="item.to"
            @click="closeMobileDrawer"
            class="d-flex align-items-center justify-content-between p-2 rounded-3 text-decoration-none transition-all"
            :class="authStore.themeMode === 'dark' ? 'text-white' : 'text-dark'"
            style="background: rgba(125, 125, 125, 0.05);"
          >
            <div class="d-flex align-items-center gap-2 fs-7">
              <i :class="[item.icon, item.iconClass, 'fs-6']"></i>
              <span class="fw-semibold">{{ item.fullLabel || item.label }}</span>
            </div>
            <span v-if="item.badge" class="badge bg-warning text-dark fs-8 rounded-pill py-0.5">{{ item.badge }}</span>
          </router-link>
        </div>

        <div class="fs-8 fw-bold text-muted text-uppercase mb-2">Hệ thống & Tài khoản</div>
        <div class="d-flex flex-column gap-1">
          <router-link to="/profile" @click="closeMobileDrawer" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none fs-7 text-dark-emphasis">
            <i class="bi bi-person-circle text-primary fs-6"></i>
            <span>Hồ sơ & Cài đặt Giọng đọc</span>
          </router-link>

          <router-link v-if="authStore.isAdmin || authStore.isStaff" to="/admin" @click="closeMobileDrawer" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none fs-7 text-danger fw-bold">
            <i class="bi bi-shield-lock-fill text-danger fs-6"></i>
            <span>Trang Quản trị Admin</span>
          </router-link>

          <button @click="authStore.toggleTheme" class="d-flex align-items-center justify-content-between p-2 rounded-3 border-0 bg-transparent text-start w-100 fs-7 text-dark-emphasis">
            <div class="d-flex align-items-center gap-2">
              <i class="bi fs-6" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
              <span>Giao diện {{ authStore.themeMode === 'light' ? 'Tối' : 'Sáng' }}</span>
            </div>
            <span class="badge bg-body-tertiary text-secondary fs-8 rounded-pill">Đổi</span>
          </button>

          <button v-if="authStore.isAuthenticated" @click="handleDrawerLogout" class="d-flex align-items-center gap-2 p-2 rounded-3 border-0 bg-transparent text-danger text-start w-100 fs-7 mt-1">
            <i class="bi bi-box-arrow-right text-danger fs-6"></i>
            <span>Đăng xuất tài khoản</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Global Login Required Gate Modal -->
    <LoginRequiredModal />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import LoginRequiredModal from '@/components/LoginRequiredModal.vue'

const authStore = useAuthStore()
const router = useRouter()

const isDropdownOpen = ref(false)
const dropdownRef = ref(null)

const isMoreMenuOpen = ref(false)
const moreMenuRef = ref(null)

const isMobileDrawerOpen = ref(false)
const toggleMobileDrawer = () => {
  isMobileDrawerOpen.value = !isMobileDrawerOpen.value
}
const closeMobileDrawer = () => {
  isMobileDrawerOpen.value = false
}
const handleDrawerLogout = async () => {
  closeMobileDrawer()
  await authStore.logout()
  router.push('/login')
}

const NAV_CATALOG = [
  { id: 'curriculum', label: 'Lộ trình', fullLabel: 'Lộ trình (25 Chủ đề)', to: '/curriculum', icon: 'bi bi-signpost-split-fill', iconClass: 'icon-curriculum', badge: '25 CHỦ ĐỀ' },
  { id: 'daily', label: 'Học theo Ngày', fullLabel: 'Học theo Ngày', to: '/daily', icon: 'bi bi-calendar-check-fill', iconClass: 'icon-daily' },
  { id: 'grammar', label: 'Ngữ pháp', fullLabel: 'Ngữ pháp (12 Thì & Bài tập)', to: '/grammar', icon: 'bi bi-journal-bookmark-fill', iconClass: 'icon-grammar' },
  { id: 'phrasal_verbs', label: 'Cụm từ', fullLabel: 'Cụm từ (Phrasal Verbs & 1000 Cụm từ)', to: '/phrasal-verbs', icon: 'bi bi-puzzle-fill', iconClass: 'icon-phrasal' },
  { id: 'dictionary', label: 'Tra cứu', fullLabel: 'Tra cứu 3000 từ', to: '/dictionary', icon: 'bi bi-search-heart-fill', iconClass: 'icon-dict' },
  { id: 'ipa', label: '44 Âm IPA', fullLabel: 'Bảng 44 Âm IPA', to: '/ipa', icon: 'bi bi-soundwave', iconClass: 'icon-ipa' },
  { id: 'tips', label: 'Mẹo học', fullLabel: 'Mẹo Học Tiếng Anh Hiệu Quả', to: '/tips', icon: 'bi bi-lightbulb-fill text-warning', iconClass: '' },
  { id: 'tree', label: 'Cây tri thức', fullLabel: 'Vườn Cây Tri Thức', to: '/tree', icon: 'bi bi-tree-fill text-success', iconClass: '' },
  { id: 'stats', label: 'Năng suất', fullLabel: 'Báo Cáo Năng Suất', to: '/stats', icon: 'bi bi-bar-chart-fill text-primary', iconClass: '' },
  { id: 'topics', label: 'Chủ đề', fullLabel: 'Chủ đề 3000 từ', to: '/topics', icon: 'bi bi-grid-1x2-fill text-secondary', iconClass: '' },
]

const visibleNavItems = computed(() => {
  const chosenIds = authStore.headerNavItems || ['curriculum', 'daily', 'grammar', 'phrasal_verbs']
  return chosenIds.map(id => NAV_CATALOG.find(item => item.id === id)).filter(Boolean)
})

const moreNavItems = computed(() => {
  const chosenIds = authStore.headerNavItems || ['curriculum', 'daily', 'grammar', 'phrasal_verbs']
  const moreOrder = authStore.moreNavOrder || []
  const unchosen = NAV_CATALOG.filter(item => !chosenIds.includes(item.id))
  if (moreOrder.length > 0) {
    return [...unchosen].sort((a, b) => {
      const idxA = moreOrder.indexOf(a.id)
      const idxB = moreOrder.indexOf(b.id)
      if (idxA === -1 && idxB === -1) return 0
      if (idxA === -1) return 1
      if (idxB === -1) return -1
      return idxA - idxB
    })
  }
  return unchosen
})

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const toggleMoreMenu = () => {
  isMoreMenuOpen.value = !isMoreMenuOpen.value
}

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isDropdownOpen.value = false
  }
  if (moreMenuRef.value && !moreMenuRef.value.contains(event.target)) {
    isMoreMenuOpen.value = false
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

<style scoped>
/* Navigation link base styles */
.nav-custom-link {
  color: #334155;
  transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
  font-weight: 600;
  border: 1px solid transparent;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.nav-custom-link:hover {
  background-color: rgba(0, 176, 116, 0.08);
  color: #00b074;
  transform: translateY(-1px);
}

/* Individual vibrant colorful icons when not active */
.icon-home { color: #2563eb !important; font-size: 15px; } /* Rich Royal Blue */
.icon-curriculum { color: #10b981 !important; font-size: 15px; } /* Vibrant Emerald */
.icon-daily { color: #f59e0b !important; font-size: 15px; } /* Amber Sun */
.icon-topics { color: #06b6d4 !important; font-size: 15px; } /* Vivid Cyan */
.icon-phrasal { color: #8b5cf6 !important; font-size: 15px; } /* Purple Magic */
.icon-grammar { color: #ec4899 !important; font-size: 15px; } /* Rose Pink */
.icon-ipa { color: #0d9488 !important; font-size: 15px; } /* Deep Teal */
.icon-dict { color: #ef4444 !important; font-size: 15px; } /* Coral Red */

/* ACTIVE STATE: Highlighting glowing pill with pure white text and white icon */
.nav-item-active {
  background: linear-gradient(135deg, #00b074 0%, #064e40 100%) !important;
  color: #ffffff !important;
  font-weight: 700 !important;
  box-shadow: 0 4px 14px rgba(0, 176, 116, 0.35) !important;
  border: 1px solid rgba(255, 255, 255, 0.25) !important;
  transform: translateY(-1px);
}

.nav-item-active i {
  color: #ffffff !important;
  filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.25));
}

.nav-item-active span {
  color: #ffffff !important;
}

.nav-item-active:hover {
  background: linear-gradient(135deg, #00c282 0%, #085a4a 100%) !important;
  color: #ffffff !important;
}

/* Dark mode adjustments */
:global([data-bs-theme="dark"]) .nav-custom-link,
.bg-dark .nav-custom-link {
  color: #cbd5e1;
}

:global([data-bs-theme="dark"]) .nav-custom-link:hover,
.bg-dark .nav-custom-link:hover {
  background-color: rgba(0, 176, 116, 0.16);
  color: #5eead4;
}
</style>
