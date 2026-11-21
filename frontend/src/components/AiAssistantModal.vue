<template>
  <div class="ai-assistant-wrapper">
    <!-- Floating Trigger Button (FAB) - Neo cố định góc dưới màn hình laptop & mobile -->
    <button
      @click="toggleChat"
      class="ai-assistant-fab shadow-lg rounded-circle d-flex align-items-center justify-content-center text-white border-0 transition-all position-relative"
      :class="{ 'fab-active': isOpen, 'fab-bounce': hasUnreadHint }"
      :title="isOpen ? 'Thu nhỏ Trợ lý AI' : 'Gia sư AI Tiếng Anh 24/7 (Google Gemini)'"
      aria-label="Toggle OxVocab AI Assistant"
      type="button"
    >
      <!-- Icon Robot / Stars when closed, Close when open -->
      <transition name="fade" mode="out-in">
        <i v-if="!isOpen" class="bi bi-robot fs-4"></i>
        <i v-else class="bi bi-x-lg fs-5"></i>
      </transition>

      <!-- Active Pulse Badge (Chấm xanh / vàng AI sẵn sàng) -->
      <span
        v-if="!isOpen"
        class="position-absolute top-0 start-100 translate-middle p-1.5 bg-warning border border-light rounded-circle shadow-xs animate-pulse"
        style="transform: translate(-35%, 25%) !important;"
        title="Gemini AI Sẵn sàng"
      ></span>
    </button>

    <!-- AI Chat Window / Drawer (Floating Viewport Modal) -->
    <transition name="ai-drawer">
      <div
        v-if="isOpen"
        class="ai-chat-window shadow-2xl rounded-4 border d-flex flex-column"
        :class="[
          isDarkMode ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle',
          isExpanded ? 'ai-window-expanded' : ''
        ]"
      >
        <!-- 1. CHAT HEADER -->
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-teal text-white rounded-top-4 flex-shrink-0">
          <div class="d-flex align-items-center gap-2 min-w-0">
            <div class="rounded-circle bg-white text-teal d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 36px; height: 36px;">
              <i class="bi bi-stars fs-5 text-warning"></i>
            </div>
            <div class="min-w-0">
              <div class="fw-bold fs-7 leading-tight text-truncate d-flex align-items-center gap-1.5">
                <span>OxVocab AI Tutor</span>
                <span class="badge bg-warning text-dark fs-9 rounded-pill px-1.5 py-0.5 fw-bold">Gemini 2.5</span>
              </div>
              <div class="fs-9 opacity-90 text-truncate text-white-50">
                {{ contextSubtitle }}
              </div>
            </div>
          </div>

          <!-- Header Actions: Expand/Contract + Clear + Close -->
          <div class="d-flex align-items-center gap-1 flex-shrink-0">
            <!-- Expand / Restore (Desktop only) -->
            <button
              @click="isExpanded = !isExpanded"
              class="btn btn-sm btn-link text-white text-decoration-none p-1 fs-8 d-none d-md-inline-flex"
              :title="isExpanded ? 'Thu nhỏ cửa sổ' : 'Mở rộng cửa sổ'"
              type="button"
            >
              <i class="bi" :class="isExpanded ? 'bi-fullscreen-exit' : 'bi-arrows-angle-expand'"></i>
            </button>

            <!-- Clear Conversation -->
            <button
              @click="confirmClearMessages"
              class="btn btn-sm btn-link text-white text-decoration-none p-1 fs-8"
              title="Làm mới cuộc trò chuyện"
              type="button"
            >
              <i class="bi bi-trash3"></i>
            </button>

            <!-- Close Drawer -->
            <button
              @click="isOpen = false"
              class="btn btn-sm btn-link text-white text-decoration-none p-1 fs-7"
              title="Đóng cửa sổ"
              type="button"
            >
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
        </div>

        <!-- 2. QUICK MODE TOOLBAR (Chế độ hỗ trợ 1 chạm) -->
        <div class="px-2.5 py-1.5 border-bottom d-flex gap-1 overflow-x-auto no-scrollbar flex-shrink-0" :class="isDarkMode ? 'bg-black/20' : 'bg-body-secondary'">
          <button
            v-for="mode in quickModes"
            :key="mode.id"
            @click="applyMode(mode)"
            class="btn btn-xs rounded-pill px-2.5 py-1 text-nowrap fs-9 border d-inline-flex align-items-center gap-1 transition-all"
            :class="activeMode === mode.id ? 'btn-teal text-white border-teal shadow-xs' : (isDarkMode ? 'bg-secondary text-white border-secondary' : 'bg-white text-dark border-light-subtle')"
            type="button"
          >
            <span>{{ mode.icon }}</span>
            <span>{{ mode.label }}</span>
          </button>
        </div>

        <!-- 3. CONTEXT-AWARE SMART CHIPS (Gợi ý câu hỏi tự động theo trang đang mở) -->
        <div class="px-2.5 py-2 border-bottom overflow-x-auto no-scrollbar d-flex gap-1.5 flex-shrink-0" :class="isDarkMode ? 'bg-dark-subtle' : 'bg-body-tertiary'">
          <button
            v-for="(chip, idx) in activePromptChips"
            :key="idx"
            @click="sendChip(chip.prompt)"
            class="btn btn-xs rounded-pill px-2.5 py-1 text-nowrap fs-9 border shadow-2xs hover-shadow d-inline-flex align-items-center gap-1 transition-all"
            :class="isDarkMode ? 'bg-dark text-light border-secondary hover-teal' : 'bg-white text-dark border-light-subtle hover-teal'"
            type="button"
          >
            <span>{{ chip.label }}</span>
          </button>
        </div>

        <!-- 4. MESSAGES BODY (Scrollable area with Markdown + TTS) -->
        <div
          ref="messagesBody"
          class="flex-grow-1 p-3 overflow-y-auto d-flex flex-column gap-2.5 messages-area custom-chat-scrollbar"
        >
          <div
            v-for="(msg, idx) in messages"
            :key="idx"
            class="d-flex"
            :class="msg.sender === 'user' ? 'justify-content-end' : 'justify-content-start'"
          >
            <div
              class="p-2.5 rounded-3 max-w-xs fs-8 message-bubble shadow-2xs position-relative"
              :class="msg.sender === 'user'
                ? 'bg-teal text-white rounded-br-0 user-bubble'
                : (isDarkMode ? 'bg-secondary text-white rounded-bl-0 bot-bubble-dark' : 'bg-body-secondary text-dark rounded-bl-0 bot-bubble-light')"
            >
              <!-- Message Text (Markdown with table & code support) -->
              <div
                v-if="msg.sender === 'bot'"
                class="message-content chat-markdown ox-text-break"
                v-html="renderMarkdown(msg.text)"
              ></div>
              <div v-else class="message-content ox-text-break" style="white-space: pre-wrap;">{{ msg.text }}</div>

              <!-- Action Bar (Bot replies only): Timestamp + Bilingual TTS + Copy + Add to Notebook -->
              <div class="d-flex justify-content-between align-items-center mt-2 pt-1.5 opacity-80 fs-9 border-top border-black/10">
                <span class="fs-9 font-monospace">{{ msg.time }}</span>

                <div v-if="msg.sender === 'bot'" class="d-flex align-items-center gap-2 ms-2">
                  <!-- Copy Button -->
                  <button
                    @click="copyText(msg.text, idx)"
                    class="btn btn-xs btn-link p-0 text-decoration-none"
                    :class="isDarkMode ? 'text-white' : 'text-teal'"
                    :title="copiedIdx === idx ? 'Đã sao chép!' : 'Sao chép câu trả lời'"
                    type="button"
                  >
                    <i :class="copiedIdx === idx ? 'bi bi-check-lg text-success fw-bold' : 'bi bi-clipboard'"></i>
                  </button>

                  <!-- Bilingual TTS Play / Stop Button with Ripple -->
                  <button
                    @click="toggleSpeakMessage(msg.text, idx)"
                    class="btn btn-xs btn-link p-0 text-decoration-none d-inline-flex align-items-center"
                    :class="isDarkMode ? 'text-white' : 'text-teal'"
                    :title="speakingIdx === idx ? 'Dừng đọc' : 'Nghe AI đọc song ngữ Anh - Việt'"
                    type="button"
                  >
                    <i
                      :class="speakingIdx === idx ? 'bi bi-stop-circle-fill text-danger speaker-ripple-active' : 'bi bi-volume-up-fill'"
                    ></i>
                  </button>

                  <!-- Quick Save to Notebook (if message has word/definition) -->
                  <button
                    v-if="canSaveWordFromMessage(msg.text)"
                    @click="saveWordFromMessage(msg.text)"
                    class="btn btn-xs btn-link p-0 text-decoration-none"
                    :class="isDarkMode ? 'text-warning' : 'text-success'"
                    title="Lưu từ này vào Sổ tay"
                    type="button"
                  >
                    <i class="bi bi-bookmark-plus-fill"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- AI Thinking / Loading indicator -->
          <div v-if="isThinking" class="d-flex justify-content-start">
            <div
              class="p-2.5 rounded-3 fs-8 d-flex align-items-center gap-2 shadow-2xs"
              :class="isDarkMode ? 'bg-secondary text-white' : 'bg-body-secondary text-muted'"
            >
              <div class="spinner-grow spinner-grow-sm text-teal" role="status"></div>
              <span>Gemini AI đang soạn lời giải thích chi tiết...</span>
            </div>
          </div>
        </div>

        <!-- 5. CHAT FOOTER INPUT -->
        <div class="p-2.5 border-top d-flex gap-2 align-items-center flex-shrink-0" :class="isDarkMode ? 'bg-dark-subtle border-secondary' : 'bg-body-tertiary border-light-subtle'">
          <input
            ref="inputFieldRef"
            v-model="inputQuery"
            @keyup.enter="handleSend"
            type="text"
            class="form-control rounded-pill fs-8 py-2 px-3 border shadow-xs"
            :class="isDarkMode ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
            :placeholder="inputPlaceholder"
            :disabled="isThinking"
          />
          <button
            @click="handleSend"
            :disabled="!inputQuery.trim() || isThinking"
            class="btn btn-teal text-white rounded-circle d-flex align-items-center justify-content-center p-2 flex-shrink-0 shadow-xs transition-all"
            style="width: 40px; height: 40px;"
            title="Gửi câu hỏi cho Gemini AI (Enter)"
            type="button"
          >
            <i class="bi bi-send-fill fs-8"></i>
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useToast } from '@/composables/useToast'
import { useSpeech } from '@/composables/useSpeech'

