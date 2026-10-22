<template>
  <div class="curriculum-roadmap-view pb-5" style="background-color: #F8F7F4; min-height: 100vh;">
    <div class="container-fluid px-3 px-md-4 px-xl-5 pt-4">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-5 my-5">
        <div class="spinner-border text-teal" role="status">
          <span class="visually-hidden">Đang tải...</span>
        </div>
        <p class="text-muted mt-2 fs-7">Đang nạp 25 chủ đề lộ trình Oxford 3000™...</p>
      </div>

      <div v-else>
        <!-- 1. TOP HEADER BANNER (MATCHING IMAGE 1) -->
        <div v-if="currentTopic" class="mb-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
              <div class="d-inline-flex align-items-center gap-2 mb-1.5">
                <span class="rounded-circle bg-teal" style="width: 7px; height: 7px;"></span>
                <span class="fw-bold fs-8 text-teal text-uppercase letter-spacing-1">LỘ TRÌNH TỪ VỰNG</span>
              </div>
              <h1 class="display-5 fw-black text-dark-emphasis mb-1">
                {{ currentTopic.title }}
              </h1>
              <p class="text-muted fs-7 mb-0">
                {{ currentTopic.description || 'Từ vựng về hệ thống giáo dục, cơ hội học tập và quá trình phát triển kỹ năng.' }}
              </p>
            </div>

            <!-- Toggle: Ngữ pháp | Từ vựng -->
            <div class="btn-group p-1 rounded-pill border bg-white shadow-xs">
              <router-link to="/grammar" class="btn btn-sm rounded-pill px-4 py-2 fs-7 fw-bold text-dark">
                Ngữ pháp
              </router-link>
              <button class="btn btn-sm rounded-pill px-4 py-2 fs-7 fw-bold text-white shadow-xs" style="background-color: #032b24;">
                Từ vựng
              </button>
            </div>
          </div>
        </div>

        <!-- 2. MAIN 2-COLUMN VIEW -->
        <div class="row g-4">
          <!-- 2.1 LEFT SIDEBAR: TOPICS SELECTOR (THƯ VIỆN IELTS / CHỌN CHỦ ĐỀ) -->
          <div class="col-12 col-lg-4 col-xl-3.5" style="max-width: 360px;">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="position: sticky; top: 88px; max-height: calc(100vh - 108px); z-index: 20; display: flex; flex-direction: column;">
              <!-- Dark Forest Teal Header Card -->
              <div class="p-3.5 text-white" style="background: linear-gradient(135deg, #032b24 0%, #064e40 60%, #087F75 100%);">
                <div class="d-flex justify-content-between align-items-center mb-1.5 flex-wrap gap-1">
                  <span class="badge px-2.5 py-1 rounded-pill fw-bold text-uppercase" style="background: rgba(255,255,255,0.18); color: #5eead4; font-size: 10px; letter-spacing: 0.5px;">
                    THƯ VIỆN IELTS
                  </span>
                  <span class="fs-8 text-white-75 fw-semibold">25 Chủ đề • 5.000 mục từ</span>
                </div>
                <h4 class="fw-black mb-2.5 text-white">Chọn chủ đề</h4>
                
                <!-- Quick Search Input -->
                <div class="position-relative mt-1">
                  <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 fs-7" style="color: #087F75; z-index: 2;"></i>
                  <input
                    v-model="searchQuery"
                    type="text"
                    class="form-control rounded-pill ps-5 pe-4 py-2 bg-white text-dark border-0 shadow-sm fs-7 fw-medium"
                    placeholder="Tìm kiếm chủ đề (VD: Education, City...)"
                    style="outline: none;"
                  />
                  <button
                    v-if="searchQuery"
                    class="btn btn-sm text-secondary position-absolute top-50 end-0 translate-middle-y me-2 p-0 px-1 fs-7"
                    @click="searchQuery = ''"
                    style="z-index: 2;"
                  >
                    <i class="bi bi-x-circle-fill"></i>
                  </button>
                </div>
              </div>

              <!-- Scrollable 25 Topics List -->
              <div class="p-2.5 overflow-y-auto flex-grow-1 d-flex flex-column gap-2" style="background-color: #F8FAFC;">
                <div
                  v-for="t in filteredTopics"
                  :key="t.id"
                  @click="selectedTopicId = t.id"
                  class="card p-2.5 rounded-3 border transition-all cursor-pointer topic-item-card"
                  :class="selectedTopicId === t.id ? 'active-topic-card' : 'bg-white border-light-subtle hover-shadow'"
                >
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                      <!-- Colored Number Badge (Left) -->
                      <div
                        class="d-flex align-items-center justify-content-center fw-bold fs-7 flex-shrink-0"
                        :style="getPillBadgeStyle(t.order_num)"
                      >
                        {{ String(t.order_num).padStart(2, '0') }}
                      </div>

                      <!-- Topic Title & Progress -->
                      <div class="text-truncate">
                        <div class="fw-bold fs-7 text-dark-emphasis text-truncate leading-snug mb-0.5">{{ t.title }}</div>
                        <div class="text-muted fs-8 text-truncate">
                          {{ t.completed_stages_count || 0 }}/10 bài đã hoàn thành • {{ t.progress_percent || 0 }}%
                        </div>
                      </div>
                    </div>

                    <!-- Small Right Dark Circle Badge -->
                    <div class="ms-2 flex-shrink-0">
                      <div 
                        class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-9 shadow-xs"
                        :style="{
                          width: '24px',
                          height: '24px',
                          backgroundColor: selectedTopicId === t.id ? '#00b074' : '#032b24'
                        }"
                      >
                        <i v-if="t.progress_percent >= 100" class="bi bi-check fs-8"></i>
                        <span v-else>{{ String(t.order_num).padStart(2, '0') }}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- No results state -->
                <div v-if="filteredTopics.length === 0" class="text-center py-4 text-muted fs-7">
                  Không tìm thấy chủ đề nào khớp với "{{ searchQuery }}".
                </div>
              </div>
            </div>
          </div>

          <!-- 2.2 RIGHT MAIN CONTENT: ZIGZAG 10 STAGES S-CURVE (MATCHING IMAGE 1) -->
          <div class="col-12 col-lg-8 col-xl-8.5 flex-grow-1">
            <!-- Top Banner: 10 bài học • 200 mục từ -->
            <div 
              v-if="currentTopic" 
              class="p-3.5 p-md-4 rounded-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 border shadow-xs"
              style="background-color: #eefaf6; border-color: #a7f3d0 !important;"
            >
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 px-3 py-2 text-white fw-bold fs-7 shadow-xs flex-shrink-0" style="background-color: #00b074;">
                  CHỦ ĐỀ {{ String(currentTopic.order_num).padStart(2, '0') }}
                </div>
                <div>
                  <h4 class="fw-black text-dark mb-0.5">10 bài học • 200 mục từ</h4>
                  <p class="text-muted fs-7 mb-0">
                    Mỗi bài học giới thiệu thêm nhiều từ hơn bài trước. (Tiến độ: {{ currentTopic.remembered_terms_count || 0 }}/200 từ)
                  </p>
                </div>
              </div>

              <router-link
                :to="`/curriculum/${currentTopic.id}/stage/1?mode=all`"
                class="btn text-white rounded-pill px-4 py-2.5 fw-bold shadow-sm d-flex align-items-center gap-2 fs-7 text-nowrap flex-shrink-0"
                style="background-color: #032b24;"
              >
                <span>Xem toàn bộ 200 thẻ</span>
                <i class="bi bi-arrow-right"></i>
              </router-link>
            </div>

            <!-- ZIGZAG S-CURVE ROADMAP CONTAINER (10 STAGES) -->
            <div v-if="currentTopic" class="card bg-white rounded-4 border shadow-sm p-4 p-md-5 position-relative mb-4" style="border-color: #E2E8F0;">
              <div class="roadmap-zigzag-timeline position-relative">
                <div 
                  v-for="(st, idx) in currentTopic.stages" 
                  :key="st.stage_num"
                  class="zigzag-stage-wrapper position-relative"
                >
                  <!-- 1. ODD STAGE (01, 03, 05, 07, 09): Circle on Left, Card on Right -->
                  <div 
                    v-if="st.stage_num % 2 !== 0"
                    class="d-flex align-items-center justify-content-start position-relative z-1 mb-4 mb-md-5"
                  >
                    <div 
                      class="stage-content-card-box d-flex align-items-center gap-3 cursor-pointer w-100"
                      style="max-width: 82%;"
                      @click="goToStage(st.stage_num)"
                    >
                      <!-- Circle Node (Attached Left) -->
                      <div 
                        class="stage-circle-node rounded-circle d-flex flex-column align-items-center justify-content-center flex-shrink-0 bg-white"
                        :class="getNodeClass(st)"
                        style="width: 68px; height: 68px;"
                      >
                        <i v-if="st.is_completed" class="bi bi-check-lg fs-4 text-success"></i>
                        <span v-else class="fs-5 fw-black leading-none" :class="isStageUnlocked(st.stage_num) ? 'text-dark' : 'text-muted'">
                          {{ String(st.stage_num).padStart(2, '0') }}
                        </span>
                        <span class="fs-9 fw-bold mt-1 text-uppercase" :class="getNodeLabelClass(st)">
                          {{ getNodeLabel(st) }}
                        </span>
                      </div>

                      <!-- Stage Card -->
                      <div class="card p-3.5 p-md-4 rounded-4 border bg-white shadow-xs hover-card flex-grow-1" style="border-color: #E2E8F0;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span class="fs-9 fw-bold text-danger text-uppercase letter-spacing-05">
                            {{ st.count }} MỤC TỪ • 200 MỤC TỪ/CHỦ ĐỀ
                          </span>
                          <span v-if="st.is_completed" class="badge bg-success-subtle text-success fs-9 fw-bold px-2 py-0.5 rounded-pill">
                            <i class="bi bi-check-circle-fill me-1"></i>Đạt {{ st.score }}/{{ st.count }} câu
                          </span>
                        </div>
                        <h5 class="fw-black text-dark mb-1">Bài từ vựng {{ String(st.stage_num).padStart(2, '0') }}</h5>
                        <p class="text-muted fs-7 mb-2 leading-relaxed">
                          Học bằng thẻ lật, sau đó kiểm tra mức độ ghi nhớ.
                        </p>
                        <div class="d-flex align-items-center justify-content-between">
                          <span class="fs-8 fw-bold" :class="st.is_completed ? 'text-success' : (isStageUnlocked(st.stage_num) ? 'text-teal' : 'text-muted')">
                            {{ getStageActionText(st) }}
                          </span>
                          <i class="bi bi-arrow-right fs-7" :class="isStageUnlocked(st.stage_num) ? 'text-teal' : 'text-muted'"></i>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Right Connector Curve (Odd to Even: Curves down on right side) -->
                  <div 
                    v-if="st.stage_num % 2 !== 0 && st.stage_num < 10" 
                    class="d-none d-md-flex justify-content-end position-relative connector-curve-right"
                    style="height: 52px; margin-top: -36px; margin-bottom: -18px; padding-right: 6%;"
                  >
                    <svg width="120" height="60" viewBox="0 0 120 60" fill="none">
                      <path 
                        d="M 10 5 H 70 C 105 5, 105 55, 70 55 H 10" 
                        fill="none" 
                        :stroke="st.is_completed ? '#00b074' : '#cbd5e1'" 
                        stroke-width="2.5" 
                        stroke-dasharray="6 6"
                        class="connector-dash-anim"
                      />
                    </svg>
                  </div>

                  <!-- 2. EVEN STAGE (02, 04, 06, 08, 10): Card on Left, Circle on Right -->
                  <div 
                    v-if="st.stage_num % 2 === 0"
                    class="d-flex align-items-center justify-content-end position-relative z-1 mb-4 mb-md-5"
                  >
                    <div 
                      class="stage-content-card-box d-flex align-items-center gap-3 cursor-pointer w-100 justify-content-end"
                      style="max-width: 82%;"
                      @click="goToStage(st.stage_num)"
                    >
                      <!-- Stage Card -->
                      <div class="card p-3.5 p-md-4 rounded-4 border bg-white shadow-xs hover-card flex-grow-1 text-start" style="border-color: #E2E8F0;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span class="fs-9 fw-bold text-danger text-uppercase letter-spacing-05">
                            {{ st.count }} MỤC TỪ • 200 MỤC TỪ/CHỦ ĐỀ
                          </span>
                          <span v-if="st.is_completed" class="badge bg-success-subtle text-success fs-9 fw-bold px-2 py-0.5 rounded-pill">
                            <i class="bi bi-check-circle-fill me-1"></i>Đạt {{ st.score }}/{{ st.count }} câu
                          </span>
                        </div>
                        <h5 class="fw-black text-dark mb-1">Bài từ vựng {{ String(st.stage_num).padStart(2, '0') }}</h5>
                        <p class="text-muted fs-7 mb-2 leading-relaxed">
                          Học bằng thẻ lật, sau đó kiểm tra mức độ ghi nhớ.
                        </p>
                        <div class="d-flex align-items-center justify-content-between">
                          <span class="fs-8 fw-bold" :class="st.is_completed ? 'text-success' : (isStageUnlocked(st.stage_num) ? 'text-teal' : 'text-muted')">
                            {{ getStageActionText(st) }}
                          </span>
                          <i class="bi bi-arrow-right fs-7" :class="isStageUnlocked(st.stage_num) ? 'text-teal' : 'text-muted'"></i>
                        </div>
                      </div>

                      <!-- Circle Node (Attached Right) -->
                      <div 
                        class="stage-circle-node rounded-circle d-flex flex-column align-items-center justify-content-center flex-shrink-0 bg-white"
                        :class="getNodeClass(st)"
                        style="width: 68px; height: 68px;"
                      >
                        <i v-if="st.is_completed" class="bi bi-check-lg fs-4 text-success"></i>
                        <span v-else class="fs-5 fw-black leading-none" :class="isStageUnlocked(st.stage_num) ? 'text-dark' : 'text-muted'">
                          {{ String(st.stage_num).padStart(2, '0') }}
                        </span>
                        <span class="fs-9 fw-bold mt-1 text-uppercase" :class="getNodeLabelClass(st)">
                          {{ getNodeLabel(st) }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- Left Connector Curve (Even to Odd: Curves down on left side) -->
                  <div 
                    v-if="st.stage_num % 2 === 0 && st.stage_num < 10" 
                    class="d-none d-md-flex justify-content-start position-relative connector-curve-left"
                    style="height: 52px; margin-top: -36px; margin-bottom: -18px; padding-left: 6%;"
                  >
                    <svg width="120" height="60" viewBox="0 0 120 60" fill="none">
                      <path 
                        d="M 110 5 H 50 C 15 5, 15 55, 50 55 H 110" 
                        fill="none" 
                        :stroke="st.is_completed ? '#00b074' : '#cbd5e1'" 
                        stroke-width="2.5" 
                        stroke-dasharray="6 6"
                        class="connector-dash-anim"
                      />
                    </svg>
                  </div>
                </div>
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
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const topics = ref([])
const loading = ref(true)
const selectedTopicId = ref(null)
const searchQuery = ref('')

const fetchCurriculumTopics = async () => {
  loading.value = true
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}
    const res = await axios.get('/api/curriculum', { headers })
    if (res.data && res.data.status === 'success') {
      topics.value = res.data.data
      if (topics.value.length > 0 && !selectedTopicId.value) {
        selectedTopicId.value = topics.value[0].id
      }
    }
  } catch (err) {
    console.error('Lỗi tải danh sách lộ trình:', err)
  } finally {
    loading.value = false
  }
}

