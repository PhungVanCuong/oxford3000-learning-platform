<template>
  <div class="admin-cefr-view">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <span class="badge bg-success text-white rounded-pill px-3 py-1 fs-8">KHUNG THAM CHIẾU CHÂU ÂU (CEFR)</span>
        <h2 class="fw-black text-teal mb-0">Quản Lý Từ Vựng CEFR 6 Cấp Độ (A1 - C2)</h2>
        <div class="text-muted fs-7">Quản lý và điều chỉnh 4.922 mục từ vựng, phiên âm IPA, loại từ và câu ví dụ theo 247 bài học</div>
      </div>

      <div class="d-flex gap-2">
        <button @click="openModal(null)" class="btn btn-teal btn-sm rounded-pill px-3 py-2 shadow-sm fw-bold">
          <i class="bi bi-plus-circle-fill me-1"></i> Thêm từ vựng CEFR mới
        </button>
      </div>
    </div>

    <!-- Quick Stats Cards for 6 Levels -->
    <div class="row g-2.5 mb-4">
      <div v-for="lvl in manifest.levels" :key="lvl.id" class="col-6 col-md-4 col-xl-2">
        <div
          @click="selectedLevel = lvl.code"
          class="card p-2.5 rounded-4 border text-center transition-all cursor-pointer h-100"
          :class="selectedLevel === lvl.code ? 'border-teal bg-teal-subtle shadow-xs' : 'bg-card-custom hover-card'"
          style="cursor: pointer;"
        >
          <div
            class="badge rounded-pill px-2.5 py-1 text-white fs-8 mx-auto mb-1.5"
            :style="{ backgroundColor: lvl.color || '#00b074' }"
          >
            {{ lvl.code }}
          </div>
          <div class="fw-bold fs-7 text-truncate mb-0.5">{{ lvl.title }}</div>
          <div class="text-teal fw-black fs-6">{{ lvl.total_words }} từ</div>
          <div class="text-muted fs-9">{{ lvl.total_lessons }} bài học</div>
        </div>
      </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-4">
      <div class="row g-2 align-items-center">
        <!-- Search -->
        <div class="col-12 col-md-5">
          <div class="input-group">
            <span class="input-group-text bg-body-tertiary border-end-0">
              <i class="bi bi-search text-teal"></i>
            </span>
            <input
              v-model="searchQuery"
              type="text"
              class="form-control border-start-0 py-2 fs-7"
              placeholder="Tìm theo từ tiếng Anh, phiên âm hoặc nghĩa tiếng Việt..."
            />
          </div>
        </div>

        <!-- Level Selector -->
        <div class="col-6 col-md-3">
          <select v-model="selectedLevel" class="form-select py-2 fs-7">
            <option value="all">Tất cả cấp độ (A1 - C2)</option>
            <option value="A1">Bậc A1 (Cơ bản khởi đầu)</option>
            <option value="A2">Bậc A2 (Sơ cấp)</option>
            <option value="B1">Bậc B1 (Trung cấp)</option>
            <option value="B2">Bậc B2 (Trung cao cấp)</option>
            <option value="C1">Bậc C1 (Cao cấp)</option>
            <option value="C2">Bậc C2 (Thành thạo bản ngữ)</option>
          </select>
        </div>

        <!-- Lesson Selector -->
        <div class="col-6 col-md-2">
          <select v-model="selectedLesson" class="form-select py-2 fs-7">
            <option value="all">Tất cả bài</option>
            <option v-for="l in 65" :key="l" :value="l">
              Bài {{ l }}
            </option>
          </select>
        </div>

        <div class="col-12 col-md-2 text-md-end text-muted fs-8">
          Khớp: <strong class="text-teal fs-7">{{ filteredWords.length }}</strong> từ
        </div>
      </div>
    </div>

    <!-- CEFR Vocabulary Table -->
    <div class="card rounded-4 border shadow-sm bg-card-custom overflow-hidden mb-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light fs-8 text-uppercase">
            <tr>
              <th style="width: 50px;">STT</th>
              <th>Bậc & Bài</th>
              <th>Từ tiếng Anh</th>
              <th>Phiên âm US</th>
              <th>Loại từ</th>
              <th>Nghĩa tiếng Việt</th>
              <th>Ví dụ câu</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody class="fs-7">
            <tr v-for="(w, idx) in paginatedWords" :key="w.id || idx">
              <td class="text-muted fw-bold">{{ (currentPage - 1) * pageSize + idx + 1 }}</td>
              <td>
                <span class="badge bg-teal-subtle text-teal fw-bold rounded-pill px-2 py-0.5 fs-8">
                  {{ w.level_code || 'A1' }} • Bài {{ w.lesson_number || 1 }}
                </span>
              </td>
              <td class="fw-bold text-teal fs-6">{{ w.word }}</td>
              <td class="font-monospace text-muted fs-8">{{ w.phonetic_us }}</td>
              <td>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0.5 fs-9">
                  {{ w.part_of_speech || 'Từ vựng' }}
                </span>
              </td>
              <td class="fw-semibold text-dark-emphasis" style="max-width: 250px;">
                {{ w.meaning_vi }}
              </td>
              <td class="fs-8 text-muted" style="max-width: 280px;">
                <div class="text-dark-emphasis text-truncate">{{ w.example_en }}</div>
                <div class="fst-italic text-truncate">{{ w.example_vi }}</div>
              </td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <button @click="openModal(w)" class="btn btn-outline-secondary" title="Sửa từ vựng">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button @click="deleteWord(w)" class="btn btn-outline-danger" title="Xóa từ vựng">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredWords.length === 0">
              <td colspan="8" class="text-center py-5 text-muted">
                Không tìm thấy từ vựng CEFR nào phù hợp với bộ lọc hiện tại.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Smart 50-Item Pagination -->
      <SmartPagination
        v-model:currentPage="currentPage"
        v-model:pageSize="pageSize"
        :totalItems="filteredWords.length"
        :pageSizeOptions="[25, 50, 100, 200]"
      />
    </div>

    <!-- Create / Edit CEFR Word Modal -->
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.55); backdrop-filter: blur(2px);">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
          <div class="modal-header border-bottom pb-3">
            <h5 class="modal-title fw-bold text-teal d-flex align-items-center gap-2">
              <i class="bi bi-award-fill"></i>
              <span>{{ isEditing ? 'Chỉnh Sửa Từ Vựng CEFR' : 'Thêm Từ Vựng CEFR Mới' }}</span>
            </h5>
            <button @click="showModal = false" type="button" class="btn-close"></button>
          </div>

          <form @submit.prevent="saveWord">
            <div class="modal-body py-3">
              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <label class="form-label fs-8 fw-bold">Từ tiếng Anh *</label>
                  <input v-model="form.word" type="text" required class="form-control" placeholder="aberration" />
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label fs-8 fw-bold">Phiên âm US (IPA) *</label>
                  <input v-model="form.phonetic_us" type="text" required class="form-control" placeholder="/ˌæb.əˈreɪ.ʃən/" />
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label fs-8 fw-bold">Bậc CEFR *</label>
                  <select v-model="form.level_code" class="form-select">
                    <option value="A1">A1</option>
                    <option value="A2">A2</option>
                    <option value="B1">B1</option>
                    <option value="B2">B2</option>
                    <option value="C1">C1</option>
                    <option value="C2">C2</option>
                  </select>
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label fs-8 fw-bold">Số bài học (Lesson Number) *</label>
                  <input v-model.number="form.lesson_number" type="number" min="1" max="65" required class="form-control" />
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label fs-8 fw-bold">Loại từ</label>
                  <input v-model="form.part_of_speech" type="text" class="form-control" placeholder="Danh từ / Động từ..." />
                </div>

                <div class="col-12">
                  <label class="form-label fs-8 fw-bold">Nghĩa tiếng Việt *</label>
                  <textarea v-model="form.meaning_vi" rows="2" required class="form-control" placeholder="Giải nghĩa tiếng Việt đầy đủ và chính xác..."></textarea>
                </div>

                <div class="col-12">
                  <label class="form-label fs-8 fw-bold">Câu ví dụ tiếng Anh</label>
                  <input v-model="form.example_en" type="text" class="form-control" placeholder="In a moment of aberration, she agreed to their proposal." />
                </div>

                <div class="col-12">
                  <label class="form-label fs-8 fw-bold">Dịch nghĩa câu ví dụ</label>
                  <input v-model="form.example_vi" type="text" class="form-control" placeholder="Trong một phút lơ đãng sai sót, cô ấy đã đồng ý lời đề nghị của họ." />
                </div>
              </div>
            </div>

            <div class="modal-footer border-top pt-2">
              <button @click="showModal = false" type="button" class="btn btn-outline-secondary rounded-pill px-4">Hủy</button>
              <button type="submit" class="btn btn-teal text-white rounded-pill px-4 fw-bold">
                <i class="bi bi-check-lg me-1"></i> Lưu từ vựng
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import SmartPagination from '@/components/SmartPagination.vue'

