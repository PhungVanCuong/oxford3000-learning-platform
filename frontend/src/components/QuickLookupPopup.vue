<template>
  <div v-if="visible" class="quick-lookup-container anim-spring-scale-up" :style="popupStyle" @mousedown.stop>
    <div class="card border-0 shadow-2xl rounded-4 p-3 quick-lookup-card text-dark-emphasis">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2 gap-2">
        <div class="d-flex align-items-center gap-1.5 min-w-0">
          <div class="rounded-circle bg-teal text-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 26px; height: 26px;">
            <i class="bi bi-translate fs-8"></i>
          </div>
          <span class="fs-8 fw-bold text-teal text-uppercase letter-spacing-1 text-truncate">Từ điển tra nhanh OxVocab</span>
          <span v-if="cefrLevel" class="badge bg-warning text-dark fs-9 rounded-pill px-1.5 py-0.5 fw-bold">
            {{ cefrLevel }}
          </span>
        </div>
        <button @click="closePopup" type="button" class="btn-close fs-9" aria-label="Close"></button>
      </div>

      <!-- Word Title & Phonetic & Audio Buttons -->
      <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
        <div class="min-w-0 flex-grow-1">
          <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <h5 class="fw-black text-teal mb-0 fs-6 text-truncate">{{ selectedWord }}</h5>
            <span v-if="pos" class="badge bg-secondary-subtle text-secondary-emphasis fs-9 rounded-pill text-uppercase">
              {{ pos }}
            </span>
          </div>
          <div v-if="phonetic" class="text-muted fs-8 font-monospace mt-0.5">
            {{ phonetic }}
          </div>
        </div>

        <!-- Audio controls (US / UK with Ripple Wave) -->
        <div class="d-flex align-items-center gap-1 flex-shrink-0">
          <button
            @click="speakAudio('en-US')"
            class="btn btn-sm btn-outline-teal rounded-pill px-2 py-0.5 fs-9 fw-bold d-flex align-items-center gap-1 shadow-2xs"
            :class="{ 'speaker-ripple-active': speech.isSpeaking.value }"
            title="Phát âm chuẩn US"
          >
            <i class="bi bi-volume-up-fill"></i> US
          </button>
          <button
            @click="speakAudio('en-GB')"
            class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0.5 fs-9 fw-bold d-flex align-items-center gap-1 shadow-2xs"
            title="Phát âm chuẩn UK"
          >
            <i class="bi bi-volume-up-fill"></i> UK
          </button>
        </div>
      </div>

      <!-- Meaning / Definition Box -->
      <div class="meaning-box p-2.5 rounded-3 bg-body-tertiary mb-2.5 fs-7 ox-text-break">
        <div v-if="loading" class="text-center py-2 text-muted fs-8">
          <div class="spinner-border spinner-border-sm text-teal me-1.5" role="status"></div>
          <span>Đang tra cứu cơ sở dữ liệu Oxford 3000 & Gemini AI...</span>
        </div>
        <div v-else>
          <!-- Nghĩa tiếng Việt rõ ràng -->
          <div class="fw-bold text-teal mb-1 fs-7 leading-snug">
            {{ meaning || 'Đang cập nhật định nghĩa tiếng Việt...' }}
          </div>

          <!-- Câu ví dụ ngữ cảnh cụ thể (Contextual Examples) -->
          <div v-if="exampleEn" class="mt-2 pt-1.5 border-top border-light-subtle">
            <div class="d-flex align-items-center justify-content-between mb-1">
              <span class="fs-9 fw-bold text-muted text-uppercase">Ví dụ & Ngữ cảnh sử dụng:</span>
              <button @click="speech.speak(exampleEn)" class="btn btn-xs btn-link p-0 text-teal text-decoration-none" title="Nghe câu ví dụ">
                <i class="bi bi-volume-up fs-8"></i>
              </button>
            </div>
            <div class="text-dark-emphasis fs-8 fst-italic leading-relaxed">
              "{{ exampleEn }}"
            </div>
            <div v-if="exampleVi" class="text-secondary fs-9 mt-0.5">
              → {{ exampleVi }}
            </div>
          </div>
        </div>
      </div>

      <!-- Actions: Save to Notebook + Ask AI + Details -->
      <div class="d-flex align-items-center justify-content-between gap-1.5 pt-1.5 border-top border-light-subtle flex-wrap">
        <div class="d-flex align-items-center gap-1.5">
          <!-- Button Lưu vào sổ tay -->
          <button
            @click="saveToNotebook"
            class="btn btn-sm rounded-pill px-2.5 py-1 fs-9 fw-bold d-flex align-items-center gap-1 transition-all shadow-2xs"
            :class="isSaved ? 'btn-success text-white' : 'btn-teal text-white'"
            :title="isSaved ? 'Đã có trong sổ tay' : 'Lưu vào sổ tay cá nhân'"
          >
            <i :class="isSaved ? 'bi bi-bookmark-check-fill' : 'bi bi-bookmark-plus-fill'"></i>
            <span>{{ isSaved ? 'Đã lưu sổ tay' : '+ Sổ tay' }}</span>
          </button>

          <!-- Button Hỏi Gemini AI -->
          <button
            @click="askGeminiAi"
            class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 fs-9 fw-bold d-flex align-items-center gap-1 shadow-2xs"
            title="Nhờ Gemini AI phân tích sâu ngữ pháp & ngữ cảnh"
          >
            <i class="bi bi-stars text-warning"></i>
            <span>Hỏi AI</span>
          </button>
        </div>

        <router-link
          :to="'/dictionary?q=' + encodeURIComponent(selectedWord)"
          @click="closePopup"
          class="btn btn-sm btn-link text-decoration-none p-0 fs-9 text-teal fw-bold d-flex align-items-center gap-0.5"
        >
          <span>Chi tiết</span>
          <i class="bi bi-chevron-right fs-9"></i>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useSpeech } from '@/composables/useSpeech'