const filteredTopics = computed(() => {
  if (!searchQuery.value.trim()) return topics.value
  const q = searchQuery.value.toLowerCase().trim()
  return topics.value.filter(t => 
    t.title.toLowerCase().includes(q) || 
    (t.title_vi && t.title_vi.toLowerCase().includes(q))
  )
})

const currentTopic = computed(() => {
  return topics.value.find(t => t.id === selectedTopicId.value) || topics.value[0] || null
})

// Aesthetic pastel palette matching Image 1
const pastelPalettes = [
  { bg: '#d1fae5', color: '#065f46' }, // 01 mint
  { bg: '#fee2e2', color: '#991b1b' }, // 02 soft red
  { bg: '#fef3c7', color: '#92400e' }, // 03 amber
  { bg: '#e0f2fe', color: '#075985' }, // 04 sky
  { bg: '#dcfce7', color: '#166534' }, // 05 emerald
  { bg: '#ffedd5', color: '#9a3412' }, // 06 orange
  { bg: '#fef9c3', color: '#854d0e' }, // 07 yellow
  { bg: '#ede9fe', color: '#5b21b6' }, // 08 purple
  { bg: '#fce7f3', color: '#9d174d' }, // 09 pink
  { bg: '#ccfbf1', color: '#115e59' }, // 10 cyan
]

