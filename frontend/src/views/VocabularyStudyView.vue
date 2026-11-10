<template>
  <div class="min-h-screen py-6">
    <!-- Top Bar Navigation -->
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
          <div class="text-xs text-indigo-400 font-bold uppercase tracking-wider">HỌC TỪ VỰNG OXFORD 3000</div>
          <h1 class="text-lg md:text-xl font-black text-white">{{ learningStore.currentNode?.title || 'Bài học từ vựng' }}</h1>
        </div>

        <!-- 2-Phase Pill Indicator -->
        <div class="flex items-center gap-1.5 p-1 rounded-xl bg-slate-900 border border-slate-800 text-xs">
          <button
            @click="currentPhase = 'learning'"
            class="px-3 py-1 rounded-lg font-medium transition-all"
            :class="currentPhase === 'learning' ? 'bg-indigo-600 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white'"
          >
            1. Flashcard & Nghe Nói
          </button>
          <button
            @click="trySwitchToQuiz"
            class="px-3 py-1 rounded-lg font-medium transition-all flex items-center gap-1"
            :class="currentPhase === 'quiz' ? 'bg-purple-600 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white'"
          >
            <span>2. Bài kiểm tra (10 câu)</span>
            <span v-if="!learningStore.allCardsViewed" class="text-[10px]">🔒</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Phase Content -->
    <div v-if="learningStore.isLoading" class="text-center py-20">
      <div class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
      <p class="text-sm text-slate-400 font-medium">Đang tải dữ liệu từ điển và khởi tạo bộ thẻ...</p>
    </div>

    <div v-else>
      <!-- Giai đoạn A: Flashcard & Nghe Nói -->
      <div v-if="currentPhase === 'learning'">
        <FlashcardLearning
          :words="learningStore.stageWords"
          @start-quiz="startQuiz"
        />
      </div>

      <!-- Giai đoạn B: Bài kiểm tra củng cố (Quiz Engine) -->
      <div v-else-if="currentPhase === 'quiz'">
        <QuizEngine
          quizType="vocabulary"
          @retake="startQuiz"
          @back-to-roadmap="goBack"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useLearningStore } from '@/stores/learningStore'
import { useQuizStore } from '@/stores/quizStore'
import FlashcardLearning from '@/components/FlashcardLearning.vue'
import QuizEngine from '@/components/QuizEngine.vue'

const route = useRoute()
const router = useRouter()
const learningStore = useLearningStore()
const quizStore = useQuizStore()

const currentPhase = ref('learning') // 'learning' | 'quiz'

const goBack = () => {
  router.push('/roadmap')
}

const trySwitchToQuiz = () => {
  if (learningStore.allCardsViewed) {
    startQuiz()
  } else {
    alert('Vui lòng lật qua xem hết tất cả các thẻ từ vựng ở Giai đoạn 1 trước khi bắt đầu làm bài kiểm tra nhé!')
  }
}

const startQuiz = async () => {
  currentPhase.value = 'quiz'
  const nodeId = route.params.id
  await quizStore.loadVocabularyQuiz(nodeId)
}

onMounted(async () => {
  const nodeId = route.params.id
  await learningStore.fetchStageWords(nodeId)
})
</script>
