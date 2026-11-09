<template>
  <div class="flashcard-player w-100 mx-auto my-3" style="max-width: 920px;">
    <!-- 1. Helper Tips Banner (Desktop: Phím tắt đầy đủ; Mobile: Chỉ dẫn chạm ngón tay) -->
    <div class="alert alert-primary bg-primary-subtle border-primary-subtle text-primary-emphasis d-none d-md-flex align-items-center justify-content-between py-2 px-3 mb-3 rounded-3 shadow-xs fs-7">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <i class="bi bi-lightbulb-fill text-warning fs-6"></i>
        <span>
          <strong>Phím tắt:</strong>
          <code>Space</code>: Lật thẻ |
          <code>Q / ←</code>: Trước |
          <code>E / →</code>: Tiếp |
          <code>A</code>: Nghe |
          <code>D</code>: Luyện nói |
          <code>S</code>: Đã nhớ |
          <code>X</code>: Cần ôn
        </span>
      </div>
      <div class="badge bg-primary text-white ms-2 fs-7 px-2.5 py-1 rounded-pill shrink-0">
        {{ currentIndex + 1 }} / {{ words.length }}
      </div>
    </div>

    <!-- Mobile Touch Hint Banner -->
    <div class="d-flex d-md-none align-items-center justify-content-between py-1.5 px-3 mb-2 rounded-3 bg-primary-subtle text-primary-emphasis fs-8 shadow-2xs">
      <div class="d-flex align-items-center gap-1.5">
        <i class="bi bi-hand-index-thumb-fill text-warning"></i>
        <span><strong>Chạm thẻ bất kỳ đâu</strong> để lật xem nghĩa & gõ từ</span>
      </div>
      <div class="badge bg-primary text-white fs-8 px-2 py-0.5 rounded-pill">
        {{ currentIndex + 1 }} / {{ words.length }}
      </div>
    </div>

    <!-- 2. THE 3D CARD SCENE (Hỗ trợ lật thẻ bằng cách click bất kỳ vị trí trống nào, Hover Glow cao cấp trên PC) -->
    <div
      class="card-scene perspective-1000 w-100 mb-3 flashcard-3d-box"
      @click="handleCardClick"
      title="Bấm vào thẻ để lật 3D (Phím Space)"
    >
      <div
        class="flashcard-3d-inner position-relative w-100 rounded-4 shadow-lg border-0 transform-style-preserve-3d"
        :class="{ 'rotate-y-180': isFlipped }"
      >
        <!-- ================= FRONT FACE (Mặt Trước: Tiếng Anh, Phiên âm IPA, Audio US, Luyện nói AI, Ví dụ) ================= -->
        <div
          class="card-face card-face-front position-absolute top-0 start-0 w-100 h-100 rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between text-white border-0 overflow-hidden"
          style="background: linear-gradient(145deg, #02231d 0%, #04382f 40%, #064e40 100%);"
        >
          <!-- Top Bar: Category / Badge & Status Indicator -->
          <div class="d-flex justify-content-between align-items-center position-relative z-1 flex-wrap gap-2">
            <span class="fs-8 fw-bold letter-spacing-1 text-uppercase text-truncate" style="color: #5eead4; max-width: 260px;">
              {{ currentCategoryLabel }}
            </span>

            <div class="d-flex align-items-center gap-1.5 shrink-0 interactive-zone">
              <span v-if="currentWord?.cefr_level" class="badge px-2.5 py-1 rounded-pill fw-bold fs-9" style="background-color: rgba(94, 234, 212, 0.2); color: #5eead4; border: 1px solid rgba(94, 234, 212, 0.35);">
                {{ currentWord.cefr_level }}
              </span>
              <span 
                class="badge px-3 py-1.5 rounded-pill fw-bold fs-9 shadow-xs"
                :class="isCurrentMastered ? 'bg-success text-white' : 'text-white'"
                :style="!isCurrentMastered ? 'background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.25);' : ''"
              >
                {{ isCurrentMastered ? '✔ ĐÃ GHI NHỚ' : currentWord?.status === 'learning' ? 'CẦN ÔN' : 'CHƯA HỌC' }}
              </span>
            </div>
          </div>

          <!-- Main Term & Phonetics Body -->
          <div class="text-center my-auto py-2 position-relative z-1">
            <h1 class="display-5 display-md-4 fw-black text-white mb-2 tracking-tight" style="letter-spacing: -0.5px; word-break: break-word;">
              {{ currentWordText }}
            </h1>

            <!-- Phonetics & Pronunciation Audio -->
            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-2 interactive-zone">
              <span v-if="currentWordPhonetic" class="fs-6 fst-italic" style="color: #a7f3d0;">
                {{ currentWordPhonetic }}
              </span>
              <span v-if="currentWordPartOfSpeech" class="badge px-3 py-1 rounded-pill text-uppercase fs-8 fw-bold" style="background-color: rgba(94, 234, 212, 0.2); color: #5eead4; border: 1px solid rgba(94, 234, 212, 0.35);">
                {{ currentWordPartOfSpeech }}
              </span>
              <button
                type="button"
                @click.stop="speakCurrent"
                class="btn btn-sm text-white rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center gap-1.5 shadow-xs border"
                style="background-color: #032b24; border-color: rgba(255,255,255,0.3) !important;"
                title="Nghe phát âm chuẩn US"
              >
                <i class="bi bi-volume-up-fill text-warning"></i>
                <span>NGHE US</span>
              </button>
            </div>

            <!-- English Example Sentence Shown on Front -->
            <div
              v-if="currentWordExampleEn"
              class="interactive-zone p-3 rounded-3 text-start my-2 shadow-xs"
              style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.16); backdrop-filter: blur(4px);"
              @click.stop
            >
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge px-2.5 py-0.5 rounded-pill fs-9 fw-bold text-uppercase" style="background-color: rgba(94, 234, 212, 0.25); color: #5eead4;">
                  CÂU VÍ DỤ SONG NGỮ
                </span>
                <button
                  @click.stop="speech.speak(currentWordExampleEn)"
                  class="btn btn-sm text-white py-0 px-2 rounded-pill fs-8 border border-white-50"
                  style="background-color: rgba(0,0,0,0.3);"
                  title="Nghe câu ví dụ"
                >
                  <i class="bi bi-volume-up-fill text-warning"></i> Nghe câu
                </button>
              </div>
              <p class="fs-6 fw-semibold mb-0 text-white" v-html="highlightWord(currentWordExampleEn, currentWordText)"></p>
            </div>

            <!-- Speech practice transcript feedback (if user spoke) -->
            <div 
              v-if="speech.transcript.value || speech.isListening.value" 
              class="interactive-zone mt-2 px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-2 shadow-sm"
              style="background-color: rgba(0, 0, 0, 0.65); border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(8px);"
              @click.stop
            >
              <i class="bi" :class="speech.isListening.value ? 'bi-mic-fill text-danger animate-mic-pulse' : 'bi-check-circle-fill text-warning'"></i>
              <span v-if="speech.isListening.value" class="fw-bold fs-8 text-warning">
                Đang lắng nghe phát âm của bạn... Hãy nói to rõ!
              </span>
              <span v-else class="fw-bold fs-8 text-white">
                Bạn đọc: <strong class="text-warning font-monospace">"{{ speech.transcript.value }}"</strong> ({{ speech.pronunciationScore.value }}%
                <span :class="speech.pronunciationFeedback.value === 'excellent' ? 'text-success fw-bold' : speech.pronunciationFeedback.value === 'good' ? 'text-warning fw-bold' : 'text-danger fw-bold'">
                  {{ speech.pronunciationFeedback.value === 'excellent' ? '• Chuẩn xác! 🎉' : speech.pronunciationFeedback.value === 'good' ? '• Tốt 👍' : '• Thử lại' }}
                </span>)
              </span>
            </div>
          </div>

          <!-- Bottom Visual Indicator for Flipping -->
          <div class="d-flex align-items-center justify-content-between pt-2 border-top text-white-50 fs-8 position-relative z-1" style="border-top-color: rgba(255,255,255,0.15) !important;">
            <span><i class="bi bi-arrow-repeat text-warning me-1"></i>Chạm thẻ để lật xem nghĩa tiếng Việt & gõ lại</span>
            <span class="d-none d-md-inline text-muted fs-9">Nhấn <strong>[Space]</strong> để lật nhanh</span>
          </div>

          <!-- Decorative SVG Wave Background -->
          <div class="position-absolute end-0 bottom-0 pointer-events-none" style="width: 240px; height: 180px; opacity: 0.85;">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="#00b074" d="M47.5,-57.2C59.7,-48.1,66.8,-32.1,68.9,-15.8C71,0.5,68,17.2,59.9,30.8C51.8,44.4,38.6,55,23.6,61.4C8.6,67.8,-8.2,70,-23.7,65.3C-39.2,60.6,-53.4,49,-62.4,34.2C-71.4,19.4,-75.2,1.3,-71.4,-14.8C-67.7,-30.9,-56.3,-45,-42.6,-53.8C-28.9,-62.6,-14.5,-66.1,1.1,-67.4C16.6,-68.7,35.2,-66.3,47.5,-57.2Z" transform="translate(100 100)" />
            </svg>
          </div>
        </div>

        <!-- ================= BACK FACE (Mặt Sau: Nghĩa Tiếng Việt, Cấu Trúc/Mẫu Câu, Ô GÕ LẠI TỪ & NÚT XEM KẾT QUẢ) ================= -->
        <div
          class="card-face card-face-back position-absolute top-0 start-0 w-100 h-100 rounded-4 p-3.5 p-md-5 d-flex flex-column justify-content-between text-dark border-0 shadow-lg"
          style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);"
        >
          <!-- Top Bar Mặt Sau: Có nhãn & Nút Lật Về Mặt Trước -->
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="badge bg-success-subtle text-success fw-bold fs-8 px-3 py-1.5 rounded-pill border border-success-subtle shadow-2xs">
              MẶT SAU • NGHĨA & BẢN DỊCH
            </span>
            <button
              type="button"
              @click.stop="toggleFlip"
              class="interactive-zone btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1.5 shadow-2xs transition-all hover-shadow"
              title="Lật thẻ về mặt trước"
            >
              <i class="bi bi-arrow-return-left"></i>
              <span>Lật về mặt trước</span>
            </button>
          </div>

          <!-- Meaning & Vietnamese Translation Section -->
          <div class="my-auto py-1 text-center">
            <div class="text-muted fs-8 mb-1 fw-semibold text-uppercase">Dịch nghĩa tiếng Việt:</div>
            <h2 class="display-6 fw-bold text-success mb-2" style="word-break: break-word;">
              {{ currentWordMeaning }}
            </h2>

            <!-- Grammar Pattern / Structure (if present in curriculum data) -->
            <div v-if="currentWord?.pattern" class="p-2 rounded-3 bg-light text-muted fs-8 mb-2 border text-start interactive-zone" @click.stop>
              <i class="bi bi-diagram-2 me-1 text-teal"></i>
              <strong>Mẫu câu / Cấu trúc:</strong> <span class="text-dark-emphasis font-monospace">{{ currentWord.pattern }}</span>
            </div>

            <!-- Vietnamese translation / Examples list -->
            <div v-if="currentWord?.example_vi" class="bg-light p-2.5 rounded-3 text-start border my-1 interactive-zone" @click.stop>
              <span class="badge bg-secondary text-white fs-9 mb-1">BẢN DỊCH CÂU VÍ DỤ</span>
              <p class="fs-7 text-dark-emphasis mb-0 fst-italic">
                "{{ currentWord.example_vi }}"
              </p>
            </div>

            <!-- Multiple Curriculum Examples (if split by |) -->
            <div v-if="curriculumExamplesList.length > 0" class="bg-light p-2.5 rounded-3 text-start border my-1 interactive-zone" @click.stop>
              <span class="badge bg-secondary text-white fs-9 mb-1">CÂU VÍ DỤ THỰC TẾ ({{ curriculumExamplesList.length }})</span>
              <ul class="list-unstyled mb-0 fs-8">
                <li v-for="(ex, exIdx) in curriculumExamplesList" :key="exIdx" class="text-dark-emphasis mb-1">
                  • <em>{{ ex }}</em>
                </li>
              </ul>
            </div>

            <!-- ================= TÍNH NĂNG ĐỒNG BỘ: GÕ LẠI TỪ TIẾNG ANH & NÚT XEM KẾT QUẢ ================= -->
            <div
              v-if="showTypingInput"
              class="interactive-zone no-flip p-3 rounded-4 bg-light-subtle border border-success-subtle shadow-xs my-2 text-start position-relative z-3"
              @click.stop
            >
              <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <label class="fs-8 fw-bold text-teal d-flex align-items-center gap-1.5 mb-0">
                  <i class="bi bi-pencil-square text-teal"></i>
                  <span>Luyện gõ từ tiếng Anh (Active Recall):</span>
                </label>

                <!-- NÚT XEM KẾT QUẢ: Bo góc mềm mại, hiển thị rõ nét, bấm là HIỆN NGAY KẾT QUẢ KHÔNG LẬT THẺ, KHÔNG BẮT BUỘC PHẢI NHẬP TRƯỚC -->
                <button
                  type="button"
                  @click.stop="revealResultNow"
                  class="btn btn-sm rounded-pill px-3 py-1 fs-8 fw-bold d-inline-flex align-items-center gap-1.5 shadow-xs transition-all border border-teal text-teal bg-white hover-bg-teal"
                  style="border-width: 1.5px !important; z-index: 25;"
                  title="Hiện ngay kết quả và điền từ tiếng Anh chuẩn xác (không bị lật thẻ)"
                >
                  <i class="bi bi-eye-fill text-teal"></i>
                  <span>Xem kết quả</span>
                </button>
              </div>

              <!-- Input Group with Stop Propagation on Keydown/Space -->
              <div class="input-group input-group-sm">
                <input
                  ref="recallInputRef"
                  type="text"
                  v-model="typedRecallWord"
                  @input="checkSpellRecall"
                  @keyup.enter.stop="submitSpellRecall"
                  @keydown.space.stop
                  @keydown.stop
                  @click.stop
                  class="form-control rounded-pill-start py-2 px-3 border spell-recall-input"
                  :class="{
                    'border-success bg-success-subtle text-success fw-bold': isSpellCorrect === true,
                    'border-danger bg-danger-subtle': isSpellCorrect === false
                  }"
                  placeholder="Gõ lại từ tiếng Anh của mặt trước..."
                  autocomplete="off"
                  autocorrect="off"
                  autocapitalize="off"
                  spellcheck="false"
                />
                <button
                  type="button"
                  @click.stop="submitSpellRecall"
                  class="btn btn-sm btn-teal text-white rounded-pill-end px-3.5 fw-bold d-flex align-items-center gap-1 shadow-2xs"
                >
                  <i class="bi bi-check2-circle"></i>
                  <span>Kiểm tra</span>
                </button>
              </div>

              <!-- Result Banner: Correct -->
              <div v-if="isSpellCorrect === true" class="mt-2 text-success fs-8 fw-bold d-flex align-items-center gap-1.5 p-2 rounded-3 bg-success-subtle border border-success-subtle">
                <i class="bi bi-check-circle-fill fs-6"></i>
                <span>Chính xác tuyệt đối! Đáp án đúng: <strong class="font-monospace">{{ currentWordText }}</strong></span>
              </div>

              <!-- Result Banner: Incorrect -->
              <div v-else-if="isSpellCorrect === false" class="mt-2 text-danger fs-8 fw-semibold d-flex align-items-center justify-content-between gap-1.5 p-2 rounded-3 bg-danger-subtle border border-danger-subtle flex-wrap">
                <div class="d-flex align-items-center gap-1.5">
                  <i class="bi bi-x-circle-fill fs-6"></i>
                  <span>Chưa chính xác! Thử gõ lại hoặc bấm <strong>"Xem kết quả"</strong>.</span>
                </div>
                <button type="button" @click.stop="revealResultNow" class="btn btn-xs btn-outline-danger rounded-pill px-2.5 py-0.5 fs-9 fw-bold">
                  Hiện đáp án
                </button>
              </div>

              <!-- Answer Reveal Banner (When 'Xem kết quả' is pressed) -->
              <div v-if="showAnswer" class="mt-2 p-2.5 rounded-3 bg-teal-subtle text-teal border border-teal d-flex justify-content-between align-items-center shadow-2xs flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-teal text-white fs-9 rounded-pill">ĐÁP ÁN CHUẨN</span>
                  <strong class="fs-6 text-teal font-monospace">{{ currentWordText }}</strong>
                  <span v-if="currentWordPhonetic" class="text-muted fs-8 font-monospace">({{ currentWordPhonetic }})</span>
                </div>
                <button
                  type="button"
                  @click.stop="speakCurrent"
                  class="btn btn-xs btn-teal text-white rounded-pill px-2.5 py-1 fs-9 fw-bold d-inline-flex align-items-center gap-1"
                >
                  <i class="bi bi-volume-up-fill"></i> Phát âm
                </button>
              </div>
            </div>
          </div>

          <!-- Bottom hint to flip back: Click anywhere on whitespace to flip -->
          <div class="text-center pt-2 border-top text-muted fs-8">
            <i class="bi bi-arrow-repeat me-1"></i>Chạm vào bất kỳ vùng trống nào của thẻ để lật lại mặt trước
          </div>
        </div>
      </div>
    </div>

    <!-- 3. MOBILE-ONLY 2-ROW CONTROL KEYPAD (Thiết kế công thái học vừa khít 100% màn hình điện thoại) -->
    <div class="d-flex d-md-none flex-column gap-1.5 w-100">
      <!-- Row 1: Điều hướng trước / lật thẻ / tiếp theo -->
      <div class="d-flex align-items-center gap-1.5 w-100">
        <button
          @click="prevCard"
          :disabled="currentIndex === 0"
          class="btn btn-outline-secondary btn-sm flex-fill py-2 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8 border"
        >
          <i class="bi bi-chevron-left"></i>
          <span>Trước</span>
        </button>

        <button
          @click="toggleFlip"
          class="btn btn-teal btn-sm flex-fill py-2 rounded-3 shadow-2xs text-white d-flex align-items-center justify-content-center gap-1.5 fs-8 fw-bold"
          style="flex: 1.8;"
        >
          <i class="bi bi-arrow-repeat fs-7"></i>
          <span>Lật thẻ</span>
        </button>

        <button
          @click="nextCard"
          :disabled="currentIndex === words.length - 1"
          class="btn btn-outline-secondary btn-sm flex-fill py-2 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8 border"
        >
          <span>Tiếp</span>
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>

      <!-- Row 2: Nghe, Nói Micro, Thuộc từ, Cần ôn -->
      <div class="d-flex align-items-center gap-1.5 w-100">
        <button
          @click="speakCurrent"
          class="btn btn-outline-primary btn-sm flex-fill py-1.5 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8 fw-bold border"
          title="Nghe phát âm chuẩn US"
        >
          <i class="bi bi-volume-up-fill"></i>
          <span>Nghe</span>
        </button>

        <button
          @click="recordCurrent"
          class="btn btn-sm flex-fill py-1.5 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8 fw-bold border"
          :class="speech.isListening.value ? 'btn-danger animate-mic-pulse text-white' : 'btn-outline-info'"
          title="Luyện nói Micro"
        >
          <i class="bi" :class="speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
          <span>{{ speech.isListening.value ? 'Đang nghe...' : 'Nói' }}</span>
        </button>

        <button
          @click="markCurrentMastered"
          class="btn btn-outline-success btn-sm flex-fill py-1.5 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8 fw-bold border"
          title="Đánh dấu đã nhớ từ này"
        >
          <i class="bi bi-check-circle-fill"></i>
          <span>Đã nhớ</span>
        </button>

        <button
          @click="markCurrentLearning"
          class="btn btn-outline-warning btn-sm flex-fill py-1.5 rounded-3 shadow-2xs d-flex align-items-center justify-content-center gap-1 fs-8 fw-bold border"
          title="Cần ôn lại từ này"
        >
          <i class="bi bi-arrow-clockwise"></i>
          <span>Cần ôn</span>
        </button>
      </div>
    </div>

    <!-- 4. DESKTOP-ONLY SINGLE-ROW CONTROL BAR (>= 768px, giữ nguyên 100% phím tắt và thiết kế PC / Laptop) -->
    <div class="d-none d-md-flex align-items-center justify-content-center gap-1.5 flex-nowrap py-1 w-100 flashcard-controls-row">
      <!-- [← / Q] Prev -->
      <button
        @click="prevCard"
        :disabled="currentIndex === 0"
        class="btn btn-outline-secondary btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 border"
        title="Lùi thẻ (Phím ← hoặc Q)"
      >
        <i class="bi bi-chevron-left"></i>
        <span>[Q] Trước</span>
      </button>

      <!-- [Space] Flip -->
      <button
        @click="toggleFlip"
        class="btn btn-teal btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold text-white border"
        title="Lật thẻ (Phím Space)"
      >
        <i class="bi bi-arrow-repeat"></i>
        <span>[Space] Lật thẻ</span>
      </button>

      <!-- [A] Listen -->
      <button
        @click="speakCurrent"
        class="btn btn-primary btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold border"
        title="Nghe phát âm chuẩn US (Phím A)"
      >
        <i class="bi bi-volume-up-fill"></i>
        <span>[A] Nghe US</span>
      </button>

      <!-- [D] Speak -->
      <button
        @click="recordCurrent"
        class="btn btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold border"
        :class="speech.isListening.value ? 'btn-danger animate-mic-pulse text-white' : 'btn-info text-white'"
        title="Luyện nói Micro (Phím D)"
      >
        <i class="bi" :class="speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
        <span>[D] {{ speech.isListening.value ? 'Đang nghe...' : 'Luyện nói' }}</span>
      </button>

      <!-- [S] Mastered -->
      <button
        @click="markCurrentMastered"
        class="btn btn-success btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold border"
        title="Đã nhớ từ này (Phím S)"
      >
        <i class="bi bi-check-circle-fill"></i>
        <span>[S] Đã nhớ</span>
      </button>

      <!-- [X] Need Review -->
      <button
        @click="markCurrentLearning"
        class="btn btn-warning btn-sm text-dark px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 fw-bold border"
        title="Cần ôn lại từ này (Phím X)"
      >
        <i class="bi bi-arrow-clockwise"></i>
        <span>[X] Cần ôn</span>
      </button>

      <!-- [→ / E] Next -->
      <button
        @click="nextCard"
        :disabled="currentIndex === words.length - 1"
        class="btn btn-outline-secondary btn-sm px-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8 border"
        title="Tiếp thẻ (Phím → hoặc E)"
      >
        <span>[E] Tiếp</span>
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useSpeech } from '@/composables/useSpeech'
import { useAuthStore } from '@/stores/authStore'
import { useReviewStore } from '@/stores/reviewStore'
import { useToast } from '@/composables/useToast'

