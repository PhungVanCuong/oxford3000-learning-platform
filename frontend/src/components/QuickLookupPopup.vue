<template>
  <div v-if="visible" class="quick-lookup-container" :style="popupStyle" @mousedown.stop>
    <div class="card border-0 shadow-lg rounded-4 p-3 quick-lookup-card text-dark-emphasis">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2 gap-2">
        <div class="d-flex align-items-center gap-1.5 min-w-0">
          <div class="rounded-circle bg-teal text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 24px; height: 24px;">
            <i class="bi bi-translate fs-9"></i>
          </div>
          <span class="fs-8 fw-bold text-teal text-uppercase letter-spacing-1 text-truncate">Từ điển tra nhanh</span>
        </div>
        <button @click="closePopup" type="button" class="btn-close fs-9" aria-label="Close"></button>
      </div>

      <!-- Word Title & Phonetic & Audio -->
      <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
        <div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <h5 class="fw-black text-teal mb-0 fs-6">{{ selectedWord }}</h5>
            <span v-if="pos" class="badge bg-secondary-subtle text-secondary-emphasis fs-9 rounded-pill">
              {{ pos }}
            </span>
          </div>
          <div v-if="phonetic" class="text-muted fs-8 font-monospace mt-0.5">
            {{ phonetic }}
          </div>
        </div>

        <div class="d-flex align-items-center gap-1">
          <button
            @click="speak('en-US')"
            class="btn btn-sm btn-outline-teal rounded-pill px-2 py-0.5 fs-9 fw-bold d-flex align-items-center gap-1 shadow-2xs"
            title="Phát âm chuẩn US"
          >
            <i class="bi bi-volume-up-fill"></i> US
          </button>
          <button
            @click="speak('en-GB')"
            class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0.5 fs-9 fw-bold d-flex align-items-center gap-1 shadow-2xs"
            title="Phát âm chuẩn UK"
          >
            <i class="bi bi-volume-up-fill"></i> UK
          </button>
        </div>
      </div>

      <!-- Meaning / Definition -->
      <div class="meaning-box p-2.5 rounded-3 bg-body-tertiary mb-2.5 fs-7">
        <div v-if="loading" class="text-center py-2 text-muted fs-8">
          <div class="spinner-border spinner-border-sm text-teal me-1.5" role="status"></div>
          <span>Đang tra cứu từ điển...</span>
        </div>
        <div v-else>
          <div class="fw-bold text-teal mb-1">{{ meaning || 'Từ vựng tiếng Anh thông dụng.' }}</div>
          <div v-if="example" class="text-muted fs-8 fst-italic">
            "{{ example }}"
          </div>
        </div>
      </div>

      <!-- Actions: Save to Notebook & Full Dictionary Link -->
      <div class="d-flex align-items-center justify-content-between gap-2 pt-1 border-top border-light-subtle">
        <button
          @click="saveToNotebook"
          class="btn btn-sm rounded-pill px-3 py-1 fs-8 fw-bold d-flex align-items-center gap-1.5 transition-all shadow-2xs"
          :class="isSaved ? 'btn-success text-white' : 'btn-teal text-white'"
        >
          <i :class="isSaved ? 'bi bi-check-circle-fill' : 'bi bi-bookmark-plus-fill'"></i>
          <span>{{ isSaved ? 'Đã lưu sổ tay' : 'Lưu vào Sổ tay' }}</span>
        </button>

        <router-link
          :to="'/dictionary?q=' + encodeURIComponent(selectedWord)"
          @click="closePopup"
          class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 fs-8 text-decoration-none"
        >
          Xem chi tiết <i class="bi bi-chevron-right fs-9"></i>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const STORAGE_KEY = 'oxford_user_notebook'

const visible = ref(false)
const popupStyle = ref({})
const selectedWord = ref('')
const phonetic = ref('')
const pos = ref('')
const meaning = ref('')
const example = ref('')
const loading = ref(false)
const isSaved = ref(false)

const closePopup = () => {
  visible.value = false
}

const speak = (lang = 'en-US') => {
  if (!selectedWord.value || !window.speechSynthesis) return
  window.speechSynthesis.cancel()
  const utter = new SpeechSynthesisUtterance(selectedWord.value)
  utter.lang = lang
  utter.rate = 0.88
  window.speechSynthesis.speak(utter)
}

const checkIsSaved = (word) => {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    if (!raw) return false
    const list = JSON.parse(raw)
    return list.some(item => item.word.toLowerCase() === word.toLowerCase())
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

    const exists = list.some(item => item.word.toLowerCase() === selectedWord.value.toLowerCase())
    if (!exists) {
      list.unshift({
        id: Date.now(),
        word: selectedWord.value,
        phonetic: phonetic.value || '',
        pos: pos.value || 'từ mới',
        meaning: meaning.value || selectedWord.value,
        example: example.value || '',
        example_vi: '',
        mastered: false,
        created_at: Date.now()
      })
      localStorage.setItem(STORAGE_KEY, JSON.stringify(list))
    }
    isSaved.value = true
  } catch (e) {
    console.error('Error saving to notebook:', e)
  }
}

const lookupWord = async (word) => {
  loading.value = true
  meaning.value = ''
  phonetic.value = ''
  pos.value = ''
  example.value = ''
  isSaved.value = checkIsSaved(word)

  try {
    // 1. Try free dictionary API
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
            example.value = m.definitions[0].example || ''
          }
        }
      }
    }
  } catch (err) {
    // fallback
  } finally {
    if (!meaning.value) {
      meaning.value = 'Nghĩa tiếng Việt đang cập nhật từ kho từ điển OxVocab.'
    }
    loading.value = false
  }
}

const handleTextSelection = (event) => {
  // If target is inside an input, textarea or our popup, ignore
  if (event.target.closest('input, textarea, .quick-lookup-container, .btn, .modal')) {
    return
  }

  const sel = window.getSelection()
  if (!sel || sel.isCollapsed) {
    return
  }

  const text = sel.toString().trim()
  // Only trigger for 1-3 English words, clean of punctuation
  if (!text || text.length > 35 || text.split(/\s+/).length > 3) {
    return
  }

  const cleanText = text.replace(/^[^\w]+|[^\w]+$/g, '')
  if (!cleanText || cleanText.length < 2) return

  selectedWord.value = cleanText

  const range = sel.getRangeAt(0)
  const rect = range.getBoundingClientRect()

  // Calculate coordinates with bounds checking
  let top = rect.bottom + window.scrollY + 8
  let left = rect.left + window.scrollX

  // If too close to right edge, shift left
  const screenWidth = window.innerWidth
  const popupWidth = 320
  if (left + popupWidth > screenWidth - 16) {
    left = screenWidth - popupWidth - 16
  }
  if (left < 16) left = 16

  // If too close to bottom, show above
  if (rect.bottom + 220 > window.innerHeight) {
    top = Math.max(8, rect.top + window.scrollY - 220)
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
  width: 320px;
  max-width: 90vw;
  animation: quickLookupFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.quick-lookup-card {
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(0, 176, 116, 0.25) !important;
  box-shadow: 0 12px 36px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(0, 176, 116, 0.12) !important;
}

:global([data-bs-theme="dark"]) .quick-lookup-card {
  background: rgba(26, 32, 44, 0.98) !important;
  border-color: rgba(0, 176, 116, 0.4) !important;
  color: #e2e8f0 !important;
}

@keyframes quickLookupFadeIn {
  from {
    opacity: 0;
    transform: translateY(6px) scale(0.96);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>
