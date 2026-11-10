import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useToastStore = defineStore('toast', () => {
  const toasts = ref([])

  /**
   * Thêm một Toast mới vào danh sách hiển thị
   * @param {Object} options
   * @param {'success'|'error'|'saved'|'review'|'mastered'|'info'|'warning'|'streak'} options.type
   * @param {string} options.message
   * @param {string} [options.title]
   * @param {number} [options.duration=3800]
   */
  const addToast = ({
    type = 'info',
    message = '',
    title = '',
    duration = 3800,
    icon = null,
  }) => {
    if (!message) return null

    const id = 'toast_' + Date.now() + '_' + Math.random().toString(36).substring(2, 7)
    
    // Giới hạn tối đa 5 thông báo cùng lúc để không chiếm diện tích màn hình
    if (toasts.value.length >= 5) {
      toasts.value.shift()
    }

    const newToast = {
      id,
      type,
      message,
      title,
      duration,
      icon,
      createdAt: Date.now(),
    }

    toasts.value.push(newToast)

    return id
  }

  const removeToast = (id) => {
    const idx = toasts.value.findIndex(t => t.id === id)
    if (idx !== -1) {
      toasts.value.splice(idx, 1)
    }
  }

  const clearAll = () => {
    toasts.value = []
  }

  return {
    toasts,
    addToast,
    removeToast,
    clearAll,
  }
})
