/**
 * Directive & Utility: Universal Mouse Drag-to-Scroll (Kéo lướt ngang bằng chuột trái)
 * Cho phép người dùng nhấn giữ chuột trái và kéo qua lại mượt mà trên desktop
 * mà không cần phải nhấn Shift + lăn chuột hay hiển thị thanh cuộn thô chiếm diện tích.
 */

export function enableDragScroll(el) {
  if (!el || el._dragScrollEnabled) return
  el._dragScrollEnabled = true

  let isDown = false
  let startX = 0
  let scrollLeft = 0
  let hasMoved = false

  el.classList.add('drag-scroll-active')

  const onMouseDown = (e) => {
    // Chỉ kích hoạt khi click chuột trái (button === 0)
    if (e.button !== 0) return
    // Không cản trở nếu click vào input hoặc textarea
    if (e.target.closest('input, textarea, select')) return

    isDown = true
    hasMoved = false
    startX = e.pageX - el.getBoundingClientRect().left
    scrollLeft = el.scrollLeft
    el.style.cursor = 'grabbing'
    el.style.userSelect = 'none'
  }

  const onMouseMove = (e) => {
    if (!isDown) return
    const currentX = e.pageX - el.getBoundingClientRect().left
    const walk = (currentX - startX) * 1.6 // Hệ số độ nhạy mượt mà
    if (Math.abs(currentX - startX) > 4) {
      hasMoved = true
    }
    el.scrollLeft = scrollLeft - walk
  }

  const onMouseUp = (e) => {
    if (!isDown) return
    isDown = false
    el.style.cursor = 'grab'
    el.style.removeProperty('user-select')

    // Nếu người dùng đã kéo rê chuột, ngăn không kích hoạt sự kiện click của các nút con
    if (hasMoved) {
      const preventClick = (clickEvent) => {
        clickEvent.stopPropagation()
        clickEvent.preventDefault()
      }
      el.addEventListener('click', preventClick, { capture: true, once: true })
      setTimeout(() => {
        el.removeEventListener('click', preventClick, { capture: true })
      }, 80)
    }
  }

  const onMouseLeave = () => {
    if (isDown) {
      isDown = false
      el.style.cursor = 'grab'
      el.style.removeProperty('user-select')
    }
  }

  const onDragStart = (e) => {
    // Chặn kéo hình ảnh/nút mặc định của trình duyệt để lướt mượt mà
    e.preventDefault()
  }

  // Tự động chuyển con lăn chuột dọc thành cuộn ngang khi rê chuột vào thanh danh sách ngang
  const onWheel = (e) => {
    if (el.scrollWidth > el.clientWidth && Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
      const canScrollLeft = el.scrollLeft > 0 && e.deltaY < 0
      const canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 1) && e.deltaY > 0
      if (canScrollLeft || canScrollRight) {
        e.preventDefault()
        el.scrollLeft += e.deltaY * 0.95
      }
    }
  }

  el.addEventListener('mousedown', onMouseDown)
  window.addEventListener('mousemove', onMouseMove)
  window.addEventListener('mouseup', onMouseUp)
  el.addEventListener('mouseleave', onMouseLeave)
  el.addEventListener('dragstart', onDragStart)
  el.addEventListener('wheel', onWheel, { passive: false })

  el._cleanupDragScroll = () => {
    el.removeEventListener('mousedown', onMouseDown)
    window.removeEventListener('mousemove', onMouseMove)
    window.removeEventListener('mouseup', onMouseUp)
    el.removeEventListener('mouseleave', onMouseLeave)
    el.removeEventListener('dragstart', onDragStart)
    el.removeEventListener('wheel', onWheel)
    delete el._dragScrollEnabled
  }
}

export const vDragScroll = {
  mounted(el) {
    enableDragScroll(el)
  },
  unmounted(el) {
    if (el._cleanupDragScroll) {
      el._cleanupDragScroll()
    }
  }
}

// Global auto-init cho toàn bộ các thanh ngang có thể cuộn trên toàn hệ thống
export function initGlobalDragScroll() {
  if (typeof window === 'undefined') return

  const applyToElements = () => {
    const targets = document.querySelectorAll(
      '.drag-scroll-x, .mobile-scroll-x, .overflow-x-auto, .no-scrollbar, [data-drag-scroll]'
    )
    targets.forEach(el => {
      // Chỉ áp dụng cho các phần tử thực sự có overflow theo chiều ngang
      if (el.scrollWidth > el.clientWidth || el.classList.contains('overflow-x-auto') || el.classList.contains('drag-scroll-x') || el.classList.contains('mobile-scroll-x')) {
        enableDragScroll(el)
      }
    })
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyToElements)
  } else {
    applyToElements()
  }

  // MutationObserver để tự động nhận diện các phần tử mới được render trong Vue SPA
  const observer = new MutationObserver((mutations) => {
    for (const m of mutations) {
      if (m.addedNodes.length > 0) {
        applyToElements()
        break
      }
    }
  })

  observer.observe(document.body, { childList: true, subtree: true })
}
