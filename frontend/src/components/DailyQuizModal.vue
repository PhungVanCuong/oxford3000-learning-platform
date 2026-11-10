<template>
  <div
    class="modal fade"
    id="dailyQuizModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="dailyQuizModalLabel"
    aria-hidden="true"
    ref="modalElement"
  >
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-0 shadow-lg" :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark'">
        <!-- Modal Header -->
        <div class="modal-header border-bottom py-3" :class="authStore.themeMode === 'dark' ? 'border-secondary' : 'border-light-subtle'">
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-teal text-white py-1.5 px-3 rounded-pill fs-7">
              <i class="bi bi-patch-check-fill me-1"></i> BÀI KIỂM TRA TRẮC NGHIỆM
            </span>
            <h5 class="modal-title fw-bold mb-0 text-teal" id="dailyQuizModalLabel">
              {{ quizData?.reference_info || 'Kiểm tra 15 câu trắc nghiệm' }}
            </h5>
          </div>

          <button
            type="button"
            class="btn-close"
            :class="authStore.themeMode === 'dark' ? 'btn-close-white' : ''"
            @click="confirmCloseModal"
            aria-label="Close"
          ></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body p-4 p-md-5">
          <!-- STATE 1: ACTIVE QUIZ IN PROGRESS -->
          <div v-if="!isSubmitted && currentQuestion">
            <!-- Progress & Timer Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
              <span class="fw-bold fs-6 text-muted">
                Câu hỏi {{ currentIndex + 1 }} / {{ questions.length }}
              </span>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace px-2.5 py-1.5 fs-7">
                  <i class="bi bi-clock me-1"></i> {{ formatTime(timerSeconds) }}
                </span>
                <div class="progress" style="width: 140px; height: 10px;">
                  <div
                    class="progress-bar bg-teal"
                    role="progressbar"
                    :style="{ width: `${progressPercent}%` }"
                  ></div>
                </div>
              </div>
            </div>

            <!-- Active Question Card -->
            <div class="card p-4 p-md-5 rounded-4 border shadow-sm mb-4" :class="authStore.themeMode === 'dark' ? 'bg-black border-secondary' : 'bg-light border-light-subtle'">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fs-8">
                  Trắc nghiệm 4 lựa chọn (A, B, C, D)
                </span>
                <button
                  v-if="currentQuestion.audio_text"
                  @click="speech.speak(currentQuestion.audio_text)"
                  class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-8"
                >
                  <i class="bi bi-volume-up-fill"></i> Phát âm
                </button>
              </div>

              <div class="fs-7 text-muted mb-1">{{ currentQuestion.title || 'Chọn đáp án chính xác:' }}</div>
              <h2 class="fw-bold mb-2 text-teal">{{ currentQuestion.prompt }}</h2>
              <div v-if="currentQuestion.phonetic" class="text-muted font-monospace mb-4 fs-6">
                {{ currentQuestion.phonetic }} • <span class="badge bg-secondary-subtle text-secondary">{{ currentQuestion.part_of_speech }}</span>
              </div>

              <!-- 100% Multiple Choice (4 Options A, B, C, D) -->
              <div class="row g-3">
                <div v-for="(opt, oIdx) in activeOptions" :key="oIdx" class="col-12 col-md-6">
                  <button
                    @click="selectOption(opt)"
                    :disabled="isAnswerChecked"
                    class="btn w-100 p-3 text-start rounded-3 d-flex align-items-center gap-3 border shadow-sm transition-all"
                    :class="getOptionClass(opt)"
                  >
                    <span class="badge rounded-circle px-2.5 py-1.5 fs-7" :class="getBadgeClass(opt, oIdx)">
                      {{ ['A', 'B', 'C', 'D'][oIdx] }}
                    </span>
                    <span class="fs-6 fw-medium flex-grow-1">{{ opt }}</span>
                  </button>
                </div>
              </div>

              <!-- Instant Explanation Box (after checked) -->
              <div v-if="isAnswerChecked" class="alert mt-4 mb-0 rounded-3 py-3 px-4" :class="isCurrentCorrect ? 'alert-success border-success' : 'alert-danger border-danger'">
                <div class="d-flex align-items-center gap-2 fw-bold mb-1 fs-6">
                  <i class="bi" :class="isCurrentCorrect ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger'"></i>
                  <span>{{ isCurrentCorrect ? 'Chính xác! Làm rất tốt 🎉' : 'Chưa chính xác! Đáp án đúng là: ' + currentQuestion.correct_answer }}</span>
                </div>
                <p class="mb-1 fs-7">{{ currentQuestion.explanation }}</p>
                <div v-if="currentQuestion.example_en" class="fs-8 text-muted pt-1 border-top mt-2">
                  <strong>Ví dụ: </strong> {{ currentQuestion.example_en }} - <em>{{ currentQuestion.example_vi }}</em>
                </div>
              </div>
            </div>

            <!-- Next or Submit Button -->
            <div v-if="isAnswerChecked" class="d-flex justify-content-end">
              <button
                v-if="currentIndex < questions.length - 1"
                @click="nextQuestion"
                class="btn btn-teal btn-lg rounded-pill px-5 fw-bold shadow"
              >
                Câu tiếp theo →
              </button>
              <button
                v-else
                @click="submitFinalQuiz"
                class="btn btn-warning btn-lg text-white rounded-pill px-5 fw-bold shadow"
              >
                Nộp bài kiểm tra & Xem kết quả 🏆
              </button>
            </div>
          </div>

          <!-- STATE 2: QUIZ COMPLETED / RESULTS -->
          <div v-else-if="isSubmitted && quizResult" class="text-center py-4">
            <div class="mb-3">
              <span
                class="badge rounded-pill px-4 py-2 fs-6 shadow-sm"
                :class="quizResult.accuracy_percent >= 80 ? 'bg-success text-white' : 'bg-warning text-dark'"
              >
                {{ quizResult.accuracy_percent >= 80 ? '🎉 ĐẠT CHUẨN XUẤT SẮC' : '💪 CẦN CỐ GẮNG THÊM' }}
              </span>
            </div>

            <h1 class="display-3 fw-black mb-2 text-teal">
              {{ quizResult.accuracy_percent }}%
            </h1>
            <p class="text-muted fs-6 mb-4">
              Bạn đã trả lời đúng <strong>{{ quizResult.score }}</strong> / <strong>{{ quizResult.total_questions }}</strong> câu hỏi.
              <span v-if="quizResult.xp_earned > 0" class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2">
                +{{ quizResult.xp_earned }} XP Tích lũy
              </span>
            </p>

            <!-- Detailed Answer Review List -->
            <div class="card rounded-4 border shadow-sm text-start overflow-hidden mb-4 bg-card-custom">
              <div class="card-header bg-teal text-white py-2.5 px-4 fw-bold fs-7">
                Chi Tiết Từng Câu Hỏi
              </div>
              <div class="list-group list-group-flush fs-7">
                <div
                  v-for="(ans, aIdx) in quizResult.details || userAnswers"
                  :key="aIdx"
                  class="list-group-item p-3"
                >
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold text-teal">Câu {{ aIdx + 1 }}: {{ ans.prompt }}</span>
                    <span class="badge" :class="ans.is_correct ? 'bg-success' : 'bg-danger'">
                      {{ ans.is_correct ? 'Đúng' : 'Sai' }}
                    </span>
                  </div>
                  <div class="fs-8">
                    <span>Bạn chọn: <strong :class="ans.is_correct ? 'text-success' : 'text-danger'">{{ ans.user_answer }}</strong></span>
                    <span v-if="!ans.is_correct" class="ms-3 text-muted">
                      Đáp án đúng: <strong class="text-success">{{ ans.correct_answer }}</strong>
                    </span>
                  </div>
                  <div v-if="ans.explanation" class="fs-8 text-secondary mt-1">
                    {{ ans.explanation }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-center gap-3">
              <button @click="resetAndRetake" class="btn btn-outline-teal rounded-pill px-4 py-2.5 fw-bold">
                <i class="bi bi-arrow-repeat me-1"></i> Làm lại bài kiểm tra
              </button>
              <button @click="dismissModal" class="btn btn-teal rounded-pill px-5 py-2.5 fw-bold shadow">
                Hoàn tất & Đóng
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onUnmounted } from 'vue'
import axios from 'axios'
import confetti from 'canvas-confetti'
import { useAuthStore } from '@/stores/authStore'
import { useSpeech } from '@/composables/useSpeech'
import { useHistoryStore } from '@/stores/historyStore'
import * as bootstrap from 'bootstrap'

