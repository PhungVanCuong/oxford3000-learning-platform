<template>
  <div class="admin-grammars-view">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <span class="badge bg-danger text-white rounded-pill px-3 py-1 fs-8">HỆ THỐNG NGỮ PHÁP</span>
        <h2 class="fw-black text-teal mb-0">Quản Lý 12 Thì Ngữ Pháp & Bài Tập Thực Hành</h2>
        <div class="text-muted fs-7">Biên soạn công thức ngữ pháp, dấu hiệu nhận biết, câu bài tập và trắc nghiệm thực chiến</div>
      </div>

      <div class="d-flex gap-2">
        <button @click="openModal(null)" class="btn btn-teal btn-sm rounded-pill px-3 py-2 shadow-sm fw-bold">
          <i class="bi bi-plus-circle-fill me-1"></i> Thêm chuyên đề ngữ pháp
        </button>
      </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Tổng Chuyên Đề</div>
          <div class="display-6 fw-black text-teal">{{ grammars.length }}</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Cơ Bản (A1 - A2)</div>
          <div class="display-6 fw-black text-success">{{ basicCount }}</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Trung Cấp (B1 - B2)</div>
          <div class="display-6 fw-black text-warning">{{ intermediateCount }}</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Nâng Cao (C1 - C2)</div>
          <div class="display-6 fw-black text-danger">{{ advancedCount }}</div>
        </div>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-4">
      <div class="row g-2 align-items-center">
        <div class="col-12 col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-body-tertiary border-end-0">
              <i class="bi bi-search text-teal"></i>
            </span>
            <input
              v-model="searchQuery"
              type="text"
              class="form-control border-start-0 py-2 fs-7"
              placeholder="Tìm theo tên thì ngữ pháp hoặc tóm tắt công thức..."
            />
          </div>
        </div>

        <div class="col-6 col-md-3">
          <select v-model="filterLevel" class="form-select py-2 fs-7">
            <option value="all">Tất cả cấp độ</option>
            <option value="Cơ bản">Cơ bản (A1-A2)</option>
            <option value="Trung cấp">Trung cấp (B1-B2)</option>
            <option value="Nâng cao">Nâng cao (C1-C2)</option>
          </select>
        </div>

        <div class="col-6 col-md-3 text-md-end text-muted fs-8">
          Khớp: <strong class="text-teal fs-7">{{ filteredGrammars.length }}</strong> bài học
        </div>
      </div>
    </div>

    <!-- Grammars Table -->
    <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light fs-8 text-uppercase">
            <tr>
              <th style="width: 50px;">STT</th>
              <th>Chuyên đề Ngữ pháp</th>
              <th>Cấp độ</th>
              <th>Tóm tắt & Công thức</th>
              <th class="text-center">Số bài tập</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody class="fs-7">
            <tr v-for="(g, index) in paginatedGrammars" :key="g.id">
              <td class="text-muted fw-bold">{{ (currentPage - 1) * pageSize + index + 1 }}</td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div
                    class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-2xs shrink-0"
                    :style="{ width: '36px', height: '36px', backgroundColor: g.badge_color || '#00b074' }"
                  >
                    <i class="bi" :class="g.icon || 'bi-journal-bookmark-fill'"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-teal fs-6">{{ g.title }}</div>
                    <div class="text-muted fs-8">{{ g.slug }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span
                  class="badge rounded-pill px-2.5 py-1 fs-8"
                  :class="g.level === 'Nâng cao' ? 'bg-danger text-white' : g.level === 'Trung cấp' ? 'bg-warning text-dark' : 'bg-success text-white'"
                >
                  {{ g.level || 'Cơ bản' }}
                </span>
              </td>
              <td style="max-width: 320px;">
                <div class="text-truncate text-dark-emphasis fw-semibold fs-8">{{ g.summary || 'Công thức và cách dùng chuẩn' }}</div>
                <div class="text-muted fs-9 fst-italic text-truncate">{{ g.structure || 'S + V(s/es) + O' }}</div>
              </td>
              <td class="text-center">
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 fs-8">
                  {{ g.total_exercises || g.questions_count || 20 }} câu hỏi
                </span>
              </td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <button @click="openModal(g)" class="btn btn-outline-secondary" title="Chỉnh sửa ngữ pháp">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <router-link :to="`/grammar/${g.id}`" target="_blank" class="btn btn-outline-teal" title="Xem trên web học viên">
                    <i class="bi bi-eye"></i>
                  </router-link>
                  <button @click="deleteGrammar(g.id)" class="btn btn-outline-danger" title="Xóa chuyên đề">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredGrammars.length === 0">
              <td colspan="6" class="text-center py-5 text-muted">
                {{ isLoading ? 'Đang tải danh sách ngữ pháp...' : 'Không tìm thấy chuyên đề ngữ pháp nào phù hợp.' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Smart 50-Item Pagination -->
      <SmartPagination
        v-model:currentPage="currentPage"
        v-model:pageSize="pageSize"
        :totalItems="filteredGrammars.length"
        :pageSizeOptions="[10, 25, 50, 100]"
      />
    </div>

    <!-- Create / Edit Modal -->
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.55); backdrop-filter: blur(2px);">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
          <div class="modal-header border-bottom pb-3">
            <h5 class="modal-title fw-bold text-teal d-flex align-items-center gap-2">
              <i class="bi bi-journal-check"></i>
              <span>{{ isEditing ? 'Chỉnh Sửa Chuyên Đề Ngữ Pháp' : 'Thêm Chuyên Đề Ngữ Pháp Mới' }}</span>
            </h5>
            <button @click="showModal = false" type="button" class="btn-close"></button>
          </div>

          <form @submit.prevent="saveGrammar">
            <div class="modal-body py-3">
              <div class="row g-3">
                <div class="col-12 col-md-8">
                  <label class="form-label fs-8 fw-bold">Tên thì / Chuyên đề *</label>
                  <input v-model="form.title" type="text" required class="form-control" placeholder="Ví dụ: Present Simple (Hiện tại đơn)" />
                </div>
                <div class="col-12 col-md-4">
                  <label class="form-label fs-8 fw-bold">Cấp độ *</label>
                  <select v-model="form.level" class="form-select">
                    <option value="Cơ bản">Cơ bản (A1-A2)</option>
                    <option value="Trung cấp">Trung cấp (B1-B2)</option>
                    <option value="Nâng cao">Nâng cao (C1-C2)</option>
                  </select>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label fs-8 fw-bold">Slug URL *</label>
                  <input v-model="form.slug" type="text" required class="form-control" placeholder="present-simple" />
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label fs-8 fw-bold">Thứ tự hiển thị</label>
                  <input v-model.number="form.order_index" type="number" class="form-control" />
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label fs-8 fw-bold">Màu thẻ (Hex)</label>
                  <input v-model="form.badge_color" type="color" class="form-control form-control-color w-100" />
                </div>

                <div class="col-12">
                  <label class="form-label fs-8 fw-bold">Tóm tắt ngắn gọn</label>
                  <input v-model="form.summary" type="text" class="form-control" placeholder="Diễn tả thói quen, chân lý hiển nhiên hoặc lịch trình cố định..." />
                </div>

                <div class="col-12">
                  <label class="form-label fs-8 fw-bold">Công thức / Cấu trúc ngữ pháp</label>
                  <textarea v-model="form.structure" rows="2" class="form-control" placeholder="(+) S + V(s/es) | (-) S + do/does not + V_inf | (?) Do/Does + S + V_inf?"></textarea>
                </div>

                <div class="col-12">
                  <label class="form-label fs-8 fw-bold">Dấu hiệu nhận biết</label>
                  <input v-model="form.signal_words" type="text" class="form-control" placeholder="always, usually, often, sometimes, every day, once a week..." />
                </div>
              </div>
            </div>

            <div class="modal-footer border-top pt-2">
              <button @click="showModal = false" type="button" class="btn btn-outline-secondary rounded-pill px-4">Hủy</button>
              <button type="submit" class="btn btn-teal text-white rounded-pill px-4 fw-bold">
                <i class="bi bi-check-lg me-1"></i> Lưu chuyên đề
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import SmartPagination from '@/components/SmartPagination.vue'

const grammars = ref([])
const isLoading = ref(false)
const searchQuery = ref('')
const filterLevel = ref('all')

const currentPage = ref(1)
const pageSize = ref(50) // Mặc định 50 mục/trang

const showModal = ref(false)
const isEditing = ref(false)
const form = ref({
  id: null,
  title: '',
  slug: '',
  level: 'Cơ bản',
  order_index: 1,
  badge_color: '#00b074',
  summary: '',
  structure: '',
  signal_words: ''
})

const fetchGrammars = async () => {
  isLoading.value = true
  try {
    const res = await axios.get('/api/grammars')
    if (res.data?.data) {
      grammars.value = res.data.data
    } else if (Array.isArray(res.data)) {
      grammars.value = res.data
    }
  } catch (err) {
    console.error('Lỗi tải danh sách ngữ pháp:', err)
  } finally {
    isLoading.value = false
  }
}

const basicCount = computed(() => grammars.value.filter(g => g.level === 'Cơ bản').length)
const intermediateCount = computed(() => grammars.value.filter(g => g.level === 'Trung cấp').length)
const advancedCount = computed(() => grammars.value.filter(g => g.level === 'Nâng cao').length)

const filteredGrammars = computed(() => {
  return grammars.value.filter(g => {
    const matchSearch = !searchQuery.value ||
      g.title?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      g.summary?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      g.slug?.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchLevel = filterLevel.value === 'all' || g.level === filterLevel.value
    return matchSearch && matchLevel
  })
})

const paginatedGrammars = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value
  return filteredGrammars.value.slice(start, start + pageSize.value)
})

