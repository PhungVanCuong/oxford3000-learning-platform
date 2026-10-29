<template>
  <div class="curriculum-stage-detail-view min-vh-100 d-flex flex-column bg-light-subtle pb-5">
    <!-- 1. TOP PROGRESS BAR RUNNING ACROSS THE TOP -->
    <div class="w-100 bg-secondary-subtle" style="height: 4px;">
      <div 
        class="h-100 bg-success transition-all"
        :style="{ width: topProgressPercent + '%', transition: 'width 0.3s ease' }"
      ></div>
    </div>

    <!-- 2. STAGE NAVIGATION BAR (DESKTOP & MOBILE TÁCH BIỆT HOÀN HẢO) -->
    <div class="container-fluid px-2 px-md-4 px-xl-5 pt-2 pt-md-3 pb-2">
      <!-- 2A. DESKTOP STAGE NAVIGATION (>= 768px) -->
      <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white d-none d-md-block">
        <!-- Top Row: Back button, Topic Name, Current Stage Status & Mode Switcher -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2.5 mb-3">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <router-link 
              to="/curriculum" 
              class="btn btn-sm btn-outline-secondary rounded-pill px-3.5 py-1.5 d-flex align-items-center gap-1.5 fs-7 bg-white shadow-xs fw-semibold"
            >
              <i class="bi bi-arrow-left"></i>
              <span>Trở về lộ trình</span>
            </router-link>

            <span class="badge rounded-pill text-white px-3.5 py-2 fs-7 fw-bold" style="background-color: #008060;">
              {{ topic?.title || 'Education & Learning' }}
            </span>

            <span v-if="!isAllTermsMode && stagesProgress[currentStageNum]?.is_completed" class="badge rounded-pill px-3.5 py-2 fs-7 fw-bold d-flex align-items-center gap-1.5" style="background-color: #d1fae5; color: #065f46;">
              <i class="bi bi-check-circle-fill"></i>
              <span>Chặng {{ currentStageNum }} Đã Hoàn Thành ({{ stagesProgress[currentStageNum]?.score || getStageCount(currentStageNum) }}/{{ stagesProgress[currentStageNum]?.count || getStageCount(currentStageNum) }} từ)</span>
            </span>
            <span v-else-if="!isAllTermsMode" class="badge rounded-pill px-3.5 py-2 fs-7 fw-semibold d-flex align-items-center gap-1.5" style="background-color: #fef3c7; color: #92400e;">
              <i class="bi bi-clock-history"></i>
              <span>Chặng {{ currentStageNum }}: Đang học {{ stagesProgress[currentStageNum]?.score || 0 }}/{{ terms.length }} từ</span>
            </span>
          </div>

          <!-- Mode Toggle Pill: Thẻ 3D & Kiểm tra Trắc nghiệm -->
          <div class="d-flex align-items-center gap-2.5">
            <!-- Counter (e.g. 001 / 200 or 001 / 010) -->
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold fs-7 shadow-xs">
              {{ formatIndex(currentDisplayIndex + 1) }} / {{ formatIndex(totalCount) }}
            </span>

            <div v-if="!isAllTermsMode" class="btn-group shadow-xs rounded-pill border bg-light p-1">
              <button 
                class="btn btn-sm rounded-pill px-3.5 py-1.5 fs-7 fw-bold d-flex align-items-center gap-1.5"
                :class="activeMode === 'flashcard' ? 'btn-success text-white shadow-xs' : 'text-muted border-0 bg-transparent'"
                @click="activeMode = 'flashcard'"
              >
                <i class="bi bi-card-text"></i>
                <span>Thẻ 3D</span>
              </button>
              <button 
                class="btn btn-sm rounded-pill px-3.5 py-1.5 fs-7 fw-bold d-flex align-items-center gap-1.5"
                :class="activeMode === 'quiz' ? 'btn-success text-white shadow-xs' : 'text-muted border-0 bg-transparent'"
                @click="activeMode = 'quiz'"
              >
                <i class="bi bi-ui-checks"></i>
                <span>Trắc nghiệm</span>
              </button>
            </div>
          </div>
        </div>

        <!-- 10 Big, Clear Stage Buttons with Prev & Next Arrows (Row 2 in Image 2) -->
        <div v-if="!isAllTermsMode" class="position-relative pt-2.5 border-top">
          <div class="d-flex align-items-center justify-content-between gap-2.5">
            <!-- Left Arrow Button -->
            <button 
              class="btn btn-sm btn-outline-secondary rounded-pill px-2 px-md-3.5 py-1.5 py-md-2 fw-bold fs-8 fs-md-7 d-flex align-items-center gap-1 text-nowrap flex-shrink-0 bg-white shadow-xs"
              :disabled="currentStageNum <= 1"
              @click="switchStage(currentStageNum - 1)"
              title="Chặng trước"
            >
              <i class="bi bi-chevron-left"></i>
              <span><span class="d-none d-sm-inline">Chặng </span>Trước</span>
            </button>

            <!-- 10 Big Stage Pills in a Smooth Horizontally Auto-Centering Track -->
            <div 
              ref="stageScrollTrack"
              class="d-flex align-items-center gap-2 overflow-x-auto py-2 px-1 flex-grow-1 stage-scroll-track mobile-scroll-x no-scrollbar"
              style="scroll-behavior: smooth;"
            >
              <button
                v-for="s in 10"
                :key="s"
                :ref="el => { if (el) stageButtons[s] = el }"
                class="btn btn-sm rounded-pill px-3 py-1.5 py-md-2 fw-bold fs-8 fs-md-7 d-flex align-items-center gap-1.5 text-nowrap transition-all flex-shrink-0"
                :style="s === currentStageNum 
                  ? 'background-color: #008060; color: #ffffff !important; border: 1.5px solid #00664d; box-shadow: 0 4px 12px rgba(0, 128, 96, 0.25);' 
                  : ''"
                :class="s === currentStageNum 
                  ? '' 
                  : (stagesProgress[s]?.is_completed 
                      ? 'btn-outline-success bg-success-subtle text-success border border-success-subtle' 
                      : (stagesProgress[s]?.remembered_count > 0 
                          ? 'btn-outline-warning text-dark border border-warning-subtle' 
                          : 'btn-light text-muted border border-light-subtle bg-white'))"
                @click="switchStage(s)"
              >
                <i v-if="stagesProgress[s]?.is_completed && s !== currentStageNum" class="bi bi-check-circle-fill text-success"></i>
                <span class="fw-black fs-8 fs-md-7">{{ String(s).padStart(2, '0') }}</span>
                <span>Chặng {{ String(s).padStart(2, '0') }}</span>
                <span 
                  class="badge rounded-pill px-1.5 py-0.5" 
                  :class="s === currentStageNum ? 'bg-white text-teal' : 'bg-secondary bg-opacity-10 text-muted'" 
                  style="font-size: 10px;"
                >
                  {{ getStageCount(s) }} từ
                </span>
              </button>
            </div>

            <!-- Right Arrow Button -->
            <button 
              class="btn btn-sm btn-outline-teal rounded-pill px-2 px-md-3.5 py-1.5 py-md-2 fw-bold fs-8 fs-md-7 d-flex align-items-center gap-1 text-nowrap flex-shrink-0 shadow-xs"
              :disabled="currentStageNum >= 10"
              @click="switchStage(currentStageNum + 1)"
              title="Chặng tiếp theo"
            >
              <span><span class="d-none d-sm-inline">Chặng </span>Sau</span>
              <i class="bi bi-chevron-right"></i>
            </button>
          </div>

          <!-- Bottom Continuous Progress Rail Across Stages (Matching Image 2) -->
          <div class="mt-2.5 position-relative" style="height: 4px; background-color: #E2E8F0; border-radius: 999px;">
            <div 
              class="h-100 rounded-pill"
              :style="{
                width: `${(currentStageNum / 10) * 100}%`,
                backgroundColor: '#00b074',
                transition: 'width 0.35s cubic-bezier(0.4, 0, 0.2, 1)'
              }"
            ></div>
          </div>
        </div>
      </div>

      <!-- 2B. MOBILE STAGE NAVIGATION (< 768px, ĐỈNH CAO THỤT RA - THỤT VÀO & CHỐNG TRÀN) -->
      <div class="card border-0 shadow-sm rounded-4 p-2.5 bg-white d-md-none">
        <!-- Mobile Row 1: Back + Topic Pill with Drawer Trigger + Mode Toggle -->
        <div class="d-flex align-items-center justify-content-between gap-1.5 mb-2">
          <router-link 
            to="/curriculum" 
            class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 d-flex align-items-center gap-1 fs-8 bg-white shadow-2xs fw-semibold flex-shrink-0"
          >
            <i class="bi bi-arrow-left"></i>
            <span>Lộ trình</span>
          </router-link>

          <!-- Nút Thụt Ra 25 Chủ Đề -->
          <button
            @click="isTopicsDrawerOpen = true"
            class="btn btn-sm rounded-pill px-2.5 py-1 fw-bold fs-8 d-flex align-items-center gap-1 text-white text-truncate shadow-2xs"
            style="background-color: #008060; max-width: 155px;"
            title="Bấm để đổi chủ đề khác"
          >
            <i class="bi bi-book-half"></i>
            <span class="text-truncate">{{ topic?.title || 'Chủ đề' }}</span>
            <i class="bi bi-chevron-down fs-9"></i>
          </button>

          <!-- Mode Toggle Pill: Thẻ 3D & Trắc nghiệm -->
          <div v-if="!isAllTermsMode" class="btn-group btn-group-sm rounded-pill border bg-light p-0.5 shadow-2xs flex-shrink-0">
            <button 
              class="btn btn-sm rounded-pill px-2 py-0.5 fs-8 fw-bold"
              :class="activeMode === 'flashcard' ? 'btn-success text-white shadow-xs' : 'text-muted border-0 bg-transparent'"
              @click="activeMode = 'flashcard'"
            >
              Thẻ 3D
            </button>
            <button 
              class="btn btn-sm rounded-pill px-2 py-0.5 fs-8 fw-bold"
              :class="activeMode === 'quiz' ? 'btn-success text-white shadow-xs' : 'text-muted border-0 bg-transparent'"
              @click="activeMode = 'quiz'"
            >
              Quiz
            </button>
          </div>
        </div>

        <!-- Mobile Row 2: 10 Chặng Selector & Drawer Trigger -->
        <div v-if="!isAllTermsMode" class="d-flex align-items-center justify-content-between gap-1.5 pt-1.5 border-top">
          <!-- Chặng Trước -->
          <button 
            class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1 fw-bold fs-8 d-flex align-items-center gap-0.5 bg-white shadow-2xs flex-shrink-0"
            :disabled="currentStageNum <= 1"
            @click="switchStage(currentStageNum - 1)"
          >
            <i class="bi bi-chevron-left"></i>
            <span>Trước</span>
          </button>

          <!-- Nút Thụt Ra 10 Chặng Học -->
          <button
            @click="isStagesDrawerOpen = true"
            class="btn btn-sm btn-teal text-white rounded-pill px-2.5 py-1 fw-bold fs-8 d-flex align-items-center justify-content-center gap-1.5 shadow-2xs flex-grow-1 mx-1 text-truncate"
            style="background: linear-gradient(135deg, #00b074 0%, #064e40 100%);"
            title="Bấm để xem danh sách 10 chặng"
          >
            <i class="bi bi-list-check"></i>
            <span class="text-truncate">Chặng {{ String(currentStageNum).padStart(2, '0') }}: {{ getStageCount(currentStageNum) }} từ</span>
            <i class="bi bi-chevron-down fs-9"></i>
          </button>

          <!-- Chặng Sau -->
          <button 
            class="btn btn-sm btn-outline-teal rounded-pill px-2 py-1 fw-bold fs-8 d-flex align-items-center gap-0.5 shadow-2xs flex-shrink-0"
            :disabled="currentStageNum >= 10"
            @click="switchStage(currentStageNum + 1)"
          >
            <span>Sau</span>
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>

        <!-- Mobile Continuous Progress Rail -->
        <div v-if="!isAllTermsMode" class="mt-2 position-relative" style="height: 4px; background-color: #E2E8F0; border-radius: 999px;">
          <div 
            class="h-100 rounded-pill"
            :style="{
              width: `${(currentStageNum / 10) * 100}%`,
              backgroundColor: '#00b074',
              transition: 'width 0.35s cubic-bezier(0.4, 0, 0.2, 1)'
            }"
          ></div>
        </div>
      </div>
    </div>

    <!-- ================= DRAWER 1: CHỌN 25 CHỦ ĐỀ (THỤT RA - THỤT VÀO) ================= -->
    <div
      v-if="isTopicsDrawerOpen"
      class="topics-drawer-overlay"
      @click="isTopicsDrawerOpen = false"
    ></div>

    <aside
      class="topics-drawer-content d-flex flex-column p-3 shadow-lg"
      :class="isTopicsDrawerOpen ? 'show' : ''"
    >
      <!-- Header -->
      <div class="d-flex align-items-center justify-content-between pb-2.5 mb-2.5 border-bottom">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-black shadow-xs" style="width: 36px; height: 36px; background: linear-gradient(135deg, #00b074 0%, #064e40 100%);">
            Ox
          </div>
          <div>
            <h6 class="fw-black mb-0 text-dark leading-tight">25 CHỦ ĐỀ LỘ TRÌNH</h6>
            <span class="text-muted fs-8">Chọn chủ đề để chuyển bài</span>
          </div>
        </div>

        <button
          @click="isTopicsDrawerOpen = false"
          class="btn btn-sm btn-danger text-white rounded-pill px-2.5 py-1 fw-bold fs-8 d-flex align-items-center gap-1 shadow-sm"
        >
          <i class="bi bi-x-lg fs-8"></i>
          <span>Thụt lại</span>
        </button>
      </div>

      <!-- Search in Drawer -->
      <div class="mb-2.5 position-relative">
        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-teal fs-8"></i>
        <input
          v-model="searchTopicQuery"
          type="text"
          class="form-control form-control-sm rounded-pill ps-5 pe-3 py-1.5 fs-8"
          placeholder="Tìm kiếm chủ đề (VD: Education, City...)"
        />
      </div>

      <!-- List of 25 Topics -->
      <div class="d-flex flex-column gap-2 overflow-y-auto flex-grow-1 pr-1">
        <div
          v-for="t in filteredTopics"
          :key="t.id"
          @click="selectTopic(t)"
          class="p-2.5 rounded-3 border transition-all cursor-pointer"
          :class="Number(topicId) === Number(t.id) ? 'border-teal bg-success-subtle shadow-xs' : 'bg-white border-light-subtle hover-shadow'"
        >
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2.5 min-w-0">
              <div
                class="rounded-2 d-flex align-items-center justify-content-center text-white fw-black fs-8 flex-shrink-0 shadow-2xs"
                style="width: 32px; height: 32px; background-color: #008060;"
              >
                {{ String(t.order_num).padStart(2, '0') }}
              </div>
              <div class="text-truncate">
                <div class="fw-bold fs-7 text-dark text-truncate">{{ t.title }}</div>
                <div class="text-muted fs-8 text-truncate">{{ t.title_vi }}</div>
              </div>
            </div>
            <div class="d-flex align-items-center gap-1.5 flex-shrink-0 ms-2">
              <span class="badge rounded-pill" :class="t.progress_percent >= 100 ? 'bg-success text-white' : 'bg-light text-muted border'" style="font-size: 10px;">
                {{ t.progress_percent || 0 }}%
              </span>
              <i v-if="Number(topicId) === Number(t.id)" class="bi bi-check-circle-fill text-success fs-7"></i>
            </div>
          </div>
        </div>
      </div>
    </aside>

    <!-- ================= DRAWER 2: CHỌN 10 CHẶNG BÀI HỌC (THỤT RA - THỤT VÀO) ================= -->
    <div
      v-if="isStagesDrawerOpen"
      class="topics-drawer-overlay"
      @click="isStagesDrawerOpen = false"
    ></div>

    <aside
      class="topics-drawer-content d-flex flex-column p-3 shadow-lg"
      :class="isStagesDrawerOpen ? 'show' : ''"
      style="max-width: 340px;"
    >
      <!-- Header -->
      <div class="d-flex align-items-center justify-content-between pb-2.5 mb-2.5 border-bottom">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-black shadow-xs" style="width: 36px; height: 36px; background: linear-gradient(135deg, #00b074 0%, #064e40 100%);">
            <i class="bi bi-signpost-split-fill fs-6"></i>
          </div>
          <div class="min-w-0">
            <h6 class="fw-black mb-0 text-dark leading-tight text-truncate">10 CHẶNG HỌC</h6>
            <span class="text-muted fs-8 text-truncate d-block">{{ topic?.title }}</span>
          </div>
        </div>

        <button
          @click="isStagesDrawerOpen = false"
          class="btn btn-sm btn-danger text-white rounded-pill px-2.5 py-1 fw-bold fs-8 d-flex align-items-center gap-1 shadow-sm flex-shrink-0 ms-2"
        >
          <i class="bi bi-x-lg fs-8"></i>
          <span>Thụt lại</span>
        </button>
      </div>

      <!-- List of 10 Stages -->
      <div class="d-flex flex-column gap-2 overflow-y-auto flex-grow-1 pr-1">
        <div
          v-for="s in 10"
          :key="s"
          @click="selectStage(s)"
          class="p-2.5 rounded-3 border transition-all cursor-pointer"
          :class="s === currentStageNum ? 'border-teal bg-success-subtle shadow-xs' : 'bg-white border-light-subtle hover-shadow'"
        >
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2.5 min-w-0">
              <div
                class="rounded-circle d-flex align-items-center justify-content-center fw-black fs-8 flex-shrink-0 text-white shadow-2xs"
                :style="{
                  width: '32px',
                  height: '32px',
                  backgroundColor: s === currentStageNum ? '#00b074' : (stagesProgress[s]?.is_completed ? '#10b981' : '#64748b')
                }"
              >
                <i v-if="stagesProgress[s]?.is_completed" class="bi bi-check-lg fs-7"></i>
                <span v-else>{{ String(s).padStart(2, '0') }}</span>
              </div>
              <div class="text-truncate">
                <div class="fw-bold fs-7 text-dark text-truncate">
                  Chặng {{ String(s).padStart(2, '0') }} • {{ s === 1 ? '10 thẻ từ vựng' : `${getStageCount(s)} câu trắc nghiệm` }}
                </div>
                <div class="fs-8 text-muted">
                  {{ stagesProgress[s]?.is_completed ? `Đã hoàn thành (${stagesProgress[s]?.score || getStageCount(s)}/${stagesProgress[s]?.count || getStageCount(s)} từ)` : (stagesProgress[s]?.remembered_count > 0 ? `Đang học (${stagesProgress[s]?.score || 0}/${getStageCount(s)} từ)` : 'Chưa bắt đầu') }}
                </div>
              </div>
            </div>

            <i v-if="s === currentStageNum" class="bi bi-check-circle-fill text-success fs-6 ms-2"></i>
            <i v-else-if="stagesProgress[s]?.is_completed" class="bi bi-patch-check-fill text-success fs-7 ms-2"></i>
            <i v-else class="bi bi-chevron-right text-muted fs-8 ms-2"></i>
          </div>
        </div>
      </div>
    </aside>

    <!-- 3. TITLE & BREADCRUMB -->
    <div class="container max-w-5xl text-center py-2">
      <div class="fs-8 text-muted text-uppercase fw-bold letter-spacing-1 mb-1">
        {{ isAllTermsMode ? 'THƯ VIỆN CHỦ ĐỀ • TOÀN BỘ 200 THẺ TỪ' : `GIAI ĐOẠN ${String(currentStageNum).padStart(2, '0')} • ${terms.length} THẺ TỪ` }}
      </div>
      <h2 class="display-6 fw-black text-dark-emphasis mb-1">
        {{ topic?.title || 'English Vocabulary' }}
      </h2>
      <p class="text-muted fs-7 mb-0">
        {{ isAllTermsMode ? 'Xem lại toàn bộ 200 mục từ của chủ đề. Bạn có thể luyện phát âm và ghi nhớ trực tiếp.' : `${topic?.title_vi || ''} - Học và ghi nhớ chuẩn phản xạ.` }}
      </p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-5 my-5">
      <div class="spinner-border text-teal" role="status"></div>
      <p class="text-muted mt-2 fs-7">Đang nạp dữ liệu bài học...</p>
    </div>

    <!-- MAIN BODY -->
    <div v-else class="container py-2 flex-grow-1 d-flex flex-column justify-content-center" style="max-width: 960px;">
      <!-- ================= MODE 1: 3D FLASHCARD ================= -->
      <div v-if="activeMode === 'flashcard'" class="flashcard-container mx-auto w-100" style="max-width: 920px;">
        <!-- Helper Tips Banner (Desktop only) -->
        <div class="alert alert-primary bg-primary-subtle border-primary-subtle text-primary-emphasis d-none d-md-flex align-items-center justify-content-between py-2 px-3 mb-3 rounded-3 shadow-xs fs-7">
          <div>
            <i class="bi bi-lightbulb-fill text-warning me-1"></i>
            <strong>Phím tắt:</strong> <code>Space</code>: Lật thẻ | <code>Q</code>: Trước | <code>E</code>: Tiếp | <code>A</code>: Nghe | <code>D</code>: Luyện nói | <code>S</code>: Đã nhớ | <code>X</code>: Cần ôn
          </div>
          <div class="badge bg-primary text-white ms-2 fs-7 px-2.5 py-1">
            {{ currentCardIndex + 1 }} / {{ terms.length }}
          </div>
        </div>

        <!-- Mobile Touch Helper Pill -->
        <div class="d-md-none d-flex align-items-center justify-content-between p-2 px-3 rounded-pill bg-white border shadow-2xs mb-2.5 fs-8">
          <span class="text-muted d-flex align-items-center gap-1.5">
            <i class="bi bi-hand-index-thumb text-teal fs-7"></i>
            <span>Chạm thẻ để lật xem nghĩa tiếng Việt</span>
          </span>
          <span class="badge bg-teal text-white fw-bold">
            Thẻ {{ currentCardIndex + 1 }} / {{ terms.length }}
          </span>
        </div>

        <!-- 3D Card (Dark Forest Teal) -->
        <div class="perspective-1000 mb-3" style="min-height: 430px;">
          <div 
            class="flashcard-3d-inner w-100 h-100 position-relative rounded-4 shadow-lg cursor-pointer transform-style-preserve-3d"
            :class="{ 'rotate-y-180': isFlipped }"
            style="min-height: 430px; transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);"
            @click="isFlipped = !isFlipped"
          >
            <!-- FRONT FACE: Dark Forest Teal with Wave Decoration -->
            <div 
              class="card h-100 w-100 position-absolute top-0 start-0 backface-hidden rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between text-white border-0 overflow-hidden"
              style="background: linear-gradient(145deg, #02231d 0%, #04382f 40%, #064e40 100%);"
            >
              <!-- Top Card Info: Category + Status -->
              <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <span class="fs-8 fw-bold letter-spacing-1 text-uppercase" style="color: #5eead4;">
                  COLLOCATION • IELTS & OXFORD 3000™
                </span>
                <span 
                  class="badge px-3 py-1.5 rounded-pill fw-bold fs-8 shadow-xs"
                  :class="currentTerm?.is_remembered ? 'bg-success text-white' : 'text-white'"
                  :style="!currentTerm?.is_remembered ? 'background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.25);' : ''"
                >
                  {{ currentTerm?.is_remembered ? '✔ ĐÃ GHI NHỚ' : 'ĐANG HỌC' }}
                </span>
              </div>

              <!-- Main English Word / Term -->
              <div class="text-center my-3 position-relative z-1">
                <h1 class="display-5 display-md-4 fw-black text-white mb-2 tracking-tight" style="letter-spacing: -0.5px; word-break: break-word;">
                  {{ currentTerm?.term }}
                </h1>

                <!-- Part of Speech Badge -->
                <div v-if="currentTerm?.part_of_speech" class="mb-2">
                  <span class="badge px-3 py-1 rounded-pill text-uppercase fs-8 fw-bold" style="background-color: rgba(94, 234, 212, 0.2); color: #5eead4; border: 1px solid rgba(94, 234, 212, 0.35);">
                    {{ currentTerm.part_of_speech }}
                  </span>
                </div>

                <!-- Pronunciation Transcription & Audio Pill Button -->
                <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                  <span class="fs-6 fst-italic" style="color: #a7f3d0;">
                    {{ currentTermPhonetic }}
                  </span>
                  <button 
                    type="button"
                    class="btn btn-sm text-white rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center gap-1.5 shadow-xs"
                    style="background-color: #032b24; border: 1px solid rgba(255,255,255,0.3);"
                    @click.stop="speakWord(currentTerm?.term)"
                  >
                    <i class="bi bi-volume-up-fill text-warning"></i>
                    <span>NGHE US</span>
                  </button>
                </div>

                <!-- Speech Practice Result Box (Clean Glassmorphism with High Contrast) -->
                <div 
                  v-if="speechScore !== null || isListening" 
                  class="mt-3 px-3 py-2 rounded-pill d-inline-flex align-items-center gap-2 shadow-sm"
                  :style="{
                    backgroundColor: isListening ? 'rgba(239, 68, 68, 0.25)' : 'rgba(0, 0, 0, 0.55)',
                    border: isListening ? '1px solid rgba(239, 68, 68, 0.6)' : '1px solid rgba(255, 255, 255, 0.25)',
                    backdropFilter: 'blur(8px)'
                  }"
                >
                  <i :class="isListening ? 'bi bi-mic-fill text-danger animate-pulse' : 'bi bi-check-circle-fill text-warning'"></i>
                  <span class="fw-bold fs-7" style="color: #facc15;">
                    {{ isListening ? 'Đang lắng nghe giọng bạn...' : `Điểm phát âm: ${speechScore}%` }}
                  </span>
                  <span v-if="recognizedText && !isListening" class="fs-8 text-white-50">
                    "{{ recognizedText }}"
                  </span>
                </div>
              </div>

              <!-- Flip Hint at Bottom -->
              <div class="text-center text-white-50 fs-8 position-relative z-1">
                <i class="bi bi-arrow-repeat me-1"></i>Chạm để lật thẻ xem nghĩa tiếng Việt & câu ví dụ
              </div>

              <!-- Decorative SVG Waves Background -->
              <div class="position-absolute end-0 bottom-0 pointer-events-none" style="width: 240px; height: 180px; opacity: 0.85;">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                  <path fill="#00b074" d="M47.5,-57.2C59.7,-48.1,66.8,-32.1,68.9,-15.8C71,0.5,68,17.2,59.9,30.8C51.8,44.4,38.6,55,23.6,61.4C8.6,67.8,-8.2,70,-23.7,65.3C-39.2,60.6,-53.4,49,-62.4,34.2C-71.4,19.4,-75.2,1.3,-71.4,-14.8C-67.7,-30.9,-56.3,-45,-42.6,-53.8C-28.9,-62.6,-14.5,-66.1,1.1,-67.4C16.6,-68.7,35.2,-66.3,47.5,-57.2Z" transform="translate(100 100)" />
                </svg>
              </div>
            </div>

            <!-- BACK FACE: Vietnamese Translation & Pattern (White Card) -->
            <div 
              class="card h-100 w-100 position-absolute top-0 start-0 backface-hidden rotate-y-180 rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between bg-white text-dark border-0 shadow-lg"
              style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);"
            >
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-success-subtle text-success fw-bold fs-8 px-3 py-1 rounded-pill">
                  NGHĨA TIẾNG VIỆT
                </span>
                <span class="fs-8 text-muted">
                  <i class="bi bi-arrow-repeat me-1"></i>Chạm để quay lại
                </span>
              </div>

              <div class="my-3">
                <h3 class="fw-black text-success mb-3">
                  {{ currentTerm?.definition }}
                </h3>

                <div v-if="currentTerm?.pattern" class="p-2.5 rounded-3 bg-light text-muted fs-7 mb-3 border">
                  <i class="bi bi-diagram-2 me-1 text-teal"></i>
                  <strong>Mẫu câu / Cấu trúc:</strong> {{ currentTerm.pattern }}
                </div>

                <div v-if="currentTerm?.examples" class="p-3 rounded-3 bg-light border">
                  <div class="fw-bold fs-7 text-dark-emphasis mb-1 d-flex align-items-center gap-1.5">
                    <i class="bi bi-quote text-teal"></i>
                    <span>Ví dụ thực tế:</span>
                  </div>
                  <ul class="list-unstyled mb-0 fs-7">
                    <li 
                      v-for="(ex, eIdx) in parsedExamples(currentTerm?.examples)" 
                      :key="eIdx"
                      class="mb-1 text-dark-emphasis"
                    >
                      • <em>{{ ex }}</em>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="text-center text-muted fs-8">
                Nhấn phím [S] để đánh dấu "Đã nhớ" hoặc [Space] để lật thẻ
              </div>
            </div>
          </div>
        </div>

        <!-- CONTROLS: 1. DESKTOP SINGLE-ROW CONTROLS (>= 768px, giữ nguyên phím tắt) -->
        <div class="d-none d-md-flex align-items-center justify-content-center gap-1.5 flex-nowrap overflow-x-auto py-1 w-100 flashcard-controls-row mb-4">
          <!-- [Q] Trước -->
          <button 
            type="button"
            class="btn btn-outline-secondary btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-semibold"
            :disabled="currentCardIndex === 0"
            @click="prevCard"
            title="Phím [Q] hoặc mũi tên trái"
          >
            <i class="bi bi-chevron-left"></i>
            <span><strong>[Q]</strong> Trước</span>
          </button>

          <!-- [Space] Lật thẻ -->
          <button 
            type="button"
            class="btn btn-teal text-white btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold"
            @click="isFlipped = !isFlipped"
            title="Phím [Space]"
          >
            <i class="bi bi-arrow-repeat"></i>
            <span><strong>[Space]</strong> Lật</span>
          </button>

          <!-- [A] Nghe US -->
          <button 
            type="button"
            class="btn btn-primary btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold"
            @click="speakWord(currentTerm?.term)"
            title="Phím [A]"
          >
            <i class="bi bi-volume-up-fill"></i>
            <span><strong>[A]</strong> Nghe US</span>
          </button>

          <!-- [D] Luyện nói -->
          <button 
            type="button"
            class="btn btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold"
            :class="isListening ? 'btn-danger animate-mic-pulse' : 'btn-info text-white'"
            @click="startSpeechRecognition"
            title="Phím [D]"
          >
            <i class="bi" :class="isListening ? 'bi-record-fill' : 'bi-mic-fill'"></i>
            <span><strong>[D]</strong> {{ isListening ? 'Đang nghe...' : 'Luyện nói' }}</span>
          </button>

          <!-- [S] Đã nhớ -->
          <button 
            type="button"
            class="btn btn-success btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold"
            @click="markTermRemembered"
            title="Phím [S]"
          >
            <i class="bi bi-check-circle-fill"></i>
            <span><strong>[S]</strong> Đã nhớ</span>
          </button>

          <!-- [X] Cần ôn -->
          <button 
            type="button"
            class="btn btn-warning text-dark btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold"
            @click="markTermLearning"
            title="Phím [X]"
          >
            <i class="bi bi-arrow-clockwise"></i>
            <span><strong>[X]</strong> Cần ôn</span>
          </button>

          <!-- [E] Tiếp -->
          <button 
            type="button"
            class="btn btn-outline-secondary btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-semibold"
            :disabled="currentCardIndex >= terms.length - 1"
            @click="nextCard"
            title="Phím [E] hoặc mũi tên phải"
          >
            <span><strong>[E]</strong> Tiếp</span>
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>

        <!-- CONTROLS: 2. MOBILE DEDICATED 2-ROW ERGONOMIC TOUCH KEYPAD (< 768px, CHỐNG TRÀN 100%) -->
        <div class="d-flex d-md-none flex-column gap-2 w-100 mb-4 px-1">
          <!-- Hàng 1: Điều hướng & Lật Thẻ Xem Nghĩa (3 Nút Lớn Rộng Rãi) -->
          <div class="d-flex align-items-center gap-2 w-100">
            <!-- Trước -->
            <button 
              type="button"
              class="btn btn-outline-secondary btn-sm rounded-3 py-2 px-2.5 d-flex align-items-center justify-content-center gap-1 fw-bold fs-7 shadow-2xs"
              style="flex: 1; height: 44px;"
              :disabled="currentCardIndex === 0"
              @click="prevCard"
            >
              <i class="bi bi-chevron-left"></i>
              <span>Trước</span>
            </button>

            <!-- Lật Thẻ Trung Tâm (Nổi bật, to rõ, dễ chạm) -->
            <button 
              type="button"
              class="btn btn-teal text-white btn-sm rounded-3 py-2 px-3 d-flex align-items-center justify-content-center gap-1.5 fw-bold fs-7 shadow-sm"
              style="flex: 2; height: 44px; background: linear-gradient(135deg, #00b074 0%, #064e40 100%);"
              @click="isFlipped = !isFlipped"
            >
              <i class="bi bi-arrow-repeat fs-6"></i>
              <span>Lật Xem Nghĩa</span>
            </button>

            <!-- Tiếp -->
            <button 
              type="button"
              class="btn btn-outline-secondary btn-sm rounded-3 py-2 px-2.5 d-flex align-items-center justify-content-center gap-1 fw-bold fs-7 shadow-2xs"
              style="flex: 1; height: 44px;"
              :disabled="currentCardIndex >= terms.length - 1"
              @click="nextCard"
            >
              <span>Tiếp</span>
              <i class="bi bi-chevron-right"></i>
            </button>
          </div>

          <!-- Hàng 2: Tương tác Âm thanh & Đánh giá (4 Nút Cân Bằng Đồng Đều) -->
          <div class="d-flex align-items-center gap-1.5 w-100">
            <!-- Nghe US -->
            <button 
              type="button"
              class="btn btn-primary btn-sm rounded-3 py-1.5 px-1 d-flex flex-column align-items-center justify-content-center gap-0.5 fw-bold text-nowrap shadow-2xs"
              style="flex: 1; height: 48px; font-size: 11px;"
              @click="speakWord(currentTerm?.term)"
            >
              <i class="bi bi-volume-up-fill fs-6"></i>
              <span>Nghe US</span>
            </button>

            <!-- Luyện Nói -->
            <button 
              type="button"
              class="btn btn-sm rounded-3 py-1.5 px-1 d-flex flex-column align-items-center justify-content-center gap-0.5 fw-bold text-nowrap shadow-2xs"
              :class="isListening ? 'btn-danger animate-mic-pulse' : 'btn-info text-white'"
              style="flex: 1; height: 48px; font-size: 11px;"
              @click="startSpeechRecognition"
            >
              <i class="bi fs-6" :class="isListening ? 'bi-record-fill' : 'bi-mic-fill'"></i>
              <span>{{ isListening ? 'Đang nghe...' : 'Luyện nói' }}</span>
            </button>

            <!-- Đã Nhớ -->
            <button 
              type="button"
              class="btn btn-success btn-sm rounded-3 py-1.5 px-1 d-flex flex-column align-items-center justify-content-center gap-0.5 fw-bold text-nowrap shadow-2xs text-white"
              style="flex: 1; height: 48px; font-size: 11px;"
              @click="markTermRemembered"
            >
              <i class="bi bi-check-circle-fill fs-6"></i>
              <span>Đã nhớ</span>
            </button>

            <!-- Cần Ôn -->
            <button 
              type="button"
              class="btn btn-warning text-dark btn-sm rounded-3 py-1.5 px-1 d-flex flex-column align-items-center justify-content-center gap-0.5 fw-bold text-nowrap shadow-2xs"
              style="flex: 1; height: 48px; font-size: 11px;"
              @click="markTermLearning"
            >
              <i class="bi bi-arrow-clockwise fs-6"></i>
              <span>Cần ôn</span>
            </button>
          </div>
        </div>
      </div>

      <!-- ================= MODE 2: MULTIPLE-CHOICE QUIZ ================= -->
      <div v-else class="quiz-container">
        <!-- Quiz Header Info -->
        <div class="card border-0 bg-white shadow-sm rounded-4 p-4 mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fs-7 fw-bold text-dark-emphasis">
              Câu hỏi <strong>{{ currentQuestionIndex + 1 }}</strong> / {{ quizQuestions.length }}
            </span>
            <span class="fs-7 fw-bold text-teal">
              Đúng {{ quizScore }} / {{ currentQuestionIndex + (hasAnsweredCurrent ? 1 : 0) }} câu
            </span>
          </div>
          <div class="progress" style="height: 8px; border-radius: 4px;">
            <div 
              class="progress-bar bg-teal progress-bar-striped progress-bar-animated"
              :style="{ width: ((currentQuestionIndex + (hasAnsweredCurrent ? 1 : 0)) / quizQuestions.length) * 100 + '%' }"
            ></div>
          </div>
        </div>

        <!-- Question Card -->
        <div v-if="currentQuestion" class="card border-0 bg-white shadow-sm rounded-4 p-4 p-md-5 mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-secondary-subtle text-secondary-emphasis fw-bold fs-8 px-3 py-1.5 rounded-pill">
              TRẮC NGHIỆM TỪ VỰNG
            </span>
            <button 
              class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 d-flex align-items-center gap-1.5 fs-8"
              @click="speakWord(currentQuestion.term)"
            >
              <i class="bi bi-volume-up-fill"></i>
              <span>Phát âm</span>
            </button>
          </div>

          <div class="my-3">
            <div class="text-muted fs-7 mb-1">{{ currentQuestion.question_text }}</div>
            <h2 class="display-6 fw-black text-teal mb-2">{{ currentQuestion.term }}</h2>
            <div v-if="currentQuestion.part_of_speech" class="badge bg-light text-muted border mb-3">
              {{ currentQuestion.part_of_speech }}
            </div>
            <div v-if="currentQuestion.sample_example" class="p-3 rounded-3 bg-light border text-muted fs-7 mb-4">
              <i class="bi bi-chat-quote me-1 text-teal"></i>
              <em>"{{ currentQuestion.sample_example }}"</em>
            </div>
          </div>

          <!-- Options -->
          <div class="row g-3">
            <div 
              v-for="(option, optIdx) in currentQuestion.options" 
              :key="optIdx"
              class="col-12 col-md-6"
            >
              <button
                class="btn w-100 p-3 text-start d-flex align-items-center justify-content-between rounded-3 border option-btn"
                :class="getOptionButtonClass(optIdx)"
                :disabled="hasAnsweredCurrent"
                @click="handleSelectOption(optIdx)"
              >
                <div class="d-flex align-items-center gap-2.5">
                  <span 
                    class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-8 flex-shrink-0"
                    :class="getOptionLetterClass(optIdx)"
                    style="width: 28px; height: 28px;"
                  >
                    {{ ['A', 'B', 'C', 'D'][optIdx] }}
                  </span>
                  <span class="fs-7 fw-semibold">{{ option }}</span>
                </div>
                <i v-if="hasAnsweredCurrent && optIdx === currentQuestion.correct_index" class="bi bi-check-circle-fill text-success fs-5"></i>
                <i v-else-if="hasAnsweredCurrent && selectedOptionIndex === optIdx" class="bi bi-x-circle-fill text-danger fs-5"></i>
              </button>
            </div>
          </div>

          <!-- Feedback & Next Action -->
          <div v-if="hasAnsweredCurrent" class="mt-4 pt-3 border-top">
            <div 
              class="p-3 rounded-3 mb-3 d-flex align-items-center justify-content-between"
              :class="isCurrentCorrect ? 'bg-success-subtle text-success-emphasis border border-success-subtle' : 'bg-danger-subtle text-danger-emphasis border border-danger-subtle'"
            >
              <div class="d-flex align-items-center gap-2">
                <i :class="isCurrentCorrect ? 'bi bi-check-circle-fill fs-4' : 'bi bi-x-circle-fill fs-4'"></i>
                <div>
                  <strong>{{ isCurrentCorrect ? 'Chính xác!' : 'Chưa đúng!' }}</strong>
                  <div class="fs-7">Đáp án đúng: <strong>{{ currentQuestion.correct_meaning }}</strong></div>
                </div>
              </div>

              <button class="btn btn-teal px-4 py-2 fs-7" @click="handleNextQuestion">
                <span>{{ currentQuestionIndex === quizQuestions.length - 1 ? 'Xem kết quả' : 'Câu tiếp theo' }}</span>
                <i class="bi bi-arrow-right ms-1"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CELEBRATION MODAL (Dismissable by clicking outside backdrop) -->
    <div 
      v-if="showResultModal" 
      class="modal-backdrop-custom" 
      @click.self="showResultModal = false"
    >
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 500px; width: 100%;">
        <div class="card-body p-4 p-md-5 text-center">
          <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 text-white shadow-sm" style="width: 72px; height: 72px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%);">
            <i class="bi bi-trophy-fill fs-2"></i>
          </div>

          <h3 class="fw-black mb-1">Hoàn Thành Chặng {{ currentStageNum }}!</h3>
          <p class="text-muted fs-7 mb-4">Kết quả đã được tự động lưu vào tiến độ học tập của bạn.</p>

          <div class="bg-light p-3 rounded-3 border mb-4">
            <div class="row g-2 text-center">
              <div class="col-6 border-end">
                <div class="text-muted fs-8 text-uppercase fw-bold">Điểm số</div>
                <div class="display-6 fw-black text-teal">{{ quizScore }} / {{ quizQuestions.length }}</div>
              </div>
              <div class="col-6">
                <div class="text-muted fs-8 text-uppercase fw-bold">Chính xác</div>
                <div class="display-6 fw-black text-success">{{ Math.round((quizScore / quizQuestions.length) * 100) }}%</div>
              </div>
            </div>
          </div>

          <div class="d-flex flex-column gap-2">
            <button 
              v-if="currentStageNum < 10" 
              class="btn btn-teal w-100 py-2.5 fs-7 fw-bold"
              @click="goToNextStage"
            >
              Tiến vào Giai đoạn {{ currentStageNum + 1 }} →
            </button>
            <button class="btn btn-outline-secondary w-100 py-2 fs-7" @click="retryQuiz">
              Làm lại bài kiểm tra
            </button>
            <router-link to="/curriculum" class="btn btn-link text-decoration-none text-muted fs-7">
              Quay về Lộ trình
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useSpeech } from '@/composables/useSpeech'

