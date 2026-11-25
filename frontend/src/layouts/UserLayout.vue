<template>
  <div class="user-layout min-vh-100 d-flex flex-column">
    <!-- 3-ZONE ABSOLUTE-CENTER HEADER NAVBAR -->
    <nav class="ox-header-navbar sticky-top">
      <div class="ox-header-grid">
        <!-- 1. LEFT ZONE: Brand Logo Header (Logo ngang có chữ, nền trong suốt) -->
        <div class="ox-header-left">
          <router-link to="/" class="ox-logo-wrapper" title="OXVOCAB - Quay về trang chủ">
            <img
              src="/images/branding/logo-header-transparent.png"
              alt="OXVOCAB - Học Từ Vựng & Lộ Trình Thông Minh"
              class="brand-header-logo"
              loading="eager"
            />
          </router-link>
        </div>

        <!-- 2. CENTER ZONE: CĂN GIỮA TUYỆT ĐỐI (Chữ trơn không icon, khoảng cách thoáng rộng) -->
        <div class="ox-header-center d-none d-lg-flex">
          <nav class="ox-nav-menu">
            <!-- 1. Trang chủ (Cố định) -->
            <router-link to="/" class="ox-nav-link" exact-active-class="active">
              <span>Trang chủ</span>
            </router-link>

            <!-- 2..6 Các mục điều hướng tùy chỉnh đồng bộ từ Hồ sơ (1..5 mục) -->
            <router-link
              v-for="item in visibleNavItems"
              :key="item.id"
              :to="item.to"
              class="ox-nav-link"
              active-class="active"
            >
              <span>{{ item.label }}</span>
            </router-link>

            <!-- 7. Xem thêm Dropdown (Tất cả mục còn lại theo thứ tự đã sắp xếp, Mẹo học cuối cùng) -->
            <div class="ox-nav-item" ref="moreMenuRef">
              <button
                @click="toggleMoreMenu"
                class="ox-nav-link"
                :class="{ 'active': isMoreMenuOpen || isMoreMenuActive }"
                type="button"
                :aria-expanded="isMoreMenuOpen"
              >
                <span>Xem thêm</span>
                <i class="bi bi-chevron-down ox-chevron-icon" :class="{ 'rotated': isMoreMenuOpen }"></i>
              </button>

              <Transition name="ox-dropdown">
                <div v-if="isMoreMenuOpen" class="ox-dropdown-pane shadow-lg">
                  <router-link
                    v-for="mItem in moreNavItems"
                    :key="mItem.id"
                    :to="mItem.to"
                    class="ox-dropdown-item"
                    active-class="active"
                    @click="isMoreMenuOpen = false"
                  >
                    <div class="ox-dropdown-item-title">{{ mItem.label }}</div>
                    <div class="ox-dropdown-item-desc">{{ mItem.desc }}</div>
                  </router-link>
                </div>
              </Transition>
            </div>
          </nav>
        </div>

        <!-- 3. RIGHT ZONE: BỘ THẺ ÔN TẬP (BADGE SỐ 4 ĐỎ) + A11Y + THEME TOGGLE + DIVIDER + AUTH WIDGET -->
        <div class="ox-header-right">
          <!-- Nút "Bộ thẻ ôn tập" (Icon tròn thanh lịch, có badge số 4 đỏ nổi bật giống Ảnh 1 & Ảnh 2) -->
          <router-link
            to="/review"
            class="ox-icon-btn"
            title="Bộ thẻ ôn tập (Spaced Repetition)"
          >
            <i class="bi bi-collection-play-fill text-teal fs-6"></i>
            <span v-if="dueCardsCount > 0" class="ox-deck-badge">{{ dueCardsCount }}</span>
          </router-link>

          <!-- BỘ LỌC HIỂN THỊ & CÀI ĐẶT CHUẨN QUỐC TẾ (Độ sáng, Độ tương phản, Độ ấm, Độ xám) -->
          <div class="position-relative" ref="a11yMenuRef">
            <button
              @click="toggleA11yMenu"
              class="ox-icon-btn"
              title="Bộ lọc màn hình & Cài đặt chuẩn quốc tế"
              type="button"
            >
              <i class="bi bi-sliders text-teal"></i>
            </button>

            <div
              v-if="isA11yOpen"
              class="dropdown-menu show position-absolute end-0 mt-2 shadow-lg border rounded-4 p-3 z-3"
              :class="authStore.themeMode === 'dark' ? 'dropdown-menu-dark bg-dark border-secondary' : 'bg-white border-light-subtle'"
              style="min-width: 300px;"
            >
              <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2.5">
                <div class="d-flex align-items-center gap-1.5">
                  <i class="bi bi-sliders text-teal fs-6"></i>
                  <span class="fw-bold fs-8 text-teal text-uppercase">Bộ Lọc & Chuẩn Quốc Tế</span>
                </div>
                <button @click="isA11yOpen = false" class="btn-close fs-9"></button>
              </div>

              <!-- Cỡ chữ hiển thị -->
              <div class="mb-3">
                <label class="fs-9 fw-bold text-muted text-uppercase d-block mb-1">Cỡ chữ hiển thị</label>
                <div class="btn-group btn-group-sm w-100 p-0.5 rounded-pill bg-body-tertiary border">
                  <button @click="setFontSize('sm')" class="btn btn-sm rounded-pill fs-9 py-1 fw-bold" :class="currentFontSize === 'sm' ? 'btn-teal text-white' : 'btn-light'">A- Nhỏ</button>
                  <button @click="setFontSize('md')" class="btn btn-sm rounded-pill fs-9 py-1 fw-bold" :class="currentFontSize === 'md' ? 'btn-teal text-white' : 'btn-light'">Chuẩn</button>
                  <button @click="setFontSize('lg')" class="btn btn-sm rounded-pill fs-9 py-1 fw-bold" :class="currentFontSize === 'lg' ? 'btn-teal text-white' : 'btn-light'">A+ Lớn</button>
                </div>
              </div>

              <!-- Giọng đọc phát âm -->
              <div class="mb-3">
                <label class="fs-9 fw-bold text-muted text-uppercase d-block mb-1">Giọng đọc tiếng Anh</label>
                <div class="btn-group btn-group-sm w-100 p-0.5 rounded-pill bg-body-tertiary border">
                  <button @click="setVoiceAccent('US')" class="btn btn-sm rounded-pill fs-9 py-1 fw-bold" :class="currentAccent === 'US' ? 'btn-teal text-white' : 'btn-light'">🇺🇸 US (Nữ - Chuẩn)</button>
                  <button @click="setVoiceAccent('UK')" class="btn btn-sm rounded-pill fs-9 py-1 fw-bold" :class="currentAccent === 'UK' ? 'btn-teal text-white' : 'btn-light'">🇬🇧 UK (Anh - Anh)</button>
                </div>
              </div>

              <div class="border-top pt-2.5 mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fs-9 fw-bold text-muted text-uppercase">Bộ lọc màn hình chuyên sâu</span>
                  <button @click="resetDisplayFilters" class="btn btn-link btn-sm p-0 fs-9 text-teal text-decoration-none fw-bold">
                    <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                  </button>
                </div>

                <!-- 1. Độ sáng (Brightness: 70 - 130%) -->
                <div class="mb-2">
                  <div class="d-flex justify-content-between align-items-center fs-9 mb-0.5">
                    <span class="fw-semibold">☀️ Độ sáng</span>
                    <span class="badge bg-secondary-subtle text-secondary px-1.5 py-0.5 rounded-pill">{{ filterBrightness }}%</span>
                  </div>
                  <input
                    type="range"
                    min="70"
                    max="130"
                    step="2"
                    v-model.number="filterBrightness"
                    @input="applyDisplayFilters"
                    class="form-range form-range-sm"
                  />
                </div>

                <!-- 2. Độ tương phản (Contrast: 70 - 150%) -->
                <div class="mb-2">
                  <div class="d-flex justify-content-between align-items-center fs-9 mb-0.5">
                    <span class="fw-semibold">🌓 Độ tương phản</span>
                    <span class="badge bg-secondary-subtle text-secondary px-1.5 py-0.5 rounded-pill">{{ filterContrast }}%</span>
                  </div>
                  <input
                    type="range"
                    min="70"
                    max="150"
                    step="2"
                    v-model.number="filterContrast"
                    @input="applyDisplayFilters"
                    class="form-range form-range-sm"
                  />
                </div>

                <!-- 3. Độ ấm / Lọc ánh sáng xanh (Warmth: 0 - 60%) -->
                <div class="mb-2">
                  <div class="d-flex justify-content-between align-items-center fs-9 mb-0.5">
                    <span class="fw-semibold">🍵 Độ ấm (Ánh sáng vàng)</span>
                    <span class="badge bg-secondary-subtle text-secondary px-1.5 py-0.5 rounded-pill">{{ filterWarmth }}%</span>
                  </div>
                  <input
                    type="range"
                    min="0"
                    max="60"
                    step="2"
                    v-model.number="filterWarmth"
                    @input="applyDisplayFilters"
                    class="form-range form-range-sm"
                  />
                </div>

                <!-- 4. Độ xám (Grayscale: 0 - 100%) -->
                <div>
                  <div class="d-flex justify-content-between align-items-center fs-9 mb-0.5">
                    <span class="fw-semibold">⚪ Độ xám (Tập trung đọc)</span>
                    <span class="badge bg-secondary-subtle text-secondary px-1.5 py-0.5 rounded-pill">{{ filterGrayscale }}%</span>
                  </div>
                  <input
                    type="range"
                    min="0"
                    max="100"
                    step="5"
                    v-model.number="filterGrayscale"
                    @input="applyDisplayFilters"
                    class="form-range form-range-sm"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Theme Toggle Button -->
          <button
            @click="authStore.toggleTheme"
            class="ox-icon-btn"
            :title="authStore.themeMode === 'light' ? 'Chuyển sang Chế độ Tối' : 'Chuyển sang Chế độ Sáng'"
            type="button"
          >
            <i class="bi" :class="authStore.themeMode === 'light' ? 'bi-moon-stars-fill text-warning' : 'bi-sun-fill text-warning'"></i>
          </button>

          <!-- Divider tinh tế ngăn cách tiện ích và User Profile giống Ảnh 1 -->
          <div class="ox-header-divider d-none d-sm-block"></div>

          <!-- AUTH HEADER WIDGET (Google Login Button & Avatar Dropdown) -->
          <AuthHeaderWidget />

          <!-- Mobile Hamburger Button (D-lg-none) -->
          <button
            class="ox-icon-btn d-lg-none"
            type="button"
            @click="toggleMobileDrawer"
            aria-label="Toggle navigation"
          >
            <i class="bi bi-list fs-5"></i>
          </button>
        </div>
      </div>
    </nav>

    <!-- Main View Outlet với hiệu ứng chuyển trang siêu mượt mà không delay -->
    <main class="flex-grow-1 user-main-content">
      <router-view v-slot="{ Component }">
        <transition name="page-fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Footer (Ẩn trên mobile để nhường không gian cho bottom nav) -->
    <footer class="border-top py-4 text-center text-muted fs-8 mt-5 d-none d-md-block" :class="authStore.themeMode === 'dark' ? 'bg-dark border-secondary' : 'bg-white border-light-subtle'">
      <div class="container">
        <div><strong>OXVOCAB™ - Nền Tảng Học Từ Vựng & Lộ Trình Tiếng Anh Toàn Diện</strong> • Full-Stack Vue 3 + Laravel API</div>
        <div class="text-secondary mt-1">Lộ trình 3000 từ vựng • 25 Chủ đề IELTS • Học Cụm từ • 12 Thì Ngữ pháp • 44 Âm IPA • Trắc nghiệm & Luyện nói AI</div>
      </div>
    </footer>

    <!-- STICKY MOBILE BOTTOM NAVIGATION (Height 58-64px, iOS safe area, active dots) -->
    <nav class="mobile-bottom-nav d-flex d-lg-none border-top position-fixed bottom-0 start-0 w-100 align-items-center justify-content-around">
      <!-- 1. Trang chủ -->
      <router-link to="/" class="mobile-nav-item" exact-active-class="active">
        <i class="bi bi-house-door-fill"></i>
        <span>Trang chủ</span>
        <span v-if="$route.path === '/'" class="mobile-nav-active-dot"></span>
      </router-link>

      <!-- 2. Lộ trình -->
      <router-link to="/curriculum" class="mobile-nav-item" active-class="active">
        <i class="bi bi-signpost-split-fill"></i>
        <span>Lộ trình</span>
        <span v-if="$route.path.startsWith('/curriculum')" class="mobile-nav-active-dot"></span>
      </router-link>

      <!-- 3. Cụm từ -->
      <router-link to="/phrasal-verbs" class="mobile-nav-item" active-class="active">
        <i class="bi bi-collection-play-fill"></i>
        <span>Cụm từ</span>
        <span v-if="$route.path.startsWith('/phrasal-verbs')" class="mobile-nav-active-dot"></span>
      </router-link>

      <!-- 4. Ôn tập -->
      <router-link to="/review" class="mobile-nav-item" active-class="active">
        <i class="bi bi-clock-history"></i>
        <span>Ôn tập</span>
        <span v-if="$route.path.startsWith('/review')" class="mobile-nav-active-dot"></span>
      </router-link>

      <!-- 5. Trợ lý AI Chat (Google Gemini) -->
      <button @click="triggerAiChat" class="mobile-nav-item border-0 bg-transparent text-center" title="Mở Gia sư AI Gemini">
        <div class="position-relative d-inline-block">
          <i class="bi bi-stars text-warning fs-5"></i>
          <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle animate-pulse" style="transform: translate(-20%, 0%) !important;"></span>
        </div>
        <span class="text-teal fw-bold">AI Chat</span>
      </button>

      <!-- 6. Menu Drawer -->
      <button @click="toggleMobileDrawer" class="mobile-nav-item border-0 bg-transparent" :class="{ 'active': isMobileDrawerOpen }" title="Menu toàn diện">
        <i class="bi bi-grid-fill"></i>
        <span>Menu</span>
        <span v-if="isMobileDrawerOpen" class="mobile-nav-active-dot"></span>
      </button>
    </nav>

    <!-- MOBILE DRAWER / OFFCANVAS (Menu trượt toàn diện trên điện thoại) -->
    <div v-if="isMobileDrawerOpen" class="mobile-drawer-overlay d-lg-none" @click="closeMobileDrawer"></div>
    <div
      v-if="isMobileDrawerOpen"
      class="mobile-drawer-content d-lg-none p-3 d-flex flex-column"
      :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark'"
    >
      <!-- Drawer Header: Có nút Đóng / Thụt lại menu cực kỳ to rõ -->
      <div class="d-flex justify-content-between align-items-center pb-2.5 border-bottom border-light-subtle mb-3">
        <div class="d-flex align-items-center gap-2">
          <img
            src="/images/branding/favicon-transparent.png"
            alt="OxVocab Icon"
            class="rounded-3 shadow-xs"
            width="36"
            height="36"
          />
          <div>
            <div class="fw-black fs-6 text-teal text-uppercase leading-none">OxVocab™</div>
            <div class="text-muted" style="font-size: 10px;">Menu Học Tập Di Động</div>
          </div>
        </div>
        <button
          @click="closeMobileDrawer"
          class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 d-flex align-items-center gap-1.5 fw-bold fs-8 shadow-xs"
          title="Đóng / Thụt lại menu"
        >
          <i class="bi bi-x-lg"></i>
          <span>Thụt lại</span>
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
          <span class="badge bg-warning text-dark fs-8 rounded-pill">⚡ {{ authStore.user?.xp_points ?? 0 }} XP</span>
          <span class="badge bg-danger text-white fs-8 rounded-pill">🔥 {{ authStore.user?.streak_days ?? 0 }} ngày</span>
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

      <!-- FULL TẤT CẢ CÁC MỤC HỌC TẬP (Liệt kê 100% đầy đủ mọi tính năng, bấm là thụt lại ngay) -->
      <div class="flex-grow-1 overflow-y-auto no-scrollbar py-1">
        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
          <span class="fs-8 fw-bold text-muted text-uppercase">Tất cả mục học tập ({{ MOBILE_FULL_NAV.length }})</span>
          <span class="badge bg-body-tertiary text-secondary fs-9 rounded-pill">Chạm để mở</span>
        </div>

        <div class="d-flex flex-column gap-1.5 mb-3">
          <router-link
            v-for="item in MOBILE_FULL_NAV"
            :key="item.id"
            :to="item.to"
            @click="closeMobileDrawer"
            class="d-flex align-items-center justify-content-between p-2 rounded-3 text-decoration-none transition-all mobile-drawer-link"
            :class="[
              authStore.themeMode === 'dark' ? 'text-white' : 'text-dark',
              $route.path === item.to || ($route.path.startsWith(item.to) && item.to !== '/') ? 'active-mobile-link' : ''
            ]"
            style="background: rgba(125, 125, 125, 0.05);"
          >
            <div class="d-flex align-items-center gap-2.5 fs-7 min-w-0">
              <div class="rounded-2 d-flex align-items-center justify-content-center shrink-0 shadow-2xs" :style="{ width: '32px', height: '32px', backgroundColor: item.color + '22', color: item.color }">
                <i :class="item.icon" class="fs-6"></i>
              </div>
              <div class="min-w-0">
                <div class="fw-bold fs-7 text-truncate">{{ item.label }}</div>
                <div class="text-muted fs-9 text-truncate" style="font-size: 11px;">{{ item.desc }}</div>
              </div>
            </div>
            <span v-if="item.badge" class="badge bg-warning text-dark fs-9 rounded-pill px-2 py-0.5 shrink-0 ms-1">{{ item.badge }}</span>
          </router-link>
        </div>

        <div class="fs-8 fw-bold text-muted text-uppercase mb-2 px-1">Cài đặt & Tài khoản</div>
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

          <!-- Nút Đăng nhập Google trong menu điện thoại nếu chưa đăng nhập -->
          <div v-else class="mt-2">
            <button
              @click="() => { closeMobileDrawer(); openGoogleLoginPopup(); }"
              class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 rounded-3 border shadow-xs"
              :class="authStore.themeMode === 'dark' ? 'btn-dark bg-dark-subtle border-secondary text-white' : 'btn-white bg-white border text-dark-emphasis'"
            >
              <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
              </svg>
              <span class="fs-7 fw-bold">Đăng nhập bằng Google</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Global Login Required Gate Modal -->
    <LoginRequiredModal />

    <!-- Global In-page Quick Lookup Tooltip (Bôi đen từ là tra ngay & lưu vào sổ tay) -->
    <QuickLookupPopup />

    <!-- Global 24/7 AI English Tutor Assistant Widget -->
    <AiAssistantModal />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useReviewStore } from '@/stores/reviewStore'
