<template>
  <div
    class="toast-item-card position-relative rounded-4 p-3 shadow-lg overflow-hidden user-select-none"
    :class="[
      themeClasses,
      typeClass,
      { 'is-swiping': isSwiping }
    ]"
    :style="cardTransformStyle"
    @mouseenter="pauseTimer"
    @mouseleave="resumeTimer"
    @touchstart="handleTouchStart"
    @touchmove="handleTouchMove"
    @touchend="handleTouchEnd"
    role="alert"
  >
    <div class="d-flex align-items-start gap-2.5">
      <!-- Icon Wrapper -->
      <div class="toast-icon-circle rounded-circle d-flex align-items-center justify-content-center shrink-0">
        <i :class="['bi', computedIcon, 'fs-5']"></i>
      </div>

      <!-- Content -->
      <div class="flex-grow-1 min-w-0 pt-0.5">
        <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
          <span class="fw-bold fs-7 tracking-tight toast-title text-truncate">
            {{ toast.title || defaultTitle }}
          </span>
          <span class="text-xs opacity-60 ms-1 shrink-0 font-monospace" style="font-size: 10px;">
            Vừa xong
          </span>
        </div>
        <p class="mb-0 fs-8 lh-sm toast-message" style="word-break: break-word;">
          {{ toast.message }}
        </p>
      </div>

      <!-- Close Button -->
      <button
        type="button"
        class="toast-close-btn btn btn-sm p-0 border-0 bg-transparent opacity-60 hover-opacity-100 shrink-0"
        @click="dismiss"
        aria-label="Đóng"
      >
        <i class="bi bi-x-lg fs-7"></i>
      </button>
    </div>

    <!-- Countdown Progress Bar -->
    <div class="toast-progress-track position-absolute bottom-0 start-0 w-100">
      <div
        class="toast-progress-bar h-100"
        :style="{ width: progressPercent + '%' }"
      ></div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useToastStore } from '@/stores/toastStore'

const props = defineProps({
  toast: {
    type: Object,
    required: true,
  },
})

const authStore = useAuthStore()
const toastStore = useToastStore()

const remainingTime = ref(props.toast.duration || 3800)
const totalDuration = props.toast.duration || 3800
const progressPercent = ref(100)
let timer = null
let lastTick = Date.now()
const isPaused = ref(false)

// Touch swipe to dismiss
const touchStartX = ref(0)
const currentTranslateX = ref(0)
const isSwiping = ref(false)

const dismiss = () => {
  clearInterval(timer)
  toastStore.removeToast(props.toast.id)
}

const pauseTimer = () => {
  isPaused.value = true
}

const resumeTimer = () => {
  isPaused.value = false
  lastTick = Date.now()
}

// Touch Handlers
const handleTouchStart = (e) => {
  touchStartX.value = e.touches[0].clientX
  isSwiping.value = true
  pauseTimer()
}

const handleTouchMove = (e) => {
  if (!isSwiping.value) return
  const currentX = e.touches[0].clientX
  const diff = currentX - touchStartX.value
  // Allow swiping to the right or lightly to the left
  if (diff > -20) {
    currentTranslateX.value = diff
  }
}

const handleTouchEnd = () => {
  isSwiping.value = false
  if (currentTranslateX.value > 90) {
    // Swiped past threshold -> dismiss!
    dismiss()
  } else {
    // Snap back
    currentTranslateX.value = 0
    resumeTimer()
  }
}

const cardTransformStyle = computed(() => {
  if (currentTranslateX.value !== 0) {
    const opacity = Math.max(0.2, 1 - currentTranslateX.value / 250)
    return {
      transform: `translateX(${currentTranslateX.value}px)`,
      opacity,
      transition: isSwiping.value ? 'none' : 'transform 0.25s ease, opacity 0.25s ease',
    }
  }
  return {}
})

// Type configurations
const typeClass = computed(() => `toast-type-${props.toast.type || 'info'}`)

