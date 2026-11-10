<template>
  <div class="container py-4 max-w-6xl">
    <!-- Hero Banner with Deep Teal styling -->
    <div class="rounded-4 p-4 p-md-5 mb-4 shadow-sm text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #064e40 0%, #085346 50%, #0d7362 100%);">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 position-relative z-1">
        <div>
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge rounded-pill px-3 py-1 fs-8 fw-bold" style="background-color: rgba(94, 234, 212, 0.2); color: #5eead4; border: 1px solid rgba(94, 234, 212, 0.4);">
              OXFORD 3000™ • HỌC THÔNG MINH QUIZLET
            </span>
            <span class="badge bg-white text-dark rounded-pill px-2.5 py-0.5 fs-8 fw-bold">
              MỞ KHÓA 100%
            </span>
          </div>
          <h1 class="display-5 fw-black mb-2 tracking-tight">
            Xin chào, {{ authStore.userName }}! 👋
          </h1>
          <p class="fs-6 text-white-50 max-w-xl mb-4 leading-relaxed">
            Triết lý cốt lõi: <strong>Xây dựng kiến thức trước → Làm bài kiểm tra sau</strong>. Học qua Flashcard 3D, Nghe giọng Mỹ, Luyện nói chấm điểm và trắc nghiệm 15 câu.
          </p>
          <div class="d-flex gap-3 flex-wrap">
            <router-link to="/daily" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold shadow-sm d-flex align-items-center gap-2" style="color: #064e40;">
              <i class="bi bi-play-circle-fill fs-5"></i>
              <span>Vào học theo Ngày (3000 từ)</span>
            </router-link>
            <router-link to="/dictionary" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-semibold fs-7 d-flex align-items-center gap-2">
              <i class="bi bi-search"></i>
              <span>Tra cứu 3000 Từ Vựng</span>
            </router-link>
          </div>
        </div>

        <!-- Right Quick Pill: Accurate Streak & Role -->
        <div class="bg-black bg-opacity-25 backdrop-blur p-4 rounded-4 border border-white-50 text-center shadow" style="min-width: 210px;">
          <div class="fs-1 fw-black text-warning mb-1">
            🔥 {{ currentStreak }} ngày
          </div>
          <div class="fs-7 text-white-50 fw-semibold mb-2">Chuỗi ngày học liên tục</div>
          <div class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
            {{ authStore.isAdmin ? 'Quản trị viên (Admin)' : 'Học viên Oxford' }}
          </div>
        </div>
      </div>
    </div>

    <!-- SEEDLING TREE & OVERALL PROGRESS SECTION (Tính năng Cây Hạt Giống) -->
    <div class="row g-4 mb-5">
      <!-- Cây Hạt Giống Widget -->
      <div class="col-12 col-lg-7">
        <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
              <div class="rounded-3 p-2 bg-success-subtle text-success fs-4">
                {{ currentTreeStage.icon }}
              </div>
              <div>
                <h5 class="fw-bold text-teal mb-0">Cây Tri Thức: {{ currentTreeStage.title }}</h5>
                <span class="text-muted fs-8">Giai đoạn {{ currentTreeStage.stageNumber }}/6 • {{ authStore.user?.xp_points || 0 }} XP</span>
              </div>
            </div>
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fs-8">
              {{ currentTreeStage.badgeText }}
            </span>
          </div>

          <p class="text-muted fs-7 mb-3">
            {{ currentTreeStage.description }}
          </p>

          <!-- XP Progress Bar towards next stage -->
          <div class="mb-2">
            <div class="d-flex justify-content-between align-items-center fs-8 fw-semibold mb-1">
              <span class="text-teal">Tiến độ phát triển giai đoạn</span>
              <span class="text-success">{{ stageProgressPercent }}%</span>
            </div>
            <div class="progress" style="height: 12px; border-radius: 8px;">
              <div
                class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                role="progressbar"
                :style="{ width: stageProgressPercent + '%' }"
              ></div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center text-muted fs-8 mt-2">
            <span>{{ authStore.user?.xp_points || 0 }} / {{ currentTreeStage.nextThreshold }} XP</span>
            <span class="text-warning-emphasis fw-bold">
              Còn {{ Math.max(0, currentTreeStage.nextThreshold - (authStore.user?.xp_points || 0)) }} XP nữa để lên cấp cây tiếp theo!
            </span>
          </div>
        </div>
      </div>

      <!-- Overall Course Progress Card -->
      <div class="col-12 col-lg-5">
        <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-muted fs-8 fw-semibold text-uppercase">Tiến độ toàn bộ 3000 từ</span>
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-8">
                3.351 từ Oxford
              </span>
            </div>

            <div class="d-flex align-items-baseline gap-2 mb-2">
              <h2 class="display-5 fw-black text-teal mb-0">{{ masteredPercent }}%</h2>
              <span class="text-muted fs-7">đã thuộc hoàn toàn</span>
            </div>

            <div class="progress mb-3" style="height: 14px; border-radius: 8px;">
              <div
                class="progress-bar bg-teal"
                role="progressbar"
                :style="{ width: masteredPercent + '%' }"
              ></div>
            </div>

            <div class="row g-2 text-center fs-8">
              <div class="col-4 p-2 rounded-3 bg-body-tertiary border">
                <div class="fw-bold text-success fs-6">{{ authStore.stats?.mastered_words || 0 }}</div>
                <div class="text-muted">Đã nhớ</div>
              </div>
              <div class="col-4 p-2 rounded-3 bg-body-tertiary border">
                <div class="fw-bold text-warning fs-6">{{ authStore.stats?.learning_words || 0 }}</div>
                <div class="text-muted">Cần ôn</div>
              </div>
              <div class="col-4 p-2 rounded-3 bg-body-tertiary border">
                <div class="fw-bold text-muted fs-6">{{ unlearnedWords }}</div>
                <div class="text-muted">Chưa học</div>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <router-link to="/daily" class="btn btn-outline-teal w-100 rounded-pill py-2 fs-7 fw-semibold">
              <i class="bi bi-arrow-right-circle me-1"></i> Tiếp tục bài học hàng ngày
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="row g-3 mb-5">
      <div class="col-6 col-md-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Tổng kho từ vựng</div>
          <div class="fs-2 fw-black text-teal">3,351</div>
          <div class="fs-8 text-muted">Từ điển Oxford chuẩn Mỹ</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Đã nhớ (Mastered)</div>
          <div class="fs-2 fw-black text-success">{{ authStore.stats?.mastered_words || 0 }}</div>
          <div class="fs-8 text-muted">Thuộc vững qua bài test</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Đang cần ôn</div>
          <div class="fs-2 fw-black text-warning">{{ authStore.stats?.learning_words || 0 }}</div>
          <div class="fs-8 text-muted">Lặp lại ngắt quãng (SRS)</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Ngữ pháp hoàn thành</div>
          <div class="fs-2 fw-black text-primary">{{ authStore.stats?.completed_grammars || 0 }}/15</div>
          <div class="fs-8 text-muted">12 thì & bài học nâng cao</div>
        </div>
      </div>
    </div>

    <!-- Quick Navigation Hub -->
    <h4 class="fw-bold text-teal mb-3"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Trung Tâm Lộ Trình Học</h4>
    <div class="row g-4 mb-5">
      <!-- Hub 1: Học theo ngày -->
      <div class="col-12 col-md-4">
        <div class="card p-4 rounded-4 border shadow-sm bg-card-custom h-100 d-flex flex-column justify-content-between hover-card">
          <div>
            <div class="rounded-3 p-3 bg-teal text-white d-inline-block mb-3 fs-3">
              <i class="bi bi-calendar3"></i>
            </div>
            <h5 class="fw-bold text-teal">Học theo Ngày (3000 từ)</h5>
            <p class="text-muted fs-7 mb-4">
              Chia 3000 từ thành từng ngày theo tốc độ của bạn (15 từ/ngày, 20 từ/ngày). Kiểm tra 15 câu ngay đầu trang và ôn tập gộp nhiều ngày.
            </p>
          </div>
          <router-link to="/daily" class="btn btn-teal w-100 rounded-pill py-2 fw-semibold fs-7 shadow-sm">
            Bắt đầu học theo Ngày →
          </router-link>
        </div>
      </div>

      <!-- Hub 2: 25 Chủ đề -->
      <div class="col-12 col-md-4">
        <div class="card p-4 rounded-4 border shadow-sm bg-card-custom h-100 d-flex flex-column justify-content-between hover-card">
          <div>
            <div class="rounded-3 p-3 bg-warning text-white d-inline-block mb-3 fs-3">
              <i class="bi bi-diagram-3"></i>
            </div>
            <h5 class="fw-bold text-teal">25 Chủ Đề Thông Dụng</h5>
            <p class="text-muted fs-7 mb-4">
              Gia đình, Công việc, Du lịch, Công nghệ, Ăn uống... Mở khóa 100% tất cả chủ đề để bạn học theo sở thích bất cứ lúc nào.
            </p>
          </div>
          <router-link to="/topics" class="btn btn-teal w-100 rounded-pill py-2 fw-semibold fs-7 shadow-sm">
            Xem 25 Chủ đề →
          </router-link>
        </div>
      </div>

      <!-- Hub 3: 12 Thì & Ngữ pháp -->
      <div class="col-12 col-md-4">
        <div class="card p-4 rounded-4 border shadow-sm bg-card-custom h-100 d-flex flex-column justify-content-between hover-card">
          <div>
            <div class="rounded-3 p-3 bg-primary text-white d-inline-block mb-3 fs-3">
              <i class="bi bi-journal-check"></i>
            </div>
            <h5 class="fw-bold text-teal">12 Thì & Ngữ Pháp Toàn Diện</h5>
            <p class="text-muted fs-7 mb-4">
              Đầy đủ 12 thì từ cơ bản đến nâng cao: công thức, dấu hiệu, ví dụ song ngữ, kèm bài kiểm tra trắc nghiệm và điền từ củng cố.
            </p>
          </div>
          <router-link to="/grammar" class="btn btn-teal w-100 rounded-pill py-2 fw-semibold fs-7 shadow-sm">
            Học Ngữ pháp →
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()

