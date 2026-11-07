<template>
  <div class="container py-3 py-md-4 max-w-6xl vocabulary-notebook-page">
    <!-- Header Banner -->
    <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-3 mb-md-4" style="border-left: 6px solid #f59e0b;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-8 fw-bold">
              <i class="bi bi-bookmark-star-fill me-1"></i> SỔ TAY CÁ NHÂN
            </span>
            <span class="badge bg-teal text-white rounded-pill px-2.5 py-1 fs-8 fw-bold">
              {{ words.length }} từ vựng đã lưu
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-8">
              {{ masteredCount }} đã thuộc ({{ percentMastered }}%)
            </span>
          </div>
          <h2 class="fs-4 fs-md-3 fw-black text-teal mb-1">
            Sổ Tay Từ Vựng & Kho Flashcard Riêng
          </h2>
          <p class="text-muted fs-7 mb-0">
            Lưu trữ từ mới khi lướt web (bôi đen / click đúp từ), tự thêm từ mới và ôn tập với Flashcard 3D phản xạ chủ động.
          </p>
        </div>

        <div class="d-flex align-items-center gap-2 w-100 w-md-auto">
          <button
            @click="openAddModal"
            class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-7 shadow-sm d-flex align-items-center gap-1.5 flex-fill flex-md-grow-0 justify-content-center"
          >
            <i class="bi bi-plus-circle-fill"></i>
            <span>Thêm từ mới</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Navigation Modes: Danh sách | Thẻ 3D Ôn tập | Trắc nghiệm phản xạ -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div class="btn-group p-1 rounded-pill bg-body-tertiary border shadow-2xs">
        <button
          @click="activeMode = 'list'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 transition-all"
          :class="activeMode === 'list' ? 'btn-teal text-white shadow-xs' : 'text-muted'"
        >
          <i class="bi bi-list-ul me-1"></i> Danh sách từ ({{ filteredWords.length }})
        </button>
        <button
          @click="activeMode = 'flashcard'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 transition-all"
          :class="activeMode === 'flashcard' ? 'btn-teal text-white shadow-xs' : 'text-muted'"
          :disabled="words.length === 0"
        >
          <i class="bi bi-card-heading me-1"></i> Lật thẻ 3D ({{ words.length }})
        </button>
        <button
          @click="activeMode = 'quiz'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 transition-all"
          :class="activeMode === 'quiz' ? 'btn-teal text-white shadow-xs' : 'text-muted'"
          :disabled="words.length < 3"
        >
          <i class="bi bi-check2-circle me-1"></i> Trắc nghiệm phản xạ
        </button>
      </div>

      <!-- Search & Filters -->
      <div v-if="activeMode === 'list'" class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0" style="max-width: 320px;">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted">
            <i class="bi bi-search"></i>
          </span>
          <input
            v-model="searchQuery"
            type="text"
            class="form-control border-start-0 rounded-end-pill fs-8"
            placeholder="Tìm từ, nghĩa, phiên âm..."
          />
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="words.length === 0" class="card p-5 rounded-4 text-center border-dashed bg-card-custom my-4">
      <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex p-3 mx-auto mb-3" style="width: 64px; height: 64px; align-items: center; justify-content: center;">
        <i class="bi bi-journal-plus fs-2"></i>
      </div>
      <h5 class="fw-bold mb-1">Sổ tay của bạn đang trống!</h5>
      <p class="text-muted fs-7 max-w-md mx-auto mb-3">
        Hãy bôi đen bất kỳ từ tiếng Anh nào trên web để tra cứu và bấm <strong>[+ Lưu vào Sổ tay]</strong>, hoặc bấm nút thêm từ bên dưới.
      </p>
      <div>
        <button @click="openAddModal" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7 shadow-xs">
          <i class="bi bi-plus-lg me-1"></i> Thêm từ đầu tiên ngay
        </button>
      </div>
    </div>

    <!-- MODE 1: LIST VIEW -->
    <div v-else-if="activeMode === 'list'" class="row g-2.5">
      <div v-for="(item, idx) in filteredWords" :key="item.id || idx" class="col-12 col-md-6 col-xl-4">
        <div
          class="card p-3 rounded-3 border bg-card-custom h-100 shadow-2xs transition-all position-relative"
          :class="item.mastered ? 'border-success-subtle bg-success-subtle/10' : ''"
        >
          <div class="d-flex justify-content-between align-items-start gap-2 mb-1.5">
            <div>
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="fw-black fs-5 text-teal">{{ item.word }}</span>
                <span v-if="item.pos" class="badge bg-secondary-subtle text-secondary-emphasis fs-9 rounded-pill text-uppercase">
                  {{ item.pos }}
                </span>
                <span v-if="item.mastered" class="badge bg-success text-white fs-9 rounded-pill">
                  ✓ Đã thuộc
                </span>
              </div>
              <div class="text-muted fs-8 font-monospace mt-0.5" v-if="item.phonetic">
                {{ item.phonetic }}
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center gap-1">
              <button
                @click="speak(item.word)"
                class="btn btn-sm btn-outline-teal rounded-circle p-1 d-flex align-items-center justify-content-center"
                style="width: 28px; height: 28px;"
                title="Nghe phát âm US"
              >
                <i class="bi bi-volume-up-fill fs-8"></i>
              </button>
              <button
                @click="toggleMastered(item.id)"
                class="btn btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center"
                :class="item.mastered ? 'btn-success text-white' : 'btn-outline-secondary'"
                style="width: 28px; height: 28px;"
                :title="item.mastered ? 'Đánh dấu cần ôn' : 'Đánh dấu đã nhớ'"
              >
                <i class="bi bi-check-lg fs-8"></i>
              </button>
              <button
                @click="removeWord(item.id)"
                class="btn btn-sm btn-outline-danger rounded-circle p-1 d-flex align-items-center justify-content-center"
                style="width: 28px; height: 28px;"
                title="Xóa khỏi sổ tay"
              >
                <i class="bi bi-trash3 fs-8"></i>
              </button>
            </div>
          </div>

          <div class="fw-semibold text-dark-emphasis fs-7 mb-1.5">
            {{ item.meaning }}
          </div>

          <div v-if="item.example" class="p-2 rounded-2 bg-body-tertiary border border-light-subtle fs-8 text-secondary mt-auto">
            <div class="fst-italic">"{{ item.example }}"</div>
            <div v-if="item.example_vi" class="text-muted fs-9 mt-0.5">→ {{ item.example_vi }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODE 2: FLASHCARD 3D WITH ACTIVE RECALL SPELLING -->
    <div v-else-if="activeMode === 'flashcard'" class="d-flex flex-column align-items-center py-2">
      <div class="w-100 max-w-lg">
        <!-- Progress Bar & Counter -->
        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
          <span class="fs-8 fw-bold text-muted">Thẻ {{ currentCardIdx + 1 }} / {{ words.length }}</span>
          <span class="badge bg-teal text-white rounded-pill fs-8">Sổ tay cá nhân</span>
        </div>
        <div class="progress mb-3" style="height: 6px;">
          <div
            class="progress-bar bg-teal"
            :style="{ width: ((currentCardIdx + 1) / words.length * 100) + '%' }"
          ></div>
        </div>

        <!-- 3D Card Box -->
        <div
          class="notebook-flashcard-box cursor-pointer"
          @click="isFlipped = !isFlipped"
          :class="{ 'is-flipped': isFlipped }"
        >
          <div class="notebook-flashcard-inner">
            <!-- Front Face: English -->
            <div class="notebook-face notebook-front p-4 rounded-4 shadow-sm border bg-white d-flex flex-column justify-content-between text-center">
              <div class="d-flex justify-content-between align-items-center w-100">
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 fs-9 text-uppercase">
                  {{ currentCard?.pos || 'VOCABULARY' }}
                </span>
                <button
                  @click.stop="speak(currentCard?.word)"
                  class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1 fs-8 fw-bold d-flex align-items-center gap-1 shadow-2xs"
                >
                  <i class="bi bi-volume-up-fill"></i> US
                </button>
              </div>

              <div class="my-auto py-4">
                <h2 class="display-5 fw-black text-teal mb-1">{{ currentCard?.word }}</h2>
                <div class="fs-6 font-monospace text-muted" v-if="currentCard?.phonetic">
                  {{ currentCard?.phonetic }}
                </div>
              </div>

              <div class="text-muted fs-8">
                <i class="bi bi-arrow-repeat me-1"></i> Bấm để lật thẻ xem nghĩa & gõ nhớ từ
              </div>
            </div>

            <!-- Back Face: Vietnamese Meaning + Active Recall Spelling input -->
            <div class="notebook-face notebook-back p-4 rounded-4 shadow-sm border bg-dark text-white d-flex flex-column justify-content-between" @click.stop>
              <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-9 fw-bold">
                    ĐÁP ÁN & NGHĨA
                  </span>
                  <button
                    @click.stop="isFlipped = false"
                    class="btn btn-sm btn-outline-light rounded-pill px-2.5 py-0.5 fs-8"
                  >
                    <i class="bi bi-arrow-return-left me-1"></i> Mặt trước
                  </button>
                </div>
                <h4 class="fw-black text-warning mb-1">{{ currentCard?.meaning }}</h4>
                <div v-if="currentCard?.example" class="p-2 rounded-3 bg-secondary-subtle/20 border border-secondary fs-8 mt-2 text-light">
                  <div class="fst-italic text-info-emphasis">"{{ currentCard.example }}"</div>
                  <div v-if="currentCard.example_vi" class="text-light-emphasis fs-9 mt-0.5">→ {{ currentCard.example_vi }}</div>
                </div>
              </div>

              <!-- Active Recall: Nhập lại từ tiếng Anh -->
              <div class="my-3 p-3 rounded-3 border border-secondary" style="background: rgba(255, 255, 255, 0.05);">
                <div class="d-flex justify-content-between align-items-center mb-1.5">
                  <label class="fs-8 fw-bold text-light mb-0">✍️ Gõ lại từ tiếng Anh để nhớ sâu:</label>
                  <button
                    @click="revealCardSpelling"
                    type="button"
                    class="btn btn-xs rounded-pill px-2.5 py-1 fw-bold fs-9 shadow-xs"
                    style="border: 1.5px solid #22c55e; color: #22c55e; background: rgba(34, 197, 94, 0.1);"
                  >
                    <i class="bi bi-eye-fill me-1"></i> Xem đáp án
                  </button>
                </div>
                <div class="input-group input-group-sm">
                  <input
                    v-model="cardSpellingInput"
                    @keyup.enter="checkCardSpelling"
                    type="text"
                    class="form-control rounded-start-pill fs-7 text-white font-monospace"
                    style="background: rgba(0, 0, 0, 0.4); border-color: #64748b;"
                    placeholder="Gõ từ tiếng Anh tại đây..."
                  />
                  <button
                    @click="checkCardSpelling"
                    class="btn btn-teal text-white rounded-end-pill px-3 fw-bold fs-8"
                    type="button"
                  >
                    Kiểm tra
                  </button>
                </div>
                <div v-if="cardSpellingFeedback" class="mt-1.5 fs-8 fw-bold" :class="cardSpellingFeedback.isCorrect ? 'text-success' : 'text-danger'">
                  <i :class="cardSpellingFeedback.isCorrect ? 'bi bi-check-circle-fill' : 'bi bi-exclamation-triangle-fill'" class="me-1"></i>
                  {{ cardSpellingFeedback.message }}
                </div>
              </div>

              <!-- Card Nav -->
              <div class="d-flex justify-content-between align-items-center gap-2">
                <button
                  @click="prevCard"
                  class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5 fs-8"
                  :disabled="currentCardIdx === 0"
                >
                  <i class="bi bi-chevron-left me-1"></i> Trước
                </button>
                <button
                  @click="toggleMastered(currentCard?.id)"
                  class="btn btn-sm rounded-pill px-3 py-1.5 fs-8 fw-bold"
                  :class="currentCard?.mastered ? 'btn-success text-white' : 'btn-outline-warning text-warning'"
                >
                  {{ currentCard?.mastered ? '✓ Đã nhớ' : 'Đánh dấu đã thuộc' }}
                </button>
                <button
                  @click="nextCard"
                  class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fs-8"
                  :disabled="currentCardIdx >= words.length - 1"
                >
                  Tiếp <i class="bi bi-chevron-right ms-1"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODE 3: QUICK QUIZ -->
    <div v-else-if="activeMode === 'quiz'" class="max-w-md mx-auto py-3">
      <div class="card p-4 rounded-4 shadow-sm border bg-card-custom">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge bg-teal text-white rounded-pill fs-8">Câu hỏi {{ quizIdx + 1 }} / {{ quizQuestions.length }}</span>
          <span class="fs-8 fw-bold text-muted">Điểm: {{ quizScore }} / {{ quizQuestions.length }}</span>
        </div>

        <div v-if="quizCurrentQuestion">
          <div class="text-center py-3">
            <span class="fs-8 text-muted text-uppercase fw-bold">Từ tiếng Anh</span>
            <h3 class="display-6 fw-black text-teal mb-1">{{ quizCurrentQuestion.word }}</h3>
            <div class="text-muted fs-7 font-monospace" v-if="quizCurrentQuestion.phonetic">
              {{ quizCurrentQuestion.phonetic }}
            </div>
          </div>

          <div class="d-flex flex-column gap-2 mt-3">
            <button
              v-for="(opt, optIdx) in quizCurrentQuestion.options"
              :key="optIdx"
              @click="submitQuizAnswer(opt)"
              :disabled="quizAnswerSubmitted"
              class="btn text-start p-3 rounded-3 border fw-semibold fs-7 transition-all"
              :class="getQuizOptionClass(opt)"
            >
              <div class="d-flex align-items-center justify-content-between">
                <span>{{ opt }}</span>
                <i v-if="quizAnswerSubmitted && opt === quizCurrentQuestion.meaning" class="bi bi-check-circle-fill text-success fs-6"></i>
              </div>
            </button>
          </div>

          <div v-if="quizAnswerSubmitted" class="mt-3 text-center">
            <button @click="nextQuizQuestion" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7 shadow-xs">
              {{ quizIdx < quizQuestions.length - 1 ? 'Câu tiếp theo' : 'Xem kết quả' }}
            </button>
          </div>
        </div>

        <div v-else class="text-center py-4">
          <div class="rounded-circle bg-success-subtle text-success d-inline-flex p-3 mx-auto mb-3" style="width: 60px; height: 60px; align-items: center; justify-content: center;">
            <i class="bi bi-trophy-fill fs-3"></i>
          </div>
          <h4 class="fw-bold text-teal mb-1">Hoàn thành bài ôn tập!</h4>
          <p class="text-muted fs-7">Bạn đạt <strong>{{ quizScore }} / {{ quizQuestions.length }}</strong> câu chính xác.</p>
          <button @click="initQuiz" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7 shadow-xs">
            Làm lại bài kiểm tra
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL: ADD CUSTOM WORD -->
    <div v-if="isAddModalOpen" class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.65); z-index: 1060;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-card-custom">
          <div class="modal-header border-bottom pb-2">
            <h5 class="modal-title fw-black text-teal fs-6">
              <i class="bi bi-plus-circle-fill me-1 text-teal"></i> Thêm từ vựng vào Sổ tay
            </h5>
            <button @click="isAddModalOpen = false" type="button" class="btn-close" aria-label="Close"></button>
          </div>
          <form @submit.prevent="saveNewWord">
            <div class="modal-body py-3">
              <div class="mb-2.5">
                <label class="form-label fs-8 fw-bold text-muted">Từ tiếng Anh (*)</label>
                <input v-model="newWordForm.word" required type="text" class="form-control rounded-3 fs-7" placeholder="Ví dụ: resilient" />
              </div>
              <div class="row g-2 mb-2.5">
                <div class="col-6">
                  <label class="form-label fs-8 fw-bold text-muted">Phiên âm IPA</label>
                  <input v-model="newWordForm.phonetic" type="text" class="form-control rounded-3 fs-7 font-monospace" placeholder="/rɪˈzɪliənt/" />
                </div>
                <div class="col-6">
                  <label class="form-label fs-8 fw-bold text-muted">Loại từ</label>
                  <select v-model="newWordForm.pos" class="form-select rounded-3 fs-7">
                    <option value="danh từ">Danh từ (n)</option>
                    <option value="động từ">Động từ (v)</option>
                    <option value="tính từ">Tính từ (adj)</option>
                    <option value="trạng từ">Trạng từ (adv)</option>
                    <option value="cụm từ">Cụm từ (phrase)</option>
                  </select>
                </div>
              </div>
              <div class="mb-2.5">
                <label class="form-label fs-8 fw-bold text-muted">Nghĩa tiếng Việt (*)</label>
                <input v-model="newWordForm.meaning" required type="text" class="form-control rounded-3 fs-7" placeholder="Kiên cường, có khả năng phục hồi nhanh" />
              </div>
              <div class="mb-2.5">
                <label class="form-label fs-8 fw-bold text-muted">Câu ví dụ tiếng Anh</label>
                <input v-model="newWordForm.example" type="text" class="form-control rounded-3 fs-7" placeholder="He showed resilient character under pressure." />
              </div>
              <div>
                <label class="form-label fs-8 fw-bold text-muted">Dịch nghĩa câu ví dụ</label>
                <input v-model="newWordForm.example_vi" type="text" class="form-control rounded-3 fs-7" placeholder="Anh ấy đã thể hiện bản lĩnh kiên cường dưới áp lực." />
              </div>
            </div>
            <div class="modal-footer border-top pt-2">
              <button @click="isAddModalOpen = false" type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Hủy</button>
              <button type="submit" class="btn btn-sm btn-teal text-white rounded-pill px-3.5 fw-bold shadow-xs">Lưu vào Sổ tay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const STORAGE_KEY = 'oxford_user_notebook'