const themeClasses = computed(() => {
  return authStore.themeMode === 'dark' ? 'toast-dark text-white' : 'toast-light text-dark-emphasis'
})

const defaultTitle = computed(() => {
  switch (props.toast.type) {
    case 'success': return 'Thành công'
    case 'error': return 'Thông báo lỗi'
    case 'saved': return 'Đã lưu'
    case 'review': return 'Cần ôn tập'
    case 'mastered': return 'Đã ghi nhớ'
    case 'streak': return 'Chuỗi học tập'
    case 'warning': return 'Cảnh báo'
    default: return 'Thông báo'
  }
})

const computedIcon = computed(() => {
  if (props.toast.icon) return props.toast.icon
  switch (props.toast.type) {
    case 'success': return 'bi-check2-circle'
    case 'error': return 'bi-exclamation-octagon-fill'
    case 'saved': return 'bi-bookmark-check-fill'
    case 'review': return 'bi-arrow-clockwise'
    case 'mastered': return 'bi-stars'
    case 'streak': return 'bi-fire'
    case 'warning': return 'bi-exclamation-triangle-fill'
    default: return 'bi-info-circle-fill'
  }
})

onMounted(() => {
  lastTick = Date.now()
  timer = setInterval(() => {
    if (isPaused.value) return
    const now = Date.now()
    const elapsed = now - lastTick
    lastTick = now

    remainingTime.value -= elapsed
    progressPercent.value = Math.max(0, (remainingTime.value / totalDuration) * 100)

    if (remainingTime.value <= 0) {
      dismiss()
    }
  }, 30)
})

onUnmounted(() => {
  if (timer) clearInterval(timer)
})
</script>

<style scoped>
.toast-item-card {
  pointer-events: auto;
  border-width: 1.5px;
  border-style: solid;
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  min-width: 300px;
  max-width: 420px;
  transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
  will-change: transform, opacity;
}

.toast-item-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 16px 32px rgba(0, 0, 0, 0.2) !important;
}

/* Light & Dark Base */
.toast-light {
  background: rgba(255, 255, 255, 0.94);
  color: #0f172a;
}
.toast-dark {
  background: rgba(15, 23, 42, 0.92);
  color: #f8fafc;
}

/* 1. SUCCESS: Emerald (#00b074) */
.toast-type-success {
  border-color: rgba(16, 185, 129, 0.5) !important;
  box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.25);
}
.toast-type-success .toast-icon-circle {
  background: rgba(16, 185, 129, 0.15);
  color: #10b981;
  width: 36px;
  height: 36px;
}
.toast-type-success .toast-title {
  color: #059669;
}
.toast-dark.toast-type-success .toast-title {
  color: #34d399;
}
.toast-type-success .toast-progress-bar {
  background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
}

