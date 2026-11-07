<template>
  <div class="container py-4 max-w-6xl">
    <!-- Header Banner -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div>
        <div class="d-inline-flex align-items-center gap-2 mb-1.5">
          <span class="rounded-circle bg-teal" style="width: 8px; height: 8px;"></span>
          <span class="badge bg-teal-subtle text-teal border border-teal-subtle rounded-pill px-3 py-1 fs-8 fw-bold">
            CHUYÊN ĐỀ NGỮ PHÁP TIẾNG ANH
          </span>
        </div>
        <h2 class="display-6 fw-black text-dark-emphasis mb-1">
          Hệ Thống <span class="text-teal">12 Thì & Ngữ Pháp</span> Toàn Diện
        </h2>
        <p class="text-muted fs-7 mb-0">
          Tổng hợp toàn bộ công thức chủ động & bị động, dấu hiệu nhận biết, phát âm đuôi -ed/-s và kho bài tập dịch câu Việt - Anh có AI chấm điểm chuẩn xác.
        </p>
      </div>

      <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Segmented Toggle: Ngữ pháp | Từ vựng chủ đề | Từ vựng CEFR -->
        <div class="btn-group p-1 rounded-pill border bg-white shadow-xs">
          <button class="btn btn-sm rounded-pill px-3.5 py-2 fs-7 fw-bold text-white shadow-xs" style="background-color: #032b24;">
            Ngữ pháp
          </button>
          <router-link to="/curriculum" class="btn btn-sm rounded-pill px-3.5 py-2 fs-7 fw-bold text-dark text-decoration-none">
            Từ vựng chủ đề
          </router-link>
          <router-link to="/cefr" class="btn btn-sm rounded-pill px-3.5 py-2 fs-7 fw-bold text-dark text-decoration-none">
            Từ vựng CEFR
          </router-link>
        </div>
      </div>
    </div>

    <!-- Top Overall Progress Dashboard (Matching Curriculum & Daily) -->
    <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4" style="border-left: 6px solid #00b074;">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-md-4">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-4 p-3 bg-teal-subtle text-teal d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
              <i class="bi bi-mortarboard-fill fs-3"></i>
            </div>
            <div>
              <div class="text-muted fs-8 fw-bold text-uppercase">Bài học hoàn thành</div>
              <div class="fs-4 fw-black text-teal">{{ completedLessonsCount }} / {{ grammars.length }} bài</div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4 border-start-md">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-4 p-3 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
              <i class="bi bi-pencil-square fs-3"></i>
            </div>
            <div>
              <div class="text-muted fs-8 fw-bold text-uppercase">Tổng số câu đã hoàn thành</div>
              <div class="fs-4 fw-black text-warning-emphasis">{{ completedQuestionsCount }} / {{ totalQuestionsCount }} câu</div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4 border-start-md">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted fs-8 fw-bold text-uppercase">Tiến độ ngữ pháp toàn khóa</span>
            <span class="fw-black text-teal fs-6">{{ overallPercent }}%</span>
          </div>
          <div class="progress" style="height: 10px; border-radius: 999px;">
            <div
              class="progress-bar rounded-pill bg-teal"
              role="progressbar"
              :style="{ width: `${overallPercent}%` }"
            ></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="d-flex align-items-center gap-2 mb-4 overflow-x-auto pb-1 flex-nowrap mobile-scroll-x no-scrollbar">
      <button
        v-for="cat in categories"
        :key="cat.key"
        @click="selectedCategory = cat.key"
        class="btn btn-sm rounded-pill px-3.5 py-2 fw-bold text-nowrap transition-all shadow-2xs"
        :class="selectedCategory === cat.key ? 'btn-teal text-white shadow-sm' : 'btn-outline-secondary bg-white text-secondary'"
      >
        <i :class="cat.icon" class="me-1.5"></i>
        <span>{{ cat.label }}</span>
        <span class="badge ms-1.5 px-2 py-0.5 rounded-pill" :class="selectedCategory === cat.key ? 'bg-white text-dark' : 'bg-light text-secondary'">
          {{ getCategoryCount(cat.key) }}
        </span>
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="text-center py-5">
      <div class="spinner-border text-teal mb-2" role="status"></div>
      <div class="text-muted fs-7">Đang tải danh mục bài học ngữ pháp...</div>
    </div>

    <!-- Grammar 3-Column Grid (3 ô 1 hàng theo yêu cầu người dùng) -->
    <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <div v-for="g in filteredGrammars" :key="g.id" class="col">
        <div 
          class="card rounded-4 border p-4 h-100 d-flex flex-column justify-content-between grammar-card shadow-sm hover-shadow transition-all bg-white"
          :style="{ borderTop: `4px solid ${g.badge_color || '#00b074'}` }"
        >
          <!-- Top Row: Icon + Level + Status -->
          <div>
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div 
                class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm"
                :style="{ width: '42px', height: '42px', backgroundColor: g.badge_color || '#00b074', fontSize: '20px' }"
              >
                <i :class="g.icon || 'bi-journal-bookmark-fill'"></i>
              </div>

              <div class="d-flex align-items-center gap-1.5 flex-wrap justify-content-end">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-8 fw-bold">
                  {{ g.level }}
                </span>
                <span v-if="isGrammarCompleted(g)" class="badge bg-success text-white px-2.5 py-1 rounded-pill fs-8 fw-bold shadow-xs">
                  <i class="bi bi-check-circle-fill me-1"></i>✓ ĐÃ XONG
                </span>
                <span v-else-if="getGrammarCompletedCount(g) > 0" class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fs-8 fw-bold">
                  <i class="bi bi-clock-history me-1"></i>Đang học ({{ getGrammarPercent(g) }}%)
                </span>
                <span v-else class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill fs-8">
                  Chưa học
                </span>
              </div>
            </div>

            <!-- Title & Summary: IN ĐẬM, TO RÕ, ĐẸP MẮT (Theo yêu cầu) -->
            <h4 class="fw-black text-dark-emphasis mb-2 grammar-card-title">
              {{ g.title }}
            </h4>

            <p class="text-secondary fs-7 mb-3 line-clamp-2" style="line-height: 1.5; min-height: 42px;">
              {{ g.summary || 'Nắm trọn cấu trúc, dấu hiệu nhận biết, bảng công thức chủ động & bị động và bài tập dịch câu.' }}
            </p>

            <!-- Card Progress Section (Matching Curriculum & Daily) -->
            <div class="p-2.5 rounded-3 bg-body-tertiary border mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1 fs-8">
                <span class="text-muted fw-semibold">
                  <i class="bi bi-pencil-fill text-teal me-1"></i>
                  Đã làm: <strong>{{ getGrammarCompletedCount(g) }}/{{ getGrammarTotalCount(g) }}</strong> câu
                </span>
                <span class="fw-bold" :class="isGrammarCompleted(g) ? 'text-success' : 'text-teal'">
                  {{ getGrammarPercent(g) }}%
                </span>
              </div>
              <div class="progress" style="height: 6px; border-radius: 999px;">
                <div
                  class="progress-bar rounded-pill"
                  :class="isGrammarCompleted(g) ? 'bg-success' : 'bg-teal'"
                  :style="{ width: `${getGrammarPercent(g)}%` }"
                ></div>
              </div>
            </div>

            <!-- Feature Badges within Card -->
            <div class="d-flex flex-wrap gap-1.5 mb-2 fs-8">
              <span class="badge bg-light text-secondary border px-2 py-1">
                <i class="bi bi-translate text-teal me-1"></i> {{ getGrammarTotalCount(g) }} bài tập thực hành
              </span>
              <span class="badge bg-light text-secondary border px-2 py-1">
                <i class="bi bi-robot text-primary me-1"></i> Chấm điểm AI
              </span>
            </div>
          </div>

          <!-- Bottom CTA Button -->
          <div class="pt-3 border-top mt-2">
            <router-link 
              :to="`/grammar/${g.id}`" 
              class="btn w-100 rounded-pill py-2.5 fw-black fs-7 shadow-xs d-flex align-items-center justify-content-center gap-2 btn-grammar-action text-white"
              :style="{ backgroundColor: isGrammarCompleted(g) ? '#059669' : (g.badge_color || '#00b074') }"
            >
              <i :class="isGrammarCompleted(g) ? 'bi bi-check2-circle' : 'bi bi-play-circle-fill'"></i>
              <span>{{ isGrammarCompleted(g) ? 'Xem lại kết quả & Ôn tập ✓' : 'Học lý thuyết & Làm bài tập' }}</span>
              <i class="bi bi-arrow-right"></i>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const grammars = ref([])
