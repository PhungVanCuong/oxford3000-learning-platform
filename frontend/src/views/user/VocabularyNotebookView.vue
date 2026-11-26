<template>
  <div class="container py-3 py-md-4 max-w-6xl vocabulary-notebook-page">
    <!-- 1. Header Banner -->
    <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-3 mb-md-4" style="border-left: 6px solid #00b074;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-8 fw-bold">
              <i class="bi bi-bookmark-star-fill me-1"></i> SỔ TAY CÁ NHÂN
            </span>
            <span class="badge bg-teal text-white rounded-pill px-2.5 py-1 fs-8 fw-bold">
              {{ words.length }} từ vựng đã lưu
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-8">
              {{ masteredCount }} đã thuộc ({{ percentMastered }}%)
            </span>
          </div>
          <h2 class="fs-4 fs-md-3 fw-black text-teal mb-1">
            Sổ Tay Từ Vựng & Kho Flashcard Riêng
          </h2>
          <p class="text-muted fs-7 mb-0">
            Lưu trữ từ mới khi bôi đen bất kỳ đâu trên web, tự thêm từ mới và ôn tập phản xạ chủ động.
          </p>
        </div>

        <div class="d-flex align-items-center gap-2 w-100 w-md-auto flex-wrap">
          <button
            @click="syncAllToReviewDeck"
            :disabled="words.length === 0"
            class="btn btn-outline-teal rounded-pill px-3.5 py-2 fw-bold fs-8 d-flex align-items-center gap-1.5"
            title="Đồng bộ toàn bộ từ vào Bộ thẻ Spaced Repetition"
          >
            <i class="bi bi-arrow-repeat"></i>
            <span>Đồng bộ sang Bộ Thẻ</span>
          </button>

          <button
            @click="openAddModal"
            class="btn btn-teal text-white rounded-pill px-3.5 py-2 fw-bold fs-8 shadow-sm d-flex align-items-center gap-1.5"
          >
            <i class="bi bi-plus-circle-fill"></i>
            <span>Thêm từ mới</span>
          </button>
        </div>
      </div>
    </div>

    <!-- 2. Controls: Modes (Danh sách | Lật thẻ 3D | Trắc nghiệm) & Filters & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2.5 mb-3">
      <!-- Mode Tabs -->
      <div class="btn-group p-1 rounded-pill bg-body-tertiary border shadow-2xs">
        <button
          @click="activeMode = 'list'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 transition-all"
          :class="activeMode === 'list' ? 'btn-teal text-white shadow-xs' : 'text-muted'"
        >
          <i class="bi bi-list-ul me-1"></i> Danh sách ({{ filteredWords.length }})
        </button>
        <button
          @click="activeMode = 'flashcard'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 transition-all"
          :class="activeMode === 'flashcard' ? 'btn-teal text-white shadow-xs' : 'text-muted'"
          :disabled="words.length === 0"
        >
          <i class="bi bi-card-heading me-1"></i> Lật thẻ 3D ({{ words.length }})
        </button>
        <button
          @click="activeMode = 'quiz'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 transition-all"
          :class="activeMode === 'quiz' ? 'btn-teal text-white shadow-xs' : 'text-muted'"
          :disabled="words.length < 3"
        >
          <i class="bi bi-check2-circle me-1"></i> Trắc nghiệm phản xạ
        </button>
      </div>

      <!-- Filters & Search in List mode -->
      <div v-if="activeMode === 'list'" class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Filter Status Dropdown -->
        <select v-model="filterStatus" class="form-select form-select-sm rounded-pill fs-8 border shadow-2xs" style="width: auto;">
          <option value="all">Tất cả từ ({{ words.length }})</option>
          <option value="learning">Đang học / Cần ôn ({{ words.length - masteredCount }})</option>
          <option value="mastered">Đã thuộc ({{ masteredCount }})</option>
        </select>

        <!-- Search Input -->
        <div class="input-group input-group-sm" style="min-width: 200px; max-width: 280px;">
          <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted">
            <i class="bi bi-search"></i>
          </span>
          <input
            v-model="searchQuery"
            type="text"
            class="form-control border-start-0 rounded-end-pill fs-8"
            placeholder="Tìm từ, nghĩa, phiên âm..."
          />
        </div>
      </div>
    </div>

    <!-- 3. SKELETON LOADING STATE -->
    <div v-if="isLoading" class="row g-3">
      <div v-for="n in 6" :key="n" class="col-12 col-md-6 col-xl-4">
        <div class="card p-3 rounded-4 border bg-card-custom h-100 shadow-2xs">
          <div class="d-flex justify-content-between mb-2">
            <div class="ox-skeleton" style="width: 120px; height: 24px;"></div>
            <div class="ox-skeleton rounded-circle" style="width: 28px; height: 28px;"></div>
          </div>
          <div class="ox-skeleton mb-2" style="width: 80px; height: 16px;"></div>
          <div class="ox-skeleton mb-3" style="width: 100%; height: 38px;"></div>
          <div class="ox-skeleton mt-auto" style="width: 100%; height: 48px;"></div>
        </div>
      </div>
    </div>

    <!-- 4. EMPTY STATE -->
    <div v-else-if="words.length === 0" class="card p-5 rounded-4 text-center border-dashed bg-card-custom my-4">
      <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex p-3 mx-auto mb-3" style="width: 64px; height: 64px; align-items: center; justify-content: center;">
        <i class="bi bi-journal-plus fs-2"></i>
      </div>
      <h5 class="fw-bold mb-1">Sổ tay của bạn đang trống!</h5>
      <p class="text-muted fs-7 max-w-md mx-auto mb-3">
        Hãy bôi đen bất kỳ từ tiếng Anh nào trên web để tra cứu và bấm <strong>[+ Sổ tay]</strong>, hoặc bấm nút thêm từ bên dưới.
      </p>
      <div>
        <button @click="openAddModal" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7 shadow-xs">
          <i class="bi bi-plus-lg me-1"></i> Thêm từ đầu tiên ngay
        </button>
      </div>
    </div>

    <!-- 5. MODE 1: LIST VIEW (WITH STAGGERED FADE-IN ANIMATION & OVERFLOW PROTECTION) -->
    <div v-else-if="activeMode === 'list'" class="row g-2.5">
      <div
        v-for="(item, idx) in filteredWords"
        :key="item.id || idx"
        class="col-12 col-md-6 col-xl-4 anim-staggered-item"
        :style="{ animationDelay: `${Math.min(idx * 0.04, 0.4)}s` }"
      >
        <div
          class="card p-3 rounded-4 border bg-card-custom h-100 shadow-2xs transition-all position-relative"
          :class="item.mastered ? 'border-success-subtle' : 'border-light-subtle'"
        >
          <!-- Top Row: Word & Badges & Action Buttons -->
          <div class="d-flex justify-content-between align-items-start gap-2 mb-1.5">
            <div class="min-w-0 flex-grow-1">
              <div class="d-flex align-items-center gap-1.5 flex-wrap">
                <span class="fw-black fs-5 text-teal text-truncate">{{ item.word }}</span>
                <span v-if="item.pos" class="badge bg-secondary-subtle text-secondary-emphasis fs-9 rounded-pill text-uppercase">
                  {{ item.pos }}
                </span>
                <span v-if="item.mastered" class="badge bg-success-subtle text-success fs-9 rounded-pill border border-success-subtle">
                  ✓ Đã thuộc
                </span>
              </div>
              <div class="text-muted fs-8 font-monospace mt-0.5" v-if="item.phonetic">
                {{ item.phonetic }}
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center gap-1 flex-shrink-0">
              <!-- TTS Play Button with Wave Animation -->
              <button
                @click="speech.speak(item.word)"
                class="btn btn-sm btn-outline-teal rounded-circle p-1 d-flex align-items-center justify-content-center"
                style="width: 30px; height: 30px;"
                title="Nghe phát âm chuẩn US"
              >
                <i class="bi bi-volume-up-fill fs-8"></i>
              </button>

              <!-- Mark Mastered / Learning -->
              <button
                @click="toggleMastered(item.id)"
                class="btn btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center"
                :class="item.mastered ? 'btn-success text-white' : 'btn-outline-secondary'"
                style="width: 30px; height: 30px;"
                :title="item.mastered ? 'Đánh dấu cần ôn lại' : 'Đánh dấu đã thuộc'"
              >
                <i class="bi bi-check-lg fs-8"></i>
              </button>

              <!-- Push to Review Deck -->
              <button
                @click="pushToReviewDeck(item)"
                class="btn btn-sm btn-outline-warning rounded-circle p-1 d-flex align-items-center justify-content-center"
                style="width: 30px; height: 30px;"
                title="Thêm vào Bộ thẻ Spaced Repetition"
              >
                <i class="bi bi-collection-play fs-8"></i>
              </button>

              <!-- Delete button -->
              <button
                @click="removeWord(item.id)"
                class="btn btn-sm btn-outline-danger rounded-circle p-1 d-flex align-items-center justify-content-center"
                style="width: 30px; height: 30px;"
                title="Xóa khỏi sổ tay"
              >
                <i class="bi bi-trash3 fs-8"></i>
              </button>
            </div>
          </div>

          <!-- Meaning Text (Protected from overflow) -->
          <div class="fw-semibold text-dark-emphasis fs-7 mb-2 ox-text-break leading-snug">
            {{ item.meaning }}
          </div>

          <!-- Example Box -->
          <div v-if="item.example" class="p-2.5 rounded-3 bg-body-tertiary border border-light-subtle fs-8 text-secondary mt-auto ox-text-break">
            <div class="d-flex align-items-center justify-content-between mb-0.5">
              <span class="fs-9 fw-bold text-muted text-uppercase">Ví dụ thực tế:</span>
              <button @click="speech.speak(item.example)" class="btn btn-xs btn-link p-0 text-teal text-decoration-none" title="Nghe câu ví dụ">
                <i class="bi bi-volume-up fs-8"></i>
              </button>
            </div>
            <div class="fst-italic text-dark-emphasis leading-relaxed">"{{ item.example }}"</div>
            <div v-if="item.example_vi" class="text-secondary fs-9 mt-1">→ {{ item.example_vi }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- 6. MODE 2: FLASHCARD 3D WITH 3D ROTATION & SHADOW -->
    <div v-else-if="activeMode === 'flashcard'" class="d-flex flex-column align-items-center py-2">
      <div class="w-100 max-w-lg">
        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
          <span class="fs-8 fw-bold text-muted">Thẻ {{ currentCardIdx + 1 }} / {{ words.length }}</span>
          <span class="badge bg-teal text-white rounded-pill fs-8">Sổ tay cá nhân</span>
        </div>
        <div class="progress mb-3" style="height: 6px;">
          <div
            class="progress-bar bg-teal"
            :style="{ width: ((currentCardIdx + 1) / words.length * 100) + '%' }"
          ></div>
        </div>

        <!-- 3D Card Box with Realistic Depth -->
        <div
          class="notebook-flashcard-box cursor-pointer"
          @click="isFlipped = !isFlipped"
          :class="{ 'is-flipped': isFlipped }"
        >
          <div class="notebook-flashcard-inner">
            <!-- Front Face: English -->
            <div class="notebook-face notebook-front p-4 rounded-4 shadow-lg border bg-card-custom d-flex flex-column justify-content-between text-center">
              <div class="d-flex justify-content-between align-items-center w-100">
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 fs-9 text-uppercase">
                  {{ currentCard?.pos || 'TỪ MỚI' }}
                </span>
                <button
                  @click.stop="speech.speak(currentCard?.word)"
                  class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1 fs-8 fw-bold d-flex align-items-center gap-1 shadow-2xs"
                >
                  <i class="bi bi-volume-up-fill"></i> US
                </button>
              </div>

              <div class="my-auto py-4 ox-text-break">
                <h2 class="display-5 fw-black text-teal mb-1">{{ currentCard?.word }}</h2>
                <div class="fs-6 font-monospace text-muted" v-if="currentCard?.phonetic">
                  {{ currentCard?.phonetic }}
                </div>
              </div>

              <div class="text-muted fs-8">
                <i class="bi bi-arrow-repeat me-1"></i> Bấm để lật thẻ xem nghĩa & gõ nhớ từ
              </div>
            </div>

            <!-- Back Face: Vietnamese Meaning + Active Recall -->
            <div class="notebook-face notebook-back p-4 rounded-4 shadow-lg border bg-dark text-white d-flex flex-column justify-content-between" @click.stop>
              <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-9 fw-bold">
                    ĐÁP ÁN & NGHĨA
                  </span>
                  <button
                    @click.stop="isFlipped = false"
                    class="btn btn-sm btn-outline-light rounded-pill px-2.5 py-0.5 fs-8"
                  >
                    <i class="bi bi-arrow-return-left me-1"></i> Mặt trước
                  </button>
                </div>
                <h4 class="fw-black text-warning mb-1 ox-text-break">{{ currentCard?.meaning }}</h4>
                <div v-if="currentCard?.example" class="p-2.5 rounded-3 bg-secondary-subtle/20 border border-secondary fs-8 mt-2 text-light ox-text-break">
                  <div class="fst-italic text-info-emphasis">"{{ currentCard.example }}"</div>
                  <div v-if="currentCard.example_vi" class="text-light-emphasis fs-9 mt-0.5">→ {{ currentCard.example_vi }}</div>
                </div>
              </div>

              <!-- Active Recall: Nhập lại từ tiếng Anh -->
              <div class="my-3 p-3 rounded-3 border border-secondary" style="background: rgba(255, 255, 255, 0.05);">
                <div class="d-flex justify-content-between align-items-center mb-1.5">
                  <label class="fs-8 fw-bold text-light mb-0">✍️ Gõ lại từ tiếng Anh:</label>
                  <button
                    @click.stop="revealCardSpelling"
                    type="button"
                    class="btn btn-xs rounded-pill px-2.5 py-1 fw-bold fs-9 shadow-xs"
                    style="border: 1.5px solid #22c55e; color: #22c55e; background: rgba(34, 197, 94, 0.1);"
                  >
                    <i class="bi bi-eye-fill me-1"></i> Xem đáp án
                  </button>
                </div>
                <div class="input-group input-group-sm">
                  <input
                    v-model="cardSpellingInput"
                    @keyup.enter="checkCardSpelling"
                    type="text"
                    class="form-control rounded-start-pill fs-7 text-white font-monospace"
                    style="background: rgba(0, 0, 0, 0.35); border-color: #4b5563;"
                    placeholder="Nhập từ chính xác..."
                    autocomplete="off"
                  />
                  <button @click.stop="checkCardSpelling" class="btn btn-teal text-white rounded-end-pill px-3 fw-bold">
                    Kiểm tra
                  </button>
                </div>

                <div v-if="cardSpellingFeedback === 'correct'" class="text-success fs-8 fw-bold mt-2">
                  <i class="bi bi-check-circle-fill me-1"></i> Chính xác tuyệt đối!
                </div>
                <div v-else-if="cardSpellingFeedback === 'wrong'" class="text-danger fs-8 fw-bold mt-2">
                  <i class="bi bi-x-circle-fill me-1"></i> Chưa chuẩn! Bấm [Xem đáp án] hoặc thử lại.
                </div>
                <div v-else-if="cardSpellingFeedback === 'revealed'" class="text-warning fs-8 fw-bold mt-2">
                  Từ đúng là: <span class="text-white">{{ currentCard?.word }}</span>
                </div>
              </div>

              <!-- Controls: Trước / Tiếp -->
              <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary">
                <button @click.stop="prevCard" :disabled="currentCardIdx === 0" class="btn btn-sm btn-outline-light rounded-pill px-3">
                  ← Trước
                </button>
                <button
                  @click.stop="toggleMastered(currentCard?.id)"
                  class="btn btn-sm rounded-pill px-3"
                  :class="currentCard?.mastered ? 'btn-success text-white' : 'btn-outline-warning text-warning'"
                >
                  {{ currentCard?.mastered ? '✓ Đã thuộc' : 'Đánh dấu đã thuộc' }}
                </button>
                <button @click.stop="nextCard" :disabled="currentCardIdx >= words.length - 1" class="btn btn-sm btn-teal text-white rounded-pill px-3">
                  Tiếp →
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 7. MODE 3: TRẮC NGHIỆM PHẢN XẠ -->
    <div v-else-if="activeMode === 'quiz'" class="d-flex flex-column align-items-center py-2">
      <div class="w-100 max-w-lg card p-4 rounded-4 shadow-sm border bg-card-custom">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <span class="fs-8 fw-bold text-muted">Câu {{ quizIdx + 1 }} / {{ quizQuestions.length }}</span>
          <span class="badge bg-warning text-dark rounded-pill fs-8">Điểm: {{ quizScore }} / {{ quizQuestions.length }}</span>
        </div>

        <div v-if="currentQuizQ">
          <div class="text-center py-3">
            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-0.5 fs-9 text-uppercase mb-2">
              TỪ TIẾNG ANH
            </span>
            <h3 class="fw-black text-teal mb-1 ox-text-break">{{ currentQuizQ.word }}</h3>
            <div class="text-muted fs-8 font-monospace mb-2">{{ currentQuizQ.phonetic }}</div>
            <button @click="speech.speak(currentQuizQ.word)" class="btn btn-xs btn-outline-teal rounded-pill px-2.5 py-1 fs-9">
              <i class="bi bi-volume-up-fill me-1"></i> Nghe
            </button>
          </div>

          <!-- Options -->
          <div class="d-flex flex-column gap-2 my-3">
            <button
              v-for="(opt, oIdx) in currentQuizQ.options"
              :key="oIdx"
              @click="selectQuizAnswer(opt)"
              :disabled="quizAnswerSubmitted"
              class="btn text-start p-3 rounded-3 border transition-all fs-7 ox-text-break"
              :class="getQuizOptionClass(opt)"
            >
              <div class="d-flex align-items-center justify-content-between">
                <span>{{ opt }}</span>
                <i v-if="quizAnswerSubmitted && opt === currentQuizQ.meaning" class="bi bi-check-circle-fill text-success fs-6"></i>
                <i v-else-if="quizAnswerSubmitted && opt === quizSelectedOption && opt !== currentQuizQ.meaning" class="bi bi-x-circle-fill text-danger fs-6"></i>
              </div>
            </button>
          </div>

          <div v-if="quizAnswerSubmitted" class="text-center pt-2">
            <button @click="nextQuizQuestion" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7 shadow-xs">
              {{ quizIdx < quizQuestions.length - 1 ? 'Câu tiếp theo →' : 'Xem kết quả bài tập' }}
            </button>
          </div>
        </div>

        <div v-else class="text-center py-4">
          <i class="bi bi-trophy-fill text-warning display-4 d-block mb-2"></i>
          <h4 class="fw-bold mb-1">Hoàn thành bài trắc nghiệm!</h4>
          <p class="text-muted fs-7 mb-3">Bạn đạt được {{ quizScore }} / {{ quizQuestions.length }} câu đúng.</p>
          <button @click="startQuiz" class="btn btn-teal text-white rounded-pill px-4 py-2 fw-bold fs-7">
            Luyện tập lại
          </button>
        </div>
      </div>
    </div>

    <!-- 8. MODAL: THÊM TỪ MỚI THỦ CÔNG -->
    <div v-if="isAddModalOpen" class="modal show d-block" style="background: rgba(0,0,0,0.65); z-index: 1085;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-2xl bg-card-custom">
          <div class="modal-header border-bottom">
            <h5 class="modal-title fw-black text-teal">
              <i class="bi bi-journal-plus me-1.5"></i> Thêm từ vựng vào Sổ tay
            </h5>
            <button @click="isAddModalOpen = false" class="btn-close"></button>
          </div>
          <form @submit.prevent="saveNewWord">
            <div class="modal-body p-4">
              <div class="mb-2.5">
                <label class="form-label fs-8 fw-bold text-muted">Từ tiếng Anh (*)</label>
                <input v-model="newWordForm.word" required type="text" class="form-control rounded-3 fs-7" placeholder="e.g. resilient" />
              </div>
              <div class="row g-2 mb-2.5">
                <div class="col-7">
                  <label class="form-label fs-8 fw-bold text-muted">Phiên âm (IPA)</label>
                  <input v-model="newWordForm.phonetic" type="text" class="form-control rounded-3 fs-7 font-monospace" placeholder="/rɪˈzɪliənt/" />
                </div>
                <div class="col-5">
                  <label class="form-label fs-8 fw-bold text-muted">Loại từ</label>
                  <select v-model="newWordForm.pos" class="form-select rounded-3 fs-7">
                    <option value="danh từ">Danh từ</option>
                    <option value="động từ">Động từ</option>
                    <option value="tính từ">Tính từ</option>
                    <option value="trạng từ">Trạng từ</option>
                    <option value="cụm từ">Cụm từ</option>
                  </select>
                </div>
              </div>
              <div class="mb-2.5">
                <label class="form-label fs-8 fw-bold text-muted">Nghĩa tiếng Việt (*)</label>
                <input v-model="newWordForm.meaning" required type="text" class="form-control rounded-3 fs-7" placeholder="Kiên cường, hồi phục nhanh" />
              </div>
              <div class="mb-2.5">
                <label class="form-label fs-8 fw-bold text-muted">Câu ví dụ tiếng Anh</label>
                <input v-model="newWordForm.example" type="text" class="form-control rounded-3 fs-7" placeholder="She is a resilient woman." />
              </div>
              <div>
                <label class="form-label fs-8 fw-bold text-muted">Dịch nghĩa câu ví dụ</label>
                <input v-model="newWordForm.example_vi" type="text" class="form-control rounded-3 fs-7" placeholder="Cô ấy là một người phụ nữ kiên cường." />
              </div>
            </div>
            <div class="modal-footer border-top pt-2">
              <button @click="isAddModalOpen = false" type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Hủy</button>
              <button type="submit" class="btn btn-sm btn-teal text-white rounded-pill px-3.5 fw-bold shadow-xs">Lưu vào Sổ tay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useSpeech } from '@/composables/useSpeech'