const props = defineProps({
  words: {
    type: Array,
    required: true,
    default: () => [],
  },
  initialIndex: {
    type: Number,
    default: 0,
  },
  categoryTitle: {
    type: String,
    default: '',
  },
  showTypingInput: {
    type: Boolean,
    default: true,
  }
})

const emit = defineEmits(['status-updated', 'update-status', 'index-changed'])

const speech = useSpeech()
const authStore = useAuthStore()
const toast = useToast()

const currentIndex = ref(props.initialIndex || 0)
const isFlipped = ref(false)
const recallInputRef = ref(null)

// Active recall spelling states
const typedRecallWord = ref('')
const showAnswer = ref(false)
const isSpellCorrect = ref(null) // null | true | false

const currentWord = computed(() => {
  if (!props.words || props.words.length === 0) return null
  return props.words[currentIndex.value] || null
})

// Unified word fields extractor supporting all variations across the app
const currentWordText = computed(() => {
  if (!currentWord.value) return ''
  return currentWord.value.word || currentWord.value.term || currentWord.value.phrase || ''
})

const currentWordPhonetic = computed(() => {
  if (!currentWord.value) return ''
  return currentWord.value.phonetic_us || currentWord.value.phonetic || currentWord.value.ipa || ''
})

const currentWordPartOfSpeech = computed(() => {
  if (!currentWord.value) return ''
  return currentWord.value.part_of_speech || currentWord.value.pos || currentWord.value.type || ''
})

