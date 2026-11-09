<template>
  <div class="toast-global-container" aria-live="polite" aria-atomic="true">
    <TransitionGroup name="toast-anim" tag="div" class="toast-stack-wrapper">
      <ToastItem
        v-for="toast in toastStore.toasts"
        :key="toast.id"
        :toast="toast"
      />
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToastStore } from '@/stores/toastStore'
import ToastItem from './ToastItem.vue'

const toastStore = useToastStore()
</script>

<style scoped>
.toast-global-container {
  position: fixed;
  top: 24px;
  right: 24px;
  z-index: 999999 !important;
  pointer-events: none;
  max-width: 420px;
  width: auto;
}

.toast-stack-wrapper {
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-items: flex-end;
}

/* DESKTOP ANIMATIONS (Slide in from right) */
.toast-anim-enter-active,
.toast-anim-leave-active {
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-anim-enter-from {
  opacity: 0;
  transform: translateX(100px) scale(0.95);
}

.toast-anim-leave-to {
  opacity: 0;
  transform: translateX(100px) scale(0.9);
}

.toast-anim-move {
  transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

/* MOBILE ADAPTATION (Centered at top, slide down from top) */
@media (max-width: 767.98px) {
  .toast-global-container {
    top: 16px;
    left: 50% !important;
    right: auto !important;
    transform: translateX(-50%);
    width: calc(100% - 32px);
    max-width: 420px;
  }

  .toast-stack-wrapper {
    align-items: center;
    width: 100%;
  }

  :deep(.toast-item-card) {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
  }

  .toast-anim-enter-from {
    opacity: 0;
    transform: translateY(-40px) scale(0.95);
  }

  .toast-anim-leave-to {
    opacity: 0;
    transform: translateY(-40px) scale(0.9);
  }
}
</style>