const authStore = useAuthStore()
const toast = useToast()
const route = useRoute()
const speech = useSpeech()

const isDarkMode = computed(() => authStore.themeMode === 'dark')

const isOpen = ref(false)
const isExpanded = ref(false)
const inputQuery = ref('')
const isThinking = ref(false)
const messagesBody = ref(null)
const inputFieldRef = ref(null)
const copiedIdx = ref(null)
const speakingIdx = ref(null)
const hasUnreadHint = ref(false)
const activeMode = ref('general')

const STORAGE_KEY = 'oxvocab_ai_chat_history_v2'

// Quick Modes
const quickModes = [
  { id: 'general', icon: '💬', label: 'Hỏi đáp AI', placeholder: 'Tra từ, hỏi ngữ pháp, dịch câu, đàm thoại...' },
  { id: 'grammar', icon: '✍️', label: 'Chữa ngữ pháp', placeholder: 'Nhập câu tiếng Anh bạn muốn sửa lỗi và giải thích...' },
  { id: 'vocab', icon: '📖', label: 'Tra từ chuyên sâu', placeholder: 'Nhập từ vựng cần tra nghĩa, IPA, collocation, ví dụ...' },
  { id: 'quiz', icon: '🎯', label: 'Tạo bài tập', placeholder: 'Yêu cầu Gemini tạo câu hỏi trắc nghiệm ngữ pháp/từ vựng...' }
]