const currentWordMeaning = computed(() => {
  if (!currentWord.value) return ''
  return currentWord.value.meaning_vi || currentWord.value.definition || currentWord.value.meaning || ''
})

const currentWordExampleEn = computed(() => {
  if (!currentWord.value) return ''
  if (currentWord.value.example_en) return currentWord.value.example_en
  if (currentWord.value.examples) {
    return Array.isArray(currentWord.value.examples) ? currentWord.value.examples[0] : currentWord.value.examples.split('|')[0]
  }
  return ''
})

const curriculumExamplesList = computed(() => {
  if (!currentWord.value || !currentWord.value.examples) return []
  if (Array.isArray(currentWord.value.examples)) return currentWord.value.examples
  const parts = currentWord.value.examples.split('|').map(s => s.trim()).filter(Boolean)
  return parts.length > 1 ? parts : []
})

const currentCategoryLabel = computed(() => {
  if (props.categoryTitle) return props.categoryTitle
  if (currentWord.value?.category_label) return currentWord.value.category_label
  if (currentWord.value?.topic_title) return `CHỦ ĐỀ • ${currentWord.value.topic_title.toUpperCase()}`
  return 'COLLOCATION • IELTS & OXFORD 3000™'
})

const isCurrentMastered = computed(() => {
  return currentWord.value?.status === 'mastered' || currentWord.value?.is_remembered === true
})

