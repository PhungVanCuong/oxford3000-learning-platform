<template>
  <div class="arena-lobby-page py-3 py-md-4">
    <div class="container max-w-6xl">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-2.5">
        <ol class="breadcrumb fs-8 mb-0">
          <li class="breadcrumb-item"><router-link to="/" class="text-decoration-none text-muted">Trang chủ</router-link></li>
          <li class="breadcrumb-item active text-teal fw-bold" aria-current="page">Đấu trường Quiz & Bảng Xếp Hạng Liên Server</li>
        </ol>
      </nav>

      <!-- League Master Header: Banner Liên Server -->
      <div class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-3.5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #091e2b 0%, #064e40 50%, #087F75 100%);">
        <div class="position-relative z-1 text-white">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
              <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                <span class="badge rounded-pill px-2.5 py-1 fw-bold fs-8 shadow-xs" style="background: rgba(94, 234, 212, 0.2); color: #5eead4;">
                  <i class="bi bi-globe-americas me-1"></i> LIÊN SERVER TOÀN HỆ THỐNG
                </span>
                <span class="badge rounded-pill px-2.5 py-1 fw-bold fs-8 bg-warning text-dark shadow-xs">
                  🏆 MÙA 1 - 2026: CHIẾN THẦN TRI THỨC
                </span>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2 py-0.5 fs-8">
                  Cụm Global VN-01
                </span>
              </div>
              <h2 class="fw-black mb-1 fs-3">Đấu Trường Trắc Nghiệm & Bảng Vàng Cao Thủ</h2>
              <p class="text-white-50 fs-8 mb-0 max-w-2xl">
                Bảng xếp hạng trực tiếp đồng bộ toàn bộ học viên. Thi đua phản xạ trắc nghiệm realtime, tích lũy điểm XP, bảo vệ chuỗi ngày và chinh phục danh hiệu Đại Cao Thủ!
              </p>
            </div>

            <!-- Mini stats counter -->
            <div class="d-flex gap-2 shrink-0">
              <div class="px-3 py-2 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(4px);">
                <div class="fs-8 text-white-50">Học viên</div>
                <div class="fw-black fs-6 text-white">{{ totalLearners }}</div>
              </div>
              <div class="px-3 py-2 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(4px);">
                <div class="fs-8 text-white-50">Tổng XP</div>
                <div class="fw-black fs-6 text-warning">{{ (totalSystemXp / 1000).toFixed(1) }}k</div>
              </div>
              <div class="px-3 py-2 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(4px);">
                <div class="fs-8 text-white-50">Phòng mở</div>
                <div class="fw-black fs-6 text-success">{{ rooms.length }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Navigation Tabs: Bảng Xếp Hạng vs Phòng Thi Đấu -->
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2.5 mb-3.5 border-bottom pb-2.5">
        <ul class="nav nav-pills gap-2 fs-7 fw-bold" role="tablist">
          <li class="nav-item">
            <button
              class="nav-link rounded-pill px-3.5 py-1.5 d-flex align-items-center gap-2 transition-all"
              :class="activeTab === 'leaderboard' ? 'active bg-teal text-white shadow-sm' : 'text-secondary bg-body-tertiary'"
              @click="switchTab('leaderboard')"
            >
              <i class="bi bi-trophy-fill text-warning"></i>
              <span>Bảng Xếp Hạng Liên Server</span>
              <span class="badge bg-white text-dark rounded-pill px-2 py-0.5 fs-8" v-if="totalLearners">
                {{ totalLearners }}
              </span>
            </button>
          </li>
          <li class="nav-item">
            <button
              class="nav-link rounded-pill px-3.5 py-1.5 d-flex align-items-center gap-2 transition-all"
              :class="activeTab === 'rooms' ? 'active bg-teal text-white shadow-sm' : 'text-secondary bg-body-tertiary'"
              @click="switchTab('rooms')"
            >
              <i class="bi bi-controller"></i>
              <span>Phòng Thi Đấu Realtime</span>
              <span class="badge bg-white text-dark rounded-pill px-2 py-0.5 fs-8" v-if="rooms.length">
                {{ rooms.length }}
              </span>
            </button>
          </li>
        </ul>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center gap-2">
          <button
            v-if="activeTab === 'rooms'"
            @click="openCreateModal"
            class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8 shadow-sm d-flex align-items-center gap-1.5"
          >
            <i class="bi bi-plus-circle-fill"></i>
            <span>Mở phòng thi mới</span>
          </button>
          <router-link to="/tree" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 fw-bold fs-8 d-flex align-items-center gap-1.5">
            <i class="bi bi-tree-fill text-success"></i>
            <span>Cây Tri Thức</span>
          </router-link>
        </div>
      </div>

      <!-- ================= TAB 1: BẢNG XẾP HẠNG LIÊN SERVER ================= -->
      <div v-if="activeTab === 'leaderboard'">
        <!-- Filter & Search Toolbar (Small & Crisp) -->
        <div class="card p-2.5 p-md-3 rounded-4 border shadow-2xs bg-card-custom mb-3">
          <div class="d-flex flex-column flex-lg-row justify-content-between align-items-stretch align-items-lg-center gap-2.5">
            <!-- 4 Metric Filter Pills -->
            <div class="d-flex flex-wrap gap-1.5">
              <button
                v-for="ft in leaderboardFilters"
                :key="ft.type"
                @click="changeLeaderboardType(ft.type)"
                class="btn btn-sm rounded-pill px-3 py-1.5 fs-8 fw-bold transition-all"
                :class="currentLeaderboardType === ft.type ? 'btn-teal text-white shadow-xs' : 'btn-outline-secondary'"
              >
                <i :class="ft.icon" class="me-1"></i>
                {{ ft.label }}
              </button>
            </div>

            <!-- Search & Tier Filter -->
            <div class="d-flex flex-wrap align-items-center gap-2">
              <!-- Quick Search Input -->
              <div class="input-group input-group-sm" style="max-width: 240px;">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input
                  type="text"
                  v-model="searchQuery"
                  @keyup.enter="loadLeaderboard"
                  class="form-control border-start-0 fs-8"
                  placeholder="Tìm tên, email..."
                />
                <button @click="loadLeaderboard" class="btn btn-outline-secondary fs-8">Tìm</button>
              </div>

              <!-- Tier Dropdown -->
              <select v-model="selectedTier" @change="loadLeaderboard" class="form-select form-select-sm fs-8 rounded-pill" style="width: auto;">
                <option value="all">Tất cả cấp bậc</option>
                <option value="grandmaster">💎 Đại Cao Thủ (≥5000 XP)</option>
                <option value="diamond">💠 Kim Cương (3000-4999 XP)</option>
                <option value="platinum">🏅 Bạch Kim (1500-2999 XP)</option>
                <option value="gold">🌟 Vàng (800-1499 XP)</option>
                <option value="silver">🥈 Bạc (300-799 XP)</option>
                <option value="bronze">🥉 Đồng (<300 XP)</option>
              </select>

              <!-- View All Toggle -->
              <button
                @click="toggleViewAll"
                class="btn btn-sm rounded-pill px-2.5 py-1 fs-8 fw-semibold"
                :class="viewAll ? 'btn-warning text-dark' : 'btn-outline-secondary'"
                title="Tính trực tiếp hiển thị tất cả danh sách người học"
              >
                {{ viewAll ? 'Hiện 50/trang' : 'Tất cả (' + totalLearners + ')' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoadingLeaderboard" class="text-center py-5">
          <div class="spinner-border text-teal" role="status"></div>
          <div class="text-muted fs-7 mt-2">Đang tải bảng xếp hạng liên server...</div>
        </div>

        <div v-else>
          <!-- COMPACT REFINED TOP 3 PODIUM -->
          <div v-if="leaderboardData.length >= 3 && currentPage === 1 && !searchQuery" class="row g-2.5 mb-3.5">
            <!-- 2nd Place (Silver) -->
            <div class="col-12 col-md-4 order-2 order-md-1">
              <div class="card p-3 rounded-4 border bg-card-custom h-100 shadow-2xs d-flex flex-row align-items-center gap-3 position-relative overflow-hidden border-secondary-subtle">
                <div class="position-relative shrink-0">
                  <img :src="leaderboardData[1].avatar_url" class="rounded-circle border border-2 border-secondary" style="width: 52px; height: 52px; object-fit: cover;" />
                  <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-secondary text-white rounded-pill fs-8 shadow-2xs" style="font-size: 10px;">🥈 #2</span>
                </div>
                <div class="flex-grow-1 min-w-0">
                  <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
                    <span class="fw-bold fs-7 text-dark-emphasis text-truncate">{{ leaderboardData[1].name }}</span>
                    <span v-if="leaderboardData[1].trend > 0" class="badge bg-success-subtle text-success fs-8">▲+{{ leaderboardData[1].trend }}</span>
                  </div>
                  <div class="badge rounded-pill fs-8 mb-1" :class="leaderboardData[1].tier_class">
                    {{ leaderboardData[1].tier_badge }} {{ leaderboardData[1].tier_name }}
                  </div>
                  <div class="fw-black text-dark fs-6">{{ formatMetricValue(leaderboardData[1]) }}</div>
                </div>
              </div>
            </div>

            <!-- 1st Place (Gold Champion) -->
            <div class="col-12 col-md-4 order-1 order-md-2">
              <div class="card p-3 rounded-4 border border-warning bg-card-custom h-100 shadow-sm d-flex flex-row align-items-center gap-3 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(254, 243, 199, 0.4) 0%, rgba(253, 224, 71, 0.15) 100%);">
                <div class="position-relative shrink-0 text-center">
                  <div class="fs-5 leading-none mb-n1">👑</div>
                  <img :src="leaderboardData[0].avatar_url" class="rounded-circle border border-3 border-warning shadow-xs" style="width: 58px; height: 58px; object-fit: cover;" />
                  <span class="badge bg-warning text-dark rounded-pill fs-8 fw-bold position-absolute bottom-0 start-50 translate-middle-x shadow-2xs" style="font-size: 10px;">🥇 Quán Quân</span>
                </div>
                <div class="flex-grow-1 min-w-0">
                  <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
                    <span class="fw-black fs-6 text-dark-emphasis text-truncate">{{ leaderboardData[0].name }}</span>
                    <span class="badge bg-warning text-dark fs-8">#1 TOP</span>
                  </div>
                  <div class="badge rounded-pill fs-8 mb-1" :class="leaderboardData[0].tier_class">
                    {{ leaderboardData[0].tier_badge }} {{ leaderboardData[0].tier_name }}
                  </div>
                  <div class="fw-black text-warning-emphasis fs-5">{{ formatMetricValue(leaderboardData[0]) }}</div>
                </div>
              </div>
            </div>

            <!-- 3rd Place (Bronze) -->
            <div class="col-12 col-md-4 order-3 order-md-3">
              <div class="card p-3 rounded-4 border bg-card-custom h-100 shadow-2xs d-flex flex-row align-items-center gap-3 position-relative overflow-hidden border-danger-subtle">
                <div class="position-relative shrink-0">
                  <img :src="leaderboardData[2].avatar_url" class="rounded-circle border border-2 border-danger-subtle" style="width: 52px; height: 52px; object-fit: cover;" />
                  <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-danger-subtle text-danger rounded-pill fs-8 shadow-2xs" style="font-size: 10px;">🥉 #3</span>
                </div>
                <div class="flex-grow-1 min-w-0">
                  <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
                    <span class="fw-bold fs-7 text-dark-emphasis text-truncate">{{ leaderboardData[2].name }}</span>
                    <span v-if="leaderboardData[2].trend !== 0" class="badge" :class="leaderboardData[2].trend > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'">
                      {{ leaderboardData[2].trend > 0 ? '▲+' : '▼' }}{{ leaderboardData[2].trend }}
                    </span>
                  </div>
                  <div class="badge rounded-pill fs-8 mb-1" :class="leaderboardData[2].tier_class">
                    {{ leaderboardData[2].tier_badge }} {{ leaderboardData[2].tier_name }}
                  </div>
                  <div class="fw-black text-dark fs-6">{{ formatMetricValue(leaderboardData[2]) }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- CURRENT USER PERSONAL RANK CARD (Compact & Sleek) -->
          <div v-if="myRankData" class="card p-2.5 p-md-3 rounded-4 border border-teal bg-card-custom mb-3.5 shadow-2xs" style="background: linear-gradient(135deg, rgba(8, 127, 117, 0.04) 0%, rgba(20, 184, 166, 0.08) 100%);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2.5">
              <div class="d-flex align-items-center gap-2.5">
                <!-- Rank Pill -->
                <div class="rounded-pill bg-teal text-white px-3 py-1 text-center fw-black shadow-xs shrink-0 d-flex align-items-center gap-1.5">
                  <span class="fs-8 text-white-50">HẠNG</span>
                  <span class="fs-6">#{{ myRankData.rank }}</span>
                </div>

                <img :src="myRankData.avatar_url" class="rounded-circle border border-2 border-teal" style="width: 40px; height: 40px; object-fit: cover;" />
                <div>
                  <div class="d-flex align-items-center gap-1.5">
                    <span class="fw-bold text-dark-emphasis fs-7">{{ myRankData.name }}</span>
                    <span class="badge bg-teal text-white rounded-pill fs-8" style="font-size: 9px;">BẠN</span>
                    <span class="badge rounded-pill fs-8" :class="myRankData.tier_class">
                      {{ myRankData.tier_badge }} {{ myRankData.tier_name }}
                    </span>
                  </div>
                  <div class="text-muted fs-8">Trong tổng số {{ totalLearners }} học viên liên server</div>
                </div>
              </div>

              <!-- Compact Stats Chips -->
              <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-white text-dark border px-2.5 py-1.5 rounded-pill fs-8 shadow-2xs">
                  🌟 XP: <strong class="text-warning">{{ myRankData.xp_points }}</strong>
                </span>
                <span class="badge bg-white text-dark border px-2.5 py-1.5 rounded-pill fs-8 shadow-2xs">
                  🔥 Chuỗi: <strong class="text-danger">{{ myRankData.streak_days }} ngày</strong>
                </span>
                <span class="badge bg-white text-dark border px-2.5 py-1.5 rounded-pill fs-8 shadow-2xs">
                  ⚔️ Đấu trường: <strong class="text-teal">{{ myRankData.arena_score }} XP</strong>
                </span>
                <span class="badge bg-white text-dark border px-2.5 py-1.5 rounded-pill fs-8 shadow-2xs">
                  📚 Nắm vững: <strong class="text-primary">{{ myRankData.mastered_words }} từ</strong>
                </span>
              </div>
            </div>
          </div>

          <!-- FULL ALL-LEARNERS LEADERBOARD TABLE -->
          <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-3.5">
            <div class="card-header bg-body-tertiary border-bottom py-2.5 px-3.5 d-flex justify-content-between align-items-center">
              <div>
                <h6 class="fw-bold mb-0 text-dark-emphasis fs-7">
                  <i class="bi bi-list-ol text-teal me-1"></i> Bảng Xếp Hạng Học Viên Liên Server ({{ filteredCount }})
                </h6>
              </div>
              <div class="fs-8 text-muted">
                Cập nhật trực tiếp thời gian thực
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0 fs-7">
                <thead class="table-light fs-8 text-uppercase text-muted">
                  <tr>
                    <th style="width: 65px;" class="text-center ps-3">Hạng</th>
                    <th style="width: 45px;" class="text-center">Xu hướng</th>
                    <th>Học viên</th>
                    <th class="text-center">Cấp bậc</th>
                    <th class="text-center">Chuỗi ngày 🔥</th>
                    <th class="text-center">Từ nắm vững 📚</th>
                    <th class="text-center">Đấu trường ⚔️</th>
                    <th class="text-center">Cụm Server</th>
                    <th class="text-end pe-3.5">Tổng XP 🌟</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="u in leaderboardData"
                    :key="u.user_id"
                    :class="u.is_current_user ? 'table-teal-highlight' : ''"
                  >
                    <!-- Rank -->
                    <td class="text-center ps-3 fw-black">
                      <span v-if="u.rank === 1" class="badge bg-warning text-dark fs-8 rounded-pill px-2 py-0.5 shadow-2xs">🥇 1</span>
                      <span v-else-if="u.rank === 2" class="badge bg-secondary text-white fs-8 rounded-pill px-2 py-0.5 shadow-2xs">🥈 2</span>
                      <span v-else-if="u.rank === 3" class="badge bg-danger-subtle text-danger fs-8 rounded-pill px-2 py-0.5 shadow-2xs">🥉 3</span>
                      <span v-else class="text-muted fw-bold">#{{ u.rank }}</span>
                    </td>

                    <!-- Trend -->
                    <td class="text-center">
                      <span v-if="u.trend > 0" class="badge bg-success-subtle text-success fs-8 rounded-pill px-1.5 py-0.5">▲+{{ u.trend }}</span>
                      <span v-else-if="u.trend < 0" class="badge bg-danger-subtle text-danger fs-8 rounded-pill px-1.5 py-0.5">▼{{ u.trend }}</span>
                      <span v-else class="text-muted fs-8 font-monospace">—</span>
                    </td>

                    <!-- User Name & Avatar -->
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img :src="u.avatar_url" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;" />
                        <div>
                          <div class="fw-bold text-dark-emphasis d-flex align-items-center gap-1.5 fs-7">
                            <span>{{ u.name }}</span>
                            <span v-if="u.is_current_user" class="badge bg-teal text-white rounded-pill" style="font-size: 8px;">Bạn</span>
                            <span v-if="u.role === 'admin'" class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 8px;">Admin</span>
                            <span v-else-if="u.role === 'tutor'" class="badge bg-info-subtle text-info rounded-pill" style="font-size: 8px;">Trợ giảng</span>
                          </div>
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
                    <td class="text-center text-muted fs-8">
                      {{ u.mastered_words }} từ
                    </td>

                    <!-- Arena Score -->
                    <td class="text-center">
                      <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2 py-0.5 fs-8">
                        {{ u.arena_score }} XP ({{ u.arena_matches }} trận)
                      </span>
                    </td>

                    <!-- Cluster -->
                    <td class="text-center">
                      <span class="badge bg-secondary-subtle text-secondary rounded-pill fs-8">
                        {{ u.server_cluster || 'Global VN-01' }}
                      </span>
                    </td>

                    <!-- Total XP -->
                    <td class="text-end pe-3.5 fw-black text-warning fs-6">
                      {{ u.xp_points.toLocaleString() }} XP
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination Bar -->
            <div v-if="!viewAll && lastPage > 1" class="card-footer bg-body-tertiary border-top py-2 px-3.5 d-flex justify-content-between align-items-center">
              <span class="fs-8 text-muted">
                Trang <strong>{{ currentPage }}</strong> / <strong>{{ lastPage }}</strong> ({{ filteredCount }} học viên)
              </span>

              <div class="d-flex gap-1">
                <button
                  :disabled="currentPage <= 1"
                  @click="changePage(currentPage - 1)"
                  class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-0.5 fs-8"
                >
                  ← Trước
                </button>
                <button
                  v-for="p in lastPage"
                  :key="p"
                  @click="changePage(p)"
                  class="btn btn-sm rounded-pill px-2.5 py-0.5 fs-8 fw-bold"
                  :class="currentPage === p ? 'btn-teal text-white' : 'btn-outline-secondary'"
                >
                  {{ p }}
                </button>
                <button
                  :disabled="currentPage >= lastPage"
                  @click="changePage(currentPage + 1)"
                  class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-0.5 fs-8"
                >
                  Sau →
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ================= TAB 2: PHÒNG THI ĐẤU REALTIME ================= -->
      <div v-else-if="activeTab === 'rooms'">
        <!-- Quick Join & Create Actions -->
        <div class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-3.5 text-center">
          <div class="max-w-xl mx-auto">
            <h5 class="fw-black mb-1.5">Vào Phòng Thi Trực Tiếp Qua Mã</h5>
            <p class="text-muted fs-8 mb-3">Nhập mã phòng do thầy cô hoặc trưởng nhóm chia sẻ (VD: OX-ARENA1 hoặc OX-VIP99):</p>
            <div class="input-group mb-3">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key-fill"></i></span>
              <input
                type="text"
                v-model="quickCode"
                @keyup.enter="handleQuickJoin"
                class="form-control form-control-lg fs-7 border-start-0"
                placeholder="Nhập mã phòng thi..."
              />
              <button @click="handleQuickJoin" class="btn btn-warning text-dark fw-bold px-4 fs-7">
                Vào ngay <i class="bi bi-box-arrow-in-right ms-1"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Category Filters -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2.5 mb-3.5">
          <div class="d-flex flex-wrap gap-1.5">
            <button
              v-for="cat in categories"
              :key="cat.id"
              @click="selectedCategory = cat.id"
              class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold transition-all"
              :class="selectedCategory === cat.id ? 'btn-teal text-white shadow-xs' : 'btn-outline-secondary'"
            >
              {{ cat.name }}
            </button>
          </div>

          <button @click="loadRooms" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fs-8">
            <i class="bi bi-arrow-clockwise me-1"></i> Làm mới danh sách
          </button>
        </div>

        <!-- Loading Rooms -->
        <div v-if="isLoadingRooms" class="text-center py-5">
          <div class="spinner-border text-teal" role="status"></div>
          <div class="text-muted fs-7 mt-2">Đang tải danh sách phòng thi đấu...</div>
        </div>

        <!-- Rooms Grid -->
        <div v-else-if="filteredRooms.length" class="row g-3">
          <div v-for="room in filteredRooms" :key="room.id" class="col-12 col-md-6 col-lg-4">
            <div class="card p-3.5 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between transition-all hover-card">
              <div>
                <!-- Header Badges -->
                <div class="d-flex justify-content-between align-items-center mb-2.5">
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-8 fw-bold">
                    {{ room.room_code }}
                  </span>

                  <div class="d-flex align-items-center gap-1">
                    <span v-if="room.status === 'playing'" class="badge bg-danger text-white rounded-pill px-2 py-0.5 fs-8 animate-pulse">
                      🔴 Đang thi đấu
                    </span>
                    <span v-else-if="room.status === 'finished'" class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0.5 fs-8">
                      Đã kết thúc
                    </span>
                    <span v-else class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-8">
                      🟢 Đang chờ
                    </span>

                    <span v-if="room.has_password" class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 fs-8" title="Có mật khẩu">
                      <i class="bi bi-lock-fill"></i>
                    </span>
                  </div>
                </div>

                <!-- Title -->
                <h6 class="fw-bold text-dark-emphasis mb-2 line-clamp-2" style="min-height: 40px;">
                  {{ room.title }}
                </h6>

                <!-- Host -->
                <div class="d-flex align-items-center gap-2 mb-2.5">
                  <img :src="room.host_avatar || 'https://api.dicebear.com/7.x/bottts/svg?seed=Teacher'" class="rounded-circle border" style="width: 24px; height: 24px;" />
                  <span class="fs-8 text-muted">Chủ phòng: <strong class="text-dark-emphasis">{{ room.host_name }}</strong></span>
                </div>

                <!-- Meta Pills -->
                <div class="d-flex flex-wrap gap-1.5 mb-3">
                  <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2 py-0.5 fs-8">
                    {{ room.total_questions }} câu
                  </span>
                  <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2 py-0.5 fs-8">
                    {{ room.time_per_question > 0 ? room.time_per_question + 's/câu' : 'Không giới hạn' }}
                  </span>
                  <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2 py-0.5 fs-8">
                    👥 {{ room.participants_count }}/{{ room.max_players }}
                  </span>
                </div>
              </div>

              <!-- Join Button -->
              <button @click="joinRoom(room)" class="btn btn-teal text-white w-100 rounded-pill py-2 fw-bold fs-7 shadow-xs">
                <span>{{ room.has_password ? 'Nhập mật khẩu để vào' : 'Tham gia phòng' }}</span>
                <i class="bi" :class="room.has_password ? 'bi-shield-lock-fill ms-1' : 'bi-arrow-right ms-1'"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="card p-5 rounded-4 border text-center bg-card-custom">
          <i class="bi bi-door-closed display-4 text-muted mb-2"></i>
          <h5 class="fw-bold text-muted">Chưa có phòng thi nào trong danh mục này</h5>
          <p class="text-secondary fs-8 mb-3">Bạn có thể tự tạo phòng thi đấu mới và gửi mã mời cho bạn bè!</p>
          <div>
            <button @click="openCreateModal" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7">
              + Mở phòng ngay
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-header border-bottom py-2.5">
            <h6 class="modal-title fw-bold text-teal"><i class="bi bi-lock-fill me-1"></i> Mật khẩu phòng thi</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-3">
            <div v-if="passwordError" class="alert alert-danger py-2 px-2.5 fs-8 rounded-3 mb-2">
              <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ passwordError }}
            </div>

            <div class="mb-3">
              <label class="form-label fs-8 text-muted">Nhập mật khẩu để vào phòng <strong>{{ targetRoom?.room_code }}</strong>:</label>
              <input
                type="password"
                v-model="roomPassword"
                @keyup.enter="confirmJoinWithPassword"
                class="form-control rounded-3"
                placeholder="Mật khẩu..."
                autofocus
              />
              <div v-if="targetRoom?.room_code === 'OX-VIP99'" class="form-text fs-8 text-teal mt-1">
                <i class="bi bi-key-fill me-1"></i> Mật khẩu lớp cô Thu Trang: <strong>123456</strong>
              </div>
            </div>
            <button
              @click="confirmJoinWithPassword"
              :disabled="isVerifyingPassword"
              class="btn btn-teal text-white w-100 rounded-pill fw-bold fs-7"
            >
              <span v-if="isVerifyingPassword" class="spinner-border spinner-border-sm me-1" role="status"></span>
              <span>{{ isVerifyingPassword ? 'Đang kiểm tra...' : 'Xác nhận & Vào phòng' }}</span>
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
              <div class="text-muted fs-8">Tùy chỉnh nguồn câu hỏi, thời gian, điểm thưởng và mật khẩu</div>
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
                <div class="form-text fs-8 text-teal">Lấy các từ vựng thuộc ngày này để làm bài quiz.</div>
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
                  <span>{{ newRoom.category === 'grammar' ? 'Bao gồm toàn bộ 12 thì & câu bị động' : 'Bao phủ từ vựng Oxford theo CEFR A1 - C1' }}</span>
                </div>
              </div>
            </div>

            <!-- Questions count & Time per question -->
            <div class="row g-3 mb-3">
              <div class="col-12 col-md-6">
                <label class="form-label fs-8 fw-bold text-muted">Số lượng câu hỏi:</label>
                <div class="input-group">
                  <input type="number" min="3" max="50" v-model.number="newRoom.questions_count" class="form-control" placeholder="10" />
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Gợi ý</button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 5">5 câu (Nhanh)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 10">10 câu (Chuẩn)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 15">15 câu (Thử thách)</a></li>
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.questions_count = 20">20 câu (Marathon)</a></li>
                  </ul>
                </div>
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
                    <li><a class="dropdown-item fs-8" href="#" @click.prevent="newRoom.time_per_question = 0">0 (Không giới hạn)</a></li>
                  </ul>
                </div>
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
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fs-8 fw-bold text-muted">Số lượng học viên tối đa:</label>
                <input type="number" min="0" max="500" v-model.number="newRoom.max_players" class="form-control" placeholder="Để trống = Không giới hạn" />
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
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import * as bootstrap from 'bootstrap'

const router = useRouter()
const route = useRoute()

// Active Tab: 'leaderboard' or 'rooms'
const activeTab = ref(route.query.tab === 'rooms' ? 'rooms' : 'leaderboard')

// Rooms States
const isLoadingRooms = ref(true)
const rooms = ref([])
const curriculumTopics = ref([])
const quickCode = ref('')
const selectedCategory = ref('all')
const targetRoom = ref(null)
const roomPassword = ref('')
const passwordError = ref('')
const isVerifyingPassword = ref(false)

// Leaderboard States
const isLoadingLeaderboard = ref(false)
const currentLeaderboardType = ref('all_xp')
const leaderboardData = ref([])
const myRankData = ref(null)
const totalLearners = ref(0)
const totalSystemXp = ref(0)
const filteredCount = ref(0)
const currentPage = ref(1)
const lastPage = ref(1)
const viewAll = ref(false)
const searchQuery = ref('')
const selectedTier = ref('all')

const leaderboardFilters = [
  { type: 'all_xp', label: '🌟 Tổng XP', icon: 'bi-stars' },
  { type: 'streak', label: '🔥 Chuỗi Ngày', icon: 'bi-fire' },
  { type: 'arena', label: '⚔️ Đấu Trường', icon: 'bi-controller' },
  { type: 'words', label: '📚 Từ Nắm Vững', icon: 'bi-book-half' }
]

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

const switchTab = (tab) => {
  activeTab.value = tab
  if (tab === 'leaderboard' && leaderboardData.value.length === 0) {
    loadLeaderboard()
  } else if (tab === 'rooms' && rooms.value.length === 0) {
    loadRooms()
  }
}

const toggleViewAll = () => {
  viewAll.value = !viewAll.value
  currentPage.value = 1
  loadLeaderboard()
}

const changePage = (p) => {
  currentPage.value = p
  loadLeaderboard()
}

const changeLeaderboardType = (type) => {
  currentLeaderboardType.value = type
  currentPage.value = 1
  loadLeaderboard()
}

const loadLeaderboard = async () => {
  isLoadingLeaderboard.value = true
  try {
    const params = {
      type: currentLeaderboardType.value,
      search: searchQuery.value,
      tier: selectedTier.value,
      all: viewAll.value ? 'true' : 'false',
      page: currentPage.value,
      per_page: viewAll.value ? 'all' : '50'
    }
    const res = await axios.get('/api/arena/leaderboard', { params })
    const payload = res.data.data
    leaderboardData.value = payload.leaderboard || []
    myRankData.value = payload.my_rank || null
    totalLearners.value = payload.total_learners || 0
    totalSystemXp.value = payload.total_system_xp || 0
    filteredCount.value = payload.filtered_count || 0
    currentPage.value = payload.current_page || 1
    lastPage.value = payload.last_page || 1
  } catch (err) {
    console.error('Failed to load arena leaderboard:', err)
  } finally {
    isLoadingLeaderboard.value = false
  }
}

const loadRooms = async () => {
  isLoadingRooms.value = true
  try {
    const res = await axios.get('/api/arena/rooms')
    rooms.value = res.data.data || []
  } catch (err) {
    console.error('Failed to load arena rooms:', err)
  } finally {
    isLoadingRooms.value = false
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
  passwordError.value = ''
  if (room.has_password) {
    targetRoom.value = room
    roomPassword.value = room.room_code === 'OX-VIP99' ? '123456' : ''
    const modalEl = document.getElementById('passwordModal')
    if (modalEl) {
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl)
      modal.show()
    }
  } else {
    router.push(`/arena/${room.room_code}`)
  }
}

const confirmJoinWithPassword = async () => {
  if (!targetRoom.value) return
  if (!roomPassword.value.trim()) {
    passwordError.value = 'Vui lòng nhập mật khẩu phòng thi.'
    return
  }

  isVerifyingPassword.value = true
  passwordError.value = ''

  try {
    await axios.post('/api/arena/rooms/join', {
      room_code: targetRoom.value.room_code,
      password: roomPassword.value.trim()
    })

    const modalEl = document.getElementById('passwordModal')
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl)
      if (modal) modal.hide()
    }

    router.push({
      path: `/arena/${targetRoom.value.room_code}`,
      query: { pwd: roomPassword.value.trim() }
    })
  } catch (err) {
    passwordError.value = err.response?.data?.message || 'Mật khẩu không chính xác! Vui lòng thử lại.'
  } finally {
    isVerifyingPassword.value = false
  }
}