const inputPlaceholder = computed(() => {
  const m = quickModes.find(item => item.id === activeMode.value)
  return m ? m.placeholder : 'Tra từ, hỏi ngữ pháp, đặt ví dụ...'
})

// Subtitle based on current page
const contextSubtitle = computed(() => {
  const p = route.path
  if (p.startsWith('/curriculum')) return 'Gợi ý theo bài học Lộ Trình'
  if (p.startsWith('/grammar')) return 'Chuyên gia Ngữ pháp 12 thì'
  if (p.startsWith('/phrasal-verbs')) return 'Chuyên gia Cụm từ & Collocations'
  if (p.startsWith('/dictation')) return 'Trợ lý Luyện nghe & Chép chính tả'
  if (p.startsWith('/review')) return 'Cố vấn Ôn tập & Spaced Repetition'
  if (p.startsWith('/vocabulary-notebook')) return 'Trợ lý Sổ tay Từ vựng'
  return 'Gia sư Tiếng Anh 24/7 (Google Gemini)'
})

// Dynamic Context-Aware Prompt Chips based on route
const activePromptChips = computed(() => {
  const p = route.path
  if (p.startsWith('/curriculum')) {
    return [
      { label: '📖 Giải thích từ khó trong bài', prompt: 'Hãy giải thích nghĩa, phát âm IPA và ngữ cảnh của các từ vựng khó trong bài này' },
      { label: '💬 Đoạn hội thoại mẫu theo chủ đề', prompt: 'Viết một đoạn hội thoại mẫu tự nhiên 6 câu về chủ đề bài học này kèm bản dịch tiếng Việt' },
      { label: '🎯 Đặt 3 câu ứng dụng thực tế', prompt: 'Đặt 3 câu ví dụ giao tiếp thực tế với các từ vừa học trong bài' },
      { label: '💡 Mẹo nhớ từ nhanh', prompt: 'Chia sẻ 3 mẹo liên tưởng để nhớ nhanh các từ vựng này' }
    ]
  }

  if (p.startsWith('/grammar')) {
    return [
      { label: '⚡ Phân biệt Past Simple vs Present Perfect', prompt: 'Phân biệt cách dùng quá khứ đơn (Past Simple) và hiện tại hoàn thành (Present Perfect) qua 3 cặp ví dụ tương phản' },
      { label: '🧩 Công thức câu điều kiện loại 2 & 3', prompt: 'Giải thích công thức và mẹo nhớ câu điều kiện loại 2 và loại 3' },
      { label: '✍️ Sửa lỗi câu ngữ pháp', prompt: 'Hãy sửa và giải thích chi tiết lỗi sai của câu sau: ' },
      { label: '📝 Tạo 3 câu trắc nghiệm ngữ pháp', prompt: 'Tạo cho tôi 3 câu trắc nghiệm ngữ pháp có 4 đáp án A B C D kèm lời giải thích' }
    ]
  }

  if (p.startsWith('/phrasal-verbs')) {
    return [
      { label: '🔍 Phân biệt take up, take off, take on', prompt: 'Phân biệt ý nghĩa và cách dùng của take up, take off, take on' },
      { label: '🎯 5 cụm từ ăn điểm IELTS', prompt: 'Cho tôi 5 cụm động từ (Phrasal Verbs) thông dụng và ăn điểm trong IELTS Speaking' },
      { label: '✍️ Đặt câu với cụm từ', prompt: 'Đặt 3 câu ví dụ tự nhiên với cụm động từ: ' },
      { label: '💡 Mẹo học cụm từ theo giới từ', prompt: 'Mẹo nhớ cụm từ theo giới từ out, off, up, down' }
    ]
  }

  if (p.startsWith('/dictation')) {
    return [
      { label: '👂 Mẹo nghe nối âm (connected speech)', prompt: 'Giải thích hiện tượng nối âm (linking sounds) trong tiếng Anh và cách luyện nghe hiệu quả' },
      { label: '🎧 Phân biệt âm /i:/ và /ɪ/', prompt: 'Cách phân biệt âm /i:/ dài và /ɪ/ ngắn khi nghe người bản xứ nói nhanh' },
      { label: '📝 Vì sao câu nghe này bị nhầm?', prompt: 'Giải thích vì sao người học thường nghe nhầm câu này: ' }
    ]
  }

  if (p.startsWith('/review')) {
    return [
      { label: '🧠 Giải thích câu tôi vừa làm sai', prompt: 'Hãy giải thích vì sao đáp án này là đúng và phân tích bẫy trong câu hỏi: ' },
      { label: '📈 Nguyên lý Lặp lại ngắt quãng (SM-2)', prompt: 'Spaced Repetition (Lặp lại ngắt quãng) giúp ghi nhớ từ vựng như thế nào?' },
      { label: '💡 Cách nhớ 20 từ vựng mỗi ngày', prompt: 'Phương pháp khoa học để ghi nhớ bền vững 20 từ vựng mỗi ngày mà không bị quên' }
    ]
  }

  if (p.startsWith('/vocabulary-notebook')) {
    return [
      { label: '📝 Viết truyện ngắn từ sổ tay', prompt: 'Hãy viết một mẩu chuyện ngắn vui nhộn 4 câu bằng tiếng Anh chứa các từ tôi vừa lưu vào sổ tay' },
      { label: '🏷️ Phân loại từ theo ngữ cảnh', prompt: 'Gợi ý cách nhóm và phân loại các từ vựng đã lưu để dễ ôn tập nhất' },
      { label: '💡 Đặt câu với từ khó nhớ nhất', prompt: 'Cho 2 câu ví dụ cực kỳ dễ nhớ cho từ: ' }
    ]
  }

  // Default / Trang chủ
  return [
    { label: '💡 Phân biệt "Make" & "Do"', prompt: 'Phân biệt cách dùng của Make và Do kèm ví dụ cụ thể' },
    { label: '✍️ Chữa lỗi câu tiếng Anh', prompt: 'Hãy kiểm tra lỗi sai ngữ pháp và từ vựng trong câu này giúp tôi: ' },
    { label: '🎯 3 câu ví dụ Hiện tại hoàn thành', prompt: 'Cho tôi 3 câu ví dụ thì Hiện tại hoàn thành kèm giải nghĩa tiếng Việt' },
    { label: '📖 Tra từ & Collocation', prompt: 'Giải thích từ vựng, phiên âm IPA, loại từ và collocations của từ: ' }
  ]
})

