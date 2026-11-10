<template>
  <div v-if="totalItems > 0" class="smart-pagination-container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 p-3 border-top flex-wrap">
    <!-- Left: Items info & Page Size Selector -->
    <div class="d-flex align-items-center gap-2 flex-wrap fs-8 text-muted w-100 w-md-auto justify-content-between justify-content-md-start">
      <span>
        Hiển thị <strong class="text-teal">{{ startItem }} - {{ endItem }}</strong> / tổng số <strong class="text-dark-emphasis">{{ totalItems }}</strong> mục
      </span>

      <span class="mx-1 d-none d-sm-inline">•</span>

      <!-- Page Size Selector -->
      <div class="d-inline-flex align-items-center gap-1">
        <label for="pageSizeSelect" class="text-nowrap mb-0 fs-8">Mỗi trang:</label>
        <select
          id="pageSizeSelect"
          :value="pageSize"
          @change="onPageSizeChange($event.target.value)"
          class="form-select form-select-sm py-1 px-2 rounded-3 border fs-8 fw-semibold"
          style="width: auto; min-width: 68px;"
        >
          <option v-for="opt in pageSizeOptions" :key="opt" :value="opt">
            {{ opt }}
          </option>
        </select>
      </div>
    </div>

    <!-- Mobile-Only Condensed Navigation (d-md-none) -->
    <div class="d-flex d-md-none align-items-center justify-content-between w-100 gap-2 mt-2 pt-2 border-top border-light-subtle">
      <button
        @click="goTo(currentPage - 1)"
        :disabled="currentPage <= 1"
        class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
      >
        <i class="bi bi-chevron-left"></i>
        <span>Trước</span>
      </button>

      <span class="fs-8 fw-bold text-dark-emphasis px-2 py-1 rounded-pill bg-light border">
        Trang <strong class="text-teal">{{ currentPage }}</strong> / {{ totalPages }}
      </span>

      <button
        @click="goTo(currentPage + 1)"
        :disabled="currentPage >= totalPages"
        class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
      >
        <span>Sau</span>
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>

    <!-- Desktop Navigation Buttons (d-none d-md-flex) -->
    <div class="d-none d-md-flex align-items-center gap-1.5 flex-wrap">
      <!-- 1. Đầu trang << -->
      <button
        @click="goTo(1)"
        :disabled="currentPage <= 1"
        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
        title="Trang đầu tiên"
      >
        <i class="bi bi-chevron-double-left"></i>
        <span class="d-none d-lg-inline">Đầu</span>
      </button>

      <!-- 2. Trang trước < -->
      <button
        @click="goTo(currentPage - 1)"
        :disabled="currentPage <= 1"
        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
        title="Trang trước"
      >
        <i class="bi bi-chevron-left"></i>
        <span>Lên</span>
      </button>

      <!-- 3. Dynamic Page Number Pills -->
      <div class="d-inline-flex align-items-center gap-1">
        <template v-for="(p, idx) in visiblePages" :key="idx">
          <span v-if="p === '...'" class="px-1 text-muted fs-8 user-select-none">…</span>
          <button
            v-else
            @click="goTo(p)"
            class="btn btn-sm rounded-3 px-2.5 py-1 fs-8 fw-bold transition-all shadow-2xs"
            :class="currentPage === p ? 'btn-teal text-white shadow-xs' : 'btn-outline-secondary'"
            style="min-width: 32px;"
          >
            {{ p }}
          </button>
        </template>
      </div>

      <!-- 4. Trang sau > -->
      <button
        @click="goTo(currentPage + 1)"
        :disabled="currentPage >= totalPages"
        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
        title="Trang tiếp theo"
      >
        <span>Xuống</span>
        <i class="bi bi-chevron-right"></i>
      </button>

      <!-- 5. Cuối trang >> -->
      <button
        @click="goTo(totalPages)"
        :disabled="currentPage >= totalPages"
        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
        title="Trang cuối cùng"
      >
        <span class="d-none d-lg-inline">Cuối</span>
        <i class="bi bi-chevron-double-right"></i>
      </button>

      <!-- 6. Nhảy trực tiếp tới trang -->
      <div class="d-inline-flex align-items-center gap-1 ms-1">
        <input
          v-model.number="jumpPageInput"
          @keyup.enter="handleJump"
          type="number"
          min="1"
          :max="totalPages"
          placeholder="Số..."
          class="form-control form-control-sm py-1 px-1.5 text-center rounded-3 fs-8 border"
          style="width: 56px;"
          title="Nhập số trang và nhấn Enter"
        />
        <button
          @click="handleJump"
          class="btn btn-sm btn-outline-teal rounded-3 px-2 py-1 fs-8 fw-bold"
          title="Chuyển ngay tới trang"
        >
          Đi
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

const props = defineProps({
  currentPage: {
    type: Number,
    default: 1
  },
  totalItems: {
    type: Number,
    required: true
  },
  pageSize: {
    type: Number,
    default: 20
  },
  pageSizeOptions: {
    type: Array,
    default: () => [10, 20, 50, 100]
  },
  autoScroll: {
    type: Boolean,
    default: true
  },
  scrollTarget: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:currentPage', 'update:pageSize', 'change'])

const jumpPageInput = ref('')

const totalPages = computed(() => {
  if (props.totalItems <= 0) return 1
  return Math.ceil(props.totalItems / props.pageSize) || 1
})

const startItem = computed(() => {
  if (props.totalItems === 0) return 0
  return (props.currentPage - 1) * props.pageSize + 1
})

const endItem = computed(() => {
  return Math.min(props.currentPage * props.pageSize, props.totalItems)
})

// Calculate visible page buttons with intelligent windowing
const visiblePages = computed(() => {
  const total = totalPages.value
  const current = props.currentPage
  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1)
  }

  const pages = []
  pages.push(1)

  if (current > 3) {
    pages.push('...')
  }

  const start = Math.max(2, current - 1)
  const end = Math.min(total - 1, current + 1)

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  if (current < total - 2) {
    pages.push('...')
  }

  pages.push(total)
  return pages
})

const goTo = (page) => {
  if (page < 1 || page > totalPages.value || page === props.currentPage) return
  emit('update:currentPage', page)
  emit('change', page)

  if (props.autoScroll) {
    nextTick(() => {
      if (props.scrollTarget) {
        const el = document.querySelector(props.scrollTarget)
        if (el) {
          el.scrollIntoView({ behavior: 'smooth', block: 'start' })
          return
        }
      }
      window.scrollTo({ top: 0, behavior: 'smooth' })
    })
  }
}

const onPageSizeChange = (val) => {
  const newSize = parseInt(val, 10) || 20
  emit('update:pageSize', newSize)
  emit('update:currentPage', 1)
  emit('change', 1)
}

const handleJump = () => {
  const val = parseInt(jumpPageInput.value, 10)
  if (val >= 1 && val <= totalPages.value) {
    goTo(val)
    jumpPageInput.value = ''
  }
}
</script>

<style scoped>
.smart-pagination-container {
  background: rgba(0, 0, 0, 0.015);
}
.btn-outline-teal {
  color: #00b074;
  border-color: #00b074;
}
.btn-outline-teal:hover {
  background-color: #00b074;
  color: #ffffff;
}
</style>