import { useGoogleAuth } from '@/composables/useGoogleAuth'
import { useToast } from '@/composables/useToast'
import AuthHeaderWidget from '@/components/AuthHeaderWidget.vue'
import LoginRequiredModal from '@/components/LoginRequiredModal.vue'
import QuickLookupPopup from '@/components/QuickLookupPopup.vue'
import AiAssistantModal from '@/components/AiAssistantModal.vue'
import { NAV_CATALOG, DEFAULT_HEADER_NAV_IDS, buildOrderedMoreItems } from '@/constants/navCatalog'

const authStore = useAuthStore()
const reviewStore = useReviewStore()
const router = useRouter()
const route = useRoute()
const toast = useToast()
const { openGoogleLoginPopup } = useGoogleAuth()

const dueCardsCount = computed(() => reviewStore.dueCardsCount || 0)

const visibleNavItems = computed(() => {
  const chosenIds = (authStore.headerNavItems && authStore.headerNavItems.length > 0)
    ? authStore.headerNavItems
    : DEFAULT_HEADER_NAV_IDS
  return chosenIds.map(id => NAV_CATALOG.find(item => item.id === id)).filter(Boolean)
})

const moreNavItems = computed(() => {
  const chosenIds = visibleNavItems.value.map(i => i.id)
  return buildOrderedMoreItems(chosenIds, authStore.moreNavOrder)
})

