<template>
  <div class="container py-4 max-w-6xl">
    <!-- Header -->
    <div class="text-center mb-4">
      <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">KHO TỪ ĐIỂN OXFORD 3000™</span>
      <h2 class="fw-black text-teal mt-2 mb-2">Tra Cứu & Học Toàn Bộ 3.351 Từ Vựng</h2>
      <p class="text-muted fs-7 max-w-xl mx-auto mb-0">
        Tìm kiếm thông minh, lọc linh hoạt theo chủ đề, bảng chữ cái A-Z, số thứ tự, độ phổ biến CEFR, cùng chế độ Vừa xem vừa học trực tiếp.
      </p>
    </div>

    <!-- Search Input & Quick Controls -->
    <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
      <div class="row g-2 align-items-center mb-3">
        <div class="col-12 col-md-8">
          <div class="input-group">
            <span class="input-group-text bg-body-tertiary border-end-0">
              <i class="bi bi-search text-teal"></i>
            </span>
            <input
              v-model="searchQuery"
              @input="handleSearchInput"
              type="text"
              class="form-control border-start-0 py-2.5 fs-7"
              placeholder="Nhập từ tiếng Anh hoặc nghĩa tiếng Việt (VD: abandon, ability, du lịch, công việc...)..."
            />
            <button v-if="searchQuery" @click="clearSearch" class="btn btn-outline-secondary border-start-0" type="button">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
        </div>

        <div class="col-6 col-md-2">
          <select v-model="filterStatus" @change="applyFilters" class="form-select py-2.5 fs-7">
            <option value="all">Tất cả trạng thái</option>
            <option value="mastered">Đã nhớ (Mastered)</option>
            <option value="learning">Cần ôn (Learning)</option>
            <option value="unlearned">Chưa học</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <button
            @click="isStudyMode = !isStudyMode"
            class="btn w-100 py-2.5 fs-7 d-flex align-items-center justify-content-center gap-1"
            :class="isStudyMode ? 'btn-success' : 'btn-outline-teal'"
          >
            <i class="bi" :class="isStudyMode ? 'bi-check-circle-fill' : 'bi-mortarboard'"></i>
            <span>{{ isStudyMode ? 'Chế độ Học: BẬT' : 'Vừa xem vừa học' }}</span>
          </button>
        </div>
      </div>

      <!-- Mode Tabs (Theo Chủ đề, Bảng chữ cái, Số thứ tự, CEFR, Mới thêm) - Kéo chuột trái lướt ngang -->
      <div v-drag-scroll class="d-flex align-items-center gap-2 overflow-x-auto pb-2 border-bottom mb-3 flex-nowrap fs-7 mobile-scroll-x no-scrollbar drag-scroll-x">
        <button
          @click="setTab('all')"
          class="btn btn-sm text-nowrap rounded-pill px-3 py-1"
          :class="activeTab === 'all' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-grid-fill me-1"></i> Tất cả từ vựng
        </button>

        <button
          @click="setTab('alphabet')"
          class="btn btn-sm text-nowrap rounded-pill px-3 py-1"
          :class="activeTab === 'alphabet' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-sort-alpha-down me-1"></i> Chữ cái (A - Z)
        </button>

        <button
          @click="setTab('topic')"
          class="btn btn-sm text-nowrap rounded-pill px-3 py-1"
          :class="activeTab === 'topic' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-diagram-3 me-1"></i> Theo Chủ đề
        </button>

        <button
          @click="setTab('index')"
          class="btn btn-sm text-nowrap rounded-pill px-3 py-1"
          :class="activeTab === 'index' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-123 me-1"></i> Theo Thứ tự (1..3000)
        </button>

        <button
          @click="setTab('cefr')"
          class="btn btn-sm text-nowrap rounded-pill px-3 py-1"
          :class="activeTab === 'cefr' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-award me-1"></i> Độ phổ biến (CEFR)
        </button>

        <button
          @click="setTab('newest')"
          class="btn btn-sm text-nowrap rounded-pill px-3 py-1"
          :class="activeTab === 'newest' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-clock-history me-1"></i> Vừa mới thêm
        </button>
      </div>

      <!-- Sub-filters based on activeTab -->
      <!-- Sub: Alphabet A-Z bar -->
      <div v-if="activeTab === 'alphabet'" v-drag-scroll class="d-flex align-items-center gap-1 overflow-x-auto pb-1 flex-nowrap no-scrollbar drag-scroll-x">
        <button
          @click="selectLetter('')"
          class="btn btn-sm px-2 py-0.5 rounded-2 fs-8"
          :class="selectedLetter === '' ? 'btn-primary' : 'btn-outline-secondary'"
        >
          Tất cả
        </button>
        <button
          v-for="char in alphabetList"
          :key="char"
          @click="selectLetter(char)"
          class="btn btn-sm px-2 py-0.5 rounded-2 fs-8"
          :class="selectedLetter === char ? 'btn-teal' : 'btn-outline-secondary'"
        >
          {{ char }}
        </button>
      </div>

      <!-- Sub: Topic filter -->
      <div v-if="activeTab === 'topic'" class="row g-2">
        <div class="col-12 col-md-6">
          <select v-model="selectedTopicId" @change="applyFilters" class="form-select form-select-sm fs-7">
            <option value="">-- Chọn một trong 25 Chủ đề --</option>
            <option v-for="t in topicsList" :key="t.id" :value="t.id">
              {{ t.icon }} {{ t.name }}
            </option>
          </select>
        </div>
      </div>

      <!-- Sub: Index Range filter -->
      <div v-if="activeTab === 'index'" v-drag-scroll class="d-flex gap-1 overflow-x-auto pb-1 flex-nowrap no-scrollbar drag-scroll-x">
        <button
          v-for="range in indexRanges"
          :key="range.value"
          @click="selectIdRange(range.value)"
          class="btn btn-sm px-2.5 py-1 rounded-pill fs-8 text-nowrap"
          :class="selectedIdRange === range.value ? 'btn-teal' : 'btn-outline-secondary'"
        >
          {{ range.label }}
        </button>
      </div>

      <!-- Sub: CEFR Level filter -->
      <div v-if="activeTab === 'cefr'" class="d-flex gap-2">
        <button
          v-for="lvl in ['A1', 'A2', 'B1', 'B2']"
          :key="lvl"
          @click="selectCefr(lvl)"
          class="btn btn-sm px-3 py-1 rounded-pill fs-8"
          :class="selectedCefr === lvl ? 'btn-success' : 'btn-outline-secondary'"
        >
          Cấp độ {{ lvl }}
        </button>
        <button v-if="selectedCefr" @click="selectCefr('')" class="btn btn-sm btn-outline-danger px-2 py-1 rounded-pill fs-8">
          Bỏ lọc
        </button>
      </div>
    </div>

    <!-- Results Overview -->
    <div class="d-flex justify-content-between align-items-center mb-3 text-muted fs-8">
      <div>
        Tìm thấy <strong>{{ totalWords }}</strong> từ vựng phù hợp
        <span v-if="totalPages > 1">(Trang {{ currentPage }} / {{ totalPages }})</span>
      </div>
      <div v-if="isLoading" class="spinner-border spinner-border-sm text-teal" role="status"></div>
    </div>

    <!-- Word List Grid -->
    <div v-if="isLoading && words.length === 0" class="text-center py-5">
      <div class="spinner-border text-teal mb-3" style="width: 3rem; height: 3rem;"></div>
      <p class="text-muted fs-7">Đang nạp từ điển Oxford 3000...</p>
    </div>

    <div v-else-if="words.length === 0" class="card p-5 text-center rounded-4 border bg-card-custom">
      <i class="bi bi-search fs-1 text-muted mb-2"></i>
      <h5 class="fw-bold text-teal">Không tìm thấy từ vựng phù hợp</h5>
      <p class="text-muted fs-7 mb-3">Vui lòng thử tìm với từ khóa khác hoặc xóa bớt bộ lọc.</p>
      <div>
        <button @click="resetAllFilters" class="btn btn-teal btn-sm px-4 rounded-pill">
          Đặt lại bộ lọc
        </button>
      </div>
    </div>

    <!-- Words Display -->
    <div v-else class="row g-3 mb-4">
      <div v-for="word in words" :key="word.id" class="col-12 col-md-6 col-lg-4">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between hover-card">
          <div>
            <!-- Word Header -->
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <div class="d-flex align-items-center gap-2">
                  <h4 class="fw-black text-teal mb-0 tracking-tight">{{ word.word }}</h4>
                  <span v-if="word.cefr_level" class="badge bg-success-subtle text-success border border-success-subtle fs-8">
                    {{ word.cefr_level }}
                  </span>
                </div>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <span class="font-monospace text-muted fs-7">{{ word.phonetic_us }}</span>
                  <span class="badge bg-secondary-subtle text-secondary-emphasis fs-8">{{ word.part_of_speech }}</span>
                </div>
              </div>

              <!-- Quick Audio & Status Pill -->
              <div class="d-flex align-items-center gap-1">
                <button
                  type="button"
                  @click="speech.speak(word.word)"
                  class="btn btn-sm btn-outline-teal rounded-circle p-1.5 shadow-sm"
                  title="Nghe phát âm chuẩn US"
                >
                  <i class="bi bi-volume-up-fill"></i>
                </button>
                <button
                  type="button"
                  @click.stop="startListeningWord(word)"
                  class="btn btn-sm rounded-circle p-1.5 shadow-sm transition-all"
                  :class="activeListeningWordId === word.id && speech.isListening.value ? 'btn-danger animate-mic-pulse text-white' : 'btn-outline-info'"
                  :title="activeListeningWordId === word.id && speech.isListening.value ? 'Đang lắng nghe... bấm để dừng' : 'Luyện nói từ này'"
                >
                  <i class="bi" :class="activeListeningWordId === word.id && speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
                </button>
              </div>
            </div>

            <!-- Listening indicator box -->
            <div 
              v-if="activeListeningWordId === word.id && speech.isListening.value" 
              class="alert alert-warning py-1.5 px-2.5 rounded-3 mb-2 fs-8 d-flex align-items-center gap-2 border-warning shadow-xs"
            >
              <span class="spinner-grow spinner-grow-sm text-danger flex-shrink-0" role="status"></span>
              <span class="fw-semibold text-dark">
                Đang lắng nghe... Hãy đọc to: <strong class="text-danger">"{{ word.word }}"</strong>
              </span>
            </div>

            <!-- Pronunciation result box -->
            <div 
              v-else-if="pronunciationResults[word.id]" 
              class="alert py-1.5 px-2.5 rounded-3 mb-2 fs-8 border transition-all shadow-xs"
              :class="pronunciationResults[word.id].score >= 80 ? 'alert-success border-success' : pronunciationResults[word.id].score >= 50 ? 'alert-warning border-warning' : 'alert-danger border-danger'"
            >
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-1.5 text-truncate me-2">
                  <i class="bi" :class="pronunciationResults[word.id].score >= 80 ? 'bi-check-circle-fill text-success' : 'bi-exclamation-circle-fill text-warning'"></i>
                  <span class="text-truncate">
                    Bạn đọc: <strong>"{{ pronunciationResults[word.id].transcript || '...' }}"</strong>
                  </span>
                </div>
                <span 
                  class="badge rounded-pill fw-bold fs-9 flex-shrink-0"
                  :class="pronunciationResults[word.id].score >= 80 ? 'bg-success text-white' : pronunciationResults[word.id].score >= 50 ? 'bg-warning text-dark' : 'bg-danger text-white'"
                >
                  {{ pronunciationResults[word.id].score }}% • {{ pronunciationResults[word.id].score >= 80 ? 'Chuẩn 🎉' : pronunciationResults[word.id].score >= 50 ? 'Tương đối 👍' : 'Thử lại ❌' }}
                </span>
              </div>
            </div>

            <!-- Meaning -->
            <div class="fw-bold text-success fs-6 mb-2">
              {{ word.meaning_vi }}
            </div>

            <!-- Example sentence -->
            <div v-if="word.example_en" class="bg-body-tertiary p-2.5 rounded-3 border fs-8 mb-2">
              <div class="text-teal fw-semibold mb-1">
                <i class="bi bi-chat-quote me-1"></i>{{ word.example_en }}
              </div>
              <div class="text-muted fst-italic">
                {{ word.example_vi }}
              </div>
            </div>
          </div>

          <!-- Card Footer Controls -->
          <div class="pt-2 border-top d-flex align-items-center justify-content-between gap-2 mt-2">
            <span class="badge" :class="word.status === 'mastered' ? 'bg-success text-white' : word.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary-subtle text-secondary'">
              {{ word.status === 'mastered' ? 'Đã nhớ' : word.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
            </span>

            <div class="d-flex gap-1">
              <button
                @click="toggleStatus(word, 'mastered')"
                class="btn btn-sm px-2 py-0.5 rounded-2 fs-8"
                :class="word.status === 'mastered' ? 'btn-success' : 'btn-outline-success'"
                title="Đánh dấu đã nhớ"
              >
                <i class="bi bi-check-lg"></i>
              </button>
              <button
                @click="toggleStatus(word, 'learning')"
                class="btn btn-sm px-2 py-0.5 rounded-2 fs-8"
                :class="word.status === 'learning' ? 'btn-warning text-white' : 'btn-outline-warning'"
                title="Đánh dấu cần ôn"
              >
                <i class="bi bi-arrow-clockwise"></i>
              </button>
              <button
                @click="openAiExplainModal(word)"
                class="btn btn-sm btn-outline-teal px-2 py-0.5 rounded-2 fs-8"
                title="Hỏi Gemini AI giải thích từ vựng"
              >
                <i class="bi bi-stars"></i> AI
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="d-flex justify-content-center align-items-center gap-2 mb-5">
      <button
        @click="goToPage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="btn btn-sm btn-outline-teal rounded-pill px-3"
      >
        <i class="bi bi-chevron-left me-1"></i> Trang trước
      </button>

      <span class="fs-7 text-muted px-2">
        Trang <strong>{{ currentPage }}</strong> / <strong>{{ totalPages }}</strong>
      </span>

      <button
        @click="goToPage(currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="btn btn-sm btn-outline-teal rounded-pill px-3"
      >
        Trang sau <i class="bi bi-chevron-right ms-1"></i>
      </button>
    </div>

    <!-- AI Explain Centered Modal -->
    <div
      v-if="aiModalWord"
      class="modal fade show d-block"
      style="background: rgba(0, 0, 0, 0.6);"
      tabindex="-1"
      @click.self="aiModalWord = null"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-card-custom">
          <div class="modal-header border-bottom py-3">
            <h5 class="modal-title fw-bold text-teal d-flex align-items-center gap-2">
              <i class="bi bi-stars text-warning"></i> Trợ Lý Gemini AI Giải Thích
            </h5>
            <button @click="aiModalWord = null" type="button" class="btn-close" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <span class="fs-4 fw-black text-teal">{{ aiModalWord.word }}</span>
              <span class="font-monospace text-muted ms-2 fs-7">{{ aiModalWord.phonetic_us }}</span>
              <div class="text-success fw-bold fs-6 mt-1">{{ aiModalWord.meaning_vi }}</div>
            </div>

            <div v-if="aiLoading" class="text-center py-4">
              <div class="spinner-border text-teal mb-2" role="status"></div>
              <p class="text-muted fs-8 mb-0">Gemini AI đang phân tích và chuẩn bị lời giải thích...</p>
            </div>

            <div v-else class="alert alert-light border rounded-3 p-3 fs-7 mb-0">
              <strong class="text-teal"><i class="bi bi-lightbulb me-1"></i> Nhận xét & Ngữ cảnh sử dụng:</strong>
              <p class="mt-2 mb-0 leading-relaxed">{{ aiResponseText }}</p>
            </div>
          </div>
          <div class="modal-footer border-top py-2">
            <button @click="aiModalWord = null" class="btn btn-teal btn-sm px-4 rounded-pill">
              Đã hiểu
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useSpeech } from '@/composables/useSpeech'
import { useToast } from '@/composables/useToast'
import { useReviewStore } from '@/stores/reviewStore'

const authStore = useAuthStore()
const speech = useSpeech()
const toast = useToast()
const reviewStore = useReviewStore()

const words = ref([])
const totalWords = ref(0)
const currentPage = ref(1)
const totalPages = ref(1)
const isLoading = ref(false)

const activeListeningWordId = ref(null)
const pronunciationResults = ref({})

const startListeningWord = async (word) => {
  if (activeListeningWordId.value === word.id && speech.isListening.value) {
    speech.stopSpeech()
    speech.isListening.value = false
    activeListeningWordId.value = null
    return
  }

  activeListeningWordId.value = word.id
  await speech.startListening(word.word)
}

watch(() => speech.isListening.value, (isListening) => {
  if (!isListening && activeListeningWordId.value !== null) {
    const currentWordId = activeListeningWordId.value
    if (speech.transcript.value || speech.pronunciationScore.value !== null) {
      const score = speech.pronunciationScore.value ?? 0
      pronunciationResults.value[currentWordId] = {
        score,
        transcript: speech.transcript.value,
        feedback: score >= 80 ? 'excellent' : score >= 50 ? 'good' : 'poor'
      }

      // If pronounced correctly (>= 80%), automatically upgrade status to mastered
      const targetWord = words.value.find(w => w.id === currentWordId)
      if (score >= 80 && targetWord && targetWord.status !== 'mastered') {
        toggleStatus(targetWord, 'mastered')
      }
    }
  }
})

const searchQuery = ref('')
const filterStatus = ref('all')
const isStudyMode = ref(false)
const activeTab = ref('all') // 'all', 'alphabet', 'topic', 'index', 'cefr', 'newest'

const alphabetList = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')
const selectedLetter = ref('')
const selectedTopicId = ref('')
const topicsList = ref([])

const selectedIdRange = ref('')
const indexRanges = [
  { label: 'Từ 1 - 100', value: '1-100' },
  { label: 'Từ 101 - 250', value: '101-250' },
  { label: 'Từ 251 - 500', value: '251-500' },
  { label: 'Từ 501 - 1000', value: '501-1000' },
  { label: 'Từ 1001 - 2000', value: '1001-2000' },
  { label: 'Từ 2001 - 3351', value: '2001-3351' },
]

const selectedCefr = ref('')

// AI Modal state
const aiModalWord = ref(null)
const aiLoading = ref(false)
const aiResponseText = ref('')

let searchTimeout = null

const fetchWords = async (page = 1) => {
  isLoading.value = true
  try {
    const params = {
      page,
      q: searchQuery.value,
      status: filterStatus.value,
      topic_id: selectedTopicId.value || undefined,
      letter: selectedLetter.value || undefined,
      id_range: selectedIdRange.value || undefined,
      cefr_level: selectedCefr.value || undefined,
      sort: activeTab.value === 'newest' ? 'newest' : (activeTab.value === 'cefr' ? 'frequency' : 'default'),
    }

    const res = await axios.get('/api/dictionary/search', { params })
    words.value = res.data.data.items || []
    currentPage.value = res.data.data.current_page || 1
    totalPages.value = res.data.data.last_page || 1
    totalWords.value = res.data.data.total || 0
    if (res.data.data.topics && topicsList.value.length === 0) {
      topicsList.value = res.data.data.topics
    }
  } catch (err) {
    console.error('Failed to load dictionary:', err)
  } finally {
    isLoading.value = false
  }
}

const handleSearchInput = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    currentPage.value = 1
    fetchWords(1)
  }, 350)
}

