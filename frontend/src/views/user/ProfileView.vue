<template>
  <div class="container py-4 max-w-5xl">
    <!-- 1. Header Banner (Dark teal #064e40, white text, exact layout) -->
    <div class="rounded-4 p-4 p-md-5 mb-4 shadow-sm text-white position-relative overflow-hidden" style="background-color: #064e40;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 position-relative z-1">
        <!-- Avatar and Name info -->
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-4 overflow-hidden border border-2 border-white shadow-sm shrink-0" style="width: 72px; height: 72px; background-color: #0d7362;">
            <img
              :src="authStore.user?.avatar_url || 'https://api.dicebear.com/7.x/bottts/svg?seed=CuongPhung'"
              alt="Avatar"
              class="w-100 h-100 object-fit-cover"
            />
          </div>
          <div>
            <div class="text-uppercase fw-bold fs-8 tracking-wider mb-1" style="color: #5eead4;">
              TRANG CÁ NHÂN
            </div>
            <h2 class="fw-black mb-0 fs-3">{{ authStore.userName }}</h2>
            <div class="text-white-50 fs-7 mt-0.5">{{ authStore.userEmail }}</div>
            <span class="badge mt-1" :class="authStore.isAdmin ? 'bg-danger text-white' : 'bg-success text-white'" style="font-size: 11px;">
              {{ authStore.isAdmin ? 'Quản trị viên (Admin)' : 'Học viên' }}
            </span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap align-items-center gap-2">
          <router-link
            to="/stats"
            class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold fs-7 d-flex align-items-center gap-1"
          >
            <i class="bi bi-bar-chart-fill text-warning"></i>
            <span>Báo cáo Năng suất</span>
          </router-link>

          <router-link
            to="/tree"
            class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold fs-7 d-flex align-items-center gap-1"
          >
            <i class="bi bi-tree-fill text-success"></i>
            <span>Cây Tri Thức</span>
          </router-link>

          <router-link
            to="/daily"
            class="btn btn-light rounded-pill px-3.5 py-2 fw-bold shadow-sm d-flex align-items-center gap-1.5"
            style="color: #064e40;"
          >
            <span>Tiếp tục học</span>
            <i class="bi bi-arrow-right"></i>
          </router-link>

          <button
            @click="handleLogout"
            class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold fs-7"
          >
            Đăng xuất
          </button>
        </div>
      </div>
    </div>

    <!-- 2. Four Stat Cards Row -->
    <div class="row g-3 mb-4">
      <!-- Stat 1: XP đã tích luỹ -->
      <div class="col-6 col-lg-3">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">XP đã tích luỹ</div>
          <div class="display-6 fw-black text-teal">{{ authStore.user?.xp_points ?? 0 }}</div>
        </div>
      </div>

      <!-- Stat 2: Bài đã hoàn thành -->
      <div class="col-6 col-lg-3">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Bài đã hoàn thành</div>
          <div class="display-6 fw-black text-teal">{{ authStore.stats?.total_quizzes ?? 0 }}</div>
        </div>
      </div>

      <!-- Stat 3: Độ chính xác -->
      <div class="col-6 col-lg-3">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Độ chính xác</div>
          <div class="display-6 fw-black text-teal">{{ authStore.stats?.avg_accuracy ? `${authStore.stats.avg_accuracy}%` : '0%' }}</div>
        </div>
      </div>

      <!-- Stat 4: Chuỗi học -->
      <div class="col-6 col-lg-3">
        <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 hover-card">
          <div class="text-muted fs-8 fw-semibold text-uppercase mb-1">Chuỗi học</div>
          <div class="display-6 fw-black text-teal">{{ authStore.user?.streak_days ?? 1 }} ngày</div>
        </div>
      </div>
    </div>

    <!-- 3. Two Columns: 01 THÔNG TIN CÁ NHÂN & 02 TIẾN ĐỘ HỌC TẬP -->
    <div class="row g-4 mb-4">
      <!-- Cột trái: Card 01 THÔNG TIN CÁ NHÂN -->
      <div class="col-12 col-lg-6">
        <div class="card p-4 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-teal-dark text-white rounded-pill px-2.5 py-1 fs-8">01</span>
              <h5 class="fw-bold mb-0 text-teal">THÔNG TIN CÁ NHÂN & CẤU HÌNH</h5>
            </div>
            <div class="text-muted fs-7 mb-3">Thông tin được đồng bộ với tài khoản học tập của bạn.</div>

            <!-- Form -->
            <form @submit.prevent="saveProfile">
              <!-- Họ và tên -->
              <div class="mb-3">
                <label class="form-label fs-7 fw-bold mb-1">Họ và tên</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control rounded-3 py-2 fs-7"
                  placeholder="Nhập họ và tên..."
                  required
                />
              </div>

              <!-- Avatar Selector -->
              <div class="mb-3">
                <label class="form-label fs-7 fw-bold mb-1">Ảnh đại diện (Avatar)</label>
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                  <div
                    v-for="ava in presetAvatars"
                    :key="ava"
                    @click="form.avatar_url = ava"
                    class="rounded-circle p-1 cursor-pointer border transition-all"
                    :class="form.avatar_url === ava ? 'border-success border-2 shadow-sm bg-success-subtle' : 'border-light-subtle'"
                    style="width: 44px; height: 44px; cursor: pointer;"
                  >
                    <img :src="ava" class="w-100 h-100 rounded-circle object-fit-cover" />
                  </div>
                </div>
                <input
                  v-model="form.avatar_url"
                  type="text"
                  class="form-control form-control-sm rounded-3 fs-8"
                  placeholder="Hoặc dán URL ảnh đại diện..."
                />
              </div>

              <!-- Ngày sinh -->
              <div class="mb-3">
                <label class="form-label fs-7 fw-bold mb-1">Ngày sinh</label>
                <input
                  v-model="form.birth_date"
                  type="date"
                  class="form-control rounded-3 py-2 fs-7"
                />
                <div class="form-text fs-8 text-muted">Nhập theo dạng ngày/tháng/năm (VD: 18/10/2005).</div>
              </div>

              <!-- Số từ vựng học mỗi ngày -->
              <div class="mb-4">
                <label class="form-label fs-7 fw-bold mb-1">Số từ vựng học mỗi ngày</label>
                <select v-model="form.words_per_day" class="form-select rounded-3 py-2 fs-7">
                  <option :value="10">10 từ/ngày (khoảng 300 ngày)</option>
                  <option :value="15">15 từ/ngày (khoảng 200 ngày - Khuyên dùng)</option>
                  <option :value="20">20 từ/ngày (khoảng 150 ngày)</option>
                  <option :value="25">25 từ/ngày (khoảng 120 ngày)</option>
                  <option :value="30">30 từ/ngày (khoảng 100 ngày)</option>
                  <option :value="50">50 từ/ngày (khoảng 60 ngày - Cấp tốc)</option>
                </select>
                <div class="form-text fs-8 text-muted">Hệ thống sẽ tự động tính lại số ngày và phân chia bộ từ vựng tương ứng mà không mất tiến độ từ đã thuộc.</div>
              </div>

              <div class="d-flex justify-content-end">
                <button
                  type="submit"
                  class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm"
                  style="background-color: #064e40;"
                  :disabled="isSaving"
                >
                  <span v-if="isSaving" class="spinner-border spinner-border-sm me-1"></span>
                  Lưu thay đổi
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Cột phải: Card 02 TIẾN ĐỘ HỌC TẬP -->
      <div class="col-12 col-lg-6">
        <div class="card p-4 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-teal-dark text-white rounded-pill px-2.5 py-1 fs-8">02</span>
              <h5 class="fw-bold mb-0 text-teal">TIẾN ĐỘ HỌC TẬP</h5>
            </div>
            <div class="text-muted fs-7 mb-4">Các kết quả đã lưu trên toàn bộ lộ trình.</div>

            <!-- Progress 1: Lộ trình 25 Chủ đề IELTS (5.000 từ) -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-1 fs-7">
                <span class="fw-bold d-flex align-items-center gap-1.5">
                  <i class="bi bi-signpost-split-fill text-teal"></i>
                  <span>Lộ trình 25 Chủ đề IELTS (5.000 từ)</span>
                </span>
                <div class="d-flex gap-2">
                  <span class="text-muted">{{ curriculumRememberedCount }}/5000 từ</span>
                  <span class="fw-bold text-teal">{{ formatFloatPercent(curriculumPercent) }}%</span>
                </div>
              </div>
              <div class="progress" style="height: 10px; border-radius: 999px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: `${Math.min(100, curriculumPercent)}%`, backgroundColor: '#00b074' }"
                ></div>
              </div>
            </div>

            <!-- Progress 2: Từ vựng Oxford 3000 -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-1 fs-7">
                <span class="fw-bold d-flex align-items-center gap-1.5">
                  <i class="bi bi-journal-bookmark-fill text-success"></i>
                  <span>Kho Từ vựng Oxford 3000™</span>
                </span>
                <div class="d-flex gap-2">
                  <span class="text-muted">{{ authStore.stats?.mastered_words || 0 }}/3351 từ</span>
                  <span class="fw-bold text-teal">{{ formatFloatPercent(vocabPercent) }}%</span>
                </div>
              </div>
              <div class="progress" style="height: 10px; border-radius: 999px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: `${Math.min(100, vocabPercent)}%`, backgroundColor: '#10b981' }"
                ></div>
              </div>
            </div>

            <!-- Progress 3: Ngữ pháp -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-1 fs-7">
                <span class="fw-bold d-flex align-items-center gap-1.5">
                  <i class="bi bi-journal-bookmark-fill text-danger"></i>
                  <span>Ngữ pháp (12 thì & chuyên đề)</span>
                </span>
                <div class="d-flex gap-2">
                  <span class="text-muted">{{ authStore.stats?.completed_grammars || 0 }}/15 bài</span>
                  <span class="fw-bold text-teal">{{ formatFloatPercent(grammarPercent) }}%</span>
                </div>
              </div>
              <div class="progress" style="height: 10px; border-radius: 999px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: `${Math.min(100, grammarPercent)}%`, backgroundColor: '#0d7362' }"
                ></div>
              </div>
            </div>

            <!-- Progress 4: Cụm từ & Cụm động từ (Phrasal Verbs & 1000 Cụm từ) -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-1 fs-7">
                <span class="fw-bold d-flex align-items-center gap-1.5">
                  <i class="bi bi-puzzle-fill text-warning"></i>
                  <span>Cụm từ & Cụm động từ (945 câu & cụm)</span>
                </span>
                <div class="d-flex gap-2">
                  <span class="text-muted">{{ authStore.stats?.mastered_phrases || 0 }}/{{ authStore.stats?.total_phrases || 945 }} câu</span>
                  <span class="fw-bold text-teal">{{ formatFloatPercent(phrasesPercent) }}%</span>
                </div>
              </div>
              <div class="progress" style="height: 10px; border-radius: 999px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: `${Math.min(100, phrasesPercent)}%`, backgroundColor: '#f59e0b' }"
                ></div>
              </div>
            </div>
          </div>

          <!-- Khung trạng thái giai đoạn cây dưới cùng (nền xanh nhạt) -->
          <div class="p-3 rounded-4 mt-3 d-flex justify-content-between align-items-center" style="background-color: rgba(13, 115, 98, 0.1); border: 1px solid rgba(13, 115, 98, 0.2);">
            <div class="d-flex align-items-center gap-2">
              <span class="fs-4">🌱</span>
              <div>
                <div class="fs-8 fw-semibold text-muted text-uppercase">Cây tri thức hiện tại</div>
                <div class="fw-bold text-teal">{{ plantStage }}</div>
              </div>
            </div>
            <div class="badge bg-teal text-white px-3 py-2 rounded-pill fs-7 fw-bold">
              {{ formatFloatPercent(vocabPercent) }}% từ đã nhớ
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CÀI ĐẶT GIỌNG ĐỌC & PHÁT ÂM (VOICE SETTINGS) -->
    <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4" style="border-color: #E2E8F0;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-teal-dark text-white rounded-pill px-2.5 py-1 fs-8">VOICE</span>
            <h5 class="fw-bold mb-0 text-teal">CÀI ĐẶT GIỌNG ĐỌC & PHÁT ÂM (VOICE SETTINGS)</h5>
          </div>
          <div class="text-muted fs-7">
            Tùy chỉnh giọng đọc tiếng Anh (Nam 👨 / Nữ 👩, Accent US/UK/AU) và tốc độ phát âm chuẩn cho toàn bộ hệ thống (Flashcard, Từ điển, Ngữ pháp, Cụm từ).
          </div>
        </div>

        <button
          @click="testCurrentVoice"
          class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-7 d-flex align-items-center gap-1.5 shadow-sm"
        >
          <i class="bi bi-volume-up-fill fs-6"></i>
          <span>🔊 Nghe thử giọng này</span>
        </button>
      </div>

      <div class="row g-3">
        <!-- Voice Selector -->
        <div class="col-12 col-md-8">
          <label class="form-label fs-7 fw-bold mb-1">Chọn giọng đọc tiếng Anh:</label>
          <div class="input-group">
            <span class="input-group-text bg-body-tertiary border-end-0"><i class="bi bi-mic-fill text-teal"></i></span>
            <select
              v-model="selectedVoiceURI"
              @change="onVoiceChanged"
              class="form-select border-start-0 py-2 fs-7"
            >
              <option value="">-- Mặc định (Google US English / Trình duyệt) --</option>
              <option
                v-for="v in speech.availableVoices.value"
                :key="v.voiceURI"
                :value="v.voiceURI"
              >
                {{ v.label }}
              </option>
            </select>
          </div>
          <div class="form-text fs-8 text-muted mt-1">
            Hệ thống tự động phát hiện tất cả các giọng đọc tiếng Anh cài đặt trên trình duyệt và thiết bị của bạn.
          </div>
        </div>

        <!-- Speech Speed -->
        <div class="col-12 col-md-4">
          <label class="form-label fs-7 fw-bold mb-1">Tốc độ phát âm:</label>
          <div class="btn-group w-100" role="group">
            <button
              type="button"
              @click="onSpeedChanged(0.75)"
              class="btn btn-sm rounded-start-pill py-2 fs-8 fw-bold"
              :class="currentSpeed === 0.75 ? 'btn-teal text-white' : 'btn-outline-secondary'"
            >
              0.75x (Chậm)
            </button>
            <button
              type="button"
              @click="onSpeedChanged(1.0)"
              class="btn btn-sm py-2 fs-8 fw-bold"
              :class="currentSpeed === 1.0 ? 'btn-teal text-white' : 'btn-outline-secondary'"
            >
              1.0x (Chuẩn)
            </button>
            <button
              type="button"
              @click="onSpeedChanged(1.25)"
              class="btn btn-sm rounded-end-pill py-2 fs-8 fw-bold"
              :class="currentSpeed === 1.25 ? 'btn-teal text-white' : 'btn-outline-secondary'"
            >
              1.25x (Nhanh)
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. TÙY CHỈNH THANH ĐIỀU HƯỚNG HEADER (MENU 6 MỤC) -->
    <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4" style="border-color: #E2E8F0;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-teal-dark text-white rounded-pill px-2.5 py-1 fs-8">NAV</span>
            <h5 class="fw-bold mb-0 text-teal">TÙY CHỈNH THANH ĐIỀU HƯỚNG HEADER (MENU 6 MỤC)</h5>
          </div>
          <div class="text-muted fs-7">
            Thanh Header gồm 6 mục: <strong>Trang chủ</strong> (Cố định vị trí 1), <strong>4 Mục bạn tự chọn</strong> (Vị trí 2 - 5), và <strong>Xem thêm ▾</strong> (Cố định vị trí 6 chứa các mục còn lại theo thứ tự bạn sắp xếp).
          </div>
        </div>
        <button
          @click="saveHeaderNav"
          class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7 d-flex align-items-center gap-1.5 shadow-sm"
          :disabled="isSavingNav"
        >
          <span v-if="isSavingNav" class="spinner-border spinner-border-sm me-1"></span>
          <i v-else class="bi bi-check2-circle fs-6"></i>
          <span>Lưu Cấu Hình Menu ({{ selectedNavIds.length }}/4)</span>
        </button>
      </div>

      <!-- Live Preview of Header Bar -->
      <div class="p-3 rounded-4 mb-3" style="background-color: rgba(6, 78, 64, 0.05); border: 1.5px dashed rgba(6, 78, 64, 0.2);">
        <div class="text-muted fs-8 fw-bold text-uppercase mb-2 d-flex align-items-center gap-1.5">
          <i class="bi bi-eye-fill text-teal"></i>
          <span>Mô Phỏng Giao Diện Thanh Header 6 Mục Của Bạn:</span>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
          <!-- Fixed 1: Trang chủ -->
          <div class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-7 fw-semibold d-flex align-items-center gap-1.5 shadow-xs">
            <i class="bi bi-house-door-fill text-primary"></i>
            <span>1. Trang chủ (Cố định)</span>
          </div>

          <!-- Dynamic Slots 2-5 -->
          <div
            v-for="(id, idx) in selectedNavIds"
            :key="id"
            class="badge text-white px-3 py-2 rounded-pill fs-7 fw-bold d-flex align-items-center gap-1.5 shadow-sm"
            style="background: linear-gradient(135deg, #00b074 0%, #064e40 100%);"
          >
            <i :class="[getNavOption(id)?.icon, 'text-warning']"></i>
            <span>{{ idx + 2 }}. {{ getNavOption(id)?.label }}</span>
            <div class="d-inline-flex gap-1 ms-1">
              <button
                v-if="idx > 0"
                @click.stop="moveHeaderItemUp(idx)"
                class="btn btn-sm btn-link p-0 text-white text-decoration-none lh-1"
                title="Di chuyển sang trái"
              >
                ◀
              </button>
              <button
                v-if="idx < selectedNavIds.length - 1"
                @click.stop="moveHeaderItemDown(idx)"
                class="btn btn-sm btn-link p-0 text-white text-decoration-none lh-1"
                title="Di chuyển sang phải"
              >
                ▶
              </button>
            </div>
            <button
              @click.stop="toggleNavItem(id)"
              class="btn-close btn-close-white p-0 ms-1"
              style="width: 8px; height: 8px;"
              title="Bỏ mục này"
            ></button>
          </div>

          <!-- Empty placeholders if < 4 -->
          <div
            v-for="emptyIdx in (4 - selectedNavIds.length)"
            :key="'empty-' + emptyIdx"
            class="badge bg-light text-muted border border-dashed px-3 py-2 rounded-pill fs-7 fw-normal"
          >
            + Chọn thêm {{ emptyIdx }} mục
          </div>

          <!-- Fixed 6: Xem thêm -->
          <div class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-7 fw-semibold d-flex align-items-center gap-1.5 shadow-xs">
            <i class="bi bi-grid-fill text-teal"></i>
            <span>6. Xem thêm ▾ ({{ unselectedNavIds.length }} mục phụ)</span>
          </div>
        </div>

        <!-- More Menu Order Reordering Panel -->
        <div v-if="unselectedNavIds.length > 0" class="mt-2.5 pt-2 border-top">
          <div class="text-muted fs-8 fw-semibold mb-1.5">
            <i class="bi bi-sort-numeric-down text-teal me-1"></i>
            <span>Thứ tự các mục sẽ hiển thị trong dropdown "Xem thêm ▾" (Nhấp mũi tên để điều chỉnh thứ tự):</span>
          </div>
          <div class="d-flex gap-1.5 flex-wrap">
            <span
              v-for="(id, mIdx) in unselectedNavIds"
              :key="id"
              class="badge bg-white text-dark border px-2.5 py-1.5 rounded-pill fs-8 d-inline-flex align-items-center gap-1 shadow-2xs"
            >
              <i :class="[getNavOption(id)?.icon, 'text-teal']"></i>
              <span>#{{ mIdx + 1 }}. {{ getNavOption(id)?.label }}</span>
              <button
                v-if="mIdx > 0"
                @click="moveMoreItemUp(mIdx)"
                class="btn btn-sm btn-link p-0 text-muted ms-1 text-decoration-none"
                title="Lên trên"
              >
                ▲
              </button>
              <button
                v-if="mIdx < unselectedNavIds.length - 1"
                @click="moveMoreItemDown(mIdx)"
                class="btn btn-sm btn-link p-0 text-muted ms-1 text-decoration-none"
                title="Xuống dưới"
              >
                ▼
              </button>
            </span>
          </div>
        </div>
      </div>

      <!-- Selectable items grid -->
      <div class="row g-2.5">
        <div v-for="opt in availableNavOptions" :key="opt.id" class="col-12 col-sm-6 col-lg-4 col-xl-2-4">
          <div
            @click="toggleNavItem(opt.id)"
            class="card p-3 rounded-4 border h-100 cursor-pointer transition-all user-select-none"
            :class="selectedNavIds.includes(opt.id) ? 'border-success bg-success-subtle shadow-xs' : 'border-light-subtle bg-white hover-card'"
            style="cursor: pointer;"
          >
            <div class="d-flex justify-content-between align-items-start mb-1.5">
              <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" :class="selectedNavIds.includes(opt.id) ? 'bg-success text-white' : 'bg-light ' + opt.color">
                <i :class="opt.icon"></i>
              </div>
              <span v-if="selectedNavIds.includes(opt.id)" class="badge bg-success rounded-pill px-2 py-1 fs-8">
                <i class="bi bi-check-lg me-0.5"></i>Vị trí {{ selectedNavIds.indexOf(opt.id) + 2 }}
              </span>
              <span v-else class="badge bg-light text-muted rounded-pill px-2 py-1 fs-8">
                Xem thêm ▾
              </span>
            </div>
            <div class="fw-bold fs-7 text-dark-emphasis mb-0.5">{{ opt.label }}</div>
            <div class="text-muted fs-8 leading-tight">{{ opt.desc }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. TIẾN ĐỘ CHI TIẾT 25 CHỦ ĐỀ LỘ TRÌNH IELTS (5.000 TỪ) -->
    <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4" style="border-color: #E2E8F0;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-teal-dark text-white rounded-pill px-2.5 py-1 fs-8">03</span>
            <h5 class="fw-bold mb-0 text-teal">TIẾN ĐỘ 25 CHỦ ĐỀ LỘ TRÌNH IELTS (5.000 TỪ)</h5>
          </div>
          <div class="text-muted fs-7">Chi tiết từng chủ đề, số bài hoàn thành và tỷ lệ ghi nhớ được đồng bộ tự động.</div>
        </div>
        <router-link to="/curriculum" class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8 d-flex align-items-center gap-1.5 shadow-xs">
          <i class="bi bi-signpost-split-fill"></i>
          <span>Vào học Lộ Trình →</span>
        </router-link>
      </div>

      <div class="row g-2.5">
        <div v-for="t in curriculumTopics" :key="t.id" class="col-12 col-md-6 col-lg-4">
          <router-link :to="`/curriculum`" class="card p-3 rounded-3 border text-decoration-none hover-card h-100 bg-white" style="border-color: #E2E8F0;">
            <div class="d-flex justify-content-between align-items-start mb-1.5">
              <span class="badge rounded-pill text-teal fw-bold bg-teal-subtle px-2 py-0.5" style="font-size: 10px;">
                CHỦ ĐỀ {{ String(t.order_num).padStart(2, '0') }}
              </span>
              <span 
                class="badge rounded-pill fw-bold" 
                :class="t.progress_percent >= 100 ? 'bg-success text-white' : (t.progress_percent > 0 ? 'bg-teal text-white' : 'bg-light text-muted border')" 
                style="font-size: 10px;"
              >
                {{ t.progress_percent }}%
              </span>
            </div>
            <div class="fw-bold fs-7 text-dark text-truncate">{{ t.title }}</div>
            <div class="text-muted fs-8 mb-2 text-truncate">{{ t.title_vi }}</div>
            <div class="d-flex justify-content-between align-items-center fs-8 text-muted mb-1">
              <span>{{ t.remembered_terms_count || 0 }}/200 từ đã nhớ</span>
              <span class="text-teal fw-semibold">{{ t.completed_stages_count || 0 }}/10 bài</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 999px;">
              <div 
                class="progress-bar rounded-pill" 
                :class="t.progress_percent >= 100 ? 'bg-success' : 'bg-teal'" 
                :style="{ width: `${t.progress_percent}%` }"
              ></div>
            </div>
          </router-link>
        </div>
      </div>
    </div>

    <!-- 4. Danger Zone: Reset Learning Progress Card -->
    <div class="card p-4 rounded-4 shadow-sm border border-danger-subtle bg-danger-subtle bg-opacity-25">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h6 class="fw-bold text-danger mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i> Thiết Lập Lại Quá Trình Học (Reset Progress)
          </h6>
          <p class="text-muted fs-7 mb-0">
            Nếu bạn muốn bắt đầu lại từ vựng và ngữ pháp từ đầu, tính năng này sẽ xóa sạch lịch sử làm bài kiểm tra và đưa số từ đã nhớ về 0.
          </p>
        </div>
        <button
          @click="showResetConfirm = true"
          class="btn btn-outline-danger btn-sm px-4 py-2 rounded-pill fw-bold text-nowrap shadow-sm"
        >
          <i class="bi bi-trash3 me-1"></i> Xóa & Làm lại từ đầu
        </button>
      </div>
    </div>

    <!-- Reset Confirmation Centered Modal -->
    <div
      v-if="showResetConfirm"
      class="modal fade show d-block"
      style="background: rgba(0, 0, 0, 0.65);"
      tabindex="-1"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-card-custom">
          <div class="modal-header border-bottom py-3">
            <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
              <i class="bi bi-exclamation-triangle-fill"></i> Xác nhận Reset Tiến Độ
            </h5>
            <button @click="showResetConfirm = false" type="button" class="btn-close" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <p class="fs-7 text-dark-emphasis mb-2">
              Bạn có chắc chắn muốn xóa toàn bộ tiến độ học từ vựng, ngữ pháp và lịch sử kiểm tra của tài khoản <strong>{{ authStore.userName }}</strong> không?
            </p>
            <div class="alert alert-warning py-2 px-3 fs-8 mb-0 rounded-3">
              <i class="bi bi-info-circle me-1"></i>Hành động này không thể hoàn tác. Điểm kinh nghiệm (XP) sẽ trở về 0 và chuỗi ngày học sẽ được đặt lại.
            </div>
          </div>
          <div class="modal-footer border-top py-2">
            <button @click="showResetConfirm = false" class="btn btn-secondary btn-sm px-3 rounded-pill">
              Hủy bỏ
            </button>
            <button
              @click="executeResetProgress"
              class="btn btn-danger btn-sm px-4 rounded-pill"
              :disabled="isResetting"
            >
              <span v-if="isResetting" class="spinner-border spinner-border-sm me-1"></span>
              Xác nhận Xóa Hết
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useSpeech } from '@/composables/useSpeech'
import axios from 'axios'

const authStore = useAuthStore()
const router = useRouter()
const speech = useSpeech()

const selectedVoiceURI = ref(localStorage.getItem('oxford_voice_uri') || '')
const currentSpeed = ref(parseFloat(localStorage.getItem('oxford_speech_rate') || '1.0'))

const onVoiceChanged = () => {
  speech.setVoice(selectedVoiceURI.value)
}

const onSpeedChanged = (spd) => {
  currentSpeed.value = spd
  speech.setSpeed(spd)
}

const testCurrentVoice = () => {
  speech.testVoice(selectedVoiceURI.value, 'Hello! Welcome to Oxford English learning system. This is your selected voice.')
}

const isSaving = ref(false)
const showResetConfirm = ref(false)
const isResetting = ref(false)
const isSavingNav = ref(false)

const availableNavOptions = [
  { id: 'curriculum', label: 'Lộ trình', desc: '25 Chủ đề IELTS 5000 từ', icon: 'bi bi-signpost-split-fill', color: 'text-success' },
  { id: 'daily', label: 'Học theo Ngày', desc: 'Lộ trình từng ngày 3000 từ', icon: 'bi bi-calendar-check-fill', color: 'text-warning' },
  { id: 'grammar', label: 'Ngữ pháp', desc: '12 thì và bài tập dịch câu', icon: 'bi bi-journal-bookmark-fill', color: 'text-danger' },
  { id: 'phrasal_verbs', label: 'Cụm từ', desc: '100 Phrasal Verbs & 1000 Cụm từ', icon: 'bi bi-puzzle-fill', color: 'text-info' },
  { id: 'dictionary', label: 'Tra cứu', desc: 'Từ điển tra cứu 3000 từ vựng', icon: 'bi bi-search-heart-fill', color: 'text-teal' },
  { id: 'ipa', label: '44 Âm IPA', desc: 'Bảng phiên âm chuẩn quốc tế', icon: 'bi bi-soundwave', color: 'text-primary' },
  { id: 'tips', label: 'Mẹo học', desc: 'Phương pháp & bí quyết học từ', icon: 'bi bi-lightbulb-fill', color: 'text-warning' },
  { id: 'tree', label: 'Cây tri thức', desc: 'Vườn cây tiến độ học tập', icon: 'bi bi-tree-fill', color: 'text-success' },
  { id: 'stats', label: 'Năng suất', desc: 'Biểu đồ giờ học & streak', icon: 'bi bi-bar-chart-fill', color: 'text-primary' },
  { id: 'topics', label: 'Chủ đề', desc: 'Danh mục chủ đề từ vựng', icon: 'bi bi-grid-1x2-fill', color: 'text-secondary' },
]

const selectedNavIds = ref([...(authStore.headerNavItems || ['curriculum', 'daily', 'grammar', 'phrasal_verbs'])])

const computeUnselectedNavIds = () => {
  const allIds = availableNavOptions.map(opt => opt.id)
  const moreOrder = authStore.moreNavOrder || []
  const unselected = allIds.filter(id => !selectedNavIds.value.includes(id))
  if (moreOrder.length > 0) {
    unselected.sort((a, b) => {
      const idxA = moreOrder.indexOf(a)
      const idxB = moreOrder.indexOf(b)
      if (idxA === -1 && idxB === -1) return 0
      if (idxA === -1) return 1
      if (idxB === -1) return -1
      return idxA - idxB
    })
  }
  return unselected
}

const unselectedNavIds = ref(computeUnselectedNavIds())

watch(selectedNavIds, () => {
  unselectedNavIds.value = computeUnselectedNavIds()
}, { deep: true })

const getNavOption = (id) => availableNavOptions.find(opt => opt.id === id)

const toggleNavItem = (id) => {
  if (selectedNavIds.value.includes(id)) {
    if (selectedNavIds.value.length <= 1) {
      alert('Vui lòng chọn ít nhất 1 mục hiển thị trên thanh Header!')
      return
    }
    selectedNavIds.value = selectedNavIds.value.filter(item => item !== id)
  } else {
    if (selectedNavIds.value.length >= 4) {
      alert('Tối đa 4 mục tùy chỉnh trên thanh Header (kết hợp với Trang chủ và Xem thêm là tròn 6 mục)! Hãy bỏ bớt 1 mục trước khi chọn thêm.')
      return
    }
    selectedNavIds.value.push(id)
  }
}

const moveHeaderItemUp = (idx) => {
  if (idx > 0) {
    const temp = selectedNavIds.value[idx]
    selectedNavIds.value[idx] = selectedNavIds.value[idx - 1]
    selectedNavIds.value[idx - 1] = temp
  }
}

const moveHeaderItemDown = (idx) => {
  if (idx < selectedNavIds.value.length - 1) {
    const temp = selectedNavIds.value[idx]
    selectedNavIds.value[idx] = selectedNavIds.value[idx + 1]
    selectedNavIds.value[idx + 1] = temp
  }
}

const moveMoreItemUp = (idx) => {
  if (idx > 0) {
    const temp = unselectedNavIds.value[idx]
    unselectedNavIds.value[idx] = unselectedNavIds.value[idx - 1]
    unselectedNavIds.value[idx - 1] = temp
  }
}

const moveMoreItemDown = (idx) => {
  if (idx < unselectedNavIds.value.length - 1) {
    const temp = unselectedNavIds.value[idx]
    unselectedNavIds.value[idx] = unselectedNavIds.value[idx + 1]
    unselectedNavIds.value[idx + 1] = temp
  }
}

const saveHeaderNav = async () => {
  if (selectedNavIds.value.length !== 4) {
    alert('Vui lòng chọn đúng 4 mục để thanh Header hiển thị chuẩn xác 6 mục!')
    return
  }
  isSavingNav.value = true
  try {
    await authStore.setHeaderNavAndMore(selectedNavIds.value, unselectedNavIds.value)
    alert('Đã lưu cấu hình menu Header và danh sách Xem thêm thành công!')
  } catch (err) {
    console.error(err)
    alert('Lỗi lưu cấu hình: ' + (err.response?.data?.message || err.message))
  } finally {
    isSavingNav.value = false
  }
}

const presetAvatars = [
  'https://api.dicebear.com/7.x/bottts/svg?seed=CuongPhung',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Felix',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Aneka',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Bella',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Max',
  'https://api.dicebear.com/7.x/bottts/svg?seed=Oliver',
]

const form = ref({
  name: '',
  avatar_url: '',
  birth_date: '',
  words_per_day: 15,
})

const curriculumTopics = ref([])

const curriculumRememberedCount = computed(() => {
  return authStore.stats?.curriculum_remembered_terms || 
    curriculumTopics.value.reduce((acc, t) => acc + (t.remembered_terms_count || 0), 0)
})

const formatFloatPercent = (val) => {
  if (val === null || val === undefined || isNaN(val)) return '0.00'
  const num = Number(val)
  if (num <= 0) return '0.00'
  if (num >= 100) return '100.00'
  return num.toFixed(2)
}

const curriculumPercent = computed(() => {
  if (authStore.stats?.curriculum_percent !== undefined) {
    return Number(authStore.stats.curriculum_percent)
  }
  return Math.min(100, ((curriculumRememberedCount.value || 0) / 5000) * 100)
})

const grammarPercent = computed(() => {
  const comp = authStore.stats?.completed_grammars || 0
  return Math.min(100, (comp / 15) * 100)
})

const vocabPercent = computed(() => {
  const comp = authStore.stats?.mastered_words || 0
  return Math.min(100, (comp / 3351) * 100)
})

const phrasesPercent = computed(() => {
  if (authStore.stats?.phrases_percent !== undefined) {
    return Number(authStore.stats.phrases_percent)
  }
  const comp = authStore.stats?.mastered_phrases || 0
  const total = authStore.stats?.total_phrases || 945
  return Math.min(100, (comp / total) * 100)
})

const plantStage = computed(() => {
  const xp = authStore.user?.xp_points || 0
  if (xp < 150) return 'Hạt giống ươm mầm'
  if (xp < 400) return 'Mầm non nảy chồi'
  if (xp < 800) return 'Cây con phát triển'
  if (xp < 1500) return 'Thân cây vươn cành'
  if (xp < 2500) return 'Tán lá xanh mướt'
  return 'Đơm hoa kết trái (Master)'
})

const saveProfile = async () => {
  isSaving.value = true
  await authStore.updateProfile(form.value)
  isSaving.value = false
  alert('Đã cập nhật thông tin thành công!')
}

const executeResetProgress = async () => {
  isResetting.value = true
  try {
    const res = await axios.post('/api/auth/reset-progress')
    await authStore.fetchMe()
    showResetConfirm.value = false
    alert(res.data.message || 'Đã đặt lại tiến độ học thành công!')
  } catch (err) {
    alert('Lỗi đặt lại tiến độ: ' + (err.response?.data?.message || err.message))
  } finally {
    isResetting.value = false
  }
}

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}

onMounted(async () => {
  await authStore.fetchMe()
  if (authStore.user) {
    form.value.name = authStore.user.name || 'Cường Phùng'
    form.value.avatar_url = authStore.user.avatar_url || presetAvatars[0]
    form.value.birth_date = authStore.user.birth_date ? authStore.user.birth_date.substring(0, 10) : '2005-10-18'
    form.value.words_per_day = authStore.user.words_per_day || 15
  }

  if (authStore.headerNavItems && Array.isArray(authStore.headerNavItems) && authStore.headerNavItems.length === 4) {
    selectedNavIds.value = [...authStore.headerNavItems]
  }

  // Load Curriculum 25 Topics Progress
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}
    const cRes = await axios.get('/api/curriculum', { headers })
    if (cRes.data?.data) {
      curriculumTopics.value = cRes.data.data
    }
  } catch (e) {
    console.error('Lỗi tải tiến độ lộ trình:', e)
  }
})
</script>