import { useReviewStore } from '@/stores/reviewStore'
import { useToast } from '@/composables/useToast'

const STORAGE_KEY = 'oxford_user_notebook'

const speech = useSpeech()
const reviewStore = useReviewStore()
const toast = useToast()

const DEFAULT_SAMPLE_WORDS = [
  { id: 1, word: 'resilient', phonetic: '/rɪˈzɪliənt/', pos: 'tính từ', meaning: 'Kiên cường, có khả năng hồi phục nhanh sau khó khăn', example: 'She is a resilient woman who overcomes every obstacle.', example_vi: 'Cô ấy là một người phụ nữ kiên cường vượt qua mọi trở ngại.', mastered: true, created_at: Date.now() - 86400000 },
  { id: 2, word: 'meticulous', phonetic: '/məˈtɪkjələs/', pos: 'tính từ', meaning: 'Tỉ mỉ, cẩn trọng từng chi tiết nhỏ', example: 'His meticulous research helped solve the problem.', example_vi: 'Nghiên cứu tỉ mỉ của anh ấy đã giúp giải quyết vấn đề.', mastered: false, created_at: Date.now() - 43200000 },
  { id: 3, word: 'breakthrough', phonetic: '/ˈbreɪkθruː/', pos: 'danh từ', meaning: 'Bước đột phá, phát kiến mang tính cách mạng', example: 'Scientists made a major breakthrough in cancer treatment.', example_vi: 'Các nhà khoa học đã đạt bước đột phá lớn trong điều trị ung thư.', mastered: false, created_at: Date.now() },
  { id: 4, word: 'persuasive', phonetic: '/pərˈsweɪsɪv/', pos: 'tính từ', meaning: 'Có sức thuyết phục, lôi cuốn người nghe', example: 'She presented a persuasive argument in the debate.', example_vi: 'Cô ấy đã trình bày một lập luận thuyết phục trong buổi tranh luận.', mastered: true, created_at: Date.now() }
]