const resetRecallState = () => {
  typedRecallWord.value = ''
  showAnswer.value = false
  isSpellCorrect.value = null
}

const toggleFlip = () => {
  isFlipped.value = !isFlipped.value
  if (!isFlipped.value) {
    resetRecallState()
  }
}

// Handler ensuring clicking any non-interactive part of the card flips it smoothly
const handleCardClick = (e) => {
  // If clicked inside an input, button, textarea, link or interactive container, do NOT flip
  const interactiveEl = e.target.closest('input, button, textarea, select, a, .interactive-zone, .no-flip')
  if (interactiveEl) {
    return
  }
  toggleFlip()
}

const clean = (s) => (s || '').toLowerCase().trim().replace(/[.,?!:;"'_\-()\/\\\[\]]/g, '')

const isWordMatching = (input, target) => {
  if (!input || !target) return false
  const normInput = clean(input)
  const normTarget = clean(target)
  if (normInput === normTarget) return true
  // Allow variants separated by comma or slash e.g. "a, an" or "travel/trip"
  const parts = target.split(/[,/|]/).map(clean).filter(Boolean)
  if (parts.includes(normInput)) return true
  return false
}

const checkSpellRecall = () => {
  const target = currentWordText.value
  if (!target || !typedRecallWord.value) {
    isSpellCorrect.value = null
    return
  }
  if (isWordMatching(typedRecallWord.value, target)) {
    isSpellCorrect.value = true
  } else {
    isSpellCorrect.value = null
  }
}

const submitSpellRecall = (e) => {
  if (e) {
    e.stopPropagation()
    e.preventDefault()
  }
  const target = currentWordText.value
  if (!target) return
  if (isWordMatching(typedRecallWord.value, target)) {
    isSpellCorrect.value = true
    speech.speak(target)
    toast.success(`Chính xác: "${target}" (+10 XP)`, 'Phản xạ chuẩn xác')
  } else {
    isSpellCorrect.value = false
    toast.error(`Chưa đúng: "${typedRecallWord.value || '(để trống)'}". Thử lại hoặc bấm "Xem kết quả".`, 'Kiểm tra lại')
  }
}

// BẤM XEM KẾT QUẢ: Hiển thị ngay kết quả, điền từ chuẩn xác vào ô mà TUYỆT ĐỐI KHÔNG BỊ LẬT THẺ, KHÔNG CẦN NHẬP TRƯỚC
const revealResultNow = (e) => {
  if (e) {
    e.stopPropagation()
    e.preventDefault()
  }
  const target = currentWordText.value
  if (target) {
    typedRecallWord.value = target
    showAnswer.value = true
    isSpellCorrect.value = true
    speech.speak(target)
    toast.info(`Đáp án đúng là: "${target}"`, 'Kết quả từ vựng')
  }
}

const nextCard = () => {
  if (currentIndex.value < props.words.length - 1) {
    currentIndex.value++
    isFlipped.value = false
    resetRecallState()
    emit('index-changed', currentIndex.value)
  }
}

const prevCard = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
    isFlipped.value = false
    resetRecallState()
    emit('index-changed', currentIndex.value)
  }
}

