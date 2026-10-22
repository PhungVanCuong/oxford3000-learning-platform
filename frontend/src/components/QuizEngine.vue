<template>
  <div class="max-w-3xl mx-auto px-4 py-6">
    <!-- Header: Title, Progress, Timer -->
    <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
      <div>
        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-500/20 text-purple-400 border border-purple-500/30">
          Câu {{ quizStore.currentIndex + 1 }} / {{ quizStore.questions.length }}
        </span>
        <h2 class="text-xl font-bold text-white mt-1">{{ quizStore.currentQuestion?.title || 'Bài kiểm tra kiến thức' }}</h2>
      </div>

      <div class="flex items-center gap-4">
        <!-- Timer -->
        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 text-xs font-mono font-medium">
          <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ formatTime(quizStore.timeSpentSeconds) }}</span>
        </div>

        <!-- Progress bar -->
        <div class="w-28 bg-slate-800 rounded-full h-2.5 overflow-hidden">
          <div
            class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2.5 rounded-full transition-all duration-300"
            :style="{ width: `${quizStore.progressPercentage}%` }"
          ></div>
        </div>
      </div>
    </div>

    <!-- Active Question Card -->
    <div v-if="quizStore.currentQuestion" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-2xl mb-6">
      <!-- Question Prompt Area -->
      <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
            {{ quizStore.currentQuestion.question_type === 'multiple_choice' ? 'Phần 1: Trắc nghiệm nhận diện' : 'Phần 2: Điền từ chính xác' }}
          </span>

          <!-- Audio button for prompt if available -->
          <button
            v-if="quizStore.currentQuestion.audio_text"
            @click="speech.speak(quizStore.currentQuestion.audio_text)"
            class="flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 text-xs font-medium transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
            </svg>
            <span>Phát âm</span>
          </button>
        </div>

        <h3 class="text-2xl md:text-3xl font-bold text-white tracking-tight mb-2">
          {{ quizStore.currentQuestion.prompt }}
        </h3>

        <div v-if="quizStore.currentQuestion.phonetic" class="text-sm font-mono text-indigo-300">
          {{ quizStore.currentQuestion.phonetic }} • <span class="text-slate-400 font-sans">{{ quizStore.currentQuestion.part_of_speech }}</span>
        </div>
      </div>

      <!-- TYPE A: Multiple Choice Questions -->
      <div v-if="quizStore.currentQuestion.question_type === 'multiple_choice'" class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
        <button
          v-for="(option, idx) in quizStore.currentQuestion.options"
          :key="idx"
          @click="selectOption(option)"
          :disabled="quizStore.isAnswerChecked"
          class="flex items-center gap-3 p-4 rounded-xl border text-left transition-all font-medium text-sm md:text-base"
          :class="getOptionClass(option)"
        >
          <span
            class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0"
            :class="getOptionBadgeClass(option, idx)"
          >
            {{ ['A', 'B', 'C', 'D'][idx] || idx + 1 }}
          </span>
          <span class="flex-1">{{ option }}</span>
        </button>
      </div>

      <!-- TYPE B: Fill-in-the-blank / Typing Questions -->
      <div v-else class="mb-6">
        <div class="mb-4">
          <div class="flex items-center justify-between mb-2">
            <label class="text-xs font-semibold text-slate-300">Gõ đáp án tiếng Anh:</label>
            <button
              v-if="quizStore.currentQuestion.first_letter && !quizStore.isAnswerChecked"
              @click="showHint = true"
              class="text-xs text-amber-400 hover:text-amber-300 font-medium flex items-center gap-1"
            >
              💡 <span>Gợi ý chữ cái đầu</span>
            </button>
          </div>

          <div v-if="showHint" class="mb-2 text-xs px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/30 text-amber-300">
            Từ bắt đầu bằng chữ: <strong class="text-amber-200 font-bold text-sm">[{{ quizStore.currentQuestion.first_letter }}]</strong>
          </div>

          <div class="relative">
            <input
              ref="typingInput"
              v-model="typedInput"
              type="text"
              placeholder="Nhập từ hoặc cụm từ tại đây..."
              :disabled="quizStore.isAnswerChecked"
              @keydown.enter="handleTypingSubmit"
              class="w-full px-4 py-3.5 rounded-xl bg-slate-950 border text-white font-medium text-lg placeholder-slate-600 focus:outline-none transition-colors"
              :class="{
                'border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20': !quizStore.isAnswerChecked,
                'border-emerald-500 bg-emerald-950/20 text-emerald-300': quizStore.isAnswerChecked && quizStore.isCurrentCorrect,
                'border-rose-500 bg-rose-950/20 text-rose-300': quizStore.isAnswerChecked && !quizStore.isCurrentCorrect,
              }"
            />

            <button
              v-if="!quizStore.isAnswerChecked"
              @click="handleTypingSubmit"
              :disabled="!typedInput.trim()"
              class="absolute right-2 top-2 bottom-2 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-bold transition-colors flex items-center gap-1"
            >
              <span>Kiểm tra</span>
              <kbd class="hidden md:inline px-1 py-0.5 rounded bg-indigo-800 text-[10px]">Enter</kbd>
            </button>
          </div>
        </div>

        <!-- RE-TYPE PRACTICE WHEN INCORRECT (Pedagogical retention requirement) -->
        <div
          v-if="quizStore.isAnswerChecked && !quizStore.isCurrentCorrect"
          class="p-4 rounded-xl bg-rose-950/30 border border-rose-500/40 mb-4"
        >
          <div class="flex items-center gap-2 text-rose-400 font-bold text-sm mb-1">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Chưa chính xác! Đáp án đúng là:</span>
            <span class="text-white font-mono bg-rose-900/60 px-2 py-0.5 rounded text-base border border-rose-700/60">
              {{ quizStore.currentQuestion.correct_answer }}
            </span>
          </div>

          <div class="mt-3">
            <label class="text-xs text-rose-300 block mb-1.5 font-medium">
              ✍️ Hãy gõ lại đáp án đúng vào đây để ghi nhớ sâu trước khi sang câu tiếp theo:
            </label>
            <input
              ref="retypeInput"
              v-model="retypedAnswer"
              type="text"
              placeholder="Gõ lại chính xác từ..."
              class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white font-medium text-sm focus:outline-none focus:border-indigo-400"
              :class="{ 'border-emerald-500': isRetypeMatched }"
            />
          </div>
        </div>
      </div>

      <!-- Feedback & Explanation Box (Shown after checking) -->
      <div
        v-if="quizStore.isAnswerChecked"
        class="p-4 rounded-xl border mb-6 transition-all"
        :class="quizStore.isCurrentCorrect ? 'bg-emerald-950/30 border-emerald-500/40' : 'bg-slate-950/80 border-slate-800'"
      >
        <div class="flex items-center gap-2 mb-2 font-bold" :class="quizStore.isCurrentCorrect ? 'text-emerald-400' : 'text-slate-300'">
          <span>{{ quizStore.isCurrentCorrect ? '🎉 Tuyệt vời! Chính xác' : '💡 Giải thích chi tiết:' }}</span>
        </div>
        <p class="text-sm text-slate-300 whitespace-pre-line leading-relaxed">
          {{ quizStore.currentQuestion.explanation }}
        </p>

        <div v-if="quizStore.currentQuestion.example_en" class="mt-3 pt-3 border-t border-slate-800 text-xs text-slate-400">
          <span class="font-semibold text-indigo-400">Ví dụ: </span>
          <span class="text-slate-200">{{ quizStore.currentQuestion.example_en }}</span>
          <div class="italic text-slate-500 mt-0.5">{{ quizStore.currentQuestion.example_vi }}</div>
        </div>
      </div>

      <!-- Bottom Button: Next question or Finish Quiz -->
      <div v-if="quizStore.isAnswerChecked" class="flex justify-end">
        <button
          v-if="!quizStore.isLastQuestion"
          @click="handleNextQuestion"
          :disabled="quizStore.isAnswerChecked && !quizStore.isCurrentCorrect && !isRetypeMatched"
          class="flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold text-sm transition-all shadow-lg shadow-indigo-600/30"
        >
          <span>Câu tiếp theo</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </button>

        <button
          v-else
          @click="handleFinishQuiz"
          :disabled="quizStore.isAnswerChecked && !quizStore.isCurrentCorrect && !isRetypeMatched"
          class="flex items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold text-sm transition-all shadow-lg shadow-emerald-600/40"
        >
          <span>Hoàn thành & Xem kết quả</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
      </div>
    </div>

    <!-- RESULT MODAL -->
    <div
      v-if="quizStore.quizResult"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
    >
      <div class="bg-slate-900 border border-slate-700 rounded-3xl p-6 md:p-8 max-w-lg w-full text-center shadow-2xl relative overflow-hidden">
        <!-- Glowing background decoration -->
        <div
          class="absolute -top-24 -left-24 w-48 h-48 rounded-full blur-3xl opacity-30 pointer-events-none"
          :class="quizStore.quizResult.is_passed ? 'bg-emerald-500' : 'bg-rose-500'"
        ></div>

        <!-- Trophy or Retry Icon -->
        <div class="mb-4 inline-flex items-center justify-center w-20 h-20 rounded-2xl mx-auto shadow-xl"
             :class="quizStore.quizResult.is_passed ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-amber-500/20 text-amber-400 border border-amber-500/40'">
          <span class="text-4xl">{{ quizStore.quizResult.is_passed ? '🏆' : '💪' }}</span>
        </div>

        <h3 class="text-2xl font-black text-white mb-2">
          {{ quizStore.quizResult.is_passed ? 'Chúc mừng bạn đã vượt qua!' : 'Cố gắng lên nhé!' }}
        </h3>
        <p class="text-sm text-slate-400 mb-6">
          {{ quizStore.quizResult.is_passed ? 'Bạn đã đạt chuẩn kiến thức để mở khóa chặng tiếp theo.' : 'Bạn cần đạt tối thiểu 80% điểm để mở khóa bài học tiếp theo.' }}
        </p>

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-3 mb-6 bg-slate-950 p-4 rounded-2xl border border-slate-800">
          <div>
            <div class="text-xs text-slate-500 mb-1">Điểm số</div>
            <div class="text-2xl font-black text-white">
              {{ quizStore.quizResult.score }}/{{ quizStore.quizResult.total_questions }}
            </div>
          </div>
          <div>
            <div class="text-xs text-slate-500 mb-1">Tỷ lệ đúng</div>
            <div
              class="text-2xl font-black"
              :class="quizStore.quizResult.is_passed ? 'text-emerald-400' : 'text-amber-400'"
            >
              {{ quizStore.quizResult.percentage }}%
            </div>
          </div>
          <div>
            <div class="text-xs text-slate-500 mb-1">XP nhận được</div>
            <div class="text-2xl font-black text-indigo-400">
              +{{ quizStore.quizResult.earned_xp }}
            </div>
          </div>
        </div>

        <!-- Unlocked Next Stage Banner -->
        <div
          v-if="quizStore.quizResult.unlocked_next_node"
          class="p-4 rounded-2xl bg-indigo-950/60 border border-indigo-500/40 mb-6 text-left"
        >
          <div class="text-xs text-indigo-400 font-semibold mb-1">🔓 BÀI HỌC TIẾP THEO ĐÃ MỞ KHÓA:</div>
          <div class="text-base font-bold text-white">{{ quizStore.quizResult.unlocked_next_node.title }}</div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 justify-center flex-wrap">
          <button
            @click="$emit('retake')"
            class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-sm transition-colors border border-slate-700"
          >
            Làm lại bài này
          </button>
          <button
            @click="$emit('back-to-roadmap')"
            class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm transition-all shadow-lg shadow-indigo-600/30"
          >
            Quay về Lộ trình học
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch, onMounted } from 'vue'
import { useQuizStore } from '@/stores/quizStore'
import { useSpeech } from '@/composables/useSpeech'
import confetti from 'canvas-confetti'

