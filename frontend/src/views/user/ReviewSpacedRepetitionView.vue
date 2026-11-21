<template>
  <div class="review-page min-vh-100 pb-5">
    <div class="container-fluid px-3 px-md-4 px-xl-5 pt-3 pt-md-4 max-w-7xl mx-auto">
      <!-- 1. TOP HEADER (MATCHING IMAGE 3 & 4) -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3 mb-md-4">
        <div>
          <h1 class="display-6 fw-black text-dark-emphasis mb-1 tracking-tight">
            Bộ thẻ ôn tập
          </h1>
          <p class="text-muted fs-7 mb-0">
            {{ reviewStore.dueCardsCount }} thẻ tới hạn hôm nay.
          </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
          <!-- Button Ôn ngay / Ôn bộ này -->
          <button
            @click="startReviewSession"
            :disabled="reviewStore.dueCardsCount === 0 && reviewStore.totalCardsCount === 0"
            class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-8 d-inline-flex align-items-center gap-2 shadow-sm"
          >
            <span>{{ reviewStore.activeFolder === 'all' ? 'Ôn ngay' : 'Ôn bộ này' }}</span>
            <span class="badge bg-white text-dark rounded-pill px-2 py-0.5 fs-9 fw-black">
              {{ reviewStore.activeFolder === 'all' ? reviewStore.dueCardsCount : activeFolderCardsCount }}
            </span>
          </button>

          <!-- Button + Tạo bộ thẻ -->
          <button
            @click="openAddCardModal"
            class="btn btn-outline-secondary rounded-pill px-3.5 py-2 fw-semibold fs-8 d-inline-flex align-items-center gap-1 shadow-2xs"
            :class="authStore.themeMode === 'dark' ? 'border-secondary text-white' : 'border-light-subtle bg-white text-dark'"
          >
            <i class="bi bi-plus-lg"></i>
            <span>Tạo bộ thẻ</span>
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

      <!-- 2. SUMMARY PROGRESS CARD (MATCHING IMAGE 3 & 4) -->
      <div class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-3.5 mb-md-4">
        <div class="d-flex justify-content-between align-items-center mb-2.5">
          <div class="d-flex align-items-baseline gap-2">
            <span class="display-6 fw-black text-teal leading-none">
              {{ reviewStore.dueCardsCount }}
            </span>
            <span class="fs-7 fw-bold text-dark-emphasis">thẻ tới hạn</span>
          </div>
          <div class="text-muted fs-8">
            Tổng {{ reviewStore.totalCardsCount }} thẻ
          </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress rounded-pill mb-3" style="height: 6px; background-color: rgba(0, 176, 116, 0.15);">
          <div
            class="progress-bar rounded-pill bg-teal"
            :style="{ width: `${progressPercent}%`, transition: 'width 0.4s ease' }"
          ></div>
        </div>

        <!-- Bottom row: Đã thuộc, Đang học & Chiều ôn toggle -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2.5 pt-1">
          <div class="d-flex align-items-center gap-3 fs-8">
            <span class="d-inline-flex align-items-center gap-1.5 text-muted">
              <span class="rounded-circle bg-teal" style="width: 7px; height: 7px;"></span>
              <span>Đã thuộc <strong class="text-dark-emphasis">{{ reviewStore.masteredCardsCount }}</strong></span>
            </span>
            <span class="d-inline-flex align-items-center gap-1.5 text-muted">
              <span class="rounded-circle border border-teal" style="width: 7px; height: 7px;"></span>
              <span>Đang học <strong class="text-dark-emphasis">{{ reviewStore.learningCardsCount }}</strong></span>
            </span>
          </div>

          <!-- Chiều ôn: [Việt -> gõ Anh] [Anh -> nhớ Việt] -->
          <div class="d-flex align-items-center gap-2">
            <span class="fs-8 text-muted">Chiều ôn:</span>
            <div class="d-inline-flex p-1 rounded-pill border bg-body-tertiary shadow-2xs">
              <button
                @click="reviewStore.studyDirection = 'vi_to_en'; reviewStore.saveToStorage()"
                class="btn btn-xs rounded-pill px-2.5 py-1 fs-8 fw-bold transition-all border-0"
                :class="reviewStore.studyDirection === 'vi_to_en' ? 'btn-teal text-white shadow-2xs' : 'text-muted bg-transparent'"
              >
                Việt → gõ Anh
              </button>
              <button
                @click="reviewStore.studyDirection = 'en_to_vi'; reviewStore.saveToStorage()"
                class="btn btn-xs rounded-pill px-2.5 py-1 fs-8 fw-bold transition-all border-0"
                :class="reviewStore.studyDirection === 'en_to_vi' ? 'btn-teal text-white shadow-2xs' : 'text-muted bg-transparent'"
              >
                Anh → nhớ Việt
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. MAIN 2-COLUMN WORKSPACE: LEFT FOLDERS + RIGHT TABLE -->
      <div class="row g-3 g-md-4">
        <!-- CỘT TRÁI: BỘ THẺ (FOLDERS) -->
        <div class="col-12 col-md-4 col-lg-3">
          <div class="card p-3 rounded-4 shadow-sm border bg-card-custom sticky-top" style="top: 80px;">
            <h6 class="fw-black text-dark-emphasis mb-3 fs-7">
              Bộ thẻ
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

              <!-- Dynamic Folders: Chưa xếp, Thư mục tạo riêng -->
              <div
                v-for="f in reviewStore.folders"
                :key="f"
                class="d-flex align-items-center justify-content-between group-folder"
              >
                <button
                  @click="reviewStore.activeFolder = f"
                  class="btn btn-sm rounded-3 py-2 px-3 text-start d-flex align-items-center justify-content-between border-0 flex-grow-1 transition-all"
                  :class="reviewStore.activeFolder === f ? 'active-folder-btn' : 'hover-folder-btn'"
                >
                  <span class="fw-bold fs-7 text-truncate">{{ f }}</span>
                  <span class="badge rounded-pill" :class="reviewStore.activeFolder === f ? 'bg-white text-dark' : 'bg-body-secondary text-secondary'">
                    {{ reviewStore.getFolderCount(f) }}
                  </span>
                </button>

                <button
                  v-if="f !== 'Chưa xếp'"
                  @click="reviewStore.deleteFolder(f)"
                  class="btn btn-sm text-danger p-1 ms-1 opacity-50 hover-opacity-100 border-0"
                  title="Xóa thư mục này"
                >
                  <i class="bi bi-x-circle"></i>
                </button>
              </div>
            </div>

            <!-- INLINE FOLDER CREATOR (MATCHING IMAGE 4: [Tên thư mục] [Tạo] [Hủy]) -->
            <div v-if="isCreatingFolder" class="p-2 rounded-3 border bg-body-tertiary mb-2">
              <div class="input-group input-group-sm mb-1.5">
                <input
                  v-model="newFolderName"
                  ref="newFolderInput"
                  @keyup.enter="handleCreateFolder"
                  type="text"
                  class="form-control rounded-2 fs-8"
                  placeholder="Tên thư mục"
                  maxlength="30"
                />
              </div>
              <div class="d-flex justify-content-end gap-1.5">
                <button @click="handleCreateFolder" class="btn btn-xs btn-teal text-white rounded-pill px-2.5 py-1 fw-bold fs-9">
                  Tạo
                </button>
                <button @click="isCreatingFolder = false; newFolderName = ''" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 fs-9">
                  Hủy
                </button>
              </div>
            </div>

            <button
              v-else
              @click="openFolderInput"
              class="btn btn-sm btn-outline-secondary rounded-pill w-100 py-1.5 fs-8 fw-semibold d-flex align-items-center justify-content-center gap-1.5"
            >
              <i class="bi bi-folder-plus text-teal"></i>
              <span>+ Thư mục</span>
            </button>
          </div>
        </div>

        <!-- CỘT PHẢI: BẢNG DANH SÁCH THẺ ÔN TẬP -->
        <div class="col-12 col-md-8 col-lg-9">
          <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
              <div class="fw-black text-dark-emphasis fs-6 d-flex align-items-center gap-2">
                <span>{{ folderDisplayTitle }}</span>
                <span class="text-teal font-monospace fs-7">{{ reviewStore.filteredCards.length }}</span>
              </div>

              <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-sm-auto justify-content-end">
                <!-- Search input -->
                <div class="input-group input-group-sm" style="max-width: 220px;">
                  <span class="input-group-text bg-body-tertiary border-end-0">
                    <i class="bi bi-search text-muted fs-8"></i>
                  </span>
                  <input
                    v-model="reviewStore.searchKeyword"
                    type="text"
                    class="form-control border-start-0 fs-8"
                    placeholder="Tìm từ hoặc nghĩa..."
                  />
                </div>

                <!-- Sort dropdown -->
                <select v-model="reviewStore.sortOption" class="form-select form-select-sm fs-8 rounded-pill" style="width: auto;">
                  <option value="due_first">Tới hạn trước</option>
                  <option value="newest">Mới nhất</option>
                  <option value="alphabet">Bảng chữ cái A-Z</option>
                </select>

                <!-- Button + Thêm thẻ -->
                <button @click="openAddCardModal" class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8 d-inline-flex align-items-center gap-1">
                  <i class="bi bi-plus-lg"></i>
                  <span>Thêm thẻ</span>
                </button>
              </div>
            </div>

            <!-- TABLE OF CARDS (MATCHING IMAGE 3 & 4) -->
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="fs-8 text-muted border-bottom text-uppercase">
                  <tr>
                    <th style="width: 36px;">
                      <input type="checkbox" class="form-check-input" @change="toggleSelectAll" />
                    </th>
                    <th style="min-width: 170px;">Từ / câu</th>
                    <th style="min-width: 280px;">Nghĩa</th>
                    <th style="min-width: 110px;">Bộ thẻ</th>
                    <th style="min-width: 100px;">Lịch ôn</th>
                    <th class="text-end" style="width: 90px;">Thao tác</th>
                  </tr>
                </thead>
                <tbody class="fs-7">
                  <tr v-for="c in reviewStore.filteredCards" :key="c.id" class="transition-all">
                    <td>
                      <input type="checkbox" :value="c.id" v-model="selectedCardIds" class="form-check-input" />
                    </td>

                    <!-- Term + Level -->
                    <td>
                      <div class="d-flex align-items-start gap-2">
                        <button
                          @click="reviewStore.toggleStar(c.id)"
                          class="btn btn-link p-0 text-decoration-none border-0 mt-0.5"
                          title="Đánh dấu thẻ quan trọng"
                        >
                          <i class="bi fs-7" :class="c.starred ? 'bi-star-fill text-warning' : 'bi-star text-muted'"></i>
                        </button>
                        <div>
                          <div class="d-flex align-items-center gap-1.5">
                            <span class="fw-bold text-dark-emphasis fs-6">{{ c.word }}</span>
                            <button @click="speech.speak(c.word)" class="btn btn-xs btn-link p-0 text-teal" title="Nghe phát âm">
                              <i class="bi bi-volume-up-fill"></i>
                            </button>
                          </div>
                          <div class="text-muted fs-8 font-monospace">
                            {{ c.level || 'Từ vựng' }}
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- Meaning & Translation -->
                    <td>
                      <div class="text-dark-emphasis fw-medium fs-8" style="line-height: 1.45;">
                        {{ c.meaning_vi }}
                      </div>
                      <div v-if="c.example_en" class="text-muted fs-9 fst-italic mt-0.5">
                        {{ c.example_en }}
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

                    <!-- Actions: Sửa, Bỏ -->
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
                      <span>Chưa có thẻ nào trong bộ này. Nhấn <strong>"Cần ôn" (phím X)</strong> trong bài học hoặc bấm <strong>"+ Thêm thẻ"</strong> để bắt đầu ôn tập!</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. MODAL: THÊM / CHỈNH SỬA THẺ ÔN TẬP -->
    <div v-if="isCardModalOpen" class="modal show d-block" style="background: rgba(0,0,0,0.6); z-index: 1080;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-card-custom">
          <div class="modal-header border-bottom">
            <h5 class="modal-title fw-black text-teal">
              {{ editingCardId ? 'Chỉnh Sửa Thẻ Ôn Tập' : 'Tạo Thẻ Ôn Tập Mới' }}
            </h5>
            <button @click="isCardModalOpen = false" class="btn-close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fs-8 fw-bold">Từ hoặc Cụm từ tiếng Anh (*):</label>
              <input v-model="cardForm.word" type="text" class="form-control rounded-3" placeholder="e.g. aberration, take over..." />
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
                <label class="form-label fs-8 fw-bold">Cấp độ / Phân loại:</label>
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

    <!-- 5. INTERACTIVE REVIEW SESSION MODAL (ÔN TẬP PHẢN XẠ 3D + GÕ TỪ / NHỚ NGHĨA) -->
    <div v-if="isReviewingSession" class="modal show d-block" style="background: rgba(0,0,0,0.85); z-index: 1090;">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-2xl bg-card-custom overflow-hidden">
          <div class="modal-header border-bottom py-2.5 px-4 bg-teal text-white">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-card-checklist fs-5"></i>
              <strong class="fs-6">Phiên Ôn Tập • Thẻ {{ currentReviewIndex + 1 }} / {{ activeSessionCards.length }}</strong>
            </div>
            <button @click="isReviewingSession = false" class="btn-close btn-close-white"></button>
          </div>

          <div class="modal-body p-4 text-center">
            <div v-if="currentActiveReviewCard" class="max-w-xl mx-auto">
              <!-- Mode A: Việt -> gõ Anh -->
              <div v-if="reviewStore.studyDirection === 'vi_to_en'" class="p-4 rounded-4 shadow-sm border bg-body-tertiary mb-3 text-start">
                <span class="badge bg-teal text-white fs-9 rounded-pill mb-2">NGHĨA TIẾNG VIỆT</span>
                <h4 class="fw-bold text-dark-emphasis mb-3">{{ currentActiveReviewCard.meaning_vi }}</h4>
                <div v-if="currentActiveReviewCard.example_vi" class="text-muted fs-8 fst-italic mb-3">
                  Ví dụ: "{{ currentActiveReviewCard.example_vi }}"
                </div>

                <label class="form-label fs-8 fw-bold text-teal">Gõ lại từ tiếng Anh chính xác:</label>
                <div class="input-group mb-2">
                  <input
                    v-model="typedReviewWord"
                    @keyup.enter="checkReviewRecall"
                    type="text"
                    class="form-control form-control-lg rounded-pill-start fs-6"
                    placeholder="Gõ từ tiếng Anh..."
                    autocomplete="off"
                  />
                  <button @click="checkReviewRecall" class="btn btn-teal text-white rounded-pill-end px-4 fw-bold">
                    Kiểm tra
                  </button>
                </div>

                <div v-if="reviewFeedback === 'correct'" class="alert alert-success p-2.5 rounded-3 fs-8 fw-bold d-flex align-items-center gap-2 mb-0">
                  <i class="bi bi-check-circle-fill fs-6"></i>
                  <span>Tuyệt vời! Đáp án chuẩn: <strong>{{ currentActiveReviewCard.word }}</strong></span>
                </div>
                <div v-else-if="reviewFeedback === 'wrong'" class="alert alert-danger p-2.5 rounded-3 fs-8 fw-bold d-flex justify-content-between align-items-center mb-0">
                  <span>Chưa chính xác! Đáp án đúng: <strong>{{ currentActiveReviewCard.word }}</strong></span>
                  <button @click="speech.speak(currentActiveReviewCard.word)" class="btn btn-xs btn-outline-danger rounded-pill px-2 py-0.5">
                    <i class="bi bi-volume-up-fill"></i> Nghe
                  </button>
                </div>
              </div>

              <!-- Mode B: Anh -> nhớ Việt -->
              <div v-else class="p-4 rounded-4 shadow-sm border bg-body-tertiary mb-3 text-center">
                <span class="badge bg-teal text-white fs-9 rounded-pill mb-2">TỪ TIẾNG ANH</span>
                <h2 class="display-6 fw-black text-teal mb-2">{{ currentActiveReviewCard.word }}</h2>
                <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                  <span class="fst-italic text-muted">{{ currentActiveReviewCard.phonetic_us }}</span>
                  <button @click="speech.speak(currentActiveReviewCard.word)" class="btn btn-sm btn-outline-teal rounded-circle py-1 px-2">
                    <i class="bi bi-volume-up-fill"></i>
                  </button>
                </div>

                <div v-if="isMeaningRevealed" class="p-3 rounded-3 bg-white border shadow-xs text-start mb-3">
                  <div class="fw-bold text-dark-emphasis fs-6">{{ currentActiveReviewCard.meaning_vi }}</div>
                  <div v-if="currentActiveReviewCard.example_en" class="text-muted fs-8 mt-1">
                    {{ currentActiveReviewCard.example_en }}
                  </div>
                </div>

                <button v-else @click="isMeaningRevealed = true" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-bold fs-8 mb-3">
                  <i class="bi bi-eye-fill me-1"></i> Xem nghĩa tiếng Việt
                </button>
              </div>

              <!-- Decision Buttons: [Đã nhớ ✓] vs [Cần ôn lại ↺] -->
              <div class="d-flex justify-content-center gap-3">
                <button @click="handleReviewAnswer(false)" class="btn btn-outline-warning rounded-pill px-4 py-2 fw-bold fs-8 shadow-xs">
                  <i class="bi bi-arrow-clockwise me-1"></i> Cần ôn lại
                </button>
                <button @click="handleReviewAnswer(true)" class="btn btn-success rounded-pill px-4 py-2 fw-bold fs-8 shadow-sm">
                  <i class="bi bi-check2-circle me-1"></i> Đã nhớ ✓
                </button>
              </div>
            </div>

            <!-- All Done State -->
            <div v-else class="py-5 text-center">
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
import { ref, computed, nextTick } from 'vue'
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
const activeSessionCards = ref([])
const currentReviewIndex = ref(0)
const typedReviewWord = ref('')
const reviewFeedback = ref(null)
const isMeaningRevealed = ref(false)

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

// Interactive Review Session
const startReviewSession = () => {
  activeSessionCards.value = reviewStore.filteredCards.filter(c => c.status === 'due' || !c.due_date)
  if (activeSessionCards.value.length === 0) {
    activeSessionCards.value = [...reviewStore.filteredCards]
  }
  currentReviewIndex.value = 0
  typedReviewWord.value = ''
  reviewFeedback.value = null
  isMeaningRevealed.value = false
  isReviewingSession.value = true

  if (activeSessionCards.value[0]?.word) {
    speech.speak(activeSessionCards.value[0].word)
  }
}

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

const handleReviewAnswer = (isSuccess) => {
  if (currentActiveReviewCard.value) {
    reviewStore.recordReviewResult(currentActiveReviewCard.value.id, isSuccess)
  }
  currentReviewIndex.value++
  typedReviewWord.value = ''
  reviewFeedback.value = null
  isMeaningRevealed.value = false

  if (currentActiveReviewCard.value?.word) {
    speech.speak(currentActiveReviewCard.value.word)
  }
}
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
</style>
