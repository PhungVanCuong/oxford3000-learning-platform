<template>
  <div class="admin-arena-view pb-5">
    <!-- Header -->
    <div class="card p-4 rounded-4 border shadow-sm bg-card-custom mb-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1.5">
            <span class="badge px-3 py-1 rounded-pill fw-bold fs-8" style="background-color: #E6F7F3; color: #087F75;">
              ● TRUNG TÂM ĐẤU TRƯỜNG & BẢNG XẾP HẠNG LIÊN SERVER
            </span>
            <span class="badge bg-warning-subtle text-warning-emphasis border rounded-pill px-2.5 py-1 fs-8">
              Mùa 1 - 2026: Chiến Thần Tri Thức
            </span>
          </div>
          <h2 class="fw-black text-dark mb-1">Quản Trị Đấu Trường & Bảng Xếp Hạng ⚔️🏆</h2>
          <p class="text-muted fs-7 mb-0">
            Theo dõi bảng xếp hạng liên server toàn bộ học viên trong hệ thống, quản lý phòng thi đấu trực tuyến, giám sát điểm số và chuỗi học tập.
          </p>
        </div>

        <div class="d-flex gap-2">
          <router-link to="/arena" class="btn btn-outline-teal rounded-pill px-3.5 py-2 fw-bold fs-7 shadow-xs">
            <i class="bi bi-box-arrow-up-right me-1"></i> Giao diện học viên
          </router-link>
          <button @click="refreshCurrentTab" class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-7 shadow-sm">
            <i class="bi bi-arrow-clockwise me-1"></i> Làm mới
          </button>
        </div>
      </div>
    </div>

    <!-- Main Navigation Tabs: Leaderboard vs Rooms (Kéo chuột trái lướt ngang) -->
    <ul v-drag-scroll class="nav nav-pills gap-2 mb-4 border-bottom pb-2 fs-7 fw-bold flex-nowrap overflow-x-auto no-scrollbar drag-scroll-x" role="tablist">
      <li class="nav-item">
        <button
          class="nav-link rounded-pill px-3 px-md-4 py-2 d-flex align-items-center gap-1.5 gap-md-2 text-nowrap"
          :class="activeMainTab === 'leaderboard' ? 'active bg-teal text-white shadow-sm' : 'text-secondary bg-body-tertiary'"
          @click="switchMainTab('leaderboard')"
        >
          <i class="bi bi-trophy-fill text-warning"></i>
          <span>Bảng Xếp Hạng Toàn Hệ Thống ({{ totalLearners }})</span>
        </button>
      </li>
      <li class="nav-item">
        <button
          class="nav-link rounded-pill px-3 px-md-4 py-2 d-flex align-items-center gap-1.5 gap-md-2 text-nowrap"
          :class="activeMainTab === 'rooms' ? 'active bg-teal text-white shadow-sm' : 'text-secondary bg-body-tertiary'"
          @click="switchMainTab('rooms')"
        >
          <i class="bi bi-controller"></i>
          <span>Quản Lý Phòng Thi Đấu ({{ rooms.length }})</span>
        </button>
      </li>
    </ul>

    <!-- ================= TAB 1: BẢNG XẾP HẠNG TOÀN HỆ THỐNG ================= -->
    <div v-if="activeMainTab === 'leaderboard'">
      <!-- Stats Overview Cards -->
      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="card p-3 rounded-4 border shadow-2xs bg-card-custom h-100">
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="rounded-circle bg-primary-subtle text-primary p-2 fs-7"><i class="bi bi-people-fill"></i></span>
              <span class="fs-8 text-muted fw-bold text-uppercase">Tổng Người Học</span>
            </div>
            <div class="display-6 fw-black text-dark-emphasis">{{ totalLearners }}</div>
            <div class="fs-8 text-success mt-1"><i class="bi bi-check-circle me-1"></i>Đồng bộ trực tiếp DB</div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="card p-3 rounded-4 border shadow-2xs bg-card-custom h-100">
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="rounded-circle bg-warning-subtle text-warning p-2 fs-7"><i class="bi bi-stars"></i></span>
              <span class="fs-8 text-muted fw-bold text-uppercase">Tổng XP Hệ Thống</span>
            </div>
            <div class="display-6 fw-black text-warning">{{ totalSystemXp.toLocaleString() }}</div>
            <div class="fs-8 text-muted mt-1">Toàn bộ cụm Global VN-01</div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="card p-3 rounded-4 border shadow-2xs bg-card-custom h-100">
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="rounded-circle bg-danger-subtle text-danger p-2 fs-7"><i class="bi bi-gem"></i></span>
              <span class="fs-8 text-muted fw-bold text-uppercase">Bậc Cao Thủ / Kim Cương</span>
            </div>
            <div class="display-6 fw-black text-danger">{{ (tierCounts.grandmaster || 0) + (tierCounts.diamond || 0) }}</div>
            <div class="fs-8 text-muted mt-1">Học viên dẫn đầu đỉnh cao</div>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <div class="card p-3 rounded-4 border shadow-2xs bg-card-custom h-100">
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="rounded-circle bg-success-subtle text-success p-2 fs-7"><i class="bi bi-award-fill"></i></span>
              <span class="fs-8 text-muted fw-bold text-uppercase">Quán Quân Hiện Tại</span>
            </div>
            <div class="fw-black text-dark text-truncate fs-6">{{ leaderboardList[0]?.name || 'Đang cập nhật' }}</div>
            <div class="fs-8 text-teal fw-bold mt-1">{{ leaderboardList[0]?.xp_points?.toLocaleString() || 0 }} XP • {{ leaderboardList[0]?.streak_days || 0 }} ngày 🔥</div>
          </div>
        </div>
      </div>

      <!-- Controls & Filters Bar -->
      <div class="card p-3 rounded-4 border shadow-sm bg-card-custom mb-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-stretch align-items-lg-center gap-3">
          <!-- Metric Sorting Tabs -->
          <div class="d-flex flex-wrap gap-1.5">
            <button
              v-for="ft in leaderboardFilters"
              :key="ft.type"
              @click="setLeaderboardType(ft.type)"
              class="btn btn-sm rounded-pill px-3 py-1.5 fs-8 fw-bold"
              :class="leaderboardType === ft.type ? 'btn-teal text-white shadow-xs' : 'btn-outline-secondary'"
            >
              <i :class="ft.icon" class="me-1"></i>
              {{ ft.label }}
            </button>
          </div>

          <!-- Search & Tier Filter -->
          <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Search -->
            <div class="input-group input-group-sm" style="max-width: 260px;">
              <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
              <input
                type="text"
                v-model="searchQuery"
                @keyup.enter="loadLeaderboard"
                class="form-control border-start-0 fs-8"
                placeholder="Tìm tên, email học viên..."
              />
              <button @click="loadLeaderboard" class="btn btn-outline-secondary fs-8">Tìm</button>
            </div>

            <!-- Tier Select -->
            <select v-model="selectedTier" @change="loadLeaderboard" class="form-select form-select-sm fs-8 rounded-pill" style="width: auto;">
              <option value="all">Tất cả cấp bậc</option>
              <option value="grandmaster">💎 Đại Cao Thủ (≥5000 XP)</option>
              <option value="diamond">💠 Kim Cương (3000-4999 XP)</option>
              <option value="platinum">🏅 Bạch Kim (1500-2999 XP)</option>
              <option value="gold">🌟 Vàng (800-1499 XP)</option>
              <option value="silver">🥈 Bạc (300-799 XP)</option>
              <option value="bronze">🥉 Đồng (<300 XP)</option>
            </select>

            <!-- Show All / Per Page Toggle -->
            <button
              @click="toggleShowAll"
              class="btn btn-sm rounded-pill px-3 py-1 fs-8 fw-semibold"
              :class="viewAll ? 'btn-warning text-dark' : 'btn-outline-secondary'"
            >
              {{ viewAll ? 'Hiện phân trang (50)' : 'Hiện tất cả học viên' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Leaderboard Table -->
      <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden">
        <div v-if="isLoadingLeaderboard" class="text-center py-5">
          <div class="spinner-border text-teal" role="status"></div>
          <div class="text-muted fs-7 mt-2">Đang tải bảng xếp hạng toàn hệ thống...</div>
        </div>

        <div v-else-if="leaderboardList.length" class="table-responsive">
          <table class="table table-hover align-middle mb-0 fs-7">
            <thead class="table-light fs-8 text-uppercase text-muted">
              <tr>
                <th style="width: 70px;" class="ps-3 text-center">Hạng</th>
                <th style="width: 50px;" class="text-center">Biến động</th>
                <th>Học viên</th>
                <th class="text-center">Cấp bậc</th>
                <th class="text-center">Chuỗi ngày 🔥</th>
                <th class="text-center">Từ nắm vững 📚</th>
                <th class="text-center">Đấu trường ⚔️</th>
                <th class="text-center">Cụm Server</th>
                <th class="text-end pe-4">Tổng XP 🌟</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="u in leaderboardList" :key="u.user_id" :class="u.is_current_user ? 'table-teal-highlight' : ''">
                <!-- Rank -->
                <td class="ps-3 text-center fw-black">
                  <span v-if="u.rank === 1" class="badge bg-warning text-dark fs-7 rounded-pill px-2.5 py-1 shadow-2xs">🥇 1</span>
                  <span v-else-if="u.rank === 2" class="badge bg-secondary text-white fs-7 rounded-pill px-2.5 py-1 shadow-2xs">🥈 2</span>
                  <span v-else-if="u.rank === 3" class="badge bg-danger-subtle text-danger fs-7 rounded-pill px-2.5 py-1 shadow-2xs">🥉 3</span>
                  <span v-else class="text-muted fw-bold">#{{ u.rank }}</span>
                </td>

                <!-- Trend -->
                <td class="text-center">
                  <span v-if="u.trend > 0" class="badge bg-success-subtle text-success fs-8 rounded-pill px-1.5 py-0.5" title="Thăng hạng">
                    ▲ +{{ u.trend }}
                  </span>
                  <span v-else-if="u.trend < 0" class="badge bg-danger-subtle text-danger fs-8 rounded-pill px-1.5 py-0.5" title="Tụt hạng">
                    ▼ {{ u.trend }}
                  </span>
                  <span v-else class="text-muted fs-8 font-monospace">—</span>
                </td>

                <!-- User Info -->
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <img :src="u.avatar_url" class="rounded-circle border" style="width: 36px; height: 36px; object-fit: cover;" />
                    <div>
                      <div class="fw-bold text-dark-emphasis d-flex align-items-center gap-1.5">
                        <span>{{ u.name }}</span>
                        <span v-if="u.role === 'admin'" class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 9px;">Admin</span>
                        <span v-else-if="u.role === 'tutor'" class="badge bg-info-subtle text-info rounded-pill" style="font-size: 9px;">Trợ giảng</span>
                        <span v-else-if="u.role === 'moderator'" class="badge bg-warning-subtle text-dark rounded-pill" style="font-size: 9px;">Mod</span>
                      </div>
                      <div class="text-muted fs-8">{{ u.email }}</div>
                    </div>
                  </div>
                </td>

                <!-- Tier -->
                <td class="text-center">
                  <span class="badge rounded-pill fs-8" :class="u.tier_class">
                    {{ u.tier_badge }} {{ u.tier_name }}
                  </span>
                </td>

                <!-- Streak -->
                <td class="text-center">
                  <span class="fw-bold text-danger">
                    <i class="bi bi-fire"></i> {{ u.streak_days }} ngày
                  </span>
                </td>

                <!-- Mastered Words -->
                <td class="text-center fw-semibold text-secondary">
                  {{ u.mastered_words }} từ
                </td>

                <!-- Arena -->
                <td class="text-center">
                  <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2.5 py-1 fs-8">
                    {{ u.arena_score }} XP ({{ u.arena_matches }} trận)
                  </span>
                </td>

                <!-- Server Cluster -->
                <td class="text-center">
                  <span class="badge bg-secondary-subtle text-secondary rounded-pill fs-8">
                    {{ u.server_cluster || 'Global VN-01' }}
                  </span>
                </td>

                <!-- Total XP -->
                <td class="text-end pe-4 fw-black text-warning fs-6">
                  {{ u.xp_points.toLocaleString() }} XP
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="text-center py-5">
          <i class="bi bi-inbox display-5 text-muted mb-2"></i>
          <div class="fw-bold text-muted fs-6">Không tìm thấy học viên nào phù hợp bộ lọc.</div>
        </div>

        <!-- Smart 50-Item Pagination -->
        <SmartPagination
          v-if="!viewAll && filteredCount > 0"
          v-model:currentPage="currentPage"
          v-model:pageSize="perPage"
          :totalItems="filteredCount"
          :pageSizeOptions="[25, 50, 100, 200]"
          @change="loadLeaderboard"
        />
      </div>
    </div>

    <!-- ================= TAB 2: QUẢN LÝ PHÒNG THI ĐẤU ================= -->
    <div v-else-if="activeMainTab === 'rooms'">
      <!-- Filter Pills -->
      <div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
        <span class="fs-8 fw-bold text-muted text-uppercase me-2">Bộ lọc trạng thái:</span>
        <button
          v-for="st in statusFilters"
          :key="st.key"
          @click="activeStatus = st.key"
          class="btn btn-sm rounded-pill px-3 py-1.5 fs-8 fw-bold"
          :class="activeStatus === st.key ? 'btn-teal text-white shadow-xs' : 'btn-outline-secondary'"
        >
          {{ st.label }} ({{ getStatusCount(st.key) }})
        </button>
      </div>

      <!-- Table of Rooms -->
      <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden">
        <div v-if="isLoadingRooms" class="text-center py-5">
          <div class="spinner-border text-teal" role="status"></div>
          <div class="text-muted fs-7 mt-2">Đang tải danh sách phòng đấu trường...</div>
        </div>

        <div v-else-if="filteredRooms.length" class="table-responsive">
          <table class="table table-hover align-middle mb-0 fs-7">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Mã phòng</th>
                <th>Tiêu đề & Chủ đề</th>
                <th>Chủ phòng</th>
                <th>Cấu hình thi</th>
                <th>Số người / Tối đa</th>
                <th>Thời hạn</th>
                <th>Trạng thái</th>
                <th class="text-end pe-4">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="r in filteredRooms" :key="r.id">
                <!-- Room Code & Password -->
                <td class="ps-4">
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold fs-7 font-monospace">
                    {{ r.room_code }}
                  </span>
                  <div v-if="r.has_password" class="fs-8 text-danger mt-1">
                    <i class="bi bi-lock-fill me-1"></i> Có mật khẩu
                  </div>
                </td>

                <!-- Title & Category -->
                <td>
                  <div class="fw-bold text-dark-emphasis">{{ r.title }}</div>
                  <span class="badge bg-secondary-subtle text-secondary rounded-pill fs-8">
                    {{ formatCategory(r.category) }}
                  </span>
                </td>

                <!-- Host -->
                <td>
                  <div class="fw-semibold text-dark">{{ r.host_name }}</div>
                  <div class="text-muted fs-8">{{ r.host_email || 'Hệ thống' }}</div>
                </td>

                <!-- Config -->
                <td>
                  <div class="fs-8 text-dark-emphasis">
                    <strong>{{ r.total_questions }}</strong> câu • <strong>{{ r.time_per_question ? r.time_per_question + 's' : 'Vô hạn' }}</strong>
                  </div>
                  <div class="fs-8 text-warning fw-bold">
                    +{{ r.xp_per_question }} XP/câu
                  </div>
                </td>

                <!-- Participants -->
                <td>
                  <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill fs-8">
                    👥 {{ r.participants_count }} / {{ r.max_players }}
                  </span>
                </td>

                <!-- Lifetime -->
                <td>
                  <span class="fs-8 text-muted">
                    {{ r.expires_at === 'Vĩnh viễn' ? '♾️ Vĩnh viễn' : (r.expires_at || 'Không giới hạn') }}
                  </span>
                </td>

                <!-- Status -->
                <td>
                  <span v-if="r.status === 'waiting'" class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 fs-8">
                    ⏳ Đang chờ
                  </span>
                  <span v-else-if="r.status === 'playing'" class="badge bg-success text-white rounded-pill px-2.5 py-1 fs-8 animate-pulse">
                    ⚡ Đang thi đấu
                  </span>
                  <span v-else class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 fs-8">
                    🏁 Đã kết thúc
                  </span>
                </td>

                <!-- Actions -->
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-1.5">
                    <router-link :to="`/arena/${r.room_code}`" class="btn btn-sm btn-outline-teal rounded-pill px-2.5 py-1 fs-8" title="Vào phòng trực tiếp">
                      <i class="bi bi-box-arrow-up-right"></i>
                    </router-link>

                    <button
                      v-if="r.status !== 'finished'"
                      @click="closeRoom(r)"
                      class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 fs-8 text-dark"
                      title="Đóng phòng thi đấu"
                    >
                      <i class="bi bi-stop-circle-fill"></i>
                    </button>

                    <button
                      @click="deleteRoom(r)"
                      class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fs-8"
                      title="Xóa phòng vĩnh viễn"
                    >
                      <i class="bi bi-trash3-fill"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="text-center py-5">
          <i class="bi bi-inbox display-5 text-muted mb-2"></i>
          <div class="fw-bold text-muted fs-6">Không có phòng nào trong danh mục này.</div>
        </div>
      </div>
    </div>

    <BaseModal
      v-model="confirmModal.show"
      :title="confirmModal.title"
      icon="bi bi-exclamation-triangle-fill"
      maxWidth="460px"
      hideFooter
    >
      <p class="fs-7 mb-3 text-dark-emphasis">{{ confirmModal.message }}</p>
      <div class="d-flex justify-content-end gap-2">
        <button @click="confirmModal.show = false" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Hủy</button>
        <button @click="executeConfirmRoomAction" class="btn btn-sm rounded-pill px-4 fw-bold text-white" :class="confirmModal.danger ? 'btn-danger' : 'btn-warning text-dark'">
          Xác nhận
        </button>
      </div>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import SmartPagination from '@/components/SmartPagination.vue'
import BaseModal from '@/components/BaseModal.vue'
import { useToast } from '@/composables/useToast'

const toast = useToast()
const confirmModal = ref({ show: false, title: '', message: '', danger: false, onConfirm: null })

const activeMainTab = ref('leaderboard')

// Leaderboard States
const isLoadingLeaderboard = ref(true)
const leaderboardList = ref([])
const totalLearners = ref(0)
const totalSystemXp = ref(0)
const filteredCount = ref(0)
const currentPage = ref(1)
const perPage = ref(50)
const lastPage = ref(1)
const viewAll = ref(false)
const searchQuery = ref('')
const selectedTier = ref('all')
const leaderboardType = ref('all_xp')
const tierCounts = ref({})

const leaderboardFilters = [
  { type: 'all_xp', label: '🌟 Tổng Điểm XP', icon: 'bi-stars' },
  { type: 'streak', label: '🔥 Chuỗi Ngày', icon: 'bi-fire' },
  { type: 'arena', label: '⚔️ Đấu Trường', icon: 'bi-controller' },
  { type: 'words', label: '📚 Từ Nắm Vững', icon: 'bi-book-half' }
]

// Room Management States
const isLoadingRooms = ref(true)
const rooms = ref([])
const activeStatus = ref('all')

const statusFilters = [
  { key: 'all', label: 'Tất cả' },
  { key: 'waiting', label: 'Đang chờ' },
  { key: 'playing', label: 'Đang thi' },
  { key: 'finished', label: 'Đã đóng' },
]

const switchMainTab = (tab) => {
  activeMainTab.value = tab
  if (tab === 'leaderboard' && leaderboardList.value.length === 0) {
    loadLeaderboard()
  } else if (tab === 'rooms' && rooms.value.length === 0) {
    loadRooms()
  }
}

const refreshCurrentTab = () => {
  if (activeMainTab.value === 'leaderboard') {
    loadLeaderboard()
  } else {
    loadRooms()
  }
}

const setLeaderboardType = (t) => {
  leaderboardType.value = t
  currentPage.value = 1
  loadLeaderboard()
}

const toggleShowAll = () => {
  viewAll.value = !viewAll.value
  currentPage.value = 1
  loadLeaderboard()
}

const changePage = (p) => {
  currentPage.value = p
  loadLeaderboard()
}

const loadLeaderboard = async () => {
  isLoadingLeaderboard.value = true
  try {
    const params = {
      type: leaderboardType.value,
      search: searchQuery.value,
      tier: selectedTier.value,
      all: viewAll.value ? 'true' : 'false',
      page: currentPage.value,
      per_page: viewAll.value ? 'all' : '50'
    }
    const res = await axios.get('/api/arena/leaderboard', { params })
    const payload = res.data.data
    leaderboardList.value = payload.leaderboard || []
    totalLearners.value = payload.total_learners || 0
    totalSystemXp.value = payload.total_system_xp || 0
    filteredCount.value = payload.filtered_count || 0
    currentPage.value = payload.current_page || 1
    lastPage.value = payload.last_page || 1
    tierCounts.value = payload.tier_counts || {}
  } catch (err) {
    console.error('Failed to load admin leaderboard:', err)
  } finally {
    isLoadingLeaderboard.value = false
  }
}

const formatCategory = (cat) => {
  const map = {
    vocab_3000: 'Oxford 3.000 từ',
    daily: 'Học theo Ngày',
    curriculum: 'Lộ trình 25 Chủ đề',
    grammar: 'Ngữ pháp 12 thì',
    custom: 'Tự chọn'
  }
  return map[cat] || cat
}

const getStatusCount = (st) => {
  if (st === 'all') return rooms.value.length
  return rooms.value.filter(r => r.status === st).length
}

const filteredRooms = computed(() => {
  if (activeStatus.value === 'all') return rooms.value
  return rooms.value.filter(r => r.status === activeStatus.value)
})

const loadRooms = async () => {
  isLoadingRooms.value = true
  try {
    const res = await axios.get('/api/admin/arena/rooms')
    rooms.value = res.data.data || []
  } catch (err) {
    console.error('Failed to load admin arena rooms:', err)
  } finally {
    isLoadingRooms.value = false
  }
}

const closeRoom = (room) => {
  confirmModal.value = {
    show: true,
    title: 'Đóng phòng thi đấu',
    danger: false,
    message: `Bạn có chắc muốn đóng phòng thi đấu "${room.title}" (${room.room_code})?`,
    onConfirm: async () => {
      try {
        await axios.post(`/api/admin/arena/rooms/${room.id}/close`)
        room.status = 'finished'
        toast.success(`Đã đóng phòng "${room.room_code}" thành công!`, 'Quản trị Đấu trường')
      } catch (err) {
        toast.error(err.response?.data?.message || 'Không thể đóng phòng lúc này.', 'Lỗi hệ thống')
      }
    }
  }
}

const deleteRoom = (room) => {
  confirmModal.value = {
    show: true,
    title: 'Xóa vĩnh viễn phòng thi',
    danger: true,
    message: `CẢNH BÁO: Bạn có chắc chắn muốn XÓA VĨNH VIỄN phòng "${room.title}" (${room.room_code})? Hành động này không thể hoàn tác.`,
    onConfirm: async () => {
      try {
        await axios.delete(`/api/admin/arena/rooms/${room.id}`)
        rooms.value = rooms.value.filter(r => r.id !== room.id)
        toast.success(`Đã xóa phòng "${room.room_code}" thành công!`, 'Quản trị Đấu trường')
      } catch (err) {
        toast.error(err.response?.data?.message || 'Không thể xóa phòng lúc này.', 'Lỗi hệ thống')
      }
    }
  }
}

const executeConfirmRoomAction = async () => {
  const fn = confirmModal.value.onConfirm
  confirmModal.value.show = false
  if (fn) await fn()
}

onMounted(() => {
  loadLeaderboard()
  loadRooms()
})
</script>

<style scoped>
.table-teal-highlight {
  background-color: rgba(8, 127, 117, 0.08) !important;
  font-weight: 600;
}

.animate-pulse {
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

.tier-grandmaster {
  background: linear-gradient(135deg, #7e22ce, #dc2626);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.tier-diamond {
  background: linear-gradient(135deg, #0284c7, #38bdf8);
  color: #fff;
}

.tier-platinum {
  background: linear-gradient(135deg, #0d9488, #2dd4bf);
  color: #fff;
}

.tier-gold {
  background: linear-gradient(135deg, #d97706, #fbbf24);
  color: #1e1b4b;
  font-weight: 700;
}

.tier-silver {
  background: linear-gradient(135deg, #64748b, #94a3b8);
  color: #fff;
}

.tier-bronze {
  background: linear-gradient(135deg, #b45309, #d97706);
  color: #fff;
}
</style>
