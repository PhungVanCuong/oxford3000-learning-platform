<template>
  <div class="admin-arena-view pb-5">
    <!-- Header -->
    <div class="card p-4 rounded-4 border shadow-sm bg-card-custom mb-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1.5">
            <span class="badge px-3 py-1 rounded-pill fw-bold fs-8" style="background-color: #E6F7F3; color: #087F75;">
              ● QUẢN TRỊ ĐẤU TRƯỜNG THI ĐẤU
            </span>
            <span class="badge bg-warning-subtle text-warning-emphasis border rounded-pill px-2.5 py-1 fs-8">
              {{ rooms.length }} phòng đang lưu trữ
            </span>
          </div>
          <h2 class="fw-black text-dark mb-1">Quản Lý Phòng Thi Đua & Đấu Trường ⚔️</h2>
          <p class="text-muted fs-7 mb-0">
            Xem danh sách tất cả các phòng do giáo viên và học viên mở, theo dõi trạng thái, đóng phòng đang thi hoặc xóa phòng vĩnh viễn.
          </p>
        </div>

        <div class="d-flex gap-2">
          <router-link to="/arena" class="btn btn-outline-teal rounded-pill px-3.5 py-2 fw-bold fs-7 shadow-xs">
            <i class="bi bi-box-arrow-up-right me-1"></i> Xem giao diện người dùng
          </router-link>
          <button @click="loadRooms" class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-7 shadow-sm">
            <i class="bi bi-arrow-clockwise me-1"></i> Làm mới
          </button>
        </div>
      </div>
    </div>

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
      <div v-if="isLoading" class="text-center py-5">
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
                  <i class="bi bi-lock-fill me-1"></i> MK: <code>{{ r.password }}</code>
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
                  {{ r.expires_at === 'Vĩnh viễn' ? '♾️ Vĩnh viễn' : r.expires_at }}
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
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const isLoading = ref(true)
const rooms = ref([])
const activeStatus = ref('all')

const statusFilters = [
  { key: 'all', label: 'Tất cả' },
  { key: 'waiting', label: 'Đang chờ' },
  { key: 'playing', label: 'Đang thi' },
  { key: 'finished', label: 'Đã đóng' },
]

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
  isLoading.value = true
  try {
    const res = await axios.get('/api/admin/arena/rooms')
    rooms.value = res.data.data || []
  } catch (err) {
    console.error('Failed to load admin arena rooms:', err)
  } finally {
    isLoading.value = false
  }
}

const closeRoom = async (room) => {
  if (!confirm(`Bạn có chắc muốn đóng phòng thi đấu "${room.title}" (${room.room_code})?`)) return
  try {
    await axios.post(`/api/admin/arena/rooms/${room.id}/close`)
    room.status = 'finished'
  } catch (err) {
    alert(err.response?.data?.message || 'Không thể đóng phòng lúc này.')
  }
}

const deleteRoom = async (room) => {
  if (!confirm(`CẢNH BÁO: Bạn có chắc chắn muốn XÓA VĨNH VIỄN phòng "${room.title}" (${room.room_code})? Hành động này không thể hoàn tác.`)) return
  try {
    await axios.delete(`/api/admin/arena/rooms/${room.id}`)
    rooms.value = rooms.value.filter(r => r.id !== room.id)
  } catch (err) {
    alert(err.response?.data?.message || 'Không thể xóa phòng lúc này.')
  }
}

onMounted(() => {
  loadRooms()
})
</script>

<style scoped>
.animate-pulse {
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}
</style>
