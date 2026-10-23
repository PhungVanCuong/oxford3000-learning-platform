<template>
  <div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <span class="badge bg-danger text-white rounded-pill px-3 py-1 fs-8">PHÂN QUYỀN & CHỨC VỤ</span>
        <h2 class="fw-black text-teal mb-0">Quản Lý Học Viên & Danh Sách Quyền Hạn</h2>
        <div class="text-muted fs-7">Cấp vai trò, gán danh sách chức năng chi tiết và giám sát tiến độ của từng tài khoản</div>
      </div>

      <div class="d-flex gap-2 flex-wrap">
        <router-link v-if="authStore.canManageRoles" to="/admin/roles" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2 shadow-sm fw-bold">
          <i class="bi bi-shield-lock-fill me-1"></i> Quản lý Chức vụ & Đóng gói →
        </router-link>
        <button v-if="authStore.canManageUsers" @click="openCreateUserModal" class="btn btn-teal btn-sm rounded-pill px-3 py-2 shadow-sm fw-bold">
          <i class="bi bi-person-plus-fill me-1"></i> Thêm tài khoản mới
        </button>
      </div>
    </div>

    <!-- Available Roles & Permissions Summary Card -->
    <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
      <h6 class="fw-bold text-teal mb-2"><i class="bi bi-shield-check me-1 text-success"></i> Ma Trận Quyền Hạn Hệ Thống (RBAC)</h6>
      <div class="row g-3 fs-8">
        <div class="col-12 col-md-4">
          <div class="p-3 rounded-3 bg-danger-subtle border border-danger-subtle h-100">
            <span class="badge bg-danger text-white mb-1">Admin (Quản trị viên)</span>
            <div class="fw-bold text-danger">Toàn quyền hệ thống</div>
            <div class="text-muted">Quản trị toàn bộ từ điển, cụm từ, tài khoản, reset tiến độ và phân quyền.</div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="p-3 rounded-3 bg-warning-subtle border border-warning-subtle h-100">
            <span class="badge bg-warning text-dark mb-1">Moderator (Điều hành viên / Giáo viên)</span>
            <div class="fw-bold text-warning-emphasis">Quản lý nội dung học</div>
            <div class="text-muted">Biên tập từ vựng, tạo câu ví dụ qua AI, quản lý cụm từ và xem báo cáo.</div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="p-3 rounded-3 bg-secondary-subtle border border-secondary-subtle h-100">
            <span class="badge bg-secondary text-white mb-1">User (Học viên)</span>
            <div class="fw-bold text-secondary-emphasis">Học tập & Tích lũy tiến độ</div>
            <div class="text-muted">Truy cập toàn bộ 3000 từ, luyện nói, làm trắc nghiệm, tích điểm XP và chuỗi ngày.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4">
      <div class="card-header bg-teal text-white py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Danh Sách Học Viên & Trạng Thái Phân Quyền ({{ users.length }})</h6>
        <div class="d-flex gap-2">
          <input
            v-model="searchUser"
            type="text"
            placeholder="Tìm tên, email..."
            class="form-control form-control-sm rounded-pill px-3 fs-8"
            style="width: 220px;"
          />
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light fs-8 text-uppercase">
            <tr>
              <th style="width: 50px;">ID</th>
              <th>Họ & Tên</th>
              <th>Email</th>
              <th>Chức vụ (Role)</th>
              <th>Danh sách Quyền hạn (Permissions)</th>
              <th class="text-center">XP</th>
              <th class="text-center">Chuỗi</th>
              <th>Từ đã thuộc</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody class="fs-7">
            <tr v-for="u in filteredUsers" :key="u.id">
              <td class="text-muted fw-bold">{{ u.id }}</td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-teal text-white d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 28px; height: 28px;">
                    {{ (u.name || 'U')[0].toUpperCase() }}
                  </div>
                  <span class="fw-bold text-teal">{{ u.name }}</span>
                </div>
              </td>
              <td class="text-muted fs-8">{{ u.email }}</td>
              <td>
                <span class="badge px-2.5 py-1 rounded-pill" :class="
                  u.role === 'admin' ? 'bg-danger text-white' :
                  u.role === 'moderator' ? 'bg-warning text-dark' :
                  u.role === 'editor' ? 'bg-primary text-white' :
                  u.role === 'tutor' ? 'bg-info text-dark' : 'bg-secondary text-white'
                ">
                  {{
                    u.role === 'admin' ? 'Admin' :
                    u.role === 'moderator' ? 'Moderator' :
                    u.role === 'editor' ? 'Editor' :
                    u.role === 'tutor' ? 'Tutor' : 'Learner'
                  }}
                </span>
              </td>
              <td>
                <div class="d-flex gap-1 flex-wrap" style="max-width: 250px;">
                  <template v-if="u.role === 'admin'">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 10px;">Toàn quyền (All Permissions)</span>
                  </template>
                  <template v-else-if="u.permissions && u.permissions.length > 0">
                    <span v-for="perm in u.permissions" :key="perm" class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 10px;">
                      {{ permName(perm) }}
                    </span>
                  </template>
                  <template v-else>
                    <span class="text-muted fs-8 fst-italic">Quyền học viên mặc định</span>
                  </template>
                </div>
              </td>
              <td class="text-center fw-bold text-primary">{{ u.xp_points }}</td>
              <td class="text-center text-warning fw-bold">{{ u.streak_days }} ngày</td>
              <td>
                <div class="d-flex align-items-baseline gap-1">
                  <span class="fw-black text-success fs-6">{{ u.total_mastered_words ?? (u.mastered_words + (u.curriculum_remembered || 0) + (u.mastered_phrases || 0)) }}</span>
                  <span class="fs-8 text-muted fw-semibold">từ đã thuộc</span>
                </div>
                <div class="text-muted d-flex align-items-center gap-1 mt-0.5 flex-wrap" style="font-size: 11px;">
                  <span class="badge bg-light text-secondary border px-1.5 py-0.5">{{ u.mastered_words }} Oxford</span>
                  <span class="badge bg-light text-secondary border px-1.5 py-0.5">{{ u.curriculum_remembered || 0 }} Lộ trình</span>
                  <span class="badge bg-light text-secondary border px-1.5 py-0.5">{{ u.mastered_phrases || 0 }} Cụm từ</span>
                </div>
              </td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <button v-if="authStore.canManageRoles" @click="openRoleModal(u)" class="btn btn-outline-primary" title="Phân quyền chức vụ">
                    <i class="bi bi-shield-lock"></i>
                  </button>
                  <button v-if="authStore.hasPermission('reset_progress') || authStore.canManageUsers" @click="resetUserProgress(u)" class="btn btn-outline-warning" title="Reset toàn bộ quá trình học">
                    <i class="bi bi-arrow-counterclockwise"></i>
                  </button>
                  <button v-if="authStore.canManageUsers" @click="deleteUser(u.id)" class="btn btn-outline-danger" title="Xóa tài khoản" :disabled="u.email === 'cuongphung1810@gmail.com'">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- MODAL: PHÂN QUYỀN & CHỨC VỤ -->
    <div v-if="selectedUserForRole" class="modal fade show d-block" style="background: rgba(0,0,0,0.6);" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow bg-card-custom">
          <div class="modal-header bg-teal text-white py-3">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-shield-lock-fill me-1"></i> Phân Quyền Cho: {{ selectedUserForRole.name }}
            </h5>
            <button @click="selectedUserForRole = null" type="button" class="btn-close btn-close-white"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fs-7 fw-bold mb-1">Chọn Chức Vụ (Role):</label>
              <select v-model="roleForm.role" class="form-select rounded-3 fs-7">
                <option value="admin">Quản trị viên Cấp cao (Super Admin) - Toàn quyền</option>
                <option value="moderator">Quản trị nội dung (Content Moderator)</option>
                <option value="editor">Biên tập viên (Vocabulary Editor)</option>
                <option value="tutor">Trợ giảng / Giáo viên (Tutor / Instructor)</option>
                <option value="user">Học viên tiêu chuẩn (Standard Learner)</option>
              </select>
            </div>

            <div v-if="roleForm.role !== 'admin'" class="mb-3">
              <label class="form-label fs-7 fw-bold mb-2">Danh Sách Quyền Hạn Chi Tiết:</label>
              <div class="d-flex flex-column gap-2">
                <div v-for="p in availablePermissions" :key="p.key" class="form-check">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    :value="p.key"
                    v-model="roleForm.permissions"
                    :id="'perm_' + p.key"
                  />
                  <label class="form-check-label fs-7" :for="'perm_' + p.key">
                    <strong>{{ p.name }}</strong>
                    <span class="badge bg-secondary-subtle text-secondary ms-1 fs-8">{{ p.category }}</span>
                  </label>
                </div>
              </div>
            </div>

            <div v-else class="alert alert-info py-2 px-3 fs-8 mb-0 rounded-3">
              <i class="bi bi-info-circle-fill me-1"></i> Quản trị viên (Admin) tự động được cấp toàn bộ quyền trong hệ thống.
            </div>
          </div>
          <div class="modal-footer border-top py-2.5">
            <button @click="selectedUserForRole = null" type="button" class="btn btn-secondary btn-sm rounded-pill px-3">
              Hủy
            </button>
            <button @click="saveRoleAndPermissions" type="button" class="btn btn-teal btn-sm rounded-pill px-4 fw-bold">
              Lưu Phân Quyền
            </button>
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

