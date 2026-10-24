<template>
  <div class="arena-lobby-page py-4">
    <div class="container max-w-6xl">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb fs-8 mb-0">
          <li class="breadcrumb-item"><router-link to="/" class="text-decoration-none text-muted">Trang chủ</router-link></li>
          <li class="breadcrumb-item active text-teal fw-bold" aria-current="page">Đấu trường Quiz Trực tuyến</li>
        </ol>
      </nav>

      <!-- Hero Banner -->
      <div class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom mb-4 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #022c22 0%, #064e40 60%, #087F75 100%);">
        <div class="position-relative z-1 text-white max-w-2xl mx-auto">
          <span class="badge rounded-pill px-3 py-1.5 fw-bold mb-2 shadow-sm" style="background: rgba(94, 234, 212, 0.2); color: #5eead4; font-size: 11px;">
            <i class="bi bi-trophy-fill me-1"></i> THI ĐUA TRỰC TUYẾN THẦN TỐC
          </span>
          <h2 class="fw-black mb-2 display-6">Đấu Trường Trắc Nghiệm Realtime</h2>
          <p class="text-white-50 fs-7 mb-4">
            Tham gia phòng thi đấu trắc nghiệm cùng lớp học và bạn bè. Trả lời càng nhanh, điểm kinh nghiệm XP thưởng càng cao! Giáo viên và trưởng nhóm có thể mở phòng riêng theo từng chủ đề.
          </p>

          <!-- Quick Join Input -->
          <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 max-w-lg mx-auto mb-3">
            <div class="input-group">
              <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-key-fill"></i></span>
              <input
                type="text"
                v-model="quickCode"
                @keyup.enter="handleQuickJoin"
                class="form-control form-control-lg fs-7 border-0"
                placeholder="Nhập mã phòng (VD: OX-ARENA1 hoặc OX-VIP99)..."
              />
              <button @click="handleQuickJoin" class="btn btn-warning text-dark fw-bold px-4 fs-7">
                Vào phòng <i class="bi bi-box-arrow-in-right ms-1"></i>
              </button>
            </div>
          </div>

          <div class="d-flex justify-content-center gap-3">
            <button @click="openCreateModal" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-dark fs-7 shadow">
              <i class="bi bi-plus-circle-fill text-success me-1.5"></i> Mở phòng thi mới
            </button>
            <router-link to="/tree" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold fs-7">
              <i class="bi bi-tree-fill text-warning me-1.5"></i> Cây Tri Thức
            </router-link>
          </div>
        </div>
      </div>

      <!-- Category Filter Pills -->
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="selectedCategory = cat.id"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
            :class="selectedCategory === cat.id ? 'btn-teal text-white shadow-xs' : 'btn-outline-secondary'"
          >
            {{ cat.name }}
          </button>
        </div>

        <button @click="loadRooms" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fs-8">
          <i class="bi bi-arrow-clockwise me-1"></i> Làm mới danh sách
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-teal" role="status"></div>
        <div class="text-muted fs-7 mt-2">Đang tải danh sách phòng thi đấu...</div>
      </div>

      <!-- Active Rooms Grid -->
      <div v-else-if="filteredRooms.length" class="row g-4">
        <div v-for="room in filteredRooms" :key="room.id" class="col-12 col-md-6 col-lg-4">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between transition-all hover-card">
            <div>
              <!-- Header Badges -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-8 fw-bold">
                  {{ room.room_code }}
                </span>
                <span v-if="room.has_password" class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fs-8">
                  <i class="bi bi-lock-fill me-1"></i> Có mật khẩu
                  <span v-if="room.room_code === 'OX-VIP99'" class="badge bg-white text-dark ms-1 shadow-2xs">MK: 123456</span>
                </span>
                <span v-else class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-8">
                  <i class="bi bi-unlock-fill me-1"></i> Tự do
                </span>
              </div>

              <!-- Title -->
              <h5 class="fw-bold text-dark-emphasis mb-2 line-clamp-2" style="min-height: 48px;">
                {{ room.title }}
              </h5>

              <!-- Host Info -->
              <div class="d-flex align-items-center gap-2 mb-3">
                <img :src="room.host_avatar || 'https://api.dicebear.com/7.x/bottts/svg?seed=Teacher'" class="rounded-circle border" style="width: 26px; height: 26px;" />
                <span class="fs-8 text-muted">Chủ phòng: <strong class="text-dark-emphasis">{{ room.host_name }}</strong></span>
              </div>

              <!-- Metadata Pills -->
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2.5 py-1 fs-8">
                  <i class="bi bi-patch-question me-1"></i> {{ room.total_questions }} câu hỏi
                </span>
                <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2.5 py-1 fs-8">
                  <i class="bi bi-stopwatch me-1"></i> {{ room.time_per_question }}s/câu
                </span>
                <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2.5 py-1 fs-8">
                  <i class="bi bi-people-fill me-1"></i> {{ room.participants_count }}/{{ room.max_players }} học viên
                </span>
              </div>
            </div>

            <!-- Join Button -->
            <button @click="joinRoom(room)" class="btn btn-teal text-white w-100 rounded-pill py-2 fw-bold fs-7 shadow-xs">
              <span>Tham gia phòng</span>
              <i class="bi bi-arrow-right ms-1"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="card p-5 rounded-4 border text-center bg-card-custom">
        <i class="bi bi-door-closed display-4 text-muted mb-2"></i>
        <h5 class="fw-bold text-muted">Chưa có phòng thi nào trong danh mục này</h5>
        <p class="text-secondary fs-7 mb-3">Bạn có thể tự tạo phòng thi đấu mới và gửi mã mời cho bạn bè!</p>
        <div>
          <button @click="openCreateModal" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7">
            + Mở phòng ngay
          </button>
        </div>
      </div>
    </div>

    <!-- Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-header border-bottom py-2.5">
            <h6 class="modal-title fw-bold text-teal"><i class="bi bi-lock-fill me-1"></i> Nhập mật khẩu phòng</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-3">
            <div class="mb-3">
              <label class="form-label fs-8 text-muted">Mật khẩu phòng:</label>
              <input type="password" v-model="roomPassword" @keyup.enter="confirmJoinWithPassword" class="form-control" placeholder="Mật khẩu..." />
              <div v-if="targetRoom?.room_code === 'OX-VIP99'" class="form-text fs-8 text-teal mt-1">
                <i class="bi bi-key-fill me-1"></i> Mật khẩu lớp học Cô Thu Trang: <strong>123456</strong>
              </div>
            </div>
            <button @click="confirmJoinWithPassword" class="btn btn-teal text-white w-100 rounded-pill fw-bold fs-7">
              Xác nhận & Vào phòng
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Room Modal -->
    <div class="modal fade" id="createRoomModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
          <div class="modal-header border-bottom py-3 px-4">
            <div>
              <h5 class="modal-title fw-black text-teal mb-0"><i class="bi bi-plus-circle-fill me-1.5"></i> Mở Phòng Thi Đua Quiz Mới</h5>
              <div class="text-muted fs-8">Tùy chỉnh sâu nguồn câu hỏi, thời gian, điểm thưởng và thời hạn phòng</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <!-- Room Title -->
            <div class="mb-3">
              <label class="form-label fs-7 fw-bold">Tên phòng thi:</label>
              <input type="text" v-model="newRoom.title" class="form-control rounded-3" placeholder="VD: Lớp cô Lan - Đấu trường Oxford 3000..." />
            </div>

            <!-- Source Category -->
            <div class="row g-3 mb-3">
              <div class="col-12 col-md-6">
                <label class="form-label fs-7 fw-bold">Nguồn câu hỏi & Nội dung:</label>
                <select v-model="newRoom.category" class="form-select rounded-3">
                  <option value="vocab_3000">Kho 3.000 Từ Vựng Oxford Chuẩn</option>
                  <option value="daily">Học theo Ngày (Ngày 1 - 30)</option>
                  <option value="curriculum">Lộ trình 25 Chủ Đề Thực Chiến</option>
                  <option value="grammar">12 Thì Ngữ Pháp Tiếng Anh</option>
                </select>
              </div>

              <!-- Conditional: Select Day for 'daily' -->
              <div v-if="newRoom.category === 'daily'" class="col-12 col-md-6">
                <label class="form-label fs-7 fw-bold">Chọn Ngày học (1 - 30):</label>
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar-check"></i></span>
                  <input type="number" min="1" max="30" v-model.number="newRoom.selected_day" class="form-control rounded-end-3" placeholder="Nhập ngày (VD: 1, 5, 12...)" />
                </div>
                <div class="form-text fs-8 text-teal">Hệ thống sẽ lấy chính xác các từ vựng thuộc ngày này để làm bài quiz.</div>
              </div>

              <!-- Conditional: Select Topic for 'curriculum' -->
              <div v-else-if="newRoom.category === 'curriculum'" class="col-12 col-md-6">
                <label class="form-label fs-7 fw-bold">Chọn Chủ đề trong 25 Chủ đề:</label>
                <select v-model="newRoom.selected_topic_id" class="form-select rounded-3">
                  <option :value="null">Ngẫu nhiên tất cả 25 chủ đề</option>
                  <option v-for="top in curriculumTopics" :key="top.id" :value="top.id">
                    {{ top.title || top.name }}
                  </option>
                </select>
              </div>

              <div v-else class="col-12 col-md-6">
                <label class="form-label fs-7 fw-bold">Cấp độ & Phân loại:</label>
                <div class="p-2 rounded-3 bg-light border fs-8 text-muted d-flex align-items-center gap-2">
                  <i class="bi bi-check2-circle text-teal"></i>
                  <span>{{ newRoom.category === 'grammar' ? 'Bao gồm toàn bộ 12 thì & chuyên đề bị động' : 'Bao phủ từ vựng Oxford theo CEFR A1 - C1' }}</span>
                </div>
              </div>
            </div>

            <!-- Questions count & Time per question -->
            <div class="row g-3 mb-3">
              <div class="col-12 col-md-6">
                <label class="form-label fs-8 fw-bold text-muted">Số lượng câu hỏi (tùy chỉnh):</label>
                <div class="input-group">
                  <input type="number" min="3" max="50" v-model.number="newRoom.questions_count" class="form-control" placeholder="10" />
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Gợi ý</button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 5">5 câu (Nhanh)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 10">10 câu (Tiêu chuẩn)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 15">15 câu (Thử thách)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 20">20 câu (Marathon)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 30">30 câu (Đại hội)</a></li>
                  </ul>
                </div>
                <div class="form-text fs-8 text-muted">Để trống hoặc nhập số bất kỳ bạn muốn.</div>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fs-8 fw-bold text-muted">Thời gian mỗi câu (giây):</label>
                <div class="input-group">
                  <input type="number" min="0" max="120" v-model.number="newRoom.time_per_question" class="form-control" placeholder="15" />
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Gợi ý</button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.time_per_question = 10">10s (Tia chớp)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.time_per_question = 15">15s (Tiêu chuẩn)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.time_per_question = 30">30s (Thoải mái)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.time_per_question = 60">60s (Sâu sắc)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.time_per_question = 0">0 (Không giới hạn)</a></li>
                  </ul>
                </div>
                <div class="form-text fs-8 text-muted">Nhập 0 nếu không muốn giới hạn thời gian.</div>
              </div>
            </div>

            <!-- XP per question & Max players -->
            <div class="row g-3 mb-3">
              <div class="col-12 col-md-6">
                <label class="form-label fs-8 fw-bold text-muted">Điểm thưởng XP mỗi câu:</label>
                <div class="input-group">
                  <input type="number" min="5" max="100" v-model.number="newRoom.xp_per_question" class="form-control" placeholder="15" />
                  <span class="input-group-text bg-white fs-8 text-muted">XP/câu</span>
                </div>
                <div class="form-text fs-8 text-muted">Mặc định là 15 XP (+ thưởng trả lời nhanh).</div>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fs-8 fw-bold text-muted">Số lượng học viên tối đa:</label>
                <input type="number" min="0" max="500" v-model.number="newRoom.max_players" class="form-control" placeholder="Để trống = Không giới hạn" />
                <div class="form-text fs-8 text-muted">Để trống hoặc nhập 0 để mở không giới hạn.</div>
              </div>
            </div>

            <!-- Room Lifetime & Password -->
            <div class="row g-3 mb-4">
              <div class="col-12 col-md-6">
                <label class="form-label fs-8 fw-bold text-muted">Thời gian tồn tại của phòng:</label>
                <select v-model="newRoom.lifetime" class="form-select fs-7 rounded-3">
                  <option value="30_mins">Tự hủy sau 30 phút</option>
                  <option value="1_hour">Tự hủy sau 1 giờ</option>
                  <option value="24_hours">Tự hủy sau 24 giờ</option>
                  <option value="permanent">Kéo dài vĩnh viễn (Chỉ xóa khi vào Admin)</option>
                </select>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fs-8 fw-bold text-muted">Mật khẩu phòng (Để trống nếu mở tự do):</label>
                <input type="password" v-model="newRoom.password" class="form-control" placeholder="Nhập mật khẩu..." />
              </div>
            </div>

            <button @click="submitCreateRoom" class="btn btn-teal text-white w-100 rounded-pill py-3 fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-lightning-charge-fill"></i>
              <span>Khởi tạo phòng thi & Vào sảnh đấu</span>
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
import axios from 'axios'
import * as bootstrap from 'bootstrap'