const isLoading = ref(true)
const selectedCategory = ref('all')
const localProgressMap = ref({})

const loadLocalGrammarProgress = () => {
  try {
    localProgressMap.value = JSON.parse(localStorage.getItem('ox_all_grammar_progress') || '{}')
  } catch (e) {
    localProgressMap.value = {}
  }
}

const getGrammarTotalCount = (g) => {
  if (localProgressMap.value[g.id]?.totalCount) {
    return localProgressMap.value[g.id].totalCount
  }
  return g.total_exercises || g.questions_count || 20
}

const getGrammarCompletedCount = (g) => {
  if (localProgressMap.value[g.id]?.completedCount !== undefined) {
    return localProgressMap.value[g.id].completedCount
  }
  if (g.is_completed) {
    return getGrammarTotalCount(g)
  }
  return 0
}

const isGrammarCompleted = (g) => {
  if (localProgressMap.value[g.id]?.isFinished) return true
  if (g.is_completed) return true
  const comp = getGrammarCompletedCount(g)
  const total = getGrammarTotalCount(g)
  return total > 0 && comp >= total
}

const getGrammarPercent = (g) => {
  const total = getGrammarTotalCount(g)
  if (total === 0) return 0
  const comp = getGrammarCompletedCount(g)
  return Math.min(100, Math.round((comp / total) * 100))
}