const route = useRoute()
const router = useRouter()
const speech = useSpeech()

const stageScrollTrack = ref(null)
const stageButtons = ref({})

const topic = ref(null)
const terms = ref([])
const quizQuestions = ref([])
const stagesProgress = ref({})
const loading = ref(true)

// Modes: 'flashcard' or 'quiz'
const activeMode = ref('flashcard')
const isFlipped = ref(false)
const currentCardIndex = ref(0)

// Speech State
const isListening = ref(false)
const recognizedText = ref('')
const speechScore = ref(null)

// Retractable Mobile Drawer States ("Thụt ra - Thụt vào")
const isTopicsDrawerOpen = ref(false)
const isStagesDrawerOpen = ref(false)
const allTopics = ref([])
const searchTopicQuery = ref('')

const filteredTopics = computed(() => {
  if (!searchTopicQuery.value) return allTopics.value
  const q = searchTopicQuery.value.toLowerCase().trim()
  return allTopics.value.filter(t => 
    (t.title && t.title.toLowerCase().includes(q)) ||
    (t.title_vi && t.title_vi.toLowerCase().includes(q))
  )
})

const selectTopic = (targetTopic) => {
  isTopicsDrawerOpen.value = false
  router.push(`/curriculum/${targetTopic.id}/stage/1`)
}

