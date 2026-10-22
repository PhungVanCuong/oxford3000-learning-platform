<template>
  <div class="container py-4 max-w-4xl">
    <!-- Top Navigation Header -->
    <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
          <router-link to="/grammar" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
          </router-link>
          <div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-8 mb-1">CẤP ĐỘ {{ grammar?.level }}</span>
            <h4 class="fw-bold text-teal mb-0">{{ grammar?.title }}</h4>
          </div>
        </div>

        <!-- 2 Phase Switcher -->
        <div class="d-flex gap-2">
          <button
            @click="currentPhase = 'theory'"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
            :class="currentPhase === 'theory' ? 'btn-teal' : 'btn-outline-secondary'"
          >
            1. Lý thuyết & Công thức
          </button>
          <button
            @click="startQuiz"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
            :class="currentPhase === 'quiz' ? 'btn-teal' : 'btn-outline-secondary'"
          >
            2. Bài tập thực hành
          </button>
        </div>
      </div>
    </div>

    <!-- PHASE 1: THEORY VIEW -->
    <div v-if="currentPhase === 'theory'">
      <!-- Theory Content HTML -->
      <div class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom mb-4">
        <div class="theory-html" v-html="grammar?.theory_html"></div>
      </div>

      <!-- Bilingual Audio Examples with Mic Practice -->
      <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4">
        <h5 class="fw-bold text-teal mb-3">
          <i class="bi bi-soundwave me-2"></i> Ví dụ minh họa & Luyện ngữ điệu (Audio & Mic)
        </h5>

        <div class="d-flex flex-column gap-3">
          <div
            v-for="(ex, exIdx) in grammar?.examples || []"
            :key="exIdx"
            class="p-3 rounded-3 border bg-body-tertiary d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3"
          >
            <div>
              <div class="fs-6 fw-semibold text-teal mb-1">{{ ex.en }}</div>
              <div class="fs-7 text-muted fst-italic">{{ ex.vi }}</div>
            </div>

            <div class="d-flex gap-2 shrink-0">
              <button
                @click="speech.speak(ex.en)"
                class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-8"
              >
                <i class="bi bi-volume-up-fill me-1"></i> Nghe câu
              </button>
              <button
                @click="speech.startListening(ex.en)"
                class="btn btn-sm rounded-pill px-3 py-1 fs-8"
                :class="speech.isListening.value ? 'btn-danger animate-pulse' : 'btn-outline-success'"
              >
                <i class="bi bi-mic-fill me-1"></i> Đọc lại
              </button>
            </div>
          </div>
        </div>

        <div v-if="speech.transcript.value" class="alert alert-info mt-3 mb-0 py-2 fs-7 text-center">
          Giọng bạn vừa đọc: "{{ speech.transcript.value }}" (Chính xác: {{ speech.pronunciationScore.value }}%)
        </div>
      </div>

      <!-- Big Button: Switch to Exercise -->
      <div class="text-center my-4">
        <button
          @click="startQuiz"
          class="btn btn-teal btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg"
          style="background: linear-gradient(135deg, #064e40 0%, #0d7362 100%);"
        >
          <span>Đã nắm vững lý thuyết → Làm bài tập thực hành ngay</span>
          <i class="bi bi-arrow-right ms-2"></i>
        </button>
      </div>
    </div>

    <!-- PHASE 2: EXERCISE QUIZ MODAL -->
    <DailyQuizModal
      :quizData="grammarQuizData"
      @quiz-completed="handleGrammarQuizCompleted"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useSpeech } from '@/composables/useSpeech'
import DailyQuizModal from '@/components/DailyQuizModal.vue'
import * as bootstrap from 'bootstrap'

const route = useRoute()
const speech = useSpeech()

const grammar = ref(null)
const currentPhase = ref('theory')
const grammarQuizData = ref(null)

const startQuiz = async () => {
  currentPhase.value = 'quiz'
  try {
    const res = await axios.get(`/api/grammars/quiz/${route.params.id}`)
    grammarQuizData.value = res.data.data
    const modalEl = document.getElementById('dailyQuizModal')
    if (modalEl) {
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl)
      modal.show()
    }
  } catch (err) {
    console.error('Failed to load grammar quiz:', err)
  }
}

const handleGrammarQuizCompleted = () => {
  currentPhase.value = 'theory'
}

onMounted(async () => {
  try {
    const res = await axios.get(`/api/grammars/${route.params.id}`)
    grammar.value = res.data.data
  } catch (err) {
    console.error('Failed to load grammar:', err)
  }
})
</script>