const isMoreMenuActive = computed(() => {
  return moreNavItems.value.some(item => route.path === item.to || (item.to !== '/' && route.path.startsWith(item.to)))
})

const isDropdownOpen = ref(false)
const dropdownRef = ref(null)

const isMoreMenuOpen = ref(false)
const moreMenuRef = ref(null)

const isA11yOpen = ref(false)
const a11yMenuRef = ref(null)
const toggleA11yMenu = () => {
  isA11yOpen.value = !isA11yOpen.value
}

// Accessibility & Accent State
const currentFontSize = ref(localStorage.getItem('oxford_font_size') || 'md')
const currentAccent = ref(localStorage.getItem('oxford_accent_pref') || 'US')

// Display Filters State (Độ sáng, Độ tương phản, Độ ấm, Độ xám)
const filterBrightness = ref(parseInt(localStorage.getItem('oxford_filter_brightness') || '100', 10))
const filterContrast = ref(parseInt(localStorage.getItem('oxford_filter_contrast') || '100', 10))
const filterWarmth = ref(parseInt(localStorage.getItem('oxford_filter_warmth') || '0', 10))
const filterGrayscale = ref(parseInt(localStorage.getItem('oxford_filter_grayscale') || '0', 10))

const applyDisplayFilters = () => {
  const el = document.getElementById('app') || document.documentElement
  el.style.filter = `brightness(${filterBrightness.value}%) contrast(${filterContrast.value}%) sepia(${filterWarmth.value}%) grayscale(${filterGrayscale.value}%)`
  localStorage.setItem('oxford_filter_brightness', filterBrightness.value)
  localStorage.setItem('oxford_filter_contrast', filterContrast.value)
  localStorage.setItem('oxford_filter_warmth', filterWarmth.value)
  localStorage.setItem('oxford_filter_grayscale', filterGrayscale.value)
}