const selectStage = (stageNum) => {
  isStagesDrawerOpen.value = false
  switchStage(stageNum)
}

// Quiz State
const currentQuestionIndex = ref(0)
const selectedOptionIndex = ref(null)
const hasAnsweredCurrent = ref(false)
const quizScore = ref(0)
const masteredTermIds = ref([])
const showResultModal = ref(false)

const currentStageNum = computed(() => parseInt(route.params.stageNum) || 1)
const topicId = computed(() => route.params.topicId)
const isAllTermsMode = computed(() => route.query.mode === 'all')

const currentTerm = computed(() => terms.value[currentCardIndex.value] || null)
const currentQuestion = computed(() => quizQuestions.value[currentQuestionIndex.value] || null)

const totalCount = computed(() => {
  return activeMode.value === 'flashcard' ? terms.value.length : quizQuestions.value.length
})

const currentDisplayIndex = computed(() => {
  return activeMode.value === 'flashcard' ? currentCardIndex.value : currentQuestionIndex.value
})

const topProgressPercent = computed(() => {
  if (totalCount.value === 0) return 0
  return Math.round(((currentDisplayIndex.value + 1) / totalCount.value) * 100)
})

const isCurrentCorrect = computed(() => {
  if (!currentQuestion.value || selectedOptionIndex.value === null) return false
  return selectedOptionIndex.value === currentQuestion.value.correct_index
})