const speakCurrent = () => {
  const text = currentWordText.value
  if (text) {
    speech.speak(text)
  }
}

const recordCurrent = () => {
  const text = currentWordText.value
  if (text) {
    if (speech.isListening.value) {
      speech.stopListening()
      return
    }
    speech.startListening(text, 'player_' + (currentWord.value?.id || text))
  }
}

const markCurrentMastered = () => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để lưu tiến độ "Đã nhớ" vào tài khoản của bạn.')
    return
  }
  if (currentWord.value) {
    const wordText = currentWordText.value || 'từ vựng'
    currentWord.value.status = 'mastered'
    if ('is_remembered' in currentWord.value) {
      currentWord.value.is_remembered = true
    }
    toast.mastered(`Tuyệt vời! Đã thuộc: "${wordText}"`, 'Đã nhớ (+5 XP)')
    emit('status-updated', { wordId: currentWord.value.id, status: 'mastered', word: currentWord.value })
    emit('update-status', { wordId: currentWord.value.id, status: 'mastered', word: currentWord.value })
    if (currentIndex.value < props.words.length - 1) {
      nextCard()
    }
  }
}

const markCurrentLearning = () => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để lưu tiến độ "Cần ôn" vào tài khoản của bạn.')
    return
  }
  if (currentWord.value) {
    const wordText = currentWordText.value || 'từ vựng'
    currentWord.value.status = 'learning'
    if ('is_remembered' in currentWord.value) {
      currentWord.value.is_remembered = false
    }
    try {
      const reviewStore = useReviewStore()
      reviewStore.addWordToReview(currentWord.value)
    } catch (e) {
      console.warn('Failed to sync to reviewStore:', e)
    }
    toast.review(`Đã đưa "${wordText}" vào danh sách cần ôn tập.`, 'Cần ôn tập')
    emit('status-updated', { wordId: currentWord.value.id, status: 'learning', word: currentWord.value })
    emit('update-status', { wordId: currentWord.value.id, status: 'learning', word: currentWord.value })
    if (currentIndex.value < props.words.length - 1) {
      nextCard()
    }
  }
}

