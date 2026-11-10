<template>
  <div class="container py-4 max-w-6xl">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">25 CHỦ ĐỀ THỰC TẾ</span>
        <h2 class="fw-black text-teal mb-0">Lộ Trình Học Theo Chủ Đề</h2>
      </div>
      <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fs-7">
        Chính sách mở khóa 100%
      </span>
    </div>

    <!-- Topics Grid -->
    <div class="row g-4">
      <div v-for="topic in topics" :key="topic.id" class="col-12 col-md-6 col-lg-4">
        <div class="card p-4 rounded-4 border shadow-sm bg-card-custom h-100 d-flex flex-column justify-content-between hover-card">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="rounded-3 p-2.5 bg-teal text-white fs-4 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                <i class="bi" :class="topic.icon || 'bi-bookmarks'"></i>
              </div>
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-8">
                {{ topic.vocabularies_count }} từ • {{ topic.grammars_count }} ngữ pháp
              </span>
            </div>

            <h5 class="fw-bold text-teal mb-2">{{ topic.name }}</h5>
            <p class="text-muted fs-7 mb-4 line-clamp-2">{{ topic.description }}</p>

            <!-- Progress 1: Từ vựng -->
            <div class="mb-3">
              <div class="d-flex justify-content-between fs-8 text-muted mb-1">
                <span>Từ vựng</span>
                <span class="fw-bold text-teal">{{ topic.vocab_progress_percent }}% ({{ topic.mastered_vocabularies_count }}/{{ topic.vocabularies_count }})</span>
              </div>
              <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-teal rounded-pill" :style="{ width: `${topic.vocab_progress_percent}%` }"></div>
              </div>
            </div>

            <!-- Progress 2: Ngữ pháp -->
            <div v-if="topic.grammars_count > 0" class="mb-3">
              <div class="d-flex justify-content-between fs-8 text-muted mb-1">
                <span>Ngữ pháp</span>
                <span class="fw-bold text-teal">{{ topic.grammar_progress_percent }}%</span>
              </div>
              <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-success rounded-pill" :style="{ width: `${topic.grammar_progress_percent}%` }"></div>
              </div>
            </div>
          </div>

          <router-link :to="`/topics/${topic.id}`" class="btn btn-outline-teal w-100 rounded-pill py-2 fw-semibold fs-7 mt-3">
            Vào học chủ đề này →
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const topics = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('/api/topics')
    topics.value = res.data.data
  } catch (err) {
    console.error('Failed to load topics:', err)
  }
})
</script>
