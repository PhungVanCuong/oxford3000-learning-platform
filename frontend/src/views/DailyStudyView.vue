<template>
  <div class="container py-4 max-w-6xl">
    <!-- Header with Dynamic Setting summary -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">
            CHINH PHỤC 3000 TỪ VỰNG OXFORD
          </span>
          <span class="badge bg-success-subtle text-success border border-success-subtle fs-8">
            {{ dailyStore.wordsPerDay }} từ / ngày • {{ dailyStore.totalDays }} ngày (Mở khóa 100%)
          </span>
        </div>
        <h2 class="fw-black text-teal mb-0">Học Từ Vựng Theo Ngày</h2>
      </div>

      <div class="d-flex gap-2">
        <router-link to="/profile" class="btn btn-outline-teal btn-sm rounded-pill px-3 py-1.5 shadow-sm">
          <i class="bi bi-gear-fill me-1"></i> Đổi số từ/ngày
        </router-link>
      </div>
    </div>

    <!-- VIEW 1: DAY DETAIL VIEW (When viewing words of a specific day) -->
    <div v-if="activeDayView !== null">
      <!-- Top Actions Bar for Current Day -->
      <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
          <div class="d-flex align-items-center gap-3">
            <button @click="closeDayView" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 36px; height: 36px;">
              <i class="bi bi-arrow-left"></i>
            </button>
            <div>
              <h4 class="fw-bold text-teal mb-0">Ngày {{ activeDayView }} - {{ dailyStore.currentDayWords.length }} Từ Vựng</h4>
              <div class="text-muted fs-8">Đã thuộc: {{ masteredInActiveDay }} / {{ dailyStore.currentDayWords.length }} từ ({{ progressInActiveDay }}%)</div>
            </div>
          </div>

          <!-- PROMINENT BUTTON: KIỂM TRA NGAY 15 CÂU (OPENS BOOTSTRAP MODAL) -->
          <div class="d-flex align-items-center gap-2 w-100 w-md-auto">
            <button
              @click="openDailyQuiz(activeDayView)"
              class="btn btn-teal btn-lg rounded-pill px-4 py-2.5 fw-black shadow-lg d-flex align-items-center justify-content-center gap-2 flex-grow-1 flex-md-grow-0"
              style="background: linear-gradient(135deg, #064e40 0%, #0d7362 100%);"
            >
              <i class="bi bi-patch-check-fill text-warning fs-5"></i>
              <span>Kiểm tra ngay ({{ dailyStore.currentDayWords.length }} câu)</span>
            </button>
          </div>
        </div>

        <!-- Mode Switcher: Flashcard vs Table List -->
        <div class="d-flex gap-2 mt-3 pt-3 border-top">
          <button
            @click="dayStudyMode = 'flashcard'"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
            :class="dayStudyMode === 'flashcard' ? 'btn-teal' : 'btn-outline-secondary'"
          >
            <i class="bi bi-card-text me-1"></i> Thẻ Flashcard 3D & Phím tắt
          </button>
          <button
            @click="dayStudyMode = 'list'"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
            :class="dayStudyMode === 'list' ? 'btn-teal' : 'btn-outline-secondary'"
          >
            <i class="bi bi-list-ul me-1"></i> Danh sách từ vựng chi tiết
          </button>
        </div>
      </div>

      <!-- Mode A: Flashcard 3D Player -->
      <div v-if="dayStudyMode === 'flashcard'">
        <FlashcardPlayer
          :words="dailyStore.currentDayWords"
          @status-updated="handleWordStatusUpdated"
        />
      </div>

      <!-- Mode B: Table List -->
      <div v-else class="card rounded-4 border shadow-sm overflow-hidden bg-card-custom mb-4">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light fs-8 text-uppercase">
              <tr>
                <th style="width: 40px;">#</th>
                <th>Từ vựng</th>
                <th>Phiên âm</th>
                <th>Từ loại</th>
                <th>Nghĩa tiếng Việt</th>
                <th>Ví dụ song ngữ</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Âm thanh</th>
              </tr>
            </thead>
            <tbody class="fs-7">
              <tr v-for="(word, wIdx) in dailyStore.currentDayWords" :key="word.id">
                <td class="text-muted fw-bold">{{ wIdx + 1 }}</td>
                <td class="fw-bold text-teal fs-6">{{ word.word }}</td>
                <td class="font-monospace text-muted">{{ word.phonetic_us }}</td>
                <td><span class="badge bg-secondary-subtle text-secondary">{{ word.part_of_speech }}</span></td>
                <td class="fw-semibold text-success">{{ word.meaning_vi }}</td>
                <td class="fs-8 text-muted">
                  <div class="text-dark-emphasis">{{ word.example_en }}</div>
                  <div class="fst-italic">{{ word.example_vi }}</div>
                </td>
                <td class="text-center">
                  <span class="badge" :class="word.status === 'mastered' ? 'bg-success' : word.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary'">
                    {{ word.status === 'mastered' ? 'Đã nhớ' : word.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                  </span>
                </td>
                <td class="text-center">
                  <button @click="speech.speak(word.word)" class="btn btn-sm btn-outline-teal rounded-circle py-1 px-2" title="Nghe phát âm">
                    <i class="bi bi-volume-up-fill"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- VIEW 2: DAYS GRID (200 / 150 Days) with Multi-Day Selection -->
    <div v-else>
      <!-- Multi-Day Selection Floating Bar -->
      <div v-if="dailyStore.selectedMultiDays.length > 0" class="alert alert-info border-info d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 p-3 rounded-4 shadow mb-4 sticky-top" style="top: 70px; z-index: 30; background-color: #ecfdf5;">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-check2-square fs-4 text-teal"></i>
          <div>
            <strong class="text-teal">Đã chọn {{ dailyStore.selectedMultiDays.length }} ngày:</strong>
            <span class="text-muted ms-1">Ngày {{ dailyStore.selectedMultiDays.join(', ') }}</span>
          </div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <button @click="startMultiDayFlashcard" class="btn btn-teal btn-sm rounded-pill px-3 py-2 fw-bold shadow-sm">
            <i class="bi bi-card-text me-1"></i> Học Flashcard các ngày đã chọn
          </button>
          <button @click="startMultiDayQuiz" class="btn btn-warning btn-sm rounded-pill px-3 py-2 fw-bold shadow-sm text-dark">
            <i class="bi bi-patch-check-fill me-1"></i> Làm kiểm tra tổng hợp
          </button>
          <button @click="dailyStore.clearSelectedDays" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-1.5">
            Bỏ chọn
          </button>
        </div>
      </div>

      <!-- Quick Days Selector Filter -->
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-muted fs-7">
          Nhấp vào ô ngày để vào học chi tiết hoặc đánh dấu tích để ôn tập gộp nhiều ngày.
        </span>
        <div class="badge bg-teal-dark text-white px-3 py-1.5 rounded-pill fs-8">
          Tất cả các ngày đều mở khóa 100%
        </div>
      </div>

      <!-- Days Grid -->
      <div class="row g-3">
        <div v-for="d in dailyStore.days" :key="d.day" class="col-6 col-sm-4 col-md-3 col-lg-2">
          <div
            class="card p-3 rounded-4 border shadow-sm h-100 position-relative transition-all"
            :class="{
              'border-success bg-success-subtle': d.progress_percent >= 80,
              'bg-card-custom': d.progress_percent < 80,
              'ring-2 ring-teal': dailyStore.selectedMultiDays.includes(d.day)
            }"
            style="cursor: pointer;"
            @click="openDay(d.day)"
          >
            <!-- Checkbox to select multiple days -->
            <div class="position-absolute top-0 end-0 p-2" @click.stop>
              <input
                type="checkbox"
                class="form-check-input"
                :checked="dailyStore.selectedMultiDays.includes(d.day)"
                @change="dailyStore.toggleSelectDay(d.day)"
                title="Chọn ngày này để ôn gộp"
              />
            </div>

            <div class="fw-black fs-5 text-teal mb-1">
              Ngày {{ d.day }}
            </div>

            <div class="text-muted fs-8 mb-2">
              {{ d.mastered_words }}/{{ d.total_words }} từ ({{ d.progress_percent }}%)
            </div>

            <!-- Progress Bar -->
            <div class="progress" style="height: 6px;">
              <div
                class="progress-bar rounded-pill"
                role="progressbar"
                :style="{ width: `${d.progress_percent}%`, backgroundColor: d.progress_percent >= 80 ? '#10b981' : '#0d7362' }"
              ></div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top fs-8 text-muted">
              <span>{{ d.words_count || dailyStore.wordsPerDay }} từ</span>
              <span v-if="d.progress_percent >= 80" class="text-success fw-bold">
                <i class="bi bi-check-circle-fill"></i> Hoàn thành
              </span>
              <span v-else class="text-teal">
                Học ngay →
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- DAILY QUIZ MODAL COMPONENT (15 Questions Modal) -->
    <DailyQuizModal
      :quizData="dailyQuizData"
      @quiz-completed="handleQuizCompleted"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useDailyStudyStore } from '@/stores/dailyStudyStore'
