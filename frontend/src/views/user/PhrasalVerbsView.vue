<template>
  <div class="container py-4 max-w-6xl">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">
            NGỮ PHÁP & TỪ VỰNG KẾT HỢP
          </span>
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-8">
            Ghép Từ Đơn Thành Cụm Có Nghĩa • Đồng Bộ Tiến Độ Tự Động
          </span>
        </div>
        <h2 class="fw-black text-teal mb-0">Học Theo Cụm Từ & Phrasal Verbs</h2>
      </div>

      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fs-7 fw-bold">
          <i class="bi bi-check2-circle me-1"></i> Đã thuộc: {{ masteredCount }} / {{ phrases.length }} cụm
        </span>
      </div>
    </div>

    <!-- Controls & Filters Bar -->
    <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
      <div class="row g-2 align-items-center mb-3">
        <div class="col-12 col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-body-tertiary border-end-0">
              <i class="bi bi-search text-teal"></i>
            </span>
            <input
              v-model="searchQuery"
              @input="fetchPhrases"
              type="text"
              class="form-control border-start-0 py-2 fs-7"
              placeholder="Tìm cụm từ, nghĩa tiếng Việt (VD: look forward to, give up, take care of...)..."
            />
            <button v-if="searchQuery" @click="clearSearch" class="btn btn-outline-secondary border-start-0" type="button">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
        </div>

        <div class="col-6 col-md-3">
          <select v-model="filterType" @change="fetchPhrases" class="form-select py-2 fs-7">
            <option value="all">Tất cả thể loại</option>
            <option value="phrasal_verb">Cụm động từ (Phrasal Verb)</option>
            <option value="collocation">Cụm từ cố định (Collocation)</option>
            <option value="idiom">Thành ngữ (Idiom)</option>
          </select>
        </div>

        <div class="col-6 col-md-3">
          <select v-model="filterStatus" @change="fetchPhrases" class="form-select py-2 fs-7">
            <option value="all">Tất cả tiến độ</option>
            <option value="mastered">Đã nhớ (Mastered)</option>
            <option value="learning">Cần ôn (Learning)</option>
            <option value="unlearned">Chưa học</option>
          </select>
        </div>
      </div>

      <!-- Mode Switcher -->
      <div class="d-flex gap-2 pt-2 border-top">
        <button
          @click="activeMode = 'flashcard'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
          :class="activeMode === 'flashcard' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-card-text me-1"></i> Thẻ Flashcard 3D Cụm Từ
        </button>
        <button
          @click="activeMode = 'list'"
          class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
          :class="activeMode === 'list' ? 'btn-teal' : 'btn-outline-secondary'"
        >
          <i class="bi bi-list-ul me-1"></i> Danh Sách Tra Cứu Chi Tiết ({{ phrases.length }})
        </button>
      </div>
    </div>

    <!-- MODE 1: FLASHCARD 3D PLAYER -->
    <div v-if="activeMode === 'flashcard'">
      <div v-if="phrases.length > 0" class="flashcard-player w-100 max-w-3xl mx-auto my-3">
        <!-- Progress Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted fs-7">
            Cụm từ số <strong>{{ currentPhraseIndex + 1 }}</strong> / {{ phrases.length }}
          </span>
          <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fs-8">
              {{ currentPhrase?.type === 'phrasal_verb' ? 'Phrasal Verb' : currentPhrase?.type === 'collocation' ? 'Collocation' : 'Idiom' }}
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fs-8">
              {{ currentPhrase?.cefr_level || 'B1' }}
            </span>
          </div>
        </div>

        <!-- 3D Card Box -->
        <div
          class="perspective-1000 w-100 cursor-pointer select-none mb-3"
          style="min-height: 380px;"
          @click="isFlipped = !isFlipped"
        >
          <div
            class="position-relative w-100 h-100 rounded-4 shadow-lg border border-2 transform-style-preserve-3d flashcard-3d-inner"
            :class="{ 'rotate-y-180': isFlipped }"
            style="min-height: 380px;"
          >
            <!-- FRONT SIDE -->
            <div
              class="position-absolute top-0 start-0 w-100 h-100 backface-hidden rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between"
              :class="authStore.themeMode === 'dark' ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
            >
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-teal text-white px-3 py-1.5 rounded-pill fs-8">
                  MẶT TRƯỚC • CỤM TỪ & CÁC TỪ GHÉP THÀNH
                </span>
                <span class="badge" :class="currentPhrase?.status === 'mastered' ? 'bg-success' : currentPhrase?.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary'">
                  {{ currentPhrase?.status === 'mastered' ? 'Đã nhớ' : currentPhrase?.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                </span>
              </div>

              <div class="text-center my-auto py-3">
                <h1 class="display-4 fw-black text-teal mb-3 tracking-tight">
                  {{ currentPhrase?.phrase }}
                </h1>

                <!-- Component Words Indicator -->
                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-3">
                  <span class="fs-8 text-muted fw-semibold">Ghép từ các từ đơn:</span>
                  <span
                    v-for="word in currentPhrase?.component_words_json || []"
                    :key="word"
                    class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-7 font-monospace"
                  >
                    + {{ word }}
                  </span>
                </div>

                <!-- Example EN -->
                <div v-if="currentPhrase?.example_en" class="bg-body-tertiary p-3 rounded-3 text-start border my-2" @click.stop>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-teal text-white fs-8">CÂU VÍ DỤ SONG NGỮ</span>
                    <button @click="speech.speak(currentPhrase?.example_en)" class="btn btn-sm btn-outline-teal py-0 px-2 rounded-pill fs-8">
                      <i class="bi bi-volume-up-fill"></i> Nghe câu
                    </button>
                  </div>
                  <p class="fs-6 fw-semibold mb-0 text-teal">{{ currentPhrase?.example_en }}</p>
                </div>
              </div>

              <div class="text-center pt-2 border-top text-muted fs-8">
                <i class="bi bi-arrow-repeat me-1"></i>Nhấp chuột để lật thẻ xem Nghĩa & Dịch tiếng Việt
              </div>
            </div>

            <!-- BACK SIDE -->
            <div
              class="position-absolute top-0 start-0 w-100 h-100 backface-hidden rotate-y-180 rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between"
              :class="authStore.themeMode === 'dark' ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
            >
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fs-8">
                  MẶT SAU • NGHĨA & DỊCH NGHĨA TIẾNG VIỆT
                </span>
                <span class="badge" :class="currentPhrase?.status === 'mastered' ? 'bg-success' : currentPhrase?.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary'">
                  {{ currentPhrase?.status === 'mastered' ? 'Đã nhớ' : currentPhrase?.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                </span>
              </div>

              <div class="my-auto py-3 text-center">
                <div class="text-muted fs-7 mb-1">Nghĩa tiếng Việt của cụm:</div>
                <h2 class="display-6 fw-bold text-success mb-4">
                  {{ currentPhrase?.meaning_vi }}
                </h2>

                <div v-if="currentPhrase?.example_vi" class="bg-body-tertiary p-3 rounded-3 text-start border my-2">
                  <span class="badge bg-secondary text-white fs-8 mb-2">BẢN DỊCH CÂU VÍ DỤ</span>
                  <p class="fs-6 text-dark-emphasis mb-0 fst-italic">
                    "{{ currentPhrase?.example_vi }}"
                  </p>
                </div>

                <div class="alert alert-info py-2 px-3 fs-8 mt-3 mb-0 rounded-3 text-start">
                  <i class="bi bi-info-circle-fill me-1 text-primary"></i>
                  Khi đánh dấu <strong>Đã nhớ</strong> cụm từ này, hệ thống sẽ tự động đồng bộ và cộng điểm cho các từ đơn:
                  <span class="fw-bold">{{ (currentPhrase?.component_words_json || []).join(', ') }}</span> trong kho 3000 từ vựng.
                </div>
              </div>

              <div class="text-center pt-2 border-top text-muted fs-8">
                <i class="bi bi-arrow-repeat me-1"></i>Nhấp chuột để lật lại mặt trước
              </div>
            </div>
          </div>
        </div>

        <!-- Controls Row -->
        <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
          <button
            @click="prevPhrase"
            :disabled="currentPhraseIndex === 0"
            class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
          >
            <i class="bi bi-chevron-left"></i> Trước
          </button>

          <button
            @click="isFlipped = !isFlipped"
            class="btn btn-teal btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
          >
            <i class="bi bi-arrow-repeat"></i> Lật thẻ
          </button>

          <button
            @click="speech.speak(currentPhrase?.phrase)"
            class="btn btn-primary btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
          >
            <i class="bi bi-volume-up-fill"></i> Nghe cụm
          </button>

          <button
            @click="speech.startListening(currentPhrase?.phrase)"
            class="btn btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
            :class="speech.isListening.value ? 'btn-danger animate-mic-pulse' : 'btn-info text-white'"
          >
            <i class="bi" :class="speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
            <span>{{ speech.isListening.value ? 'Đang nghe...' : 'Luyện nói' }}</span>
          </button>

          <button
            @click="markPhraseStatus(currentPhrase, 'mastered')"
            class="btn btn-success btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
          >
            <i class="bi bi-check-circle-fill"></i> Đã nhớ
          </button>

          <button
            @click="markPhraseStatus(currentPhrase, 'learning')"
            class="btn btn-warning btn-sm text-white px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
          >
            <i class="bi bi-arrow-clockwise"></i> Cần ôn
          </button>

          <button
            @click="nextPhrase"
            :disabled="currentPhraseIndex === phrases.length - 1"
            class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1"
          >
            Tiếp <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>

      <div v-else class="text-center py-5 text-muted">
        Không tìm thấy cụm từ nào phù hợp với bộ lọc.
      </div>
    </div>

    <!-- MODE 2: TABLE LIST VIEW -->
    <div v-else class="card rounded-4 border shadow-sm overflow-hidden bg-card-custom mb-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light fs-8 text-uppercase">
            <tr>
              <th style="width: 40px;">#</th>
              <th>Cụm từ (Phrase)</th>
              <th>Phân loại</th>
              <th>Nghĩa tiếng Việt</th>
              <th>Từ đơn cấu thành</th>
              <th>Ví dụ song ngữ</th>
              <th class="text-center">Trạng thái</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody class="fs-7">
            <tr v-for="(p, pIdx) in phrases" :key="p.id">
              <td class="text-muted fw-bold">{{ pIdx + 1 }}</td>
              <td>
                <div class="fw-bold text-teal fs-6">{{ p.phrase }}</div>
                <span class="badge bg-light text-muted border" style="font-size: 10px;">{{ p.cefr_level || 'B1' }}</span>
              </td>
              <td>
                <span class="badge" :class="p.type === 'phrasal_verb' ? 'bg-primary-subtle text-primary border border-primary-subtle' : p.type === 'collocation' ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle'">
                  {{ p.type === 'phrasal_verb' ? 'Phrasal Verb' : p.type === 'collocation' ? 'Collocation' : 'Idiom' }}
                </span>
              </td>
              <td class="fw-semibold text-success">{{ p.meaning_vi }}</td>
              <td>
                <div class="d-flex gap-1 flex-wrap">
                  <span v-for="cw in p.component_words_json || []" :key="cw" class="badge bg-secondary-subtle text-secondary font-monospace" style="font-size: 11px;">
                    {{ cw }}
                  </span>
                </div>
              </td>
              <td class="fs-8 text-muted" style="max-width: 280px;">
                <div class="text-dark-emphasis fw-medium">{{ p.example_en }}</div>
                <div class="fst-italic">{{ p.example_vi }}</div>
              </td>
              <td class="text-center">
                <span class="badge" :class="p.status === 'mastered' ? 'bg-success' : p.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary'">
                  {{ p.status === 'mastered' ? 'Đã nhớ' : p.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                </span>
              </td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <button @click="speech.speak(p.phrase)" class="btn btn-outline-teal" title="Nghe phát âm">
                    <i class="bi bi-volume-up-fill"></i>
                  </button>
                  <button
                    @click="markPhraseStatus(p, p.status === 'mastered' ? 'unlearned' : 'mastered')"
                    class="btn"
                    :class="p.status === 'mastered' ? 'btn-success' : 'btn-outline-success'"
                    title="Đánh dấu Đã nhớ (đồng bộ các từ con)"
                  >
                    <i class="bi bi-check2"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useSpeech } from '@/composables/useSpeech'

const authStore = useAuthStore()
const speech = useSpeech()

const phrases = ref([])
const activeMode = ref('flashcard') // 'flashcard' | 'list'
const currentPhraseIndex = ref(0)
const isFlipped = ref(false)

const searchQuery = ref('')
const filterType = ref('all')
const filterStatus = ref('all')

const currentPhrase = computed(() => {
  if (phrases.value.length === 0) return null
  return phrases.value[currentPhraseIndex.value] || null
})

const masteredCount = computed(() => {
  return phrases.value.filter(p => p.status === 'mastered').length
})

const fetchPhrases = async () => {
  try {
    const params = {
      q: searchQuery.value || undefined,
      type: filterType.value !== 'all' ? filterType.value : undefined,
      status: filterStatus.value !== 'all' ? filterStatus.value : undefined,
    }
    const res = await axios.get('/api/phrasal-verbs', { params })
    phrases.value = res.data.data.items || []
    if (currentPhraseIndex.value >= phrases.value.length) {
      currentPhraseIndex.value = 0
    }
  } catch (err) {
    console.error('Failed to load phrasal verbs:', err)
  }
}

const clearSearch = () => {
  searchQuery.value = ''
  fetchPhrases()
}

const prevPhrase = () => {
  if (currentPhraseIndex.value > 0) {
    currentPhraseIndex.value--
    isFlipped.value = false
  }
}

const nextPhrase = () => {
  if (currentPhraseIndex.value < phrases.value.length - 1) {
    currentPhraseIndex.value++
    isFlipped.value = false
  }
}

const markPhraseStatus = async (phrase, status) => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để lưu tiến độ học cụm từ và tích lũy điểm số!')
    return
  }

  try {
    await axios.post('/api/phrasal-verbs/status', {
      phrase_id: phrase.id,
      status,
    })
    phrase.status = status
    if (activeMode.value === 'flashcard' && currentPhraseIndex.value < phrases.value.length - 1) {
      nextPhrase()
    }
  } catch (err) {
    console.error('Failed to mark phrase status:', err)
  }
}

onMounted(() => {
  fetchPhrases()
})
</script>