// Import manifest & level datasets
import manifestData from '@/data/cefr/manifest.json'
import a1Data from '@/data/cefr/a1.json'
import a2Data from '@/data/cefr/a2.json'
import b1Data from '@/data/cefr/b1.json'
import b2Data from '@/data/cefr/b2.json'
import c1Data from '@/data/cefr/c1.json'
import c2Data from '@/data/cefr/c2.json'

const manifest = manifestData

// Flatten all CEFR words into a consolidated array with level_code and lesson_number
const flattenLevel = (levelData, levelCode) => {
  const result = []
  if (!levelData?.lessons) return result
  levelData.lessons.forEach(l => {
    (l.words || []).forEach(w => {
      result.push({
        ...w,
        level_code: levelCode,
        lesson_number: l.lesson_number,
        lesson_title: l.title
      })
    })
  })
  return result
}

// Initial combined dataset with localStorage overrides if modified
const savedOverrides = JSON.parse(localStorage.getItem('oxford_admin_cefr_words') || 'null')
const initialWords = savedOverrides || [
  ...flattenLevel(a1Data, 'A1'),
  ...flattenLevel(a2Data, 'A2'),
  ...flattenLevel(b1Data, 'B1'),
  ...flattenLevel(b2Data, 'B2'),
  ...flattenLevel(c1Data, 'C1'),
  ...flattenLevel(c2Data, 'C2'),
]

