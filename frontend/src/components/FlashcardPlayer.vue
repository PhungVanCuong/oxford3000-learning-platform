<template>
  <div class="flashcard-player w-100 max-w-3xl mx-auto my-3">
    <!-- Helper Tips Banner -->
    <div class="alert alert-primary bg-primary-subtle border-primary-subtle text-primary-emphasis d-flex align-items-center justify-content-between py-2 px-3 mb-3 rounded-3 shadow-sm fs-7">
      <div>
        <i class="bi bi-lightbulb-fill text-warning me-1"></i>
        <strong>Phím tắt:</strong> <code>Space</code>: Lật thẻ | <code>Q / ←</code>: Trước | <code>E / →</code>: Tiếp | <code>A</code>: Nghe | <code>D</code>: Luyện nói | <code>S</code>: Đã nhớ | <code>X</code>: Cần ôn
      </div>
      <div class="badge bg-primary text-white ms-2 fs-7 px-2.5 py-1">
        {{ currentIndex + 1 }} / {{ words.length }}
      </div>
    </div>

    <!-- 3D Card Box -->
    <div
      class="perspective-1000 w-100 cursor-pointer select-none mb-3"
      style="min-height: 380px;"
      @click="toggleFlip"
    >
      <div
        class="position-relative w-100 h-100 rounded-4 shadow-lg border border-2 transform-style-preserve-3d flashcard-3d-inner"
        :class="{ 'rotate-y-180': isFlipped }"
        style="min-height: 380px;"
      >
        <!-- FRONT SIDE: Word + Phonetics + ENGLISH EXAMPLE SENTENCE -->
        <div
          class="position-absolute top-0 start-0 w-100 h-100 backface-hidden rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between"
          :class="isDark ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
        >
          <div class="d-flex justify-content-between align-items-center">
            <span class="badge bg-teal text-white px-3 py-1.5 rounded-pill fs-8">
              MẶT TRƯỚC • TỪ TIẾNG ANH & VÍ DỤ
            </span>
            <span v-if="currentWord?.cefr_level" class="badge bg-success-subtle text-success border border-success-subtle">
              {{ currentWord.cefr_level }}
            </span>
          </div>

          <!-- Main Word Display -->
          <div class="text-center my-auto py-2">
            <h1 class="display-4 fw-black text-teal mb-2 tracking-tight">
              {{ currentWord?.word }}
            </h1>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-3">
              <span class="font-monospace fs-5 text-muted bg-body-tertiary px-3 py-1 rounded-3 border">
                {{ currentWord?.phonetic_us }}
              </span>
              <span class="badge bg-secondary-subtle text-secondary-emphasis fs-7 px-3 py-1.5 rounded-3">
                {{ currentWord?.part_of_speech }}
              </span>
            </div>

            <!-- English Example Sentence Shown on Front -->
            <div v-if="currentWord?.example_en" class="bg-body-tertiary p-3 rounded-3 text-start border my-2" @click.stop>
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge bg-teal text-white fs-8">CÂU VÍ DỤ SONG NGỮ</span>
                <button
                  @click="speech.speak(currentWord?.example_en)"
                  class="btn btn-sm btn-outline-teal py-0 px-2 rounded-pill fs-8"
                  title="Nghe câu ví dụ"
                >
                  <i class="bi bi-volume-up-fill"></i> Nghe câu
                </button>
              </div>
              <p class="fs-6 fw-semibold mb-0 text-teal" v-html="highlightWord(currentWord?.example_en, currentWord?.word)"></p>
            </div>
          </div>

          <!-- Front Hint & Speech result -->
          <div class="text-center pt-2 border-top">
            <div v-if="speech.transcript.value" class="alert alert-info py-1 px-3 d-inline-block mb-1 fs-7">
              Bạn đọc: <strong>"{{ speech.transcript.value }}"</strong> ({{ speech.pronunciationScore.value }}%
              <span :class="speech.pronunciationFeedback.value === 'excellent' ? 'text-success fw-bold' : speech.pronunciationFeedback.value === 'good' ? 'text-warning fw-bold' : 'text-danger fw-bold'">
                {{ speech.pronunciationFeedback.value === 'excellent' ? '• Rất chuẩn! 🎉' : speech.pronunciationFeedback.value === 'good' ? '• Tương đối 👍' : '• Hãy đọc lại' }}
              </span>)
            </div>
            <div class="text-muted fs-8">
              <i class="bi bi-arrow-repeat me-1"></i>Nhấp chuột hoặc gõ <strong>Space</strong> để lật thẻ xem Nghĩa & Dịch tiếng Việt
            </div>
          </div>
        </div>

        <!-- BACK SIDE: ONLY VIETNAMESE MEANING & VIETNAMESE TRANSLATION -->
        <div
          class="position-absolute top-0 start-0 w-100 h-100 backface-hidden rotate-y-180 rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between"
          :class="isDark ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
        >
          <div class="d-flex justify-content-between align-items-center">
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fs-8">
              MẶT SAU • NGHĨA & BẢN DỊCH TIẾNG VIỆT
            </span>
            <span class="badge" :class="currentWord?.status === 'mastered' ? 'bg-success text-white' : currentWord?.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary text-white'">
              {{ currentWord?.status === 'mastered' ? 'Đã nhớ' : currentWord?.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
            </span>
          </div>

          <!-- Meaning & Vietnamese Translation -->
          <div class="my-auto py-3 text-center">
            <div class="text-muted fs-7 mb-1">Nghĩa tiếng Việt của từ:</div>
            <h2 class="display-6 fw-bold text-success mb-4">
              {{ currentWord?.meaning_vi }}
            </h2>

            <!-- Vietnamese translation of the example sentence -->
            <div v-if="currentWord?.example_vi" class="bg-body-tertiary p-3 rounded-3 text-start border my-2">
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

    <!-- Complete 7 Action Buttons Row with Shortcut Labels -->
    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
      <!-- [← / Q] Prev -->
      <button
        @click="prevCard"
        :disabled="currentIndex === 0"
        class="btn btn-outline-secondary btn-sm px-2 px-md-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
        title="Lùi thẻ (Phím ← hoặc Q)"
      >
        <i class="bi bi-chevron-left"></i>
        <span class="d-none d-sm-inline">[Q] Trước</span>
      </button>

      <!-- [Space] Flip -->
      <button
        @click="toggleFlip"
        class="btn btn-teal btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
        title="Lật thẻ (Phím Space)"
      >
        <i class="bi bi-arrow-repeat"></i>
        <span>[Space] Lật thẻ</span>
      </button>

      <!-- [A] Listen -->
      <button
        @click="speakCurrent"
        class="btn btn-primary btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
        title="Nghe phát âm chuẩn US (Phím A)"
      >
        <i class="bi bi-volume-up-fill"></i>
        <span>[A] Nghe US</span>
      </button>

      <!-- [D] Speak -->
      <button
        @click="recordCurrent"
        class="btn btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
        :class="speech.isListening.value ? 'btn-danger animate-mic-pulse' : 'btn-info text-white'"
        title="Luyện nói Micro (Phím D)"
      >
        <i class="bi" :class="speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
        <span>[D] {{ speech.isListening.value ? 'Đang nghe...' : 'Luyện nói' }}</span>
      </button>

      <!-- [S] Mastered -->
      <button
        @click="markCurrentMastered"
        class="btn btn-success btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
        title="Đã nhớ từ này (Phím S)"
      >
        <i class="bi bi-check-circle-fill"></i>
        <span>[S] Đã nhớ</span>
      </button>

      <!-- [X] Need Review -->
      <button
        @click="markCurrentLearning"
        class="btn btn-warning btn-sm text-white px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
        title="Cần ôn lại từ này (Phím X)"
      >
        <i class="bi bi-arrow-clockwise"></i>
        <span>[X] Cần ôn</span>
      </button>

      <!-- [→ / E] Next -->
      <button
        @click="nextCard"
        :disabled="currentIndex === words.length - 1"
        class="btn btn-outline-secondary btn-sm px-2 px-md-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
        title="Tiếp thẻ (Phím → hoặc E)"
      >
        <span class="d-none d-sm-inline">[E] Tiếp</span>
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
