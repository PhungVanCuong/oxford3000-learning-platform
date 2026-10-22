<template>
  <div class="container py-4 max-w-5xl">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">CHUYÊN ĐỀ NGỮ PHÁP TIẾNG ANH</span>
        <h2 class="fw-black text-teal mb-0">Học Ngữ Pháp Toàn Diện (101 Bài Học)</h2>
      </div>
      <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fs-7">
        Mở khóa 100% • Tự do chọn bài
      </span>
    </div>

    <!-- Grammar List -->
    <div class="row g-3">
      <div v-for="g in grammars" :key="g.id" class="col-12 col-md-6">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-8">
                Cấp độ {{ g.level }}
              </span>
              <span v-if="g.is_completed" class="badge bg-success text-white fs-8">
                <i class="bi bi-check-circle-fill"></i> Đã hoàn thành ({{ g.best_score }}%)
              </span>
              <span v-else class="badge bg-secondary-subtle text-secondary fs-8">
                Chưa học
              </span>
            </div>

            <h5 class="fw-bold text-teal mb-2">{{ g.title }}</h5>
            <div class="text-muted fs-8 mb-3">
              <i class="bi bi-tag me-1"></i> Chủ đề: {{ g.topic_name }} • {{ g.questions_count }} câu hỏi thực hành
            </div>
          </div>

          <router-link :to="`/grammar/${g.id}`" class="btn btn-outline-teal w-100 rounded-pill py-2 fw-semibold fs-7 mt-2">
            Học lý thuyết & Làm bài tập →
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const grammars = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('/api/grammars')
    grammars.value = res.data.data
  } catch (err) {
    console.error('Failed to load grammars:', err)
  }
})
</script>