const words = ref([])
const isLoading = ref(true)
const activeMode = ref('list') // 'list' | 'flashcard' | 'quiz'
const searchQuery = ref('')
const filterStatus = ref('all') // 'all' | 'learning' | 'mastered'

const isAddModalOpen = ref(false)
const newWordForm = ref({
  word: '',
  phonetic: '',
  pos: 'danh từ',
  meaning: '',
  example: '',
  example_vi: ''
})

// Flashcard 3D State
const currentCardIdx = ref(0)
const isFlipped = ref(false)
const cardSpellingInput = ref('')
const cardSpellingFeedback = ref(null)

// Quiz State
const quizIdx = ref(0)
const quizScore = ref(0)
const quizAnswerSubmitted = ref(false)
const quizSelectedOption = ref(null)
const quizQuestions = ref([])

const loadNotebook = async () => {
  isLoading.value = true
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    const localWords = raw ? JSON.parse(raw) : DEFAULT_SAMPLE_WORDS
    words.value = Array.isArray(localWords) ? localWords : DEFAULT_SAMPLE_WORDS

    if (localStorage.getItem('oxford_token')) {
      const res = await axios.get('/api/user/notebook')
      const remoteWords = res.data?.data
      if (Array.isArray(remoteWords) && remoteWords.length > 0) {
        words.value = remoteWords
        localStorage.setItem(STORAGE_KEY, JSON.stringify(words.value))
      } else if (words.value.length > 0) {
        await axios.post('/api/user/notebook/sync', { words: words.value }).catch(() => {})
      }
    }
  } catch (e) {
    if (!words.value.length) words.value = DEFAULT_SAMPLE_WORDS
  } finally {
    isLoading.value = false
  }
}