const completedLessonsCount = computed(() => {
  return grammars.value.filter(g => isGrammarCompleted(g)).length
})

const totalQuestionsCount = computed(() => {
  return grammars.value.reduce((acc, g) => acc + getGrammarTotalCount(g), 0)
})

const completedQuestionsCount = computed(() => {
  return grammars.value.reduce((acc, g) => acc + getGrammarCompletedCount(g), 0)
})

const overallPercent = computed(() => {
  if (totalQuestionsCount.value === 0) return 0
  return Math.min(100, Math.round((completedQuestionsCount.value / totalQuestionsCount.value) * 100))
})

const categories = [
  { key: 'all', label: 'Tất cả 12 thì & Chuyên đề', icon: 'bi-grid-fill' },
  { key: 'present', label: 'Hiện tại (Present)', icon: 'bi-sun-fill' },
  { key: 'past', label: 'Quá khứ (Past)', icon: 'bi-clock-fill' },
  { key: 'future', label: 'Tương lai (Future)', icon: 'bi-forward-fill' },
  { key: 'special', label: 'Chuyên đề đặc biệt', icon: 'bi-stars' },
]

const fetchGrammars = async () => {
  isLoading.value = true
  try {
    const res = await axios.get('/api/grammars')
    grammars.value = res.data.data || []
  } catch (err) {
    console.error('Failed to load grammars:', err)
  } finally {
    isLoading.value = false
  }
}

const getCategoryCount = (catKey) => {
  if (catKey === 'all') return grammars.value.length
  return grammars.value.filter(g => matchesCategory(g, catKey)).length
}

const matchesCategory = (g, catKey) => {
  const t = g.title.toLowerCase()
  if (catKey === 'present') return t.includes('hiện tại')
  if (catKey === 'past') return t.includes('quá khứ')
  if (catKey === 'future') return t.includes('tương lai')
  if (catKey === 'special') return t.includes('chuyên đề') || t.includes('bị động') || t.includes('phát âm')
  return true
}

const filteredGrammars = computed(() => {
  if (selectedCategory.value === 'all') return grammars.value
  return grammars.value.filter(g => matchesCategory(g, selectedCategory.value))
})

onMounted(() => {
  loadLocalGrammarProgress()
  fetchGrammars()
})
</script>

<style scoped>
.grammar-card {
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  border-color: #e2e8f0 !important;
}

.grammar-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
}

.grammar-card-title {
  font-size: 1.15rem;
  letter-spacing: -0.2px;
  line-height: 1.35;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.btn-grammar-action {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.btn-grammar-action:hover {
  opacity: 0.92;
  transform: scale(1.02);
}

.shadow-2xs {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}
</style>