const props = defineProps({
  quizType: {
    type: String,
    default: 'vocabulary', // 'vocabulary' | 'grammar'
  },
})

const emit = defineEmits(['retake', 'back-to-roadmap'])

const quizStore = useQuizStore()
const speech = useSpeech()

const typedInput = ref('')
const retypedAnswer = ref('')
const showHint = ref(false)
const typingInput = ref(null)
const retypeInput = ref(null)

const isRetypeMatched = computed(() => {
  if (!quizStore.currentQuestion) return false
  const cleanTarget = (quizStore.currentQuestion.correct_answer || '').trim().toLowerCase()
  const cleanRetyped = (retypedAnswer.value || '').trim().toLowerCase()
  return cleanTarget === cleanRetyped
})

const selectOption = (option) => {
  if (quizStore.isAnswerChecked) return
  quizStore.checkAnswer(option)
}

const handleTypingSubmit = () => {
  if (!typedInput.value.trim() || quizStore.isAnswerChecked) return
  quizStore.checkAnswer(typedInput.value)
  if (!quizStore.isCurrentCorrect) {
    nextTick(() => {
      if (retypeInput.value) {
        retypeInput.value.focus()
      }
    })
  }
}

const handleNextQuestion = () => {
  typedInput.value = ''
  retypedAnswer.value = ''
  showHint.value = false
  quizStore.nextQuestion()
  nextTick(() => {
    if (typingInput.value) {
      typingInput.value.focus()
    }
  })
}