const users = ref([])
const searchUser = ref('')
const selectedUserForRole = ref(null)
const roleForm = ref({
  role: 'user',
  permissions: [],
})

const availablePermissions = [
  { key: 'manage_vocab', name: 'Quản lý kho từ vựng (Thêm, Sửa, Xóa & AI)', category: 'Từ vựng' },
  { key: 'manage_phrases', name: 'Quản lý cụm từ & Phrasal Verbs', category: 'Cụm từ' },
  { key: 'manage_users', name: 'Quản lý tài khoản & Phân quyền', category: 'Hệ thống' },
  { key: 'reset_progress', name: 'Quyền Reset tiến độ học viên', category: 'Học viên' },
  { key: 'view_reports', name: 'Xem thống kê toàn hệ thống', category: 'Báo cáo' },
]

const filteredUsers = computed(() => {
  if (!searchUser.value.trim()) return users.value
  const q = searchUser.value.toLowerCase().trim()
  return users.value.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q))
})

const permName = (key) => {
  const found = availablePermissions.find(p => p.key === key)
  return found ? found.name.split(' (')[0] : key
}

const fetchUsers = async () => {
  try {
    const res = await axios.get('/api/admin/users')
    users.value = res.data.data || []
  } catch (err) {
    console.error('Failed to load users:', err)
  }
}