const saveNotebook = () => {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(words.value))
  if (localStorage.getItem('oxford_token')) {
    axios.post('/api/user/notebook/sync', { words: words.value }).catch(() => {})
  }
}

const masteredCount = computed(() => words.value.filter(w => w.mastered).length)
const percentMastered = computed(() => {
  if (words.value.length === 0) return 0
  return Math.round((masteredCount.value / words.value.length) * 100)
})

const filteredWords = computed(() => {
  let list = words.value

  // Lọc theo trạng thái
  if (filterStatus.value === 'learning') {
    list = list.filter(w => !w.mastered)
  } else if (filterStatus.value === 'mastered') {
    list = list.filter(w => w.mastered)
  }

  // Tìm kiếm
  if (!searchQuery.value.trim()) return list
  const q = searchQuery.value.toLowerCase().trim()
  return list.filter(w =>
    (w.word || '').toLowerCase().includes(q) ||
    (w.meaning || '').toLowerCase().includes(q) ||
    (w.phonetic || '').toLowerCase().includes(q)
  )
})

const currentCard = computed(() => {
  if (words.value.length === 0) return null
  return words.value[currentCardIdx.value] || words.value[0]
})

const currentQuizQ = computed(() => {
  if (quizQuestions.value.length === 0) return null
  return quizQuestions.value[quizIdx.value] || null
})

