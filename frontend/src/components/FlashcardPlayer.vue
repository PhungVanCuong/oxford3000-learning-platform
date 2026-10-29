<template>
  <div class="flashcard-player w-100 mx-auto my-3" style="max-width: 920px;">
    <!-- Helper Tips Banner (Desktop: phím tắt bàn phím; Mobile: chỉ dẫn chạm màn hình) -->
    <div class="alert alert-primary bg-primary-subtle border-primary-subtle text-primary-emphasis d-none d-md-flex align-items-center justify-content-between py-2 px-3 mb-3 rounded-3 shadow-sm fs-7">
      <div>
        <i class="bi bi-lightbulb-fill text-warning me-1"></i>
        <strong>Phím tắt:</strong> <code>Space</code>: Lật thẻ | <code>Q / ←</code>: Trước | <code>E / →</code>: Tiếp | <code>A</code>: Nghe | <code>D</code>: Luyện nói | <code>S</code>: Đã nhớ | <code>X</code>: Cần ôn
      </div>
      <div class="badge bg-primary text-white ms-2 fs-7 px-2.5 py-1">
        {{ currentIndex + 1 }} / {{ words.length }}
      </div>
    </div>

    <!-- Mobile Quick Hint Banner -->
    <div class="d-flex d-md-none align-items-center justify-content-between py-1.5 px-3 mb-2 rounded-3 bg-primary-subtle text-primary-emphasis fs-8 shadow-2xs">
      <div class="d-flex align-items-center gap-1.5">
        <i class="bi bi-hand-index-thumb-fill text-warning"></i>
        <span><strong>Chạm thẻ</strong> để lật xem nghĩa & bản dịch</span>
      </div>
      <div class="badge bg-primary text-white fs-8 px-2 py-0.5 rounded-pill">
        {{ currentIndex + 1 }} / {{ words.length }}
      </div>
    </div>

    <!-- 3D Card Box -->
    <div
      class="perspective-1000 w-100 cursor-pointer select-none mb-3 flashcard-3d-box"
      @click="toggleFlip"
    >
      <div
        class="position-relative w-100 h-100 rounded-4 shadow-lg border-0 transform-style-preserve-3d flashcard-3d-inner"
        :class="{ 'rotate-y-180': isFlipped }"
        style="transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);"
      >
        <!-- FRONT SIDE: Dark Forest Teal with Wave Decoration (Matching Roadmap) -->
        <div
          class="position-absolute top-0 start-0 w-100 h-100 backface-hidden rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between text-white border-0 overflow-hidden"
          style="background: linear-gradient(145deg, #02231d 0%, #04382f 40%, #064e40 100%);"
        >
          <!-- Top Card Info: Category + Status -->
          <div class="d-flex justify-content-between align-items-center position-relative z-1 flex-wrap gap-1">
            <span class="fs-8 fw-bold letter-spacing-1 text-uppercase text-truncate" style="color: #5eead4; max-width: 170px;">
              OXFORD 3000™
            </span>
            <div class="d-flex align-items-center gap-1.5 shrink-0">
              <span v-if="currentWord?.cefr_level" class="badge px-2 py-0.5 rounded-pill fw-bold fs-9" style="background-color: rgba(94, 234, 212, 0.2); color: #5eead4; border: 1px solid rgba(94, 234, 212, 0.35);">
                {{ currentWord.cefr_level }}
              </span>
              <span 
                class="badge px-2.5 py-1 rounded-pill fw-bold fs-9 shadow-xs"
                :class="currentWord?.status === 'mastered' ? 'bg-success text-white' : 'text-white'"
                :style="currentWord?.status !== 'mastered' ? 'background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.25);' : ''"
              >
                {{ currentWord?.status === 'mastered' ? '✔ ĐÃ NHỚ' : currentWord?.status === 'learning' ? 'CẦN ÔN' : 'CHƯA HỌC' }}
              </span>
            </div>
          </div>

          <!-- Main Word Display -->
          <div class="text-center my-auto py-2 position-relative z-1">
            <h1 class="display-5 display-md-4 fw-black text-white mb-2 tracking-tight" style="letter-spacing: -0.5px; word-break: break-word;">
              {{ currentWord?.word }}
            </h1>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-2">
              <span v-if="currentWord?.phonetic_us" class="fs-6 fst-italic" style="color: #a7f3d0;">
                {{ currentWord.phonetic_us }}
              </span>
              <span v-if="currentWord?.part_of_speech" class="badge px-3 py-1 rounded-pill text-uppercase fs-8 fw-bold" style="background-color: rgba(94, 234, 212, 0.2); color: #5eead4; border: 1px solid rgba(94, 234, 212, 0.35);">
                {{ currentWord.part_of_speech }}
              </span>
              <button
                type="button"
                @click.stop="speakCurrent"
                class="btn btn-sm text-white rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center gap-1.5 shadow-xs"
                style="background-color: #032b24; border: 1px solid rgba(255,255,255,0.3);"
                title="Nghe phát âm chuẩn US"
              >
                <i class="bi bi-volume-up-fill text-warning"></i>
                <span>NGHE US</span>
              </button>
            </div>

            <!-- English Example Sentence Shown on Front -->
            <div v-if="currentWord?.example_en" class="p-3 rounded-3 text-start my-2 shadow-xs" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.16); backdrop-filter: blur(4px);" @click.stop>
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge px-2.5 py-0.5 rounded-pill fs-9 fw-bold text-uppercase" style="background-color: rgba(94, 234, 212, 0.25); color: #5eead4;">
                  CÂU VÍ DỤ SONG NGỮ
                </span>
                <button
                  @click="speech.speak(currentWord?.example_en)"
                  class="btn btn-sm text-white py-0 px-2 rounded-pill fs-8 border border-white-50"
                  style="background-color: rgba(0,0,0,0.3);"
                  title="Nghe câu ví dụ"
                >
                  <i class="bi bi-volume-up-fill text-warning"></i> Nghe câu
                </button>
              </div>
              <p class="fs-6 fw-semibold mb-0 text-white" v-html="highlightWord(currentWord?.example_en, currentWord?.word)"></p>
            </div>

            <!-- Speech practice transcript feedback -->
            <div 
              v-if="speech.transcript.value" 
              class="mt-2 px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-2 shadow-sm"
              style="background-color: rgba(0, 0, 0, 0.55); border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(8px);"
            >
              <i class="bi bi-mic-fill text-warning"></i>
              <span class="fw-bold fs-7" style="color: #facc15;">
                Bạn đọc: "{{ speech.transcript.value }}" ({{ speech.pronunciationScore.value }}%
                <span :class="speech.pronunciationFeedback.value === 'excellent' ? 'text-success fw-bold' : speech.pronunciationFeedback.value === 'good' ? 'text-warning fw-bold' : 'text-danger fw-bold'">
                  {{ speech.pronunciationFeedback.value === 'excellent' ? '• Rất chuẩn! 🎉' : speech.pronunciationFeedback.value === 'good' ? '• Tương đối 👍' : '• Hãy đọc lại' }}
                </span>)
              </span>
            </div>
          </div>

          <!-- Flip Hint at Bottom -->
          <div class="text-center text-white-50 fs-8 position-relative z-1 pt-2 border-top" style="border-top-color: rgba(255,255,255,0.12) !important;">
            <i class="bi bi-arrow-repeat me-1"></i>Chạm để lật thẻ xem nghĩa tiếng Việt & câu ví dụ
          </div>

          <!-- Decorative SVG Wave Background (Matching Roadmap & Image) -->
          <div class="position-absolute end-0 bottom-0 pointer-events-none" style="width: 240px; height: 180px; opacity: 0.85;">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="#00b074" d="M47.5,-57.2C59.7,-48.1,66.8,-32.1,68.9,-15.8C71,0.5,68,17.2,59.9,30.8C51.8,44.4,38.6,55,23.6,61.4C8.6,67.8,-8.2,70,-23.7,65.3C-39.2,60.6,-53.4,49,-62.4,34.2C-71.4,19.4,-75.2,1.3,-71.4,-14.8C-67.7,-30.9,-56.3,-45,-42.6,-53.8C-28.9,-62.6,-14.5,-66.1,1.1,-67.4C16.6,-68.7,35.2,-66.3,47.5,-57.2Z" transform="translate(100 100)" />
            </svg>
          </div>
        </div>

        <!-- BACK SIDE: ONLY VIETNAMESE MEANING & VIETNAMESE TRANSLATION -->
        <div
          class="position-absolute top-0 start-0 w-100 h-100 backface-hidden rotate-y-180 rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between text-dark border-0 shadow-lg"
          style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);"
        >
          <div class="d-flex justify-content-between align-items-center">
            <span class="badge bg-success-subtle text-success fw-bold fs-8 px-3 py-1 rounded-pill">
              NGHĨA & BẢN DỊCH TIẾNG VIỆT
            </span>
            <span class="fs-8 text-muted">
              <i class="bi bi-arrow-repeat me-1"></i>Chạm để quay lại
            </span>
          </div>

          <!-- Meaning & Vietnamese Translation -->
          <div class="my-auto py-3 text-center">
            <div class="text-muted fs-7 mb-1">Nghĩa tiếng Việt của từ:</div>
            <h2 class="display-6 fw-bold text-success mb-3">
              {{ currentWord?.meaning_vi }}
            </h2>

            <!-- Vietnamese translation of the example sentence -->
            <div v-if="currentWord?.example_vi" class="bg-light p-3 rounded-3 text-start border my-2">
              <span class="badge bg-secondary text-white fs-8 mb-2">BẢN DỊCH CÂU VÍ DỤ</span>
              <p class="fs-6 text-dark-emphasis mb-0 fst-italic">
                "{{ currentWord?.example_vi }}"
              </p>
            </div>
          </div>

          <div class="text-center pt-2 border-top text-muted fs-8">
            <i class="bi bi-arrow-repeat me-1"></i>Bấm <strong>Space</strong> hoặc nhấp chuột để lật lại mặt trước
          </div>
        </div>
      </div>
    </div>

    <!-- 1. MOBILE-ONLY 2-ROW CONTROL KEYPAD (Thiết kế công thái học vừa khít 100% màn hình điện thoại) -->
    <div class="d-flex d-md-none flex-column gap-1.5 w-100">
      <!-- Row 1: Điều hướng trước / lật thẻ / tiếp theo -->
      <div class="d-flex align-items-center gap-1.5 w-100">
        <button
          @click="prevCard"
          :disabled="currentIndex === 0"
          class="btn btn-outline-secondary btn-sm flex-fill py-2 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8"
        >
          <i class="bi bi-chevron-left"></i>
          <span>Trước</span>
        </button>

        <button
          @click="toggleFlip"
          class="btn btn-teal btn-sm flex-fill py-2 rounded-3 shadow-2xs text-white fw-bold d-flex align-items-center justify-content-center gap-1 fs-8"
          style="background-color: #087F75;"
        >
          <i class="bi bi-arrow-repeat"></i>
          <span>Lật thẻ</span>
        </button>

        <button
          @click="nextCard"
          :disabled="currentIndex === words.length - 1"
          class="btn btn-outline-secondary btn-sm flex-fill py-2 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8"
        >
          <span>Tiếp</span>
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>

      <!-- Row 2: Nghe US / Luyện nói / Đã nhớ / Cần ôn -->
      <div class="d-flex align-items-center gap-1.5 w-100">
        <button
          @click="speakCurrent"
          class="btn btn-primary btn-sm flex-fill py-2 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8"
        >
          <i class="bi bi-volume-up-fill"></i>
          <span>Nghe</span>
        </button>

        <button
          @click="recordCurrent"
          class="btn btn-sm flex-fill py-2 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8"
          :class="speech.isListening.value ? 'btn-danger animate-mic-pulse' : 'btn-info text-white'"
        >
          <i class="bi" :class="speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
          <span>{{ speech.isListening.value ? 'Đang nghe...' : 'Nói' }}</span>
        </button>

        <button
          @click="markCurrentMastered"
          class="btn btn-success btn-sm flex-fill py-2 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8"
        >
          <i class="bi bi-check-circle-fill"></i>
          <span>Đã nhớ</span>
        </button>

        <button
          @click="markCurrentLearning"
          class="btn btn-warning btn-sm text-white flex-fill py-2 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8"
        >
          <i class="bi bi-arrow-clockwise"></i>
          <span>Cần ôn</span>
        </button>
      </div>
    </div>

    <!-- 2. DESKTOP-ONLY CONTROL ROW (Giữ nguyên 100% phím tắt và thiết kế PC / Laptop) -->
    <div class="d-none d-md-flex align-items-center justify-content-center gap-1.5 flex-nowrap py-1 w-100 flashcard-controls-row">
      <!-- [← / Q] Prev -->
      <button
        @click="prevCard"
        :disabled="currentIndex === 0"
        class="btn btn-outline-secondary btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
        title="Lùi thẻ (Phím ← hoặc Q)"
      >
        <i class="bi bi-chevron-left"></i>
        <span>[Q] Trước</span>
      </button>

      <!-- [Space] Flip -->
      <button
        @click="toggleFlip"
        class="btn btn-teal btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
        title="Lật thẻ (Phím Space)"
      >
        <i class="bi bi-arrow-repeat"></i>
        <span>[Space] Lật thẻ</span>
      </button>

      <!-- [A] Listen -->
      <button
        @click="speakCurrent"
        class="btn btn-primary btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
        title="Nghe phát âm chuẩn US (Phím A)"
      >
        <i class="bi bi-volume-up-fill"></i>
        <span>[A] Nghe US</span>
      </button>

      <!-- [D] Speak -->
      <button
        @click="recordCurrent"
        class="btn btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
        :class="speech.isListening.value ? 'btn-danger animate-mic-pulse' : 'btn-info text-white'"
        title="Luyện nói Micro (Phím D)"
      >
        <i class="bi" :class="speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
        <span>[D] {{ speech.isListening.value ? 'Đang nghe...' : 'Luyện nói' }}</span>
      </button>

      <!-- [S] Mastered -->
      <button
        @click="markCurrentMastered"
        class="btn btn-success btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
        title="Đã nhớ từ này (Phím S)"
      >
        <i class="bi bi-check-circle-fill"></i>
        <span>[S] Đã nhớ</span>
      </button>

      <!-- [X] Need Review -->
      <button
        @click="markCurrentLearning"
        class="btn btn-warning btn-sm text-white px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
        title="Cần ôn lại từ này (Phím X)"
      >
        <i class="bi bi-arrow-clockwise"></i>
        <span>[X] Cần ôn</span>
      </button>

      <!-- [→ / E] Next -->
      <button
        @click="nextCard"
        :disabled="currentIndex === words.length - 1"
        class="btn btn-outline-secondary btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
        title="Tiếp thẻ (Phím → hoặc E)"
      >
        <span>[E] Tiếp</span>
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useSpeech } from '@/composables/useSpeech'
import { useAuthStore } from '@/stores/authStore'

