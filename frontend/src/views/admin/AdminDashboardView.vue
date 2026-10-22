<template>
  <div>
    <!-- 1. Welcome Banner Header (Matches 03_admin_s_ng_chu_n_ho\code.html) -->
    <div class="card p-4 rounded-4 border shadow-sm bg-card-custom mb-4" style="border-color: #E2E8F0;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge px-3 py-1 rounded-pill fw-bold fs-8" style="background-color: #E6F7F3; color: #087F75;">
              ● HỆ THỐNG ĐANG VẬN HÀNH ỔN ĐỊNH
            </span>
            <span class="text-muted fs-8">Máy chủ Laravel API cổng 8000</span>
          </div>
          <h2 class="fw-black text-dark mb-1">Chào mừng trở lại, {{ authStore.userName }}! 👋</h2>
          <p class="text-muted fs-7 mb-0">
            Hôm nay là <strong>{{ currentDateStr }}</strong>. Dưới đây là báo cáo thống kê tình hình học tập và dữ liệu toàn hệ thống.
          </p>
        </div>

        <div class="d-flex align-items-center gap-2">
          <router-link to="/admin/users" class="btn btn-teal text-white rounded-pill px-4 py-2.5 fw-bold shadow-sm d-flex align-items-center gap-2 fs-7">
            <i class="bi bi-person-plus-fill"></i>
            <span>Thêm Học Viên Mới</span>
          </router-link>
        </div>
      </div>
    </div>

    <!-- 2. Four Stat Cards Row (Matches 03_admin_s_ng_chu_n_ho) -->
    <div class="row g-3 mb-4">
      <!-- Card 1: Tổng học viên -->
      <div class="col-6 col-lg-3">
        <div class="card p-3.5 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 text-uppercase fw-bold">Tổng học viên</span>
            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center text-primary" style="background-color: #eff6ff; width: 38px; height: 38px;">
              <i class="bi bi-people-fill fs-5"></i>
            </div>
          </div>
          <div class="fs-2 fw-black text-teal">{{ stats.total_users || 0 }}</div>
          <div class="text-muted fs-8 mt-1">Đã cấp tài khoản trên hệ thống</div>
        </div>
      </div>

      <!-- Card 2: Kho 3000 từ vựng -->
      <div class="col-6 col-lg-3">
        <div class="card p-3.5 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 text-uppercase fw-bold">Kho từ vựng</span>
            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center text-success" style="background-color: #E6F7F3; width: 38px; height: 38px;">
              <i class="bi bi-journal-check fs-5"></i>
            </div>
          </div>
          <div class="fs-2 fw-black text-teal">{{ stats.total_words || 3351 }}</div>
          <div class="text-muted fs-8 mt-1">Từ điển chuẩn Oxford 3000™</div>
        </div>
      </div>

      <!-- Card 3: Cụm từ & Lộ trình -->
      <div class="col-6 col-lg-3">
        <div class="card p-3.5 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 text-uppercase fw-bold">Cụm từ & Lộ trình</span>
            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center text-info" style="background-color: #ecfeff; width: 38px; height: 38px;">
              <i class="bi bi-diagram-3 fs-5"></i>
            </div>
          </div>
          <div class="fs-2 fw-black text-teal">{{ stats.total_topics || 25 }} chủ đề</div>
          <div class="text-muted fs-8 mt-1">12 Thì ngữ pháp & Phrasal Verbs</div>
        </div>
      </div>

      <!-- Card 4: Lượt thi trắc nghiệm -->
      <div class="col-6 col-lg-3">
        <div class="card p-3.5 rounded-4 shadow-sm border bg-card-custom h-100 hover-card" style="border-color: #E2E8F0;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 text-uppercase fw-bold">Lượt kiểm tra</span>
            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center text-warning" style="background-color: #fffbeb; width: 38px; height: 38px;">
              <i class="bi bi-patch-check-fill fs-5"></i>
            </div>
          </div>
          <div class="fs-2 fw-black text-primary">{{ stats.total_quizzes || 0 }}</div>
          <div class="text-muted fs-8 mt-1">Lần nộp bài trắc nghiệm 100%</div>
        </div>
      </div>
    </div>

    <!-- 3. Top Learners Table & Quick Actions Section -->
    <div class="row g-4">
      <!-- Bảng Top Học Viên -->
      <div class="col-12 col-lg-8">
        <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden h-100" style="border-color: #E2E8F0;">
          <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
              <i class="bi bi-trophy-fill text-warning"></i>
              <span>Bảng Vinh Danh Top Học Viên Tích Cực Nhất</span>
            </h6>
            <router-link to="/admin/users" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-8 fw-semibold">
              Quản lý học viên →
            </router-link>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light fs-8 text-uppercase">
                <tr>
                  <th style="width: 40px;">#</th>
                  <th>Học viên</th>
                  <th>Email</th>
                  <th class="text-center">Chuỗi học</th>
                  <th class="text-center">Từ đã nhớ</th>
                  <th class="text-end">Điểm tích lũy</th>
                </tr>
              </thead>
              <tbody class="fs-7">
                <tr v-for="(learner, idx) in stats.top_learners || []" :key="learner.id">
                  <td class="fw-bold text-muted">{{ idx + 1 }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 28px; height: 28px; background-color: #00b074;">
                        {{ (learner.name || 'H')[0].toUpperCase() }}
                      </div>
                      <span class="fw-bold text-teal">{{ learner.name }}</span>
                    </div>
                  </td>
                  <td class="text-muted fs-8">{{ learner.email }}</td>
                  <td class="text-center text-warning fw-bold">🔥 {{ learner.streak_days }} ngày</td>
                  <td class="text-center text-success fw-bold">{{ learner.mastered_count }} từ</td>
                  <td class="text-end fw-black text-primary">{{ learner.xp_points }} XP</td>
                </tr>
                <tr v-if="!stats.top_learners || stats.top_learners.length === 0">
                  <td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu học viên.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Quick Shortcuts -->
      <div class="col-12 col-lg-4">
        <div class="card p-4 rounded-4 border shadow-sm bg-card-custom h-100" style="border-color: #E2E8F0;">
          <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <span>Thao Tác Quản Trị Thường Dùng</span>
          </h6>

          <div class="d-flex flex-column gap-2.5">
            <router-link to="/admin/users" class="card p-3 rounded-3 border text-decoration-none hover-card text-dark d-flex align-items-center justify-content-between">
              <div>
                <div class="fw-bold fs-7 text-teal">Phân Quyền Chức Vụ (RBAC)</div>
                <div class="fs-8 text-muted">Cấp vai trò Admin / Moderator / User và quyền hạn</div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </router-link>

            <router-link to="/admin/vocabularies" class="card p-3 rounded-3 border text-decoration-none hover-card text-dark d-flex align-items-center justify-content-between">
              <div>
                <div class="fw-bold fs-7 text-teal">Quản Lý 3000 Từ & Gemini AI</div>
                <div class="fs-8 text-muted">Thêm mới, sửa từ, dùng AI tự động tạo câu ví dụ</div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </router-link>

            <router-link to="/admin/phrasal-verbs" class="card p-3 rounded-3 border text-decoration-none hover-card text-dark d-flex align-items-center justify-content-between">
              <div>
                <div class="fw-bold fs-7 text-teal">Quản Lý Cụm Từ & Phrasal Verbs</div>
                <div class="fs-8 text-muted">Cấu hình đồng bộ tiến độ các từ đơn tự động</div>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </router-link>

            <router-link to="/" class="btn btn-outline-secondary rounded-pill py-2 text-start d-flex align-items-center justify-content-between mt-2 fs-7">
              <span class="d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-up-right text-teal"></i>
                <span>Xem giao diện phía Người Học</span>
              </span>
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
    stats.value = res.data.data
  } catch (err) {
    console.error('Failed to load stats:', err)
  }
}

onMounted(() => {
  fetchStats()
})
</script>
