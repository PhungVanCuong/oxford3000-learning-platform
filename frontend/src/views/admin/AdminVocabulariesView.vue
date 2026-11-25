<template>
  <div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">KHO TỪ VỰNG & GEMINI AI</span>
        <h2 class="fw-black text-teal mb-0">Quản Lý 3000 Từ Vựng & Sinh Ví Dụ Tự Động</h2>
        <div class="text-muted fs-7">Biên soạn kho từ Oxford 3000, phát âm, phiên âm và tạo câu ví dụ qua Gemini AI</div>
      </div>

      <div class="d-flex gap-2">
        <button @click="openVocabModal(null)" class="btn btn-teal btn-sm rounded-pill px-3 py-2 shadow-sm fw-bold">
          <i class="bi bi-plus-circle-fill me-1"></i> Thêm từ vựng mới
        </button>
      </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-4">
      <div class="row g-2 align-items-center">
        <div class="col-12 col-md-8">
          <div class="input-group">
            <span class="input-group-text bg-body-tertiary border-end-0">
              <i class="bi bi-search text-teal"></i>
            </span>
            <input
              v-model="vocabSearch"
              @input="handleSearch"
              type="text"
              class="form-control border-start-0 py-2 fs-7"
              placeholder="Tìm theo từ tiếng Anh hoặc nghĩa tiếng Việt..."
            />
          </div>
        </div>

        <div class="col-12 col-md-4 text-md-end text-muted fs-8">
          Tổng số từ tìm thấy: <strong class="text-teal fs-7">{{ totalCount }}</strong> từ
        </div>
      </div>
    </div>

    <!-- Vocabularies Table -->
    <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light fs-8 text-uppercase">
            <tr>
              <th style="width: 50px;">ID</th>
              <th>Từ tiếng Anh</th>
              <th>Phiên âm US</th>
              <th>Loại từ</th>
              <th>Nghĩa tiếng Việt</th>
              <th>Ví dụ tiếng Anh</th>
              <th class="text-center">Cấp độ</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody class="fs-7">
            <tr v-for="w in vocabList" :key="w.id">
              <td class="text-muted fw-bold">{{ w.id }}</td>
              <td class="fw-bold text-teal fs-6">{{ w.word }}</td>
              <td class="font-monospace text-muted">{{ w.phonetic_us }}</td>
              <td><span class="badge bg-secondary-subtle text-secondary">{{ w.part_of_speech }}</span></td>
              <td class="fw-semibold text-success">{{ w.meaning_vi }}</td>
              <td class="fs-8 text-muted text-truncate" style="max-width: 250px;">
                <div class="text-dark-emphasis">{{ w.example_en }}</div>
                <div class="fst-italic">{{ w.example_vi }}</div>
              </td>
              <td class="text-center"><span class="badge bg-teal">{{ w.cefr_level }}</span></td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <button @click="openVocabModal(w)" class="btn btn-outline-secondary" title="Sửa từ vựng">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button @click="deleteVocab(w.id)" class="btn btn-outline-danger" title="Xóa từ vựng">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="vocabList.length === 0">
              <td colspan="8" class="text-center py-5 text-muted">
                {{ isLoading ? 'Đang tải dữ liệu từ vựng...' : 'Không tìm thấy từ vựng nào.' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Smart 50-Item Pagination -->
      <SmartPagination
        v-model:currentPage="currentPage"
        v-model:pageSize="pageSize"
        :totalItems="totalCount"
        :pageSizeOptions="[25, 50, 100, 200]"
        @change="fetchVocabs"
      />
    </div>

    <!-- STANDARDIZED VIEWPORT-CENTERED VOCABULARY MODAL WITH GEMINI AI GENERATION -->
    <BaseModal
      v-model="showVocabModal"
      :title="editingVocab?.id ? 'Chỉnh Sửa Từ Vựng' : 'Thêm Từ Vựng Mới'"
      icon="bi bi-book-half"
      maxWidth="780px"
      hideFooter
    >
      <form @submit.prevent="saveVocab">
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label fs-7 fw-bold mb-1">Từ tiếng Anh (Word)</label>
            <input v-model="vocabForm.word" type="text" class="form-control rounded-3 fs-7" required />
          </div>
          <div class="col-md-6">
            <label class="form-label fs-7 fw-bold mb-1">Phiên âm US</label>
            <input v-model="vocabForm.phonetic_us" type="text" class="form-control rounded-3 fs-7" placeholder="/.../" />
          </div>
          <div class="col-md-6">
            <label class="form-label fs-7 fw-bold mb-1">Loại từ</label>
            <input v-model="vocabForm.part_of_speech" type="text" class="form-control rounded-3 fs-7" placeholder="Động từ, Danh từ..." />
          </div>
          <div class="col-md-6">
            <label class="form-label fs-7 fw-bold mb-1">Cấp độ CEFR</label>
            <select v-model="vocabForm.cefr_level" class="form-select rounded-3 fs-7">
              <option value="A1">A1</option>
              <option value="A2">A2</option>
              <option value="B1">B1</option>
              <option value="B2">B2</option>
              <option value="C1">C1</option>
              <option value="C2">C2</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label fs-7 fw-bold mb-1">Nghĩa tiếng Việt</label>
            <input v-model="vocabForm.meaning_vi" type="text" class="form-control rounded-3 fs-7" required />
          </div>
        </div>

        <!-- Gemini AI Generator Button -->
        <div class="p-3 rounded-3 bg-body-tertiary border mb-3 d-flex justify-content-between align-items-center">
          <div>
            <strong class="text-teal fs-7"><i class="bi bi-stars text-warning me-1"></i> Gemini AI Tạo Ví Dụ Tự Động:</strong>
            <div class="text-muted fs-8">Tự động sinh câu ví dụ tiếng Anh tự nhiên & bản dịch chuẩn xác.</div>
          </div>
          <button
            type="button"
            @click="generateAiExamples"
            class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 shadow-sm"
            :disabled="aiGenerating || !vocabForm.word"
          >
            <span v-if="aiGenerating" class="spinner-border spinner-border-sm me-1"></span>
            <i v-else class="bi bi-magic me-1"></i>
            <span>AI Tạo Ngay</span>
          </button>
        </div>

        <div class="mb-3">
          <label class="form-label fs-7 fw-bold mb-1">Câu ví dụ tiếng Anh</label>
          <textarea v-model="vocabForm.example_en" class="form-control rounded-3 fs-7" rows="2"></textarea>
        </div>

        <div class="mb-4">
          <label class="form-label fs-7 fw-bold mb-1">Dịch nghĩa câu ví dụ (Tiếng Việt)</label>
          <textarea v-model="vocabForm.example_vi" class="form-control rounded-3 fs-7" rows="2"></textarea>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-3">
          <button type="button" @click="showVocabModal = false" class="btn btn-secondary rounded-pill px-3 fs-7">
            Hủy
          </button>
          <button type="submit" class="btn btn-teal rounded-pill px-4 fs-7 fw-bold shadow-sm">
            Lưu từ vựng
          </button>
        </div>
      </form>
    </BaseModal>

    <BaseModal
      v-model="showDeleteConfirm"
      title="Xác nhận Xóa Từ Vựng"
      icon="bi bi-trash-fill"
      maxWidth="440px"
      hideFooter
    >
      <p class="fs-7 mb-3 text-dark-emphasis">Bạn có chắc chắn muốn xóa từ vựng này khỏi kho dữ liệu không?</p>
      <div class="d-flex justify-content-end gap-2">
        <button @click="showDeleteConfirm = false" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Hủy bỏ</button>
        <button @click="confirmDeleteVocab" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold">Xóa ngay</button>
      </div>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import SmartPagination from '@/components/SmartPagination.vue'
import BaseModal from '@/components/BaseModal.vue'
import { useToast } from '@/composables/useToast'

const toast = useToast()

const vocabList = ref([])
const totalCount = ref(0)
const currentPage = ref(1)
const pageSize = ref(50)
const totalPages = ref(1)
const vocabSearch = ref('')
const isLoading = ref(false)

const showVocabModal = ref(false)
const editingVocab = ref(null)
const vocabForm = ref({
  word: '',
  phonetic_us: '',
  part_of_speech: 'Danh từ',
  meaning_vi: '',
  example_en: '',
  example_vi: '',
  cefr_level: 'A1',
})
const aiGenerating = ref(false)
let searchTimeout = null

const fetchVocabs = async (page = 1) => {
  isLoading.value = true
  try {
    const res = await axios.get('/api/dictionary/search', {
      params: {
        q: vocabSearch.value || undefined,
        page,
        per_page: pageSize.value,
      },
    })
    vocabList.value = res.data.data.items || []
    totalCount.value = res.data.data.total || 0
    currentPage.value = res.data.data.current_page || 1
    totalPages.value = res.data.data.last_page || 1
  } catch (err) {
    console.error('Failed to load vocabs:', err)
  } finally {
    isLoading.value = false
  }
}

const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    currentPage.value = 1
    fetchVocabs(1)
  }, 350)
}

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    fetchVocabs(page)
  }
}