const STORAGE_KEY = 'oxford_user_notebook'
const speech = useSpeech()

const visible = ref(false)
const popupStyle = ref({})
const selectedWord = ref('')
const phonetic = ref('')
const pos = ref('')
const meaning = ref('')
const exampleEn = ref('')
const exampleVi = ref('')
const cefrLevel = ref('')
const loading = ref(false)
const isSaved = ref(false)

const closePopup = () => {
  visible.value = false
}

const speakAudio = (lang = 'en-US') => {
  if (!selectedWord.value) return
  speech.speak(selectedWord.value, 0.88, lang)
}

const checkIsSaved = (word) => {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    if (!raw) return false
    const list = JSON.parse(raw)
    return list.some(item => (item.word || '').toLowerCase() === word.toLowerCase())
  } catch (e) {
    return false
  }
}

const saveToNotebook = () => {
  if (!selectedWord.value) return
  try {
    let list = []
    const raw = localStorage.getItem(STORAGE_KEY)
    if (raw) list = JSON.parse(raw)

    const exists = list.some(item => (item.word || '').toLowerCase() === selectedWord.value.toLowerCase())
    if (!exists) {
      list.unshift({
        id: Date.now(),
        word: selectedWord.value,
        phonetic: phonetic.value || '',
        pos: pos.value || 'từ mới',
        meaning: meaning.value || selectedWord.value,
        example: exampleEn.value || '',
        example_vi: exampleVi.value || '',
        mastered: false,
        created_at: Date.now()
      })
      localStorage.setItem(STORAGE_KEY, JSON.stringify(list))
    }
    isSaved.value = true
  } catch (e) {
    console.error('Lỗi khi lưu vào sổ tay:', e)
  }
}

const askGeminiAi = () => {
  closePopup()
  window.dispatchEvent(new CustomEvent('open-ai-chat', {
    detail: {
      prompt: `Giải thích chi tiết từ vựng "${selectedWord.value}", cách dùng trong câu và 3 ví dụ thực tế.`
    }
  }))
}