const formatIndex = (num) => String(num).padStart(3, '0')

const stageCounts = [10, 12, 14, 16, 20, 24, 24, 26, 26, 28]
const getStageCount = (s) => stageCounts[s - 1] || 10

// Phonetic fallback approximation
const currentTermPhonetic = computed(() => {
  if (!currentTerm.value) return ''
  return `/${currentTerm.value.term.toLowerCase()}/`
})

const parsedExamples = (raw) => {
  if (!raw) return []
  return raw.split('|').map(s => s.trim()).filter(Boolean)
}

// Fetch Stage Data or All Terms
const fetchData = async () => {
  loading.value = true
  isFlipped.value = false
  currentCardIndex.value = 0
  currentQuestionIndex.value = 0
  selectedOptionIndex.value = null
  hasAnsweredCurrent.value = false
  quizScore.value = 0
  masteredTermIds.value = []
  showResultModal.value = false
  speechScore.value = null

  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}

    if (isAllTermsMode.value) {
      // Load all 200 terms of this topic
      const res = await axios.get(`/api/curriculum/${topicId.value}/all-terms`, { headers })
      if (res.data?.status === 'success') {
        topic.value = res.data.data.topic
        terms.value = res.data.data.terms || []
      }
    } else {
      // Load specific stage
      const res = await axios.get(`/api/curriculum/${topicId.value}/stage/${currentStageNum.value}`, { headers })
      if (res.data?.status === 'success') {
        topic.value = res.data.data.topic
        terms.value = res.data.data.terms || []
        quizQuestions.value = res.data.data.quiz_questions || []
      }

      // Load overview of 10 stages status
      const topicRes = await axios.get(`/api/curriculum/${topicId.value}`, { headers })
      if (topicRes.data?.data?.stages) {
        const pMap = {}
        topicRes.data.data.stages.forEach(st => {
          pMap[st.stage_num] = st
        })
        stagesProgress.value = pMap
      }
    }

    // Load full 25 topics for the mobile retractable drawer
    if (allTopics.value.length === 0) {
      try {
        const allRes = await axios.get('/api/curriculum', { headers })
        if (allRes.data?.data?.topics) {
          allTopics.value = allRes.data.data.topics
        }
      } catch (e) {
        console.warn('Không tải được danh sách 25 chủ đề:', e)
      }
    }
  } catch (err) {
    console.error('Lỗi nạp bài học:', err)
  } finally {
    loading.value = false
  }
}

