<template>
  <div class="cefr-vocabulary-page min-vh-100 pb-5" :style="authStore.themeMode === 'dark' ? '' : 'background-color: #F8F7F4;'">
    <div class="container-fluid px-3 px-md-4 px-xl-5 pt-3 pt-md-4 max-w-7xl mx-auto">
      <!-- 1. TOP HEADER BANNER (ĐỒNG BỘ 100% CẢ VỊ TRÍ, KÍCH THƯỚC VÀ NÚT CHUYỂN ĐỔI) -->
      <LearningNavHeader
        category="LỘ TRÌNH TỪ VỰNG CEFR"
        title="Kho Từ Vựng CEFR Toàn Diện & Phản Xạ 3D"
        description="Học trọn bộ từ vựng 6 bậc từ A1 đến C2 theo chuẩn châu Âu. Luyện nhớ chủ động với tính năng gõ lại từ tiếng Anh ở mặt sau, nghe chuẩn US, luyện nói AI và bài tập trắc nghiệm."
        activeTab="cefr"
      >
        <template #badge>
          <!-- Mobile Drawer Toggle Button -->
          <button
            @click="isMobileDrawerOpen = true"
            class="btn btn-xs btn-outline-teal rounded-pill px-2.5 py-1 fw-bold fs-9 d-inline-flex d-lg-none align-items-center gap-1 ms-2"
          >
            <i class="bi bi-list-nested"></i>
            <span>Chọn bài ({{ currentLevel?.code }} • Bài {{ currentLessonNumber }})</span>
          </button>
        </template>
      </LearningNavHeader>

      <!-- ================= 2. MAIN WORKSPACE: 2 CỘT (SIDEBAR + CONTENT) ================= -->
      <div class="row g-3 g-md-4">
      <!-- CỘT TRÁI: DANH SÁCH 6 BẬC CEFR (DESKTOP) -->
      <div class="col-12 col-lg-4 d-none d-lg-block">
        <div class="card p-3 rounded-4 shadow-sm border bg-card-custom sticky-top" style="top: 80px;">
          <div class="pb-2.5 mb-3 border-bottom">
            <span class="fs-8 fw-bold text-uppercase text-muted d-block letter-spacing-1">TỪ VỰNG CƠ BẢN</span>
            <h5 class="fw-black text-teal mb-0">Chọn phần học</h5>
            <span class="text-muted fs-8">Chuẩn CEFR theo bậc • 6 phần</span>
          </div>

          <!-- 6 Level Cards -->
          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="lvl in manifest.levels"
              :key="lvl.id"
              @click="selectLevel(lvl.id)"
              class="p-3 rounded-3 border transition-all cursor-pointer level-card-item"
              :class="selectedLevelId === lvl.id ? 'active-level-card' : 'bg-white hover-shadow'"
            >
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2.5 min-w-0">
                  <div
                    class="rounded-3 d-flex align-items-center justify-content-center text-white fw-black fs-7 flex-shrink-0 shadow-2xs"
                    :style="{ backgroundColor: lvl.color || '#00b074', width: '42px', height: '42px' }"
                  >
                    {{ lvl.code }}
                  </div>
                  <div class="min-w-0">
                    <div class="fw-bold fs-7 text-dark-emphasis text-truncate mb-0.5">
                      {{ lvl.title }}
                    </div>
                    <div class="text-muted fs-8">
                      {{ getLevelProgress(lvl.id).completed }}/{{ lvl.total_lessons }} bài đã hoàn thành • {{ getLevelProgress(lvl.id).percent }}%
                    </div>
                  </div>
                </div>

                <div class="d-flex align-items-center gap-1.5 flex-shrink-0 ms-2">
                  <span class="badge rounded-pill bg-light text-muted border fs-9">
                    {{ lvl.total_words }} từ
                  </span>
                  <i v-if="selectedLevelId === lvl.id" class="bi bi-chevron-right text-teal fw-bold"></i>
                </div>
              </div>

              <!-- Progress bar -->
              <div class="progress mt-2" style="height: 4px;">
                <div
                  class="progress-bar rounded-pill"
                  :style="{ width: `${getLevelProgress(lvl.id).percent}%`, backgroundColor: lvl.color || '#00b074' }"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CỘT PHẢI: BÀI HỌC VÀ KHÔNG GIAN HỌC TẬP (HỖ TRỢ CẢ GIAO DIỆN LỘ TRÌNH CHUẨN ẢNH VÀ CHẾ ĐỘ HỌC TƯƠNG TÁC) -->
      <div class="col-12 col-lg-8">
        <!-- Chuyển đổi giữa [ Lộ trình bài học ] và [ Không gian luyện tập ] -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <div class="btn-group p-1 rounded-pill bg-body-tertiary border shadow-2xs">
            <button
              @click="viewMode = 'roadmap'"
              class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 transition-all"
              :class="viewMode === 'roadmap' ? 'btn-teal text-white shadow-xs' : 'text-muted'"
            >
              <i class="bi bi-diagram-3-fill me-1"></i> Lộ trình bài học ({{ currentLevel?.total_lessons }} bài)
            </button>
            <button
              @click="viewMode = 'study'"
              class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 transition-all"
              :class="viewMode === 'study' ? 'btn-teal text-white shadow-xs' : 'text-muted'"
            >
              <i class="bi bi-mortarboard-fill me-1"></i> Luyện tập: Bài {{ currentLessonNumber }}
            </button>
          </div>

          <button
            v-if="viewMode === 'study'"
            @click="viewMode = 'roadmap'"
            class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-bold fs-8 shadow-2xs d-flex align-items-center gap-1.5"
          >
            <i class="bi bi-arrow-left"></i>
            <span>Về danh sách bài</span>
          </button>
        </div>

        <!-- ================= GIAO DIỆN 1: LỘ TRÌNH BÀI HỌC (CHUẨN 100% THEO ẢNH NGƯỜI DÙNG) ================= -->
        <div v-if="viewMode === 'roadmap'">
          <!-- Phân cấp phần học Banner (Phần 01, 0/45 bài học, 0/450 ⭐, Nút Toàn bộ thẻ →) -->
          <div class="card p-3.5 p-md-4 rounded-4 border-0 shadow-sm text-white mb-3.5" style="background: linear-gradient(135deg, #02231d 0%, #064e40 100%);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
              <div>
                <span class="badge bg-warning text-dark fs-9 fw-black rounded-pill px-2.5 py-1 mb-1.5 d-inline-block">
                  PHẦN {{ String(getLevelIndex(selectedLevelId) + 1).padStart(2, '0') }}
                </span>
                <div class="d-flex align-items-center gap-3 fs-6 fw-bold">
                  <span>{{ completedLessonsInCurrentLevel }} / {{ currentLevel?.total_lessons }} bài học</span>
                  <span class="text-warning">⭐ {{ completedLessonsInCurrentLevel * 10 }} / {{ (currentLevel?.total_lessons || 45) * 10 }}</span>
                </div>
              </div>
              <button
                @click="startAllCards"
                class="btn btn-sm rounded-pill px-3.5 py-2 fw-bold fs-8 shadow-xs border text-white d-flex align-items-center gap-1"
                style="background-color: rgba(255, 255, 255, 0.12); border-color: rgba(255, 255, 255, 0.25) !important;"
              >
                <span>Toàn bộ thẻ</span>
                <i class="bi bi-arrow-right"></i>
              </button>
            </div>

            <!-- Banner: Thi vượt cấp chủ đề -->
            <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.16); backdrop-filter: blur(4px);">
              <div class="d-flex justify-content-between align-items-center flex-wrap gap-2.5">
                <div class="max-w-xl">
                  <div class="fw-bold fs-7 text-white mb-0.5">Thi vượt cấp chủ đề</div>
                  <div class="text-white-50 fs-8 mb-0 leading-relaxed">
                    30 câu theo mức khó: 25 câu bài tập và 5 câu nghe viết. Đúng từ 25 câu là mở khoá mọi bài, mọi mức của chủ đề.
                  </div>
                </div>
                <button
                  @click="startPlacementExam"
                  class="btn btn-sm btn-light text-dark rounded-pill px-3.5 py-2 fw-bold fs-8 shadow-sm d-flex align-items-center gap-1 flex-shrink-0"
                >
                  <span>Làm bài thi</span>
                  <i class="bi bi-arrow-right"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Danh sách từng thẻ bài học (Bài 01, Bài 02... đến bài cuối cùng) -->
          <div class="d-flex flex-column gap-3">
            <div
              v-for="l in currentLevel?.lessons || []"
              :key="l.lesson_number"
              class="card p-3 p-md-3.5 rounded-4 border bg-card-custom shadow-2xs hover-shadow transition-all"
            >
              <!-- Card Header -->
              <div class="d-flex justify-content-between align-items-center pb-2.5 mb-2.5 border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                  <h6 class="fw-black text-teal mb-0 fs-6">{{ l.title }}</h6>
                  <span class="text-muted fs-8 fw-semibold">{{ l.word_count }} mục từ</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span
                    class="badge rounded-pill px-2.5 py-1 fs-9 fw-bold"
                    :class="isLessonCompleted(currentLevel?.id, l.lesson_number) ? 'bg-success text-white' : (currentLessonNumber === l.lesson_number ? 'bg-teal text-white' : 'bg-body-secondary text-muted')"
                  >
                    {{ isLessonCompleted(currentLevel?.id, l.lesson_number) ? 'Đã hoàn thành' : (currentLessonNumber === l.lesson_number ? 'Đang học' : 'Chưa mở') }}
                  </span>
                  <span class="text-warning fw-bold fs-8">
                    {{ isLessonCompleted(currentLevel?.id, l.lesson_number) ? '10/10 ⭐' : '0/10 ⭐' }}
                  </span>
                </div>
              </div>

              <!-- Card Content: 2 cột (Cột 1: Để qua bài, Cột 2: Luyện thêm tuỳ chọn) -->
              <div class="row g-3 align-items-center">
                <!-- Để qua bài: (1) Học thẻ, (2) Bài tập -->
                <div class="col-12 col-md-6 border-end-md">
                  <div class="fs-9 fw-bold text-muted text-uppercase mb-2">Để qua bài</div>
                  <div class="d-flex align-items-center gap-2">
                    <button
                      @click="openLessonStudy(l.lesson_number, 'flashcard')"
                      class="btn btn-sm rounded-pill px-3 py-2 fw-bold fs-8 d-flex align-items-center gap-2 flex-fill justify-content-center shadow-xs transition-all"
                      :class="currentLessonNumber === l.lesson_number || isLessonCompleted(currentLevel?.id, l.lesson_number) ? 'btn-teal text-white' : 'btn-outline-secondary'"
                      title="Học thẻ Flashcard 3D"
                    >
                      <span class="rounded-circle bg-white text-dark d-inline-flex align-items-center justify-content-center fw-black shadow-2xs" style="width: 20px; height: 20px; font-size: 11px;">1</span>
                      <span>Học thẻ</span>
                    </button>

                    <button
                      @click="openLessonStudy(l.lesson_number, 'quiz')"
                      class="btn btn-sm rounded-pill px-3 py-2 fw-bold fs-8 d-flex align-items-center gap-2 flex-fill justify-content-center shadow-xs transition-all"
                      :class="isLessonCompleted(currentLevel?.id, l.lesson_number) ? 'btn-success text-white' : 'btn-outline-secondary'"
                      title="Làm bài tập trắc nghiệm"
                    >
                      <span class="rounded-circle bg-white text-dark d-inline-flex align-items-center justify-content-center fw-black shadow-2xs" style="width: 20px; height: 20px; font-size: 11px;">2</span>
                      <span>{{ isLessonCompleted(currentLevel?.id, l.lesson_number) ? 'Bài tập' : 'Khoá' }}</span>
                    </button>
                  </div>
                </div>

                <!-- Luyện thêm tuỳ chọn: Nghe viết, Phát âm -->
                <div class="col-12 col-md-6">
                  <div class="fs-9 fw-bold text-muted text-uppercase mb-2">Luyện thêm tuỳ chọn</div>
                  <div class="d-flex align-items-center gap-2">
                    <button
                      @click="openLessonStudy(l.lesson_number, 'dictation')"
                      class="btn btn-sm btn-outline-teal rounded-pill px-3 py-2 fw-semibold fs-8 d-flex align-items-center gap-1.5 flex-fill justify-content-center shadow-2xs transition-all"
                    >
                      <i class="bi bi-headphones"></i>
                      <span>Nghe viết</span>
                    </button>

                    <button
                      @click="openLessonStudy(l.lesson_number, 'speech')"
                      class="btn btn-sm btn-outline-teal rounded-pill px-3 py-2 fw-semibold fs-8 d-flex align-items-center gap-1.5 flex-fill justify-content-center shadow-2xs transition-all"
                    >
                      <i class="bi bi-mic-fill"></i>
                      <span>Phát âm</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= GIAO DIỆN 2: KHÔNG GIAN HỌC TẬP TƯƠNG TÁC (FLASHCARD, DICTATION, SPEECH, QUIZ) ================= -->
        <div v-else>
          <!-- Lesson Header & Selector Carousel -->
          <div class="card p-3 p-md-3.5 rounded-4 shadow-sm border bg-card-custom mb-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2.5">
              <div>
                <div class="d-flex align-items-center gap-2 mb-0.5">
                  <span class="badge px-2.5 py-1 rounded-pill text-white fw-black fs-8" :style="{ backgroundColor: currentLevel?.color || '#00b074' }">
                    {{ currentLevel?.code }}
                  </span>
                  <h5 class="fw-black text-teal mb-0">
                    {{ currentLevel?.title }} • Bài {{ currentLessonNumber }}
                  </h5>
                </div>
                <div class="text-muted fs-8">
                  {{ currentLessonWords.length }} từ vựng trọng tâm • Đã thuộc: {{ masteredInCurrentLesson }} / {{ currentLessonWords.length }} từ ({{ lessonPercent }}%)
                </div>
              </div>

              <!-- Fast Lesson Dropdown -->
              <div class="dropdown">
                <button
                  class="btn btn-sm btn-outline-teal dropdown-toggle rounded-pill px-3 py-1.5 fw-semibold fs-8"
                  type="button"
                  data-bs-toggle="dropdown"
                >
                  <span>Chuyển nhanh bài ({{ currentLevel?.total_lessons }} bài)</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-8 overflow-y-auto" style="max-height: 280px;">
                  <li v-for="l in currentLevel?.lessons || []" :key="l.lesson_number">
                    <button
                      class="dropdown-item d-flex justify-content-between align-items-center"
                      :class="{ 'active bg-teal text-white': currentLessonNumber === l.lesson_number }"
                      @click="selectLesson(l.lesson_number)"
                    >
                      <span>{{ l.title }} ({{ l.word_count }} từ)</span>
                      <i v-if="isLessonCompleted(currentLevel?.id, l.lesson_number)" class="bi bi-check-circle-fill text-success ms-2"></i>
                    </button>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Lesson Navigation Pills Carousel (Nhấn giữ chuột trái kéo qua & vuốt ngang mượt mà) -->
            <div v-drag-scroll class="d-flex gap-1.5 overflow-x-auto pb-1 no-scrollbar drag-scroll-x">
              <button
                v-for="l in currentLevel?.lessons || []"
                :key="l.lesson_number"
                @click="selectLesson(l.lesson_number)"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap d-flex align-items-center gap-1.5 border"
                :class="currentLessonNumber === l.lesson_number ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
              >
                <span>{{ l.title }}</span>
                <span class="badge rounded-pill" :class="currentLessonNumber === l.lesson_number ? 'bg-white text-dark' : 'bg-light text-muted'" style="font-size: 9px;">
                  {{ l.word_count }} từ
                </span>
                <i v-if="isLessonCompleted(currentLevel?.id, l.lesson_number)" class="bi bi-check-circle-fill text-success fs-9"></i>
              </button>
            </div>

            <!-- Learning Mode Selector (Nhấn giữ chuột trái kéo qua & ẩn thanh kéo) -->
            <div v-drag-scroll class="d-flex gap-2 mt-3 pt-3 border-top overflow-x-auto pb-1 no-scrollbar drag-scroll-x">
              <button
                @click="activeMode = 'flashcard'"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-nowrap border"
                :class="activeMode === 'flashcard' ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
              >
                <i class="bi bi-card-text me-1"></i> Thẻ Flashcard 3D & Gõ từ
              </button>
              <button
                @click="activeMode = 'dictation'"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-nowrap border"
                :class="activeMode === 'dictation' ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
              >
                <i class="bi bi-headphones me-1"></i> Luyện Nghe & Viết
              </button>
              <button
                @click="activeMode = 'speech'"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-nowrap border"
                :class="activeMode === 'speech' ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
              >
                <i class="bi bi-mic-fill me-1"></i> Luyện Phát Âm AI
              </button>
              <button
                @click="activeMode = 'quiz'"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-nowrap border"
                :class="activeMode === 'quiz' ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
              >
                <i class="bi bi-patch-check-fill me-1 text-warning"></i> Trắc Nghiệm (+XP)
              </button>
              <button
                @click="activeMode = 'list'"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-nowrap border"
                :class="activeMode === 'list' ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
              >
                <i class="bi bi-list-ul me-1"></i> Danh Sách Từ Vựng
              </button>
            </div>
          </div>

        <!-- ================= MODE 1: THẺ FLASHCARD 3D TƯƠNG TÁC + GÕ LẠI TỪ MẶT SAU ================= -->
        <div v-if="activeMode === 'flashcard'">
          <FlashcardPlayer
            :words="currentLessonWordsFormatted"
            @status-updated="handleWordStatusUpdated"
            @update-status="handleWordStatusUpdated"
          />
        </div>

        <!-- ================= MODE 2: LUYỆN NGHE & VIẾT CHÍNH TẢ (DICTATION & SPELLING) ================= -->
        <div v-else-if="activeMode === 'dictation'" class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
              <h5 class="fw-black text-teal mb-1">🎧 Luyện Nghe & Gõ Lại Từ Vựng Chính Tả</h5>
              <p class="text-muted fs-8 mb-0">Nghe phát âm chuẩn US và gõ lại đúng chính tả từ tiếng Anh để rèn phản xạ đôi tai!</p>
            </div>
            <span class="badge bg-teal text-white rounded-pill px-3 py-1.5 fs-8 fw-bold">
              Câu {{ dictationIndex + 1 }} / {{ currentLessonWords.length }}
            </span>
          </div>

          <div v-if="currentDictationWord" class="p-4 rounded-4 border bg-white text-center shadow-xs">
            <!-- Audio trigger button -->
            <button
              @click="speech.speak(currentDictationWord.term)"
              class="btn btn-teal rounded-circle p-0 d-inline-flex align-items-center justify-content-center shadow-sm mb-3 animate-bounce"
              style="width: 72px; height: 72px;"
              title="Bấm để nghe phát âm"
            >
              <i class="bi bi-volume-up-fill fs-2 text-white"></i>
            </button>
            <div class="text-muted fs-8 mb-3">
              Bấm vào biểu tượng loa để nghe lại âm thanh (hoặc phím Enter để kiểm tra)
            </div>

            <!-- Meaning hint -->
            <div class="p-2.5 rounded-3 bg-light border mb-3 fs-7">
              <span class="text-muted me-1">Nghĩa tiếng Việt:</span>
              <strong class="text-success">{{ currentDictationWord.definition }}</strong>
              <span class="badge bg-light text-muted border ms-2 fs-8">{{ currentDictationWord.part_of_speech }}</span>
            </div>

            <!-- Dictation input form -->
            <div class="max-w-md mx-auto">
              <div class="input-group mb-2">
                <input
                  ref="dictationInputRef"
                  v-model="dictationInput"
                  @keyup.enter="checkDictationAnswer"
                  type="text"
                  class="form-control form-control-lg rounded-pill-start px-3 py-2 fs-6 border"
                  :class="{
                    'border-success bg-success-subtle text-success fw-bold': dictationResult === true,
                    'border-danger bg-danger-subtle': dictationResult === false
                  }"
                  placeholder="Gõ từ bạn vừa nghe được..."
                  autocomplete="off"
                />
                <button
                  @click="checkDictationAnswer"
                  class="btn btn-teal text-white rounded-pill-end px-3.5 fw-bold"
                >
                  Kiểm tra
                </button>
              </div>

              <!-- Answer helpers -->
              <div class="d-flex justify-content-between align-items-center gap-2 mt-2">
                <button
                  @click="dictationShowAnswer = !dictationShowAnswer"
                  class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fs-8 border"
                >
                  <i class="bi" :class="dictationShowAnswer ? 'bi-eye-slash' : 'bi-eye'"></i>
                  <span>{{ dictationShowAnswer ? 'Ẩn đáp án' : 'Xem đáp án' }}</span>
                </button>

                <div class="d-flex gap-1.5">
                  <button
                    @click="prevDictation"
                    :disabled="dictationIndex === 0"
                    class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fs-8"
                  >
                    ← Trước
                  </button>
                  <button
                    @click="nextDictation"
                    :disabled="dictationIndex === currentLessonWords.length - 1"
                    class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1 fs-8"
                  >
                    Tiếp →
                  </button>
                </div>
              </div>

              <!-- Answer Feedback -->
              <div v-if="dictationResult === true" class="mt-3 p-2.5 rounded-3 bg-success-subtle text-success border border-success-subtle fs-7 fw-bold">
                ✓ Chính xác tuyệt đối! Đáp án đúng: "{{ currentDictationWord.term }}"
              </div>
              <div v-else-if="dictationResult === false" class="mt-3 p-2.5 rounded-3 bg-danger-subtle text-danger border border-danger-subtle fs-7">
                ✕ Chưa chính xác! Hãy nghe kỹ lại hoặc bấm "Xem đáp án" để đối chiếu nhé.
              </div>

              <div v-if="dictationShowAnswer" class="mt-2.5 p-2.5 rounded-3 bg-teal-subtle text-teal border border-teal fs-7 fw-bold">
                Đáp án: <span class="font-monospace text-decoration-underline">{{ currentDictationWord.term }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= MODE 3: LUYỆN NÓI PHÁT ÂM AI CHUẨN TỪNG TỪ ================= -->
        <div v-else-if="activeMode === 'speech'" class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
              <h5 class="fw-black text-teal mb-1">🎙️ Luyện Phát Âm Trực Tiếp Với AI</h5>
              <p class="text-muted fs-8 mb-0">Hệ thống AI nhận diện giọng nói, so sánh phiên âm và chấm điểm độ chính xác cho từng từ!</p>
            </div>
            <span class="badge bg-teal text-white rounded-pill px-3 py-1.5 fs-8 fw-bold">
              Đạt &gt;= 80% là qua bài
            </span>
          </div>

          <div class="row g-3">
            <div
              v-for="word in currentLessonWords"
              :key="word.id"
              class="col-12 col-md-6"
            >
              <div class="card p-3 rounded-4 border bg-white shadow-2xs h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <h5 class="fw-black text-teal mb-0.5">{{ word.term }}</h5>
                      <span class="badge bg-light text-muted border fs-8">{{ word.part_of_speech }}</span>
                    </div>
                    <div class="d-flex gap-1.5">
                      <button
                        @click="speech.speak(word.term)"
                        class="btn btn-sm btn-outline-teal rounded-circle p-1.5 shadow-2xs"
                        style="width: 32px; height: 32px;"
                        title="Nghe mẫu chuẩn US"
                      >
                        <i class="bi bi-volume-up-fill fs-8"></i>
                      </button>
                      <button
                        @click="toggleSpeechForWord(word)"
                        class="btn btn-sm rounded-circle p-1.5 shadow-2xs transition-all"
                        :class="activeListeningWordId === word.id && speech.isListening.value ? 'btn-danger animate-mic-pulse text-white' : 'btn-outline-info'"
                        style="width: 32px; height: 32px;"
                        title="Luyện nói từ này"
                      >
                        <i class="bi" :class="activeListeningWordId === word.id && speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
                      </button>
                    </div>
                  </div>

                  <div class="fw-bold text-success fs-7 mb-1">{{ word.definition }}</div>
                  <div v-if="word.pattern" class="text-muted fs-8 mb-2 fst-italic">Cấu trúc: {{ word.pattern }}</div>
                </div>

                <!-- Speech Result Box -->
                <div class="mt-2 pt-2 border-top">
                  <div v-if="activeListeningWordId === word.id && speech.isListening.value" class="alert alert-warning py-1 px-2 rounded-3 fs-8 mb-0 d-flex align-items-center gap-1.5">
                    <span class="spinner-grow spinner-grow-sm text-danger"></span>
                    <span>Đang nghe... Đọc to: <strong>"{{ word.term }}"</strong></span>
                  </div>
                  <div v-else-if="speechResults[word.id]" class="d-flex justify-content-between align-items-center fs-8">
                    <span class="text-truncate me-1 text-muted">Bạn đọc: <strong>"{{ speechResults[word.id].transcript || '...' }}"</strong></span>
                    <span class="badge" :class="speechResults[word.id].score >= 80 ? 'bg-success text-white' : 'bg-warning text-dark'">
                      {{ speechResults[word.id].score }}%
                    </span>
                  </div>
                  <div v-else class="text-muted fs-8 fst-italic text-center">
                    Bấm biểu tượng Micro để đọc thử từ này
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= MODE 4: TRẮC NGHIỆM TÍCH LŨY ĐIỂM SỐ (+XP) ================= -->
        <div v-else-if="activeMode === 'quiz'" class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
              <h5 class="fw-black text-teal mb-1">📝 Trắc Nghiệm Ôn Tập Bài {{ currentLessonNumber }}</h5>
              <p class="text-muted fs-8 mb-0">Trả lời 10 câu hỏi trắc nghiệm để củng cố phản xạ và nhận +15 XP cho mỗi câu đúng!</p>
            </div>
            <button
              @click="generateQuiz"
              class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-8 fw-bold"
            >
              <i class="bi bi-arrow-repeat me-1"></i> Làm đề mới
            </button>
          </div>

          <!-- Quiz Questions List -->
          <div class="d-flex flex-column gap-3">
            <div
              v-for="(q, idx) in quizQuestions"
              :key="idx"
              class="p-3.5 rounded-4 border bg-white shadow-2xs"
            >
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-teal text-white rounded-pill px-2.5 py-1 fs-8 fw-bold">
                  Câu {{ idx + 1 }}
                </span>
                <span v-if="quizUserAnswers[idx] !== undefined" class="badge rounded-pill px-2.5 py-1 fs-8" :class="quizUserAnswers[idx] === q.correct ? 'bg-success text-white' : 'bg-danger text-white'">
                  {{ quizUserAnswers[idx] === q.correct ? '✓ Đúng (+15 XP)' : '✕ Sai' }}
                </span>
              </div>

              <div class="fs-6 fw-bold text-dark-emphasis mb-2.5">
                Từ <span class="text-teal fs-5 font-monospace">"{{ q.prompt_word }}"</span> có nghĩa tiếng Việt là gì?
              </div>

              <!-- Options Grid -->
              <div class="row g-2">
                <div
                  v-for="(opt, optIdx) in q.options"
                  :key="optIdx"
                  class="col-12 col-md-6"
                >
                  <button
                    @click="chooseQuizOption(idx, optIdx)"
                    class="btn btn-sm w-100 text-start p-2.5 rounded-3 border transition-all fs-8 fw-semibold"
                    :class="{
                      'btn-teal text-white shadow-xs': quizUserAnswers[idx] === optIdx && quizUserAnswers[idx] === q.correct,
                      'btn-danger text-white shadow-xs': quizUserAnswers[idx] === optIdx && quizUserAnswers[idx] !== q.correct,
                      'btn-success text-white': quizUserAnswers[idx] !== undefined && optIdx === q.correct,
                      'btn-outline-secondary': quizUserAnswers[idx] === undefined,
                      'bg-light text-muted': quizUserAnswers[idx] !== undefined && quizUserAnswers[idx] !== optIdx && optIdx !== q.correct
                    }"
                    :disabled="quizUserAnswers[idx] !== undefined"
                  >
                    <span class="badge rounded-circle bg-white text-dark me-1.5" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center;">
                      {{ ['A', 'B', 'C', 'D'][optIdx] }}
                    </span>
                    <span>{{ opt }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= MODE 5: DANH SÁCH TỪ VỰNG CHI TIẾT ================= -->
        <div v-else-if="activeMode === 'list'" class="card rounded-4 border shadow-sm overflow-hidden bg-card-custom mb-4">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light fs-8 text-uppercase">
                <tr>
                  <th style="width: 50px;" class="text-center">#</th>
                  <th>Từ tiếng Anh</th>
                  <th>Nghĩa tiếng Việt</th>
                  <th>Từ loại</th>
                  <th>Cấu trúc / Mẫu câu</th>
                  <th style="width: 120px;" class="text-center">Trạng thái</th>
                </tr>
              </thead>
              <tbody class="fs-7">
                <tr v-for="(w, idx) in currentLessonWords" :key="w.id">
                  <td class="text-center text-muted fw-bold">{{ idx + 1 }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <button
                        @click="speech.speak(w.term)"
                        class="btn btn-sm btn-outline-teal rounded-circle p-1 d-flex align-items-center justify-content-center shadow-2xs"
                        style="width: 28px; height: 28px;"
                        title="Nghe phát âm"
                      >
                        <i class="bi bi-volume-up-fill fs-8"></i>
                      </button>
                      <div>
                        <strong class="text-teal fs-6">{{ w.term }}</strong>
                        <div v-if="w.example_en" class="text-muted fs-8 fst-italic">{{ w.example_en }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="fw-semibold text-dark-emphasis">{{ w.definition }}</td>
                  <td><span class="badge bg-light text-muted border fs-8">{{ w.part_of_speech }}</span></td>
                  <td class="text-muted fs-8">{{ w.pattern || '—' }}</td>
                  <td class="text-center">
                    <span
                      class="badge px-2.5 py-1 rounded-pill fw-bold fs-8"
                      :class="userWordStatus[w.id] === 'mastered' ? 'bg-success text-white' : userWordStatus[w.id] === 'learning' ? 'bg-warning text-dark' : 'bg-light text-muted border'"
                    >
                      {{ userWordStatus[w.id] === 'mastered' ? '✓ Đã nhớ' : userWordStatus[w.id] === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        </div> <!-- End of v-else (Interactive Study Mode) -->
      </div>
    </div>

    <!-- ================= MOBILE OFFCANVAS DRAWER: CHỌN BẬC & BÀI HỌC (CHỈ HIỆN TRÊN MOBILE, KHÔNG BAO GIỜ HIỆN TRÊN DESKTOP) ================= -->
    <div
      v-if="isMobileDrawerOpen"
      class="mobile-drawer-overlay d-lg-none"
      @click="isMobileDrawerOpen = false"
    ></div>

    <aside
      v-if="isMobileDrawerOpen"
      class="cefr-mobile-drawer d-lg-none d-flex flex-column p-3 shadow-lg no-scrollbar"
      :class="authStore.themeMode === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark'"
      style="max-width: 320px;"
    >
      <div class="d-flex align-items-center justify-content-between pb-2.5 mb-2.5 border-bottom">
        <div>
          <span class="fs-8 fw-bold text-uppercase text-muted">TỪ VỰNG CƠ BẢN</span>
          <h6 class="fw-black mb-0 text-teal">CHỌN PHẦN HỌC CEFR</h6>
        </div>
        <button
          @click="isMobileDrawerOpen = false"
          class="btn btn-sm btn-danger text-white rounded-pill px-2.5 py-1 fw-bold fs-8 d-flex align-items-center gap-1 shadow-sm"
        >
          <i class="bi bi-x-lg"></i>
          <span>Thụt lại</span>
        </button>
      </div>

      <!-- Level Selector on Mobile Drawer -->
      <div class="d-flex flex-column gap-2 overflow-y-auto flex-grow-1 pr-1 no-scrollbar">
        <div
          v-for="lvl in manifest.levels"
          :key="lvl.id"
          @click="selectLevel(lvl.id)"
          class="p-2.5 rounded-3 border transition-all cursor-pointer"
          :class="selectedLevelId === lvl.id ? 'active-level-card' : (authStore.themeMode === 'dark' ? 'bg-secondary-subtle' : 'bg-white hover-shadow')"
        >
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 min-w-0">
              <div
                class="rounded-2 d-flex align-items-center justify-content-center text-white fw-black fs-8 flex-shrink-0"
                :style="{ backgroundColor: lvl.color || '#00b074', width: '32px', height: '32px' }"
              >
                {{ lvl.code }}
              </div>
              <div class="text-truncate">
                <div class="fw-bold fs-7 text-truncate" :class="authStore.themeMode === 'dark' ? 'text-white' : 'text-dark'">{{ lvl.title }}</div>
                <div class="text-muted fs-8">{{ lvl.total_lessons }} bài • {{ lvl.total_words }} từ</div>
              </div>
            </div>
            <i v-if="selectedLevelId === lvl.id" class="bi bi-check-circle-fill text-success fs-7"></i>
          </div>
        </div>

        <!-- Lessons List for selected level on drawer -->
        <div class="mt-3 pt-2.5 border-top">
          <span class="fs-8 fw-bold text-muted text-uppercase d-block mb-2">
            Chọn Bài Học ({{ currentLevel?.total_lessons }} bài):
          </span>
          <div class="d-grid gap-1.5" style="grid-template-columns: repeat(2, 1fr);">
            <button
              v-for="l in currentLevel?.lessons || []"
              :key="l.lesson_number"
              @click="selectLesson(l.lesson_number); isMobileDrawerOpen = false;"
              class="btn btn-sm rounded-pill p-1.5 fs-8 text-truncate border text-start d-flex align-items-center justify-content-between"
              :class="currentLessonNumber === l.lesson_number ? 'btn-teal text-white border-teal fw-bold' : 'btn-outline-secondary'"
            >
              <span class="text-truncate">{{ l.title }}</span>
              <i v-if="isLessonCompleted(currentLevel?.id, l.lesson_number)" class="bi bi-check-circle-fill text-success fs-9 ms-1"></i>
            </button>
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useSpeech } from '@/composables/useSpeech'
import LearningNavHeader from '@/components/LearningNavHeader.vue'
import FlashcardPlayer from '@/components/FlashcardPlayer.vue'

// Import manifest & level datasets
import manifestData from '@/data/cefr/manifest.json'
import a1Data from '@/data/cefr/a1.json'
import a2Data from '@/data/cefr/a2.json'
import b1Data from '@/data/cefr/b1.json'
import b2Data from '@/data/cefr/b2.json'
import c1Data from '@/data/cefr/c1.json'
import c2Data from '@/data/cefr/c2.json'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const speech = useSpeech()

const manifest = manifestData
const levelDatasets = {
  a1: a1Data,
  a2: a2Data,
  b1: b1Data,
  b2: b2Data,
  c1: c1Data,
  c2: c2Data,
}

// View mode: 'roadmap' (mặc định theo giao diện ảnh người dùng cung cấp) hoặc 'study' (không gian luyện tập)
const viewMode = ref('roadmap')
const selectedLevelId = ref(route.params.levelId || 'a1')
const currentLessonNumber = ref(parseInt(route.params.lessonNum) || 1)
const activeMode = ref('flashcard') // 'flashcard' | 'dictation' | 'speech' | 'quiz' | 'list'
const isMobileDrawerOpen = ref(false)

const getLevelIndex = (lvlId) => {
  return manifest.levels.findIndex(l => l.id === lvlId)
}

const completedLessonsInCurrentLevel = computed(() => {
  const lvl = currentLevel.value
  if (!lvl || !lvl.lessons) return 0
  return lvl.lessons.filter(l => isLessonCompleted(lvl.id, l.lesson_number)).length
})

const openLessonStudy = (lessonNum, mode = 'flashcard') => {
  currentLessonNumber.value = lessonNum
  activeMode.value = mode
  viewMode.value = 'study'
  dictationIndex.value = 0
  dictationInput.value = ''
  dictationResult.value = null
  dictationShowAnswer.value = false
  generateQuiz()
  window.scrollTo({ top: 120, behavior: 'smooth' })
}

const startAllCards = () => {
  activeMode.value = 'flashcard'
  viewMode.value = 'study'
  window.scrollTo({ top: 120, behavior: 'smooth' })
}

const startPlacementExam = () => {
  activeMode.value = 'quiz'
  viewMode.value = 'study'
  generateQuiz()
  window.scrollTo({ top: 120, behavior: 'smooth' })
}

// User progress storage (localStorage)
const userWordStatus = ref({})
const userCompletedLessons = ref({})

// Speech state
const activeListeningWordId = ref(null)
const speechResults = ref({})

// Dictation state
const dictationIndex = ref(0)
const dictationInput = ref('')
const dictationResult = ref(null) // null | true | false
const dictationShowAnswer = ref(false)

// Quiz state
const quizQuestions = ref([])
const quizUserAnswers = ref({})

const currentLevel = computed(() => {
  return levelDatasets[selectedLevelId.value] || a1Data
})

const currentLesson = computed(() => {
  const lessons = currentLevel.value?.lessons || []
  return lessons.find(l => l.lesson_number === currentLessonNumber.value) || lessons[0] || null
})

const currentLessonWords = computed(() => {
  return currentLesson.value?.words || []
})

// Format words for FlashcardPlayer
const currentLessonWordsFormatted = computed(() => {
  return currentLessonWords.value.map(w => ({
    id: w.id,
    word: w.term,
    phonetic_us: w.pattern || '',
    part_of_speech: w.part_of_speech || '',
    meaning_vi: w.definition,
    example_en: w.example_en || '',
    example_vi: w.example_vi || '',
    cefr_level: currentLevel.value?.code || 'A1',
    status: userWordStatus.value[w.id] || 'unlearned',
  }))
})

const currentDictationWord = computed(() => {
  return currentLessonWords.value[dictationIndex.value] || null
})

const masteredInCurrentLesson = computed(() => {
  return currentLessonWords.value.filter(w => userWordStatus.value[w.id] === 'mastered').length
})

const lessonPercent = computed(() => {
  if (currentLessonWords.value.length === 0) return 0
  return Math.round((masteredInCurrentLesson.value / currentLessonWords.value.length) * 100)
})

// Navigation actions
const selectLevel = (levelId) => {
  selectedLevelId.value = levelId
  currentLessonNumber.value = 1
  dictationIndex.value = 0
  dictationInput.value = ''
  dictationResult.value = null
  dictationShowAnswer.value = false
  generateQuiz()
}

const selectLesson = (lessonNum) => {
  currentLessonNumber.value = lessonNum
  dictationIndex.value = 0
  dictationInput.value = ''
  dictationResult.value = null
  dictationShowAnswer.value = false
  generateQuiz()
}

// Word status handling
const handleWordStatusUpdated = ({ wordId, status }) => {
  userWordStatus.value[wordId] = status
  saveProgress()
  checkAndMarkLessonCompletion()
}

const checkAndMarkLessonCompletion = () => {
  if (currentLessonWords.value.length === 0) return
  const allMastered = currentLessonWords.value.every(w => userWordStatus.value[w.id] === 'mastered')
  const key = `${selectedLevelId.value}_lesson_${currentLessonNumber.value}`
  if (allMastered) {
    userCompletedLessons.value[key] = true
  }
}

const isLessonCompleted = (levelId, lessonNum) => {
  return !!userCompletedLessons.value[`${levelId}_lesson_${lessonNum}`]
}

const getLevelProgress = (levelId) => {
  const lvl = manifest.levels.find(l => l.id === levelId)
  if (!lvl) return { completed: 0, percent: 0 }
  let completedCount = 0
  for (let i = 1; i <= lvl.total_lessons; i++) {
    if (userCompletedLessons.value[`${levelId}_lesson_${i}`]) {
      completedCount++
    }
  }
  const percent = Math.round((completedCount / lvl.total_lessons) * 100)
  return { completed: completedCount, percent }
}

// Dictation actions
const cleanStr = (s) => (s || '').toLowerCase().trim().replace(/[.,?!:;"'_\-()]/g, '')

const checkDictationAnswer = () => {
  if (!currentDictationWord.value) return
  const isMatch = cleanStr(dictationInput.value) === cleanStr(currentDictationWord.value.term)
  dictationResult.value = isMatch
  if (isMatch) {
    speech.speak(currentDictationWord.value.term)
    handleWordStatusUpdated({ wordId: currentDictationWord.value.id, status: 'mastered' })
  }
}

const nextDictation = () => {
  if (dictationIndex.value < currentLessonWords.value.length - 1) {
    dictationIndex.value++
    dictationInput.value = ''
    dictationResult.value = null
    dictationShowAnswer.value = false
  }
}

const prevDictation = () => {
  if (dictationIndex.value > 0) {
    dictationIndex.value--
    dictationInput.value = ''
    dictationResult.value = null
    dictationShowAnswer.value = false
  }
}

// Speech recognition for word
const toggleSpeechForWord = (word) => {
  if (activeListeningWordId.value === word.id && speech.isListening.value) {
    speech.stopListening()
    activeListeningWordId.value = null
    return
  }
  activeListeningWordId.value = word.id
  speech.startListening(word.term, 'cefr_' + word.id)
}

watch(() => speech.isListening.value, (isListening) => {
  if (!isListening && activeListeningWordId.value) {
    const wordId = activeListeningWordId.value
    const res = speech.itemResults.value['cefr_' + wordId]
    if (res) {
      speechResults.value[wordId] = res
      if (res.score >= 80) {
        handleWordStatusUpdated({ wordId, status: 'mastered' })
      }
    }
    activeListeningWordId.value = null
  }
})

// Quiz generator
const generateQuiz = () => {
  quizUserAnswers.value = {}
  const words = currentLessonWords.value
  if (words.length === 0) {
    quizQuestions.value = []
    return
  }

  const allDefinitions = words.map(w => w.definition)
  const qs = []

  words.slice(0, 10).forEach(w => {
    const correctDef = w.definition
    const otherDefs = allDefinitions.filter(d => d !== correctDef)
    // Pick 3 random distractors
    const shuffledDistractors = otherDefs.sort(() => 0.5 - Math.random()).slice(0, 3)
    const options = [correctDef, ...shuffledDistractors].sort(() => 0.5 - Math.random())
    const correctIdx = options.indexOf(correctDef)

    qs.push({
      prompt_word: w.term,
      options,
      correct: correctIdx,
    })
  })

  quizQuestions.value = qs
}

const chooseQuizOption = (qIdx, optIdx) => {
  if (quizUserAnswers.value[qIdx] !== undefined) return
  quizUserAnswers.value[qIdx] = optIdx
  const q = quizQuestions.value[qIdx]
  if (optIdx === q.correct) {
    // Award small XP and update authStore
    if (authStore.user) {
      authStore.user.xp_points = (authStore.user.xp_points || 0) + 15
    }
  }
}

// Storage persistence
const loadProgress = () => {
  try {
    const savedWords = localStorage.getItem('oxford_cefr_words')
    if (savedWords) userWordStatus.value = JSON.parse(savedWords)
    const savedLessons = localStorage.getItem('oxford_cefr_lessons')
    if (savedLessons) userCompletedLessons.value = JSON.parse(savedLessons)
  } catch (e) {}
}

const saveProgress = () => {
  try {
    localStorage.setItem('oxford_cefr_words', JSON.stringify(userWordStatus.value))
    localStorage.setItem('oxford_cefr_lessons', JSON.stringify(userCompletedLessons.value))
  } catch (e) {}
}

onMounted(() => {
  loadProgress()
  generateQuiz()
})

onUnmounted(() => {
  speech.stopSpeech()
  speech.stopListening()
})
</script>

<style scoped>
.cefr-vocabulary-page {
  min-height: calc(100vh - 120px);
}

.learning-tabs-pill a {
  color: #64748b;
}

.learning-tabs-pill a.btn-teal {
  color: #ffffff !important;
  background: linear-gradient(135deg, #00b074 0%, #064e40 100%) !important;
}

.level-card-item {
  border-color: rgba(0, 0, 0, 0.08);
}

.active-level-card {
  border-color: #00b074 !important;
  background-color: rgba(0, 176, 116, 0.08) !important;
  box-shadow: 0 4px 12px rgba(0, 176, 116, 0.12) !important;
}

.hover-shadow:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  border-color: rgba(0, 176, 116, 0.35);
}

.cursor-pointer {
  cursor: pointer;
}

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Mobile Drawer Overlay */
.mobile-drawer-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  z-index: 1060;
}

.mobile-drawer-content {
  position: fixed;
  top: 0;
  left: 0;
  width: 82vw;
  max-width: 320px;
  height: 100vh;
  background-color: #ffffff;
  z-index: 1070;
  transform: translateX(-100%);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.mobile-drawer-content.show {
  transform: translateX(0);
}
</style>
