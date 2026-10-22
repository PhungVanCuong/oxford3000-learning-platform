<template>
  <div class="admin-curriculum-view pb-5">
    <!-- Header -->
    <div class="card border-0 bg-white shadow-sm rounded-4 p-4 mb-4">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
          <div class="d-inline-flex align-items-center gap-2 bg-teal-subtle text-teal px-3 py-1 rounded-pill mb-2 fs-8 fw-bold">
            <i class="bi bi-signpost-split-fill"></i>
            <span>DỮ LIỆU LỘ TRÌNH 25 CHỦ ĐỀ CHUYÊN SÂU</span>
          </div>
          <h3 class="fw-black text-dark-emphasis mb-1">Quản lý Lộ trình 25 Chủ Đề & 5.000 Từ vựng</h3>
          <p class="text-muted fs-7 mb-0">
            Quản trị 25 file CSV học thuật, cấu trúc 10 giai đoạn (200 từ vựng/chủ đề), phân bổ bài kiểm tra trắc nghiệm.
          </p>
        </div>

        <div class="d-flex gap-2">
          <button 
            v-if="selectedTopic" 
            class="btn btn-outline-secondary fs-7 d-flex align-items-center gap-1.5"
            @click="selectedTopic = null"
          >
            <i class="bi bi-arrow-left"></i>
            <span>Về danh sách 25 Chủ đề</span>
          </button>

          <button 
            v-if="selectedTopic" 
            class="btn btn-teal fs-7 d-flex align-items-center gap-1.5"
            @click="openAddTermModal"
          >
            <i class="bi bi-plus-circle-fill"></i>
            <span>Thêm từ vựng vào Chủ đề</span>
          </button>
        </div>
      </div>
    </div>

    <!-- VIEW 1: 25 TOPICS LIST -->
    <div v-if="!selectedTopic">
      <!-- Loading State -->
      <div v-if="loadingTopics" class="text-center py-5">
        <div class="spinner-border text-teal" role="status">
          <span class="visually-hidden">Đang tải...</span>
        </div>
      </div>

      <div v-else class="row g-4">
        <div 
          v-for="t in topics" 
          :key="t.id"
          class="col-12 col-md-6 col-xl-4"
        >
          <div class="card h-100 border-light-subtle shadow-sm hover-card rounded-4 d-flex flex-column">
            <div class="card-body p-4 flex-grow-1 d-flex flex-column">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                  <div 
                    class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
                    :style="{ width: '48px', height: '48px', backgroundColor: t.color || '#00b074' }"
                  >
                    <i :class="['bi', t.icon || 'bi-journal-bookmark-fill', 'fs-4']"></i>
                  </div>
                  <div>
                    <span class="badge bg-secondary-subtle text-secondary-emphasis fw-bold" style="font-size: 10px;">
                      #{{ String(t.order_num).padStart(2, '0') }} • {{ t.csv_filename }}
                    </span>
                    <h5 class="fw-bold mb-0 text-dark-emphasis mt-1">{{ t.title_vi }}</h5>
                  </div>
                </div>

                <button 
                  class="btn btn-sm btn-outline-secondary rounded-circle"
                  style="width: 32px; height: 32px;"
                  title="Chỉnh sửa chủ đề"
                  @click="openEditTopicModal(t)"
                >
                  <i class="bi bi-pencil-fill" style="font-size: 11px;"></i>
                </button>
              </div>

              <div class="text-secondary fw-semibold fs-7 mb-2">
                {{ t.title }}
              </div>

              <p class="text-muted fs-7 mb-3 flex-grow-1">
                {{ t.description }}
              </p>

              <div class="p-2.5 rounded-3 bg-light border border-light-subtle d-flex justify-content-between align-items-center fs-7 text-muted">
                <span>Số lượng từ vựng:</span>
                <strong class="text-teal fs-6">{{ t.terms_count || 200 }} từ (10 chặng)</strong>
              </div>
            </div>

            <div class="card-footer bg-transparent border-top-0 p-3 pt-0">
              <button 
                class="btn btn-teal w-100 py-2 d-flex align-items-center justify-content-center gap-2 fs-7"
                @click="selectTopic(t)"
              >
                <i class="bi bi-folder2-open"></i>
                <span>Xem & Quản lý {{ t.terms_count || 200 }} từ vựng</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- VIEW 2: TERMS OF SELECTED TOPIC -->
    <div v-else>
      <!-- Topic Header Info -->
      <div class="card border-light-subtle shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
          <div class="d-flex align-items-center gap-3">
            <div 
              class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
              :style="{ width: '48px', height: '48px', backgroundColor: selectedTopic.color || '#00b074' }"
            >
              <i :class="['bi', selectedTopic.icon || 'bi-journal-bookmark-fill', 'fs-4']"></i>
            </div>
            <div>
              <span class="badge bg-secondary-subtle text-secondary-emphasis fw-bold fs-8">
                CHỦ ĐỀ #{{ String(selectedTopic.order_num).padStart(2, '0') }}
              </span>
              <h4 class="fw-black mb-0 text-dark-emphasis mt-1">
                {{ selectedTopic.title_vi }} <span class="fs-6 text-muted fw-normal">({{ selectedTopic.title }})</span>
              </h4>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-teal-subtle text-teal fw-bold fs-7 px-3 py-2 rounded-pill">
              {{ termsPagination.total || 0 }} từ vựng trong kho
            </span>
          </div>
        </div>

        <hr class="my-3 opacity-25">

        <!-- Stage Filter & Search Bar -->
        <div class="row g-3 align-items-center">
          <div class="col-12 col-md-4">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted">
                <i class="bi bi-search"></i>
              </span>
              <input 
                type="text" 
                v-model="termSearch" 
                @input="debouncedSearch"
                class="form-control border-start-0 ps-0 fs-7" 
                placeholder="Tìm kiếm từ tiếng Anh hoặc nghĩa..."
              />
            </div>
          </div>

          <div class="col-12 col-md-3">
            <select 
              v-model="selectedStageFilter" 
              class="form-select fs-7"
              @change="fetchTerms(1)"
            >
              <option value="">-- Tất cả 10 Giai đoạn --</option>
              <option v-for="s in 10" :key="s" :value="s">
                Giai đoạn {{ s }} ({{ getStageQuestionCount(s) }} câu)
              </option>
            </select>
          </div>

          <div class="col-12 col-md-3">
            <select 
              v-model="perPage" 
              class="form-select fs-7 fw-semibold"
              @change="fetchTerms(1)"
            >
              <option :value="200">Hiển thị Toàn bộ 200 từ vựng</option>
              <option :value="100">100 từ / trang</option>
              <option :value="50">50 từ / trang</option>
              <option :value="25">25 từ / trang</option>
            </select>
          </div>

          <div class="col-12 col-md-2 text-md-end">
            <button class="btn btn-outline-secondary fs-7 w-100" @click="fetchTerms(1)">
              <i class="bi bi-arrow-clockwise me-1"></i>Làm mới
            </button>
          </div>
        </div>
      </div>

      <!-- Terms Table -->
      <div class="card border-light-subtle shadow-sm rounded-4 overflow-hidden mb-4">
        <div v-if="loadingTerms" class="text-center py-5">
          <div class="spinner-border text-teal" role="status"></div>
        </div>

        <div v-else-if="terms.length === 0" class="text-center py-5">
          <i class="bi bi-inbox fs-1 text-muted"></i>
          <p class="text-muted mt-2 fs-7">Không có từ vựng nào phù hợp với bộ lọc hiện tại.</p>
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr class="fs-8 text-uppercase text-muted fw-bold">
                <th style="width: 60px;" class="ps-4">STT</th>
                <th style="width: 100px;">Giai đoạn</th>
                <th style="width: 220px;">Thuật ngữ (Term)</th>
                <th>Định nghĩa Tiếng Việt</th>
                <th style="width: 180px;">Mẫu câu (Pattern)</th>
                <th style="width: 110px;" class="text-end pe-4">Thao tác</th>
              </tr>
            </thead>
            <tbody class="fs-7">
              <tr v-for="t in terms" :key="t.id">
                <td class="ps-4 text-muted fw-bold">#{{ t.order_in_topic }}</td>
                <td>
                  <span class="badge bg-teal-subtle text-teal fw-bold">
                    Chặng {{ t.stage_num }}
                  </span>
                </td>
                <td>
                  <div class="fw-bold text-dark-emphasis">{{ t.term }}</div>
                  <span v-if="t.part_of_speech" class="badge bg-light text-secondary border mt-1" style="font-size: 10px;">
                    {{ t.part_of_speech }}
                  </span>
                </td>
                <td>
                  <div class="text-dark-emphasis fw-semibold">{{ t.definition }}</div>
                  <div v-if="t.examples" class="text-muted fs-8 mt-1 text-truncate" style="max-width: 380px;">
                    <i class="bi bi-quote"></i> {{ t.examples.split('|')[0] }}
                  </div>
                </td>
                <td>
                  <span class="text-muted fs-8">{{ t.pattern || '—' }}</span>
                </td>
                <td class="text-end pe-4">
                  <div class="btn-group">
                    <button 
                      class="btn btn-sm btn-outline-secondary" 
                      title="Chỉnh sửa từ"
                      @click="openEditTermModal(t)"
                    >
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button 
                      class="btn btn-sm btn-outline-danger" 
                      title="Xóa từ"
                      @click="deleteTerm(t)"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="termsPagination.total > termsPagination.per_page" class="card-footer bg-white p-3 d-flex justify-content-between align-items-center">
          <span class="fs-8 text-muted">
            Hiển thị {{ termsPagination.from }} - {{ termsPagination.to }} trong tổng {{ termsPagination.total }} từ
          </span>
          <div class="btn-group">
            <button 
              class="btn btn-sm btn-outline-secondary"
              :disabled="termsPagination.current_page <= 1"
              @click="fetchTerms(termsPagination.current_page - 1)"
            >
              Trước
            </button>
            <button 
              class="btn btn-sm btn-outline-secondary"
              :disabled="termsPagination.current_page >= termsPagination.last_page"
              @click="fetchTerms(termsPagination.current_page + 1)"
            >
              Sau
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= MODAL: ADD / EDIT TERM ================= -->
    <div 
      v-if="showTermModal" 
      class="modal-backdrop-custom" 
      @click.self="showTermModal = false"
    >
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 580px; width: 100%;">
        <div class="card-header bg-teal text-white p-3 d-flex align-items-center justify-content-between">
          <h5 class="fw-bold mb-0">{{ editingTerm ? 'Chỉnh sửa Từ vựng Lộ trình' : 'Thêm Từ vựng Mới vào Lộ trình' }}</h5>
          <button type="button" class="btn-close btn-close-white" @click="showTermModal = false"></button>
        </div>

        <form @submit.prevent="saveTerm">
          <div class="card-body p-4">
            <div class="mb-3">
              <label class="form-label fs-7 fw-bold">Thuật ngữ Tiếng Anh (Term) <span class="text-danger">*</span></label>
              <input type="text" v-model="termForm.term" class="form-control" required placeholder="Ví dụ: compulsory education" />
            </div>

            <div class="mb-3">
              <label class="form-label fs-7 fw-bold">Nghĩa Tiếng Việt (Definition) <span class="text-danger">*</span></label>
              <input type="text" v-model="termForm.definition" class="form-control" required placeholder="Ví dụ: giáo dục bắt buộc" />
            </div>

            <div class="row g-3 mb-3">
              <div class="col-6">
                <label class="form-label fs-7 fw-bold">Giai đoạn (1 - 10) <span class="text-danger">*</span></label>
                <select v-model="termForm.stage_num" class="form-select" required>
                  <option v-for="s in 10" :key="s" :value="s">Giai đoạn {{ s }}</option>
                </select>
              </div>

              <div class="col-6">
                <label class="form-label fs-7 fw-bold">Loại từ (Part of Speech)</label>
                <input type="text" v-model="termForm.part_of_speech" class="form-control" placeholder="Ví dụ: Cụm danh từ, Động từ..." />
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fs-7 fw-bold">Mẫu câu / Cấu trúc (Pattern)</label>
              <input type="text" v-model="termForm.pattern" class="form-control" placeholder="Ví dụ: extend/complete + compulsory education" />
            </div>

            <div class="mb-3">
              <label class="form-label fs-7 fw-bold">Câu ví dụ (Phân tách nhiều câu bằng dấu |)</label>
              <textarea v-model="termForm.examples" class="form-control" rows="3" placeholder="Ví dụ câu tiếng Anh 1 | Ví dụ câu tiếng Anh 2"></textarea>
            </div>
          </div>

          <div class="card-footer bg-light p-3 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary btn-sm px-3" @click="showTermModal = false">Hủy</button>
            <button type="submit" class="btn btn-teal btn-sm px-4" :disabled="savingTerm">
              <span v-if="savingTerm" class="spinner-border spinner-border-sm me-1"></span>
              {{ editingTerm ? 'Lưu thay đổi' : 'Thêm từ vựng' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================= MODAL: EDIT TOPIC ================= -->
    <div 
      v-if="showTopicModal" 
      class="modal-backdrop-custom" 
      @click.self="showTopicModal = false"
    >
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 540px; width: 100%;">
        <div class="card-header bg-teal text-white p-3 d-flex align-items-center justify-content-between">
          <h5 class="fw-bold mb-0">Chỉnh sửa Chủ đề #{{ editingTopic?.order_num }}</h5>
          <button type="button" class="btn-close btn-close-white" @click="showTopicModal = false"></button>
        </div>

        <form @submit.prevent="saveTopic">
          <div class="card-body p-4">
            <div class="mb-3">
              <label class="form-label fs-7 fw-bold">Tên tiếng Anh (English Title)</label>
              <input type="text" v-model="topicForm.title" class="form-control" required />
            </div>

            <div class="mb-3">
              <label class="form-label fs-7 fw-bold">Tên tiếng Việt</label>
              <input type="text" v-model="topicForm.title_vi" class="form-control" required />
            </div>

            <div class="row g-3 mb-3">
              <div class="col-6">
                <label class="form-label fs-7 fw-bold">Biểu tượng Bootstrap Icon</label>
                <input type="text" v-model="topicForm.icon" class="form-control" placeholder="bi-mortarboard-fill" />
              </div>
              <div class="col-6">
                <label class="form-label fs-7 fw-bold">Mã màu đại diện</label>
                <input type="color" v-model="topicForm.color" class="form-control form-control-color w-100" />
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fs-7 fw-bold">Mô tả chủ đề</label>
              <textarea v-model="topicForm.description" class="form-control" rows="3"></textarea>
            </div>
          </div>

          <div class="card-footer bg-light p-3 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary btn-sm px-3" @click="showTopicModal = false">Hủy</button>
            <button type="submit" class="btn btn-teal btn-sm px-4">Lưu chủ đề</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const topics = ref([])
const loadingTopics = ref(true)
const selectedTopic = ref(null)

const terms = ref([])
const loadingTerms = ref(false)
const termSearch = ref('')
const selectedStageFilter = ref('')
const perPage = ref(200)
const termsPagination = ref({})

// Modal States
const showTermModal = ref(false)
const editingTerm = ref(null)
const savingTerm = ref(false)
const termForm = ref({
  term: '',
  definition: '',
  stage_num: 1,
  part_of_speech: '',
  pattern: '',
  examples: ''
})

const showTopicModal = ref(false)
const editingTopic = ref(null)
const topicForm = ref({
  title: '',
  title_vi: '',
  icon: '',
  color: '#00b074',
  description: ''
})

const stageQuestionsMap = { 1: 10, 2: 12, 3: 14, 4: 16, 5: 20, 6: 24, 7: 24, 8: 26, 9: 26, 10: 28 }
const getStageQuestionCount = (s) => stageQuestionsMap[s] || 20

// Fetch Topics
const fetchTopics = async () => {
  loadingTopics.value = true
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}
    const res = await axios.get('/api/admin/curriculum/topics', { headers })
    if (res.data?.status === 'success') {
      topics.value = res.data.data
    }
  } catch (err) {
    console.error('Error fetching admin topics:', err)
  } finally {
    loadingTopics.value = false
  }
}

