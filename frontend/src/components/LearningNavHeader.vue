<template>
  <div class="learning-nav-header w-100 mb-3 mb-md-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
      <!-- CỘT TRÁI: TIÊU ĐỀ & MÔ TẢ ĐỒNG BỘ CHUẨN XỊN -->
      <div class="header-left-col min-w-0">
        <div class="d-inline-flex align-items-center gap-2 mb-1.5">
          <span class="header-dot rounded-circle bg-teal" style="width: 8px; height: 8px;"></span>
          <span class="header-category fw-bold fs-8 text-teal text-uppercase letter-spacing-1">
            {{ category || 'LỘ TRÌNH HỌC TẬP' }}
          </span>
          <slot name="badge"></slot>
        </div>
        <h1 class="header-title display-6 display-md-5 fw-black text-dark-emphasis mb-1 tracking-tight">
          {{ title }}
        </h1>
        <p class="header-desc text-muted fs-7 mb-0">
          {{ description }}
        </p>
      </div>

      <!-- CỘT PHẢI: BỘ CHUYỂN ĐỔI 3 MỤC (NGỮ PHÁP | TỪ VỰNG CHỦ ĐỀ | TỪ VỰNG CEFR) -->
      <!-- ĐỒNG BỘ 100% CẢ VỊ TRÍ, KÍCH THƯỚC VÀ HIỆU ỨNG CHUYỂN CẢNH SIÊU MƯỢT MÀ -->
      <div class="header-right-col flex-shrink-0 align-self-start align-self-md-center">
        <div 
          class="learning-segmented-pill p-1 rounded-pill d-inline-flex align-items-center shadow-xs border"
          :class="authStore.themeMode === 'dark' ? 'segmented-dark' : 'segmented-light'"
        >
          <!-- 1. Ngữ pháp -->
          <router-link
            to="/grammar"
            class="segmented-pill-btn text-decoration-none"
            :class="{ 'active': activeTab === 'grammar' }"
            title="Chuyên đề 12 thì & Ngữ pháp tiếng Anh toàn diện"
          >
            Ngữ pháp
          </router-link>

          <!-- 2. Từ vựng chủ đề -->
          <router-link
            to="/curriculum"
            class="segmented-pill-btn text-decoration-none"
            :class="{ 'active': activeTab === 'curriculum' }"
            title="Lộ trình 25 chủ đề từ vựng Oxford 3000™"
          >
            Từ vựng chủ đề
          </router-link>

          <!-- 3. Từ vựng CEFR -->
          <router-link
            to="/cefr"
            class="segmented-pill-btn text-decoration-none"
            :class="{ 'active': activeTab === 'cefr' }"
            title="Từ vựng 6 bậc chuẩn khung tham chiếu Châu Âu (A1 - C2)"
          >
            Từ vựng CEFR
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/authStore'

defineProps({
  category: {
    type: String,
    default: 'LỘ TRÌNH HỌC TẬP'
  },
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    default: ''
  },
  activeTab: {
    type: String,
    required: true,
    validator: (v) => ['grammar', 'curriculum', 'cefr'].includes(v)
  }
})

const authStore = useAuthStore()
</script>

<style scoped>
.learning-nav-header {
  transition: opacity 0.2s ease;
}

.header-category {
  letter-spacing: 0.8px;
}

.header-title {
  letter-spacing: -0.5px;
  line-height: 1.2;
}

/* Container của 3 nút chuyển đổi đồng bộ */
.learning-segmented-pill {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  user-select: none;
}

.segmented-light {
  background-color: #ffffff;
  border-color: rgba(226, 232, 240, 0.9) !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.segmented-dark {
  background-color: #0b1412 !important;
  border-color: rgba(255, 255, 255, 0.14) !important;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
}

/* Nút thành phần trong bộ chuyển đổi */
.segmented-pill-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 8px 18px;
  font-size: 0.88rem;
  font-weight: 700;
  border-radius: 9999px;
  white-space: nowrap;
  transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
  color: inherit;
  border: 1px solid transparent;
}

.segmented-light .segmented-pill-btn {
  color: #334155;
}

.segmented-light .segmented-pill-btn:hover:not(.active) {
  color: #008060;
  background-color: rgba(0, 128, 96, 0.08);
}

.segmented-dark .segmented-pill-btn {
  color: rgba(255, 255, 255, 0.75);
}

.segmented-dark .segmented-pill-btn:hover:not(.active) {
  color: #5eead4;
  background-color: rgba(94, 234, 212, 0.1);
}

/* Trạng thái đang chọn (Active) - Khớp 100% ảnh thiết kế của người dùng */
.segmented-pill-btn.active {
  background-color: #032b24 !important;
  color: #ffffff !important;
  font-weight: 800;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(0, 176, 116, 0.35);
}

/* Tối ưu màn hình điện thoại (Mobile) */
@media (max-width: 767.98px) {
  .header-title {
    font-size: 1.45rem !important;
  }
  .learning-segmented-pill {
    width: 100%;
    display: flex;
    justify-content: space-between;
  }
  .segmented-pill-btn {
    flex: 1;
    padding: 6px 8px;
    font-size: 0.78rem;
    text-align: center;
  }
}
</style>