const openVocabModal = (vocab = null) => {
  editingVocab.value = vocab
  if (vocab) {
    vocabForm.value = { ...vocab }
  } else {
    vocabForm.value = {
      word: '',
      phonetic_us: '',
      part_of_speech: 'Danh từ',
      meaning_vi: '',
      example_en: '',
      example_vi: '',
      cefr_level: 'A1',
    }
  }
  showVocabModal.value = true
}

const generateAiExamples = async () => {
  if (!vocabForm.value.word) return
  aiGenerating.value = true
  try {
    const res = await axios.post('/api/ai/generate-examples', {
      word: vocabForm.value.word,
      meaning_vi: vocabForm.value.meaning_vi,
      part_of_speech: vocabForm.value.part_of_speech,
    })
    const data = res.data.data
    vocabForm.value.example_en = data.example_en
    vocabForm.value.example_vi = data.example_vi
    if (data.phonetic_us && !vocabForm.value.phonetic_us) {
      vocabForm.value.phonetic_us = data.phonetic_us
    }
  } catch (err) {
    toast.error('Không thể tạo ví dụ tự động qua AI.', 'Lỗi AI')
  } finally {
    aiGenerating.value = false
  }
}

const saveVocab = async () => {
  try {
    if (editingVocab.value?.id) {
      await axios.put(`/api/admin/vocabularies/${editingVocab.value.id}`, vocabForm.value)
      toast.success('Đã cập nhật từ vựng thành công!', 'Quản trị Từ vựng')
    } else {
      await axios.post('/api/admin/vocabularies', vocabForm.value)
      toast.success('Đã thêm từ vựng mới thành công!', 'Quản trị Từ vựng')
    }
    showVocabModal.value = false
    fetchVocabs(currentPage.value)
  } catch (err) {
    toast.error('Lỗi lưu từ: ' + (err.response?.data?.message || err.message), 'Lỗi hệ thống')
  }
}

const showDeleteConfirm = ref(false)
const pendingDeleteId = ref(null)

const deleteVocab = (id) => {
  pendingDeleteId.value = id
  showDeleteConfirm.value = true
}

const confirmDeleteVocab = async () => {
  if (!pendingDeleteId.value) return
  try {
    await axios.delete(`/api/admin/vocabularies/${pendingDeleteId.value}`)
    toast.success('Đã xóa từ vựng thành công!', 'Quản trị Từ vựng')
    showDeleteConfirm.value = false
    pendingDeleteId.value = null
    fetchVocabs(currentPage.value)
  } catch (err) {
    toast.error('Lỗi khi xóa từ vựng.', 'Lỗi hệ thống')
  }
}

onMounted(() => {
  fetchVocabs()
})
</script>