const words = ref(initialWords)
const searchQuery = ref('')
const selectedLevel = ref('all')
const selectedLesson = ref('all')

const currentPage = ref(1)
const pageSize = ref(50) // Mặc định 50 mục/trang

const showModal = ref(false)
const isEditing = ref(false)
const form = ref({
  id: null,
  word: '',
  phonetic_us: '',
  level_code: 'A1',
  lesson_number: 1,
  part_of_speech: 'Danh từ',
  meaning_vi: '',
  example_en: '',
  example_vi: ''
})

const filteredWords = computed(() => {
  return words.value.filter(w => {
    const q = searchQuery.value.toLowerCase().trim()
    const matchSearch = !q ||
      w.word?.toLowerCase().includes(q) ||
      w.meaning_vi?.toLowerCase().includes(q) ||
      w.phonetic_us?.toLowerCase().includes(q)

    const matchLevel = selectedLevel.value === 'all' || w.level_code === selectedLevel.value
    const matchLesson = selectedLesson.value === 'all' || w.lesson_number === parseInt(selectedLesson.value, 10)

    return matchSearch && matchLevel && matchLesson
  })
})

const paginatedWords = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value
  return filteredWords.value.slice(start, start + pageSize.value)
})

const openModal = (item) => {
  if (item) {
    isEditing.value = true
    form.value = { ...item }
  } else {
    isEditing.value = false
    form.value = {
      id: null,
      word: '',
      phonetic_us: '',
      level_code: selectedLevel.value === 'all' ? 'A1' : selectedLevel.value,
      lesson_number: selectedLesson.value === 'all' ? 1 : parseInt(selectedLesson.value, 10),
      part_of_speech: 'Danh từ',
      meaning_vi: '',
      example_en: '',
      example_vi: ''
    }
  }
  showModal.value = true
}

const saveWord = () => {
  if (!form.value.word || !form.value.meaning_vi) {
    alert('Vui lòng nhập từ tiếng Anh và nghĩa tiếng Việt')
    return
  }

  if (isEditing.value) {
    const idx = words.value.findIndex(w => (w.id && w.id === form.value.id) || w.word === form.value.word)
    if (idx !== -1) {
      words.value[idx] = { ...words.value[idx], ...form.value }
    }
  } else {
    words.value.unshift({
      ...form.value,
      id: `cefr_custom_${Date.now()}`
    })
  }

  localStorage.setItem('oxford_admin_cefr_words', JSON.stringify(words.value.slice(0, 500)))
  showModal.value = false
  alert('Đã lưu từ vựng CEFR thành công!')
}

const deleteWord = (item) => {
  if (confirm(`Bạn có chắc chắn muốn xóa từ "${item.word}" khỏi cấp độ ${item.level_code}?`)) {
    words.value = words.value.filter(w => w !== item && w.id !== item.id)
    alert('Đã xóa từ vựng thành công!')
  }
}
</script>

<style scoped>
.hover-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}
</style>
