<template>
  <div class="arena-room-page py-4">
    <div class="container max-w-4xl">
      <!-- Top Navigation -->
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb fs-8 mb-0">
          <li class="breadcrumb-item"><router-link to="/" class="text-decoration-none text-muted">Trang chủ</router-link></li>
          <li class="breadcrumb-item"><router-link to="/arena" class="text-decoration-none text-muted">Đấu trường</router-link></li>
          <li class="breadcrumb-item active text-teal fw-bold" aria-current="page">{{ room?.room_code || 'Phòng thi đấu' }}</li>
        </ol>
      </nav>

      <!-- Loading State -->
      <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-teal" role="status"></div>
        <div class="text-muted fs-7 mt-2">Đang kết nối vào phòng thi đấu...</div>
      </div>

      <!-- Error State (Never blank screen) -->
      <div v-else-if="errorMessage || !room" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom text-center">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning mb-3" style="width: 64px; height: 64px;">
          <i class="bi bi-exclamation-triangle-fill fs-2"></i>
        </div>
        <h4 class="fw-black text-dark-emphasis mb-2">{{ errorMessage || 'Không tìm thấy phòng thi đấu' }}</h4>
        <p class="text-muted fs-7 max-w-md mx-auto mb-4">
          Phòng thi đấu này có thể đã kết thúc thời hạn, hoặc mã phòng không tồn tại. Vui lòng kiểm tra lại mã hoặc tham gia phòng khác.
        </p>
        <div class="d-flex justify-content-center gap-2">
          <router-link to="/arena" class="btn btn-teal rounded-pill px-4 py-2 fw-bold text-white fs-7 shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay về Sảnh Đấu Trường
          </router-link>
        </div>
      </div>

      <div v-else-if="room">
        <!-- ================= STAGE 1: WAITING LOBBY ================= -->
        <div v-if="room.status === 'waiting'" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom text-center">
          <!-- Room Code Pill -->
          <div class="mb-2">
            <span class="badge bg-teal text-white rounded-pill px-3 py-1.5 fs-7 fw-bold shadow-xs">
              MÃ PHÒNG: {{ room.room_code }}
            </span>
          </div>

          <h2 class="fw-black text-dark-emphasis mb-2 display-6">{{ room.title }}</h2>
          <p class="text-muted fs-7 max-w-lg mx-auto mb-4">
            Đang tập hợp các học viên và thành viên trong lớp. Khi chủ phòng bắt đầu, hệ thống sẽ kích hoạt đồng hồ đếm ngược trắc nghiệm tính điểm tốc độ!
          </p>

          <!-- Share Link Box -->
          <div class="d-flex justify-content-center gap-2 mb-4">
            <button @click="copyShareLink" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 fs-8 fw-semibold shadow-xs">
              <i class="bi" :class="copied ? 'bi-check-lg text-success' : 'bi-link-45deg'"></i>
              <span>{{ copied ? 'Đã sao chép link tham gia!' : 'Sao chép link phòng' }}</span>
            </button>
            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1.5 fs-8 d-flex align-items-center">
              <i class="bi bi-clock me-1"></i> {{ room.time_per_question }}s/câu • {{ questions.length }} câu hỏi
            </span>
          </div>

          <!-- Participants Grid -->
          <div class="card p-3 rounded-4 bg-body-tertiary border mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 px-2">
              <span class="fs-8 fw-bold text-muted text-uppercase">Danh sách thành viên sẵn sàng ({{ participants.length }})</span>
              <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-8">
                <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Đang online
              </span>
            </div>

            <div class="row g-2">
              <div v-for="part in participants" :key="part.id" class="col-6 col-md-4">
                <div class="p-2.5 rounded-3 bg-white border d-flex align-items-center gap-2 text-start shadow-2xs">
                  <img :src="part.user_avatar || 'https://api.dicebear.com/7.x/bottts/svg?seed=' + part.user_name" class="rounded-circle border" style="width: 32px; height: 32px;" />
                  <div class="text-truncate">
                    <div class="fw-bold fs-8 text-dark-emphasis text-truncate">{{ part.user_name }}</div>
                    <div class="text-muted fs-8">{{ part.total_score || 0 }} XP</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Start Button (Host) or Waiting Text (Guest) -->
          <div class="d-flex justify-content-center">
            <button
              v-if="isHost"
              @click="startGame"
              class="btn btn-teal btn-lg rounded-pill px-5 py-3 fw-bold text-white shadow-lg d-flex align-items-center gap-2"
            >
              <i class="bi bi-lightning-charge-fill fs-5"></i>
              <span>Bắt đầu thi đấu ngay</span>
            </button>
            <div v-else class="alert alert-info border-0 rounded-pill px-4 py-2.5 fs-7 d-inline-flex align-items-center gap-2">
              <div class="spinner-border spinner-border-sm text-info" role="status"></div>
              <span>Đang chờ chủ phòng (<strong>{{ room.host_name }}</strong>) bấm bắt đầu...</span>
            </div>
          </div>
        </div>

        <!-- ================= STAGE 2: LIVE COMPETITION IN PROGRESS ================= -->
        <div v-else-if="room.status === 'playing'" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom">
          <!-- Top Bar: Progress & Speed Timer -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-teal text-white rounded-pill px-3 py-1.5 fs-8 fw-bold">
                CÂU {{ currentQuestionIndex + 1 }} / {{ questions.length }}
              </span>
              <span class="fs-8 text-muted">Điểm hiện tại: <strong class="text-warning">{{ currentMyScore }} XP</strong></span>
            </div>

            <!-- Countdown Timer Clock -->
            <div class="d-flex align-items-center gap-2">
              <span class="fs-8 text-muted d-none d-sm-inline">Thời gian còn lại:</span>
              <div
                class="rounded-pill px-3 py-1 fw-black fs-6 border"
                :class="timeLeft <= 5 ? 'bg-danger text-white border-danger animate-pulse' : 'bg-warning-subtle text-dark border-warning-subtle'"
              >
                ⏱️ {{ timeLeft }}s
              </div>
            </div>
          </div>

          <!-- Timer Progress Bar -->
          <div class="progress mb-4" style="height: 6px;">
            <div
              class="progress-bar transition-all"
              :class="timeLeft <= 5 ? 'bg-danger' : 'bg-teal'"
              role="progressbar"
              :style="{ width: (timeLeft / (room.time_per_question || 15)) * 100 + '%' }"
            ></div>
          </div>

          <!-- Question Prompt -->
          <div class="p-4 rounded-4 bg-body-tertiary border mb-4 text-center">
            <div class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 fs-8 mb-2">
              {{ currentQuestion?.type || 'Trắc nghiệm chọn đáp án đúng' }}
            </div>
            <h4 class="fw-black text-dark-emphasis mb-0 leading-snug">
              {{ currentQuestion?.q || 'Đang chuẩn bị câu hỏi...' }}
            </h4>
          </div>

          <!-- Speed bonus tip -->
          <div class="d-flex justify-content-between align-items-center fs-8 text-muted mb-3 px-1">
            <span><i class="bi bi-speedometer2 text-teal me-1"></i> Trả lời trong 3s đầu: <strong>+30 XP</strong></span>
            <span><i class="bi bi-shield-check text-success me-1"></i> Trả lời đúng: <strong>+15 XP</strong></span>
          </div>

          <!-- 4 Multiple Choice Options (A, B, C, D) -->
          <div class="row g-3 mb-4">
            <div
              v-for="(opt, optIdx) in currentQuestion?.options || []"
              :key="optIdx"
              class="col-12 col-md-6"
            >
              <button
                @click="chooseOption(opt)"
                class="btn w-100 p-3.5 rounded-4 text-start border transition-all h-100 d-flex align-items-center gap-3"
                :class="getOptionClass(opt)"
                :disabled="selectedAnswer !== null"
              >
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7 shrink-0 option-badge">
                  {{ ['A', 'B', 'C', 'D'][optIdx] }}
                </div>
                <div class="fw-bold fs-7 text-dark-emphasis">{{ opt }}</div>
              </button>
            </div>
          </div>

          <!-- Instant Feedback Alert -->
          <div v-if="selectedAnswer !== null" class="alert border-0 rounded-4 p-3 mb-0" :class="isCurrentCorrect ? 'alert-success' : 'alert-danger'">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <strong>{{ isCurrentCorrect ? '🎉 Chính xác!' : '❌ Chưa chính xác!' }}</strong>
                <span class="ms-2 fs-8">{{ currentQuestion?.explain || '' }}</span>
              </div>
              <span class="badge bg-white text-dark rounded-pill px-2.5 py-1 fs-8 fw-bold">
                {{ isCurrentCorrect ? `+${lastEarnedXp} XP` : '+0 XP' }}
              </span>
            </div>
          </div>
        </div>

        <!-- ================= STAGE 3: FINISHED / PODIUM ================= -->
        <div v-else-if="room.status === 'finished'" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom text-center">
          <div class="mb-2">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fs-7 fw-bold shadow-xs">
              🏆 BẢNG VINH DANH HOÀN TẤT TRẬN ĐẤU
            </span>
          </div>
          <h2 class="fw-black text-dark-emphasis mb-2 display-6">Kết Quả Chung Cuộc</h2>
          <p class="text-muted fs-7 mb-4">Xin chúc mừng các học viên đã xuất sắc trả lời nhanh và chính xác nhất!</p>

          <!-- Top 3 Podium (1st, 2nd, 3rd) -->
          <div class="d-flex justify-content-center align-items-end gap-3 mb-5 px-3" style="min-height: 200px;">
            <!-- 2nd Place -->
            <div v-if="sortedParticipants[1]" class="d-flex flex-column align-items-center" style="width: 120px;">
              <img :src="sortedParticipants[1].user_avatar" class="rounded-circle border border-2 border-secondary mb-1" style="width: 50px; height: 50px;" />
              <div class="fw-bold fs-8 text-dark-emphasis text-truncate w-100">{{ sortedParticipants[1].user_name }}</div>
              <div class="badge bg-secondary text-white rounded-pill fs-8 mb-2">{{ sortedParticipants[1].total_score }} XP</div>
              <div class="podium-step bg-secondary-subtle border rounded-top-3 w-100 d-flex align-items-center justify-content-center fw-black fs-4 text-secondary" style="height: 90px;">
                🥈 2
              </div>
            </div>

            <!-- 1st Place (Champion) -->
            <div v-if="sortedParticipants[0]" class="d-flex flex-column align-items-center" style="width: 140px;">
              <div class="fs-4">👑</div>
              <img :src="sortedParticipants[0].user_avatar" class="rounded-circle border border-3 border-warning mb-1 shadow" style="width: 64px; height: 64px;" />
              <div class="fw-black fs-7 text-dark-emphasis text-truncate w-100">{{ sortedParticipants[0].user_name }}</div>
              <div class="badge bg-warning text-dark rounded-pill fs-8 fw-bold mb-2 shadow-xs">{{ sortedParticipants[0].total_score }} XP</div>
              <div class="podium-step bg-warning-subtle border border-warning rounded-top-3 w-100 d-flex align-items-center justify-content-center fw-black fs-3 text-warning-emphasis shadow-sm" style="height: 130px;">
                🥇 1
              </div>
            </div>

            <!-- 3rd Place -->
            <div v-if="sortedParticipants[2]" class="d-flex flex-column align-items-center" style="width: 120px;">
              <img :src="sortedParticipants[2].user_avatar" class="rounded-circle border border-2 border-danger-subtle mb-1" style="width: 50px; height: 50px;" />
              <div class="fw-bold fs-8 text-dark-emphasis text-truncate w-100">{{ sortedParticipants[2].user_name }}</div>
              <div class="badge bg-danger-subtle text-danger rounded-pill fs-8 mb-2">{{ sortedParticipants[2].total_score }} XP</div>
              <div class="podium-step bg-danger-subtle border rounded-top-3 w-100 d-flex align-items-center justify-content-center fw-black fs-4 text-danger" style="height: 70px;">
                🥉 3
              </div>
            </div>
          </div>

          <!-- Full Participants Table -->
          <div class="table-responsive mb-4">
            <table class="table table-hover align-middle border rounded-4 overflow-hidden fs-7">
              <thead class="table-light">
                <tr>
                  <th style="width: 60px;">Hạng</th>
                  <th>Học viên</th>
                  <th>Câu đúng</th>
                  <th>Thời gian TB</th>
                  <th class="text-end">Tổng XP</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(p, rIdx) in sortedParticipants" :key="p.id">
                  <td class="fw-bold">{{ rIdx + 1 }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <img :src="p.user_avatar" class="rounded-circle border" style="width: 28px; height: 28px;" />
                      <span class="fw-semibold">{{ p.user_name }}</span>
                    </div>
                  </td>
                  <td><span class="badge bg-success-subtle text-success">{{ p.correct_answers_count || 0 }}/{{ questions.length }}</span></td>
                  <td class="text-muted font-monospace">{{ p.avg_speed || '4.2' }}s</td>
                  <td class="text-end fw-black text-warning">{{ p.total_score || 0 }} XP</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-center gap-3">
            <router-link to="/arena" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7">
              <i class="bi bi-arrow-left me-1"></i> Quay về Sảnh Đấu Trường
            </router-link>
            <router-link to="/tree" class="btn btn-outline-teal rounded-pill px-4 py-2 fw-bold fs-7">
              🌱 Xem Cây Tri Thức
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import confetti from 'canvas-confetti'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const isLoading = ref(true)
const errorMessage = ref('')
const room = ref(null)
const participants = ref([])
const questions = ref([])
const copied = ref(false)

// In-game states
const currentQuestionIndex = ref(0)
const timeLeft = ref(15)
const timerInterval = ref(null)
const selectedAnswer = ref(null)
const isCurrentCorrect = ref(false)
const lastEarnedXp = ref(0)
const currentMyScore = ref(0)
const answerStartTime = ref(Date.now())

const isHost = computed(() => {
  return authStore.user && room.value && (room.value.host_user_id === authStore.user.id || room.value.created_by === authStore.user.id || authStore.isAdmin)
})

const currentQuestion = computed(() => {
  return questions.value[currentQuestionIndex.value] || null
})

const sortedParticipants = computed(() => {
  return [...participants.value].sort((a, b) => (b.score || b.total_score || 0) - (a.score || a.total_score || 0))
})

const copyShareLink = () => {
  navigator.clipboard.writeText(window.location.href)
  copied.value = true
  setTimeout(() => { copied.value = false }, 2500)
}

const loadRoomDetails = async () => {
  errorMessage.value = ''
  try {
    const res = await axios.get(`/api/arena/rooms/${route.params.idOrCode}`)
    const payload = res.data.data
    room.value = payload.room || payload
    
    // Normalize participants
    const rawParts = payload.participants || []
    participants.value = rawParts.map(p => ({
      ...p,
      total_score: p.score ?? p.total_score ?? 0,
      user_name: p.user_name || (p.user ? p.user.name : 'Học viên'),
      user_avatar: p.user_avatar || (p.user ? p.user.avatar_url : `https://api.dicebear.com/7.x/bottts/svg?seed=${p.user_id}`)
    }))

    // Normalize questions array
    const rawQuestions = payload.questions || payload.questions_data || []
    questions.value = rawQuestions.map((q, idx) => ({
      id: q.id || idx + 1,
      q: q.prompt || q.question || q.q || 'Câu hỏi trắc nghiệm',
      options: q.options || q.options_json || ['A', 'B', 'C', 'D'],
      answer: q.correct_answer || q.answer,
      correct_answer: q.correct_answer || q.answer,
      explain: q.explanation || q.explain || 'Đáp án chính xác.',
      type: q.type || 'Trắc nghiệm phản xạ'
    }))

    timeLeft.value = room.value.time_per_question || 15

    // Auto join participant if logged in and not joined yet
    if (authStore.isAuthenticated && authStore.user) {
      const alreadyIn = participants.value.some(p => p.user_id === authStore.user.id)
      if (!alreadyIn) {
        try {
          await axios.post('/api/arena/rooms/join', {
            room_code: room.value.room_code,
            password: ''
          })
          const refRes = await axios.get(`/api/arena/rooms/${route.params.idOrCode}`)
          const refPayload = refRes.data.data
          participants.value = (refPayload.participants || []).map(p => ({
            ...p,
            total_score: p.score ?? p.total_score ?? 0,
            user_name: p.user_name || (p.user ? p.user.name : 'Học viên'),
            user_avatar: p.user_avatar || (p.user ? p.user.avatar_url : `https://api.dicebear.com/7.x/bottts/svg?seed=${p.user_id}`)
          }))
        } catch (joinErr) {
          console.warn('Auto-join notification:', joinErr.response?.data?.message || joinErr.message)
        }
      }
    }
  } catch (err) {
    console.error('Failed to load arena room:', err)
    errorMessage.value = err.response?.data?.message || 'Không thể kết nối phòng thi đấu. Vui lòng kiểm tra lại đường dẫn.'
  } finally {
    isLoading.value = false
  }
}

const startGame = async () => {
  try {
    await axios.post(`/api/arena/rooms/${room.value.id || route.params.idOrCode}/start`)
    room.value.status = 'playing'
    startQuestionTimer()
  } catch (err) {
    console.error('Failed to start game:', err)
  }
}

const startQuestionTimer = () => {
  selectedAnswer.value = null
  answerStartTime.value = Date.now()
  timeLeft.value = room.value.time_per_question || 15

  clearInterval(timerInterval.value)
  timerInterval.value = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--
    } else {
      // Time expired
      clearInterval(timerInterval.value)
      if (selectedAnswer.value === null) {
        chooseOption('__TIMEOUT__')
      }
    }
  }, 1000)
}