// Smoothly scroll active stage button to center view
const scrollToStage = (stageNum) => {
  nextTick(() => {
    const btn = stageButtons.value[stageNum]
    const track = stageScrollTrack.value
    if (btn && track) {
      const btnLeft = btn.offsetLeft
      const btnWidth = btn.offsetWidth
      const trackWidth = track.clientWidth
      const targetScroll = btnLeft - (trackWidth / 2) + (btnWidth / 2)
      track.scrollTo({
        left: Math.max(0, targetScroll),
        behavior: 'smooth'
      })
    }
  })
}

// Switch Stage
const switchStage = (s) => {
  if (s < 1 || s > 10) return
  router.push(`/curriculum/${topicId.value}/stage/${s}`)
  scrollToStage(s)
}

// Speech Synthesis [A]
const speakWord = (text) => {
  if (!text) return
  speech.speak(text)
}

// Speech Recognition [D] (Luyện nói)
const startSpeechRecognition = () => {
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition
  if (!SpeechRecognition) {
    alert('Trình duyệt của bạn chưa hỗ trợ Web Speech Recognition API.')
    return
  }

  const recognition = new SpeechRecognition()
  recognition.lang = 'en-US'
  recognition.interimResults = false
  recognition.maxAlternatives = 1

  isListening.value = true
  speechScore.value = null
  recognizedText.value = 'Đang lắng nghe giọng bạn...'

  recognition.onresult = (event) => {
    isListening.value = false
    const transcript = event.results[0][0].transcript.toLowerCase().trim()
    recognizedText.value = transcript

    const target = (currentTerm.value?.term || '').toLowerCase().trim()
    if (transcript === target) {
      speechScore.value = 100
    } else {
      // Calculate simple word similarity
      const targetWords = target.split(/\s+/)
      const spokenWords = transcript.split(/\s+/)
      let matched = 0
      targetWords.forEach(w => {
        if (spokenWords.includes(w)) matched++
      })
      speechScore.value = Math.round((matched / targetWords.length) * 90) + 10
    }
  }

  recognition.onerror = () => {
    isListening.value = false
    recognizedText.value = 'Không nhận diện được giọng nói, vui lòng thử lại.'
  }

  recognition.onend = () => {
    isListening.value = false
  }

  recognition.start()
}

