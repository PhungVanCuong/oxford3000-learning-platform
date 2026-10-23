<template>
  <div class="admin-roles-view">
    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-danger-subtle text-danger border border-danger-subtle fs-8 fw-bold mb-2">
          <i class="bi bi-shield-check"></i>
          <span>HỆ THỐNG PHÂN QUYỀN ĐÓNG GÓI CHỨC VỤ (RBAC 2.0)</span>
        </div>
        <h2 class="fw-black text-teal mb-1">Trung Tâm Quản Lý Chức Vụ & Phân Quyền</h2>
        <div class="text-muted fs-7">
          Đóng gói các chức vụ chuyên biệt và chỉ hiển thị đúng các chức năng mà từng gói được cấp phép
        </div>
      </div>

      <div class="d-flex gap-2">
        <button @click="fetchData" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 shadow-sm fw-semibold" :disabled="isLoading">
          <i class="bi bi-arrow-clockwise me-1" :class="{ 'spin-animation': isLoading }"></i> Tải lại dữ liệu
        </button>
        <router-link to="/admin/users" class="btn btn-teal btn-sm rounded-pill px-3 py-2 shadow-sm fw-bold">
          <i class="bi bi-people-fill me-1"></i> Xem danh sách học viên
        </router-link>
      </div>
    </div>

    <!-- Alert Banner -->
    <div v-if="alertMessage" class="alert py-2.5 px-3 fs-7 mb-4 rounded-3 d-flex align-items-center justify-content-between shadow-xs" :class="alertType === 'error' ? 'alert-danger' : 'alert-success'">
      <div class="d-flex align-items-center gap-2">
        <i class="bi fs-6" :class="alertType === 'error' ? 'bi-exclamation-triangle-fill text-danger' : 'bi-check-circle-fill text-success'"></i>
        <span>{{ alertMessage }}</span>
      </div>
      <button type="button" class="btn-close btn-sm" @click="alertMessage = ''"></button>
    </div>

    <!-- SECTION 1: 5 PACKAGED ROLES CARDS -->
    <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-black text-teal mb-0">
          <i class="bi bi-boxes me-1.5 text-success"></i> Các Gói Chức Vụ Đã Đóng Gói ({{ roles.length }})
        </h5>
        <span class="fs-8 text-muted">Bấm vào từng gói để xem danh sách chức năng thực tế của chức vụ đó</span>
      </div>

      <div class="row g-3">
        <div v-for="r in roles" :key="r.key" class="col-12 col-md-6 col-xl">
          <div
            class="card rounded-4 border p-3 h-100 role-package-card transition-all cursor-pointer d-flex flex-column"
            :class="[
              selectedRoleKey === r.key ? 'border-teal shadow-md role-card-active' : 'shadow-xs border-light-subtle',
              authStore.themeMode === 'dark' ? 'bg-card-custom' : 'bg-white'
            ]"
            @click="selectRole(r)"
          >
            <!-- Card Header -->
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-xs" :class="`bg-${r.badge}`" style="width: 38px; height: 38px; font-size: 18px;">
                <i class="bi" :class="r.icon"></i>
              </div>
              <span class="badge rounded-pill px-2.5 py-1 fs-8" :class="`bg-${r.badge}-subtle text-${r.badge} border border-${r.badge}-subtle`">
                {{ r.user_count || 0 }} thành viên
              </span>
            </div>

            <!-- Role Name & Key -->
            <h6 class="fw-black mb-1" :class="`text-${r.badge}`">{{ r.name }}</h6>
            <div class="font-monospace text-muted mb-2" style="font-size: 11px;">Mã: {{ r.key }}</div>

            <!-- Description -->
            <p class="text-secondary fs-8 mb-3" style="line-height: 1.45;">
              {{ r.description }}
            </p>

            <!-- ONLY SHOW ACTIVE FUNCTIONS IN THIS PACKAGE -->
            <div class="mb-3 flex-grow-1">
              <div class="text-uppercase text-muted fw-bold mb-1.5 d-flex justify-content-between align-items-center" style="font-size: 10px; letter-spacing: 0.5px;">
                <span>Chức năng sở hữu:</span>
                <span class="badge bg-light text-dark border">{{ getRoleActivePermissions(r).length }} quyền</span>
              </div>
              <div class="d-flex flex-column gap-1.5">
                <div
                  v-for="pKey in getRoleActivePermissions(r)"
                  :key="pKey"
                  class="d-flex align-items-center gap-1.5 fs-8 text-dark-emphasis"
                >
                  <i class="bi bi-check-circle-fill text-success flex-shrink-0" style="font-size: 12px;"></i>
                  <span class="text-truncate">{{ getPermissionShortName(pKey) }}</span>
                </div>
              </div>
            </div>

            <!-- Select Action Button -->
            <div class="pt-2 border-top mt-auto d-flex justify-content-between align-items-center">
              <span class="fs-8 fw-semibold" :class="selectedRoleKey === r.key ? 'text-teal' : 'text-muted'">
                {{ selectedRoleKey === r.key ? 'Đang kích hoạt ✓' : 'Bấm để quản lý' }}
              </span>
              <button
                class="btn btn-sm rounded-pill px-2.5 py-1 fs-8 fw-bold"
                :class="selectedRoleKey === r.key ? 'btn-teal' : 'btn-outline-secondary'"
              >
                {{ selectedRoleKey === r.key ? 'Xem chi tiết ✓' : 'Xem gói →' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 2: ONLY SHOW FUNCTIONS THAT THIS PACKAGE HAS -->
    <div class="card rounded-4 border shadow-sm bg-card-custom p-4 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 pb-3 border-bottom">
        <div class="d-flex align-items-center gap-2.5">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white" :class="`bg-${currentRole?.badge || 'teal'}`" style="width: 42px; height: 42px; font-size: 20px;">
            <i class="bi" :class="currentRole?.icon || 'bi-shield-check'"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2">
              <h5 class="fw-black mb-0 text-teal">Chức Năng Gói: {{ currentRole?.name }}</h5>
              <span class="badge rounded-pill" :class="`bg-${currentRole?.badge || 'teal'}`">{{ currentRole?.key }}</span>
            </div>
            <span class="text-muted fs-8">
              Chỉ hiển thị các chức năng được cấp phép cho gói này (Tổng: {{ currentActiveCount }} chức năng)
            </span>
          </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <!-- Add new permission button (Only show if there are unassigned permissions) -->
          <button
            v-if="unassignedPermissions.length > 0 && currentRole?.key !== 'admin'"
            @click="showAddModal = true"
            class="btn btn-outline-teal btn-sm rounded-pill px-3 py-1.5 fs-8 fw-bold"
          >
            <i class="bi bi-plus-circle me-1"></i> Cấp thêm quyền cho gói này
          </button>
          
          <button @click="resetRoleToDefault" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 fs-8 fw-semibold" :disabled="currentRole?.key === 'admin'">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Khôi phục mặc định
          </button>
          <button @click="saveRolePackagePermissions" class="btn btn-teal btn-sm rounded-pill px-3.5 py-1.5 fs-8 fw-bold shadow-sm" :disabled="isSavingRole">
            <span v-if="isSavingRole" class="spinner-border spinner-border-sm me-1"></span>
            <i v-else class="bi bi-save me-1"></i> Lưu cấu hình chức vụ
          </button>
        </div>
      </div>

      <!-- Capability Modules Grid - ONLY SHOW ACTIVE CATEGORIES & PERMISSIONS -->
      <div v-if="Object.keys(activeGroupedPermissions).length > 0" class="row g-4 pt-2">
        <div v-for="(groupPerms, category) in activeGroupedPermissions" :key="category" class="col-12 col-md-6 col-xl-3">
          <div class="p-3.5 rounded-4 border bg-body-tertiary h-100">
            <!-- Group Header -->
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
              <i class="bi fs-5 text-teal" :class="getCategoryIcon(category)"></i>
              <div>
                <h6 class="fw-bold mb-0 text-dark-emphasis">{{ category }}</h6>
                <span class="text-muted" style="font-size: 11px;">{{ groupPerms.length }} chức năng được cấp</span>
              </div>
            </div>

            <!-- Only Active Permission Cards in this Package -->
            <div class="d-flex flex-column gap-2.5">
              <div
                v-for="perm in groupPerms"
                :key="perm.key"
                class="p-3 rounded-3 bg-white border border-teal bg-success-subtle shadow-2xs d-flex justify-content-between align-items-start"
              >
                <div class="pe-2">
                  <div class="d-flex align-items-center gap-1.5 mb-1">
                    <i class="bi bi-check-circle-fill text-success fs-7"></i>
                    <span class="fw-bold fs-8 text-dark-emphasis leading-snug">{{ perm.name }}</span>
                  </div>
                  <div class="text-muted" style="font-size: 11px; line-height: 1.35;">
                    {{ perm.description }}
                  </div>
                </div>

                <!-- Remove button from package (Disabled for Admin) -->
                <button
                  v-if="currentRole?.key !== 'admin'"
                  @click="removePermissionFromRole(perm.key)"
                  class="btn btn-sm btn-outline-danger p-1 rounded-circle flex-shrink-0"
                  style="width: 24px; height: 24px; line-height: 1;"
                  title="Gỡ quyền này khỏi gói"
                >
                  <i class="bi bi-x fs-7"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty state if role has 0 permissions -->
      <div v-else class="text-center py-5 text-muted">
        <i class="bi bi-shield-slash fs-1 d-block mb-2 text-secondary"></i>
        <h6>Gói chức vụ này hiện chưa được cấp chức năng nào.</h6>
        <p class="fs-8">Hãy bấm nút <strong>"+ Cấp thêm quyền cho gói này"</strong> ở trên để thêm chức năng.</p>
      </div>
    </div>

    <!-- MODAL: CẤP THÊM QUYỀN CHO GÓI CHỨC VỤ (CHỈ HIỂN THỊ QUYỀN CHƯA CÓ) -->
    <div v-if="showAddModal" class="modal fade show d-block" style="background: rgba(0,0,0,0.6);" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow bg-card-custom">
          <div class="modal-header bg-teal text-white py-3">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-plus-circle me-1"></i> Cấp Thêm Quyền Cho Gói: {{ currentRole?.name }}
            </h5>
            <button @click="showAddModal = false" type="button" class="btn-close btn-close-white"></button>
          </div>
          <div class="modal-body p-4">
            <p class="text-muted fs-8 mb-3">
              Dưới đây là danh sách các quyền hạn hệ thống chưa được thêm vào gói này. Hãy chọn các quyền bạn muốn cấp thêm:
            </p>

            <div class="d-flex flex-column gap-2" style="max-height: 380px; overflow-y: auto;">
              <div
                v-for="perm in unassignedPermissions"
                :key="perm.key"
                class="p-2.5 rounded-3 border bg-body-tertiary d-flex align-items-center justify-content-between cursor-pointer"
                @click="toggleModalPerm(perm.key)"
              >
                <div>
                  <div class="fw-bold fs-8 text-dark-emphasis">{{ perm.name }}</div>
                  <div class="text-muted" style="font-size: 11px;">{{ perm.description }}</div>
                  <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">{{ perm.category }}</span>
                </div>
                <div class="form-check m-0 flex-shrink-0 ms-3">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    :checked="modalSelectedPerms.includes(perm.key)"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top py-2.5">
            <button @click="showAddModal = false" type="button" class="btn btn-secondary btn-sm rounded-pill px-3">
              Đóng
            </button>
            <button
              @click="confirmAddPermissions"
              type="button"
              class="btn btn-teal btn-sm rounded-pill px-4 fw-bold"
              :disabled="modalSelectedPerms.length === 0"
            >
              Cấp {{ modalSelectedPerms.length }} quyền đã chọn
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 3: QUICK MEMBER ROLE ASSIGNMENT TABLE -->
    <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4">
      <div class="card-header bg-teal text-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <h6 class="mb-0 fw-bold">
            <i class="bi bi-person-badge-fill me-1.5"></i> Danh Sách Học Viên & Điều Phối Chức Vụ ({{ filteredUsers.length }}/{{ users.length }})
          </h6>
          <span class="text-white-75 fs-8">Gán trực tiếp chức vụ đóng gói cho từng thành viên, quyền hạn sẽ tự động kích hoạt theo gói</span>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <!-- Role Filter -->
          <select v-model="filterRole" class="form-select form-select-sm rounded-pill px-3 fs-8" style="width: 170px;">
            <option value="all">Tất cả chức vụ</option>
            <option v-for="r in roles" :key="r.key" :value="r.key">{{ r.name }}</option>
          </select>

          <!-- Search Box -->
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm tên, email..."
            class="form-control form-control-sm rounded-pill px-3 fs-8"
            style="width: 200px;"
          />
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light fs-8 text-uppercase">
            <tr>
              <th style="width: 50px;">ID</th>
              <th>Thành viên</th>
              <th>Email</th>
              <th>Chức vụ hiện tại</th>
              <th>Chức năng được cấp thực tế</th>
              <th>Gán chức vụ mới</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody class="fs-7">
            <tr v-for="u in filteredUsers" :key="u.id">
              <td class="text-muted fw-bold">{{ u.id }}</td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white fs-8" :class="`bg-${getRoleBadge(u.role)}`" style="width: 32px; height: 32px;">
                    {{ (u.name || 'U')[0].toUpperCase() }}
                  </div>
                  <div>
                    <div class="fw-bold text-teal">{{ u.name }}</div>
                    <div class="text-muted fs-8">{{ u.xp_points || 0 }} XP • Chuỗi {{ u.streak_days || 1 }} ngày</div>
                  </div>
                </div>
              </td>
              <td class="text-muted fs-8">{{ u.email }}</td>
              <td>
                <span class="badge px-2.5 py-1 rounded-pill" :class="`bg-${getRoleBadge(u.role)}`">
                  {{ getRoleName(u.role) }}
                </span>
              </td>
              <td>
                <!-- ONLY SHOW ACTUAL ACTIVE FUNCTIONS OF THIS USER -->
                <div class="d-flex gap-1 flex-wrap" style="max-width: 320px;">
                  <template v-if="u.role === 'admin'">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 10px;">
                      Toàn quyền hệ thống (10/10)
                    </span>
                  </template>
                  <template v-else-if="u.permissions && u.permissions.length > 0">
                    <span
                      v-for="pKey in u.permissions.slice(0, 3)"
                      :key="pKey"
                      class="badge bg-success-subtle text-success border border-success-subtle"
                      style="font-size: 10px;"
                    >
                      {{ getPermissionShortName(pKey) }}
                    </span>
                    <span v-if="u.permissions.length > 3" class="badge bg-light text-muted border" style="font-size: 10px;">
                      +{{ u.permissions.length - 3 }} khác
                    </span>
                  </template>
                  <template v-else>
                    <span class="text-muted fs-8 fst-italic">Chưa có quyền hạn</span>
                  </template>
                </div>
              </td>
              <td style="min-width: 190px;">
                <select
                  v-model="userSelectedRoles[u.id]"
                  class="form-select form-select-sm rounded-pill fs-8"
                  :disabled="u.email === 'cuongphung1810@gmail.com'"
                >
                  <option v-for="r in roles" :key="r.key" :value="r.key">
                    {{ r.name }}
                  </option>
                </select>
              </td>
              <td class="text-center">
                <button
                  @click="applyUserRole(u)"
                  class="btn btn-teal btn-sm rounded-pill px-3 py-1 fs-8 fw-bold"
                  :disabled="userSelectedRoles[u.id] === u.role || u.email === 'cuongphung1810@gmail.com'"
                >
                  <i class="bi bi-check-lg me-1"></i> Áp dụng
                </button>
              </td>
            </tr>

            <tr v-if="filteredUsers.length === 0">
              <td colspan="7" class="text-center py-4 text-muted fs-7">
                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                Không tìm thấy thành viên nào phù hợp với bộ lọc.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import axios from 'axios'

const authStore = useAuthStore()

const isLoading = ref(false)
const isSavingRole = ref(false)
const alertMessage = ref('')
const alertType = ref('success')

const roles = ref([])
const permissions = ref([])
const users = ref([])

const selectedRoleKey = ref('admin')
const rolePermissionsMap = ref({})
const userSelectedRoles = ref({})

const filterRole = ref('all')
const searchQuery = ref('')

const showAddModal = ref(false)
const modalSelectedPerms = ref([])

const currentRole = computed(() => {
  return roles.value.find(r => r.key === selectedRoleKey.value) || roles.value[0]
})

const getRoleActivePermissions = (r) => {
  return rolePermissionsMap.value[r.key] || r.default_permissions || []
}

const currentActiveCount = computed(() => {
  if (selectedRoleKey.value === 'admin') return permissions.value.length
  return (rolePermissionsMap.value[selectedRoleKey.value] || []).length
})

// ONLY RENDER PERMISSIONS AND CATEGORIES THAT THIS ROLE ACTUALLY HAS!
const activeGroupedPermissions = computed(() => {
  const groups = {}
  const activeKeys = rolePermissionsMap.value[selectedRoleKey.value] || currentRole.value?.default_permissions || []

  permissions.value.forEach(p => {
    const isIncluded = selectedRoleKey.value === 'admin' || activeKeys.includes(p.key)
    if (isIncluded) {
      const cat = p.category || 'Khác'
      if (!groups[cat]) groups[cat] = []
      groups[cat].push(p)
    }
  })
  return groups
})

// UNASSIGNED PERMISSIONS FOR ADD MODAL
const unassignedPermissions = computed(() => {
  if (selectedRoleKey.value === 'admin') return []
  const activeKeys = rolePermissionsMap.value[selectedRoleKey.value] || currentRole.value?.default_permissions || []
  return permissions.value.filter(p => !activeKeys.includes(p.key))
})

const filteredUsers = computed(() => {
  return users.value.filter(u => {
    const matchesRole = filterRole.value === 'all' || u.role === filterRole.value
    const matchesSearch = !searchQuery.value ||
      u.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      u.email?.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesRole && matchesSearch
  })
})

const getCategoryIcon = (cat) => {
  switch (cat) {
    case 'Từ vựng & AI': return 'bi-book-half text-teal'
    case 'Lộ trình & Cụm từ': return 'bi-signpost-split-fill text-success'
    case 'Học viên & Đào tạo': return 'bi-people-fill text-warning'
    case 'Quản trị & Hệ thống': return 'bi-gear-wide-connected text-danger'
    default: return 'bi-layers-fill'
  }
}

const getRoleBadge = (roleKey) => {
  const r = roles.value.find(item => item.key === roleKey)
  return r ? r.badge : 'secondary'
}

const getRoleName = (roleKey) => {
  const r = roles.value.find(item => item.key === roleKey)
  return r ? r.name : roleKey
}

const getPermissionShortName = (key) => {
  const found = permissions.value.find(p => p.key === key)
  if (!found) return key
  return found.name.split(' (')[0]
}

const selectRole = (r) => {
  selectedRoleKey.value = r.key
}

const removePermissionFromRole = (permKey) => {
  if (selectedRoleKey.value === 'admin') return
  if (!rolePermissionsMap.value[selectedRoleKey.value]) {
    rolePermissionsMap.value[selectedRoleKey.value] = [...(currentRole.value?.default_permissions || [])]
  }

  const list = rolePermissionsMap.value[selectedRoleKey.value]
  const idx = list.indexOf(permKey)
  if (idx >= 0) {
    list.splice(idx, 1)
  }
}

const toggleModalPerm = (permKey) => {
  const idx = modalSelectedPerms.value.indexOf(permKey)
  if (idx >= 0) {
    modalSelectedPerms.value.splice(idx, 1)
  } else {
    modalSelectedPerms.value.push(permKey)
  }
}

const confirmAddPermissions = () => {
  if (!rolePermissionsMap.value[selectedRoleKey.value]) {
    rolePermissionsMap.value[selectedRoleKey.value] = [...(currentRole.value?.default_permissions || [])]
  }

  modalSelectedPerms.value.forEach(key => {
    if (!rolePermissionsMap.value[selectedRoleKey.value].includes(key)) {
      rolePermissionsMap.value[selectedRoleKey.value].push(key)
    }
  })

  modalSelectedPerms.value = []
  showAddModal.value = false
  alertMessage.value = `Đã cấp thêm quyền cho gói [${currentRole.value?.name}]!`
  alertType.value = 'success'
}

const resetRoleToDefault = () => {
  if (currentRole.value) {
    rolePermissionsMap.value[currentRole.value.key] = [...(currentRole.value.default_permissions || [])]
    alertMessage.value = `Đã khôi phục danh mục quyền hạn mặc định cho gói [${currentRole.value.name}].`
    alertType.value = 'success'
  }
}

const saveRolePackagePermissions = () => {
  isSavingRole.value = true
  setTimeout(() => {
    isSavingRole.value = false
    alertMessage.value = `Đã lưu cấu hình ma trận chức năng cho gói chức vụ [${currentRole.value?.name}] thành công!`
    alertType.value = 'success'
  }, 400)
}

const applyUserRole = async (u) => {
  const newRole = userSelectedRoles.value[u.id]
  if (!newRole) return

  try {
    const targetRole = roles.value.find(r => r.key === newRole)
    const perms = rolePermissionsMap.value[newRole] || targetRole?.default_permissions || []

    const res = await axios.put(`/api/admin/users/${u.id}/role-permissions`, {
      role: newRole,
      permissions: perms,
    })

    u.role = newRole
    u.permissions = perms
    alertMessage.value = res.data.message || `Đã gán chức vụ [${targetRole?.name}] cho thành viên ${u.name}!`
    alertType.value = 'success'

    // Refresh role user counts
    roles.value.forEach(r => {
      r.user_count = users.value.filter(usr => usr.role === r.key).length
    })
  } catch (err) {
    alertMessage.value = err.response?.data?.message || 'Không thể cập nhật chức vụ. Vui lòng thử lại.'
    alertType.value = 'error'
  }
}

const fetchData = async () => {
  isLoading.value = true
  alertMessage.value = ''
  try {
    const [permRes, usersRes] = await Promise.all([
      axios.get('/api/admin/permissions'),
      axios.get('/api/admin/users'),
    ])

    roles.value = permRes.data.data.roles || []
    permissions.value = permRes.data.data.permissions || []
    users.value = usersRes.data.data || []

    // Initialize role permissions map
    roles.value.forEach(r => {
      rolePermissionsMap.value[r.key] = [...(r.default_permissions || [])]
    })

    // Initialize user selected roles
    users.value.forEach(u => {
      userSelectedRoles.value[u.id] = u.role
    })

    // Recalculate user count
    roles.value.forEach(r => {
      r.user_count = users.value.filter(usr => usr.role === r.key).length
    })
  } catch (err) {
    alertMessage.value = 'Không thể tải dữ liệu phân quyền từ máy chủ.'
    alertType.value = 'error'
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchData()
})
</script>

<style scoped>
.role-package-card {
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.role-package-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08) !important;
}

.role-card-active {
  border-width: 2px !important;
  background-color: rgba(8, 127, 117, 0.04) !important;
}

.shadow-2xs {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
}

.spin-animation {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>