const getPillBadgeStyle = (num) => {
  const p = pastelPalettes[(num - 1) % pastelPalettes.length]
  return {
    backgroundColor: p.bg,
    color: p.color,
    minWidth: '38px',
    height: '32px',
    borderRadius: '8px',
    fontSize: '12px'
  }
}

const isStageUnlocked = (stageNum) => {
  if (stageNum === 1) return true
  const prevStage = currentTopic.value?.stages?.find(s => s.stage_num === stageNum - 1)
  return prevStage ? (prevStage.is_completed || prevStage.score > 0) : false
}

const getNodeClass = (st) => {
  if (st.is_completed) return 'border-2 border-success text-success shadow-xs'
  if (isStageUnlocked(st.stage_num)) return 'border-2 border-teal text-teal shadow-sm active-node-pulse'
  return 'border-2 border-slate-300 text-muted opacity-75'
}

const getNodeLabel = (st) => {
  if (st.is_completed) return 'ĐẠT'
  if (st.stage_num === 1) return 'HỌC'
  if (isStageUnlocked(st.stage_num)) return 'LÀM BÀI'
  return 'KHÓA'
}

const getNodeLabelClass = (st) => {
  if (st.is_completed) return 'text-success'
  if (isStageUnlocked(st.stage_num)) return 'text-teal'
  return 'text-muted'
}