/* 2. ERROR: Rose Red (#f43f5e) */
.toast-type-error {
  border-color: rgba(244, 63, 94, 0.5) !important;
  box-shadow: 0 10px 25px -5px rgba(244, 63, 94, 0.25);
}
.toast-type-error .toast-icon-circle {
  background: rgba(244, 63, 94, 0.15);
  color: #f43f5e;
  width: 36px;
  height: 36px;
}
.toast-type-error .toast-title {
  color: #e11d48;
}
.toast-dark.toast-type-error .toast-title {
  color: #fb7185;
}
.toast-type-error .toast-progress-bar {
  background: linear-gradient(90deg, #f43f5e 0%, #fda4af 100%);
}

/* 3. SAVED: Sky Blue (#0284c7) */
.toast-type-saved {
  border-color: rgba(2, 132, 199, 0.5) !important;
  box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.25);
}
.toast-type-saved .toast-icon-circle {
  background: rgba(2, 132, 199, 0.15);
  color: #0284c7;
  width: 36px;
  height: 36px;
}
.toast-type-saved .toast-title {
  color: #0284c7;
}
.toast-dark.toast-type-saved .toast-title {
  color: #38bdf8;
}
.toast-type-saved .toast-progress-bar {
  background: linear-gradient(90deg, #0284c7 0%, #38bdf8 100%);
}

/* 4. REVIEW: Amber Gold (#f59e0b) */
.toast-type-review {
  border-color: rgba(245, 158, 11, 0.5) !important;
  box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.25);
}
.toast-type-review .toast-icon-circle {
  background: rgba(245, 158, 11, 0.15);
  color: #f59e0b;
  width: 36px;
  height: 36px;
}
.toast-type-review .toast-title {
  color: #d97706;
}
.toast-dark.toast-type-review .toast-title {
  color: #fbbf24;
}
.toast-type-review .toast-progress-bar {
  background: linear-gradient(90deg, #f59e0b 0%, #fcd34d 100%);
}

/* 5. MASTERED: Royal Purple & Gold Star (#8b5cf6) */
.toast-type-mastered {
  border-color: rgba(139, 92, 246, 0.55) !important;
  box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.3);
}
.toast-type-mastered .toast-icon-circle {
  background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%);
  color: #8b5cf6;
  width: 36px;
  height: 36px;
}
.toast-type-mastered .toast-title {
  background: linear-gradient(135deg, #7c3aed 0%, #d97706 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.toast-dark.toast-type-mastered .toast-title {
  background: linear-gradient(135deg, #a78bfa 0%, #fde047 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.toast-type-mastered .toast-progress-bar {
  background: linear-gradient(90deg, #8b5cf6 0%, #f59e0b 100%);
}

/* 6. STREAK: Fire Orange-Red (#ff5722) */
.toast-type-streak {
  border-color: rgba(255, 87, 34, 0.55) !important;
  box-shadow: 0 10px 25px -5px rgba(255, 87, 34, 0.35);
}
.toast-type-streak .toast-icon-circle {
  background: linear-gradient(135deg, rgba(255, 87, 34, 0.2) 0%, rgba(239, 68, 68, 0.2) 100%);
  color: #ff5722;
  width: 36px;
  height: 36px;
}
.toast-type-streak .toast-title {
  color: #ea580c;
}
.toast-dark.toast-type-streak .toast-title {
  color: #fb923c;
}
.toast-type-streak .toast-progress-bar {
  background: linear-gradient(90deg, #ff5722 0%, #ef4444 100%);
}

/* 7. INFO: Cyan (#06b6d4) */
.toast-type-info {
  border-color: rgba(6, 182, 212, 0.5) !important;
  box-shadow: 0 10px 25px -5px rgba(6, 182, 212, 0.2);
}
.toast-type-info .toast-icon-circle {
  background: rgba(6, 182, 212, 0.15);
  color: #06b6d4;
  width: 36px;
  height: 36px;
}
.toast-type-info .toast-title {
  color: #0891b2;
}
.toast-dark.toast-type-info .toast-title {
  color: #22d3ee;
}
.toast-type-info .toast-progress-bar {
  background: linear-gradient(90deg, #06b6d4 0%, #67e8f9 100%);
}

/* 8. WARNING: Orange (#f97316) */
.toast-type-warning {
  border-color: rgba(249, 115, 22, 0.5) !important;
  box-shadow: 0 10px 25px -5px rgba(249, 115, 22, 0.25);
}
.toast-type-warning .toast-icon-circle {
  background: rgba(249, 115, 22, 0.15);
  color: #f97316;
  width: 36px;
  height: 36px;
}
.toast-type-warning .toast-title {
  color: #ea580c;
}
.toast-dark.toast-type-warning .toast-title {
  color: #fb923c;
}
.toast-type-warning .toast-progress-bar {
  background: linear-gradient(90deg, #f97316 0%, #fdba74 100%);
}

/* Progress track */
.toast-progress-track {
  height: 3px;
  background: rgba(0, 0, 0, 0.05);
}
.toast-dark .toast-progress-track {
  background: rgba(255, 255, 255, 0.08);
}
.toast-progress-bar {
  transition: width 0.05s linear;
}

.toast-close-btn {
  color: inherit;
  cursor: pointer;
}
</style>