const chooseOption = async (opt) => {
  if (selectedAnswer.value !== null) return
  selectedAnswer.value = opt
  clearInterval(timerInterval.value)

  const timeTaken = (Date.now() - answerStartTime.value) / 1000
  const isCorrect = opt === currentQuestion.value.answer || opt === currentQuestion.value.correct_answer
  isCurrentCorrect.value = isCorrect

  // Speed bonus
  let bonusXp = 0
  const baseReward = room.value.xp_per_question || 15
  if (isCorrect) {
    if (timeTaken <= 3) bonusXp = baseReward + 15
    else if (timeTaken <= 7) bonusXp = baseReward + 10
    else bonusXp = baseReward
    currentMyScore.value += bonusXp
    lastEarnedXp.value = bonusXp
    if (authStore.user) {
      authStore.user.xp_points = (authStore.user.xp_points || 0) + bonusXp
    }
  }

  try {
    await axios.post(`/api/arena/rooms/${room.value.id || route.params.idOrCode}/answer`, {
      question_index: currentQuestionIndex.value,
      question_id: currentQuestion.value.id || currentQuestionIndex.value,
      selected_option: opt,
      user_answer: opt,
      time_taken_ms: Math.round(timeTaken * 1000),
      time_taken_seconds: timeTaken,
      is_correct: isCorrect,
      xp_earned: bonusXp
    })
  } catch (err) {
    console.error('Failed to submit answer:', err)
  }

  // Delay then next question
  setTimeout(() => {
    if (currentQuestionIndex.value < questions.value.length - 1) {
      currentQuestionIndex.value++
      startQuestionTimer()
    } else {
      finishGame()
    }
  }, 2200)
}

const getOptionClass = (opt) => {
  if (selectedAnswer.value === null) {
    return 'bg-body-tertiary hover-border-teal'
  }
  if (opt === currentQuestion.value?.answer || opt === currentQuestion.value?.correct_answer) {
    return 'bg-success text-white border-success'
  }
  if (opt === selectedAnswer.value && !isCurrentCorrect.value) {
    return 'bg-danger text-white border-danger'
  }
  return 'bg-body-tertiary opacity-50'
}

const finishGame = () => {
  room.value.status = 'finished'
  confetti({
    particleCount: 150,
    spread: 90,
    origin: { y: 0.6 }
  })
}

onMounted(() => {
  loadRoomDetails()
})

onUnmounted(() => {
  clearInterval(timerInterval.value)
})
</script>

<style scoped>
.hover-border-teal:hover {
  border-color: #00b074 !important;
  background-color: rgba(0, 176, 116, 0.06) !important;
}

.option-badge {
  width: 32px;
  height: 32px;
  background-color: rgba(0, 0, 0, 0.08);
}

.animate-pulse {
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.85; transform: scale(1.05); }
}
</style>