const DEFAULT_SAMPLE_WORDS = [
  { id: 1, word: 'resilient', phonetic: '/rɪˈzɪliənt/', pos: 'tính từ', meaning: 'Kiên cường, có khả năng hồi phục nhanh sau khó khăn', example: 'She is a resilient woman who overcomes every obstacle.', example_vi: 'Cô ấy là một người phụ nữ kiên cường vượt qua mọi trở ngại.', mastered: true, created_at: Date.now() - 86400000 },
  { id: 2, word: 'meticulous', phonetic: '/məˈtɪkjələs/', pos: 'tính từ', meaning: 'Tỉ mỉ, cẩn trọng từng chi tiết nhỏ', example: 'His meticulous research helped solve the problem.', example_vi: 'Nghiên cứu tỉ mỉ của anh ấy đã giúp giải quyết vấn đề.', mastered: false, created_at: Date.now() - 43200000 },
  { id: 3, word: 'breakthrough', phonetic: '/ˈbreɪkθruː/', pos: 'danh từ', meaning: 'Bước đột phá, phát kiến mang tính cách mạng', example: 'Scientists made a major breakthrough in cancer treatment.', example_vi: 'Các nhà khoa học đã đạt bước đột phá lớn trong điều trị ung thư.', mastered: false, created_at: Date.now() },
  { id: 4, word: 'persuasive', phonetic: '/pərˈsweɪsɪv/', pos: 'tính từ', meaning: 'Có sức thuyết phục, lôi cuốn người nghe', example: 'She presented a persuasive argument in the debate.', example_vi: 'Cô ấy đã trình bày một lập luận thuyết phục trong buổi tranh luận.', mastered: true, created_at: Date.now() }
]