const toggleMastered = (id) => {
  const item = words.value.find(w => w.id === id)
  if (item) {
    item.mastered = !item.mastered
    saveNotebook()
    toast.success(item.mastered ? `Đã đánh dấu thuộc "${item.word}"` : `Chuyển "${item.word}" vào danh sách cần ôn`)
  }
}

const removeWord = (id) => {
  const item = words.value.find(w => w.id === id)
  const name = item ? item.word : 'từ vựng'
  words.value = words.value.filter(w => w.id !== id)
  saveNotebook()
  toast.info(`Đã xóa "${name}" khỏi sổ tay`)
}

const pushToReviewDeck = (item) => {
  if (!item) return
  reviewStore.addWordToReview({
    word: item.word,
    phonetic_us: item.phonetic,
    part_of_speech: item.pos,
    meaning_vi: item.meaning,
    example_en: item.example,
    example_vi: item.example_vi,
    level: 'Sổ tay'
  }, 'Sổ tay từ vựng')
  toast.success(`Đã thêm "${item.word}" vào Bộ thẻ Spaced Repetition!`)
}

const syncAllToReviewDeck = () => {
  let count = 0
  words.value.forEach(item => {
    reviewStore.addWordToReview({
      word: item.word,
      phonetic_us: item.phonetic,
      part_of_speech: item.pos,
      meaning_vi: item.meaning,
      example_en: item.example,
      example_vi: item.example_vi,
      level: 'Sổ tay'
    }, 'Sổ tay từ vựng')
    count++
  })
  toast.success(`Đã đồng bộ ${count} từ vựng sang Bộ thẻ ôn tập!`)
}