const router = useRouter()

const isLoading = ref(true)
const rooms = ref([])
const curriculumTopics = ref([])
const quickCode = ref('')
const selectedCategory = ref('all')
const targetRoom = ref(null)
const roomPassword = ref('')

const categories = [
  { id: 'all', name: 'Tất cả phòng' },
  { id: 'vocab_3000', name: 'Oxford 3000 từ' },
  { id: 'daily', name: 'Học theo ngày' },
  { id: 'curriculum', name: 'Lộ trình 25 Chủ đề' },
  { id: 'grammar', name: 'Ngữ pháp 12 thì' }
]

const newRoom = ref({
  title: '',
  category: 'vocab_3000',
  selected_day: 1,
  selected_topic_id: null,
  questions_count: 10,
  time_per_question: 15,
  xp_per_question: 15,
  max_players: null,
  lifetime: 'permanent',
  password: ''
})

const filteredRooms = computed(() => {
  if (selectedCategory.value === 'all') return rooms.value
  return rooms.value.filter(r => r.category === selectedCategory.value)
})

const loadRooms = async () => {
  isLoading.value = true
  try {
    const res = await axios.get('/api/arena/rooms')
    rooms.value = res.data.data || []
  } catch (err) {
    console.error('Failed to load arena rooms:', err)
  } finally {
    isLoading.value = false
  }
}

