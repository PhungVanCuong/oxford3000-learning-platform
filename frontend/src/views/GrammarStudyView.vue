<template>
  <div class="min-h-screen py-6">
    <!-- Top Navigation -->
    <div class="max-w-4xl mx-auto px-4 mb-6">
      <div class="flex items-center justify-between flex-wrap gap-4 pb-4 border-b border-slate-800">
        <button
          @click="goBack"
          class="flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Quay về Lộ trình</span>
        </button>

        <div class="text-center">
          <div class="text-xs text-purple-400 font-bold uppercase tracking-wider">CHUYÊN ĐỀ NGỮ PHÁP TIẾNG ANH</div>
          <h1 class="text-lg md:text-xl font-black text-white">{{ grammarData?.title || 'Bài học ngữ pháp' }}</h1>
        </div>

        <!-- 2-Phase Pill -->
        <div class="flex items-center gap-1.5 p-1 rounded-xl bg-slate-900 border border-slate-800 text-xs">
          <button
            @click="currentPhase = 'theory'"
            class="px-3 py-1 rounded-lg font-medium transition-all"
            :class="currentPhase === 'theory' ? 'bg-purple-600 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white'"
          >
            1. Lý thuyết & Công thức
          </button>
          <button
            @click="startQuiz"
            class="px-3 py-1 rounded-lg font-medium transition-all"
            :class="currentPhase === 'quiz' ? 'bg-indigo-600 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white'"
          >
            2. Bài tập thực hành (10 câu)
          </button>
        </div>
      </div>
    </div>

    <!-- Content Area -->
    <div v-if="isLoading" class="text-center py-20">
      <div class="w-10 h-10 border-4 border-purple-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
      <p class="text-sm text-slate-400 font-medium">Đang tải lý thuyết ngữ pháp...</p>
    </div>

    <div v-else>
      <!-- GIAI ĐOẠN A: LÝ THUYẾT NGỮ PHÁP -->
      <div v-if="currentPhase === 'theory'" class="max-w-4xl mx-auto px-4">
        <!-- Theory Banner -->
        <div class="p-6 md:p-8 rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl mb-8">
          <div class="flex items-center gap-3 mb-4">
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-purple-500/20 text-purple-400 border border-purple-500/30">
              Cấp độ: {{ grammarData?.level }}
            </span>
            <span class="text-xs text-slate-400 font-medium">Quy chuẩn ngữ pháp Oxford</span>
          </div>

          <h2 class="text-2xl md:text-3xl font-black text-white mb-3">
            {{ grammarData?.title }}
          </h2>
          <p class="text-sm md:text-base text-slate-300 leading-relaxed p-4 rounded-xl bg-slate-950/80 border border-slate-800/80">
            {{ grammarData?.summary }}
          </p>
        </div>

        <!-- Markdown / Theory Content Rendered -->
        <div class="p-6 md:p-8 rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl mb-8 prose prose-invert max-w-none text-slate-300">
          <div v-html="renderedTheory" class="space-y-4"></div>
        </div>

        <!-- Examples with Audio & Intonation Speech Practice -->
        <div class="p-6 md:p-8 rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl mb-8">
          <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <span>🗣️</span>
            <span>Ví dụ minh họa & Luyện ngữ điệu (Audio & Speech)</span>
          </h3>

          <div class="space-y-4">
            <div
              v-for="(example, idx) in grammarData?.examples || []"
              :key="idx"
              class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4"
            >
              <div>
                <div class="text-base font-semibold text-white mb-1">{{ example.en }}</div>
                <div class="text-xs text-slate-400 italic">{{ example.vi }}</div>
              </div>

              <div class="flex items-center gap-2 shrink-0">
                <button
                  @click="speech.speak(example.en)"
                  class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 text-xs font-medium transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                  </svg>
                  <span>Nghe câu</span>
                </button>

                <button
                  @click="speech.startListening(example.en)"
                  class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                  :class="speech.isListening.value ? 'bg-red-600 text-white animate-pulse' : 'bg-emerald-600/30 hover:bg-emerald-600/50 text-emerald-300'"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                  </svg>
                  <span>{{ speech.isListening.value ? 'Đang nghe...' : 'Đọc lại câu' }}</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Pronunciation result for sentence -->
          <div v-if="speech.transcript.value" class="mt-4 p-4 rounded-xl bg-slate-950 border border-slate-800 text-center">
            <span class="text-xs text-slate-400 block mb-1">Giọng nói bạn vừa đọc: "{{ speech.transcript.value }}"</span>
            <span class="text-sm font-bold text-emerald-400">Độ chính xác: {{ speech.pronunciationScore.value }}%</span>
          </div>
        </div>

        <!-- Big Transition Action Button -->
        <div class="text-center py-6">
          <button
            @click="startQuiz"
            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-extrabold text-base transition-all shadow-xl shadow-purple-600/40 inline-flex items-center gap-3 cursor-pointer"
          >
            <span>Đã hiểu lý thuyết → Chuyển sang Bài tập thực hành</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </div>
      </div>

      <!-- GIAI ĐOẠN B: BÀI TẬP THỰC HÀNH -->
      <div v-else-if="currentPhase === 'quiz'">
        <QuizEngine
          quizType="grammar"
          @retake="startQuiz"
          @back-to-roadmap="goBack"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useQuizStore } from '@/stores/quizStore'
import { useSpeech } from '@/composables/useSpeech'
import QuizEngine from '@/components/QuizEngine.vue'

const route = useRoute()
const router = useRouter()
const quizStore = useQuizStore()
const speech = useSpeech()

const currentPhase = ref('theory') // 'theory' | 'quiz'
const grammarData = ref(null)
const isLoading = ref(true)

const renderedTheory = computed(() => {
  if (!grammarData.value?.theory_content) return ''
  // Convert simple markdown headings and lists to HTML
  let content = grammarData.value.theory_content
  content = content.replace(/### (.*?)\n/g, '<h4 class="text-lg font-bold text-purple-300 mt-4 mb-2">$1</h4>')
  content = content.replace(/\* \*\*(.*?)\*\*/g, '<li class="ml-4 list-disc"><strong class="text-white">$1</strong>')
  content = content.replace(/`([^`]+)`/g, '<code class="px-1.5 py-0.5 rounded bg-slate-800 text-indigo-300 font-mono text-sm border border-slate-700">$1</code>')
  content = content.replace(/\n\n/g, '<br/>')
  return content
})

const goBack = () => {
  router.push('/roadmap')
}

const startQuiz = async () => {
  currentPhase.value = 'quiz'
  const nodeId = route.params.id
  await quizStore.loadGrammarQuiz(nodeId)
}

onMounted(async () => {
  const nodeId = route.params.id
  try {
    const res = await axios.get(`/api/grammar/node/${nodeId}`)
    grammarData.value = res.data.data.grammar
  } catch (err) {
    console.error('Failed to load grammar theory:', err)
  } finally {
    isLoading.value = false
  }
})
</script>