const resetDisplayFilters = () => {
  filterBrightness.value = 100
  filterContrast.value = 100
  filterWarmth.value = 0
  filterGrayscale.value = 0
  applyDisplayFilters()
}

const setFontSize = (size) => {
  currentFontSize.value = size
  localStorage.setItem('oxford_font_size', size)
  document.documentElement.classList.remove('font-size-sm', 'font-size-md', 'font-size-lg')
  document.documentElement.classList.add(`font-size-${size}`)
}

const setVoiceAccent = (accent) => {
  currentAccent.value = accent
  localStorage.setItem('oxford_accent_pref', accent)
}

const isMobileDrawerOpen = ref(false)
const toggleMobileDrawer = () => {
  isMobileDrawerOpen.value = !isMobileDrawerOpen.value
}
const closeMobileDrawer = () => {
  isMobileDrawerOpen.value = false
}
const handleDrawerLogout = async () => {
  closeMobileDrawer()
  const prevName = authStore.userName || 'bạn'
  await authStore.logout()
  toast.info(`Tài khoản "${prevName}" đã đăng xuất an toàn.`, 'Đăng xuất thành công')
  router.push('/login')
}

// FULL TẤT CẢ CÁC MỤC HỌC TẬP CHO GIAO DIỆN DI ĐỘNG (Đồng bộ đầy đủ 100% tính năng, gồm Sổ tay từ vựng)
const MOBILE_FULL_NAV = [
  { id: 'home', label: 'Trang chủ', desc: 'Tổng quan & Lộ trình học thông minh', to: '/', icon: 'bi bi-house-door-fill', color: '#00b074' },
  { id: 'curriculum', label: 'Lộ trình', desc: '5.000 từ vựng & 10 giai đoạn kiểm tra', to: '/curriculum', icon: 'bi bi-signpost-split-fill', color: '#087F75' },
  { id: 'phrasal_verbs', label: 'Cụm từ', desc: 'Phrasal Verbs & 1000 cụm đàm thoại', to: '/phrasal-verbs', icon: 'bi bi-puzzle-fill', color: '#ec4899' },
  { id: 'dictation', label: 'Chép chính tả', desc: 'Luyện nghe & gõ chuẩn DailyDictation', to: '/dictation', icon: 'bi bi-headphones', color: '#0ea5e9' },
  { id: 'daily', label: 'Học theo ngày', desc: 'Flashcard 3D & 20 từ mỗi ngày', to: '/daily', icon: 'bi bi-calendar-check-fill', color: '#0d9488' },
  { id: 'arena', label: 'Đấu trường', desc: 'Thi đấu realtime & BXH liên server', to: '/arena', icon: 'bi bi-trophy-fill', color: '#eab308' },
  { id: 'grammar', label: 'Ngữ pháp', desc: '12 thì chuyên đề, dịch câu & bài tập', to: '/grammar', icon: 'bi bi-journal-bookmark-fill', color: '#6366f1' },
  { id: 'cefr', label: 'Từ vựng CEFR', desc: '4.922 từ vựng chuẩn Châu Âu 6 phần', to: '/cefr', icon: 'bi bi-award-fill', color: '#10b981' },
  { id: 'vocabulary_notebook', label: 'Sổ tay từ vựng', desc: 'Kho từ đã lưu & ghi chú cá nhân', to: '/vocabulary-notebook', icon: 'bi bi-journal-text', color: '#14b8a6' },
  { id: 'review', label: 'Ôn tập', desc: 'Bộ thẻ Spaced Repetition ôn tập ngắt quãng', to: '/review', icon: 'bi bi-clock-history', color: '#f59e0b' },
  { id: 'history', label: 'Lịch sử', desc: 'Lịch sử học tập & xem lại câu sai', to: '/history', icon: 'bi bi-journal-check', color: '#06b6d4' },
  { id: 'tree', label: 'Cây tri thức', desc: '30 cấp độ tiến hóa & nuôi mầm xanh', to: '/tree', icon: 'bi bi-tree-fill', color: '#16a34a' },
  { id: 'ipa', label: '44 Âm IPA', desc: 'Nguyên âm & phụ âm chuẩn quốc tế', to: '/ipa', icon: 'bi bi-soundwave', color: '#06b6d4' },
  { id: 'stats', label: 'Năng suất', desc: 'Thống kê tần suất & chuỗi ngày Streak', to: '/stats', icon: 'bi bi-bar-chart-fill', color: '#3b82f6' },
  { id: 'dictionary', label: 'Tra cứu', desc: 'Kho 3.351 từ & giải thích Gemini AI', to: '/dictionary', icon: 'bi bi-search-heart-fill', color: '#14b8a6' },
  { id: 'tips', label: 'Mẹo học', desc: 'Chiến thuật ghi nhớ đỉnh cao', to: '/tips', icon: 'bi bi-lightbulb-fill', color: '#f59e0b' },
]

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
  if (a11yMenuRef.value && !a11yMenuRef.value.contains(event.target)) {
    isA11yOpen.value = false
  }
}