// Select Topic
const selectTopic = (t) => {
  selectedTopic.value = t
  termSearch.value = ''
  selectedStageFilter.value = ''
  fetchTerms(1)
}

// Fetch Terms for selected topic
const fetchTerms = async (page = 1) => {
  if (!selectedTopic.value) return
  loadingTerms.value = true
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}

    const params = {
      page,
      per_page: perPage.value,
      stage_num: selectedStageFilter.value || undefined,
      search: termSearch.value || undefined
    }

    const res = await axios.get(`/api/admin/curriculum/topics/${selectedTopic.value.id}/terms`, {
      headers,
      params
    })

    if (res.data?.status === 'success') {
      terms.value = res.data.data.data
      termsPagination.value = res.data.data
    }
  } catch (err) {
    console.error('Error fetching terms:', err)
  } finally {
    loadingTerms.value = false
  }
}

let debounceTimer = null
const debouncedSearch = () => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    fetchTerms(1)
  }, 300)
}

// Term CRUD
const openAddTermModal = () => {
  editingTerm.value = null
  termForm.value = {
    term: '',
    definition: '',
    stage_num: selectedStageFilter.value ? parseInt(selectedStageFilter.value) : 1,
    part_of_speech: 'Cụm danh từ',
    pattern: '',
    examples: ''
  }
  showTermModal.value = true
}