// Card Navigation
const prevCard = () => {
  if (currentCardIndex.value > 0) {
    isFlipped.value = false
    currentCardIndex.value--
    speechScore.value = null
  }
}

const nextCard = () => {
  if (currentCardIndex.value < terms.value.length - 1) {
    isFlipped.value = false
    currentCardIndex.value++
    speechScore.value = null
  }
}

// Mark Remembered [S]
const markTermRemembered = async () => {
  if (!currentTerm.value) return
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}
    const res = await axios.post('/api/curriculum/term-status', {
      term_id: currentTerm.value.id,
      status: 'remembered'
    }, { headers })

    currentTerm.value.is_remembered = true
    currentTerm.value.is_learning = false

    // Update real-time stage progress indicator
    if (res.data?.data && stagesProgress.value[currentStageNum.value]) {
      stagesProgress.value[currentStageNum.value].score = res.data.data.stage_score
      stagesProgress.value[currentStageNum.value].is_completed = res.data.data.stage_completed
      stagesProgress.value[currentStageNum.value].remembered_count = res.data.data.stage_remembered_count
    }

    // Auto advance to next card after marking
    if (currentCardIndex.value < terms.value.length - 1) {
      setTimeout(() => nextCard(), 250)
    }
  } catch (err) {
    console.error('Lỗi lưu từ đã nhớ:', err)
  }
}

