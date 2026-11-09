<template>
  <div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">CỤM TỪ & PHRASAL VERBS</span>
        <h2 class="fw-black text-teal mb-0">Quản Lý Cụm Từ & Đồng Bộ Từ Đơn</h2>
        <div class="text-muted fs-7">Cấu hình cụm động từ, collocation, thành ngữ và danh sách từ đơn cấu thành để đồng bộ tiến độ</div>
      </div>

      <div class="d-flex gap-2">
        <button @click="openModal(null)" class="btn btn-teal btn-sm rounded-pill px-3 py-2 shadow-sm fw-bold">
          <i class="bi bi-plus-circle-fill me-1"></i> Thêm cụm từ mới
        </button>
      </div>
    </div>

    <!-- Phrasal Verbs Table -->
    <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4">
      <div class="card-header bg-teal text-white py-2.5 py-md-3 px-3 px-md-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h6 class="mb-0 fw-bold">Danh Sách Cụm Từ ({{ phrases.length }})</h6>
        <div class="d-flex gap-2 w-100 w-sm-auto justify-content-end">
          <input
            v-model="searchPhrase"
            type="text"
            placeholder="Tìm kiếm cụm từ..."
            class="form-control form-control-sm rounded-pill px-3 fs-8 w-100 w-sm-auto"
            style="min-width: 180px; max-width: 260px;"
          />
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light fs-8 text-uppercase">
            <tr>
              <th style="width: 50px;">ID</th>
              <th>Cụm từ (Phrase)</th>
              <th>Loại</th>
              <th>Nghĩa tiếng Việt</th>
              <th>Từ đơn cấu thành (Component Words)</th>
              <th>Ví dụ</th>
              <th class="text-center">Cấp độ</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody class="fs-7">
            <tr v-for="p in paginatedPhrases" :key="p.id">
              <td class="text-muted fw-bold">{{ p.id }}</td>
              <td class="fw-bold text-teal fs-6">{{ p.phrase }}</td>
              <td>
                <span class="badge" :class="p.type === 'phrasal_verb' ? 'bg-primary-subtle text-primary border border-primary-subtle' : p.type === 'collocation' ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle'">
                  {{ p.type }}
                </span>
              </td>
              <td class="fw-semibold text-success">{{ p.meaning_vi }}</td>
              <td>
                <div class="d-flex gap-1 flex-wrap">
                  <span v-for="w in p.component_words_json || []" :key="w" class="badge bg-secondary-subtle text-secondary font-monospace" style="font-size: 11px;">
                    {{ w }}
                  </span>
                </div>
              </td>
              <td class="fs-8 text-muted text-truncate" style="max-width: 200px;">
                {{ p.example_en }}
              </td>
              <td class="text-center"><span class="badge bg-teal">{{ p.cefr_level || 'B1' }}</span></td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <button @click="openModal(p)" class="btn btn-outline-secondary" title="Sửa cụm từ">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button @click="deletePhrase(p.id)" class="btn btn-outline-danger" title="Xóa cụm từ">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredPhrases.length === 0">
              <td colspan="8" class="text-center py-4 text-muted">Không tìm thấy cụm từ nào.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Smart 50-Item Pagination -->
      <SmartPagination
        v-model:currentPage="currentPage"
        v-model:pageSize="pageSize"
        :totalItems="filteredPhrases.length"
        :pageSizeOptions="[25, 50, 100, 200]"
      />
    </div>

    <!-- MODAL: ADD / EDIT PHRASAL VERB -->
    <div v-if="showModal" class="modal fade show d-block" style="background: rgba(0,0,0,0.6);" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow bg-card-custom">
          <div class="modal-header bg-teal text-white py-3">
            <h5 class="modal-title fw-bold">
              {{ editingPhrase?.id ? 'Chỉnh Sửa Cụm Từ' : 'Thêm Cụm Từ Mới' }}
            </h5>
            <button @click="showModal = false" type="button" class="btn-close btn-close-white"></button>
          </div>
          <div class="modal-body p-4">
            <form @submit.prevent="savePhrase">
              <div class="row g-3 mb-3">
                <div class="col-md-8">
                  <label class="form-label fs-7 fw-bold mb-1">Cụm từ tiếng Anh (Phrase):</label>
                  <input v-model="form.phrase" type="text" class="form-control rounded-3 fs-7" required placeholder="VD: look forward to" />
                </div>
                <div class="col-md-4">
                  <label class="form-label fs-7 fw-bold mb-1">Thể loại:</label>
                  <select v-model="form.type" class="form-select rounded-3 fs-7">
                    <option value="phrasal_verb">Cụm động từ (Phrasal Verb)</option>
                    <option value="collocation">Cụm từ cố định (Collocation)</option>
                    <option value="idiom">Thành ngữ (Idiom)</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fs-7 fw-bold mb-1">Nghĩa tiếng Việt:</label>
                  <input v-model="form.meaning_vi" type="text" class="form-control rounded-3 fs-7" required placeholder="VD: Trông mong, háo hức chờ đợi điều gì" />
                </div>
                <div class="col-md-8">
                  <label class="form-label fs-7 fw-bold mb-1">Các từ đơn cấu thành (Cách nhau bằng dấu phẩy):</label>
                  <input v-model="form.componentsInput" type="text" class="form-control rounded-3 fs-7 font-monospace" placeholder="VD: look, forward" />
                  <div class="fs-8 text-muted mt-1">Khi học viên thuộc cụm từ này, hệ thống sẽ tự động cập nhật tiến độ các từ đơn trên.</div>
                </div>
                <div class="col-md-4">
                  <label class="form-label fs-7 fw-bold mb-1">Cấp độ CEFR:</label>
                  <select v-model="form.cefr_level" class="form-select rounded-3 fs-7">
                    <option value="A1">A1</option>
                    <option value="A2">A2</option>
                    <option value="B1">B1</option>
                    <option value="B2">B2</option>
                    <option value="C1">C1</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fs-7 fw-bold mb-1">Câu ví dụ tiếng Anh:</label>
                  <textarea v-model="form.example_en" class="form-control rounded-3 fs-7" rows="2" placeholder="VD: I look forward to hearing from you."></textarea>
                </div>
                <div class="col-12">
                  <label class="form-label fs-7 fw-bold mb-1">Dịch nghĩa câu ví dụ (Tiếng Việt):</label>
                  <textarea v-model="form.example_vi" class="form-control rounded-3 fs-7" rows="2" placeholder="VD: Tôi rất mong sớm nhận được phản hồi từ bạn."></textarea>
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                <button @click="showModal = false" type="button" class="btn btn-secondary rounded-pill px-3 fs-7">
                  Hủy
                </button>
                <button type="submit" class="btn btn-teal rounded-pill px-4 fs-7 fw-bold shadow-sm">
                  Lưu Cụm Từ
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import SmartPagination from '@/components/SmartPagination.vue'
import { useToast } from '@/composables/useToast'

