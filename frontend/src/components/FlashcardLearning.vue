<template>
  <div class="max-w-3xl mx-auto px-4 py-6">
    <!-- Top Bar: Progress and Card Count -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-2">
        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
          Thẻ {{ currentIndex + 1 }} / {{ words.length }}
        </span>
        <span v-if="currentWord?.cefr_level" class="text-xs font-bold px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
          {{ currentWord.cefr_level }}
        </span>
      </div>

      <div class="flex items-center gap-2">
        <span class="text-xs text-slate-400">Tiến độ xem:</span>
        <div class="w-32 bg-slate-800 rounded-full h-2.5 overflow-hidden">
          <div
            class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2.5 rounded-full transition-all duration-300"
            :style="{ width: `${learningStore.viewedPercentage}%` }"
          ></div>
        </div>
        <span class="text-xs font-bold text-indigo-400">{{ learningStore.viewedPercentage }}%</span>
      </div>
    </div>

    <!-- 3D Flashcard Container -->
    <div
      class="perspective-1000 w-full min-h-[380px] cursor-pointer select-none mb-6"
      @click="toggleFlip"
    >
      <div
        class="relative w-full h-full min-h-[380px] rounded-2xl transition-transform duration-500 transform-style-preserve-3d shadow-2xl"
        :class="{ 'rotate-y-180': isFlipped }"
      >
        <!-- FRONT SIDE -->
        <div
          class="absolute inset-0 w-full h-full backface-hidden rounded-2xl p-8 flex flex-col justify-between bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950/60 border border-slate-700/80 hover:border-indigo-500/50 transition-colors"
        >
          <div class="flex items-center justify-between">
            <span class="text-xs uppercase tracking-wider font-semibold text-indigo-400 bg-indigo-950/80 px-3 py-1 rounded-full border border-indigo-800/50">
              Mặt trước • Từ tiếng Anh
            </span>
            <span class="text-xs text-slate-400 italic">Bấm chuột hoặc phím Space để lật thẻ</span>
          </div>

          <!-- Main Word Display -->
          <div class="text-center my-auto py-4">
            <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-3">
              {{ currentWord?.word }}
            </h2>

            <div class="flex items-center justify-center gap-3 mb-2 flex-wrap">
              <span class="text-lg text-indigo-300 font-mono font-medium bg-slate-800/90 px-3 py-1 rounded-lg border border-slate-700">
                {{ currentWord?.phonetic_us }}
              </span>
              <span class="text-sm text-slate-300 bg-slate-800/90 px-3 py-1 rounded-lg border border-slate-700">
                {{ currentWord?.part_of_speech }}
              </span>
            </div>
          </div>

          <!-- Audio & Pronunciation Practice Bar -->
          <div class="flex flex-col gap-3 pt-4 border-t border-slate-800" @click.stop>
            <div class="flex items-center justify-center gap-3 flex-wrap">
              <!-- Listen 1.0x -->
              <button
                @click="speech.speak(currentWord?.word, 1.0)"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm transition-all shadow-lg shadow-indigo-600/30"
                :disabled="speech.isSpeaking.value"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
                <span>Nghe chuẩn (1.0x)</span>
              </button>

              <!-- Listen Slow 0.75x -->
              <button
                @click="speech.speak(currentWord?.word, 0.75)"
                class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-sm transition-all border border-slate-700"
                :disabled="speech.isSpeaking.value"
              >
                <span>Chậm (0.75x)</span>
              </button>

              <!-- Speak / Micro Button -->
              <button
                @click="handleStartListening(currentWord?.word)"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-white font-medium text-sm transition-all shadow-lg"
                :class="speech.isListening.value ? 'bg-red-600 animate-pulse ring-4 ring-red-500/40' : 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/30'"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
                <span>{{ speech.isListening.value ? 'Đang lắng nghe...' : 'Luyện phát âm' }}</span>
              </button>
            </div>

            <!-- Speech Feedback Display -->
            <div v-if="speech.transcript.value" class="text-center p-3 rounded-xl bg-slate-950/70 border border-slate-800">
              <div class="text-xs text-slate-400 mb-1">
                Bạn vừa đọc: <span class="text-white font-medium">"{{ speech.transcript.value }}"</span>
              </div>
              <div class="flex items-center justify-center gap-2">
                <span class="text-xs font-semibold">Độ chính xác:</span>
                <span
                  class="text-sm font-bold px-2.5 py-0.5 rounded-full"
                  :class="{
                    'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40': speech.pronunciationFeedback.value === 'excellent',
                    'bg-amber-500/20 text-amber-400 border border-amber-500/40': speech.pronunciationFeedback.value === 'good',
                    'bg-rose-500/20 text-rose-400 border border-rose-500/40': speech.pronunciationFeedback.value === 'poor'
                  }"
                >
                  {{ speech.pronunciationScore.value }}%
                  {{ speech.pronunciationFeedback.value === 'excellent' ? '• Rất chuẩn!' : speech.pronunciationFeedback.value === 'good' ? '• Tương đối tốt' : '• Hãy thử lại' }}
                </span>
              </div>
            </div>

            <div v-if="speech.speechError.value" class="text-xs text-rose-400 text-center">
              {{ speech.speechError.value }}
            </div>
          </div>
        </div>

        <!-- BACK SIDE -->
        <div
          class="absolute inset-0 w-full h-full backface-hidden rotate-y-180 rounded-2xl p-8 flex flex-col justify-between bg-gradient-to-br from-slate-900 via-indigo-950/50 to-slate-900 border border-indigo-500/40 shadow-2xl"
        >
          <div class="flex items-center justify-between">
            <span class="text-xs uppercase tracking-wider font-semibold text-purple-400 bg-purple-950/80 px-3 py-1 rounded-full border border-purple-800/50">
              Mặt sau • Nghĩa & Ví dụ
            </span>
            <span class="text-xs text-slate-400 italic">Bấm chuột hoặc Space để lật lại</span>
          </div>

          <!-- Meaning & Examples -->
          <div class="my-auto py-2">
            <div class="text-center mb-6">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Nghĩa tiếng Việt</span>
              <h3 class="text-2xl md:text-3xl font-bold text-emerald-400">
                {{ currentWord?.meaning_vi }}
              </h3>
            </div>

            <!-- Example Section -->
            <div class="bg-slate-950/60 rounded-xl p-4 border border-slate-800" @click.stop>
              <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                  <div class="text-xs text-indigo-400 font-semibold mb-1">CÂU VÍ DỤ SONG NGỮ:</div>
                  <p class="text-base text-slate-200 font-medium leading-relaxed mb-1.5" v-html="highlightWord(currentWord?.example_en, currentWord?.word)"></p>
                  <p class="text-sm text-slate-400 italic">{{ currentWord?.example_vi }}</p>
                </div>
                <button
                  @click="speech.speak(currentWord?.example_en, 0.9)"
                  class="p-2 rounded-lg bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 transition-colors shrink-0"
                  title="Nghe câu ví dụ"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Bottom status -->
          <div class="flex items-center justify-center gap-3 pt-4 border-t border-slate-800" @click.stop>
            <button
              @click="markStatus('need_review')"
              class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-300 font-medium text-sm border border-amber-500/30 transition-all"
            >
              <span>⚠️ Cần học lại</span>
            </button>
            <button
              @click="markStatus('mastered')"
              class="flex items-center gap-2 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm shadow-lg shadow-emerald-600/30 transition-all"
            >
              <span>✅ Đã nhớ từ này</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation & Action Controls -->
    <div class="flex items-center justify-between gap-4 flex-wrap">
      <div class="flex items-center gap-2">
        <button
          @click="prevCard"
          :disabled="currentIndex === 0"
          class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-medium transition-colors border border-slate-700"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          <span>Trước</span>
        </button>

        <button
          @click="nextCard"
          :disabled="currentIndex === words.length - 1"
          class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-medium transition-colors border border-slate-700"
        >
          <span>Tiếp theo</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      <!-- Button to advance to Phase B -->
      <div>
        <button
          @click="$emit('start-quiz')"
          :disabled="!learningStore.allCardsViewed"
          class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg"
          :class="learningStore.allCardsViewed ? 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white shadow-indigo-600/40 cursor-pointer animate-pulse' : 'bg-slate-800 text-slate-500 border border-slate-700 cursor-not-allowed'"
        >
          <span>Bắt đầu làm bài kiểm tra</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Helper keyboard shortcut tip -->
    <div class="mt-4 text-center text-xs text-slate-500">
      Mẹo: Dùng phím <kbd class="px-1.5 py-0.5 rounded bg-slate-800 border border-slate-700 text-slate-300">Space</kbd> để lật thẻ, <kbd class="px-1.5 py-0.5 rounded bg-slate-800 border border-slate-700 text-slate-300">←</kbd> và <kbd class="px-1.5 py-0.5 rounded bg-slate-800 border border-slate-700 text-slate-300">→</kbd> để chuyển thẻ.
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useSpeech } from '@/composables/useSpeech'
import { useLearningStore } from '@/stores/learningStore'