const getStageActionText = (st) => {
  if (st.is_completed) return `Đã hoàn thành ${st.score}/${st.count} câu • Luyện tập lại →`
  if (st.stage_num === 1) return 'Xem thông tin bài học →'
  if (isStageUnlocked(st.stage_num)) return 'Bắt đầu làm bài kiểm tra →'
  return 'Hoàn thành chặng trước để mở'
}

const goToStage = (stageNum) => {
  if (!currentTopic.value) return
  router.push(`/curriculum/${currentTopic.value.id}/stage/${stageNum}`)
}

onMounted(() => {
  fetchCurriculumTopics()
})
</script>

<style scoped>
.active-topic-card {
  border: 2px solid #00b074 !important;
  background-color: #ffffff !important;
  box-shadow: 0 4px 14px rgba(0, 176, 116, 0.16) !important;
}

.topic-item-card {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.topic-item-card:hover {
  transform: translateY(-1.5px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.stage-circle-node {
  border-width: 2px !important;
  border-style: solid !important;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stage-content-card-box:hover .stage-circle-node {
  transform: scale(1.08);
}

.stage-content-card-box:hover .card {
  border-color: #00b074 !important;
  box-shadow: 0 10px 24px rgba(0, 176, 116, 0.1) !important;
  transform: translateY(-2px);
}

.connector-dash-anim {
  animation: flowDash 1.2s linear infinite;
}

@keyframes flowDash {
  to {
    stroke-dashoffset: -24;
  }
}

@keyframes nodePulseRing {
  0% { box-shadow: 0 0 0 0 rgba(0, 176, 116, 0.45); }
  70% { box-shadow: 0 0 0 10px rgba(0, 176, 116, 0); }
  100% { box-shadow: 0 0 0 0 rgba(0, 176, 116, 0); }
}

.active-node-pulse {
  animation: nodePulseRing 2s infinite;
}

.letter-spacing-1 {
  letter-spacing: 1px;
}

.letter-spacing-05 {
  letter-spacing: 0.5px;
}

.shadow-xs {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
}
</style>