const currentStreak = computed(() => {
  return authStore.user?.streak_days !== undefined ? authStore.user.streak_days : 1
})

const totalWords = 3351

const masteredWords = computed(() => {
  return authStore.stats?.mastered_words || 0
})

const unlearnedWords = computed(() => {
  const learned = (authStore.stats?.mastered_words || 0) + (authStore.stats?.learning_words || 0)
  return Math.max(0, totalWords - learned)
})

const masteredPercent = computed(() => {
  return ((masteredWords.value / totalWords) * 100).toFixed(1)
})

// Stages of Seedling Plant Widget
const currentTreeStage = computed(() => {
  const xp = authStore.user?.xp_points || 0

  if (xp < 150) {
    return {
      stageNumber: 1,
      title: 'Hạt Giống Ươm Mầm',
      icon: '🌱',
      badgeText: 'Cấp độ 1',
      description: 'Hạt giống kiến thức đang được gieo xuống mảnh đất màu mỡ. Hãy học thêm từ vựng để hạt nảy mầm!',
      minThreshold: 0,
      nextThreshold: 150,
    }
  } else if (xp < 400) {
    return {
      stageNumber: 2,
      title: 'Mầm Non Nảy Chồi',
      icon: '🌿',
      badgeText: 'Cấp độ 2',
      description: 'Chồi non xanh tươi đã nhú lên đón ánh sáng mặt trời! Vốn từ vựng của bạn đang bắt đầu tích lũy vững chắc.',
      minThreshold: 150,
      nextThreshold: 400,
    }
  } else if (xp < 800) {
    return {
      stageNumber: 3,
      title: 'Cây Con Phát Triển',
      icon: '🪴',
      badgeText: 'Cấp độ 3',
      description: 'Cây con vươn cao với bộ rễ ngày càng sâu và vững chãi. Bạn đã làm chủ nhiều cụm từ giao tiếp cơ bản.',
      minThreshold: 400,
      nextThreshold: 800,
    }
  } else if (xp < 1500) {
    return {
      stageNumber: 4,
      title: 'Thân Cây Vươn Cành',
      icon: '🌳',
      badgeText: 'Cấp độ 4',
      description: 'Thân cây cứng cáp, bắt đầu đâm chồi phân cành sum sê. Phản xạ tiếng Anh của bạn ngày càng linh hoạt.',
      minThreshold: 800,
      nextThreshold: 1500,
    }
  } else if (xp < 2500) {
    return {
      stageNumber: 5,
      title: 'Tán Lá Xanh Mướt',
      icon: '🌲',
      badgeText: 'Cấp độ 5',
      description: 'Tán cây rợp bóng mát với hàng ngàn chiếc lá từ vựng xanh tươi! Bạn đã đạt trình độ trung cấp tự tin.',
      minThreshold: 1500,
      nextThreshold: 2500,
    }
  } else {
    return {
      stageNumber: 6,
      title: 'Đơm Hoa Kết Trái',
      icon: '🍎',
      badgeText: 'Đại thụ Master',
      description: 'Cây tri thức nở hoa rực rỡ và đơm những trái ngọt thành quả! Bạn đã làm chủ 3000 từ vựng Oxford xuất sắc!',
      minThreshold: 2500,
      nextThreshold: 5000,
    }
  }
})

const stageProgressPercent = computed(() => {
  const xp = authStore.user?.xp_points || 0
  const min = currentTreeStage.value.minThreshold
  const max = currentTreeStage.value.nextThreshold
  const range = max - min
  if (range <= 0) return 100
  const currentInRange = Math.max(0, xp - min)
  return Math.min(100, Math.round((currentInRange / range) * 100))
})

onMounted(() => {
  authStore.fetchMe()
})
</script>