const openAddModal = () => {
  newWordForm.value = {
    word: '',
    phonetic: '',
    pos: 'danh từ',
    meaning: '',
    example: '',
    example_vi: ''
  }
  isAddModalOpen.value = true
}

const saveNewWord = () => {
  if (!newWordForm.value.word.trim() || !newWordForm.value.meaning.trim()) return

  const item = {
    id: Date.now(),
    word: newWordForm.value.word.trim(),
    phonetic: newWordForm.value.phonetic.trim(),
    pos: newWordForm.value.pos,
    meaning: newWordForm.value.meaning.trim(),
    example: newWordForm.value.example.trim(),
    example_vi: newWordForm.value.example_vi.trim(),
    mastered: false,
    created_at: Date.now()
  }

  words.value.unshift(item)
  saveNotebook()
  isAddModalOpen.value = false
  toast.success(`Đã thêm từ "${item.word}" vào Sổ tay!`)
}

// Flashcard 3D Navigation
const nextCard = () => {
  if (currentCardIdx.value < words.value.length - 1) {
    currentCardIdx.value++
    isFlipped.value = false
    cardSpellingInput.value = ''
    cardSpellingFeedback.value = null
  }
}

const prevCard = () => {
  if (currentCardIdx.value > 0) {
    currentCardIdx.value--
    isFlipped.value = false
    cardSpellingInput.value = ''
    cardSpellingFeedback.value = null
  }
}

