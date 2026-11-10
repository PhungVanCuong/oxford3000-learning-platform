<template>
  <div class="history-page min-vh-100 pb-5" :style="authStore.themeMode === 'dark' ? 'background-color: #070d0b;' : 'background-color: #F8F7F4;'">
    <div class="container-fluid px-3 px-md-4 px-xl-5 pt-3 pt-md-4 max-w-7xl mx-auto">
      <!-- 1. TOP HEADER (MATCHING IMAGE 1) -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3 mb-md-4">
        <div>
          <h1 class="display-5 fw-black text-dark-emphasis mb-1 tracking-tight">
            Lịch sử
          </h1>
          <p class="text-muted fs-7 mb-0">
            {{ historyStore.totalAttempts }} lượt đã lưu, bấm một lượt để xem lại câu sai.
          </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
          <!-- Button Xóa lịch sử -->
          <button
            @click="handleClearHistory"
            :disabled="historyStore.totalAttempts === 0"
            class="btn btn-outline-secondary rounded-pill px-3.5 py-2 fw-semibold fs-8 d-inline-flex align-items-center gap-1 shadow-2xs"
            :class="authStore.themeMode === 'dark' ? 'border-secondary text-white' : 'border-light-subtle bg-white text-dark'"
          >
            <span>Xóa lịch sử</span>
          </button>

          <!-- Button ← Về lộ trình -->
          <router-link
            to="/curriculum"
            class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-8 d-inline-flex align-items-center gap-1.5 shadow-sm text-decoration-none"
          >
            <i class="bi bi-arrow-left"></i>
            <span>Về lộ trình</span>
          </router-link>
        </div>
      </div>

      <!-- 2. TOP 3 STATS CARDS (MATCHING IMAGE 1: Lượt đã làm | Lượt đạt | Điểm trung bình) -->
      <div class="row g-3 mb-4">
        <!-- Card 1: Lượt đã làm -->
        <div class="col-12 col-md-4">
          <div class="card p-3.5 rounded-4 shadow-sm border bg-card-custom h-100">
            <span class="fs-8 fw-bold text-muted text-uppercase d-block mb-1">
              Lượt đã làm
            </span>
            <div class="display-5 fw-black text-dark-emphasis leading-tight">
              {{ historyStore.totalAttempts }}
            </div>
          </div>
        </div>

        <!-- Card 2: Lượt đạt -->
        <div class="col-12 col-md-4">
          <div class="card p-3.5 rounded-4 shadow-sm border bg-card-custom h-100">
            <span class="fs-8 fw-bold text-muted text-uppercase d-block mb-1">
              Lượt đạt
            </span>
            <div class="display-5 fw-black text-dark-emphasis leading-tight">
              {{ historyStore.passedAttempts }}/{{ historyStore.totalAttempts }}
            </div>
          </div>
        </div>

        <!-- Card 3: Điểm trung bình -->
        <div class="col-12 col-md-4">
          <div class="card p-3.5 rounded-4 shadow-sm border bg-card-custom h-100">
            <span class="fs-8 fw-bold text-muted text-uppercase d-block mb-1">
              Điểm trung bình
            </span>
            <div class="display-5 fw-black text-dark-emphasis leading-tight">
              {{ historyStore.averageScore }}%
            </div>
          </div>
        </div>
      </div>

      <!-- 3. SEARCH BAR (MATCHING IMAGE 1: Tìm bài đã làm | Tên bài hoặc mức độ | 1 lượt) -->
      <div class="card p-2.5 rounded-4 shadow-sm border bg-card-custom mb-3.5">
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2.5">
          <span class="fs-7 fw-bold text-dark-emphasis text-nowrap px-1">
            Tìm bài đã làm
          </span>

          <div class="flex-grow-1">
            <input
              v-model="historyStore.searchKeyword"
              type="text"
              class="form-control rounded-3 border-0 bg-body-tertiary fs-7 py-2"
              placeholder="Tên bài hoặc mức độ"
            />
          </div>

          <span class="text-muted fs-8 text-nowrap px-2">
            {{ historyStore.filteredRecords.length }} lượt
          </span>
        </div>
      </div>

      <!-- 4. HISTORY RECORDS ACCORDION LIST (MATCHING IMAGE 1 & 2) -->
      <div class="d-flex flex-column gap-3">
        <div
          v-for="rec in historyStore.filteredRecords"
          :key="rec.id"
          class="card rounded-4 border bg-card-custom shadow-sm overflow-hidden transition-all"
        >
          <!-- Record Header Bar -->
          <div
            @click="toggleExpand(rec.id)"
            class="p-3.5 d-flex justify-content-between align-items-center cursor-pointer select-none hover-bg-subtle"
          >
            <div class="d-flex align-items-center gap-2.5 min-w-0">
              <!-- Score Badge / Dot -->
              <div class="d-flex flex-column align-items-center shrink-0">
                <span class="rounded-circle bg-danger mb-1" style="width: 7px; height: 7px;"></span>
                <span class="fw-black text-danger fs-8 font-monospace">{{ rec.score }}</span>
              </div>

              <!-- Title & Meta -->
              <div class="min-w-0">
                <h5 class="fw-black text-dark-emphasis mb-0.5 fs-6 text-truncate">
                  {{ rec.title }}
                </h5>
                <div class="text-muted fs-8 text-truncate">
                  {{ rec.date }} • {{ rec.status_note }}
                </div>
              </div>
            </div>

            <!-- Right badges: Difficulty + Stars + Toggle icon -->
            <div class="d-flex align-items-center gap-2.5 shrink-0 ms-2">
              <span class="badge rounded-pill px-2.5 py-1 fs-9 fw-bold bg-teal-subtle text-teal border border-teal-subtle">
                {{ rec.difficulty || 'Dễ' }}
              </span>

              <!-- Stars -->
              <div class="d-none d-sm-flex align-items-center text-warning fs-8">
                <i v-for="s in 3" :key="s" class="bi" :class="s <= (rec.stars || 0) ? 'bi-star-fill' : 'bi-star'"></i>
              </div>

              <!-- Minus / Plus Icon -->
              <i class="bi fs-5 text-muted" :class="expandedIds.includes(rec.id) ? 'bi-dash' : 'bi-plus'"></i>
            </div>
          </div>

          <!-- Expanded Questions Breakdown (MATCHING IMAGE 1 & 2) -->
          <div v-if="expandedIds.includes(rec.id)" class="border-top p-3 p-md-4 bg-body-tertiary">
            <div class="d-flex flex-column gap-2.5">
              <div
                v-for="q in rec.questions"
                :key="q.num"
                class="card p-3 rounded-3 border bg-card-custom shadow-2xs"
              >
                <div class="d-flex align-items-start gap-2.5">
                  <!-- Status Indicator Icon Box (X red, ⏱ yellow, - grey, ✓ green) -->
                  <div
                    class="rounded-2 d-flex align-items-center justify-content-center shrink-0 shadow-2xs mt-0.5 text-white fw-black"
                    :style="{ width: '28px', height: '28px', backgroundColor: getStatusColor(q.status) }"
                  >
                    <i :class="getStatusIcon(q.status)" style="font-size: 13px;"></i>
                  </div>

                  <div class="flex-grow-1 min-w-0 fs-8">
                    <!-- Top Line: Câu X • Status label -->
                    <div class="d-flex align-items-center gap-2 mb-1">
                      <strong class="text-dark-emphasis">Câu {{ q.num }}</strong>
                      <span class="text-muted">•</span>
                      <span class="fw-bold" :style="{ color: getStatusColor(q.status) }">{{ q.status_label }}</span>
                    </div>

                    <!-- Question type -->
                    <div class="fw-bold text-dark-emphasis mb-1">
                      {{ q.type_label }}
                    </div>

                    <!-- Prompt (if exists) -->
                    <div v-if="q.prompt" class="text-muted mb-2 fs-7 fw-medium">
                      {{ q.prompt }}
                    </div>

                    <!-- Answers: Bạn trả lời & Đáp án -->
                    <div class="d-flex align-items-center gap-3 flex-wrap mb-2 fs-8">
                      <!-- User Answer -->
                      <span v-if="q.status === 'timeout'" class="text-danger fw-semibold">
                        Bạn bỏ trống
                      </span>
                      <span v-else-if="q.user_answer" class="text-danger fw-semibold">
                        Bạn trả lời: <span class="text-decoration-underline">{{ q.user_answer }}</span>
                      </span>

                      <!-- Correct Answer -->
                      <span class="text-teal fw-bold">
                        Đáp án: <span>{{ q.correct_answer }}</span>
                      </span>
                    </div>

                    <!-- Explanation -->
                    <div v-if="q.explanation" class="p-2.5 rounded-2 bg-body-tertiary border text-muted fs-8 leading-relaxed">
                      {{ q.explanation }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="historyStore.filteredRecords.length === 0" class="card p-5 text-center rounded-4 border bg-card-custom text-muted">
          <i class="bi bi-clock-history fs-1 d-block mb-2 text-teal"></i>
          <h5 class="fw-bold text-dark-emphasis">Chưa có lịch sử làm bài nào</h5>
          <p class="fs-7 text-muted mb-0">Khi bạn làm bài kiểm tra trắc nghiệm ở Ngữ pháp, Lộ trình, CEFR hay Học theo ngày, kết quả chi tiết từng câu sai sẽ được lưu tại đây để bạn xem lại!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useHistoryStore } from '@/stores/historyStore'

