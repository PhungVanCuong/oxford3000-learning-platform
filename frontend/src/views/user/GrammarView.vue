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

      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fs-7 fw-bold shadow-2xs">
          <i class="bi bi-unlock-fill me-1"></i> Mở khóa 100% tự do chọn bài
        </span>
      </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="d-flex align-items-center gap-2 mb-4 overflow-x-auto pb-1 flex-nowrap">
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
                <span v-if="g.is_completed" class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-8 fw-bold">
                  <i class="bi bi-check-circle-fill me-1"></i>Đã học ({{ g.best_score }}%)
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

            <!-- Feature Badges within Card -->
            <div class="d-flex flex-wrap gap-1.5 mb-3 fs-8">
              <span class="badge bg-light text-secondary border px-2 py-1">
                <i class="bi bi-translate text-teal me-1"></i> {{ g.total_exercises || g.questions_count }} bài tập thực hành
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
              :style="{ backgroundColor: g.badge_color || '#00b074' }"
            >
              <span>Học lý thuyết & Làm bài tập</span>
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