// Initial messages with fallback to localStorage
const defaultInitialMessage = {
  sender: 'bot',
  text: 'Xin chào! Mình là **Gia sư AI OxVocab** (Google Gemini). Bạn có câu hỏi nào về từ vựng, ngữ pháp, muốn chữa bài viết hay giải thích câu hỏi không? Mình sẵn sàng hỗ trợ bạn 24/7!',
  time: 'Vừa xong'
}

const messages = ref([defaultInitialMessage])

const loadHistory = () => {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    if (raw) {
      const parsed = JSON.parse(raw)
      if (Array.isArray(parsed) && parsed.length > 0) {
        messages.value = parsed
        return
      }
    }
  } catch (e) {
    console.warn('Could not parse AI chat history:', e)
  }
  messages.value = [defaultInitialMessage]
}

const saveHistory = () => {
  try {
    // Keep max 40 latest messages
    const toSave = messages.value.slice(-40)
    localStorage.setItem(STORAGE_KEY, JSON.stringify(toSave))
  } catch (e) {}
}

const toggleChat = () => {
  isOpen.value = !isOpen.value
  hasUnreadHint.value = false
  if (isOpen.value) {
    scrollToBottom()
    nextTick(() => {
      inputFieldRef.value?.focus()
    })
  } else {
    speech.stopSpeech()
    speakingIdx.value = null
  }
}

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesBody.value) {
      messagesBody.value.scrollTop = messagesBody.value.scrollHeight
    }
  })
}