const toast = useToast()

const phrases = ref([])
const searchPhrase = ref('')
const currentPage = ref(1)
const pageSize = ref(50) // Mặc định 50 mục/trang
const showModal = ref(false)
const editingPhrase = ref(null)

const form = ref({
  phrase: '',
  type: 'phrasal_verb',
  meaning_vi: '',
  componentsInput: '',
  example_en: '',
  example_vi: '',
  cefr_level: 'B1',
})

const filteredPhrases = computed(() => {
  if (!searchPhrase.value.trim()) return phrases.value
  const q = searchPhrase.value.toLowerCase().trim()
  return phrases.value.filter(p => p.phrase.toLowerCase().includes(q) || p.meaning_vi.toLowerCase().includes(q))
})

const paginatedPhrases = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value
  return filteredPhrases.value.slice(start, start + pageSize.value)
})

const fetchPhrases = async () => {
  try {
    const res = await axios.get('/api/phrasal-verbs')
    phrases.value = res.data.data.items || []
  } catch (err) {
    console.error('Failed to load phrases:', err)
  }
}

const openModal = (p = null) => {
  editingPhrase.value = p
  if (p) {
    form.value = {
      phrase: p.phrase,
      type: p.type,
      meaning_vi: p.meaning_vi,
      componentsInput: Array.isArray(p.component_words_json) ? p.component_words_json.join(', ') : '',
      example_en: p.example_en || '',
      example_vi: p.example_vi || '',
      cefr_level: p.cefr_level || 'B1',
    }
  } else {
    form.value = {
      phrase: '',
      type: 'phrasal_verb',
      meaning_vi: '',
      componentsInput: '',
      example_en: '',
      example_vi: '',
      cefr_level: 'B1',
    }
  }
  showModal.value = true
}

const savePhrase = async () => {
  try {
    const components = form.value.componentsInput
      .split(',')
      .map(s => s.trim().toLowerCase())
      .filter(Boolean)

    const payload = {
      phrase: form.value.phrase,
      type: form.value.type,
      meaning_vi: form.value.meaning_vi,
      component_words_json: components,
      example_en: form.value.example_en,
      example_vi: form.value.example_vi,
      cefr_level: form.value.cefr_level,
    }

    if (editingPhrase.value?.id) {
      await axios.put(`/api/phrasal-verbs/${editingPhrase.value.id}`, payload)
      toast.success('Đã cập nhật cụm từ thành công!', 'Quản trị Cụm từ')
    } else {
      await axios.post('/api/phrasal-verbs', payload)
      toast.success('Đã thêm cụm từ mới thành công!', 'Quản trị Cụm từ')
    }
    showModal.value = false
    fetchPhrases()
  } catch (err) {
    toast.error('Lỗi: ' + (err.response?.data?.message || err.message), 'Lỗi hệ thống')
  }
}

const deletePhrase = async (id) => {
  if (confirm('Bạn có chắc chắn muốn xóa cụm từ này?')) {
    try {
      await axios.delete(`/api/phrasal-verbs/${id}`)
      toast.success('Đã xóa cụm từ thành công!', 'Quản trị Cụm từ')
      fetchPhrases()
    } catch (err) {
      toast.error('Không thể xóa cụm từ này.', 'Lỗi hệ thống')
    }
  }
}

onMounted(() => {
  fetchPhrases()
})
</script>