const lookupWord = async (word) => {
  loading.value = true
  meaning.value = ''
  phonetic.value = ''
  pos.value = ''
  exampleEn.value = ''
  exampleVi.value = ''
  cefrLevel.value = ''
  isSaved.value = checkIsSaved(word)

  let foundInOxfordDb = false

  // 1. Tra cứu trực tiếp từ Database Oxford 3000 của hệ thống backend
  try {
    const dbRes = await axios.get(`/api/dictionary/search?q=${encodeURIComponent(word)}`)
    if (dbRes.data && dbRes.data.status === 'success' && dbRes.data.data.items && dbRes.data.data.items.length > 0) {
      // Tìm từ khớp chính xác nhất
      const exact = dbRes.data.data.items.find(i => i.word.toLowerCase() === word.toLowerCase()) || dbRes.data.data.items[0]
      if (exact) {
        foundInOxfordDb = true
        meaning.value = exact.meaning_vi || ''
        phonetic.value = exact.phonetic_us || ''
        pos.value = exact.part_of_speech || ''
        exampleEn.value = exact.example_en || ''
        exampleVi.value = exact.example_vi || ''
        cefrLevel.value = exact.cefr_level || ''
      }
    }
  } catch (err) {
    console.warn('Lỗi tra cứu Oxford DB, chuyển sang fallback:', err)
  }

  // 2. Nếu không có trong DB Oxford, gọi fallback Free Dictionary API
  if (!foundInOxfordDb) {
    try {
      const res = await fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${encodeURIComponent(word)}`)
      if (res.ok) {
        const data = await res.json()
        if (Array.isArray(data) && data.length > 0) {
          const item = data[0]
          phonetic.value = item.phonetic || (item.phonetics && item.phonetics[0]?.text) || ''
          if (item.meanings && item.meanings.length > 0) {
            const m = item.meanings[0]
            pos.value = m.partOfSpeech || ''
            if (m.definitions && m.definitions.length > 0) {
              meaning.value = m.definitions[0].definition || ''
              exampleEn.value = m.definitions[0].example || ''
            }
          }
        }
      }
    } catch (e) {}

    // 3. Nếu vẫn chưa có nghĩa tiếng Việt hoặc muốn hỗ trợ tiếng Việt phong phú, gọi Gemini AI Explain
    if (!meaning.value || !isVietnamese(meaning.value)) {
      try {
        const aiRes = await axios.post('/api/ai/explain', { text: word })
        if (aiRes.data && aiRes.data.status === 'success' && aiRes.data.data) {
          const aiData = aiRes.data.data
          if (aiData.meaning) meaning.value = aiData.meaning
          if (aiData.phonetic && !phonetic.value) phonetic.value = aiData.phonetic
          if (aiData.part_of_speech && !pos.value) pos.value = aiData.part_of_speech
          if (aiData.example && !exampleEn.value) exampleEn.value = aiData.example
          if (aiData.example_vi && !exampleVi.value) exampleVi.value = aiData.example_vi
        }
      } catch (err) {}
    }
  }

  if (!meaning.value) {
    meaning.value = `Định nghĩa từ "${word}" đang được cập nhật.`
  }
  loading.value = false
}

const isVietnamese = (text) => {
  return /[àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ]/i.test(text)
}

const handleTextSelection = (event) => {
  if (event.target.closest('input, textarea, .quick-lookup-container, .btn, .modal, .offcanvas')) {
    return
  }

  const sel = window.getSelection()
  if (!sel || sel.isCollapsed) {
    return
  }

  const text = sel.toString().trim()
  if (!text || text.length > 35 || text.split(/\s+/).length > 3) {
    return
  }

  const cleanText = text.replace(/^[^\w]+|[^\w]+$/g, '')
  if (!cleanText || cleanText.length < 2) return

  selectedWord.value = cleanText

  const range = sel.getRangeAt(0)
  const rect = range.getBoundingClientRect()

  let top = rect.bottom + window.scrollY + 10
  let left = rect.left + window.scrollX

  const screenWidth = window.innerWidth
  const popupWidth = 340
  if (left + popupWidth > screenWidth - 16) {
    left = screenWidth - popupWidth - 16
  }
  if (left < 16) left = 16

  if (rect.bottom + 250 > window.innerHeight) {
    top = Math.max(8, rect.top + window.scrollY - 250)
  }

  popupStyle.value = {
    position: 'absolute',
    top: `${top}px`,
    left: `${left}px`,
    zIndex: 9999
  }

  visible.value = true
  lookupWord(cleanText)
}

const handleDocumentClick = (e) => {
  if (!e.target.closest('.quick-lookup-container')) {
    visible.value = false
  }
}

const handleKeyDown = (e) => {
  if (e.key === 'Escape') {
    visible.value = false
  }
}

onMounted(() => {
  document.addEventListener('mouseup', handleTextSelection)
  document.addEventListener('mousedown', handleDocumentClick)
  document.addEventListener('keydown', handleKeyDown)
})

onUnmounted(() => {
  document.removeEventListener('mouseup', handleTextSelection)
  document.removeEventListener('mousedown', handleDocumentClick)
  document.removeEventListener('keydown', handleKeyDown)
})
</script>

<style scoped>
.quick-lookup-container {
  width: 340px;
  max-width: 92vw;
}

.quick-lookup-card {
  background: var(--ox-dropdown-bg, rgba(255, 255, 255, 0.98));
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid var(--ox-border, rgba(0, 176, 116, 0.25)) !important;
  box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(0, 176, 116, 0.15) !important;
}

:global([data-bs-theme="dark"]) .quick-lookup-card {
  background: rgba(28, 40, 38, 0.98) !important;
  border-color: rgba(0, 176, 116, 0.4) !important;
  color: #f1f5f9 !important;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(0, 176, 116, 0.25) !important;
}
</style>
