<template>
  <div class="admin-layout min-vh-100 d-flex flex-column" :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-body-tertiary text-dark'">
    <!-- Top Header (Height 64px) matching design template -->
    <header class="h-16 border-bottom px-3 px-lg-4 d-flex align-items-center justify-content-between sticky-top z-3" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'" style="height: 64px;">
      <!-- Left: Mobile Hamburger Toggle + Brand + Desktop Breadcrumb -->
      <div class="d-flex align-items-center gap-2 fs-7 min-w-0">
        <!-- Nút Menu Thụt Ra / Thụt Vào trên Điện Thoại -->
        <button
          @click="toggleMobileAdminSidebar"
          class="btn btn-sm btn-outline-teal rounded-3 p-1.5 d-lg-none d-flex align-items-center justify-content-center shrink-0"
          style="width: 38px; height: 38px;"
          title="Mở menu quản trị viên"
          aria-label="Mở menu quản trị viên"
        >
          <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Brand Icon & Label on Mobile -->
        <div class="d-flex align-items-center gap-2 shrink-0">
          <div class="rounded-2 d-flex align-items-center justify-content-center text-white fw-black shadow-2xs" style="width: 32px; height: 32px; background: linear-gradient(135deg, #00b074 0%, #064e40 100%); font-size: 13px;">
            Ox
          </div>
          <div class="d-flex flex-column d-md-none">
            <span class="fw-black fs-7 text-teal leading-none">OX ADMIN</span>
            <span class="text-muted fw-semibold" style="font-size: 8.5px;">Quản Trị Viên</span>
          </div>
        </div>

        <!-- Breadcrumb info (Desktop only) -->
        <div class="d-none d-md-flex align-items-center gap-2 fs-7 ps-2 border-start">
          <router-link to="/" class="text-secondary text-decoration-none hover:text-teal">Trang chủ</router-link>
          <span class="text-secondary">/</span>
          <span class="fw-bold text-teal">Khu Vực Quản Trị Hệ Thống (Admin)</span>
        </div>
      </div>

      <!-- Right Header Actions -->
      <div class="d-flex align-items-center gap-1.5 gap-md-3 shrink-0">
        <!-- Return to Learner Portal Button -->
        <router-link to="/" class="btn btn-sm btn-outline-teal rounded-pill px-2.5 px-md-3 py-1.5 shadow-sm d-flex align-items-center gap-1 fs-8 fs-md-7">
          <i class="bi bi-box-arrow-up-right"></i>
          <span class="d-none d-sm-inline">Vào Cổng Học Viên</span>
        </router-link>

        <!-- Dark / Light theme toggle -->
        <button
          @click="authStore.toggleTheme"
          class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
          style="width: 34px; height: 34px;"
          :title="authStore.themeMode === 'light' ? 'Chế độ Tối' : 'Chế độ Sáng'"
        >
          <i class="bi" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
        </button>

        <div class="vr mx-0.5 d-none d-sm-block"></div>

        <!-- Admin Identity & Auth Header Widget -->
        <AuthHeaderWidget :isAdminView="true" />
      </div>
    </header>

    <!-- MOBILE SLIDE-IN ADMIN DRAWER ("Thụt ra - Thụt vào") -->
    <!-- Backdrop Overlay -->
    <div
      v-if="isMobileAdminSidebarOpen"
      class="admin-drawer-overlay d-lg-none"
      @click="closeMobileAdminSidebar"
    ></div>

    <!-- Drawer Content Panel -->
    <aside
      class="admin-drawer-content d-lg-none d-flex flex-column justify-content-between p-3 shadow-lg"
      :class="[
        isMobileAdminSidebarOpen ? 'show' : '',
        authStore.themeMode === 'dark' ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'
      ]"
    >
      <div>
        <!-- Drawer Header with Logo & Prominent Close / Retract Button -->
        <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-black shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #00b074 0%, #064e40 100%);">
              Ox
            </div>
            <div class="d-flex flex-column">
              <span class="fw-black fs-6 tracking-tight text-teal text-uppercase leading-tight">
                OXVOCAB<span class="text-success" style="font-size: 10px; vertical-align: top;">™</span>
              </span>
              <span class="text-muted fw-bold" style="font-size: 9px; letter-spacing: 0.3px;">QUẢN TRỊ VIÊN HỆ THỐNG</span>
            </div>
          </div>

          <!-- NÚT THỤT LẠI / ĐÓNG MENU DÀNH CHO MOBILE -->
          <button
            @click="closeMobileAdminSidebar"
            class="btn btn-sm btn-danger text-white rounded-pill px-2.5 py-1 fw-bold fs-8 d-flex align-items-center gap-1 shadow-sm"
            title="Thụt lại menu"
          >
            <i class="bi bi-x-lg fs-8"></i>
            <span>Thụt lại</span>
          </button>
        </div>

        <!-- Admin Profile Info Card -->
        <div class="p-2.5 rounded-3 mb-3 border d-flex align-items-center gap-2.5" :class="authStore.themeMode === 'dark' ? 'bg-secondary bg-opacity-10 border-secondary' : 'bg-light border-light-subtle'">
          <img
            :src="authStore.userAvatar"
            class="rounded-circle border border-2 border-teal shrink-0"
            style="width: 40px; height: 40px; object-fit: cover;"
          />
          <div class="min-w-0 flex-grow-1">
            <div class="fw-bold fs-7 text-truncate leading-tight">{{ authStore.userName }}</div>
            <div class="text-muted fs-8 text-truncate">{{ authStore.user?.email }}</div>
            <div class="mt-1">
              <span class="badge rounded-pill" :class="roleBadgeClass" style="font-size: 9px;">{{ roleDisplayName }}</span>
            </div>
          </div>
        </div>

        <!-- Status Indicator -->
        <div class="p-2 rounded-3 mb-3 d-flex align-items-center justify-content-between border" style="background-color: #E6F7F3; border-color: #A3E7D8;">
          <div class="d-flex align-items-center gap-1.5">
            <span class="rounded-circle bg-success animate-mic-pulse" style="width: 8px; height: 8px;"></span>
            <span class="fw-bold fs-8" style="color: #087F75;">Máy chủ Sẵn sàng</span>
          </div>
          <span class="badge bg-success text-white" style="font-size: 9px;">Hoạt động</span>
        </div>

        <!-- Navigation Links (Full 7 Modules) -->
        <div class="text-uppercase text-muted fs-8 fw-bold mb-2 px-1" style="letter-spacing: 0.5px;">Điều Hành Dữ Liệu</div>
        <div class="d-flex flex-column gap-1.5">
          <router-link
            v-if="authStore.canViewReports || authStore.isAdmin"
            to="/admin"
            class="admin-nav-link"
            exact-active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-speedometer2 icon-admin-dash"></i>
            <span>Tổng quan Dashboard</span>
          </router-link>

          <router-link
            v-if="authStore.canManageCurriculum || authStore.isAdmin"
            to="/admin/curriculum"
            class="admin-nav-link"
            active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-signpost-split-fill icon-admin-curriculum"></i>
            <span>Lộ trình (25 Chủ đề)</span>
          </router-link>

          <router-link
            v-if="authStore.canViewStudents || authStore.canManageUsers || authStore.isAdmin"
            to="/admin/users"
            class="admin-nav-link"
            active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-people-fill icon-admin-users"></i>
            <span>Quản lý Học viên</span>
          </router-link>

          <router-link
            v-if="authStore.canManageRoles || authStore.isAdmin"
            to="/admin/roles"
            class="admin-nav-link"
            active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-shield-lock-fill icon-admin-roles"></i>
            <span>Phân quyền chức vụ</span>
          </router-link>

          <router-link
            v-if="authStore.canManageVocab || authStore.isAdmin"
            to="/admin/vocabularies"
            class="admin-nav-link"
            active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-book-half icon-admin-vocab"></i>
            <span>Kho 3000 từ vựng & AI</span>
          </router-link>

          <router-link
            v-if="authStore.canManageVocab || authStore.isAdmin"
            to="/admin/cefr"
            class="admin-nav-link"
            active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-award-fill text-success"></i>
            <span>Từ vựng CEFR (A1-C2)</span>
          </router-link>

          <router-link
            v-if="authStore.canManageCurriculum || authStore.isAdmin"
            to="/admin/grammars"
            class="admin-nav-link"
            active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-journal-bookmark-fill text-danger"></i>
            <span>12 Thì Ngữ pháp</span>
          </router-link>

          <router-link
            v-if="authStore.canManagePhrases || authStore.isAdmin"
            to="/admin/phrasal-verbs"
            class="admin-nav-link"
            active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-puzzle-fill icon-admin-phrases"></i>
            <span>Cụm từ & Phrasal Verbs</span>
          </router-link>

          <router-link
            v-if="authStore.canManageArena || authStore.isAdmin"
            to="/admin/arena"
            class="admin-nav-link"
            active-class="admin-nav-active"
            @click="closeMobileAdminSidebar"
          >
            <i class="bi bi-trophy-fill text-warning"></i>
            <span>Quản lý Đấu trường</span>
          </router-link>
        </div>
      </div>

      <!-- Drawer Bottom Section -->
      <div class="pt-3 border-top mt-3">
        <router-link to="/" class="admin-nav-link mb-1 text-secondary" @click="closeMobileAdminSidebar">
          <i class="bi bi-box-arrow-left text-secondary"></i>
          <span>Vào Cổng Học Viên</span>
        </router-link>

        <button
          @click="authStore.toggleTheme"
          class="admin-nav-link mb-1 text-secondary w-100 text-start border-0 bg-transparent"
        >
          <i class="bi" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
          <span>Giao diện: {{ authStore.themeMode === 'light' ? 'Chuyển sang Tối' : 'Chuyển sang Sáng' }}</span>
        </button>

        <button @click="handleLogout" class="admin-nav-link text-danger w-100 text-start border-0 bg-transparent">
          <i class="bi bi-power text-danger"></i>
          <span>Đăng xuất Quản trị</span>
        </button>
      </div>
    </aside>

    <div class="container-fluid flex-grow-1 p-0">
      <div class="row g-0 flex-nowrap">
        <!-- 1. PERMANENT DESKTOP SIDEBAR (Width 260px, chỉ hiện trên PC / Laptop lớn >= 992px) -->
        <nav class="col-auto border-end p-3 shadow-sm d-none d-lg-flex flex-column justify-content-between" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'" style="min-height: calc(100vh - 64px); width: 260px;">
          <div>
            <!-- Brand in Sidebar -->
            <div class="d-flex align-items-center gap-2.5 mb-3 pb-3 border-bottom">
              <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-black shadow-sm" style="width: 42px; height: 42px; background: linear-gradient(135deg, #00b074 0%, #064e40 100%);">
                Ox
              </div>
              <div class="d-flex flex-column">
                <span class="fw-black fs-5 tracking-tight text-teal text-uppercase leading-tight" style="letter-spacing: 0.5px;">
                  OXVOCAB<span class="text-success" style="font-size: 11px; vertical-align: top;">™</span>
                </span>
                <span class="text-muted fw-semibold" style="font-size: 10px; letter-spacing: 0.3px;">HỆ THỐNG QUẢN TRỊ VIÊN</span>
              </div>
            </div>

            <!-- Admin Status Indicator with Pulsing Dot -->
            <div class="p-2.5 rounded-3 mb-3 d-flex align-items-center justify-content-between border" style="background-color: #E6F7F3; border-color: #A3E7D8;">
              <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle bg-success animate-mic-pulse" style="width: 8px; height: 8px;"></span>
                <span class="fw-bold fs-8" style="color: #087F75;">Máy chủ Sẵn sàng</span>
              </div>
              <span class="badge bg-success text-white" style="font-size: 10px;">Hoạt động</span>
            </div>

            <!-- Navigation Links -->
            <div class="text-uppercase text-muted fs-8 fw-bold mb-2 px-2" style="letter-spacing: 0.5px;">Điều Hành Dữ Liệu</div>
            <div class="d-flex flex-column gap-1.5">
              <router-link
                v-if="authStore.canViewReports || authStore.isAdmin"
                to="/admin"
                class="admin-nav-link"
                exact-active-class="admin-nav-active"
              >
                <i class="bi bi-speedometer2 icon-admin-dash"></i>
                <span>Tổng quan Dashboard</span>
              </router-link>

              <router-link
                v-if="authStore.canManageCurriculum || authStore.isAdmin"
                to="/admin/curriculum"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-signpost-split-fill icon-admin-curriculum"></i>
                <span>Lộ trình (25 Chủ đề)</span>
              </router-link>

              <router-link
                v-if="authStore.canViewStudents || authStore.canManageUsers || authStore.isAdmin"
                to="/admin/users"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-people-fill icon-admin-users"></i>
                <span>Quản lý Học viên</span>
              </router-link>

              <router-link
                v-if="authStore.canManageRoles || authStore.isAdmin"
                to="/admin/roles"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-shield-lock-fill icon-admin-roles"></i>
                <span>Phân quyền chức vụ</span>
              </router-link>

              <router-link
                v-if="authStore.canManageVocab || authStore.isAdmin"
                to="/admin/vocabularies"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-book-half icon-admin-vocab"></i>
                <span>Kho 3000 từ vựng & AI</span>
              </router-link>

              <router-link
                v-if="authStore.canManageVocab || authStore.isAdmin"
                to="/admin/cefr"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-award-fill text-success"></i>
                <span>Từ vựng CEFR</span>
              </router-link>

              <router-link
                v-if="authStore.canManageCurriculum || authStore.isAdmin"
                to="/admin/grammars"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-journal-bookmark-fill text-danger"></i>
                <span>12 Thì Ngữ pháp</span>
              </router-link>

              <router-link
                v-if="authStore.canManagePhrases || authStore.isAdmin"
                to="/admin/phrasal-verbs"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-puzzle-fill icon-admin-phrases"></i>
                <span>Cụm từ & Phrasal Verbs</span>
              </router-link>

              <router-link
                v-if="authStore.canManageArena || authStore.isAdmin"
                to="/admin/arena"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-trophy-fill text-warning"></i>
                <span>Quản lý Đấu trường</span>
              </router-link>
            </div>
          </div>

          <!-- Bottom Sidebar Section -->
          <div class="pt-3 border-top mt-auto">
            <router-link to="/" class="admin-nav-link mb-1 text-secondary">
              <i class="bi bi-box-arrow-left text-secondary"></i>
              <span>Vào Cổng Học Viên</span>
            </router-link>
            <button @click="handleLogout" class="admin-nav-link text-danger w-100 text-start border-0 bg-transparent">
              <i class="bi bi-power text-danger"></i>
              <span>Đăng xuất Quản trị</span>
            </button>
          </div>
        </nav>

        <!-- 2. MAIN ADMIN CONTENT -->
        <main class="col p-2.5 p-sm-3 p-md-4 p-lg-5 overflow-y-auto" style="min-height: calc(100vh - 64px); max-width: 100vw; overflow-x: hidden;">
          <router-view />
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import AuthHeaderWidget from '@/components/AuthHeaderWidget.vue'

const authStore = useAuthStore()
const router = useRouter()

// Reactive state for Mobile Admin Drawer
const isMobileAdminSidebarOpen = ref(false)

const toggleMobileAdminSidebar = () => {
  isMobileAdminSidebarOpen.value = !isMobileAdminSidebarOpen.value
}

const closeMobileAdminSidebar = () => {
  isMobileAdminSidebarOpen.value = false
}

const roleDisplayName = computed(() => {
  const role = authStore.user?.role
  switch (role) {
    case 'admin': return 'Quản trị viên Cấp cao'
    case 'moderator': return 'Quản trị nội dung'
    case 'editor': return 'Biên tập viên'
    case 'tutor': return 'Trợ giảng / Giáo viên'
    case 'user': return 'Học viên tiêu chuẩn'
    default: return 'Thành viên'
  }
})

const roleBadgeClass = computed(() => {
  const role = authStore.user?.role
  switch (role) {
    case 'admin': return 'bg-danger-subtle text-danger border border-danger-subtle'
    case 'moderator': return 'bg-warning-subtle text-dark border border-warning-subtle'
    case 'editor': return 'bg-primary-subtle text-primary border border-primary-subtle'
    case 'tutor': return 'bg-info-subtle text-dark border border-info-subtle'
    default: return 'bg-secondary-subtle text-secondary border border-secondary-subtle'
  }
})

const handleLogout = async () => {
  closeMobileAdminSidebar()
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
/* Admin Sidebar link base styles */
.admin-nav-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 16px;
  border-radius: 10px;
  color: #334155;
  font-weight: 600;
  font-size: 0.9rem;
  text-decoration: none;
  transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid transparent;
}

.admin-nav-link:hover {
  background-color: rgba(0, 176, 116, 0.08);
  color: #00b074;
  transform: translateX(3px);
}

/* Individual vibrant colorful icons when not active */
.icon-admin-dash { color: #2563eb !important; font-size: 1.15rem; } /* Royal Blue */
.icon-admin-curriculum { color: #10b981 !important; font-size: 1.15rem; } /* Emerald */
.icon-admin-users { color: #f59e0b !important; font-size: 1.15rem; } /* Amber */
.icon-admin-roles { color: #dc2626 !important; font-size: 1.15rem; } /* Crimson Red */
.icon-admin-vocab { color: #8b5cf6 !important; font-size: 1.15rem; } /* Purple */
.icon-admin-phrases { color: #ec4899 !important; font-size: 1.15rem; } /* Pink */

/* ACTIVE STATE: Highlighting glowing pill with pure white text and white icon */
.admin-nav-active {
  background: linear-gradient(135deg, #00b074 0%, #064e40 100%) !important;
  color: #ffffff !important;
  font-weight: 700 !important;
  box-shadow: 0 4px 14px rgba(0, 176, 116, 0.35) !important;
  border-color: rgba(255, 255, 255, 0.25) !important;
  transform: translateX(3px);
}

.admin-nav-active i {
  color: #ffffff !important;
  filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.25));
}

.admin-nav-active span {
  color: #ffffff !important;
}

.admin-nav-active:hover {
  background: linear-gradient(135deg, #00c282 0%, #085a4a 100%) !important;
  color: #ffffff !important;
}

/* Drawer styles for mobile */
.admin-drawer-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  z-index: 1060;
  animation: fadeIn 0.2s ease-out;
}

.admin-drawer-content {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 86vw;
  max-width: 320px;
  height: 100vh;
  z-index: 1065;
  overflow-y: auto;
  border-right: 1px solid rgba(0, 0, 0, 0.1);
  transform: translateX(-100%);
  transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 8px 0 32px rgba(0, 0, 0, 0.2);
}

.admin-drawer-content.show {
  transform: translateX(0);
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Dark mode adjustments */
:global([data-bs-theme="dark"]) .admin-nav-link,
.bg-dark .admin-nav-link {
  color: #cbd5e1;
}

:global([data-bs-theme="dark"]) .admin-nav-link:hover,
.bg-dark .admin-nav-link:hover {
  background-color: rgba(0, 176, 116, 0.16);
  color: #5eead4;
}
</style>


<style scoped>
/* Admin Sidebar link base styles */
.admin-nav-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 16px;
  border-radius: 10px;
  color: #334155;
  font-weight: 600;
  font-size: 0.9rem;
  text-decoration: none;
  transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid transparent;
}

.admin-nav-link:hover {
  background-color: rgba(0, 176, 116, 0.08);
  color: #00b074;
  transform: translateX(3px);
}

/* Individual vibrant colorful icons when not active */
.icon-admin-dash { color: #2563eb !important; font-size: 1.15rem; } /* Royal Blue */
.icon-admin-curriculum { color: #10b981 !important; font-size: 1.15rem; } /* Emerald */
.icon-admin-users { color: #f59e0b !important; font-size: 1.15rem; } /* Amber */
.icon-admin-roles { color: #dc2626 !important; font-size: 1.15rem; } /* Crimson Red */
.icon-admin-vocab { color: #8b5cf6 !important; font-size: 1.15rem; } /* Purple */
.icon-admin-phrases { color: #ec4899 !important; font-size: 1.15rem; } /* Pink */

/* ACTIVE STATE: Highlighting glowing pill with pure white text and white icon */
.admin-nav-active {
  background: linear-gradient(135deg, #00b074 0%, #064e40 100%) !important;
  color: #ffffff !important;
  font-weight: 700 !important;
  box-shadow: 0 4px 14px rgba(0, 176, 116, 0.35) !important;
  border-color: rgba(255, 255, 255, 0.25) !important;
  transform: translateX(3px);
}

.admin-nav-active i {
  color: #ffffff !important;
  filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.25));
}

.admin-nav-active span {
  color: #ffffff !important;
}

.admin-nav-active:hover {
  background: linear-gradient(135deg, #00c282 0%, #085a4a 100%) !important;
  color: #ffffff !important;
}

/* Dark mode adjustments */
:global([data-bs-theme="dark"]) .admin-nav-link,
.bg-dark .admin-nav-link {
  color: #cbd5e1;
}

:global([data-bs-theme="dark"]) .admin-nav-link:hover,
.bg-dark .admin-nav-link:hover {
  background-color: rgba(0, 176, 116, 0.16);
  color: #5eead4;
}
</style>