const clearSearch = () => {
  searchQuery.value = ''
  currentPage.value = 1
  fetchWords(1)
}

const setTab = (tab) => {
  activeTab.value = tab
  if (tab === 'all') {
    selectedLetter.value = ''
    selectedTopicId.value = ''
    selectedIdRange.value = ''
    selectedCefr.value = ''
  }
  currentPage.value = 1
  fetchWords(1)
}

const selectLetter = (char) => {
  selectedLetter.value = char
  currentPage.value = 1
  fetchWords(1)
}

const selectIdRange = (val) => {
  selectedIdRange.value = selectedIdRange.value === val ? '' : val
  currentPage.value = 1
  fetchWords(1)
}

const selectCefr = (lvl) => {
  selectedCefr.value = selectedCefr.value === lvl ? '' : lvl
  currentPage.value = 1
  fetchWords(1)
}

const applyFilters = () => {
  currentPage.value = 1
  fetchWords(1)
}

const resetAllFilters = () => {
  searchQuery.value = ''
  filterStatus.value = 'all'
  selectedLetter.value = ''
  selectedTopicId.value = ''
  selectedIdRange.value = ''
  selectedCefr.value = ''
  activeTab.value = 'all'
  currentPage.value = 1
  fetchWords(1)
}

const goToPage = (p) => {
  if (p >= 1 && p <= totalPages.value) {
    currentPage.value = p
    fetchWords(p)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const toggleStatus = async (word, targetStatus) => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để lưu tiến độ từ vựng vào tài khoản của bạn.')
    return
  }

  const newStatus = word.status === targetStatus ? 'unlearned' : targetStatus
  word.status = newStatus
  try {
    await axios.post('/api/progress/word-status', {
      vocabulary_id: word.id,
      status: newStatus,
    })

    if (newStatus === 'learning') {
      reviewStore.addWordToReview(word, 'Từ điển Oxford')
      toast.review(`Đã đưa "${word.word}" vào danh sách cần ôn tập.`, 'Cần ôn tập')
    } else if (newStatus === 'mastered') {
      toast.remembered(`Đã đánh dấu "${word.word}" là đã nhớ.`, 'Đã nhớ từ vựng')
    } else {
      toast.info(`Đã chuyển trạng thái "${word.word}" về chưa học.`, 'Cập nhật')
    }
  } catch (err) {
    console.error('Failed to update status:', err)
  }
}

const openAiExplainModal = async (word) => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để sử dụng tính năng Trợ lý Gemini AI!')
    return
  }

  aiModalWord.value = word
  aiLoading.value = true
  aiResponseText.value = ''
  try {
    const res = await axios.post('/api/ai/explain', {
      text: `Từ: ${word.word} (${word.part_of_speech}), nghĩa: ${word.meaning_vi}. Ví dụ: ${word.example_en}`,
    })
    aiResponseText.value = res.data.data.explanation || 'Phân tích thành công.'
  } catch (err) {
    aiResponseText.value = 'Từ vựng thông dụng trong bộ từ điển Oxford 3000 chuẩn Mỹ. Hãy thực hành đặt câu thường xuyên để ghi nhớ lâu hơn!'
  } finally {
    aiLoading.value = false
  }
}

onMounted(() => {
  fetchWords()
})
</script>