const authStore = useAuthStore()
const historyStore = useHistoryStore()

// Mặc định mở record đầu tiên giống trong ảnh mẫu
const expandedIds = ref([historyStore.records[0]?.id].filter(Boolean))

const toggleExpand = (id) => {
  if (expandedIds.value.includes(id)) {
    expandedIds.value = expandedIds.value.filter(i => i !== id)
  } else {
    expandedIds.value.push(id)
  }
}

const getStatusColor = (status) => {
  switch (status) {
    case 'incorrect': return '#dc2626' // Red
    case 'timeout': return '#f59e0b' // Amber
    case 'unanswered': return '#64748b' // Slate Grey
    case 'correct': return '#059669' // Emerald Green
    default: return '#64748b'
  }
}

const getStatusIcon = (status) => {
  switch (status) {
    case 'incorrect': return 'bi bi-x-lg'
    case 'timeout': return 'bi bi-clock-fill'
    case 'unanswered': return 'bi bi-dash-lg'
    case 'correct': return 'bi bi-check-lg'
    default: return 'bi bi-question-lg'
  }
}

const handleClearHistory = () => {
  if (confirm('Bạn có chắc chắn muốn xóa toàn bộ lịch sử các lượt làm bài kiểm tra không?')) {
    historyStore.clearHistory()
    expandedIds.value = []
  }
}
</script>

<style scoped>
.hover-bg-subtle:hover {
  background-color: rgba(0, 176, 116, 0.04);
}
</style>