const words = ref([])
const activeMode = ref('list') // 'list' | 'flashcard' | 'quiz'
const searchQuery = ref('')

const isAddModalOpen = ref(false)
const newWordForm = ref({
  word: '',
  phonetic: '',
  pos: 'danh từ',
  meaning: '',
  example: '',
  example_vi: ''
})

// Flashcard 3D State
const currentCardIdx = ref(0)
const isFlipped = ref(false)
const cardSpellingInput = ref('')
const cardSpellingFeedback = ref(null)

// Quiz State
const quizIdx = ref(0)
const quizScore = ref(0)
const quizAnswerSubmitted = ref(false)
const quizSelectedOption = ref(null)
const quizQuestions = ref([])

const loadNotebook = () => {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    if (raw) {
      words.value = JSON.parse(raw)
    } else {
      words.value = [...DEFAULT_SAMPLE_WORDS]
      saveNotebook()
    }
  } catch (e) {
    words.value = [...DEFAULT_SAMPLE_WORDS]
  }
}

const saveNotebook = () => {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(words.value))
}

const masteredCount = computed(() => words.value.filter(w => w.mastered).length)
const percentMastered = computed(() => {
  if (words.value.length === 0) return 0
  return Math.round((masteredCount.value / words.value.length) * 100)
})

