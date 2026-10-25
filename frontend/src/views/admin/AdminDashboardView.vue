<template>
  <div class="admin-dashboard-view pb-5">
    <!-- 1. Welcome Banner Header -->
    <div class="card p-4 rounded-4 border shadow-sm bg-card-custom mb-4" style="border-color: #E2E8F0;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
            <span class="badge px-3 py-1 rounded-pill fw-bold fs-8" style="background-color: #E6F7F3; color: #087F75;">
              ● HỆ THỐNG ĐANG VẬN HÀNH ỔN ĐỊNH
            </span>
            <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-8">
              Laravel API 8000 • Vite Vue 3
            </span>
          </div>
          <h2 class="fw-black text-dark mb-1">Tổng quan Bảng điều khiển Quản trị 📊</h2>
          <p class="text-muted fs-7 mb-0">
            Xin chào <strong>{{ authStore.userName }}</strong>. Hôm nay là <strong>{{ currentDateStr }}</strong>. Báo cáo dữ liệu và thống kê tình hình học tập toàn hệ thống.
          </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
          <router-link
            v-if="authStore.canManageCurriculum || authStore.isAdmin"
            to="/admin/curriculum"
            class="btn btn-outline-teal rounded-pill px-3.5 py-2 fw-bold shadow-xs fs-7 d-flex align-items-center gap-1.5"
          >
            <i class="bi bi-signpost-split-fill"></i>
            <span>QL Lộ Trình 25 Chủ Đề</span>
          </router-link>
          <router-link
            v-if="authStore.canManageArena || authStore.isAdmin"
            to="/admin/arena"
            class="btn btn-outline-warning text-dark rounded-pill px-3.5 py-2 fw-bold shadow-xs fs-7 d-flex align-items-center gap-1.5"
          >
            <i class="bi bi-trophy-fill text-warning"></i>
            <span>QL Đấu Trường Quiz</span>
          </router-link>
          <router-link
            v-if="authStore.canManageUsers || authStore.isAdmin"
            to="/admin/users"
            class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold shadow-sm d-flex align-items-center gap-1.5 fs-7"
          >
            <i class="bi bi-person-plus-fill"></i>
            <span>Thêm Học Viên Mới</span>
          </router-link>
        </div>
      </div>
    </div>

    <!-- 2. Six Airy, Spacious Stat Cards (Filtered by user permissions) -->
    <div class="row g-4 mb-4">
      <!-- Card 1: Tổng học viên -->
      <div v-if="authStore.canViewStudents || authStore.canManageUsers || authStore.isAdmin" class="col-12 col-sm-6 col-xl-4">
        <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted fs-8 text-uppercase fw-bold letter-spacing-05">Tài khoản & Học viên</span>
            <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-primary" style="background-color: #eff6ff; width: 44px; height: 44px;">
              <i class="bi bi-people-fill fs-5"></i>
            </div>
          </div>
          <div class="d-flex align-items-baseline gap-2">
            <div class="display-6 fw-black text-teal">{{ stats.total_users || 0 }}</div>
            <span class="text-muted fs-7">thành viên</span>
          </div>
          <div class="text-muted fs-8 mt-2 d-flex align-items-center gap-1.5">
            <i class="bi bi-shield-check text-success"></i>
            <span>Đã cấp quyền qua hệ thống RBAC</span>
          </div>
        </div>
      </div>

      <!-- Card 2: Lộ trình 25 Chủ đề IELTS -->
      <div v-if="authStore.canManageCurriculum || authStore.isAdmin" class="col-12 col-sm-6 col-xl-4">
        <router-link to="/admin/curriculum" class="text-decoration-none">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted fs-8 text-uppercase fw-bold letter-spacing-05">Lộ trình 25 Chủ đề</span>
              <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-info" style="background-color: #ecfeff; width: 44px; height: 44px;">
                <i class="bi bi-signpost-split-fill fs-5"></i>
              </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
              <div class="display-6 fw-black text-teal">{{ stats.total_curriculum_terms || 5000 }}</div>
              <span class="text-muted fs-7">mục từ học thuật</span>
            </div>
            <div class="text-muted fs-8 mt-2 d-flex align-items-center gap-1.5">
              <i class="bi bi-layers-fill text-info"></i>
              <span>25 Chủ đề CSV • 250 Giai đoạn</span>
            </div>
          </div>
        </router-link>
      </div>

      <!-- Card 3: Kho 3000 từ vựng Oxford -->
      <div v-if="authStore.canManageVocab || authStore.isAdmin" class="col-12 col-sm-6 col-xl-4">
        <router-link to="/admin/vocabularies" class="text-decoration-none">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted fs-8 text-uppercase fw-bold letter-spacing-05">Kho Oxford 3000™</span>
              <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-success" style="background-color: #E6F7F3; width: 44px; height: 44px;">
                <i class="bi bi-journal-bookmark-fill fs-5"></i>
              </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
              <div class="display-6 fw-black text-teal">{{ stats.total_words || 3351 }}</div>
              <span class="text-muted fs-7">từ vựng gốc</span>
            </div>
            <div class="text-muted fs-8 mt-2 d-flex align-items-center gap-1.5">
              <i class="bi bi-stars text-warning"></i>
              <span>Tích hợp AI Gemini sinh ví dụ</span>
            </div>
          </div>
        </router-link>
      </div>

      <!-- Card 4: Cụm từ & Phrasal Verbs -->
      <div v-if="authStore.canManagePhrases || authStore.isAdmin" class="col-12 col-sm-6 col-xl-4">
        <router-link to="/admin/phrasal-verbs" class="text-decoration-none">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted fs-8 text-uppercase fw-bold letter-spacing-05">Cụm từ & Phrasal Verbs</span>
              <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-warning" style="background-color: #fffbeb; width: 44px; height: 44px;">
                <i class="bi bi-diagram-3-fill fs-5"></i>
              </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
              <div class="display-6 fw-black text-teal">{{ stats.total_phrasal_verbs || 945 }}</div>
              <span class="text-muted fs-7">cụm từ & mẫu câu</span>
            </div>
            <div class="text-muted fs-8 mt-2 d-flex align-items-center gap-1.5">
              <i class="bi bi-link-45deg text-warning"></i>
              <span>95 Cụm Động Từ & 850 Cụm Từ Thông Dụng</span>
            </div>
          </div>
        </router-link>
      </div>

      <!-- Card 5: Lượt kiểm tra & Bài thi -->
      <div v-if="authStore.canViewReports || authStore.isAdmin" class="col-12 col-sm-6 col-xl-4">
        <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted fs-8 text-uppercase fw-bold letter-spacing-05">Lượt làm bài kiểm tra</span>
            <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-primary" style="background-color: #f5f3ff; width: 44px; height: 44px;">
              <i class="bi bi-patch-check-fill fs-5"></i>
            </div>
          </div>
          <div class="d-flex align-items-baseline gap-2">
            <div class="display-6 fw-black text-primary">{{ stats.total_quizzes || 0 }}</div>
            <span class="text-muted fs-7">lượt nộp bài</span>
          </div>
          <div class="text-muted fs-8 mt-2 d-flex align-items-center gap-1.5">
            <i class="bi bi-check2-circle text-primary"></i>
            <span>Tự động chấm điểm & cộng điểm XP</span>
          </div>
        </div>
      </div>

      <!-- Card 6: Đấu trường Quiz & Phòng thi đua -->
      <div v-if="authStore.canManageArena || authStore.isAdmin" class="col-12 col-sm-6 col-xl-4">
        <router-link to="/admin/arena" class="text-decoration-none">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted fs-8 text-uppercase fw-bold letter-spacing-05">Đấu trường Quiz Trực tuyến</span>
              <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-warning" style="background-color: #fefce8; width: 44px; height: 44px;">
                <i class="bi bi-trophy-fill fs-5 text-warning"></i>
              </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
              <div class="display-6 fw-black text-warning">QL Phòng</div>
              <span class="text-muted fs-7">thi đấu trực tiếp</span>
            </div>
            <div class="text-muted fs-8 mt-2 d-flex align-items-center gap-1.5">
              <i class="bi bi-lightning-charge text-warning"></i>
              <span>Quản lý phòng mở & kết thúc trận</span>
            </div>
          </div>
        </router-link>
      </div>

      <!-- Card 6: Hoạt động học tập hôm nay -->
      <div class="col-12 col-sm-6 col-xl-4">
        <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted fs-8 text-uppercase fw-bold letter-spacing-05">Hoạt động trong ngày</span>
            <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center text-danger" style="background-color: #fef2f2; width: 44px; height: 44px;">
              <i class="bi bi-fire fs-5"></i>
            </div>
          </div>
          <div class="d-flex align-items-baseline gap-2">
            <div class="display-6 fw-black text-danger">{{ stats.today_reviews || 0 }}</div>
            <span class="text-muted fs-7">lượt ôn & ghi nhớ từ</span>
          </div>
          <div class="text-muted fs-8 mt-2 d-flex align-items-center gap-1.5">
            <i class="bi bi-clock-history text-danger"></i>
            <span>Được cập nhật theo thời gian thực</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Main Data Section: 2 Columns -->
    <div class="row g-4">
      <!-- Left Column: Top Học Viên & Báo cáo Lộ Trình 25 Chủ Đề -->
      <div class="col-12 col-lg-8">
        <!-- Bảng Top Học Viên -->
        <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4" style="border-color: #E2E8F0;">
          <div class="card-header bg-white py-3.5 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
              <i class="bi bi-trophy-fill text-warning"></i>
              <span>Bảng Vinh Danh Top Học Viên Tích Cực</span>
            </h6>
            <router-link to="/admin/users" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 fs-8 fw-semibold">
              Quản lý học viên →
            </router-link>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light fs-8 text-uppercase">
                <tr>
                  <th class="ps-4" style="width: 50px;">#</th>
                  <th>Học viên</th>
                  <th>Email</th>
                  <th class="text-center">Chuỗi học</th>
                  <th class="text-center">Từ đã nhớ</th>
                  <th class="text-end pe-4">Điểm tích lũy</th>
                </tr>
              </thead>
              <tbody class="fs-7">
                <tr v-for="(learner, idx) in stats.top_learners || []" :key="learner.id">
                  <td class="fw-bold text-muted ps-4">{{ idx + 1 }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2.5">
                      <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-8 flex-shrink-0" style="width: 32px; height: 32px; background-color: #00b074;">
                        {{ (learner.name || 'H')[0].toUpperCase() }}
                      </div>
                      <span class="fw-bold text-dark">{{ learner.name }}</span>
                    </div>
                  </td>
                  <td class="text-muted fs-8">{{ learner.email }}</td>
                  <td class="text-center text-warning fw-bold">🔥 {{ learner.streak_days }} ngày</td>
                  <td class="text-center text-success fw-bold">{{ learner.mastered_count }} từ</td>
                  <td class="text-end fw-black text-primary pe-4">{{ learner.xp_points }} XP</td>
                </tr>
                <tr v-if="!stats.top_learners || stats.top_learners.length === 0">
                  <td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu học viên.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Báo cáo 25 Chủ Đề Lộ Trình IELTS -->
        <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden" style="border-color: #E2E8F0;">
          <div class="card-header bg-white py-3.5 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
              <i class="bi bi-bar-chart-line-fill text-teal"></i>
              <span>Tổng Quan 25 Chủ Đề Lộ Trình IELTS (5.000 Mục Từ)</span>
            </h6>
            <router-link to="/admin/curriculum" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 fs-8 fw-semibold">
              Xem tất cả 25 chủ đề →
            </router-link>
          </div>

          <div class="p-4">
            <div class="row g-3">
              <div 
                v-for="item in (stats.curriculum_overview || []).slice(0, 6)" 
                :key="item.id" 
                class="col-12 col-md-6"
              >
                <div class="p-3 rounded-3 border bg-light-subtle d-flex justify-content-between align-items-center">
                  <div class="text-truncate me-2">
                    <div class="fw-bold fs-8 text-dark text-truncate mb-0.5">
                      Chủ đề {{ String(item.order_num).padStart(2, '0') }}: {{ item.title }}
                    </div>
                    <div class="text-muted fs-8">{{ item.title_vi }}</div>
                  </div>
                  <div class="text-end flex-shrink-0">
                    <span class="badge bg-teal text-white rounded-pill px-2.5 py-1 fs-8 fw-bold">
                      200 từ
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Thao Tác Quản Trị & Thông Tin Hệ Thống -->
      <div class="col-12 col-lg-4">
        <!-- Thao Tác Quản Trị Nhanh -->
        <div class="card p-4 rounded-4 border shadow-sm bg-card-custom mb-4" style="border-color: #E2E8F0;">
          <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <span>Thao Tác Quản Trị Trọng Tâm</span>
          </h6>

          <div class="d-flex flex-column gap-3">
            <router-link to="/admin/curriculum" class="card p-3 rounded-3 border text-decoration-none hover-card text-dark d-flex align-items-center justify-content-between" style="border-left: 4px solid #00b074 !important;">
              <div>
                <div class="fw-bold fs-7 text-teal">Quản Lý Lộ Trình (25 Chủ Đề)</div>
                <div class="fs-8 text-muted mt-0.5">5.000 từ vựng CSV, 10 giai đoạn & câu trắc nghiệm</div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </router-link>

            <router-link to="/admin/users" class="card p-3 rounded-3 border text-decoration-none hover-card text-dark d-flex align-items-center justify-content-between" style="border-left: 4px solid #3b82f6 !important;">
              <div>
                <div class="fw-bold fs-7 text-primary">Phân Quyền Chức Vụ (RBAC)</div>
                <div class="fs-8 text-muted mt-0.5">Quản lý Admin / Moderator / User và ma trận quyền</div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </router-link>

            <router-link to="/admin/vocabularies" class="card p-3 rounded-3 border text-decoration-none hover-card text-dark d-flex align-items-center justify-content-between" style="border-left: 4px solid #10b981 !important;">
              <div>
                <div class="fw-bold fs-7 text-success">Kho 3000 Từ & Trợ Lý Gemini AI</div>
                <div class="fs-8 text-muted mt-0.5">Tự động tạo câu ví dụ song ngữ chuẩn học thuật</div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </router-link>

            <router-link to="/admin/phrasal-verbs" class="card p-3 rounded-3 border text-decoration-none hover-card text-dark d-flex align-items-center justify-content-between" style="border-left: 4px solid #f59e0b !important;">
              <div>
                <div class="fw-bold fs-7 text-warning-emphasis">Quản Lý Cụm Từ & Phrasal Verbs</div>
                <div class="fs-8 text-muted mt-0.5">Đồng bộ tự động tiến độ các từ thành phần</div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </router-link>

            <router-link to="/" class="btn btn-outline-secondary rounded-pill py-2.5 px-3 text-start d-flex align-items-center justify-content-between mt-2 fs-7">
              <span class="d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-up-right text-teal"></i>
                <span>Xem giao diện phía Học Viên</span>
              </span>
              <i class="bi bi-arrow-right"></i>
            </router-link>
          </div>
        </div>

        <!-- Thông tin Máy Chủ & Dịch Vụ -->
        <div class="card p-4 rounded-4 border shadow-sm bg-card-custom" style="border-color: #E2E8F0;">
          <h6 class="fw-bold text-dark mb-3 fs-7 d-flex align-items-center gap-2">
            <i class="bi bi-hdd-network text-teal"></i>
            <span>Thông Tin Dịch Vụ & Môi Trường</span>
          </h6>
          <ul class="list-unstyled mb-0 fs-8 d-flex flex-column gap-2.5 text-muted">
            <li class="d-flex justify-content-between border-bottom pb-2">
              <span>Backend Framework:</span>
              <span class="fw-bold text-dark">Laravel 11 REST API</span>
            </li>
            <li class="d-flex justify-content-between border-bottom pb-2">
              <span>Frontend Stack:</span>
              <span class="fw-bold text-dark">Vue 3 + Vite + Pinia</span>
            </li>
            <li class="d-flex justify-content-between border-bottom pb-2">
              <span>Cơ sở dữ liệu:</span>
              <span class="fw-bold text-dark">MySQL Database</span>
            </li>
            <li class="d-flex justify-content-between border-bottom pb-2">
              <span>Bảo mật & Token:</span>
              <span class="fw-bold text-dark">Laravel Sanctum Bearer</span>
            </li>
            <li class="d-flex justify-content-between">
              <span>Bộ từ vựng IELTS:</span>
              <span class="fw-bold text-success">5.000 Mục từ (25 File CSV)</span>
            </li>
          </ul>
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
const stats = ref({})

const currentDateStr = computed(() => {
  const d = new Date()
  const days = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy']
  const dayName = days[d.getDay()]
  const day = String(d.getDate()).padStart(2, '0')
  const month = String(d.getMonth() + 1).padStart(2, '0')
  const year = d.getFullYear()
  return `${dayName}, ${day}/${month}/${year}`
})

const fetchStats = async () => {
  try {
    const res = await axios.get('/api/admin/stats')
    if (res.data?.data) {
      stats.value = res.data.data
    }
  } catch (err) {
    console.error('Failed to load stats:', err)
  }
}

onMounted(() => {
  fetchStats()
})
</script>

<style scoped>
.letter-spacing-05 {
  letter-spacing: 0.5px;
}
.shadow-xs {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}
.hover-card {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.hover-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
}
</style>