const checkCardSpelling = () => {
  if (!currentCard.value) return
  const input = cardSpellingInput.value.trim().toLowerCase()
  const target = currentCard.value.word.trim().toLowerCase()

  if (input === target) {
    cardSpellingFeedback.value = 'correct'
    currentCard.value.mastered = true
    saveNotebook()
    speech.speak(currentCard.value.word)
  } else {
    cardSpellingFeedback.value = 'wrong'
  }
}

const revealCardSpelling = () => {
  cardSpellingFeedback.value = 'revealed'
  cardSpellingInput.value = currentCard.value?.word || ''
  speech.speak(currentCard.value?.word)
}

// Quiz Generation
const startQuiz = () => {
  if (words.value.length < 3) return
  quizIdx.value = 0
  quizScore.value = 0
  quizAnswerSubmitted.value = false
  quizSelectedOption.value = null

  // Tạo câu hỏi với 4 đáp án xáo trộn
  quizQuestions.value = words.value.slice(0, 10).map(w => {
    const wrongOptions = words.value
      .filter(other => other.id !== w.id)
      .map(o => o.meaning)
      .sort(() => 0.5 - Math.random())
      .slice(0, 3)

    const allOptions = [w.meaning, ...wrongOptions].sort(() => 0.5 - Math.random())

    return {
      word: w.word,
      phonetic: w.phonetic,
      meaning: w.meaning,
      options: allOptions
    }
  })
}

