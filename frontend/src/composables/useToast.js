import { useToastStore } from '@/stores/toastStore'

export function useToast() {
  const toastStore = useToastStore()

  return {
    /**
     * Thông báo Thành Công (Màu xanh ngọc Emerald, icon checkmark)
     * Dùng cho: Đăng nhập thành công, hoàn thành bài, lưu hồ sơ
     */
    success(message, title = 'Thành công') {
      return toastStore.addToast({
        type: 'success',
        message,
        title,
        icon: 'bi-check2-circle',
      })
    },

    /**
     * Thông báo Thất Bại / Lỗi (Màu đỏ san hô Rose, icon cảnh báo)
     * Dùng cho: Lỗi đăng nhập, lỗi mạng, sai đáp án
     */
    error(message, title = 'Thông báo lỗi') {
      return toastStore.addToast({
        type: 'error',
        message,
        title,
        icon: 'bi-exclamation-octagon-fill',
      })
    },

    /**
     * Thông báo Đã Lưu (Màu xanh dương Sky/Blue, icon bookmark)
     * Dùng cho: "Đã lưu vào bộ thẻ", "Đã lưu từ vào sổ tay"
     */
    saved(message, title = 'Đã lưu thành công') {
      return toastStore.addToast({
        type: 'saved',
        message,
        title,
        icon: 'bi-bookmark-check-fill',
      })
    },

    /**
     * Thông báo Cần Ôn Tập (Màu vàng hổ phách Amber, icon đồng hồ/lặp lại)
     * Dùng cho trạng thái từ vựng: "Đã đánh dấu: Cần ôn tập"
     */
    review(message, title = 'Cần ôn tập') {
      return toastStore.addToast({
        type: 'review',
        message,
        title,
        icon: 'bi-arrow-clockwise',
      })
    },

    /**
     * Thông báo Đã Ghi Nhớ (Màu tím Gradient / Gold, icon ngôi sao / huy chương)
     * Dùng cho trạng thái từ vựng: "Tuyệt vời! Đã ghi nhớ từ này"
     */
    mastered(message, title = 'Đã ghi nhớ') {
      return toastStore.addToast({
        type: 'mastered',
        message,
        title,
        icon: 'bi-stars',
      })
    },

    /**
     * Thông báo Thông Tin (Màu Cyan / Teal, icon info)
     */
    info(message, title = 'Thông báo') {
      return toastStore.addToast({
        type: 'info',
        message,
        title,
        icon: 'bi-info-circle-fill',
      })
    },

    /**
     * Thông báo Cảnh Báo (Màu cam Orange, icon cảnh báo tam giác)
     */
    warning(message, title = 'Lưu ý') {
      return toastStore.addToast({
        type: 'warning',
        message,
        title,
        icon: 'bi-exclamation-triangle-fill',
      })
    },

    /**
     * Thông báo Chuỗi Ngày Học / Tích Lũy Điểm XP (Màu cam lửa Fire, icon tia chớp / ngọn lửa)
     */
    streak(days, message = null) {
      return toastStore.addToast({
        type: 'streak',
        message: message || `Bạn đã duy trì chuỗi học ${days} ngày liên tiếp! Tuyệt vời! 🔥`,
        title: `Chuỗi Học Tập • ${days} Ngày 🔥`,
        icon: 'bi-fire',
        duration: 4500,
      })
    },

    /**
     * Đóng toast thủ công
     */
    dismiss(id) {
      toastStore.removeToast(id)
    },

    /**
     * Xóa toàn bộ toast
     */
    clear() {
      toastStore.clearAll()
    },
  }
}

// Export default singleton instance
export default useToast
