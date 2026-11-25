<template>
  <div class="dictation-view py-3 py-md-4 container-xl">
    <!-- ================= 1. VIEW MODE: BROWSE TOPICS & LESSONS ================= -->
    <div v-if="!currentLesson" class="dictation-browser animate-fade-in">
      <!-- Hero Header -->
      <div class="card p-4 p-md-5 rounded-4 border-0 shadow-sm mb-4 bg-gradient-teal text-white position-relative overflow-hidden">
        <div class="row align-items-center position-relative z-1">
          <div class="col-lg-8">
            <span class="badge bg-white/20 text-white backdrop-blur px-3 py-1.5 rounded-pill fs-8 fw-bold mb-3 d-inline-flex align-items-center gap-1.5">
              <i class="bi bi-headphones"></i>
              <span>TÍNH NĂNG ĐỘT PHÁ THEO DAILYDICTATION.COM</span>
            </span>
            <h1 class="display-6 fw-black mb-2 text-white">Luyện Nghe Chép Chính Tả (Dictation)</h1>
            <p class="fs-6 text-white/90 mb-3 max-w-2xl leading-relaxed">
              Phương pháp rèn luyện thính giác số 1 thế giới: Nghe từng câu, gõ lại những gì bạn nghe, hệ thống tự động phân tích và bôi màu sửa lỗi từng chữ theo thời gian thực.
            </p>
            <div class="d-flex flex-wrap gap-2 text-white/80 fs-8">
              <span class="d-flex align-items-center gap-1"><i class="bi bi-check2-circle text-warning"></i> 6 Thể loại đa dạng</span>
              <span class="mx-1">•</span>
              <span class="d-flex align-items-center gap-1"><i class="bi bi-check2-circle text-warning"></i> Kiểm tra lỗi từng từ thông minh</span>
              <span class="mx-1">•</span>
              <span class="d-flex align-items-center gap-1"><i class="bi bi-check2-circle text-warning"></i> Phím tắt Tab nghe lại tức thì</span>
            </div>
          </div>
          <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
            <div class="p-3 bg-white/10 rounded-4 border border-white/20 backdrop-blur d-inline-block text-start text-white min-w-200">
              <div class="fs-8 text-white/80 mb-1">Tiến độ chép chính tả:</div>
              <div class="fs-3 fw-black text-warning">{{ completedChallengesCount }} / {{ totalAvailableChallenges }} câu</div>
              <div class="progress bg-white/20 rounded-pill mt-2" style="height: 6px;">
                <div
                  class="progress-bar bg-warning rounded-pill"
                  :style="{ width: `${totalAvailableChallenges > 0 ? (completedChallengesCount / totalAvailableChallenges) * 100 : 0}%` }"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Categories Pills & Search Filter -->
      <div class="card p-3 p-md-3.5 rounded-4 shadow-sm border bg-card-custom mb-4">
        <div class="row g-3 align-items-center justify-content-between">
          <!-- Category Tabs -->
          <div class="col-12 col-lg-8">
            <div class="d-flex gap-1.5 flex-nowrap overflow-x-auto pb-1 no-scrollbar">
              <button
                @click="selectedCategory = 'all'"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-nowrap transition-all border"
                :class="selectedCategory === 'all' ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
              >
                Tất cả chủ đề
              </button>
              <button
                v-for="cat in categories"
                :key="cat.id"
                @click="selectedCategory = cat.id"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-nowrap transition-all border d-flex align-items-center gap-1.5"
                :class="selectedCategory === cat.id ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
              >
                <i :class="cat.icon"></i>
                <span>{{ cat.title.split('(')[0].trim() }}</span>
                <span class="badge rounded-pill ms-1" :class="selectedCategory === cat.id ? 'bg-white text-dark' : 'bg-body-tertiary text-muted'">
                  {{ cat.badge }}
                </span>
              </button>
            </div>
          </div>

          <!-- Search Input -->
          <div class="col-12 col-lg-4">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-body-tertiary border-end-0 rounded-start-pill ps-3">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input
                v-model="searchQuery"
                type="text"
                class="form-control border-start-0 rounded-end-pill pe-3 fs-8"
                placeholder="Tìm bài học (VD: Snowfall, Eating out...)"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Lessons Grid -->
      <div v-if="filteredLessons.length === 0" class="card p-5 text-center rounded-4 border bg-card-custom text-muted mb-4">
        <i class="bi bi-inbox fs-1 d-block mb-2 text-teal"></i>
        <h5 class="fw-bold text-dark-emphasis">Không tìm thấy bài học nào phù hợp</h5>
        <p class="fs-8 text-muted mb-0">Hãy thử đổi từ khóa tìm kiếm hoặc chọn lại danh mục khác.</p>
      </div>

      <div v-else class="row g-3 g-md-4 mb-4">
        <div
          v-for="lesson in filteredLessons"
          :key="lesson.id"
          class="col-12 col-md-6 col-lg-4"
        >
          <div
            class="card p-3.5 p-md-4 rounded-4 border shadow-sm h-100 d-flex flex-column justify-content-between hover-card transition-all bg-card-custom"
            style="border-top: 4px solid var(--primary-teal) !important;"
          >
            <div>
              <!-- Card Top: Category & Level Badge -->
              <div class="d-flex justify-content-between align-items-center mb-2.5">
                <span class="badge bg-teal-subtle text-teal fw-bold fs-8 rounded-pill px-2.5 py-1">
                  {{ lesson.categoryName }}
                </span>
                <span class="badge bg-warning text-dark fw-bold fs-8 rounded-pill px-2.5 py-1 shadow-2xs">
                  Level {{ lesson.level }}
                </span>
              </div>

              <!-- Lesson Title -->
              <h5 class="fw-black text-dark-emphasis mb-2 leading-snug">
                {{ lesson.title }}
              </h5>

              <!-- Sentence Count & Meta -->
              <div class="d-flex align-items-center gap-3 text-muted fs-8 mb-3">
                <span class="d-flex align-items-center gap-1">
                  <i class="bi bi-chat-left-text text-teal"></i>
                  <span>{{ lesson.totalChallenges }} câu chính tả</span>
                </span>
                <span class="d-flex align-items-center gap-1">
                  <i class="bi bi-mic text-teal"></i>
                  <span>Audio giọng bản xứ</span>
                </span>
              </div>
            </div>

            <!-- Start Action Button -->
            <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
              <span class="fs-8 text-muted">
                {{ completedChallengesByLesson[lesson.id] || 0 }}/{{ lesson.totalChallenges }} đã xong
              </span>
              <button
                @click="openLesson(lesson)"
                class="btn btn-sm btn-teal text-white rounded-pill px-3.5 py-1.5 fw-bold fs-8 d-inline-flex align-items-center gap-1.5 shadow-2xs"
              >
                <i class="bi bi-play-circle-fill"></i>
                <span>Vào luyện nghe</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= 2. VIEW MODE: ACTIVE DICTATION PRACTICE ARENA ================= -->
    <div v-else class="dictation-arena animate-fade-in">
      <!-- Breadcrumb & Top Controls -->
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <button
            @click="backToBrowser"
            class="btn btn-sm btn-outline-secondary rounded-circle p-1 d-flex align-items-center justify-content-center shrink-0 shadow-2xs"
            style="width: 34px; height: 34px;"
            title="Quay lại danh sách bài học"
          >
            <i class="bi bi-arrow-left fs-7"></i>
          </button>
          <div class="min-w-0">
            <div class="d-flex align-items-center gap-2">
              <h4 class="fw-black text-dark-emphasis mb-0 fs-5 text-truncate">{{ currentLesson.title }}</h4>
              <span class="badge bg-teal-subtle text-teal fw-bold fs-8 rounded-pill px-2 py-0.5">
                Level {{ currentLesson.level }}
              </span>
            </div>
            <div class="text-muted fs-8">
              {{ currentLesson.categoryName }} • <strong>{{ currentChallengeIndex + 1 }}</strong> / {{ currentLesson.totalChallenges }} câu
            </div>
          </div>
        </div>

        <!-- Top Right: Sentence Indicator & Settings -->
        <div class="d-flex align-items-center gap-2">
          <button
            @click="isSettingsModalOpen = true"
            class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1.5 fs-8 fw-semibold d-inline-flex align-items-center gap-1 shadow-2xs"
            title="Cài đặt phát âm & phím tắt"
          >
            <i class="bi bi-gear-fill text-teal"></i>
            <span class="d-none d-sm-inline">Cài đặt</span>
          </button>
          <button
            @click="isTipOpen = !isTipOpen"
            class="btn btn-sm btn-outline-warning text-dark rounded-circle p-1 d-flex align-items-center justify-content-center shadow-2xs"
            style="width: 34px; height: 34px;"
            title="Xem lời khuyên học tập"
          >
            <i class="bi bi-lightbulb-fill text-warning"></i>
          </button>
        </div>
      </div>

      <!-- Rotating Tip Box -->
      <div v-if="isTipOpen" class="dictation-tip-box p-2.5 px-3 mb-3 d-flex align-items-center justify-content-between gap-2 animate-fade-in fs-8">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-lightbulb-fill text-warning fs-6 shrink-0"></i>
          <span class="text-dark-emphasis fw-medium">{{ currentTip }}</span>
        </div>
        <button @click="rotateTip" class="btn btn-sm btn-link p-0 text-muted" title="Đổi lời khuyên khác">
          <i class="bi bi-arrow-clockwise fs-6"></i>
        </button>
      </div>

      <!-- Main Dictation Card & Tabs -->
      <div class="card rounded-4 shadow-sm border bg-card-custom mb-4 overflow-hidden">
        <!-- Tabs Header (Dictation vs Full Transcript) -->
        <div class="border-bottom px-3 pt-2 d-flex justify-content-between align-items-center bg-body-tertiary">
          <ul class="nav nav-tabs border-bottom-0">
            <li class="nav-item">
              <button
                @click="activeSubTab = 'dictation'"
                class="nav-link py-2 px-3.5 fs-7 fw-bold border-bottom-0"
                :class="activeSubTab === 'dictation' ? 'active bg-card-custom text-teal border-teal-top' : 'text-muted'"
              >
                <i class="bi bi-pencil-square me-1"></i>
                <span>Chép chính tả (Dictation)</span>
              </button>
            </li>
            <li class="nav-item">
              <button
                @click="activeSubTab = 'transcript'"
                class="nav-link py-2 px-3.5 fs-7 fw-bold border-bottom-0"
                :class="activeSubTab === 'transcript' ? 'active bg-card-custom text-teal border-teal-top' : 'text-muted'"
              >
                <i class="bi bi-file-text me-1"></i>
                <span>Toàn bộ bài nghe (Full Transcript)</span>
              </button>
            </li>
          </ul>

          <div class="d-none d-md-flex align-items-center gap-2 text-muted fs-8 pb-1">
            <i class="bi bi-keyboard"></i>
            <span>Nhấn <kbd class="bg-body-secondary text-dark border">Tab</kbd> để nghe lại • <kbd class="bg-body-secondary text-dark border">Enter</kbd> kiểm tra</span>
          </div>
        </div>

        <!-- ================= TAB 1: DICTATION PRACTICE ================= -->
        <div v-show="activeSubTab === 'dictation'" class="p-3 p-md-4">
          <!-- Audio Player Bar (Sticky on Mobile, Beautiful & Functional) -->
          <div class="dictation-player-bar p-3 mb-4 shadow-2xs">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <!-- Play / Pause & Rewind Controls -->
              <div class="d-flex align-items-center gap-2">
                <button
                  type="button"
                  @click="togglePlayAudio"
                  class="btn btn-teal text-white rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm transition-all"
                  style="width: 46px; height: 46px;"
                  :title="isPlaying ? 'Tạm dừng âm thanh (Space)' : 'Phát âm thanh câu này (Space/Tab)'"
                >
                  <i class="bi fs-4" :class="isPlaying ? 'bi-pause-fill' : 'bi-play-fill'"></i>
                </button>

                <button
                  type="button"
                  @click="replayAudio"
                  class="btn btn-outline-teal rounded-circle p-1.5 d-flex align-items-center justify-content-center shadow-2xs"
                  style="width: 38px; height: 38px;"
                  title="Nghe lại từ đầu câu này (Phím tắt: Tab)"
                >
                  <i class="bi bi-arrow-counterclockwise fs-6"></i>
                </button>

                <button
                  type="button"
                  @click="rewind2s"
                  class="btn btn-outline-secondary rounded-pill px-2.5 py-1 fs-8 fw-semibold shadow-2xs d-none d-sm-inline-flex align-items-center gap-1"
                  title="Tua lại 2 giây"
                >
                  <i class="bi bi-rewind-fill"></i>
                  <span>-2s</span>
                </button>
              </div>

              <!-- Time display & Progress Slider -->
              <div class="flex-grow-1 d-flex align-items-center gap-2 min-w-150">
                <span class="fs-8 text-muted font-monospace shrink-0">{{ formatTime(currentTime) }}</span>
                <input
                  type="range"
                  class="form-range flex-grow-1"
                  min="0"
                  :max="audioDuration || 1"
                  step="0.05"
                  :value="currentTime"
                  @input="handleSeek"
                />
                <span class="fs-8 text-muted font-monospace shrink-0">{{ formatTime(audioDuration) }}</span>
              </div>

              <!-- Speed, Repeat & Voice Options -->
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Playback Speed -->
                <div class="dropdown">
                  <button
                    class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill px-2.5 py-1 fs-8 fw-bold"
                    type="button"
                    data-bs-toggle="dropdown"
                  >
                    {{ playbackRate }}x
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-8 py-1">
                    <li><button class="dropdown-item" :class="{ 'active fw-bold': playbackRate === 0.75 }" @click="setSpeed(0.75)">0.75x (Chậm)</button></li>
                    <li><button class="dropdown-item" :class="{ 'active fw-bold': playbackRate === 1.0 }" @click="setSpeed(1.0)">1.0x (Bình thường)</button></li>
                    <li><button class="dropdown-item" :class="{ 'active fw-bold': playbackRate === 1.25 }" @click="setSpeed(1.25)">1.25x (Nhanh)</button></li>
                  </ul>
                </div>

                <!-- Auto-repeat Loop Counter -->
                <button
                  type="button"
                  @click="cycleAutoReplay"
                  class="btn btn-sm rounded-pill px-2.5 py-1 fs-8 fw-semibold shadow-2xs transition-all border"
                  :class="autoReplayCount > 0 ? 'btn-teal text-white border-teal' : 'btn-outline-secondary'"
                  :title="`Tự động lặp lại ${autoReplayCount} lần khi câu kết thúc`"
                >
                  <i class="bi bi-repeat me-1"></i>
                  <span>{{ autoReplayCount > 0 ? `Lặp ${autoReplayCount}x` : 'Không lặp' }}</span>
                </button>

                <!-- Voice Accent Switcher -->
                <button
                  type="button"
                  @click="toggleAccent"
                  class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 fs-8 fw-bold"
                  title="Đổi giọng phát âm"
                >
                  {{ currentAccent }}
                </button>
              </div>
            </div>

            <!-- Native Audio Element (Hidden or Controlled) -->
            <audio
              ref="audioPlayerRef"
              :src="currentChallenge?.audioSrc || currentLesson?.audioSrc"
              preload="auto"
              @play="isPlaying = true"
              @pause="isPlaying = false"
              @timeupdate="onAudioTimeUpdate"
              @loadedmetadata="onAudioLoaded"
              @ended="onAudioEnded"
              class="d-none"
            ></audio>
          </div>

          <!-- Sentence Progress Indicator -->
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fs-8 fw-bold text-teal text-uppercase d-flex align-items-center gap-1.5">
              <i class="bi bi-pencil-fill fs-7"></i>
              <span>Câu số {{ currentChallengeIndex + 1 }} / {{ currentLesson.challenges.length }}:</span>
            </span>
            <span class="badge px-2.5 py-1 rounded-pill fs-8 fw-bold" :class="isCurrentCompleted ? 'bg-success text-white' : 'bg-body-secondary text-muted'">
              {{ isCurrentCompleted ? '✓ Đã đúng 100%' : 'Chưa hoàn thành' }}
            </span>
          </div>

          <!-- Dual-layer Typing & Underline Highlight Area (Exact DailyDictation replicate) -->
          <div class="dictation__input-container">
            <!-- Background Underline Highlight Layer -->
            <div
              class="dictation__input-highlight"
              v-html="validationResult.userHighlightedHtml || '&nbsp;'"
            ></div>

            <!-- Foreground User Textarea -->
            <textarea
              ref="inputRef"
              v-model="userInput"
              @keydown="handleKeydown"
              @input="onInputChanged"
              class="form-control dictation__input"
              :class="{
                'dictation__input--done': isCurrentCompleted,
                'dictation__input--error': isIncorrect && !isCurrentCompleted
              }"
              placeholder="Nghe và gõ lại những gì bạn nghe được (Type what you hear)..."
              rows="3"
              autocomplete="off"
              autocorrect="off"
              autocapitalize="off"
              spellcheck="false"
            ></textarea>
          </div>

          <!-- Action Buttons Bar -->
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <!-- Check Button -->
              <button
                v-if="!isCurrentCompleted"
                @click="checkAnswer"
                class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7 shadow-sm d-inline-flex align-items-center gap-1.5"
              >
                <i class="bi bi-check-lg fs-6"></i>
                <span>Kiểm tra (Enter)</span>
              </button>

              <!-- Hint Button -->
              <button
                v-if="!isCurrentCompleted"
                @click="giveHint"
                class="btn btn-outline-warning text-dark rounded-pill px-3 py-2 fw-semibold fs-7 shadow-2xs d-inline-flex align-items-center gap-1.5"
                title="Gợi ý từ tiếp theo"
              >
                <i class="bi bi-magic text-warning"></i>
                <span>Gợi ý từ (Hint)</span>
              </button>

              <!-- Show Answer Button (when checked and incorrect) -->
              <button
                v-if="isIncorrect && !isCurrentCompleted && !isAnswerRevealed"
                @click="revealAnswer"
                class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold fs-7 shadow-2xs"
              >
                <i class="bi bi-eye me-1"></i>
                <span>Xem đáp án (Enter)</span>
              </button>

              <!-- Skip Button -->
              <button
                v-if="isIncorrect && !isCurrentCompleted"
                @click="skipChallenge"
                class="btn btn-outline-danger rounded-pill px-3 py-2 fw-semibold fs-7 shadow-2xs"
              >
                <span>Bỏ qua (Skip)</span>
              </button>

              <!-- Next Button (when correct) -->
              <button
                v-if="isCurrentCompleted"
                @click="nextChallenge"
                class="btn btn-success text-white rounded-pill px-4 py-2 fw-bold fs-7 shadow-sm d-inline-flex align-items-center gap-1.5 animate-bounce-subtle"
              >
                <span>Câu tiếp theo</span>
                <i class="bi bi-arrow-right fs-6"></i>
              </button>

              <!-- Replay Audio Button -->
              <button
                @click="replayAudio"
                class="btn btn-outline-teal rounded-pill px-3 py-2 fw-semibold fs-7 shadow-2xs d-inline-flex align-items-center gap-1"
                title="Phím tắt: Tab"
              >
                <i class="bi bi-volume-up-fill"></i>
                <span>Nghe lại (Tab)</span>
              </button>
            </div>

            <!-- Clear / Reset Current Input Button -->
            <button
              @click="resetInput"
              class="btn btn-sm btn-outline-secondary rounded-circle p-1.5 shadow-2xs"
              style="width: 36px; height: 36px;"
              title="Gõ lại câu này từ đầu (Esc)"
            >
              <i class="bi bi-arrow-clockwise fs-7"></i>
            </button>
          </div>

          <!-- Alert / Feedback Area -->
          <!-- 1. CORRECT STATE -->
          <div
            v-if="isCurrentCompleted"
            class="alert alert-success border-success rounded-4 p-3 mb-3 d-flex align-items-start gap-2.5 shadow-2xs animate-fade-in"
          >
            <i class="bi bi-check-circle-fill text-success fs-4 shrink-0 mt-0.5"></i>
            <div class="flex-grow-1">
              <div class="fw-bold fs-7 text-success mb-1">
                Tuyệt vời! Bạn đã nghe và chép chính xác 100% (+20 XP)
              </div>
              <div class="fs-6 fw-bold text-dark-emphasis mb-1">
                "{{ currentChallenge.content }}"
              </div>
              <div v-if="currentChallenge.vietnamese" class="fs-8 text-muted fst-italic">
                → {{ currentChallenge.vietnamese }}
              </div>
            </div>
          </div>

          <!-- 2. INCORRECT STATE & REVEALED ANSWER -->
          <div
            v-else-if="isIncorrect"
            class="alert alert-warning border-warning rounded-4 p-3 mb-3 shadow-2xs animate-fade-in"
          >
            <div class="d-flex align-items-center gap-2 mb-2">
              <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
              <span class="fw-bold text-dark">Chưa chính xác! Hãy kiểm tra lại từ bị gạch dưới màu cam:</span>
            </div>

            <!-- Revealed correction sentence with green highlighted words and unrevealed words -->
            <div v-if="isAnswerRevealed" class="p-3 bg-white rounded-3 border mb-2">
              <div class="fs-8 text-muted mb-1 fw-bold text-uppercase">Đáp án câu này:</div>
              <div class="fs-6 fw-medium mb-1" v-html="validationResult.htmlCorrectionFull"></div>
              <div v-if="currentChallenge.vietnamese" class="fs-8 text-muted fst-italic mb-2">
                → {{ currentChallenge.vietnamese }}
              </div>
              <div v-if="validationResult.alternatives" class="fs-8 text-teal">
                <i class="bi bi-info-circle me-1"></i>
                <span>Bạn cũng có thể gõ: <strong>{{ validationResult.alternatives }}</strong></span>
              </div>
            </div>

            <!-- Hint if answer not revealed yet -->
            <div v-else class="fs-8 text-muted">
              Bấm <strong>"Xem đáp án"</strong> hoặc nhấn phím <kbd class="bg-body-secondary text-dark border">Enter</kbd> để xem từ cần gõ tiếp theo.
            </div>
          </div>

          <!-- Sentence Navigation Carousel Dots / Chips -->
          <div class="mt-4 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fs-8 fw-bold text-muted text-uppercase">Danh sách tất cả câu trong bài ({{ currentLesson.challenges.length }})</span>
              <span class="fs-8 text-teal fw-semibold">Chạm vào số để chuyển câu</span>
            </div>
            <div class="d-flex gap-1.5 flex-wrap">
              <button
                v-for="(ch, idx) in currentLesson.challenges"
                :key="ch.id"
                @click="jumpToChallenge(idx)"
                class="btn btn-sm rounded-pill px-2.5 py-1 fs-8 fw-bold transition-all border"
                :class="[
                  currentChallengeIndex === idx
                    ? 'btn-teal text-white border-teal shadow-xs'
                    : isCompletedByIndex(idx)
                    ? 'btn-success text-white border-success'
                    : 'btn-outline-secondary'
                ]"
              >
                #{{ idx + 1 }}
                <i v-if="isCompletedByIndex(idx)" class="bi bi-check ms-0.5"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- ================= TAB 2: FULL TRANSCRIPT & AUDIO ================= -->
        <div v-show="activeSubTab === 'transcript'" class="p-3 p-md-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="fw-black text-teal mb-1">Toàn Bộ Bài Nghe & Bản Dịch</h5>
              <p class="text-muted fs-8 mb-0">Nghe lại toàn bộ câu chuyện và nhấp vào từng câu để nghe riêng biệt.</p>
            </div>
            <button
              @click="readFullStory"
              class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 fw-bold fs-8 d-inline-flex align-items-center gap-1.5"
            >
              <i class="bi bi-volume-up-fill"></i>
              <span>Nghe toàn bộ bài</span>
            </button>
          </div>

          <!-- Sentences List -->
          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="(item, idx) in currentLesson.challenges"
              :key="item.id"
              class="p-3 rounded-3 border bg-body-tertiary hover-card transition-all d-flex justify-content-between align-items-start gap-3"
              :class="{ 'border-teal bg-teal-subtle/10': currentChallengeIndex === idx }"
            >
              <div class="min-w-0">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5 fs-8 fw-bold">#{{ idx + 1 }}</span>
                  <span class="fs-7 fw-bold text-dark-emphasis">{{ item.content }}</span>
                </div>
                <div class="fs-8 text-muted fst-italic">
                  → {{ item.vietnamese }}
                </div>
              </div>

              <div class="d-flex align-items-center gap-1.5 shrink-0">
                <button
                  @click="playSpecificSentence(item, idx)"
                  class="btn btn-sm btn-outline-teal rounded-circle p-1 d-flex align-items-center justify-content-center shadow-2xs"
                  style="width: 32px; height: 32px;"
                  title="Nghe câu này"
                >
                  <i class="bi bi-volume-up-fill fs-8"></i>
                </button>
                <button
                  @click="jumpToChallenge(idx); activeSubTab = 'dictation'"
                  class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 fs-8 fw-semibold"
                  title="Luyện gõ câu này"
                >
                  Luyện gõ
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= SETTINGS MODAL ================= -->
    <BaseModal v-model="isSettingsModalOpen" title="Cài Đặt Luyện Nghe Chép Chính Tả" size="md">
      <div class="p-3">
        <!-- Replay Key Option -->
        <div class="mb-3">
          <label class="form-label fw-bold fs-7 text-dark-emphasis mb-1">Phím tắt nghe lại âm thanh:</label>
          <select v-model="settings.replayKey" class="form-select form-select-sm rounded-pill">
            <option value="Tab">Phím Tab (Khuyên dùng - giống DailyDictation)</option>
            <option value="`">Dấu huyền (`) góc trên bên trái</option>
            <option value="ctrl">Phím Ctrl</option>
            <option value="Space">Phím Space (Cách)</option>
          </select>
        </div>

        <!-- Auto-replay Times -->
        <div class="mb-3">
          <label class="form-label fw-bold fs-7 text-dark-emphasis mb-1">Số lần tự động lặp lại âm thanh:</label>
          <div class="d-flex gap-2">
            <button
              v-for="times in [0, 1, 2, 3]"
              :key="times"
              type="button"
              @click="settings.autoReplayTimes = times; autoReplayCount = times"
              class="btn btn-sm rounded-pill flex-fill fw-bold border"
              :class="settings.autoReplayTimes === times ? 'btn-teal text-white border-teal' : 'btn-outline-secondary'"
            >
              {{ times === 0 ? 'Tắt' : `${times} lần` }}
            </button>
          </div>
        </div>

        <!-- Auto-advance on 100% Correct -->
        <div class="form-check form-switch mb-3">
          <input
            v-model="settings.autoAdvance"
            class="form-check-input"
            type="checkbox"
            id="autoAdvanceSwitch"
          />
          <label class="form-check-label fs-7 fw-semibold" for="autoAdvanceSwitch">
            Tự động chuyển sang câu tiếp theo khi gõ đúng 100%
          </label>
        </div>

        <!-- Voice Accent -->
        <div class="mb-3">
          <label class="form-label fw-bold fs-7 text-dark-emphasis mb-1">Chất giọng dự phòng:</label>
          <select v-model="currentAccent" @change="saveSettings" class="form-select form-select-sm rounded-pill">
            <option value="US">Giọng Anh - Mỹ (American English)</option>
            <option value="UK">Giọng Anh - Anh (British English)</option>
          </select>
        </div>

        <div class="text-end mt-4">
          <button @click="isSettingsModalOpen = false" class="btn btn-sm btn-teal text-white rounded-pill px-4 py-1.5 fw-bold">
            Hoàn tất
          </button>
        </div>
      </div>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import confetti from 'canvas-confetti'
import { useToast } from '@/composables/useToast'
import { useSpeech } from '@/composables/useSpeech'
import { validateSentence } from '@/utils/dictationDiff'
import BaseModal from '@/components/BaseModal.vue'
import defaultDataset from '@/data/dictation_dataset.json'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const speech = useSpeech()

// Categories & Lessons data
const categories = ref(defaultDataset.categories || [])
const lessons = ref(defaultDataset.lessons || [])
const tipsList = ref(defaultDataset.tips || [])

// Filters & View state
const selectedCategory = ref('all')
const searchQuery = ref('')
const currentLesson = ref(null)
const activeSubTab = ref('dictation') // 'dictation' | 'transcript'

// Current Challenge State
const currentChallengeIndex = ref(0)
const userInput = ref('')
const isIncorrect = ref(false)
const isAnswerRevealed = ref(false)
const validationResult = ref({})

// Completed Progress Storage (stored in localStorage)
const completedChallengesByLesson = ref({})
const loadCompletedProgress = () => {
  try {
    const raw = localStorage.getItem('oxford_dictation_completed')
    if (raw) {
      completedChallengesByLesson.value = JSON.parse(raw)
    }
  } catch (e) {}
}

const saveCompletedProgress = () => {
  try {
    localStorage.setItem('oxford_dictation_completed', JSON.stringify(completedChallengesByLesson.value))
  } catch (e) {}
}

// Audio Player & Playback State
const audioPlayerRef = ref(null)
const inputRef = ref(null)
const isPlaying = ref(false)
const currentTime = ref(0)
const audioDuration = ref(0)
const playbackRate = ref(1.0)
const autoReplayCount = ref(0)
const replayRemaining = ref(0)
const currentAccent = ref(localStorage.getItem('oxford_accent_pref') || 'US')

// Rotating tips
const currentTipIndex = ref(0)
const currentTip = computed(() => tipsList.value[currentTipIndex.value] || 'Luyện tập đều đặn mỗi ngày để cải thiện phản xạ!')
const isTipOpen = ref(true)
const rotateTip = () => {
  currentTipIndex.value = (currentTipIndex.value + 1) % tipsList.value.length
}

// Settings
const isSettingsModalOpen = ref(false)
const settings = ref({
  replayKey: 'Tab',
  autoReplayTimes: 0,
  autoAdvance: true
})

const loadSettings = () => {
  try {
    const raw = localStorage.getItem('oxford_dictation_settings')
    if (raw) {
      settings.value = { ...settings.value, ...JSON.parse(raw) }
      autoReplayCount.value = settings.value.autoReplayTimes || 0
    }
  } catch (e) {}
}

const saveSettings = () => {
  try {
    localStorage.setItem('oxford_dictation_settings', JSON.stringify(settings.value))
  } catch (e) {}
}

watch(settings, saveSettings, { deep: true })

// Current Challenge
const currentChallenge = computed(() => {
  if (!currentLesson.value || !currentLesson.value.challenges) return null
  return currentLesson.value.challenges[currentChallengeIndex.value] || null
})

const isCurrentCompleted = computed(() => {
  if (!currentLesson.value || !currentChallenge.value) return false
  const key = `${currentLesson.value.id}_${currentChallenge.value.id}`
  return Boolean(completedChallengesByLesson.value[key])
})

const isCompletedByIndex = (idx) => {
  if (!currentLesson.value || !currentLesson.value.challenges[idx]) return false
  const ch = currentLesson.value.challenges[idx]
  const key = `${currentLesson.value.id}_${ch.id}`
  return Boolean(completedChallengesByLesson.value[key])
}

const completedChallengesCount = computed(() => {
  let count = 0
  for (const k in completedChallengesByLesson.value) {
    if (completedChallengesByLesson.value[k]) count++
  }
  return count
})

const totalAvailableChallenges = computed(() => {
  return lessons.value.reduce((acc, cur) => acc + (cur.totalChallenges || 0), 0)
})

// Filtered Lessons
const filteredLessons = computed(() => {
  return lessons.value.filter(l => {
    const matchCat = selectedCategory.value === 'all' || l.categoryId === selectedCategory.value
    const matchQ = !searchQuery.value || l.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchCat && matchQ
  })
})

// Lesson Navigation
const openLesson = (lesson) => {
  currentLesson.value = lesson
  currentChallengeIndex.value = 0
  activeSubTab.value = 'dictation'
  loadChallenge(0)
}

const backToBrowser = () => {
  stopAudio()
  currentLesson.value = null
}

const jumpToChallenge = (index) => {
  if (!currentLesson.value || index < 0 || index >= currentLesson.value.challenges.length) return
  currentChallengeIndex.value = index
  loadChallenge(index)
}

const loadChallenge = (index) => {
  userInput.value = ''
  isIncorrect.value = false
  isAnswerRevealed.value = false
  validationResult.value = {}
  replayRemaining.value = autoReplayCount.value

  nextTick(() => {
    if (inputRef.value) {
      inputRef.value.focus()
    }
    replayAudio()
  })
}

// Audio Engine (Dual mode: MP3 file + Web Speech API fallback)
const togglePlayAudio = () => {
  if (isPlaying.value) {
    stopAudio()
  } else {
    playAudio()
  }
}

const playAudio = () => {
  const el = audioPlayerRef.value
  const ch = currentChallenge.value
  if (!ch) return

  if (el && ch.audioSrc && !ch.audioSrc.includes('error')) {
    // If times are specified and different, handle clip
    if (ch.timeStart && ch.timeEnd && ch.timeEnd > ch.timeStart) {
      el.currentTime = ch.timeStart
    }
    el.playbackRate = playbackRate.value
    el.play().catch(() => {
      // Audio playback failed, use speech synthesis fallback
      speechFallback(ch.content)
    })
  } else {
    // Fallback to high quality TTS
    speechFallback(ch.content)
  }
}

const speechFallback = (text) => {
  isPlaying.value = true
  speech.speak(text)
  // Simulate duration
  const words = text.split(/\s+/).length
  const estimatedSeconds = Math.max(2, (words / 2.5) / playbackRate.value)
  audioDuration.value = estimatedSeconds
  currentTime.value = 0

  const interval = setInterval(() => {
    currentTime.value += 0.2
    if (currentTime.value >= estimatedSeconds) {
      clearInterval(interval)
      isPlaying.value = false
      onAudioEnded()
    }
  }, 200)
}

const stopAudio = () => {
  const el = audioPlayerRef.value
  if (el) {
    el.pause()
  }
  isPlaying.value = false
}

const replayAudio = () => {
  const el = audioPlayerRef.value
  const ch = currentChallenge.value
  if (!ch) return

  stopAudio()
  currentTime.value = 0
  setTimeout(() => {
    playAudio()
  }, 50)
}

const rewind2s = () => {
  const el = audioPlayerRef.value
  if (el) {
    el.currentTime = Math.max(0, el.currentTime - 2)
  }
}

const onAudioTimeUpdate = (e) => {
  const el = e.target
  currentTime.value = el.currentTime
  audioDuration.value = el.duration || 0

  // If clipping by timeEnd
  const ch = currentChallenge.value
  if (ch && ch.timeEnd && el.currentTime >= ch.timeEnd) {
    stopAudio()
    onAudioEnded()
  }
}

const onAudioLoaded = (e) => {
  const el = e.target
  audioDuration.value = el.duration || 0
}

const onAudioEnded = () => {
  isPlaying.value = false
  if (replayRemaining.value > 0) {
    replayRemaining.value--
    setTimeout(() => {
      replayAudio()
    }, 1200)
  }
}

const handleSeek = (e) => {
  const el = audioPlayerRef.value
  const val = parseFloat(e.target.value)
  currentTime.value = val
  if (el) {
    el.currentTime = val
  }
}

const setSpeed = (spd) => {
  playbackRate.value = spd
  if (audioPlayerRef.value) {
    audioPlayerRef.value.playbackRate = spd
  }
}

const cycleAutoReplay = () => {
  const options = [0, 1, 2, 3]
  const nextIdx = (options.indexOf(autoReplayCount.value) + 1) % options.length
  autoReplayCount.value = options[nextIdx]
  settings.value.autoReplayTimes = autoReplayCount.value
}

const toggleAccent = () => {
  currentAccent.value = currentAccent.value === 'US' ? 'UK' : 'US'
  localStorage.setItem('oxford_accent_pref', currentAccent.value)
}

const formatTime = (secs) => {
  if (!secs || isNaN(secs)) return '0:00'
  const m = Math.floor(secs / 60)
  const s = Math.floor(secs % 60)
  return `${m}:${s < 10 ? '0' : ''}${s}`
}

// Validation & Checking Logic
const onInputChanged = () => {
  if (isIncorrect.value) {
    // Clear error highlight on new keystroke to give smooth typing
    isIncorrect.value = false
    validationResult.value = {}
  }
}

const checkAnswer = () => {
  const ch = currentChallenge.value
  if (!ch) return

  const res = validateSentence(userInput.value, ch.content, ch.solution)
  validationResult.value = res

  if (res.isCorrect) {
    // Correct!
    isIncorrect.value = false
    markCurrentAsCompleted()
    playSuccessEffect()

    if (settings.value.autoAdvance) {
      setTimeout(() => {
        nextChallenge()
      }, 1500)
    }
  } else {
    // Incorrect!
    isIncorrect.value = true
  }
}

const markCurrentAsCompleted = () => {
  if (!currentLesson.value || !currentChallenge.value) return
  const key = `${currentLesson.value.id}_${currentChallenge.value.id}`
  completedChallengesByLesson.value[key] = true
  saveCompletedProgress()

  // Submit to backend & sync XP
  axios.post('/api/dictation/submit-challenge', {
    lesson_id: currentLesson.value.id,
    challenge_id: currentChallenge.value.id,
    accuracy: 100
  }).then(res => {
    if (authStore.user && res.data?.data?.total_xp !== undefined) {
      authStore.user.xp_points = res.data.data.total_xp
    }
  }).catch(() => {})
}

const playSuccessEffect = () => {
  try {
    confetti({
      particleCount: 50,
      spread: 60,
      origin: { y: 0.7 }
    })
  } catch (e) {}
}

const giveHint = () => {
  const ch = currentChallenge.value
  if (!ch) return

  const res = validateSentence(userInput.value, ch.content, ch.solution)
  if (res.nextWord) {
    const trimmed = userInput.value.trim()
    userInput.value = trimmed ? `${trimmed} ${res.nextWord} ` : `${res.nextWord} `
    isIncorrect.value = false
    validationResult.value = {}
    nextTick(() => {
      if (inputRef.value) {
        inputRef.value.focus()
      }
    })
  }
}

const revealAnswer = () => {
  isAnswerRevealed.value = true
  const ch = currentChallenge.value
  if (!ch) return
  const res = validateSentence(userInput.value, ch.content, ch.solution)
  validationResult.value = res
}

const skipChallenge = () => {
  nextChallenge()
}

const nextChallenge = () => {
  if (!currentLesson.value) return
  if (currentChallengeIndex.value < currentLesson.value.challenges.length - 1) {
    jumpToChallenge(currentChallengeIndex.value + 1)
  } else {
    toast.success('Chúc mừng bạn đã hoàn thành xuất sắc toàn bộ bài chép chính tả này!', 'Hoàn thành bài học')
    backToBrowser()
  }
}

const resetInput = () => {
  userInput.value = ''
  isIncorrect.value = false
  isAnswerRevealed.value = false
  validationResult.value = {}
  if (inputRef.value) {
    inputRef.value.focus()
  }
}

// Hotkey Listener (Tab, Backtick, Enter, Escape)
const handleKeydown = (e) => {
  // 1. Tab or Backtick key: Replay audio clip
  if (e.key === 'Tab' || e.key === '`') {
    e.preventDefault()
    replayAudio()
    return
  }

  // 2. Enter key: Check or Reveal answer
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault()
    if (!isCurrentCompleted.value) {
      if (isIncorrect.value && !isAnswerRevealed.value) {
        revealAnswer()
      } else {
        checkAnswer()
      }
    } else {
      nextChallenge()
    }
    return
  }

  // 3. Escape key: Clear input
  if (e.key === 'Escape') {
    e.preventDefault()
    resetInput()
    return
  }
}

