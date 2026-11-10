<template>
  <Teleport to="body">
    <Transition name="base-modal-fade">
      <div
        v-if="modelValue"
        class="base-modal-backdrop"
        :class="backdropClass"
        @click.self="handleBackdropClick"
        tabindex="-1"
        role="dialog"
        aria-modal="true"
      >
        <div
          class="base-modal-dialog"
          :class="[dialogClass, isDarkMode ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle']"
          :style="{ maxWidth: maxWidth, width: '100%' }"
        >
          <!-- Modal Header -->
          <div
            v-if="!hideHeader"
            class="base-modal-header d-flex align-items-center justify-content-between p-3.5 border-bottom flex-shrink-0"
            :class="headerClass"
          >
            <slot name="header">
              <div class="d-flex align-items-center gap-2 min-w-0">
                <i v-if="icon" :class="[icon, 'text-teal fs-5 flex-shrink-0']"></i>
                <h5 class="mb-0 fw-bold fs-6 text-truncate" :class="titleClass">
                  {{ title }}
                </h5>
              </div>
            </slot>
            <button
              v-if="showCloseButton"
              type="button"
              @click="close"
              class="btn-close ms-2 flex-shrink-0"
              :class="{ 'btn-close-white': isDarkMode }"
              aria-label="Đóng"
            ></button>
          </div>

          <!-- Modal Body (Scrollable Viewport Area) -->
          <div
            class="base-modal-body p-3.5 p-md-4 overflow-y-auto flex-grow-1 custom-thin-scrollbar"
            :class="bodyClass"
          >
            <slot></slot>
          </div>

          <!-- Modal Footer -->
          <div
            v-if="!hideFooter && $slots.footer"
            class="base-modal-footer d-flex align-items-center justify-content-end gap-2 p-3 border-top flex-shrink-0"
            :class="footerClass"
          >
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: ''
  },
  icon: {
    type: String,
    default: ''
  },
  maxWidth: {
    type: String,
    default: '640px'
  },
  closeOnBackdrop: {
    type: Boolean,
    default: true
  },
  closeOnEscape: {
    type: Boolean,
    default: true
  },
  showCloseButton: {
    type: Boolean,
    default: true
  },
  hideHeader: {
    type: Boolean,
    default: false
  },
  hideFooter: {
    type: Boolean,
    default: false
  },
  headerClass: {
    type: String,
    default: ''
  },
  titleClass: {
    type: String,
    default: 'text-teal'
  },
  bodyClass: {
    type: String,
    default: ''
  },
  footerClass: {
    type: String,
    default: ''
  },
  backdropClass: {
    type: String,
    default: ''
  },
  dialogClass: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue', 'close', 'open'])

const authStore = useAuthStore()
const isDarkMode = computed(() => authStore?.themeMode === 'dark')

const close = () => {
  emit('update:modelValue', false)
  emit('close')
}

const handleBackdropClick = () => {
  if (props.closeOnBackdrop) {
    close()
  }
}

const handleKeyDown = (e) => {
  if (props.modelValue && props.closeOnEscape && e.key === 'Escape') {
    close()
  }
}

// Body scroll lock management
watch(
  () => props.modelValue,
  (isOpen) => {
    if (isOpen) {
      document.body.style.overflow = 'hidden'
      emit('open')
    } else {
      document.body.style.overflow = ''
    }
  },
  { immediate: true }
)

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
  if (props.modelValue) {
    document.body.style.overflow = 'hidden'
  }
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
  document.body.style.overflow = ''
})
</script>

<style scoped>
/* VIEWPORT FIXED CENTER BACKDROP */
.base-modal-backdrop {
  position: fixed;
  inset: 0;
  width: 100vw;
  height: 100vh;
  height: 100dvh;
  background: rgba(0, 0, 0, 0.65);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  overflow: hidden;
}

/* CENTERED RESPONSIVE DIALOG CONTAINER */
.base-modal-dialog {
  position: relative;
  max-height: 88vh;
  max-height: 88dvh;
  display: flex;
  flex-direction: column;
  border-radius: 1.25rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
  overflow: hidden;
  will-change: transform, opacity;
  animation: modalScaleUp 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* CUSTOM THIN SCROLLBAR FOR INTERNAL SCROLL */
.custom-thin-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(0, 176, 116, 0.35) transparent;
}
.custom-thin-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-thin-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-thin-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(0, 176, 116, 0.35);
  border-radius: 999px;
}
.custom-thin-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 176, 116, 0.75);
}

/* TRANSITIONS */
.base-modal-fade-enter-active,
.base-modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.base-modal-fade-enter-from,
.base-modal-fade-leave-to {
  opacity: 0;
}

@keyframes modalScaleUp {
  0% {
    opacity: 0;
    transform: scale(0.94) translateY(12px);
  }
  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@media (max-width: 575.98px) {
  .base-modal-backdrop {
    padding: 0.75rem;
  }
  .base-modal-dialog {
    max-height: 92vh;
    max-height: 92dvh;
    border-radius: 1rem;
  }
}
</style>