const filteredWords = computed(() => {
  if (!searchQuery.value.trim()) return words.value
  const q = searchQuery.value.toLowerCase().trim()
  return words.value.filter(w =>
    w.word.toLowerCase().includes(q) ||
    w.meaning.toLowerCase().includes(q) ||
    (w.phonetic && w.phonetic.toLowerCase().includes(q))
  )
})

const currentCard = computed(() => words.value[currentCardIdx.value] || null)

const nextCard = () => {
  if (currentCardIdx.value < words.value.length - 1) {
    currentCardIdx.value++
    isFlipped.value = false
    cardSpellingInput.value = ''
    cardSpellingFeedback.value = null
  }
}

const prevCard = () => {
  if (currentCardIdx.value > 0) {
    currentCardIdx.value--
    isFlipped.value = false
    cardSpellingInput.value = ''
    cardSpellingFeedback.value = null
  }
}

const revealCardSpelling = () => {
  if (currentCard.value) {
    cardSpellingInput.value = currentCard.value.word
    cardSpellingFeedback.value = {
      isCorrect: true,
      message: `Đáp án chính xác: ${currentCard.value.word}`
    }
  }
}

const checkCardSpelling = () => {
  if (!currentCard.value || !cardSpellingInput.value.trim()) return
  const cleanInput = cardSpellingInput.value.trim().toLowerCase()
  const cleanTarget = currentCard.value.word.trim().toLowerCase()
  if (cleanInput === cleanTarget) {
    cardSpellingFeedback.value = {
      isCorrect: true,
      message: '🎉 Chính xác tuyệt đối! Bạn đã ghi nhớ từ này!'
    }
    currentCard.value.mastered = true
    saveNotebook()
  } else {
    cardSpellingFeedback.value = {
      isCorrect: false,
      message: `Chưa đúng, thử lại hoặc bấm [Xem đáp án]!`
    }
  }
}