// Mark Learning [X]
const markTermLearning = async () => {
  if (!currentTerm.value) return
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}
    const res = await axios.post('/api/curriculum/term-status', {
      term_id: currentTerm.value.id,
      status: 'learning'
    }, { headers })

    currentTerm.value.is_remembered = false
    currentTerm.value.is_learning = true

    if (res.data?.data && stagesProgress.value[currentStageNum.value]) {
      stagesProgress.value[currentStageNum.value].score = res.data.data.stage_score
      stagesProgress.value[currentStageNum.value].is_completed = res.data.data.stage_completed
      stagesProgress.value[currentStageNum.value].remembered_count = res.data.data.stage_remembered_count
    }
  } catch (err) {
    console.error('Lỗi lưu từ cần ôn:', err)
  }
}

// Quiz Handlers
const handleSelectOption = async (index) => {
  if (hasAnsweredCurrent.value) return
  selectedOptionIndex.value = index
  hasAnsweredCurrent.value = true

  if (index === currentQuestion.value.correct_index) {
    quizScore.value++
    masteredTermIds.value.push(currentQuestion.value.question_id)

    // Save accurate word progress right away!
    try {
      const token = localStorage.getItem('oxford_token')
      const headers = token ? { Authorization: `Bearer ${token}` } : {}
      const res = await axios.post('/api/curriculum/term-status', {
        term_id: currentQuestion.value.question_id,
        status: 'remembered'
      }, { headers })

      if (res.data?.data && stagesProgress.value[currentStageNum.value]) {
        stagesProgress.value[currentStageNum.value].score = Math.max(stagesProgress.value[currentStageNum.value].score || 0, res.data.data.stage_score)
        stagesProgress.value[currentStageNum.value].is_completed = res.data.data.stage_completed
        stagesProgress.value[currentStageNum.value].remembered_count = res.data.data.stage_remembered_count
      }
    } catch (e) {}
  }
}