const props = defineProps({
  words: {
    type: Array,
    required: true,
    default: () => [],
  },
})

const emit = defineEmits(['update-status', 'status-updated'])

const authStore = useAuthStore()
const isDark = computed(() => authStore.themeMode === 'dark')

const currentIndex = ref(0)
const isFlipped = ref(false)
const speech = useSpeech()

const currentWord = computed(() => {
  if (!props.words || props.words.length === 0) return null
  return props.words[currentIndex.value] || null
})

const toggleFlip = () => {
  isFlipped.value = !isFlipped.value
}

const prevCard = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
    isFlipped.value = false
    speech.transcript.value = ''
    speech.pronunciationScore.value = null
  }
}

const nextCard = () => {
  if (currentIndex.value < props.words.length - 1) {
    currentIndex.value++
    isFlipped.value = false
    speech.transcript.value = ''
    speech.pronunciationScore.value = null
  }
}

const speakCurrent = () => {
  if (currentWord.value?.word) {
    speech.speak(currentWord.value.word, 1.0)
  }
}

const recordCurrent = () => {
  if (currentWord.value?.word) {
    speech.startListening(currentWord.value.word)
  }
}

const markCurrentMastered = () => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để lưu tiến độ "Đã nhớ" vào tài khoản của bạn.')
    return
  }
  if (currentWord.value) {
    currentWord.value.status = 'mastered'
    emit('status-updated', { wordId: currentWord.value.id, status: 'mastered' })
    emit('update-status', { wordId: currentWord.value.id, status: 'mastered' })
    if (currentIndex.value < props.words.length - 1) {
      nextCard()
    }
  }
}