const toggleMastered = (id) => {
  const item = words.value.find(w => w.id === id)
  if (item) {
    item.mastered = !item.mastered
    saveNotebook()
  }
}

const removeWord = (id) => {
  if (confirm('Bạn có chắc muốn xóa từ này khỏi Sổ tay?')) {
    words.value = words.value.filter(w => w.id !== id)
    saveNotebook()
    if (currentCardIdx.value >= words.value.length) {
      currentCardIdx.value = Math.max(0, words.value.length - 1)
    }
  }
}

const openAddModal = () => {
  newWordForm.value = {
    word: '',
    phonetic: '',
    pos: 'danh từ',
    meaning: '',
    example: '',
    example_vi: ''
  }
  isAddModalOpen.value = true
}

const saveNewWord = () => {
  if (!newWordForm.value.word.trim() || !newWordForm.value.meaning.trim()) return
  const newItem = {
    id: Date.now(),
    word: newWordForm.value.word.trim(),
    phonetic: newWordForm.value.phonetic.trim(),
    pos: newWordForm.value.pos,
    meaning: newWordForm.value.meaning.trim(),
    example: newWordForm.value.example.trim(),
    example_vi: newWordForm.value.example_vi.trim(),
    mastered: false,
    created_at: Date.now()
  }
  words.value.unshift(newItem)
  saveNotebook()
  isAddModalOpen.value = false
}