const handleNextQuestion = () => {
  if (currentQuestionIndex.value < quizQuestions.value.length - 1) {
    currentQuestionIndex.value++
    selectedOptionIndex.value = null
    hasAnsweredCurrent.value = false
  } else {
    submitQuizResult()
  }
}

const submitQuizResult = async () => {
  try {
    const token = localStorage.getItem('oxford_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}

    await axios.post('/api/curriculum/stage-submit', {
      topic_id: topicId.value,
      stage_num: currentStageNum.value,
      score: quizScore.value,
      total_questions: quizQuestions.value.length,
      mastered_term_ids: masteredTermIds.value,
    }, { headers })

    if (stagesProgress.value[currentStageNum.value]) {
      stagesProgress.value[currentStageNum.value].is_completed = true
      stagesProgress.value[currentStageNum.value].score = quizScore.value
    }

    showResultModal.value = true
  } catch (err) {
    console.error('Lỗi nộp bài kiểm tra:', err)
    showResultModal.value = true
  }
}

const retryQuiz = () => {
  showResultModal.value = false
  currentQuestionIndex.value = 0
  selectedOptionIndex.value = null
  hasAnsweredCurrent.value = false
  quizScore.value = 0
  masteredTermIds.value = []
}

const goToNextStage = () => {
  showResultModal.value = false
  if (currentStageNum.value < 10) {
    switchStage(currentStageNum.value + 1)
  } else {
    router.push('/curriculum')
  }
}

const getOptionButtonClass = (idx) => {
  if (!hasAnsweredCurrent.value) return 'border-light-subtle bg-white hover:bg-light'
  if (idx === currentQuestion.value.correct_index) return 'border-success bg-success-subtle text-success-emphasis fw-bold shadow-xs'
  if (selectedOptionIndex.value === idx) return 'border-danger bg-danger-subtle text-danger-emphasis fw-bold'
  return 'border-light-subtle bg-light text-muted opacity-75'
}

const getOptionLetterClass = (idx) => {
  if (!hasAnsweredCurrent.value) return 'bg-light text-dark-emphasis border'
  if (idx === currentQuestion.value.correct_index) return 'bg-success text-white'
  if (selectedOptionIndex.value === idx) return 'bg-danger text-white'
  return 'bg-secondary-subtle text-muted'
}

// Global Keyboard Listener for [Q], [Space], [A], [D], [S], [X], [E]
const handleGlobalKeydown = (e) => {
  // Ignore typing inside inputs or textareas
  if (['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return

  const key = e.key.toLowerCase()

  if (activeMode.value === 'flashcard') {
    if (key === 'q') {
      e.preventDefault()
      prevCard()
    } else if (key === ' ' || e.code === 'Space') {
      e.preventDefault()
      isFlipped.value = !isFlipped.value
    } else if (key === 'a') {
      e.preventDefault()
      speakWord(currentTerm.value?.term)
    } else if (key === 'd') {
      e.preventDefault()
      startSpeechRecognition()
    } else if (key === 's') {
      e.preventDefault()
      markTermRemembered()
    } else if (key === 'x') {
      e.preventDefault()
      markTermLearning()
    } else if (key === 'e') {
      e.preventDefault()
      nextCard()
    }
  }
}

watch(() => [route.params.topicId, route.params.stageNum, route.query.mode], () => {
  fetchData().then(() => {
    scrollToStage(currentStageNum.value)
  })
})

onMounted(() => {
  fetchData().then(() => {
    scrollToStage(currentStageNum.value)
  })
  window.addEventListener('keydown', handleGlobalKeydown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleGlobalKeydown)
})
</script>

<style scoped>
.shadow-xs {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
}
.letter-spacing-1 {
  letter-spacing: 1px;
}
.option-btn {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.option-btn:hover:not(:disabled) {
  border-color: #087F75 !important;
  background-color: #f0fdfa !important;
  transform: translateY(-1.5px);
}
.stage-scroll-container::-webkit-scrollbar {
  height: 4px;
}
.stage-scroll-container::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 999px;
}
.active-stage-pill {
  box-shadow: 0 3px 10px rgba(0, 176, 116, 0.35) !important;
}
</style>