const openEditTermModal = (term) => {
  editingTerm.value = term
  termForm.value = {
    term: term.term,
    definition: term.definition,
    stage_num: term.stage_num,
    part_of_speech: term.part_of_speech || '',
    pattern: term.pattern || '',
    examples: term.examples || ''
  }
  showTermModal.value = true
}

const saveTerm = async () => {
  savingTerm.value = true
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}

    if (editingTerm.value) {
      await axios.put(`/api/admin/curriculum/terms/${editingTerm.value.id}`, termForm.value, { headers })
    } else {
      await axios.post('/api/admin/curriculum/terms', {
        ...termForm.value,
        topic_id: selectedTopic.value.id
      }, { headers })
    }

    showTermModal.value = false
    fetchTerms(termsPagination.value.current_page || 1)
  } catch (err) {
    alert(err.response?.data?.message || 'Lỗi khi lưu từ vựng.')
  } finally {
    savingTerm.value = false
  }
}

const deleteTerm = async (term) => {
  if (!confirm(`Bạn có chắc muốn xóa thuật ngữ "${term.term}"?`)) return
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}
    await axios.delete(`/api/admin/curriculum/terms/${term.id}`, { headers })
    fetchTerms(termsPagination.value.current_page || 1)
  } catch (err) {
    alert('Lỗi khi xóa từ vựng.')
  }
}

// Topic Edit
const openEditTopicModal = (topic) => {
  editingTopic.value = topic
  topicForm.value = {
    title: topic.title,
    title_vi: topic.title_vi,
    icon: topic.icon,
    color: topic.color || '#00b074',
    description: topic.description
  }
  showTopicModal.value = true
}

const saveTopic = async () => {
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}

    await axios.put(`/api/admin/curriculum/topics/${editingTopic.value.id}`, topicForm.value, { headers })
    showTopicModal.value = false
    fetchTopics()
  } catch (err) {
    alert('Lỗi khi cập nhật chủ đề.')
  }
}

onMounted(() => {
  fetchTopics()
})
</script>