const openModal = (item) => {
  if (item) {
    isEditing.value = true
    form.value = { ...item }
  } else {
    isEditing.value = false
    form.value = {
      id: null,
      title: '',
      slug: '',
      level: 'Cơ bản',
      order_index: grammars.value.length + 1,
      badge_color: '#00b074',
      summary: '',
      structure: '',
      signal_words: ''
    }
  }
  showModal.value = true
}

const saveGrammar = async () => {
  if (!form.value.title || !form.value.slug) {
    alert('Vui lòng nhập đầy đủ tiêu đề và slug URL')
    return
  }

  if (isEditing.value) {
    const idx = grammars.value.findIndex(g => g.id === form.value.id)
    if (idx !== -1) {
      grammars.value[idx] = { ...grammars.value[idx], ...form.value }
    }
  } else {
    const newId = Date.now()
    grammars.value.push({
      ...form.value,
      id: newId,
      total_exercises: 20
    })
  }

  showModal.value = false
  alert('Đã cập nhật chuyên đề ngữ pháp thành công!')
}

const deleteGrammar = (id) => {
  if (confirm('Bạn có chắc chắn muốn xóa chuyên đề ngữ pháp này?')) {
    grammars.value = grammars.value.filter(g => g.id !== id)
    alert('Đã xóa chuyên đề thành công!')
  }
}

onMounted(() => {
  fetchGrammars()
})
</script>

<style scoped>
.btn-outline-teal {
  color: #00b074;
  border-color: #00b074;
}
.btn-outline-teal:hover {
  background-color: #00b074;
  color: #ffffff;
}
</style>