const openCreateModal = () => {
  newRoom.value.title = `Phòng thi đấu thử thách ${new Date().toLocaleDateString('vi-VN')}`
  const modalEl = document.getElementById('createRoomModal')
  if (modalEl) {
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl)
    modal.show()
  }
}

const submitCreateRoom = async () => {
  if (!newRoom.value.title) {
    alert('Vui lòng nhập tên phòng thi.')
    return
  }

  try {
    const res = await axios.post('/api/arena/rooms', newRoom.value)
    const modalEl = document.getElementById('createRoomModal')
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl)
      if (modal) modal.hide()
    }
    router.push(`/arena/${res.data.data.room_code}`)
  } catch (err) {
    console.error('Failed to create arena room:', err)
    alert(err.response?.data?.message || 'Không thể tạo phòng lúc này.')
  }
}

const formatMetricValue = (user) => {
  if (!user) return ''
  if (currentLeaderboardType.value === 'streak') {
    return `${user.streak_days} Ngày 🔥`
  } else if (currentLeaderboardType.value === 'arena') {
    return `${user.arena_score} XP (${user.arena_matches} Trận)`
  } else if (currentLeaderboardType.value === 'words') {
    return `${user.mastered_words} Từ Nắm Vững`
  }
  return `${user.xp_points.toLocaleString()} XP`
}

onMounted(() => {
  loadLeaderboard()
  loadRooms()
  loadCurriculumTopics()
})
</script>

<style scoped>
.hover-card:hover {
  transform: translateY(-2px);
  border-color: #00b074 !important;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.animate-pulse {
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.table-teal-highlight {
  background-color: rgba(8, 127, 117, 0.08) !important;
  font-weight: 600;
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