// Global hotkeys when not in textarea
const handleGlobalKeydown = (e) => {
  if (document.activeElement && document.activeElement.tagName === 'TEXTAREA') {
    return
  }

  if (e.key === ' ' || e.key === '`') {
    e.preventDefault()
    togglePlayAudio()
  } else if (e.key === 'Tab') {
    e.preventDefault()
    replayAudio()
  }
}

// Transcript Actions
const playSpecificSentence = (item, idx) => {
  currentChallengeIndex.value = idx
  if (item.audioSrc) {
    if (audioPlayerRef.value) {
      audioPlayerRef.value.src = item.audioSrc
      audioPlayerRef.value.play().catch(() => speech.speak(item.content))
    } else {
      speech.speak(item.content)
    }
  } else {
    speech.speak(item.content)
  }
}

const readFullStory = () => {
  if (!currentLesson.value) return
  const fullText = currentLesson.value.challenges.map(c => c.content).join(' ')
  speech.speak(fullText)
}

onMounted(() => {
  loadCompletedProgress()
  loadSettings()
  window.addEventListener('keydown', handleGlobalKeydown)

  // Fetch from backend API if available
  axios.get('/api/dictation/categories').then(res => {
    if (res.data?.data?.categories?.length) {
      categories.value = res.data.data.categories
    }
    if (res.data?.data?.tips?.length) {
      tipsList.value = res.data.data.tips
    }
  }).catch(() => {})

  axios.get('/api/dictation/lessons').then(res => {
    if (res.data?.data?.length) {
      // Merge with default dataset challenges
      lessons.value = defaultDataset.lessons
    }
  }).catch(() => {})
})

onUnmounted(() => {
  stopAudio()
  window.removeEventListener('keydown', handleGlobalKeydown)
})
</script>

<style scoped>
.bg-gradient-teal {
  background: linear-gradient(135deg, #00b074 0%, #064e40 100%);
}

.max-w-2xl {
  max-width: 650px;
}

.min-w-200 {
  min-width: 200px;
}

.min-w-150 {
  min-width: 150px;
}

.border-teal-top {
  border-top: 3px solid var(--primary-teal) !important;
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-bounce-subtle {
  animation: bounceSubtle 1.5s infinite;
}

@keyframes bounceSubtle {
  0%, 100% { transform: translateX(0); }
  50% { transform: translateX(4px); }
}
</style>