const highlightWord = (sentence, word) => {
  if (!sentence || !word) return sentence || ''
  const escaped = word.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
  const regex = new RegExp(`(${escaped})`, 'gi')
  return sentence.replace(regex, '<span class="text-decoration-underline fw-bold text-success">$1</span>')
}

// Global Keyboard Shortcuts
const handleKeyDown = (e) => {
  const tag = e.target.tagName.toLowerCase()
  // When focus is inside an input, textarea or select, NEVER trigger shortcuts!
  if (tag === 'input' || tag === 'textarea' || tag === 'select') return
  if (document.querySelector('.modal.show')) return

  if (e.code === 'Space') {
    e.preventDefault()
    toggleFlip()
  } else if (e.key === 'ArrowLeft' || e.key === 'q' || e.key === 'Q') {
    e.preventDefault()
    prevCard()
  } else if (e.key === 'ArrowRight' || e.key === 'e' || e.key === 'E') {
    e.preventDefault()
    nextCard()
  } else if (e.key === 'a' || e.key === 'A') {
    e.preventDefault()
    speakCurrent()
  } else if (e.key === 'd' || e.key === 'D') {
    e.preventDefault()
    recordCurrent()
  } else if (e.key === 's' || e.key === 'S') {
    e.preventDefault()
    markCurrentMastered()
  } else if (e.key === 'x' || e.key === 'X') {
    e.preventDefault()
    markCurrentLearning()
  }
}