const handleFinishQuiz = async () => {
  await quizStore.submitQuiz(props.quizType)
  if (quizStore.quizResult && quizStore.quizResult.is_passed) {
    triggerConfetti()
  }
}

const triggerConfetti = () => {
  try {
    confetti({
      particleCount: 100,
      spread: 70,
      origin: { y: 0.6 },
    })
  } catch (e) {
    console.log('Confetti trigger', e)
  }
}

const formatTime = (seconds) => {
  const m = Math.floor(seconds / 60)
  const s = seconds % 60
  return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`
}

const getOptionClass = (option) => {
  if (!quizStore.isAnswerChecked) {
    return 'bg-slate-950/80 hover:bg-slate-800 border-slate-800 text-slate-200 hover:border-indigo-500/50'
  }

  const correct = quizStore.currentQuestion.correct_answer
  if (option === correct) {
    return 'bg-emerald-950/40 border-emerald-500 text-emerald-300 font-semibold ring-1 ring-emerald-500'
  }

  if (option === quizStore.currentSelectedOption && !quizStore.isCurrentCorrect) {
    return 'bg-rose-950/40 border-rose-500 text-rose-300'
  }

  return 'bg-slate-950/40 border-slate-900 text-slate-500 opacity-50'
}

const getOptionBadgeClass = (option, idx) => {
  if (!quizStore.isAnswerChecked) {
    return 'bg-slate-800 text-slate-300'
  }

  const correct = quizStore.currentQuestion.correct_answer
  if (option === correct) {
    return 'bg-emerald-500 text-white'
  }

  if (option === quizStore.currentSelectedOption && !quizStore.isCurrentCorrect) {
    return 'bg-rose-500 text-white'
  }

  return 'bg-slate-900 text-slate-600'
}

watch(() => quizStore.currentIndex, () => {
  typedInput.value = ''
  retypedAnswer.value = ''
  showHint.value = false
  nextTick(() => {
    if (typingInput.value) {
      typingInput.value.focus()
    }
  })
})

onMounted(() => {
  nextTick(() => {
    if (typingInput.value) {
      typingInput.value.focus()
    }
  })
})
</script>
