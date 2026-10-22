<template>
  <div class="admin-layout min-vh-100 d-flex flex-column" :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-body-tertiary text-dark'">
    <!-- Top Header (Height 64px) matching design template -->
    <header class="h-16 border-bottom px-3 px-lg-4 d-flex align-items-center justify-content-between sticky-top z-3" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'" style="height: 64px;">
      <!-- Breadcrumb info -->
      <div class="d-flex align-items-center gap-2 fs-7">
        <router-link to="/" class="text-secondary text-decoration-none hover:text-teal">Trang chủ</router-link>
        <span class="text-secondary">/</span>
        <span class="fw-bold text-teal">Khu Vực Quản Trị Hệ Thống (Admin)</span>
      </div>

      <!-- Right Header Actions -->
      <div class="d-flex align-items-center gap-3">
        <!-- Return to Learner Portal Button -->
        <router-link to="/" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 shadow-sm d-flex align-items-center gap-1.5 fs-7">
          <i class="bi bi-box-arrow-up-right"></i>
          <span>Vào Cổng Học Viên</span>
        </router-link>

        <!-- Dark / Light theme toggle -->
        <button
          @click="authStore.toggleTheme"
          class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
          style="width: 36px; height: 36px;"
          :title="authStore.themeMode === 'light' ? 'Chế độ Tối' : 'Chế độ Sáng'"
        >
          <i class="bi" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
        </button>

        <div class="vr mx-1"></div>

        <!-- Admin Identity Pill & Logout -->
        <div class="d-flex align-items-center gap-2">
          <img
            :src="authStore.userAvatar"
            class="rounded-circle border border-2 border-teal"
            style="width: 32px; height: 32px; object-fit: cover;"
          />
          <div class="d-none d-md-flex flex-column">
            <span class="fw-bold fs-7 text-dark-emphasis leading-tight">{{ authStore.userName }}</span>
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 9px;">Quản trị viên Cấp cao</span>
          </div>

          <button @click="handleLogout" class="btn btn-sm btn-outline-danger rounded-circle p-1 ms-1" title="Đăng xuất khỏi Admin" style="width: 32px; height: 32px;">
            <i class="bi bi-box-arrow-right"></i>
          </button>
        </div>
      </div>
    </header>

    <div class="container-fluid flex-grow-1 p-0">
      <div class="row g-0 flex-nowrap">
        <!-- 1. LEFT SIDEBAR (Width 260px) -->
        <nav class="col-auto border-end p-3 shadow-sm d-flex flex-column justify-content-between" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'" style="min-height: calc(100vh - 64px); width: 260px;">
          <div>
            <!-- Brand in Sidebar -->
            <div class="d-flex align-items-center gap-2.5 mb-3 pb-3 border-bottom">
              <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%);">
                Ox
              </div>
              <div class="d-flex flex-column">
                <span class="fw-black fs-6 tracking-tight text-teal text-uppercase" style="letter-spacing: 0.5px;">OXFORD 3000™</span>
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
                to="/admin"
                class="admin-nav-link"
                exact-active-class="admin-nav-active"
              >
                <i class="bi bi-speedometer2 icon-admin-dash"></i>
                <span>Tổng quan Dashboard</span>
              </router-link>

              <router-link
                to="/admin/curriculum"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-signpost-split-fill icon-admin-curriculum"></i>
                <span>Lộ trình (25 Chủ đề)</span>
              </router-link>

              <router-link
                to="/admin/users"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-people-fill icon-admin-users"></i>
                <span>Học viên & Phân quyền</span>
              </router-link>

              <router-link
                to="/admin/vocabularies"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-book-half icon-admin-vocab"></i>
                <span>Kho 3000 từ vựng & AI</span>
              </router-link>

              <router-link
                to="/admin/phrasal-verbs"
                class="admin-nav-link"
                active-class="admin-nav-active"
              >
                <i class="bi bi-puzzle-fill icon-admin-phrases"></i>
                <span>Cụm từ & Phrasal Verbs</span>
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
        <main class="col p-3 p-md-4 p-lg-5 overflow-y-auto" style="min-height: calc(100vh - 64px);">
          <router-view />
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login?portal=admin')
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