const handleLogout = async () => {
  isDropdownOpen.value = false
  await authStore.logout()
  router.push('/login')
}

const triggerAiChat = () => {
  window.dispatchEvent(new CustomEvent('open-ai-chat'))
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  applyDisplayFilters()
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
  color: #5eead4;
}

/* Page Transition: Chuyển cảnh siêu mượt mà, chuyển trang êm ái không giật lag */
.page-fade-enter-active,
.page-fade-leave-active {
  transition: opacity 0.14s cubic-bezier(0.16, 1, 0.3, 1), transform 0.14s cubic-bezier(0.16, 1, 0.3, 1);
}

.page-fade-enter-from {
  opacity: 0;
  transform: translateY(3px);
}

.page-fade-leave-to {
  opacity: 0;
  transform: translateY(-3px);
}

/* MOBILE STICKY BOTTOM NAVIGATION BAR */
.mobile-bottom-nav {
  height: 60px;
  background: rgba(255, 255, 255, 0.94);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border-top: 1px solid rgba(0, 0, 0, 0.08) !important;
  z-index: 1040 !important;
  padding-bottom: env(safe-area-inset-bottom, 0);
  box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
}

:global([data-bs-theme="dark"]) .mobile-bottom-nav,
.bg-dark .mobile-bottom-nav {
  background: rgba(15, 23, 42, 0.95);
  border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
  box-shadow: 0 -4px 25px rgba(0, 0, 0, 0.4);
}

.mobile-nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  color: #64748b;
  font-size: 10px;
  font-weight: 600;
  padding: 4px 6px;
  min-width: 52px;
  position: relative;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.mobile-nav-item i {
  font-size: 19px;
  line-height: 1.1;
  transition: transform 0.2s ease, color 0.2s ease;
}

.mobile-nav-item.active {
  color: #00b074 !important;
  font-weight: 700;
}

.mobile-nav-item.active i {
  color: #00b074 !important;
  transform: translateY(-2px) scale(1.1);
  filter: drop-shadow(0 2px 6px rgba(0, 176, 116, 0.35));
}

.mobile-nav-active-dot {
  position: absolute;
  bottom: 2px;
  width: 4px;
  height: 4px;
  background-color: #00b074;
  border-radius: 999px;
  box-shadow: 0 0 6px #00b074;
}

@media (max-width: 991.98px) {
  .user-main-content {
    padding-bottom: 84px !important;
  }
}
</style>