// Speech Audio Pronunciation
const speak = (text) => {
  if (!text || !window.speechSynthesis) return
  window.speechSynthesis.cancel()
  const utter = new SpeechSynthesisUtterance(text)
  utter.lang = 'en-US'
  utter.rate = 0.9
  window.speechSynthesis.speak(utter)
}

// Quick Quiz Generator
const initQuiz = () => {
  if (words.value.length < 2) return
  quizIdx.value = 0
  quizScore.value = 0
  quizAnswerSubmitted.value = false
  quizSelectedOption.value = null

  const shuffled = [...words.value].sort(() => 0.5 - Math.random())
  const questions = []

  for (let i = 0; i < Math.min(10, shuffled.length); i++) {
    const target = shuffled[i]
    const otherMeanings = words.value
      .filter(w => w.id !== target.id)
      .map(w => w.meaning)
      .sort(() => 0.5 - Math.random())
      .slice(0, 3)

    const options = [target.meaning, ...otherMeanings].sort(() => 0.5 - Math.random())
    questions.push({
      ...target,
      options
    })
  }

  quizQuestions.value = questions
}

const quizCurrentQuestion = computed(() => quizQuestions.value[quizIdx.value] || null)

const submitQuizAnswer = (opt) => {
  if (quizAnswerSubmitted.value || !quizCurrentQuestion.value) return
  quizSelectedOption.value = opt
  quizAnswerSubmitted.value = true
  if (opt === quizCurrentQuestion.value.meaning) {
    quizScore.value++
  }
}