const selectQuizAnswer = (opt) => {
  if (quizAnswerSubmitted.value) return
  quizSelectedOption.value = opt
  quizAnswerSubmitted.value = true

  if (opt === currentQuizQ.value?.meaning) {
    quizScore.value++
  }
}

const getQuizOptionClass = (opt) => {
  if (!quizAnswerSubmitted.value) {
    return 'btn-outline-secondary bg-body-tertiary'
  }
  if (opt === currentQuizQ.value?.meaning) {
    return 'btn-success text-white'
  }
  if (opt === quizSelectedOption.value && opt !== currentQuizQ.value?.meaning) {
    return 'btn-danger text-white'
  }
  return 'btn-outline-secondary opacity-50'
}

const nextQuizQuestion = () => {
  if (quizIdx.value < quizQuestions.value.length - 1) {
    quizIdx.value++
    quizAnswerSubmitted.value = false
    quizSelectedOption.value = null
  } else {
    quizIdx.value++
  }
}

const handleNotebookUpdated = () => {
  loadNotebook()
}

onMounted(() => {
  loadNotebook()
  startQuiz()
  window.addEventListener('notebook-updated', handleNotebookUpdated)
})

onUnmounted(() => {
  window.removeEventListener('notebook-updated', handleNotebookUpdated)
})
</script>

<style scoped>
.notebook-flashcard-box {
  perspective: 1200px;
  width: 100%;
  height: clamp(380px, 58vh, 480px);
  user-select: none;
}

.notebook-flashcard-inner {
  position: relative;
  width: 100%;
  height: 100%;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  transform-style: preserve-3d;
}

.notebook-flashcard-box.is-flipped .notebook-flashcard-inner {
  transform: rotateY(180deg);
}

.notebook-face {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}

.notebook-back {
  transform: rotateY(180deg);
}
</style>
