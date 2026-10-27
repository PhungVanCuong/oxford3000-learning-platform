<template>
  <div class="arena-lobby-page py-4">
    <div class="container max-w-6xl">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb fs-8 mb-0">
          <li class="breadcrumb-item"><router-link to="/" class="text-decoration-none text-muted">Trang chủ</router-link></li>
          <li class="breadcrumb-item active text-teal fw-bold" aria-current="page">Đấu trường Quiz & Bảng Xếp Hạng</li>
        </ol>
      </nav>

      <!-- Main Navigation Tabs: Phòng Thi vs Bảng Xếp Hạng -->
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 border-bottom pb-3">
        <ul class="nav nav-pills gap-2 fs-7 fw-bold" role="tablist">
          <li class="nav-item">
            <button
              class="nav-link rounded-pill px-4 py-2 d-flex align-items-center gap-2"
              :class="activeTab === 'rooms' ? 'active bg-teal text-white shadow-sm' : 'text-secondary bg-body-tertiary'"
              @click="activeTab = 'rooms'"
            >
              <i class="bi bi-controller"></i>
              <span>Phòng Thi Đấu Trực Tuyến</span>
              <span class="badge bg-white text-dark rounded-pill px-2 py-0.5 fs-8" v-if="rooms.length">
                {{ rooms.length }}
              </span>
            </button>
          </li>
          <li class="nav-item">
            <button
              class="nav-link rounded-pill px-4 py-2 d-flex align-items-center gap-2"
              :class="activeTab === 'leaderboard' ? 'active bg-teal text-white shadow-sm' : 'text-secondary bg-body-tertiary'"
              @click="switchTab('leaderboard')"
            >
              <i class="bi bi-trophy-fill text-warning"></i>
              <span>Bảng Xếp Hạng Vinh Quang</span>
            </button>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-2">
          <button
            v-if="activeTab === 'rooms'"
            @click="openCreateModal"
            class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-7 shadow-sm d-flex align-items-center gap-1.5"
          >
            <i class="bi bi-plus-circle-fill"></i>
            <span>Mở phòng thi mới</span>
          </button>
          <router-link to="/tree" class="btn btn-outline-teal rounded-pill px-3.5 py-2 fw-bold fs-7 d-flex align-items-center gap-1.5">
            <i class="bi bi-tree-fill text-success"></i>
            <span>Cây Tri Thức</span>
          </router-link>
        </div>
      </div>

      <!-- ================= TAB 1: PHÒNG THI ĐẤU TRỰC TUYẾN ================= -->
      <div v-if="activeTab === 'rooms'">
        <!-- Hero Banner -->
        <div class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom mb-4 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #022c22 0%, #064e40 60%, #087F75 100%);">
          <div class="position-relative z-1 text-white max-w-2xl mx-auto">
            <span class="badge rounded-pill px-3 py-1.5 fw-bold mb-2 shadow-sm" style="background: rgba(94, 234, 212, 0.2); color: #5eead4; font-size: 11px;">
              <i class="bi bi-lightning-charge-fill me-1"></i> THI ĐUA TRẮC NGHIỆM REALTIME
            </span>
            <h2 class="fw-black mb-2 display-6">Đấu Trường Trắc Nghiệm Realtime</h2>
            <p class="text-white-50 fs-7 mb-4">
              Tham gia phòng thi đấu trắc nghiệm cùng lớp học và bạn bè. Trả lời càng nhanh, điểm kinh nghiệm XP thưởng càng cao! Thầy cô và học viên có thể tự do mở phòng theo 25 chủ đề, 12 thì ngữ pháp, hoặc kho 3.000 từ Oxford.
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
              <button @click="switchTab('leaderboard')" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold fs-7">
                <i class="bi bi-trophy-fill text-warning me-1.5"></i> Xem Bảng Xếp Hạng
              </button>
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

        <!-- Loading State -->
        <div v-if="isLoadingRooms" class="text-center py-5">
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

                  <!-- Status Badge -->
                  <div class="d-flex align-items-center gap-1.5">
                    <span v-if="room.status === 'playing'" class="badge bg-danger text-white rounded-pill px-2 py-0.5 fs-8 animate-pulse">
                      🔴 Đang thi đấu
                    </span>
                    <span v-else-if="room.status === 'finished'" class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0.5 fs-8">
                      Đã kết thúc
                    </span>
                    <span v-else class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fs-8">
                      🟢 Đang chờ
                    </span>

                    <span v-if="room.has_password" class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 fs-8" title="Phòng có mật khẩu">
                      <i class="bi bi-lock-fill"></i>
                    </span>
                    <span v-else class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-0.5 fs-8" title="Tự do">
                      <i class="bi bi-unlock-fill"></i>
                    </span>
                  </div>
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
                    <i class="bi bi-patch-question me-1"></i> {{ room.total_questions }} câu
                  </span>
                  <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2.5 py-1 fs-8">
                    <i class="bi bi-stopwatch me-1"></i> {{ room.time_per_question > 0 ? room.time_per_question + 's/câu' : 'Không giới hạn' }}
                  </span>
                  <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2.5 py-1 fs-8">
                    <i class="bi bi-people-fill me-1"></i> {{ room.participants_count }}/{{ room.max_players }} học viên
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
          <p class="text-secondary fs-7 mb-3">Bạn có thể tự tạo phòng thi đấu mới và gửi mã mời cho bạn bè!</p>
          <div>
            <button @click="openCreateModal" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7">
              + Mở phòng ngay
            </button>
          </div>
        </div>
      </div>

      <!-- ================= TAB 2: BẢNG XẾP HẠNG VINH QUANG ================= -->
      <div v-else-if="activeTab === 'leaderboard'">
        <!-- Leaderboard Hero -->
        <div class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom mb-4 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
          <div class="position-relative z-1 text-white max-w-2xl mx-auto">
            <span class="badge rounded-pill px-3 py-1.5 fw-bold mb-2 shadow-sm" style="background: rgba(251, 191, 36, 0.2); color: #fbbf24; font-size: 11px;">
              <i class="bi bi-trophy-fill me-1"></i> BẢNG VINH DANH CAO THỦ OXFORD
            </span>
            <h2 class="fw-black mb-2 display-6">Bảng Xếp Hạng Vinh Quang</h2>
            <p class="text-white-50 fs-7 mb-4">
              Vinh danh những học viên chăm chỉ và xuất sắc nhất! Tích lũy điểm kinh nghiệm XP, duy trì chuỗi ngày học liên tục và tỏa sáng trên đấu trường để thăng hạng bậc Cao Thủ.
            </p>

            <!-- Filter Categories -->
            <div class="d-inline-flex flex-wrap justify-content-center p-1.5 rounded-pill bg-black bg-opacity-25 border border-white border-opacity-10 gap-1.5">
              <button
                v-for="ft in leaderboardFilters"
                :key="ft.type"
                @click="changeLeaderboardType(ft.type)"
                class="btn btn-sm rounded-pill px-3 py-1.5 fs-8 fw-bold transition-all"
                :class="currentLeaderboardType === ft.type ? 'btn-warning text-dark shadow-sm' : 'btn-link text-white text-decoration-none'"
              >
                <i :class="ft.icon" class="me-1"></i>
                {{ ft.label }}
              </button>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoadingLeaderboard" class="text-center py-5">
          <div class="spinner-border text-teal" role="status"></div>
          <div class="text-muted fs-7 mt-2">Đang tải bảng xếp hạng cao thủ...</div>
        </div>

        <div v-else>
          <!-- TOP 3 PODIUM -->
          <div v-if="leaderboardData.length >= 3" class="mb-5">
            <div class="text-center mb-4">
              <h5 class="fw-black text-dark-emphasis mb-1">👑 Top 3 Cao Thủ Dẫn Đầu</h5>
              <div class="text-muted fs-8">Học viên xuất sắc nhất hệ thống hiện tại</div>
            </div>

            <div class="d-flex justify-content-center align-items-end gap-2 gap-md-4 px-2" style="min-height: 260px;">
              <!-- 2ND PLACE (SILVER) -->
              <div class="d-flex flex-column align-items-center text-center podium-col" style="width: 140px;">
                <div class="position-relative mb-2">
                  <img :src="leaderboardData[1].avatar_url" class="rounded-circle border border-3 border-secondary shadow" style="width: 60px; height: 60px; object-fit: cover;" />
                  <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-secondary text-white rounded-pill fs-8 shadow-2xs">🥈 #2</span>
                </div>
                <div class="fw-bold fs-7 text-dark-emphasis text-truncate w-100 mb-0.5" :title="leaderboardData[1].name">
                  {{ leaderboardData[1].name }}
                </div>
                <span class="badge rounded-pill mb-2 fs-8" :class="leaderboardData[1].tier_class">
                  {{ leaderboardData[1].tier_badge }} {{ leaderboardData[1].tier_name }}
                </span>
                <div class="podium-step w-100 rounded-top-4 p-3 d-flex flex-column justify-content-center align-items-center border border-bottom-0 shadow-sm" style="height: 120px; background: linear-gradient(180deg, #f1f5f9 0%, #cbd5e1 100%);">
                  <div class="fw-black fs-5 text-secondary">2</div>
                  <div class="fw-bold text-dark fs-7">{{ formatMetricValue(leaderboardData[1]) }}</div>
                </div>
              </div>

              <!-- 1ST PLACE (GOLD - CHAMPION) -->
              <div class="d-flex flex-column align-items-center text-center podium-col" style="width: 160px;">
                <div class="fs-3 mb-n1 animate-bounce">👑</div>
                <div class="position-relative mb-2">
                  <img :src="leaderboardData[0].avatar_url" class="rounded-circle border border-4 border-warning shadow-lg" style="width: 78px; height: 78px; object-fit: cover;" />
                  <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-warning text-dark rounded-pill fs-7 fw-bold shadow-xs">🥇 #1 Quán Quân</span>
                </div>
                <div class="fw-black fs-6 text-dark-emphasis text-truncate w-100 mb-0.5" :title="leaderboardData[0].name">
                  {{ leaderboardData[0].name }}
                </div>
                <span class="badge rounded-pill mb-2 fs-8" :class="leaderboardData[0].tier_class">
                  {{ leaderboardData[0].tier_badge }} {{ leaderboardData[0].tier_name }}
                </span>
                <div class="podium-step w-100 rounded-top-4 p-3 d-flex flex-column justify-content-center align-items-center border border-warning border-bottom-0 shadow" style="height: 160px; background: linear-gradient(180deg, #fef3c7 0%, #fde047 100%);">
                  <div class="fw-black fs-3 text-warning-emphasis">1</div>
                  <div class="fw-black text-dark fs-6">{{ formatMetricValue(leaderboardData[0]) }}</div>
                </div>
              </div>

              <!-- 3RD PLACE (BRONZE) -->
              <div class="d-flex flex-column align-items-center text-center podium-col" style="width: 140px;">
                <div class="position-relative mb-2">
                  <img :src="leaderboardData[2].avatar_url" class="rounded-circle border border-3 border-danger-subtle shadow" style="width: 56px; height: 56px; object-fit: cover;" />
                  <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-danger-subtle text-danger rounded-pill fs-8 shadow-2xs">🥉 #3</span>
                </div>
                <div class="fw-bold fs-7 text-dark-emphasis text-truncate w-100 mb-0.5" :title="leaderboardData[2].name">
                  {{ leaderboardData[2].name }}
                </div>
                <span class="badge rounded-pill mb-2 fs-8" :class="leaderboardData[2].tier_class">
                  {{ leaderboardData[2].tier_badge }} {{ leaderboardData[2].tier_name }}
                </span>
                <div class="podium-step w-100 rounded-top-4 p-3 d-flex flex-column justify-content-center align-items-center border border-bottom-0 shadow-sm" style="height: 95px; background: linear-gradient(180deg, #ffedd5 0%, #fed7aa 100%);">
                  <div class="fw-black fs-5 text-danger">3</div>
                  <div class="fw-bold text-dark fs-7">{{ formatMetricValue(leaderboardData[2]) }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- CURRENT USER PERSONAL RANK CARD -->
          <div v-if="myRankData" class="card p-3 p-md-4 rounded-4 shadow-sm border border-teal bg-card-custom mb-4" style="background: linear-gradient(135deg, rgba(8, 127, 117, 0.05) 0%, rgba(20, 184, 166, 0.08) 100%);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <div class="d-flex align-items-center gap-3">
                <!-- Rank Badge -->
                <div class="rounded-circle bg-teal text-white d-flex flex-column align-items-center justify-content-center fw-black shadow-sm shrink-0" style="width: 56px; height: 56px;">
                  <span class="fs-8 text-white-50 text-uppercase" style="font-size: 9px;">HẠNG</span>
                  <span class="fs-5 leading-none">#{{ myRankData.rank }}</span>
                </div>

                <!-- Avatar & Info -->
                <img :src="myRankData.avatar_url" class="rounded-circle border border-2 border-teal" style="width: 48px; height: 48px;" />
                <div>
                  <div class="d-flex align-items-center gap-2 mb-0.5">
                    <h6 class="fw-bold mb-0 text-dark-emphasis">{{ myRankData.name }}</h6>
                    <span class="badge bg-teal text-white rounded-pill fs-8">BẠN</span>
                    <span class="badge rounded-pill fs-8" :class="myRankData.tier_class">
                      {{ myRankData.tier_badge }} {{ myRankData.tier_name }}
                    </span>
                  </div>
                  <div class="text-muted fs-8">
                    Xếp hạng trong tổng số {{ totalUsersCount }} học viên toàn hệ thống
                  </div>
                </div>
              </div>

              <!-- Stats Highlights -->
              <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="text-center px-3 py-1.5 rounded-3 bg-white border">
                  <div class="fs-8 text-muted">Tổng XP</div>
                  <div class="fw-black text-warning fs-6">{{ myRankData.xp_points }} XP</div>
                </div>
                <div class="text-center px-3 py-1.5 rounded-3 bg-white border">
                  <div class="fs-8 text-muted">Chuỗi ngày 🔥</div>
                  <div class="fw-black text-danger fs-6">{{ myRankData.streak_days }} ngày</div>
                </div>
                <div class="text-center px-3 py-1.5 rounded-3 bg-white border">
                  <div class="fs-8 text-muted">Đấu trường ⚔️</div>
                  <div class="fw-black text-teal fs-6">{{ myRankData.arena_score }} XP</div>
                </div>
                <div class="text-center px-3 py-1.5 rounded-3 bg-white border">
                  <div class="fs-8 text-muted">Từ nắm vững 📚</div>
                  <div class="fw-black text-primary fs-6">{{ myRankData.mastered_words }} từ</div>
                </div>
              </div>
            </div>
          </div>

          <!-- TOP 50 LEADERBOARD TABLE -->
          <div class="card rounded-4 shadow-sm border bg-card-custom overflow-hidden mb-4">
            <div class="card-header bg-body-tertiary border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
              <div>
                <h6 class="fw-bold mb-0 text-dark-emphasis">
                  <i class="bi bi-list-ol text-teal me-1"></i> Bảng Tổng Sắp Top 50 Học Viên Toàn Hệ Thống
                </h6>
                <div class="text-muted fs-8">Dữ liệu được cập nhật tự động theo thời gian thực</div>
              </div>
              <button @click="loadLeaderboard" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fs-8">
                <i class="bi bi-arrow-clockwise me-1"></i> Làm mới
              </button>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0 fs-7">
                <thead class="table-light fs-8 text-uppercase text-muted">
                  <tr>
                    <th style="width: 70px;" class="text-center">Hạng</th>
                    <th>Học viên</th>
                    <th class="text-center">Cấp bậc</th>
                    <th class="text-center">Chuỗi học 🔥</th>
                    <th class="text-center">Từ nắm vững 📚</th>
                    <th class="text-center">Đấu trường ⚔️</th>
                    <th class="text-end pe-4">Tổng XP 🌟</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(u, idx) in leaderboardData"
                    :key="u.user_id || idx"
                    :class="u.is_current_user ? 'table-teal-highlight' : ''"
                  >
                    <!-- Rank Column -->
                    <td class="text-center fw-black">
                      <span v-if="u.rank === 1" class="badge bg-warning text-dark fs-7 rounded-pill px-2.5 py-1 shadow-2xs">🥇 1</span>
                      <span v-else-if="u.rank === 2" class="badge bg-secondary text-white fs-7 rounded-pill px-2.5 py-1 shadow-2xs">🥈 2</span>
                      <span v-else-if="u.rank === 3" class="badge bg-danger-subtle text-danger fs-7 rounded-pill px-2.5 py-1 shadow-2xs">🥉 3</span>
                      <span v-else class="text-muted fw-bold">#{{ u.rank }}</span>
                    </td>

                    <!-- User Name & Avatar -->
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img :src="u.avatar_url" class="rounded-circle border" style="width: 34px; height: 34px; object-fit: cover;" />
                        <div>
                          <div class="fw-bold text-dark-emphasis d-flex align-items-center gap-1.5">
                            <span>{{ u.name }}</span>
                            <span v-if="u.is_current_user" class="badge bg-teal text-white rounded-pill" style="font-size: 9px;">Bạn</span>
                            <span v-if="u.role === 'admin'" class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 9px;">Admin</span>
                            <span v-else-if="u.role === 'tutor'" class="badge bg-info-subtle text-info rounded-pill" style="font-size: 9px;">Trợ giảng</span>
                            <span v-else-if="u.role === 'moderator'" class="badge bg-warning-subtle text-dark rounded-pill" style="font-size: 9px;">Mod</span>
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- Tier Badge -->
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
                    <td class="text-center text-muted">
                      {{ u.mastered_words }} từ
                    </td>

                    <!-- Arena Score -->
                    <td class="text-center">
                      <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2 py-1 fs-8">
                        {{ u.arena_score }} XP ({{ u.arena_matches }} trận)
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
                <div class="form-text fs-8 text-muted">Nhập số câu tùy ý từ 3 đến 50.</div>
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
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import * as bootstrap from 'bootstrap'

const router = useRouter()
const route = useRoute()

// Active Tab: 'rooms' or 'leaderboard'
const activeTab = ref(route.query.tab === 'leaderboard' ? 'leaderboard' : 'rooms')

// Rooms Tab States
const isLoadingRooms = ref(true)
const rooms = ref([])
const curriculumTopics = ref([])
const quickCode = ref('')
const selectedCategory = ref('all')
const targetRoom = ref(null)
const roomPassword = ref('')
const passwordError = ref('')
const isVerifyingPassword = ref(false)

// Leaderboard Tab States
const isLoadingLeaderboard = ref(false)
const currentLeaderboardType = ref('all_xp')
const leaderboardData = ref([])
const myRankData = ref(null)
const totalUsersCount = ref(0)

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
    // Strictly verify password with the backend before entering!
    await axios.post('/api/arena/rooms/join', {
      room_code: targetRoom.value.room_code,
      password: roomPassword.value.trim()
    })

    // Password is correct! Close modal and proceed to the room
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
    // If backend returns 403 or other error, show error directly in modal!
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

// Leaderboard Handlers
const changeLeaderboardType = (type) => {
  currentLeaderboardType.value = type
  loadLeaderboard()
}

const loadLeaderboard = async () => {
  isLoadingLeaderboard.value = true
  try {
    const res = await axios.get('/api/arena/leaderboard', {
      params: { type: currentLeaderboardType.value }
    })
    const payload = res.data.data
    leaderboardData.value = payload.leaderboard || []
    myRankData.value = payload.my_rank || null
    totalUsersCount.value = payload.total_users || 0
  } catch (err) {
    console.error('Failed to load arena leaderboard:', err)
  } finally {
    isLoadingLeaderboard.value = false
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
  loadRooms()
  loadCurriculumTopics()
  if (activeTab.value === 'leaderboard') {
    loadLeaderboard()
  }
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

.animate-pulse {
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.animate-bounce {
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
}

.table-teal-highlight {
  background-color: rgba(8, 127, 117, 0.08) !important;
  font-weight: 600;
}

.podium-col {
  transition: all 0.3s ease;
}

.podium-col:hover {
  transform: translateY(-4px);
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
