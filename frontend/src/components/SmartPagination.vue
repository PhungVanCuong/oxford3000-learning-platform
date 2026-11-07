<template>
  <div v-if="totalItems > 0" class="smart-pagination-container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 p-3 border-top flex-wrap">
    <!-- Left: Items info & Page Size Selector -->
    <div class="d-flex align-items-center gap-2 flex-wrap fs-8 text-muted">
      <span>
        Hiển thị <strong class="text-teal">{{ startItem }} - {{ endItem }}</strong> / tổng số <strong class="text-dark-emphasis">{{ totalItems }}</strong> mục
      </span>

      <span class="mx-1 d-none d-sm-inline">•</span>

      <!-- Page Size Selector (Mặc định 50 mục/trang) -->
      <div class="d-inline-flex align-items-center gap-1">
        <label for="pageSizeSelect" class="text-nowrap mb-0">Mỗi trang:</label>
        <select
          id="pageSizeSelect"
          :value="pageSize"
          @change="onPageSizeChange($event.target.value)"
          class="form-select form-select-sm py-1 px-2 rounded-3 border fs-8 fw-semibold"
          style="width: auto; min-width: 72px;"
        >
          <option v-for="opt in pageSizeOptions" :key="opt" :value="opt">
            {{ opt }}
          </option>
        </select>
      </div>
    </div>

    <!-- Right: Smart Navigation Buttons (Lên tất cả <<, Lên <, Trang pills, Xuống >, Xuống tất cả >>, Nhảy tới trang) -->
    <div class="d-flex align-items-center gap-1.5 flex-wrap">
      <!-- 1. Lên tất cả (Đầu trang / First Page <<) -->
      <button
        @click="goTo(1)"
        :disabled="currentPage <= 1"
        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
        title="Lên tất cả (Trang đầu tiên)"
      >
        <i class="bi bi-chevron-double-left"></i>
        <span class="d-none d-lg-inline">Đầu</span>
      </button>

      <!-- 2. Lên (Trang trước / Previous Page <) -->
      <button
        @click="goTo(currentPage - 1)"
        :disabled="currentPage <= 1"
        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
        title="Lên (Trang trước)"
      >
        <i class="bi bi-chevron-left"></i>
        <span class="d-none d-sm-inline">Lên</span>
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

      <!-- 4. Xuống (Trang sau / Next Page >) -->
      <button
        @click="goTo(currentPage + 1)"
        :disabled="currentPage >= totalPages"
        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
        title="Xuống (Trang tiếp theo)"
      >
        <span class="d-none d-sm-inline">Xuống</span>
        <i class="bi bi-chevron-right"></i>
      </button>

      <!-- 5. Xuống tất cả (Cuối trang / Last Page >>) -->
      <button
        @click="goTo(totalPages)"
        :disabled="currentPage >= totalPages"
        class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-2xs"
        title="Xuống tất cả (Trang cuối cùng)"
      >
        <span class="d-none d-lg-inline">Cuối</span>
        <i class="bi bi-chevron-double-right"></i>
      </button>

      <!-- 6. Nhảy trực tiếp tới trang (Jump to page input) -->
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
import { ref, computed, watch } from 'vue'

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
    default: 50 // Mặc định 50 mục/trang theo yêu cầu
  },
  pageSizeOptions: {
    type: Array,
    default: () => [25, 50, 100, 200]
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
}

const onPageSizeChange = (val) => {
  const newSize = parseInt(val, 10) || 50
  emit('update:pageSize', newSize)
  emit('update:currentPage', 1)
  emit('change', 1)
}

const handleJump = () => {
  const val = parseInt(jumpPageInput.value, 10)
  if (val >= 1 && val <= totalPages.value) {
    goTo(val)
    jumpPageInput.value = ''
  } else {
    alert(`Vui lòng nhập trang từ 1 đến ${totalPages.value}`)
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
