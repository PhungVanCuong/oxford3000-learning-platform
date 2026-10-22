<template>
  <div class="container py-4 max-w-5xl">
    <!-- 1. Header Banner (Dark teal #064e40, white text, exact layout) -->
    <div class="rounded-4 p-4 p-md-5 mb-4 shadow-sm text-white position-relative overflow-hidden" style="background-color: #064e40;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 position-relative z-1">
        <!-- Avatar and Name info -->
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-4 overflow-hidden border border-2 border-white shadow-sm shrink-0" style="width: 72px; height: 72px; background-color: #0d7362;">
            <img
              :src="authStore.user?.avatar_url || 'https://api.dicebear.com/7.x/bottts/svg?seed=CuongPhung'"
              alt="Avatar"
              class="w-100 h-100 object-fit-cover"
            />
          </div>
          <div>
            <div class="text-uppercase fw-bold fs-8 tracking-wider mb-1" style="color: #5eead4;">
              TRANG CÁ NHÂN
            </div>
            <h2 class="fw-black mb-0 fs-3">{{ authStore.userName }}</h2>
            <div class="text-white-50 fs-7 mt-0.5">{{ authStore.userEmail }}</div>
            <span class="badge mt-1" :class="authStore.isAdmin ? 'bg-danger text-white' : 'bg-success text-white'" style="font-size: 11px;">
              {{ authStore.isAdmin ? 'Quản trị viên (Admin)' : 'Học viên' }}
            </span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center gap-2">
          <router-link
            to="/daily"
            class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-1.5"
            style="color: #064e40;"
          >
            <span>Tiếp tục học</span>
            <i class="bi bi-arrow-right"></i>
          </router-link>

          <button
            @click="handleLogout"
            class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold fs-7"
          >
            Đăng xuất
          </button>
        </div>
      </div>
    </div>

    <!-- 2. Four Stat Cards Row -->
    <div class="row g-3 mb-4">
      <!-- Stat 1: XP đã tích luỹ -->
      <div class="col-6 col-lg-3">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">XP đã tích luỹ</div>
          <div class="display-6 fw-black text-teal">{{ authStore.user?.xp_points ?? 0 }}</div>
        </div>
      </div>

      <!-- Stat 2: Bài đã hoàn thành -->
      <div class="col-6 col-lg-3">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Bài đã hoàn thành</div>
          <div class="display-6 fw-black text-teal">{{ authStore.stats?.total_quizzes ?? 0 }}</div>
        </div>
      </div>

      <!-- Stat 3: Độ chính xác -->
      <div class="col-6 col-lg-3">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Độ chính xác</div>
          <div class="display-6 fw-black text-teal">{{ authStore.stats?.avg_accuracy ? `${authStore.stats.avg_accuracy}%` : '0%' }}</div>
        </div>
      </div>

      <!-- Stat 4: Chuỗi học -->
      <div class="col-6 col-lg-3">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Chuỗi học</div>
          <div class="display-6 fw-black text-teal">{{ authStore.user?.streak_days ?? 1 }} ngày</div>
        </div>
      </div>
    </div>

    <!-- 3. Two Columns: 01 THÔNG TIN CÁ NHÂN & 02 TIẾN ĐỘ HỌC TẬP -->
    <div class="row g-4 mb-4">
      <!-- Cột trái: Card 01 THÔNG TIN CÁ NHÂN -->
      <div class="col-12 col-lg-6">
        <div class="card p-4 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-teal-dark text-white rounded-pill px-2.5 py-1 fs-8">01</span>
              <h5 class="fw-bold mb-0 text-teal">THÔNG TIN CÁ NHÂN & CẤU HÌNH</h5>
            </div>
            <div class="text-muted fs-7 mb-3">Thông tin được đồng bộ với tài khoản học tập của bạn.</div>

            <!-- Form -->
            <form @submit.prevent="saveProfile">
              <!-- Họ và tên -->
              <div class="mb-3">
                <label class="form-label fs-7 fw-bold mb-1">Họ và tên</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control rounded-3 py-2 fs-7"
                  placeholder="Nhập họ và tên..."
                  required
                />
              </div>

              <!-- Avatar Selector -->
              <div class="mb-3">
                <label class="form-label fs-7 fw-bold mb-1">Ảnh đại diện (Avatar)</label>
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                  <div
                    v-for="ava in presetAvatars"
                    :key="ava"
                    @click="form.avatar_url = ava"
                    class="rounded-circle p-1 cursor-pointer border transition-all"
                    :class="form.avatar_url === ava ? 'border-success border-2 shadow-sm bg-success-subtle' : 'border-light-subtle'"
                    style="width: 44px; height: 44px; cursor: pointer;"
                  >
                    <img :src="ava" class="w-100 h-100 rounded-circle object-fit-cover" />
                  </div>
                </div>
                <input
                  v-model="form.avatar_url"
                  type="text"
                  class="form-control form-control-sm rounded-3 fs-8"
                  placeholder="Hoặc dán URL ảnh đại diện..."
                />
              </div>

              <!-- Ngày sinh -->
              <div class="mb-3">
                <label class="form-label fs-7 fw-bold mb-1">Ngày sinh</label>
                <input
                  v-model="form.birth_date"
                  type="date"
                  class="form-control rounded-3 py-2 fs-7"
                />
                <div class="form-text fs-8 text-muted">Nhập theo dạng ngày/tháng/năm (VD: 18/10/2005).</div>
              </div>

              <!-- Số từ vựng học mỗi ngày -->
              <div class="mb-4">
                <label class="form-label fs-7 fw-bold mb-1">Số từ vựng học mỗi ngày</label>
                <select v-model="form.words_per_day" class="form-select rounded-3 py-2 fs-7">
                  <option :value="10">10 từ/ngày (khoảng 300 ngày)</option>
                  <option :value="15">15 từ/ngày (khoảng 200 ngày - Khuyên dùng)</option>
                  <option :value="20">20 từ/ngày (khoảng 150 ngày)</option>
                  <option :value="25">25 từ/ngày (khoảng 120 ngày)</option>
                  <option :value="30">30 từ/ngày (khoảng 100 ngày)</option>
                  <option :value="50">50 từ/ngày (khoảng 60 ngày - Cấp tốc)</option>
                </select>
                <div class="form-text fs-8 text-muted">Hệ thống sẽ tự động tính lại số ngày và phân chia bộ từ vựng tương ứng mà không mất tiến độ từ đã thuộc.</div>
              </div>

              <div class="d-flex justify-content-end">
                <button
                  type="submit"
                  class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm"
                  style="background-color: #064e40;"
                  :disabled="isSaving"
                >
                  <span v-if="isSaving" class="spinner-border spinner-border-sm me-1"></span>
                  Lưu thay đổi
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Cột phải: Card 02 TIẾN ĐỘ HỌC TẬP -->
      <div class="col-12 col-lg-6">
        <div class="card p-4 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-teal-dark text-white rounded-pill px-2.5 py-1 fs-8">02</span>
              <h5 class="fw-bold mb-0 text-teal">TIẾN ĐỘ HỌC TẬP</h5>
            </div>
            <div class="text-muted fs-7 mb-4">Các kết quả đã lưu trên toàn bộ lộ trình.</div>

            <!-- Progress 1: Lộ trình 25 Chủ đề IELTS (5.000 từ) -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-1 fs-7">
                <span class="fw-bold d-flex align-items-center gap-1.5">
                  <i class="bi bi-signpost-split-fill text-teal"></i>
                  <span>Lộ trình 25 Chủ đề IELTS (5.000 từ)</span>
                </span>
                <div class="d-flex gap-2">
                  <span class="text-muted">{{ curriculumRememberedCount }}/5000 từ</span>
                  <span class="fw-bold text-teal">{{ formatFloatPercent(curriculumPercent) }}%</span>
                </div>
              </div>
              <div class="progress" style="height: 10px; border-radius: 999px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: `${Math.min(100, curriculumPercent)}%`, backgroundColor: '#00b074' }"
                ></div>
              </div>
            </div>

            <!-- Progress 2: Từ vựng Oxford 3000 -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-1 fs-7">
                <span class="fw-bold d-flex align-items-center gap-1.5">
                  <i class="bi bi-journal-bookmark-fill text-success"></i>
                  <span>Kho Từ vựng Oxford 3000™</span>
                </span>
                <div class="d-flex gap-2">
                  <span class="text-muted">{{ authStore.stats?.mastered_words || 0 }}/3351 từ</span>
                  <span class="fw-bold text-teal">{{ formatFloatPercent(vocabPercent) }}%</span>
                </div>
              </div>
              <div class="progress" style="height: 10px; border-radius: 999px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: `${Math.min(100, vocabPercent)}%`, backgroundColor: '#10b981' }"
                ></div>
              </div>
            </div>

            <!-- Progress 3: Ngữ pháp -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-1 fs-7">
                <span class="fw-bold d-flex align-items-center gap-1.5">
                  <i class="bi bi-book-half text-primary"></i>
                  <span>Ngữ pháp (12 thì & chuyên đề)</span>
                </span>
                <div class="d-flex gap-2">
                  <span class="text-muted">{{ authStore.stats?.completed_grammars || 0 }}/15 bài</span>
                  <span class="fw-bold text-teal">{{ formatFloatPercent(grammarPercent) }}%</span>
                </div>
              </div>
              <div class="progress" style="height: 10px; border-radius: 999px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: `${Math.min(100, grammarPercent)}%`, backgroundColor: '#0d7362' }"
                ></div>
              </div>
            </div>
          </div>

          <!-- Khung trạng thái giai đoạn cây dưới cùng (nền xanh nhạt) -->
          <div class="p-3 rounded-4 mt-3 d-flex justify-content-between align-items-center" style="background-color: rgba(13, 115, 98, 0.1); border: 1px solid rgba(13, 115, 98, 0.2);">
            <div class="d-flex align-items-center gap-2">
              <span class="fs-4">🌱</span>
              <div>
                <div class="fs-8 fw-semibold text-muted text-uppercase">Cây tri thức hiện tại</div>
                <div class="fw-bold text-teal">{{ plantStage }}</div>
              </div>
            </div>
            <div class="badge bg-teal text-white px-3 py-2 rounded-pill fs-7 fw-bold">
              {{ formatFloatPercent(vocabPercent) }}% từ đã nhớ
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. TIẾN ĐỘ CHI TIẾT 25 CHỦ ĐỀ LỘ TRÌNH IELTS (5.000 TỪ) -->
    <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4" style="border-color: #E2E8F0;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-teal-dark text-white rounded-pill px-2.5 py-1 fs-8">03</span>
            <h5 class="fw-bold mb-0 text-teal">TIẾN ĐỘ 25 CHỦ ĐỀ LỘ TRÌNH IELTS (5.000 TỪ)</h5>
          </div>
          <div class="text-muted fs-7">Chi tiết từng chủ đề, số bài hoàn thành và tỷ lệ ghi nhớ được đồng bộ tự động.</div>
        </div>
        <router-link to="/curriculum" class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8 d-flex align-items-center gap-1.5 shadow-xs">
          <i class="bi bi-signpost-split-fill"></i>
          <span>Vào học Lộ Trình →</span>
        </router-link>
      </div>

      <div class="row g-2.5">
        <div v-for="t in curriculumTopics" :key="t.id" class="col-12 col-md-6 col-lg-4">
          <router-link :to="`/curriculum`" class="card p-3 rounded-3 border text-decoration-none hover-card h-100 bg-white" style="border-color: #E2E8F0;">
            <div class="d-flex justify-content-between align-items-start mb-1.5">
              <span class="badge rounded-pill text-teal fw-bold bg-teal-subtle px-2 py-0.5" style="font-size: 10px;">
                CHỦ ĐỀ {{ String(t.order_num).padStart(2, '0') }}
              </span>
              <span 
                class="badge rounded-pill fw-bold" 
                :class="t.progress_percent >= 100 ? 'bg-success text-white' : (t.progress_percent > 0 ? 'bg-teal text-white' : 'bg-light text-muted border')" 
                style="font-size: 10px;"
              >
                {{ t.progress_percent }}%
              </span>
            </div>
            <div class="fw-bold fs-7 text-dark text-truncate">{{ t.title }}</div>
            <div class="text-muted fs-8 mb-2 text-truncate">{{ t.title_vi }}</div>
            <div class="d-flex justify-content-between align-items-center fs-8 text-muted mb-1">
              <span>{{ t.remembered_terms_count || 0 }}/200 từ đã nhớ</span>
              <span class="text-teal fw-semibold">{{ t.completed_stages_count || 0 }}/10 bài</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 999px;">
              <div 
                class="progress-bar rounded-pill" 
                :class="t.progress_percent >= 100 ? 'bg-success' : 'bg-teal'" 
                :style="{ width: `${t.progress_percent}%` }"
              ></div>
            </div>
          </router-link>
        </div>
      </div>
    </div>

    <!-- 4. Danger Zone: Reset Learning Progress Card -->
    <div class="card p-4 rounded-4 shadow-sm border border-danger-subtle bg-danger-subtle bg-opacity-25">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h6 class="fw-bold text-danger mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i> Thiết Lập Lại Quá Trình Học (Reset Progress)
          </h6>
          <p class="text-muted fs-7 mb-0">
            Nếu bạn muốn bắt đầu lại từ vựng và ngữ pháp từ đầu, tính năng này sẽ xóa sạch lịch sử làm bài kiểm tra và đưa số từ đã nhớ về 0.
          </p>
        </div>
        <button
          @click="showResetConfirm = true"
          class="btn btn-outline-danger btn-sm px-4 py-2 rounded-pill fw-bold text-nowrap shadow-sm"
        >
          <i class="bi bi-trash3 me-1"></i> Xóa & Làm lại từ đầu
        </button>
      </div>
    </div>

    <!-- Reset Confirmation Centered Modal -->
    <div
      v-if="showResetConfirm"
      class="modal fade show d-block"
      style="background: rgba(0, 0, 0, 0.65);"
      tabindex="-1"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-card-custom">
          <div class="modal-header border-bottom py-3">
            <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
              <i class="bi bi-exclamation-triangle-fill"></i> Xác nhận Reset Tiến Độ
            </h5>
            <button @click="showResetConfirm = false" type="button" class="btn-close" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <p class="fs-7 text-dark-emphasis mb-2">
              Bạn có chắc chắn muốn xóa toàn bộ tiến độ học từ vựng, ngữ pháp và lịch sử kiểm tra của tài khoản <strong>{{ authStore.userName }}</strong> không?
            </p>
            <div class="alert alert-warning py-2 px-3 fs-8 mb-0 rounded-3">
              <i class="bi bi-info-circle me-1"></i>Hành động này không thể hoàn tác. Điểm kinh nghiệm (XP) sẽ trở về 0 và chuỗi ngày học sẽ được đặt lại.
            </div>
          </div>
          <div class="modal-footer border-top py-2">
            <button @click="showResetConfirm = false" class="btn btn-secondary btn-sm px-3 rounded-pill">
              Hủy bỏ
            </button>
            <button
              @click="executeResetProgress"
              class="btn btn-danger btn-sm px-4 rounded-pill"
              :disabled="isResetting"
            >
              <span v-if="isResetting" class="spinner-border spinner-border-sm me-1"></span>
              Xác nhận Xóa Hết
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import axios from 'axios'

const authStore = useAuthStore()
const router = useRouter()

const isSaving = ref(false)
const showResetConfirm = ref(false)
const isResetting = ref(false)

const presetAvatars = [
  'https://api.dicebear.com/7.x/bottts/svg?seed=CuongPhung',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Felix',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Aneka',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Bella',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Max',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Oliver',
]

const form = ref({
  name: '',
  avatar_url: '',
  birth_date: '',
  words_per_day: 15,
})

const curriculumTopics = ref([])

const curriculumRememberedCount = computed(() => {
  return authStore.stats?.curriculum_remembered_terms || 
    curriculumTopics.value.reduce((acc, t) => acc + (t.remembered_terms_count || 0), 0)
})

const formatFloatPercent = (val) => {
  if (val === null || val === undefined || isNaN(val)) return '0.00'
  const num = Number(val)
  if (num <= 0) return '0.00'
  if (num >= 100) return '100.00'
  return num.toFixed(2)
}

const curriculumPercent = computed(() => {
  if (authStore.stats?.curriculum_percent !== undefined) {
    return Number(authStore.stats.curriculum_percent)
  }
  return Math.min(100, ((curriculumRememberedCount.value || 0) / 5000) * 100)
})

const grammarPercent = computed(() => {
  const comp = authStore.stats?.completed_grammars || 0
  return Math.min(100, (comp / 15) * 100)
})

const vocabPercent = computed(() => {
  const comp = authStore.stats?.mastered_words || 0
  return Math.min(100, (comp / 3351) * 100)
})

const plantStage = computed(() => {
  const xp = authStore.user?.xp_points || 0
  if (xp < 150) return 'Hạt giống ươm mầm'
  if (xp < 400) return 'Mầm non nảy chồi'
  if (xp < 800) return 'Cây con phát triển'
  if (xp < 1500) return 'Thân cây vươn cành'
  if (xp < 2500) return 'Tán lá xanh mướt'
  return 'Đơm hoa kết trái (Master)'
})

const saveProfile = async () => {
  isSaving.value = true
  await authStore.updateProfile(form.value)
  isSaving.value = false
  alert('Đã cập nhật thông tin thành công!')
}

const executeResetProgress = async () => {
  isResetting.value = true
  try {
    const res = await axios.post('/api/auth/reset-progress')
    await authStore.fetchMe()
    showResetConfirm.value = false
    alert(res.data.message || 'Đã đặt lại tiến độ học thành công!')
  } catch (err) {
    alert('Lỗi đặt lại tiến độ: ' + (err.response?.data?.message || err.message))
  } finally {
    isResetting.value = false
  }
}

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}

onMounted(async () => {
  await authStore.fetchMe()
  if (authStore.user) {
    form.value.name = authStore.user.name || 'Cường Phùng'
    form.value.avatar_url = authStore.user.avatar_url || presetAvatars[0]
    form.value.birth_date = authStore.user.birth_date ? authStore.user.birth_date.substring(0, 10) : '2005-10-18'
    form.value.words_per_day = authStore.user.words_per_day || 15
  }

  // Load Curriculum 25 Topics Progress
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}
    const cRes = await axios.get('/api/curriculum', { headers })
    if (cRes.data?.data) {
      curriculumTopics.value = cRes.data.data
    }
  } catch (e) {
    console.error('Lỗi tải tiến độ lộ trình:', e)
  }
})
</script>