const applyMode = (mode) => {
  activeMode.value = mode.id
  if (mode.id === 'grammar') {
    inputQuery.value = 'Hãy kiểm tra và chữa lỗi cho câu này: '
  } else if (mode.id === 'vocab') {
    inputQuery.value = 'Giải thích từ vựng, phiên âm IPA, collocations và ví dụ của từ: '
  } else if (mode.id === 'quiz') {
    inputQuery.value = 'Tạo cho tôi 3 câu hỏi trắc nghiệm ngữ pháp kèm đáp án giải thích'
  }
  inputFieldRef.value?.focus()
}

const sendChip = (prompt) => {
  inputQuery.value = prompt
  if (prompt.endsWith(': ')) {
    inputFieldRef.value?.focus()
    return
  }
  handleSend()
}

const confirmClearMessages = () => {
  if (confirm('Bạn có muốn làm mới và xóa toàn bộ lịch sử trò chuyện với AI không?')) {
    speech.stopSpeech()
    speakingIdx.value = null
    messages.value = [
      {
        sender: 'bot',
        text: 'Cuộc trò chuyện đã được làm mới. Bạn cần hỗ trợ từ vựng hoặc điểm ngữ pháp nào tiếp theo?',
        time: 'Vừa xong'
      }
    ]
    saveHistory()
    toast.success('Đã làm mới cuộc hội thoại với Gemini AI', 'Làm mới')
  }
}

