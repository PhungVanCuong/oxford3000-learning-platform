<template>
  <div class="container py-4 max-w-6xl">
    <!-- Header -->
    <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
          <router-link to="/topics" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
          </router-link>
          <div>
            <span class="badge bg-teal text-white rounded-pill px-2.5 py-0.5 fs-8 mb-1">CHỦ ĐỀ LỘ TRÌNH</span>
            <h3 class="fw-bold text-teal mb-0">{{ topicData?.topic?.name }}</h3>
          </div>
        </div>

        <button @click="openTopicQuiz" class="btn btn-teal rounded-pill px-4 py-2.5 fw-bold shadow-sm d-flex align-items-center gap-2">
          <i class="bi bi-patch-check-fill text-warning"></i>
          <span>Kiểm tra trắc nghiệm chủ đề</span>
        </button>
      </div>

      <!-- Mode Switcher (Kéo chuột trái lướt ngang mượt mà) -->
      <div v-drag-scroll class="d-flex gap-2 mt-4 pt-3 border-top overflow-x-auto pb-1 no-scrollbar drag-scroll-x">
        <button
          @click="activeTab = 'vocab'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
          :class="activeTab === 'vocab' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-card-text me-1"></i> Thẻ Flashcard Từ Vựng ({{ topicData?.vocabularies?.length || 0 }} từ)
        </button>
        <button
          @click="activeTab = 'grammar'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
          :class="activeTab === 'grammar' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-journal-bookmark me-1"></i> Chuyên đề Ngữ pháp ({{ topicData?.grammars?.length || 0 }} bài)
        </button>
      </div>
    </div>

    <!-- Tab 1: Vocabulary Flashcard Player -->
    <div v-if="activeTab === 'vocab'">
      <div v-if="topicData?.vocabularies && topicData.vocabularies.length > 0">
        <FlashcardPlayer
          :words="topicData.vocabularies"
          @status-updated="handleWordStatus"
          @update-status="handleWordStatus"
        />
      </div>
      <div v-else class="text-center py-5 text-muted">
        Chưa có từ vựng nào trong chủ đề này.
      </div>
    </div>

    <!-- Tab 2: Grammars List -->
    <div v-else-if="activeTab === 'grammar'">
      <div class="row g-3">
        <div v-for="g in topicData?.grammars || []" :key="g.id" class="col-12 col-md-6">
          <div class="card p-3 rounded-4 shadow-sm border bg-card-custom d-flex flex-column justify-content-between h-100">
            <div>
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-8 mb-2">Cấp độ {{ g.level }}</span>
              <h5 class="fw-bold text-teal mb-2">{{ g.title }}</h5>
              <div class="text-muted fs-7 mb-3">{{ g.questions?.length || 0 }} câu hỏi thực hành</div>
            </div>
            <router-link :to="`/grammar/${g.id}`" class="btn btn-outline-teal btn-sm rounded-pill w-100">
              Vào học lý thuyết & bài tập →
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Quiz -->
    <DailyQuizModal
      :quizData="topicQuizData"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import FlashcardPlayer from '@/components/FlashcardPlayer.vue'
import DailyQuizModal from '@/components/DailyQuizModal.vue'
import * as bootstrap from 'bootstrap'

const route = useRoute()
const authStore = useAuthStore()
const topicData = ref(null)
const activeTab = ref('vocab')
const topicQuizData = ref(null)

const openTopicQuiz = async () => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để làm bài kiểm tra trắc nghiệm chủ đề!')
    return
  }
  try {
    const res = await axios.get(`/api/topics/quiz/${route.params.id}`)
    topicQuizData.value = res.data.data
    const modalEl = document.getElementById('dailyQuizModal')
    if (modalEl) {
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl)
      modal.show()
    }
  } catch (err) {
    console.error('Quiz error:', err)
  }
}

const handleWordStatus = async ({ wordId, status }) => {
  try {
    await axios.post('/api/progress/word-status', { vocabulary_id: wordId, status })
    const w = topicData.value?.vocabularies?.find(item => item.id === wordId)
    if (w) w.status = status
  } catch (e) {}
}

onMounted(async () => {
  try {
    const res = await axios.get(`/api/topics/${route.params.id}`)
    topicData.value = res.data.data
  } catch (err) {
    console.error('Failed to load topic:', err)
  }
})
</script>