const props = defineProps({
  quizData: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits(['quiz-completed'])

const authStore = useAuthStore()
const speech = useSpeech()
const historyStore = useHistoryStore()

const modalElement = ref(null)
const currentIndex = ref(0)
const selectedOption = ref(null)
const isAnswerChecked = ref(false)
const isCurrentCorrect = ref(false)
const userAnswers = ref([])

const timerSeconds = ref(0)
let timerId = null

const isSubmitted = ref(false)
const quizResult = ref(null)

const questions = computed(() => {
  return props.quizData?.questions || []
})

const currentQuestion = computed(() => {
  if (questions.value.length === 0) return null
  return questions.value[currentIndex.value] || null
})

const activeOptions = computed(() => {
  const q = currentQuestion.value
  if (!q) return []
  if (q.options && q.options.length >= 4) {
    return q.options.slice(0, 4)
  }
  // Fallback 4 options
  const opts = q.options ? [...q.options] : []
  if (!opts.includes(q.correct_answer)) {
    opts.unshift(q.correct_answer)
  }
  while (opts.length < 4) {
    opts.push(`Đáp án khác ${opts.length + 1}`)
  }
  return opts.slice(0, 4)
})

const progressPercent = computed(() => {
  if (questions.value.length === 0) return 0
  return Math.round(((currentIndex.value + 1) / questions.value.length) * 100)
})

const startTimer = () => {
  stopTimer()
  timerSeconds.value = 0
  timerId = setInterval(() => {
    timerSeconds.value++
  }, 1000)
}

const stopTimer = () => {
  if (timerId) {
    clearInterval(timerId)
    timerId = null
  }
}

const formatTime = (secs) => {
  const m = Math.floor(secs / 60)
  const s = secs % 60
  return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`
}

const selectOption = (opt) => {
  if (isAnswerChecked.value) return
  selectedOption.value = opt
  checkCurrentAnswer(opt)
}

const checkCurrentAnswer = (ans) => {
  const q = currentQuestion.value
  if (!q) return

  const cleanAns = ans.trim().toLowerCase()
  const cleanCorrect = q.correct_answer.trim().toLowerCase()
  const isCorrect = cleanAns === cleanCorrect

  isAnswerChecked.value = true
  isCurrentCorrect.value = isCorrect

  userAnswers.value.push({
    question_id: q.id,
    prompt: q.prompt,
    user_answer: ans,
    correct_answer: q.correct_answer,
    is_correct: isCorrect,
    explanation: q.explanation || '',
    vocabulary_id: q.vocabulary_id || null,
  })
}

const nextQuestion = () => {
  if (currentIndex.value < questions.value.length - 1) {
    currentIndex.value++
    selectedOption.value = null
    isAnswerChecked.value = false
    isCurrentCorrect.value = false
  }
}

const submitFinalQuiz = async () => {
  stopTimer()
  try {
    const payload = {
      quiz_type: props.quizData.quiz_type || 'daily_vocab',
      reference_info: props.quizData.reference_info || 'Bài kiểm tra',
      answers: userAnswers.value,
    }
    const res = await axios.post('/api/progress/quiz-submit', payload)
    quizResult.value = res.data.data
    isSubmitted.value = true

    if (quizResult.value.accuracy_percent >= 80) {
      triggerConfetti()
    }

    // Đồng bộ ngay vào Lịch sử (HistoryStudyView)
    try {
      historyStore.addAttemptRecord({
        title: props.quizData?.reference_info || 'Bài kiểm tra trắc nghiệm',
        category: props.quizData?.quiz_type === 'grammar' ? 'Ngữ pháp' : 'Từ vựng',
        score: quizResult.value.correct_answers_count ?? 0,
        total: quizResult.value.total_questions ?? questions.value.length,
        difficulty: props.quizData?.difficulty || 'Dễ',
        status_note: `${quizResult.value.correct_answers_count ?? 0}/${quizResult.value.total_questions ?? questions.value.length} câu • ${quizResult.value.is_passed ? 'Đạt chuẩn' : 'Cần cải thiện'}`,
        questions: questions.value.map((q, qIdx) => {
          const ua = userAnswers.value.find(a => a.question_id === q.id)
          const isCorrect = ua ? ua.is_correct : false
          const isTimeout = !ua || !ua.selected_answer
          return {
            num: qIdx + 1,
            status: isCorrect ? 'correct' : (isTimeout ? 'timeout' : 'incorrect'),
            status_label: isCorrect ? 'Đúng' : (isTimeout ? 'Hết giờ' : 'Sai'),
            type_label: q.title || 'Chọn phương án đúng.',
            prompt: q.prompt,
            user_answer: ua?.selected_answer || null,
            correct_answer: q.correct_answer,
            explanation: q.explanation || ''
          }
        })
      })
    } catch (e) {
      console.warn('Failed to sync history attempt:', e)
    }

    authStore.fetchMe()
    emit('quiz-completed', quizResult.value)
  } catch (err) {
    console.error('Quiz submit failed:', err)
  }
}

const resetAndRetake = () => {
  currentIndex.value = 0
  selectedOption.value = null
  isAnswerChecked.value = false
  isCurrentCorrect.value = false
  userAnswers.value = []
  isSubmitted.value = false
  quizResult.value = null
  startTimer()
}

const triggerConfetti = () => {
  try {
    confetti({ particleCount: 120, spread: 80, origin: { y: 0.5 } })
  } catch (e) {}
}

const getOptionClass = (opt) => {
  if (!isAnswerChecked.value) {
    return 'btn-outline-secondary'
  }
  if (opt === currentQuestion.value?.correct_answer) {
    return 'btn-success text-white'
  }
  if (opt === selectedOption.value && !isCurrentCorrect.value) {
    return 'btn-danger text-white'
  }
  return 'btn-outline-secondary opacity-50'
}

const getBadgeClass = (opt, oIdx) => {
  if (!isAnswerChecked.value) {
    return 'bg-secondary text-white'
  }
  if (opt === currentQuestion.value?.correct_answer) {
    return 'bg-white text-success'
  }
  if (opt === selectedOption.value && !isCurrentCorrect.value) {
    return 'bg-white text-danger'
  }
  return 'bg-secondary text-white'
}

const confirmCloseModal = () => {
  if (!isSubmitted.value && currentIndex.value > 0) {
    if (confirm('Bạn đang làm bài kiểm tra. Bạn có chắc chắn muốn thoát không? Tiến độ làm bài hiện tại sẽ không được lưu.')) {
      dismissModal()
    }
  } else {
    dismissModal()
  }
}

const dismissModal = () => {
  stopTimer()
  const modalEl = document.getElementById('dailyQuizModal')
  if (modalEl) {
    const modal = bootstrap.Modal.getInstance(modalEl)
    if (modal) {
      modal.hide()
    }
  }
}

watch(() => props.quizData, (newVal) => {
  if (newVal) {
    resetAndRetake()
  }
}, { immediate: true })

onUnmounted(() => {
  stopTimer()
})
</script>