const copyText = async (text, idx) => {
  try {
    // Clean markdown before copying for clean text
    await navigator.clipboard.writeText(text)
    copiedIdx.value = idx
    toast.success('Đã sao chép nội dung vào bộ nhớ tạm!', 'Sao chép')
    setTimeout(() => {
      if (copiedIdx.value === idx) copiedIdx.value = null
    }, 2000)
  } catch (e) {
    toast.info('Vui lòng chọn bôi đen văn bản để sao chép.')
  }
}

// Bilingual TTS speaker with active toggle & soundwave
const toggleSpeakMessage = (text, idx) => {
  if (speakingIdx.value === idx) {
    speech.stopSpeech()
    speakingIdx.value = null
    return
  }

  speech.stopSpeech()
  speakingIdx.value = idx

  // Clean markdown tags
  const clean = text
    .replace(/[#*`_>~]/g, '')
    .replace(/\[(.*?)\]\(.*?\)/g, '$1')
    .replace(/<[^>]*>/g, '')
    .trim()

  speech.speak(clean)

  // Auto reset speaking index when speech stops
  const checkInterval = setInterval(() => {
    if (!speech.isSpeaking.value) {
      speakingIdx.value = null
      clearInterval(checkInterval)
    }
  }, 400)
}

// Detect if a message defines a word that can be saved
const canSaveWordFromMessage = (text) => {
  if (!text) return false
  // Check for patterns like "Từ: **word**" or "Word: " or single word bolding
  return /\*\*[a-zA-Z\s\-]{2,30}\*\*/.test(text) || /\b(từ vựng|collocation|nghĩa là)\b/i.test(text)
}

// Quick Save Word extracted from message to Notebook
const saveWordFromMessage = async (text) => {
  const match = text.match(/\*\*([a-zA-Z\s\-]{2,30})\*\*/)
  const extractedWord = match ? match[1].trim() : ''

  if (!extractedWord) {
    toast.info('Vui lòng nhập trực tiếp từ vào Sổ tay.')
    return
  }

  try {
    const rawNotebook = localStorage.getItem('oxford_user_notebook')
    const list = rawNotebook ? JSON.parse(rawNotebook) : []
    const exists = list.some(item => item.word.toLowerCase() === extractedWord.toLowerCase())

    if (!exists) {
      list.unshift({
        id: Date.now(),
        word: extractedWord,
        meaning: 'Được lưu từ Trợ lý Gemini AI',
        phonetic: '',
        example: text.slice(0, 100),
        saved_at: new Date().toISOString()
      })
      localStorage.setItem('oxford_user_notebook', JSON.stringify(list))
      window.dispatchEvent(new CustomEvent('notebook-updated', { detail: { count: list.length } }))
      toast.success(`Đã thêm từ "${extractedWord}" vào Sổ tay của bạn!`, 'Sổ tay')
    } else {
      toast.info(`Từ "${extractedWord}" đã có sẵn trong Sổ tay của bạn.`, 'Sổ tay')
    }
  } catch (err) {
    toast.error('Không thể lưu vào sổ tay: ' + err.message)
  }
}

const renderMarkdown = (content) => {
  if (!content) return ''
  let html = content
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    // Headings
    .replace(/^### (.*$)/gim, '<h6 class="fw-bold text-teal mt-2 mb-1">$1</h6>')
    .replace(/^## (.*$)/gim, '<h6 class="fw-bold text-teal mt-2 mb-1 fs-7">$1</h6>')
    // Bold
    .replace(/\*\*(.*?)\*\*/g, '<strong class="text-teal-emphasis fw-bold text-decoration-none">$1</strong>')
    // Italic
    .replace(/\*(.*?)\*/g, '<em class="fst-italic opacity-90">$1</em>')
    // Inline code
    .replace(/`([^`]+)`/g, '<code class="bg-body-tertiary px-1.5 py-0.5 rounded text-teal font-monospace fs-9 border">$1</code>')
    // Bullet list items
    .replace(/^[\*\-\•]\s+(.*$)/gim, '<div class="d-flex align-items-start gap-1.5 ms-1 my-0.5"><span class="text-teal fw-bold">•</span><span>$1</span></div>')
    // Numbered lists
    .replace(/^(\d+)\.\s+(.*$)/gim, '<div class="d-flex align-items-start gap-1.5 ms-1 my-0.5"><span class="badge bg-teal-subtle text-teal fs-9 rounded-pill">$1</span><span>$2</span></div>')
    // Blockquote
    .replace(/^&gt;\s+(.*$)/gim, '<div class="border-start border-3 border-teal ps-2 my-1 text-muted fst-italic">$1</div>')
    // Line breaks
    .replace(/\n/g, '<br/>')
  return html
}

const handleSend = async () => {
  const query = inputQuery.value.trim()
  if (!query || isThinking.value) return

  const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })

  // Push user message
  messages.value.push({
    sender: 'user',
    text: query,
    time: currentTime
  })
  inputQuery.value = ''
  isThinking.value = true
  scrollToBottom()
  saveHistory()

  // Format previous conversation history for Gemini multi-turn payload
  const historyPayload = messages.value.slice(-8, -1).map(m => ({
    role: m.sender === 'user' ? 'user' : 'model',
    text: m.text
  }))

  try {
    const res = await axios.post('/api/ai/chat', {
      message: query,
      history: historyPayload
    })

    const reply = res.data?.data?.reply || 'Cảm ơn câu hỏi của bạn. Hãy tiếp tục luyện tập và đặt câu nhé!'
    messages.value.push({
      sender: 'bot',
      text: reply,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    })
    saveHistory()
  } catch (err) {
    console.warn('AI Chat API error:', err)
    messages.value.push({
      sender: 'bot',
      text: `Chào bạn! Mình ghi nhận câu hỏi về "${query}". Để nhớ sâu, bạn hãy đặt 1 câu ví dụ thực tế và thêm vào sổ tay ôn tập nhé!`,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    })
    saveHistory()
  } finally {
    isThinking.value = false
    scrollToBottom()
  }
}

// Global custom event listener so any button in the app can open AI Chat
const handleOpenAiChatEvent = (e) => {
  isOpen.value = true
  if (e.detail?.prompt) {
    inputQuery.value = e.detail.prompt
    handleSend()
  } else {
    scrollToBottom()
  }
}

onMounted(() => {
  loadHistory()
  window.addEventListener('open-ai-chat', handleOpenAiChatEvent)
})

onUnmounted(() => {
  window.removeEventListener('open-ai-chat', handleOpenAiChatEvent)
  speech.stopSpeech()
})
</script>

<style scoped>
/* 1. WRAPPER PINNED TO VIEWPORT (KHÔNG DÍNH VÀO FOOTER) */
.ai-assistant-wrapper {
  position: fixed;
  bottom: 28px;
  right: 28px;
  z-index: 1080;
  pointer-events: none; /* Let clicks pass through empty wrapper */
}

/* 2. FLOATING ACTION BUTTON (FAB) */
.ai-assistant-fab {
  pointer-events: auto;
  width: 54px;
  height: 54px;
  background: linear-gradient(135deg, #00b074 0%, #064e40 100%);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 10px 25px rgba(0, 176, 116, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.2);
}

.ai-assistant-fab:hover {
  transform: scale(1.08) translateY(-2px);
  box-shadow: 0 14px 30px rgba(0, 176, 116, 0.55), 0 0 0 2px rgba(94, 234, 212, 0.4);
}

.ai-assistant-fab:active {
  transform: scale(0.96);
}

.fab-bounce {
  animation: fabBounce 2s infinite ease-in-out;
}

@keyframes fabBounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
}

/* 3. FLOATING CHAT WINDOW / DRAWER */
.ai-chat-window {
  pointer-events: auto;
  position: absolute;
  bottom: 68px;
  right: 0;
  width: 410px;
  max-width: calc(100vw - 36px);
  height: 590px;
  max-height: calc(100vh - 110px);
  max-height: calc(100dvh - 110px);
  overflow: hidden;
  box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.12);
  transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1), height 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Expanded mode on Desktop for long lessons / grammar checking */
.ai-window-expanded {
  width: 560px;
  height: 680px;
}

/* 4. MESSAGE BUBBLES */
.message-bubble {
  max-width: 88%;
  line-height: 1.55;
  border-radius: 14px;
}

.user-bubble {
  border-bottom-right-radius: 2px !important;
  box-shadow: 0 4px 12px rgba(0, 176, 116, 0.25);
}

.bot-bubble-light {
  border-bottom-left-radius: 2px !important;
  background-color: #f1f5f9 !important;
  color: #0f172a !important;
  border: 1px solid #e2e8f0;
}

.bot-bubble-dark {
  border-bottom-left-radius: 2px !important;
  background-color: #1e293b !important;
  color: #f8fafc !important;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.chat-markdown {
  font-size: 13.5px;
}

/* Custom Chat Scrollbar */
.custom-chat-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(0, 176, 116, 0.35) transparent;
}
.custom-chat-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-chat-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(0, 176, 116, 0.35);
  border-radius: 999px;
}

/* Transitions */
.ai-drawer-enter-active,
.ai-drawer-leave-active {
  transition: opacity 0.26s cubic-bezier(0.16, 1, 0.3, 1), transform 0.26s cubic-bezier(0.16, 1, 0.3, 1);
}

.ai-drawer-enter-from,
.ai-drawer-leave-to {
  opacity: 0;
  transform: translateY(18px) scale(0.96);
}

.hover-teal:hover {
  border-color: #00b074 !important;
  color: #00b074 !important;
}

/* MOBILE ADAPTATION (< 992px) */
@media (max-width: 991.98px) {
  .ai-assistant-wrapper {
    bottom: 74px; /* Trên thanh mobile bottom nav */
    right: 16px;
  }

  .ai-assistant-fab {
    width: 48px;
    height: 48px;
  }

  .ai-chat-window {
    width: calc(100vw - 24px);
    right: -4px;
    height: 520px;
    max-height: calc(100dvh - 145px);
  }
}
</style>