const getQuizOptionClass = (opt) => {
  if (!quizAnswerSubmitted.value) {
    return 'bg-white hover-shadow'
  }
  if (opt === quizCurrentQuestion.value.meaning) {
    return 'btn-success text-white'
  }
  if (opt === quizSelectedOption.value) {
    return 'btn-danger text-white'
  }
  return 'bg-white opacity-50'
}

const nextQuizQuestion = () => {
  if (quizIdx.value < quizQuestions.value.length) {
    quizIdx.value++
    quizAnswerSubmitted.value = false
    quizSelectedOption.value = null
  }
}

onMounted(() => {
  loadNotebook()
  initQuiz()
})
</script>

<style scoped>
.vocabulary-notebook-page {
  min-height: 85vh;
}

.notebook-flashcard-box {
  width: 100%;
  height: 440px;
  min-height: 440px;
  perspective: 1200px;
}

.notebook-flashcard-inner {
  position: relative;
  width: 100%;
  height: 100%;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  transform-style: preserve-3d;
}

.notebook-flashcard-box.is-flipped .notebook-flashcard-inner {
  transform: rotateY(180deg);
}

.notebook-face {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  border-radius: 1.25rem;
  overflow: hidden;
}

.notebook-back {
  transform: rotateY(180deg);
}

@media (max-width: 767.98px) {
  .notebook-flashcard-box {
    height: 480px;
    min-height: 480px;
  }
}
</style>