const openRoleModal = (user) => {
  selectedUserForRole.value = user
  roleForm.value = {
    role: user.role || 'user',
    permissions: Array.isArray(user.permissions) ? [...user.permissions] : [],
  }
}

const saveRoleAndPermissions = async () => {
  if (!selectedUserForRole.value) return
  try {
    const payload = {
      role: roleForm.value.role,
      permissions: roleForm.value.permissions,
    }
    const res = await axios.put(`/api/admin/users/${selectedUserForRole.value.id}/role-permissions`, payload)
    alert(res.data.message || 'Cập nhật phân quyền thành công!')
    selectedUserForRole.value = null
    fetchUsers()
  } catch (err) {
    alert('Lỗi: ' + (err.response?.data?.message || err.message))
  }
}

const resetUserProgress = async (user) => {
  if (confirm(`Bạn có chắc chắn muốn RESET toàn bộ quá trình học tập của học viên "${user.name}" không? Toàn bộ từ đã nhớ, chuỗi ngày và XP sẽ được làm lại từ đầu.`)) {
    try {
      const res = await axios.post(`/api/admin/users/${user.id}/reset-progress`)
      alert(res.data.message || 'Đã reset tiến độ học tập thành công!')
      fetchUsers()
    } catch (err) {
      alert('Lỗi: ' + (err.response?.data?.message || err.message))
    }
  }
}

const deleteUser = async (id) => {
  if (confirm('Bạn có chắc muốn xóa vĩnh viễn tài khoản này?')) {
    try {
      await axios.delete(`/api/admin/users/${id}`)
      fetchUsers()
    } catch (err) {
      alert('Không thể xóa tài khoản này.')
    }
  }
}

const openCreateUserModal = () => {
  const name = prompt('Nhập họ tên học viên:')
  const email = prompt('Nhập email học viên:')
  if (name && email) {
    axios.post('/api/admin/users', {
      name,
      email,
      password: 'password123',
      role: 'user',
      words_per_day: 15,
    }).then(() => {
      alert('Tạo tài khoản học viên thành công! Mật khẩu khởi tạo: password123 (yêu cầu đổi mật khẩu sau khi đăng nhập)')
      fetchUsers()
    }).catch(err => {
      alert('Lỗi: ' + (err.response?.data?.message || err.message))
    })
  }
}

onMounted(() => {
  fetchUsers()
})
</script>