const markCurrentLearning = () => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để lưu tiến độ "Cần ôn" vào tài khoản của bạn.')
    return
  }
  if (currentWord.value) {
    currentWord.value.status = 'learning'
    emit('status-updated', { wordId: currentWord.value.id, status: 'learning' })
    emit('update-status', { wordId: currentWord.value.id, status: 'learning' })
    if (currentIndex.value < props.words.length - 1) {
      nextCard()
    }
  }
}

const highlightWord = (sentence, word) => {
  if (!sentence || !word) return sentence || ''
  const regex = new RegExp(`(${word}[a-z]*)`, 'gi')
  return sentence.replace(regex, '<span class="text-decoration-underline fw-bold text-success">$1</span>')
}

// Global Keyboard Shortcuts
const handleKeyDown = (e) => {
  // If user is focused on an input or textarea, ignore
  const tag = e.target.tagName.toLowerCase()
  if (tag === 'input' || tag === 'textarea' || tag === 'select') return

  // If a modal is open, ignore
  if (document.querySelector('.modal.show')) return

  if (e.code === 'Space') {
    e.preventDefault()
    toggleFlip()
  } else if (e.key === 'ArrowLeft' || e.key === 'q' || e.key === 'Q') {
    e.preventDefault()
    prevCard()
  } else if (e.key === 'ArrowRight' || e.key === 'e' || e.key === 'E') {
    e.preventDefault()
    nextCard()
  } else if (e.key === 'a' || e.key === 'A') {
    e.preventDefault()
    speakCurrent()
  } else if (e.key === 'd' || e.key === 'D') {
    e.preventDefault()
    recordCurrent()
  } else if (e.key === 's' || e.key === 'S') {
    e.preventDefault()
    markCurrentMastered()
  } else if (e.key === 'x' || e.key === 'X') {
    e.preventDefault()
    markCurrentLearning()
  }
}

watch(() => props.words, () => {
  currentIndex.value = 0
  isFlipped.value = false
})

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
  speech.stopSpeech()
  speech.stopListening()
})
</script>