const props = defineProps({
  words: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits(['start-quiz'])

const learningStore = useLearningStore()
const speech = useSpeech()

const currentIndex = ref(0)
const isFlipped = ref(false)

const currentWord = computed(() => props.words[currentIndex.value] || null)

const toggleFlip = () => {
  isFlipped.value = !isFlipped.value
  if (currentWord.value) {
    learningStore.markCardAsViewed(currentWord.value.id)
  }
}

const nextCard = () => {
  if (currentIndex.value < props.words.length - 1) {
    currentIndex.value++
    isFlipped.value = false
    markCurrentAsViewed()
  }
}

const prevCard = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
    isFlipped.value = false
    markCurrentAsViewed()
  }
}

const markCurrentAsViewed = () => {
  if (currentWord.value) {
    learningStore.markCardAsViewed(currentWord.value.id)
  }
}

const markStatus = async (action) => {
  if (!currentWord.value) return
  await learningStore.updateCardStatus(currentWord.value.id, action)
  nextCard()
}

const handleStartListening = (word) => {
  if (speech.isListening.value) {
    speech.stopListening()
  } else {
    speech.startListening(word)
  }
}

const highlightWord = (sentence, word) => {
  if (!sentence || !word) return sentence || ''
  const cleanWord = word.trim().replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')
  const regex = new RegExp(`(${cleanWord})`, 'gi')
  return sentence.replace(regex, '<span class="text-indigo-400 font-bold bg-indigo-950/70 px-1 py-0.5 rounded border border-indigo-700/50">$1</span>')
}

const handleKeydown = (e) => {
  if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return
  if (e.code === 'Space') {
    e.preventDefault()
    toggleFlip()
  } else if (e.code === 'ArrowRight') {
    e.preventDefault()
    nextCard()
  } else if (e.code === 'ArrowLeft') {
    e.preventDefault()
    prevCard()
  }
}

watch(currentWord, (newWord) => {
  if (newWord) {
    markCurrentAsViewed()
  }
}, { immediate: true })

onMounted(() => {
  window.addEventListener('keydown', handleKeydown)
  markCurrentAsViewed()
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown)
})
</script>
