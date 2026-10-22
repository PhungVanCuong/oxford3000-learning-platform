<template>
  <div class="container py-4 max-w-6xl">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <span class="badge bg-danger text-white rounded-pill px-3 py-1 fs-8">HỆ THỐNG QUẢN TRỊ VIÊN</span>
        <h2 class="fw-black text-teal mb-0">Admin Management Portal</h2>
      </div>

      <div class="d-flex gap-2">
        <button @click="openAiTestModal" class="btn btn-outline-teal btn-sm rounded-pill px-3 py-1.5 shadow-sm">
          <i class="bi bi-stars me-1"></i> Trợ Lý Gemini AI
        </button>
        <router-link to="/profile" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 shadow-sm">
          Quay lại Trang cá nhân
        </router-link>
      </div>
    </div>

    <!-- 4 Stats Banner -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom hover-card">
          <div class="text-muted fs-8 text-uppercase fw-semibold mb-1">Tổng học viên</div>
          <div class="fs-2 fw-black text-teal">{{ stats.total_users || users.length || 10 }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom hover-card">
          <div class="text-muted fs-8 text-uppercase fw-semibold mb-1">Kho từ vựng</div>
          <div class="fs-2 fw-black text-teal">{{ stats.total_words || 3351 }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom hover-card">
          <div class="text-muted fs-8 text-uppercase fw-semibold mb-1">Chủ đề lộ trình</div>
          <div class="fs-2 fw-black text-teal">{{ stats.total_topics || 25 }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom hover-card">
          <div class="text-muted fs-8 text-uppercase fw-semibold mb-1">Lượt thi kiểm tra</div>
          <div class="fs-2 fw-black text-primary">{{ stats.total_quizzes || 0 }}</div>
        </div>
      </div>
    </div>

    <!-- Tab Switcher -->
    <ul class="nav nav-pills mb-4 gap-2">
      <li class="nav-item">
        <button
          @click="adminTab = 'users'"
          class="nav-link rounded-pill px-4 py-2 fw-bold fs-7 shadow-sm"
          :class="adminTab === 'users' ? 'btn-teal text-white' : 'btn-outline-secondary'"
        >
          <i class="bi bi-people-fill me-1"></i> Quản lý Tài khoản & Tiến độ ({{ users.length }})
        </button>
      </li>
      <li class="nav-item">
        <button
          @click="adminTab = 'vocab'"
          class="nav-link rounded-pill px-4 py-2 fw-bold fs-7 shadow-sm"
          :class="adminTab === 'vocab' ? 'btn-teal text-white' : 'btn-outline-secondary'"
        >
          <i class="bi bi-book-half me-1"></i> Quản lý Từ vựng & AI (3000+)
        </button>
      </li>
    </ul>

    <!-- TAB 1: USER MANAGEMENT -->
    <div v-if="adminTab === 'users'">
      <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4">
        <div class="card-header bg-teal text-white py-3 px-4 d-flex justify-content-between align-items-center">
          <h6 class="mb-0 fw-bold">Danh Sách Tài Khoản Học Viên & Theo Dõi Tiến Độ</h6>
          <button @click="openCreateUserModal" class="btn btn-sm btn-light text-teal rounded-pill px-3 py-1 fw-bold shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Thêm tài khoản mới
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light fs-8 text-uppercase">
              <tr>
                <th>Họ & Tên</th>
                <th>Email</th>
                <th>Quyền</th>
                <th>Ngày sinh</th>
                <th class="text-center">XP</th>
                <th class="text-center">Chuỗi</th>
                <th>Từ đã thuộc</th>
                <th>Ngữ pháp</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody class="fs-7">
              <tr v-for="u in users" :key="u.id">
                <td class="fw-bold text-teal">{{ u.name }}</td>
                <td class="text-muted">{{ u.email }}</td>
                <td>
                  <span class="badge" :class="u.role === 'admin' ? 'bg-danger' : 'bg-secondary'">
                    {{ u.role }}
                  </span>
                </td>
                <td class="text-muted">{{ u.birth_date || '18/10/2005' }}</td>
                <td class="text-center fw-bold text-primary">{{ u.xp_points }}</td>
                <td class="text-center text-warning fw-bold">{{ u.streak_days }} ngày</td>
                <td>
                  <span class="fw-bold text-success">{{ u.mastered_words }}</span>/3351
                </td>
                <td>
                  <span class="fw-bold text-teal">{{ u.completed_grammars }}</span>/15
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <button @click="viewUserDetails(u.id)" class="btn btn-outline-info" title="Xem chi tiết tiến độ">
                      <i class="bi bi-eye"></i>
                    </button>
                    <button @click="openEditUserModal(u)" class="btn btn-outline-secondary" title="Sửa thông tin">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button @click="resetUserProgress(u)" class="btn btn-outline-warning" title="Reset tiến độ học của user này">
                      <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                    <button @click="deleteUser(u.id)" class="btn btn-outline-danger" title="Xóa tài khoản" :disabled="u.email === 'cuongphung1810@gmail.com'">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB 2: VOCABULARY CRUD & AI GENERATOR -->
    <div v-else-if="adminTab === 'vocab'">
      <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4">
        <div class="card-header bg-teal text-white py-3 px-4 d-flex justify-content-between align-items-center">
          <h6 class="mb-0 fw-bold">Kho Dữ Liệu 3000 Từ Vựng Oxford</h6>
          <button @click="openVocabModal(null)" class="btn btn-sm btn-light text-teal rounded-pill px-3 py-1 fw-bold shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Thêm từ mới (Kèm AI)
          </button>
        </div>

        <div class="p-3 border-bottom">
          <input
            v-model="vocabSearch"
            @input="searchVocab"
            type="text"
            placeholder="Tìm kiếm nhanh theo từ hoặc nghĩa..."
            class="form-control rounded-pill px-4 fs-7"
          />
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light fs-8 text-uppercase">
              <tr>
                <th style="width: 50px;">ID</th>
                <th>Từ tiếng Anh</th>
                <th>Phiên âm</th>
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
                <td class="fs-8 text-muted text-truncate" style="max-width: 250px;">{{ w.example_en }}</td>
                <td class="text-center"><span class="badge bg-teal">{{ w.cefr_level }}</span></td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <button @click="openVocabModal(w)" class="btn btn-outline-secondary" title="Chỉnh sửa từ vựng">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button @click="deleteVocab(w.id)" class="btn btn-outline-danger" title="Xóa từ vựng">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- VOCABULARY MODAL WITH GEMINI AI GENERATION -->
    <div v-if="showVocabModal" class="modal fade show d-block" style="background-color: rgba(0,0,0,0.65);" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow bg-card-custom">
          <div class="modal-header bg-teal text-white py-3">
            <h5 class="modal-title fw-bold">
              {{ editingVocab?.id ? 'Chỉnh Sửa Từ Vựng' : 'Thêm Từ Vựng Mới' }}
            </h5>
            <button @click="showVocabModal = false" type="button" class="btn-close btn-close-white"></button>
          </div>
          <div class="modal-body p-4">
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

              <div class="d-flex justify-content-end gap-2">
                <button type="button" @click="showVocabModal = false" class="btn btn-secondary rounded-pill px-3 fs-7">
                  Hủy
                </button>
                <button type="submit" class="btn btn-teal rounded-pill px-4 fs-7 fw-bold shadow-sm">
                  Lưu từ vựng
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- AI ASSISTANT TEST MODAL -->
    <div v-if="showAiTest" class="modal fade show d-block" style="background-color: rgba(0,0,0,0.65);" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow bg-card-custom">
          <div class="modal-header bg-teal text-white py-3">
            <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
              <i class="bi bi-stars text-warning"></i> Trợ Lý Gemini AI
            </h5>
            <button @click="showAiTest = false" type="button" class="btn-close btn-close-white"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fs-7 fw-bold mb-1">Nhập từ hoặc câu cần giải thích / kiểm tra lỗi:</label>
              <textarea v-model="aiQueryText" class="form-control rounded-3 fs-7" rows="3" placeholder="Nhập câu tiếng Anh..."></textarea>
            </div>
            <button @click="askAiAssistant" class="btn btn-teal w-100 rounded-pill py-2 fw-bold shadow-sm mb-3" :disabled="aiQueryLoading || !aiQueryText">
              <span v-if="aiQueryLoading" class="spinner-border spinner-border-sm me-1"></span>
              <span>Gửi câu hỏi cho Gemini AI →</span>
            </button>
            <div v-if="aiResultText" class="alert alert-light border rounded-3 p-3 fs-7 mb-0">
              <strong class="text-teal">Phản hồi từ AI:</strong>
              <p class="mt-2 mb-0 leading-relaxed">{{ aiResultText }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const stats = ref({})
const users = ref([])
const vocabList = ref([])
const adminTab = ref('users')
const vocabSearch = ref('')

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

// AI Test Assistant
const showAiTest = ref(false)
const aiQueryText = ref('')
const aiQueryLoading = ref(false)
const aiResultText = ref('')

const fetchStats = async () => {
  try {
    const res = await axios.get('/api/admin/stats')
    stats.value = res.data.data
  } catch (err) {}
}

const fetchUsers = async () => {
  try {
    const res = await axios.get('/api/admin/users')
    users.value = res.data.data
  } catch (err) {}
}

const fetchVocabs = async () => {
  try {
    const res = await axios.get('/api/dictionary/search', { params: { q: vocabSearch.value } })
    vocabList.value = res.data.data.items || []
  } catch (err) {}
}

const searchVocab = () => {
  fetchVocabs()
}

const resetUserProgress = async (user) => {
  if (confirm(`Bạn có chắc chắn muốn RESET toàn bộ quá trình học tập của học viên "${user.name}" không?`)) {
    try {
      const res = await axios.post(`/api/admin/users/${user.id}/reset-progress`)
      alert(res.data.message || 'Đã reset tiến độ thành công!')
      fetchUsers()
      fetchStats()
    } catch (err) {
      alert('Lỗi: ' + (err.response?.data?.message || err.message))
    }
  }
}

const deleteUser = async (id) => {
  if (confirm('Bạn có chắc muốn xóa tài khoản này?')) {
    try {
      await axios.delete(`/api/admin/users/${id}`)
      fetchUsers()
      fetchStats()
    } catch (e) {
      alert('Không thể xóa tài khoản này')
    }
  }
}

const openCreateUserModal = () => {
  const name = prompt('Nhập họ tên học viên:')
  const email = prompt('Nhập email học viên:')
  if (name && email) {
    axios.post('/api/admin/users', {
      name,
      email,
      password: 'password123',
      role: 'user',
      words_per_day: 15,
    }).then(() => {
      alert('Tạo tài khoản thành công! Mật khẩu mặc định: 123456')
      fetchUsers()
      fetchStats()
    }).catch(err => alert('Lỗi: ' + (err.response?.data?.message || 'Không tạo được')))
  }
}

const openEditUserModal = (user) => {
  const newName = prompt('Sửa họ tên học viên:', user.name)
  if (newName) {
    axios.put(`/api/admin/users/${user.id}`, { name: newName }).then(() => {
      fetchUsers()
    })
  }
}

const openVocabModal = (w) => {
  editingVocab.value = w
  if (w) {
    vocabForm.value = { ...w }
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
  aiGenerating.value = true
  try {
    const res = await axios.post('/api/ai/generate-examples', {
      word: vocabForm.value.word,
      part_of_speech: vocabForm.value.part_of_speech,
      meaning_vi: vocabForm.value.meaning_vi,
    })
    if (res.data.data.example_en) {
      vocabForm.value.example_en = res.data.data.example_en
      vocabForm.value.example_vi = res.data.data.example_vi
    }
  } catch (err) {
    alert('Không thể gọi Gemini AI lúc này. Hãy kiểm tra kết nối mạng.')
  } finally {
    aiGenerating.value = false
  }
}

const saveVocab = async () => {
  try {
    if (editingVocab.value?.id) {
      await axios.put(`/api/admin/vocabularies/${editingVocab.value.id}`, vocabForm.value)
    } else {
      await axios.post('/api/admin/vocabularies', vocabForm.value)
    }
    showVocabModal.value = false
    fetchVocabs()
    fetchStats()
    alert('Đã lưu từ vựng thành công!')
  } catch (err) {
    alert('Lỗi lưu từ vựng: ' + (err.response?.data?.message || err.message))
  }
}

const deleteVocab = (id) => {
  if (confirm('Bạn có chắc muốn xóa từ vựng này?')) {
    axios.delete(`/api/admin/vocabularies/${id}`).then(() => {
      fetchVocabs()
      fetchStats()
    })
  }
}

const openAiTestModal = () => {
  showAiTest.value = true
  aiQueryText.value = 'She have two dogs and cat.'
  aiResultText.value = ''
}

const askAiAssistant = async () => {
  aiQueryLoading.value = true
  try {
    const res = await axios.post('/api/ai/explain', { text: aiQueryText.value })
    aiResultText.value = res.data.data.explanation || 'Phân tích thành công.'
  } catch (e) {
    aiResultText.value = 'Không thể gọi AI lúc này.'
  } finally {
    aiQueryLoading.value = false
  }
}

onMounted(() => {
  fetchStats()
  fetchUsers()
  fetchVocabs()
})
</script>
