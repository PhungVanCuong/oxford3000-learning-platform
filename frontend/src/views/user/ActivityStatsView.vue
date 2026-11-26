<template>
  <div class="activity-stats-page py-4">
    <div class="container max-w-6xl">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb fs-8 mb-0">
          <li class="breadcrumb-item"><router-link to="/" class="text-decoration-none text-muted">Trang chủ</router-link></li>
          <li class="breadcrumb-item active text-teal fw-bold" aria-current="page">Năng suất & Hoạt động học tập</li>
        </ol>
      </nav>

      <!-- Page Title -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
          <h2 class="fw-black text-teal mb-1 display-6">Báo Cáo Năng Suất Học Tập</h2>
          <p class="text-muted fs-7 mb-0">Theo dõi chi tiết số phút học, điểm kinh nghiệm XP, tần suất hoạt động và bản đồ đóng góp kiểu GitHub.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
          <router-link to="/tree" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 shadow-sm fw-bold">
            🌱 Vườn Cây Tri Thức
          </router-link>
          <router-link to="/arena" class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 shadow-sm fw-bold">
            ⚔️ Đấu Trường Quiz
          </router-link>
        </div>
      </div>

      <!-- ROW 1: MATCHING IMAGE 1 (media_1792821726635_511630ad.png) -->
      <div class="row g-4 mb-4">
        <!-- LEFT: Tần suất học trong tuần (Phút) -->
        <div class="col-12 col-lg-8">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100">
            <!-- Header with Time Filter Pills -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3 pb-2 border-bottom">
              <div>
                <h5 class="fw-black text-dark-emphasis mb-0">Tần suất học trong tuần (Phút)</h5>
                <div class="fs-8 text-muted mt-0.5">So sánh thời lượng học giữa tuần trước và tuần này</div>
              </div>

              <!-- Filter Buttons: Hôm nay, Tuần này, Tháng này, Tất cả -->
              <div class="btn-group btn-group-sm p-1 rounded-pill bg-body-tertiary border" role="group">
                <button
                  v-for="flt in filterOptions"
                  :key="flt.key"
                  @click="selectStatsFilter(flt.key)"
                  type="button"
                  class="btn rounded-pill px-2.5 py-1 fs-8 fw-bold border-0"
                  :class="activeFilter === flt.key ? 'btn-teal text-white shadow-xs' : 'btn-link text-muted text-decoration-none'"
                >
                  {{ flt.label }}
                </button>
              </div>
            </div>

            <!-- Stats Header Indicator -->
            <div class="d-flex align-items-center justify-content-between mb-4">
              <div class="d-flex align-items-baseline gap-2">
                <span class="display-6 fw-black text-teal">{{ currentMinutes }}</span>
                <span class="fs-6 fw-bold text-muted">Phút</span>
                <span class="text-secondary">•</span>
                <span class="fs-5 fw-bold text-warning">{{ currentXp }}</span>
                <span class="fs-7 fw-bold text-muted">XP</span>
              </div>

              <!-- Legend: Tuần trước vs Tuần này -->
              <div class="d-flex align-items-center gap-3 fs-8 fw-semibold">
                <div class="d-flex align-items-center gap-1.5">
                  <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #cbd5e1;"></span>
                  <span class="text-muted">Tuần trước</span>
                </div>
                <div class="d-flex align-items-center gap-1.5">
                  <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #00b074;"></span>
                  <span class="text-teal fw-bold">Tuần này</span>
                </div>
              </div>
            </div>

            <!-- Dual Bar Chart: T2 -> CN -->
            <div class="weekly-bars-container d-flex align-items-end justify-content-between pt-4 pb-2 px-2" style="height: 220px;">
              <div
                v-for="item in weeklyBars"
                :key="item.day_code"
                class="day-bar-column d-flex flex-column align-items-center h-100 justify-content-end"
                style="width: 12%;"
              >
                <!-- Bars Pair -->
                <div class="bars-pair d-flex align-items-end justify-content-center gap-1.5 h-100 w-100">
                  <!-- Last Week Bar (Gray) -->
                  <div
                    class="bar-single bg-secondary-subtle rounded-top transition-all"
                    :style="{ height: getBarHeight(item.last_week_minutes) + '%' }"
                    :title="'Tuần trước: ' + item.last_week_minutes + ' phút'"
                    style="width: 14px; background-color: #cbd5e1 !important;"
                  ></div>

                  <!-- This Week Bar (Green) -->
                  <div
                    class="bar-single rounded-top transition-all shadow-xs"
                    :style="{ height: getBarHeight(item.this_week_minutes) + '%' }"
                    :title="'Tuần này: ' + item.this_week_minutes + ' phút'"
                    style="width: 14px; background-color: #00b074 !important;"
                  ></div>
                </div>

                <!-- Day Label -->
                <div class="day-label mt-2 fs-8 fw-bold" :class="item.day_code === currentDayCode ? 'text-teal' : 'text-muted'">
                  {{ item.day_code }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT: Tiến độ khóa học (Circular Donut Gauge) -->
        <div class="col-12 col-lg-4">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
            <div class="border-bottom pb-2 mb-3">
              <h5 class="fw-black text-dark-emphasis mb-0">Tiến độ khóa học</h5>
              <div class="fs-8 text-muted mt-0.5">Tổng số bài học và chặng đã hoàn tất</div>
            </div>

            <!-- Circular Donut Progress Matching Image 1 -->
            <div class="d-flex justify-content-center align-items-center my-3 position-relative">
              <svg width="190" height="190" viewBox="0 0 160 160">
                <!-- Background Ring -->
                <circle
                  cx="80"
                  cy="80"
                  r="62"
                  fill="none"
                  stroke="#f1f5f9"
                  stroke-width="16"
                />
                <!-- Active Progress Ring -->
                <circle
                  cx="80"
                  cy="80"
                  r="62"
                  fill="none"
                  stroke="url(#progressGradient)"
                  stroke-width="16"
                  stroke-linecap="round"
                  :stroke-dasharray="circumference"
                  :stroke-dashoffset="dashOffset"
                  transform="rotate(-90 80 80)"
                  class="transition-all"
                />
                <defs>
                  <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#00b074" />
                    <stop offset="100%" stop-color="#064e40" />
                  </linearGradient>
                </defs>
              </svg>

              <!-- Center Text (43% / 101/235 bài) -->
              <div class="position-absolute text-center">
                <div class="display-6 fw-black text-dark-emphasis lh-1">{{ courseProgress.percent }}%</div>
                <div class="fs-8 fw-bold text-muted mt-1">{{ courseProgress.completed_lessons }}/{{ courseProgress.total_lessons }} bài</div>
              </div>
            </div>

            <!-- Details List -->
            <div class="d-flex flex-column gap-2 pt-2 border-top">
              <div class="d-flex justify-content-between fs-8">
                <span class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> Đã hoàn thành:</span>
                <span class="fw-bold text-dark-emphasis">{{ courseProgress.completed_lessons }} bài</span>
              </div>
              <div class="d-flex justify-content-between fs-8">
                <span class="text-muted"><i class="bi bi-clock-history text-muted me-1"></i> Còn lại:</span>
                <span class="fw-bold text-muted">{{ courseProgress.total_lessons - courseProgress.completed_lessons }} bài</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ROW 2: MATCHING IMAGE 2 (media_1792821793616_41830eee.png) -->
      <!-- GITHUB STYLE CONTRIBUTION HEATMAP -->
      <div class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom mb-4">
        <!-- Top Bar: Contributions Count & Year Selectors -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4 pb-2 border-bottom">
          <div class="d-flex align-items-center gap-2">
            <h5 class="fw-black text-dark-emphasis mb-0">
              {{ heatmapData.total_contributions || 214 }} contributions in the last year
            </h5>
            <span class="badge bg-teal-subtle text-teal border border-teal-subtle rounded-pill fs-8">GitHub Style</span>
          </div>

          <!-- Multi-Year Selector (Requested by user) -->
          <div class="d-flex align-items-center gap-1.5">
            <button
              v-for="yr in availableYears"
              :key="yr"
              @click="loadHeatmap(yr)"
              class="btn btn-sm rounded-pill px-3 py-1 fs-8 fw-bold"
              :class="selectedYear === yr ? 'btn-teal text-white shadow-xs' : 'btn-outline-secondary'"
            >
              {{ yr }}
            </button>
          </div>
        </div>

        <!-- Heatmap Grid Container (Horizontal scroll on mobile) -->
        <div class="heatmap-wrapper overflow-auto pb-2">
          <div class="heatmap-table" style="min-width: 780px;">
            <!-- Month Labels Header -->
            <div class="d-flex fs-8 text-muted mb-2 ms-5">
              <span v-for="(m, mIdx) in months" :key="mIdx" class="month-col" style="flex: 1; text-align: left;">
                {{ m }}
              </span>
            </div>

            <!-- Heatmap Grid: 7 Rows (Days) x 53 Columns (Weeks) -->
            <div class="d-flex">
              <!-- Day of Week Labels (Mon, Wed, Fri) -->
              <div class="d-flex flex-column justify-content-between text-muted fs-8 pe-2" style="width: 36px; height: 110px;">
                <span>Mon</span>
                <span>Wed</span>
                <span>Fri</span>
              </div>

              <!-- Cells Grid (7 rows of 53 cells) -->
              <div class="d-flex gap-1 flex-grow-1">
                <div v-for="(col, colIdx) in weekColumns" :key="colIdx" class="d-flex flex-column gap-1">
                  <div
                    v-for="(day, rIdx) in col"
                    :key="rIdx"
                    class="heatmap-cell rounded-1"
                    :style="{ backgroundColor: getCellColor(day?.intensity) }"
                    :title="formatTooltip(day)"
                    style="width: 12px; height: 12px; cursor: pointer;"
                  ></div>
                </div>
              </div>
            </div>

            <!-- Heatmap Footer: Legend -->
            <div class="d-flex justify-content-between align-items-center fs-8 text-muted mt-3 pt-2">
              <div>
                <a href="javascript:void(0)" class="text-decoration-none text-muted">Learn how we count contributions</a>
              </div>

              <div class="d-flex align-items-center gap-1.5">
                <span>Less</span>
                <span class="rounded-1 d-inline-block" style="width: 12px; height: 12px; background-color: #ebedf0;"></span>
                <span class="rounded-1 d-inline-block" style="width: 12px; height: 12px; background-color: #9be9a8;"></span>
                <span class="rounded-1 d-inline-block" style="width: 12px; height: 12px; background-color: #40c463;"></span>
                <span class="rounded-1 d-inline-block" style="width: 12px; height: 12px; background-color: #30a14e;"></span>
                <span class="rounded-1 d-inline-block" style="width: 12px; height: 12px; background-color: #216e39;"></span>
                <span>More</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()

const activeFilter = ref('today')
const filterOptions = [
  { key: 'today', label: 'Hôm nay' },
  { key: 'week', label: 'Tuần này' },
  { key: 'month', label: 'Tháng này' },
  { key: 'all', label: 'Tất cả' }
]

const weeklyBars = ref([
  { day_code: 'T2', this_week_minutes: 25, last_week_minutes: 18 },
  { day_code: 'T3', this_week_minutes: 35, last_week_minutes: 26 },
  { day_code: 'T4', this_week_minutes: 20, last_week_minutes: 25 },
  { day_code: 'T5', this_week_minutes: 42, last_week_minutes: 30 },
  { day_code: 'T6', this_week_minutes: 30, last_week_minutes: 22 },
  { day_code: 'T7', this_week_minutes: 45, last_week_minutes: 20 },
  { day_code: 'CN', this_week_minutes: 15, last_week_minutes: 15 }
])

const courseProgress = ref({
  percent: 43,
  completed_lessons: 101,
  total_lessons: 235
})

const currentDayCode = computed(() => {
  const day = new Date().getDay()
  const map = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7']
  return map[day]
})

const filterStats = ref({
  time_minutes: 0,
  earned_xp: 0,
  sessions_count: 0,
  words_learned: 0,
})

const currentMinutes = computed(() => filterStats.value.time_minutes ?? 0)

const currentXp = computed(() => {
  if (activeFilter.value === 'all') {
    return authStore.user?.xp_points ?? filterStats.value.earned_xp ?? 0
  }
  return filterStats.value.earned_xp ?? 0
})

const maxMinutes = computed(() => {
  const vals = weeklyBars.value.flatMap(b => [b.this_week_minutes, b.last_week_minutes])
  return Math.max(50, ...vals)
})

const getBarHeight = (mins) => {
  return Math.min(100, Math.max(8, Math.round((mins / maxMinutes.value) * 100)))
}

// Circular Donut Gauge calculations
const radius = 62
const circumference = 2 * Math.PI * radius
const dashOffset = computed(() => {
  const pct = courseProgress.value.percent || 0
  return circumference - (pct / 100) * circumference
})

// Heatmap Data & Settings
const selectedYear = ref(new Date().getFullYear())
const availableYears = ref([2026, 2025, 2024])
const heatmapData = ref({ total_contributions: 0, days: [] })
const months = ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct']

const weekColumns = computed(() => {
  const days = heatmapData.value.days || []
  if (!days.length) {
    // Deterministic empty placeholder grid (no random jitter)
    const cols = []
    for (let c = 0; c < 52; c++) {
      const col = []
      for (let r = 0; r < 7; r++) {
        col.push({
          date: `${selectedYear.value}`,
          count: 0,
          intensity: 0
        })
      }
      cols.push(col)
    }
    return cols
  }

  // Chunk into 7-day columns
  const cols = []
  for (let i = 0; i < days.length; i += 7) {
    cols.push(days.slice(i, i + 7))
  }
  return cols
})

const getCellColor = (intensity) => {
  const isDark = authStore.themeMode === 'dark'
  switch (intensity) {
    case 1: return '#9be9a8'
    case 2: return '#40c463'
    case 3: return '#30a14e'
    case 4: return '#216e39'
    default: return isDark ? '#1e293b' : '#ebedf0'
  }
}

const formatTooltip = (day) => {
  if (!day) return 'Chưa có hoạt động'
  return `${day.count || 0} hoạt động học tập (${day.date})`
}

const loadStudyStats = async (range = activeFilter.value) => {
  try {
    const res = await axios.get('/api/user/study-stats', { params: { range } })
    if (res.data.data) {
      if (res.data.data.weekly_frequency?.length) {
        weeklyBars.value = res.data.data.weekly_frequency
      }
      if (res.data.data.course_progress) {
        courseProgress.value = res.data.data.course_progress
      }
      if (res.data.data.filter_stats) {
        filterStats.value = res.data.data.filter_stats
      }
    }
  } catch (err) {
    console.error('Failed to load study stats:', err)
  }
}

const selectStatsFilter = (key) => {
  activeFilter.value = key
  loadStudyStats(key)
}

const loadHeatmap = async (year) => {
  selectedYear.value = year
  try {
    const res = await axios.get(`/api/user/contribution-heatmap?year=${year}`)
    if (res.data.data) {
      heatmapData.value = res.data.data
      if (res.data.data.available_years) {
        availableYears.value = res.data.data.available_years
      }
    }
  } catch (err) {
    console.error('Failed to load heatmap:', err)
  }
}

onMounted(() => {
  loadStudyStats()
  loadHeatmap(2026)
})
</script>

<style scoped>
.transition-all {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.heatmap-cell:hover {
  outline: 1.5px solid #00b074;
  outline-offset: -1px;
}
</style>
