<template>
  <div class="review-page min-vh-100 pb-5">
    <div class="container-fluid px-3 px-md-4 px-xl-5 pt-3 pt-md-4 max-w-7xl mx-auto">
      <!-- 1. TOP HEADER -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3 mb-md-4">
        <div>
          <h1 class="display-6 fw-black text-dark-emphasis mb-1 tracking-tight">
            Bộ thẻ ôn tập
          </h1>
          <p class="text-muted fs-7 mb-0">
            {{ reviewStore.dueCardsCount }} thẻ tới hạn hôm nay theo thuật toán Lặp lại ngắt quãng (Spaced Repetition).
          </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
          <!-- Button Ôn ngay / Ôn bộ này -->
          <button
            @click="startReviewSession('flip')"
            :disabled="reviewStore.dueCardsCount === 0 && reviewStore.totalCardsCount === 0"
            class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-8 d-inline-flex align-items-center gap-2 shadow-sm"
          >
            <i class="bi bi-play-circle-fill"></i>
            <span>{{ reviewStore.activeFolder === 'all' ? 'Ôn ngay' : 'Ôn bộ này' }}</span>
            <span class="badge bg-white text-dark rounded-pill px-2 py-0.5 fs-9 fw-black">
              {{ reviewStore.activeFolder === 'all' ? reviewStore.dueCardsCount : activeFolderCardsCount }}
            </span>
          </button>

          <!-- Button + Tạo thẻ mới -->
          <button
            @click="openAddCardModal"
            class="btn btn-outline-secondary rounded-pill px-3.5 py-2 fw-semibold fs-8 d-inline-flex align-items-center gap-1 shadow-2xs"
            :class="authStore.themeMode === 'dark' ? 'border-secondary text-white' : 'border-light-subtle bg-white text-dark'"
          >
            <i class="bi bi-plus-lg"></i>
            <span>Tạo thẻ mới</span>
          </button>

          <!-- Button Về lộ trình -->
          <router-link
            to="/curriculum"
            class="btn btn-outline-secondary rounded-pill px-3.5 py-2 fw-semibold fs-8 d-inline-flex align-items-center gap-1 shadow-2xs text-decoration-none"
            :class="authStore.themeMode === 'dark' ? 'border-secondary text-white' : 'border-light-subtle bg-white text-dark'"
          >
            <span>Về lộ trình</span>
          </router-link>
        </div>
      </div>

      <!-- 2. SUMMARY PROGRESS CARD -->
      <div class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-3.5 mb-md-4">
        <div class="d-flex justify-content-between align-items-center mb-2.5">
          <div class="d-flex align-items-baseline gap-2">
            <span class="display-6 fw-black text-teal leading-none">
              {{ reviewStore.dueCardsCount }}
            </span>
            <span class="fs-7 fw-bold text-dark-emphasis">thẻ tới hạn cần ôn</span>
          </div>
          <div class="text-muted fs-8">
            Tổng {{ reviewStore.totalCardsCount }} thẻ trong kho
          </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress rounded-pill mb-3" style="height: 6px; background-color: rgba(0, 176, 116, 0.15);">
          <div
            class="progress-bar rounded-pill bg-teal"
            :style="{ width: `${progressPercent}%`, transition: 'width 0.4s ease' }"
          ></div>
        </div>

        <!-- Row: Thống kê & Chiều ôn & 4 chế độ luyện tập nhanh -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2.5 pt-1">
          <div class="d-flex align-items-center gap-3 fs-8 flex-wrap">
            <span class="d-inline-flex align-items-center gap-1.5 text-muted">
              <span class="rounded-circle bg-teal" style="width: 7px; height: 7px;"></span>
              <span>Đã thuộc <strong class="text-dark-emphasis">{{ reviewStore.masteredCardsCount }}</strong></span>
            </span>
            <span class="d-inline-flex align-items-center gap-1.5 text-muted">
              <span class="rounded-circle border border-teal" style="width: 7px; height: 7px;"></span>
              <span>Đang học <strong class="text-dark-emphasis">{{ reviewStore.learningCardsCount }}</strong></span>
            </span>
          </div>

          <!-- Quick Practice Modes Trigger -->
          <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <span class="fs-8 text-muted">Luyện tập nhanh:</span>
            <button @click="startReviewSession('flip')" class="btn btn-xs rounded-pill px-2.5 py-1 fs-9 fw-bold btn-outline-teal shadow-2xs">
              <i class="bi bi-card-heading me-1"></i> Lật thẻ 3D
            </button>
            <button @click="startReviewSession('typing')" class="btn btn-xs rounded-pill px-2.5 py-1 fs-9 fw-bold btn-outline-teal shadow-2xs">
              <i class="bi bi-keyboard me-1"></i> Gõ từ
            </button>
            <button @click="startReviewSession('quiz')" class="btn btn-xs rounded-pill px-2.5 py-1 fs-9 fw-bold btn-outline-teal shadow-2xs">
              <i class="bi bi-check2-circle me-1"></i> Trắc nghiệm
            </button>
            <button @click="startReviewSession('match')" class="btn btn-xs rounded-pill px-2.5 py-1 fs-9 fw-bold btn-outline-teal shadow-2xs">
              <i class="bi bi-puzzle me-1"></i> Ghép thẻ
            </button>
          </div>
        </div>
      </div>

      <!-- 3. MAIN 2-COLUMN WORKSPACE: LEFT FOLDERS + RIGHT TABLE -->
      <div class="row g-3 g-md-4">
        <!-- CỘT TRÁI: BỘ THẺ (FOLDERS) -->
        <div class="col-12 col-md-4 col-lg-3">
          <div class="card p-3 rounded-4 shadow-sm border bg-card-custom sticky-top" style="top: 80px;">
            <h6 class="fw-black text-dark-emphasis mb-3 fs-7">
              Thư mục bộ thẻ
            </h6>

            <div class="d-flex flex-column gap-1.5 mb-3">
              <!-- Tất cả -->
              <button
                @click="reviewStore.activeFolder = 'all'"
                class="btn btn-sm rounded-3 py-2 px-3 text-start d-flex align-items-center justify-content-between border-0 transition-all"
                :class="reviewStore.activeFolder === 'all' ? 'active-folder-btn' : 'hover-folder-btn'"
              >
                <span class="fw-bold fs-7">Tất cả</span>
                <span class="badge rounded-pill" :class="reviewStore.activeFolder === 'all' ? 'bg-white text-dark' : 'bg-body-secondary text-secondary'">
                  {{ reviewStore.totalCardsCount }}
                </span>
              </button>

              <!-- Đã đánh dấu (Starred) -->
              <button
                @click="reviewStore.activeFolder = 'starred'"
                class="btn btn-sm rounded-3 py-2 px-3 text-start d-flex align-items-center justify-content-between border-0 transition-all"
                :class="reviewStore.activeFolder === 'starred' ? 'active-folder-btn' : 'hover-folder-btn'"
              >
                <div class="d-flex align-items-center gap-1.5">
                  <i class="bi bi-star-fill text-warning fs-8"></i>
                  <span class="fw-bold fs-7">Đã đánh dấu</span>
                </div>
                <span class="badge rounded-pill" :class="reviewStore.activeFolder === 'starred' ? 'bg-white text-dark' : 'bg-body-secondary text-secondary'">
                  {{ reviewStore.starredCardsCount }}
                </span>
              </button>

              <!-- Dynamic Folders: Chưa xếp, Sổ tay, etc. -->
              <div
                v-for="f in reviewStore.folders"
                :key="f"
                class="d-flex align-items-center justify-content-between group-folder"
              >
                <button
                  @click="reviewStore.activeFolder = f"
                  class="btn btn-sm rounded-3 py-2 px-3 text-start d-flex align-items-center justify-content-between border-0 transition-all flex-grow-1"
                  :class="reviewStore.activeFolder === f ? 'active-folder-btn' : 'hover-folder-btn'"
                >
                  <span class="fw-bold fs-7 text-truncate">{{ f }}</span>
                  <span class="badge rounded-pill" :class="reviewStore.activeFolder === f ? 'bg-white text-dark' : 'bg-body-secondary text-secondary'">
                    {{ reviewStore.getFolderCount(f) }}
                  </span>
                </button>
              </div>
            </div>

            <!-- Nút tạo thư mục mới -->
            <div v-if="!isCreatingFolder">
              <button
                @click="openFolderInput"
                class="btn btn-sm btn-outline-teal w-100 rounded-3 py-1.5 fw-bold fs-8 d-flex align-items-center justify-content-center gap-1"
              >
                <i class="bi bi-folder-plus"></i>
                <span>Tạo thư mục mới</span>
              </button>
            </div>
            <div v-else class="input-group input-group-sm">
              <input
                ref="newFolderInput"
                v-model="newFolderName"
                @keyup.enter="handleCreateFolder"
                type="text"
                class="form-control rounded-start-3 fs-8"
                placeholder="Tên thư mục..."
              />
              <button @click="handleCreateFolder" class="btn btn-teal text-white fw-bold fs-8">Lưu</button>
              <button @click="isCreatingFolder = false" class="btn btn-outline-secondary fs-8">Hủy</button>
            </div>
          </div>
        </div>

        <!-- CỘT PHẢI: BẢNG DANH SÁCH THẺ (CHỐNG TRÀN VÀ ĐÈ CHỮ TRIỆT ĐỂ) -->
        <div class="col-12 col-md-8 col-lg-9">
          <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
              <div class="d-flex align-items-center gap-2">
                <h5 class="fw-black text-dark-emphasis mb-0 fs-6">
                  {{ folderDisplayTitle }}
                </h5>
                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill fs-8">
                  {{ reviewStore.filteredCards.length }} thẻ
                </span>
              </div>

              <!-- Search & Sort -->
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <input
                  v-model="reviewStore.searchKeyword"
                  type="text"
                  class="form-control form-control-sm fs-8 rounded-pill"
                  placeholder="Tìm từ, nghĩa..."
                  style="max-width: 170px;"
                />

                <select v-model="reviewStore.sortOption" class="form-select form-select-sm fs-8 rounded-pill" style="width: auto;">
                  <option value="due_first">Tới hạn trước</option>
                  <option value="newest">Mới nhất</option>
                  <option value="alphabet">Bảng chữ cái A-Z</option>
                </select>

                <button @click="openAddCardModal" class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8 d-inline-flex align-items-center gap-1 shadow-2xs">
                  <i class="bi bi-plus-lg"></i>
                  <span>Thêm thẻ</span>
                </button>
              </div>
            </div>

            <!-- TABLE OF CARDS (WITH WORD-BREAK & OVERFLOW WRAP TO PREVENT TEXT OVERLAP) -->
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" style="table-layout: auto;">
                <thead class="fs-8 text-muted border-bottom text-uppercase">
                  <tr>
                    <th style="width: 36px;">
                      <input type="checkbox" class="form-check-input" @change="toggleSelectAll" />
                    </th>
                    <th style="min-width: 160px; max-width: 220px;">Từ / Câu</th>
                    <th style="min-width: 240px; max-width: 380px;">Nghĩa tiếng Việt & Ví dụ</th>
                    <th style="min-width: 110px;">Thư mục</th>
                    <th style="min-width: 100px;">Trạng thái</th>
                    <th class="text-end" style="width: 90px;">Thao tác</th>
                  </tr>
                </thead>
                <tbody class="fs-7">
                  <tr v-for="c in reviewStore.filteredCards" :key="c.id" class="transition-all">
                    <td>
                      <input type="checkbox" :value="c.id" v-model="selectedCardIds" class="form-check-input" />
                    </td>

                    <!-- Term + Pronunciation -->
                    <td class="ox-text-break" style="max-width: 220px;">
                      <div class="d-flex align-items-start gap-2">
                        <button
                          @click="reviewStore.toggleStar(c.id)"
                          class="btn btn-link p-0 text-decoration-none border-0 mt-0.5"
                          title="Đánh dấu thẻ quan trọng"
                        >
                          <i class="bi fs-7" :class="c.starred ? 'bi-star-fill text-warning' : 'bi-star text-muted'"></i>
                        </button>
                        <div class="min-w-0">
                          <div class="d-flex align-items-center gap-1.5 flex-wrap">
                            <span class="fw-bold text-teal fs-6">{{ c.word }}</span>
                            <button @click="speech.speak(c.word)" class="btn btn-xs btn-link p-0 text-teal text-decoration-none" title="Nghe phát âm">
                              <i class="bi bi-volume-up-fill"></i>
                            </button>
                          </div>
                          <div class="text-muted fs-8 font-monospace text-truncate">
                            {{ c.phonetic_us || c.level || 'Từ vựng' }}
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- Meaning & Context (Strictly Word-Break to prevent overlap) -->
                    <td class="ox-text-break" style="max-width: 380px;">
                      <div class="text-dark-emphasis fw-medium fs-8 leading-relaxed">
                        {{ c.meaning_vi }}
                      </div>
                      <div v-if="c.example_en" class="text-muted fs-9 fst-italic mt-1 leading-normal">
                        "{{ c.example_en }}"
                      </div>
                    </td>

                    <!-- Folder Dropdown -->
                    <td>
                      <select
                        :value="c.folder || 'Chưa xếp'"
                        @change="reviewStore.setCardFolder(c.id, $event.target.value)"
                        class="form-select form-select-sm fs-9 rounded-pill py-0.5 px-2 border-0 bg-body-tertiary"
                        style="width: auto;"
                      >
                        <option v-for="f in reviewStore.folders" :key="f" :value="f">{{ f }}</option>
                      </select>
                    </td>

                    <!-- Schedule status badge -->
                    <td>
                      <span
                        class="badge rounded-pill px-2.5 py-1 fs-9 fw-bold"
                        :class="c.status === 'due' ? 'bg-teal-subtle text-teal border border-teal-subtle' : (c.status === 'mastered' ? 'bg-success-subtle text-success' : 'bg-body-secondary text-secondary')"
                      >
                        {{ c.status === 'due' ? 'tới hạn' : (c.status === 'mastered' ? 'đã thuộc' : 'đang học') }}
                      </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-end">
                      <div class="d-inline-flex gap-2 fs-8">
                        <button @click="openEditCardModal(c)" class="btn btn-link p-0 text-secondary text-decoration-none hover-text-teal fw-semibold">
                          Sửa
                        </button>
                        <button @click="reviewStore.removeCard(c.id)" class="btn btn-link p-0 text-danger text-decoration-none fw-semibold">
                          Bỏ
                        </button>
                      </div>
                    </td>
                  </tr>

                  <tr v-if="reviewStore.filteredCards.length === 0">
                    <td colspan="6" class="text-center py-5 text-muted">
                      <i class="bi bi-journal-x fs-1 d-block mb-2 text-muted"></i>
                      <span>Chưa có thẻ nào trong bộ này. Nhấn <strong>"+ Thêm thẻ"</strong> để bắt đầu ôn tập!</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. MODAL: THÊM / CHỈNH SỬA THẺ -->
    <div v-if="isCardModalOpen" class="modal show d-block" style="background: rgba(0,0,0,0.65); z-index: 1085;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-2xl bg-card-custom">
          <div class="modal-header border-bottom">
            <h5 class="modal-title fw-black text-teal">
              {{ editingCardId ? 'Chỉnh Sửa Thẻ Ôn Tập' : 'Tạo Thẻ Ôn Tập Mới' }}
            </h5>
            <button @click="isCardModalOpen = false" class="btn-close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fs-8 fw-bold">Từ hoặc Cụm từ tiếng Anh (*):</label>
              <input v-model="cardForm.word" type="text" class="form-control rounded-3" placeholder="e.g. aberration, resilient..." />
            </div>
            <div class="mb-3">
              <label class="form-label fs-8 fw-bold">Nghĩa tiếng Việt (*):</label>
              <textarea v-model="cardForm.meaning_vi" class="form-control rounded-3" rows="2" placeholder="Giải nghĩa tiếng Việt rõ ràng..."></textarea>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label fs-8 fw-bold">Thư mục xếp vào:</label>
                <select v-model="cardForm.folder" class="form-select rounded-3 fs-8">
                  <option v-for="f in reviewStore.folders" :key="f" :value="f">{{ f }}</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label fs-8 fw-bold">Phân loại / Cấp độ:</label>
                <input v-model="cardForm.level" type="text" class="form-control rounded-3 fs-8" placeholder="e.g. C2, IELTS, Oxford" />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fs-8 fw-bold">Câu ví dụ (tùy chọn):</label>
              <input v-model="cardForm.example_en" type="text" class="form-control rounded-3 fs-8" placeholder="e.g. In a moment of aberration..." />
            </div>
          </div>
          <div class="modal-footer border-top">
            <button @click="isCardModalOpen = false" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 fs-8">Hủy</button>
            <button @click="handleSaveCard" class="btn btn-teal text-white rounded-pill px-4 py-1.5 fw-bold fs-8 shadow-sm">
              {{ editingCardId ? 'Lưu cập nhật' : 'Tạo thẻ ngay' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 5. INTERACTIVE MULTI-MODE REVIEW SESSION MODAL (4 CHẾ ĐỘ LUYỆN TẬP CHUYÊN SÂU) -->
    <div v-if="isReviewingSession" class="modal show d-block" style="background: rgba(0,0,0,0.85); z-index: 1090;">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-2xl bg-card-custom overflow-hidden">
          <!-- Session Header: Mode Tabs + Close -->
          <div class="modal-header border-bottom py-2 px-3 px-md-4 bg-teal text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-collection-play-fill fs-5"></i>
              <strong class="fs-7">Phiên Ôn Tập ({{ currentReviewIndex + 1 }}/{{ activeSessionCards.length }})</strong>
            </div>

            <!-- Mode Switcher Pill -->
            <div class="btn-group btn-group-sm p-0.5 rounded-pill bg-black/20 border border-white/20">
              <button
                @click="sessionMode = 'flip'"
                class="btn btn-xs rounded-pill px-2.5 py-1 fw-bold fs-9 text-white border-0"
                :class="sessionMode === 'flip' ? 'bg-white text-dark shadow-xs' : 'bg-transparent text-white-50'"
              >
                <i class="bi bi-card-heading me-1"></i> Lật thẻ 3D
              </button>
              <button
                @click="sessionMode = 'typing'"
                class="btn btn-xs rounded-pill px-2.5 py-1 fw-bold fs-9 text-white border-0"
                :class="sessionMode === 'typing' ? 'bg-white text-dark shadow-xs' : 'bg-transparent text-white-50'"
              >
                <i class="bi bi-keyboard me-1"></i> Gõ từ
              </button>
              <button
                @click="initQuizMode"
                class="btn btn-xs rounded-pill px-2.5 py-1 fw-bold fs-9 text-white border-0"
                :class="sessionMode === 'quiz' ? 'bg-white text-dark shadow-xs' : 'bg-transparent text-white-50'"
              >
                <i class="bi bi-check2-circle me-1"></i> Trắc nghiệm
              </button>
              <button
                @click="initMatchMode"
                class="btn btn-xs rounded-pill px-2.5 py-1 fw-bold fs-9 text-white border-0"
                :class="sessionMode === 'match' ? 'bg-white text-dark shadow-xs' : 'bg-transparent text-white-50'"
              >
                <i class="bi bi-puzzle me-1"></i> Ghép thẻ
              </button>
            </div>

            <button @click="isReviewingSession = false" class="btn-close btn-close-white"></button>
          </div>

          <div class="modal-body p-3 p-md-4 text-center">
            <!-- CHẾ ĐỘ 1: LẬT THẺ 3D (3D FLIP SPACED REPETITION) -->
            <div v-if="sessionMode === 'flip' && currentActiveReviewCard" class="max-w-xl mx-auto">
              <div
                class="review-3d-card-box cursor-pointer mb-3"
                @click="isCardFlipped = !isCardFlipped"
                :class="{ 'is-flipped': isCardFlipped }"
              >
                <div class="review-3d-card-inner">
                  <!-- Mặt trước (Front) -->
                  <div class="review-3d-face review-3d-front p-4 rounded-4 shadow-lg border bg-card-custom d-flex flex-column justify-content-between text-center">
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 fs-9 text-uppercase">
                        {{ currentActiveReviewCard.level || 'TỪ VỰNG' }}
                      </span>
                      <button @click.stop="speech.speak(currentActiveReviewCard.word)" class="btn btn-sm btn-outline-teal rounded-pill px-2.5 py-0.5 fs-8">
                        <i class="bi bi-volume-up-fill"></i> US
                      </button>
                    </div>

                    <div class="my-auto py-4 ox-text-break">
                      <h2 class="display-5 fw-black text-teal mb-1">{{ currentActiveReviewCard.word }}</h2>
                      <div class="text-muted fs-7 font-monospace" v-if="currentActiveReviewCard.phonetic_us">
                        {{ currentActiveReviewCard.phonetic_us }}
                      </div>
                    </div>

                    <div class="text-muted fs-8">
                      <i class="bi bi-arrow-repeat me-1"></i> Bấm hoặc nhấn [Space] để lật thẻ
                    </div>
                  </div>

                  <!-- Mặt sau (Back) -->
                  <div class="review-3d-face review-3d-back p-4 rounded-4 shadow-lg border bg-dark text-white d-flex flex-column justify-content-between text-start" @click.stop>
                    <div>
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-9 fw-bold">
                          ĐỊNH NGHĨA & BẢN DỊCH
                        </span>
                        <button @click.stop="isCardFlipped = false" class="btn btn-sm btn-outline-light rounded-pill px-2 py-0.5 fs-9">
                          Lật lại mặt trước
                        </button>
                      </div>
                      <h4 class="fw-bold text-warning mb-2 ox-text-break">{{ currentActiveReviewCard.meaning_vi }}</h4>
                      <div v-if="currentActiveReviewCard.example_en" class="p-2.5 rounded-3 bg-secondary-subtle/20 border border-secondary fs-8 mt-2 text-light ox-text-break">
                        <div class="fst-italic text-info-emphasis">"{{ currentActiveReviewCard.example_en }}"</div>
                      </div>
                    </div>

                    <div class="pt-3 border-top border-secondary d-flex justify-content-center gap-3">
                      <button @click.stop="handleReviewAnswer(false)" class="btn btn-outline-warning rounded-pill px-4 py-2 fw-bold fs-8 shadow-xs">
                        <i class="bi bi-arrow-clockwise me-1"></i> Cần ôn [X]
                      </button>
                      <button @click.stop="handleReviewAnswer(true)" class="btn btn-success rounded-pill px-4 py-2 fw-bold fs-8 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Đã nhớ [S]
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- CHẾ ĐỘ 2: GÕ LẠI TỪ (ACTIVE RECALL TYPING) -->
            <div v-else-if="sessionMode === 'typing' && currentActiveReviewCard" class="max-w-xl mx-auto text-start">
              <div class="p-4 rounded-4 shadow-sm border bg-card-custom mb-3">
                <span class="badge bg-teal text-white fs-9 rounded-pill mb-2">NGHĨA TIẾNG VIỆT</span>
                <h4 class="fw-bold text-dark-emphasis mb-3 ox-text-break">{{ currentActiveReviewCard.meaning_vi }}</h4>
                <div v-if="currentActiveReviewCard.example_en" class="text-muted fs-8 fst-italic mb-3 ox-text-break">
                  Gợi ý ngữ cảnh: "{{ currentActiveReviewCard.example_en }}"
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1.5">
                  <label class="form-label fs-8 fw-bold text-teal mb-0">Gõ lại từ tiếng Anh chính xác:</label>
                  <button @click="revealTypingWord" class="btn btn-xs btn-outline-warning rounded-pill px-2 py-0.5 fs-9">
                    Gợi ý đáp án
                  </button>
                </div>

                <div class="input-group mb-2">
                  <input
                    v-model="typedReviewWord"
                    @keyup.enter="checkReviewRecall"
                    type="text"
                    class="form-control form-control-lg rounded-pill-start fs-6 font-monospace"
                    placeholder="Gõ từ tiếng Anh..."
                    autocomplete="off"
                  />
                  <button @click="checkReviewRecall" class="btn btn-teal text-white rounded-pill-end px-4 fw-bold">
                    Kiểm tra
                  </button>
                </div>

                <div v-if="reviewFeedback === 'correct'" class="alert alert-success p-2.5 rounded-3 fs-8 fw-bold d-flex justify-content-between align-items-center mb-0">
                  <span><i class="bi bi-check-circle-fill me-1"></i> Chính xác tuyệt đối: <strong>{{ currentActiveReviewCard.word }}</strong></span>
                  <button @click="handleReviewAnswer(true)" class="btn btn-sm btn-success rounded-pill px-3 py-1 fs-9 fw-bold">Tiếp tục →</button>
                </div>
                <div v-else-if="reviewFeedback === 'wrong'" class="alert alert-danger p-2.5 rounded-3 fs-8 fw-bold d-flex justify-content-between align-items-center mb-0">
                  <span>Chưa đúng! Đáp án: <strong>{{ currentActiveReviewCard.word }}</strong></span>
                  <button @click="handleReviewAnswer(false)" class="btn btn-sm btn-danger rounded-pill px-3 py-1 fs-9 fw-bold">Ôn lại →</button>
                </div>
              </div>
            </div>

            <!-- CHẾ ĐỘ 3: TRẮC NGHIỆM PHẢN XẠ (QUIZ 4 LỰA CHỌN) -->
            <div v-else-if="sessionMode === 'quiz' && currentQuizItem" class="max-w-xl mx-auto text-start">
              <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-3">
                <div class="text-center py-2 mb-3">
                  <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-0.5 fs-9 text-uppercase mb-2">
                    TỪ CẦN ÔN
                  </span>
                  <h3 class="fw-black text-teal mb-1 ox-text-break">{{ currentQuizItem.word }}</h3>
                  <div class="text-muted fs-8 font-monospace">{{ currentQuizItem.phonetic_us }}</div>
                </div>

                <div class="d-flex flex-column gap-2 mb-3">
                  <button
                    v-for="(opt, idx) in quizOptions"
                    :key="idx"
                    @click="answerQuiz(opt)"
                    :disabled="quizAnswered"
                    class="btn text-start p-3 rounded-3 border transition-all fs-7 ox-text-break"
                    :class="getQuizBtnClass(opt)"
                  >
                    <span>{{ opt }}</span>
                  </button>
                </div>

                <div v-if="quizAnswered" class="text-center pt-2">
                  <button @click="nextQuizStep" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-8">
                    Câu tiếp theo →
                  </button>
                </div>
              </div>
            </div>

            <!-- CHẾ ĐỘ 4: GHÉP THẺ PHẢN XẠ (MATCHING PAIRS) -->
            <div v-else-if="sessionMode === 'match'" class="max-w-2xl mx-auto">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fs-8 fw-bold text-muted">Nhấn chọn cặp Từ tiếng Anh & Nghĩa tiếng Việt tương ứng:</span>
                <span class="badge bg-teal text-white rounded-pill fs-8">Đã ghép: {{ matchedPairsCount }} / {{ matchItems.length / 2 }}</span>
              </div>

              <div class="row g-2.5">
                <div v-for="tile in matchTiles" :key="tile.uid" class="col-6 col-md-4">
                  <div
                    @click="selectMatchTile(tile)"
                    class="card p-3 rounded-3 border text-center cursor-pointer transition-all h-100 d-flex align-items-center justify-content-center ox-text-break"
                    :class="getMatchTileClass(tile)"
                    style="min-height: 84px;"
                  >
                    <span class="fw-bold fs-8" :class="tile.type === 'en' ? 'text-teal' : 'text-dark-emphasis'">
                      {{ tile.text }}
                    </span>
                  </div>
                </div>
              </div>

              <div v-if="isMatchCompleted" class="alert alert-success p-3 rounded-4 mt-3 fs-7 fw-bold text-center">
                🎉 Tuyệt vời! Bạn đã ghép chính xác toàn bộ các thẻ!
              </div>
            </div>

            <!-- All Done State -->
            <div v-else-if="!currentActiveReviewCard" class="py-5 text-center">
              <i class="bi bi-patch-check-fill text-success display-3 d-block mb-3"></i>
              <h4 class="fw-bold text-dark-emphasis mb-2">Hoàn Thành Xuất Sắc!</h4>
              <p class="text-muted fs-7 mb-4">Bạn đã ôn tập xong tất cả các thẻ tới hạn của phiên này.</p>
              <button @click="isReviewingSession = false" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-8 shadow-sm">
                Đóng & Quay lại danh sách
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useReviewStore } from '@/stores/reviewStore'
import { useSpeech } from '@/composables/useSpeech'
import { useToast } from '@/composables/useToast'

const authStore = useAuthStore()
const reviewStore = useReviewStore()
const speech = useSpeech()
const toast = useToast()

const selectedCardIds = ref([])
const isCreatingFolder = ref(false)
const newFolderName = ref('')
const newFolderInput = ref(null)

const isCardModalOpen = ref(false)
const editingCardId = ref(null)
const cardForm = ref({
  word: '',
  meaning_vi: '',
  folder: 'Chưa xếp',
  level: 'Từ vựng',
  example_en: ''
})

// Review Session State
const isReviewingSession = ref(false)
const sessionMode = ref('flip') // 'flip' | 'typing' | 'quiz' | 'match'
const activeSessionCards = ref([])
const currentReviewIndex = ref(0)
const isCardFlipped = ref(false)
const typedReviewWord = ref('')
const reviewFeedback = ref(null)

// Quiz Mode State
const quizOptions = ref([])
const quizAnswered = ref(false)
const selectedQuizOpt = ref(null)

// Match Mode State
const matchItems = ref([])
const matchTiles = ref([])
const selectedTile1 = ref(null)
const selectedTile2 = ref(null)
const matchedPairsCount = ref(0)

const progressPercent = computed(() => {
  if (reviewStore.totalCardsCount === 0) return 0
  return Math.round((reviewStore.masteredCardsCount / reviewStore.totalCardsCount) * 100)
})

const activeFolderCardsCount = computed(() => {
  return reviewStore.getFolderCount(reviewStore.activeFolder)
})

const folderDisplayTitle = computed(() => {
  if (reviewStore.activeFolder === 'all') return 'Tất cả'
  if (reviewStore.activeFolder === 'starred') return 'Đã đánh dấu'
  return reviewStore.activeFolder
})

const currentActiveReviewCard = computed(() => {
  if (currentReviewIndex.value < activeSessionCards.value.length) {
    return activeSessionCards.value[currentReviewIndex.value]
  }
  return null
})

const currentQuizItem = computed(() => currentActiveReviewCard.value)

const isMatchCompleted = computed(() => {
  return matchTiles.value.length > 0 && matchTiles.value.every(t => t.matched)
})

const openFolderInput = async () => {
  isCreatingFolder.value = true
  await nextTick()
  if (newFolderInput.value) {
    newFolderInput.value.focus()
  }
}

const handleCreateFolder = () => {
  if (reviewStore.addFolder(newFolderName.value)) {
    isCreatingFolder.value = false
    newFolderName.value = ''
  }
}

const toggleSelectAll = (e) => {
  if (e.target.checked) {
    selectedCardIds.value = reviewStore.filteredCards.map(c => c.id)
  } else {
    selectedCardIds.value = []
  }
}

const openAddCardModal = () => {
  editingCardId.value = null
  cardForm.value = {
    word: '',
    meaning_vi: '',
    folder: reviewStore.activeFolder !== 'all' && reviewStore.activeFolder !== 'starred' ? reviewStore.activeFolder : 'Chưa xếp',
    level: 'Từ vựng',
    example_en: ''
  }
  isCardModalOpen.value = true
}

const openEditCardModal = (card) => {
  editingCardId.value = card.id
  cardForm.value = {
    word: card.word,
    meaning_vi: card.meaning_vi,
    folder: card.folder || 'Chưa xếp',
    level: card.level || 'Từ vựng',
    example_en: card.example_en || ''
  }
  isCardModalOpen.value = true
}

const handleSaveCard = () => {
  if (!cardForm.value.word.trim() || !cardForm.value.meaning_vi.trim()) {
    toast.warning('Vui lòng nhập cả từ tiếng Anh và nghĩa tiếng Việt!', 'Thiếu thông tin')
    return
  }
  if (editingCardId.value) {
    reviewStore.updateCard(editingCardId.value, cardForm.value)
    toast.saved(`Đã cập nhật từ "${cardForm.value.word}"`, 'Bộ thẻ ôn tập')
  } else {
    reviewStore.addWordToReview(cardForm.value, cardForm.value.folder)
    toast.saved(`Đã thêm "${cardForm.value.word}" vào bộ thẻ ôn tập!`, 'Đã lưu thẻ')
  }
  isCardModalOpen.value = false
}

// Start Session
const startReviewSession = (mode = 'flip') => {
  activeSessionCards.value = reviewStore.filteredCards.filter(c => c.status === 'due' || !c.due_date)
  if (activeSessionCards.value.length === 0) {
    activeSessionCards.value = [...reviewStore.filteredCards]
  }
  currentReviewIndex.value = 0
  isCardFlipped.value = false
  typedReviewWord.value = ''
  reviewFeedback.value = null
  sessionMode.value = mode
  isReviewingSession.value = true

  if (mode === 'quiz') {
    generateQuizStep()
  } else if (mode === 'match') {
    setupMatchGame()
  } else if (activeSessionCards.value[0]?.word) {
    speech.speak(activeSessionCards.value[0].word)
  }
}

const handleReviewAnswer = (isSuccess) => {
  if (currentActiveReviewCard.value) {
    reviewStore.recordReviewResult(currentActiveReviewCard.value.id, isSuccess)
  }
  currentReviewIndex.value++
  isCardFlipped.value = false
  typedReviewWord.value = ''
  reviewFeedback.value = null

  if (currentActiveReviewCard.value?.word) {
    speech.speak(currentActiveReviewCard.value.word)
  }
}

// Typing mode
const checkReviewRecall = () => {
  if (!currentActiveReviewCard.value || !typedReviewWord.value) return
  const clean = (s) => (s || '').toLowerCase().trim().replace(/[.,?!:;"'_\-()]/g, '')
  if (clean(typedReviewWord.value) === clean(currentActiveReviewCard.value.word)) {
    reviewFeedback.value = 'correct'
    speech.speak(currentActiveReviewCard.value.word)
  } else {
    reviewFeedback.value = 'wrong'
  }
}

const revealTypingWord = () => {
  typedReviewWord.value = currentActiveReviewCard.value?.word || ''
  speech.speak(currentActiveReviewCard.value?.word)
}

// Quiz mode
const initQuizMode = () => {
  sessionMode.value = 'quiz'
  generateQuizStep()
}

const generateQuizStep = () => {
  if (!currentActiveReviewCard.value) return
  quizAnswered.value = false
  selectedQuizOpt.value = null

  const correct = currentActiveReviewCard.value.meaning_vi
  const pool = reviewStore.filteredCards.filter(c => c.id !== currentActiveReviewCard.value.id)
  const wrong = pool.map(c => c.meaning_vi).sort(() => 0.5 - Math.random()).slice(0, 3)

  quizOptions.value = [correct, ...wrong].sort(() => 0.5 - Math.random())
}

const answerQuiz = (opt) => {
  if (quizAnswered.value) return
  selectedQuizOpt.value = opt
  quizAnswered.value = true

  const isCorrect = opt === currentActiveReviewCard.value?.meaning_vi
  reviewStore.recordReviewResult(currentActiveReviewCard.value.id, isCorrect)
}

const getQuizBtnClass = (opt) => {
  if (!quizAnswered.value) {
    return 'btn-outline-secondary bg-body-tertiary'
  }
  if (opt === currentActiveReviewCard.value?.meaning_vi) {
    return 'btn-success text-white'
  }
  if (opt === selectedQuizOpt.value && opt !== currentActiveReviewCard.value?.meaning_vi) {
    return 'btn-danger text-white'
  }
  return 'btn-outline-secondary opacity-50'
}

const nextQuizStep = () => {
  currentReviewIndex.value++
  generateQuizStep()
}

// Match mode
const initMatchMode = () => {
  sessionMode.value = 'match'
  setupMatchGame()
}

const setupMatchGame = () => {
  matchedPairsCount.value = 0
  selectedTile1.value = null
  selectedTile2.value = null

  const slice = activeSessionCards.value.slice(0, 6)
  const tiles = []

  slice.forEach(c => {
    tiles.push({ uid: `${c.id}_en`, cardId: c.id, text: c.word, type: 'en', matched: false })
    tiles.push({ uid: `${c.id}_vi`, cardId: c.id, text: c.meaning_vi, type: 'vi', matched: false })
  })

  matchTiles.value = tiles.sort(() => 0.5 - Math.random())
}

const selectMatchTile = (tile) => {
  if (tile.matched) return
  if (!selectedTile1.value) {
    selectedTile1.value = tile
    if (tile.type === 'en') speech.speak(tile.text)
    return
  }
  if (selectedTile1.value.uid === tile.uid) {
    selectedTile1.value = null
    return
  }

  selectedTile2.value = tile

  if (selectedTile1.value.cardId === tile.cardId && selectedTile1.value.type !== tile.type) {
    selectedTile1.value.matched = true
    tile.matched = true
    matchedPairsCount.value++
    selectedTile1.value = null
    selectedTile2.value = null
  } else {
    setTimeout(() => {
      selectedTile1.value = null
      selectedTile2.value = null
    }, 600)
  }
}

const getMatchTileClass = (tile) => {
  if (tile.matched) {
    return 'bg-success-subtle border-success text-success opacity-50'
  }
  if (selectedTile1.value?.uid === tile.uid || selectedTile2.value?.uid === tile.uid) {
    return 'border-teal bg-teal text-white shadow-sm'
  }
  return 'bg-card-custom hover-shadow'
}

// Keydown listeners for Space & S & X
const handleKeyDown = (e) => {
  if (!isReviewingSession.value || sessionMode.value !== 'flip') return
  if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return

  if (e.code === 'Space') {
    e.preventDefault()
    isCardFlipped.value = !isCardFlipped.value
  } else if (e.key === 's' || e.key === 'S') {
    handleReviewAnswer(true)
  } else if (e.key === 'x' || e.key === 'X') {
    handleReviewAnswer(false)
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
})
</script>

<style scoped>
.active-folder-btn {
  background-color: #032b24 !important;
  color: #ffffff !important;
}

.hover-folder-btn {
  background-color: transparent;
  color: inherit;
}

.hover-folder-btn:hover {
  background-color: rgba(0, 176, 116, 0.08);
  color: #00b074;
}

[data-bs-theme="dark"] .active-folder-btn {
  background-color: #032b24 !important;
  color: #5eead4 !important;
  border: 1px solid rgba(0, 176, 116, 0.35);
}

/* 3D Flip Card Container */
.review-3d-card-box {
  perspective: 1200px;
  width: 100%;
  height: 420px;
  user-select: none;
}

.review-3d-card-inner {
  position: relative;
  width: 100%;
  height: 100%;
  transition: transform 0.55s cubic-bezier(0.4, 0, 0.2, 1);
  transform-style: preserve-3d;
}

.review-3d-card-box.is-flipped .review-3d-card-inner {
  transform: rotateY(180deg);
}

.review-3d-face {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}

.review-3d-back {
  transform: rotateY(180deg);
}
</style>