import { useSpeech } from '@/composables/useSpeech'
import FlashcardPlayer from '@/components/FlashcardPlayer.vue'
import DailyQuizModal from '@/components/DailyQuizModal.vue'
import * as bootstrap from 'bootstrap'

const dailyStore = useDailyStudyStore()
const speech = useSpeech()

const activeDayView = ref(null)
const dayStudyMode = ref('flashcard') // 'flashcard' | 'list'
const dailyQuizData = ref(null)

const masteredInActiveDay = computed(() => {
  return dailyStore.currentDayWords.filter(w => w.status === 'mastered').length
})

const progressInActiveDay = computed(() => {
  if (dailyStore.currentDayWords.length === 0) return 0
  return Math.round((masteredInActiveDay.value / dailyStore.currentDayWords.length) * 100)
})

const openDay = async (dayNumber) => {
  activeDayView.value = dayNumber
  await dailyStore.fetchDayWords(dayNumber)
}

const closeDayView = () => {
  activeDayView.value = null
  dailyStore.fetchDays()
}

const openDailyQuiz = async (dayNumber) => {
  const quiz = await dailyStore.loadDayQuiz(dayNumber)
  dailyQuizData.value = quiz
  const modalEl = document.getElementById('dailyQuizModal')
  if (modalEl) {
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl)
    modal.show()
  }
}

const startMultiDayFlashcard = async () => {
  const words = await dailyStore.loadMultiDay('flashcard')
  if (words && words.length > 0) {
    dailyStore.currentDayWords = words
    activeDayView.value = `Gộp: ${dailyStore.selectedMultiDays.join(', ')}`
  }
}

const startMultiDayQuiz = async () => {
  const quiz = await dailyStore.loadMultiDay('quiz')
  if (quiz) {
    dailyQuizData.value = quiz
    const modalEl = document.getElementById('dailyQuizModal')
    if (modalEl) {
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl)
      modal.show()
    }
  }
}

const handleWordStatusUpdated = async ({ wordId, status }) => {
  await dailyStore.markWordStatus(wordId, status)
}

const handleQuizCompleted = () => {
  dailyStore.fetchDays()
  if (activeDayView.value) {
    dailyStore.fetchDayWords(activeDayView.value)
  }
}

onMounted(() => {
  dailyStore.fetchDays()
})
</script>