watch(() => props.words, () => {
  currentIndex.value = props.initialIndex || 0
  isFlipped.value = false
  resetRecallState()
})

watch(() => props.initialIndex, (newIdx) => {
  if (newIdx !== undefined && newIdx !== currentIndex.value) {
    currentIndex.value = newIdx
    isFlipped.value = false
    resetRecallState()
  }
})

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
  speech.stopSpeech()
  speech.stopListening()
})
</script>

<style scoped>
/* 3D SCENE & PERSPECTIVE */
.perspective-1000 {
  perspective: 1000px;
}

.card-scene {
  cursor: pointer;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.flashcard-3d-box {
  min-height: 440px;
  position: relative;
}

.flashcard-3d-inner {
  min-height: 440px;
  transform-style: preserve-3d;
  -webkit-transform-style: preserve-3d;
  transition: transform 0.5s cubic-bezier(0.4, 0.2, 0.2, 1), box-shadow 0.3s ease;
  will-change: transform;
  border-radius: 1.25rem;
}

/* DESKTOP HOVER GLOW & ELEVATION EFFECT (Báo hiệu trực quan khi rê chuột vào) */
@media (hover: hover) and (pointer: fine) {
  .card-scene:hover .flashcard-3d-inner:not(.rotate-y-180) {
    transform: translateY(-5px);
    box-shadow: 0 20px 45px -8px rgba(0, 176, 116, 0.42), 0 0 0 2px rgba(94, 234, 212, 0.6) !important;
  }
  .card-scene:hover .flashcard-3d-inner.rotate-y-180 {
    transform: translateY(-5px) rotateY(180deg);
    box-shadow: 0 20px 45px -8px rgba(16, 185, 129, 0.35), 0 0 0 2px rgba(16, 185, 129, 0.45) !important;
  }
}

/* MOBILE TAP FEEDBACK (Không trễ 300ms) */
.card-scene:active .flashcard-3d-inner:not(.rotate-y-180) {
  transform: scale(0.992);
}
.card-scene:active .flashcard-3d-inner.rotate-y-180 {
  transform: scale(0.992) rotateY(180deg);
}

/* CARD FACES WITH SAFE HARDWARE ACCELERATION */
.card-face {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  transform: translateZ(0);
  border-radius: 1.25rem;
  overflow: hidden;
}

.card-face-front {
  z-index: 2;
}

.card-face-back {
  transform: rotateY(180deg) translateZ(0);
  z-index: 1;
}

.rotate-y-180 {
  transform: rotateY(180deg);
}

/* ISOLATED INTERACTIVE ZONES & INPUT */
.interactive-zone,
.interactive-zone * {
  cursor: default;
}
.interactive-zone input {
  cursor: text !important;
}
.interactive-zone button,
.interactive-zone a {
  cursor: pointer !important;
}

.spell-recall-input {
  font-size: 16px !important; /* Critical for iOS Safari auto-zoom prevention */
  cursor: text !important;
  pointer-events: auto !important;
}

.hover-bg-teal:hover {
  background-color: #00b074 !important;
  color: #ffffff !important;
  border-color: #00b074 !important;
}
.hover-bg-teal:hover i {
  color: #ffffff !important;
}

@media (max-width: 767.98px) {
  .flashcard-3d-box,
  .flashcard-3d-inner,
  .card-face {
    min-height: 400px !important;
  }
}
</style>