const loadCurriculumTopics = async () => {
  try {
    const res = await axios.get('/api/curriculum')
    curriculumTopics.value = res.data.data || []
  } catch (err) {
    console.warn('Could not load curriculum topics for arena modal:', err)
  }
}

const handleQuickJoin = () => {
  const code = quickCode.value.trim().toUpperCase()
  if (!code) return
  router.push(`/arena/${code}`)
}

const joinRoom = (room) => {
  if (room.has_password) {
    targetRoom.value = room
    roomPassword.value = room.room_code === 'OX-VIP99' ? '123456' : ''
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('passwordModal'))
    modal.show()
  } else {
    router.push(`/arena/${room.room_code}`)
  }
}

const confirmJoinWithPassword = () => {
  if (!targetRoom.value) return
  const modal = bootstrap.Modal.getInstance(document.getElementById('passwordModal'))
  if (modal) modal.hide()
  router.push({
    path: `/arena/${targetRoom.value.room_code}`,
    query: { pwd: roomPassword.value }
  })
}

const openCreateModal = () => {
  newRoom.value.title = `Phòng thi đấu thử thách ${new Date().toLocaleDateString('vi-VN')}`
  const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('createRoomModal'))
  modal.show()
}

const submitCreateRoom = async () => {
  if (!newRoom.value.title) {
    alert('Vui lòng nhập tên phòng thi.')
    return
  }

  try {
    const res = await axios.post('/api/arena/rooms', newRoom.value)
    const modal = bootstrap.Modal.getInstance(document.getElementById('createRoomModal'))
    if (modal) modal.hide()
    router.push(`/arena/${res.data.data.room_code}`)
  } catch (err) {
    console.error('Failed to create arena room:', err)
    alert(err.response?.data?.message || 'Không thể tạo phòng lúc này.')
  }
}

onMounted(() => {
  loadRooms()
  loadCurriculumTopics()
})
</script>

<style scoped>
.hover-card:hover {
  transform: translateY(-3px);
  border-color: #00b074 !important;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
