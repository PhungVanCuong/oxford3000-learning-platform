<template>
  <div class="ai-assistant-wrapper">
    <!-- Floating Trigger Button (Bottom Right) -->
    <button
      @click="toggleChat"
      class="ai-assistant-btn shadow-lg rounded-circle d-flex align-items-center justify-content-center text-white border-0 transition-all"
      :class="{ 'btn-open': isOpen }"
      title="Gia sư AI Tiếng Anh 24/7 (Google Gemini)"
      aria-label="Open AI Assistant"
    >
      <i v-if="!isOpen" class="bi bi-robot fs-5"></i>
      <i v-else class="bi bi-x-lg fs-5"></i>
      <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-warning border border-light rounded-circle animate-pulse" style="transform: translate(-30%, 25%) !important;"></span>
    </button>

    <!-- AI Chat Window / Drawer -->
    <div
      v-if="isOpen"
      class="ai-chat-window shadow-2xl rounded-4 border d-flex flex-column"
      :class="isDarkMode ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
    >
      <!-- Chat Header -->
      <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-teal text-white rounded-top-4 flex-shrink-0">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <div class="rounded-circle bg-white text-teal d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 34px; height: 34px;">
            <i class="bi bi-stars fs-6 text-warning"></i>
          </div>
          <div class="min-w-0">
            <div class="fw-bold fs-7 leading-tight text-truncate d-flex align-items-center gap-1.5">
              <span>OxVocab AI Tutor</span>
              <span class="badge bg-warning text-dark fs-9 rounded-pill px-1.5 py-0.5 fw-bold">Gemini AI</span>
            </div>
            <div class="fs-9 opacity-90 text-truncate text-white-50">Tra từ, giải nghĩa ngữ cảnh, chữa ngữ pháp</div>
          </div>
        </div>
        <div class="d-flex align-items-center gap-1">
          <button @click="clearMessages" class="btn btn-sm btn-link text-white text-decoration-none p-1 fs-8" title="Xóa cuộc trò chuyện">
            <i class="bi bi-trash3"></i>
          </button>
          <button @click="isOpen = false" class="btn btn-sm btn-link text-white text-decoration-none p-1 fs-7" title="Đóng">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>

      <!-- Quick Prompt Suggestion Chips -->
      <div class="px-2.5 py-2 border-bottom overflow-x-auto no-scrollbar d-flex gap-1.5 bg-body-tertiary flex-shrink-0">
        <button
          v-for="(chip, idx) in promptChips"
          :key="idx"
          @click="sendChip(chip.prompt)"
          class="btn btn-xs rounded-pill px-2.5 py-1 text-nowrap fs-9 border bg-white shadow-2xs hover-shadow d-inline-flex align-items-center gap-1"
        >
          <span>{{ chip.label }}</span>
        </button>
      </div>

      <!-- Messages Body -->
      <div ref="messagesBody" class="flex-grow-1 p-3 overflow-y-auto d-flex flex-column gap-2.5 messages-area custom-chat-scrollbar">
        <div
          v-for="(msg, idx) in messages"
          :key="idx"
          class="d-flex"
          :class="msg.sender === 'user' ? 'justify-content-end' : 'justify-content-start'"
        >
          <div
            class="p-2.5 rounded-3 max-w-xs fs-8 message-bubble shadow-2xs"
            :class="msg.sender === 'user' ? 'bg-teal text-white rounded-br-0' : (isDarkMode ? 'bg-secondary text-white' : 'bg-body-secondary text-dark rounded-bl-0')"
          >
            <!-- Formatted Markdown Content -->
            <div
              v-if="msg.sender === 'bot'"
              class="message-content chat-markdown"
              v-html="renderMarkdown(msg.text)"
            ></div>
            <div v-else class="message-content" style="white-space: pre-wrap;">{{ msg.text }}</div>

            <!-- Footer: Timestamp + TTS Read + Copy button -->
            <div class="d-flex justify-content-between align-items-center mt-1.5 pt-1 opacity-75 fs-9 border-top border-black/10">
              <span class="fs-9">{{ msg.time }}</span>
              <div v-if="msg.sender === 'bot'" class="d-flex align-items-center gap-2 ms-2">
                <button
                  @click="copyText(msg.text, idx)"
                  class="btn btn-xs btn-link p-0 text-decoration-none"
                  :class="isDarkMode ? 'text-white' : 'text-teal'"
                  title="Sao chép câu trả lời"
                >
                  <i :class="copiedIdx === idx ? 'bi bi-check-lg text-success' : 'bi bi-clipboard'"></i>
                </button>
                <button
                  @click="speak(msg.text)"
                  class="btn btn-xs btn-link p-0 text-decoration-none"
                  :class="isDarkMode ? 'text-white' : 'text-teal'"
                  title="Nghe AI đọc tiếng Anh"
                >
                  <i class="bi bi-volume-up-fill"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- AI Thinking / Generating indicator -->
        <div v-if="isThinking" class="d-flex justify-content-start">
          <div class="p-2.5 rounded-3 bg-body-secondary text-muted fs-8 d-flex align-items-center gap-2 shadow-2xs">
            <div class="spinner-grow spinner-grow-sm text-teal" role="status"></div>
            <span>Gemini AI đang soạn câu trả lời chuyên sâu...</span>
          </div>
        </div>
      </div>

      <!-- Chat Footer Input -->
      <div class="p-2.5 border-top d-flex gap-2 align-items-center flex-shrink-0 bg-body-tertiary">
        <input
          v-model="inputQuery"
          @keyup.enter="handleSend"
          type="text"
          class="form-control rounded-pill fs-8 py-2 px-3 border shadow-xs"
          placeholder="Tra từ, hỏi ngữ pháp, đặt ví dụ, dịch câu..."
          :disabled="isThinking"
        />
        <button
          @click="handleSend"
          :disabled="!inputQuery.trim() || isThinking"
          class="btn btn-teal text-white rounded-circle d-flex align-items-center justify-content-center p-2 flex-shrink-0 shadow-xs"
          style="width: 38px; height: 38px;"
          title="Gửi câu hỏi cho Gemini AI"
        >
          <i class="bi bi-send-fill fs-8"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useToast } from '@/composables/useToast'

const authStore = useAuthStore()
const toast = useToast()
const isDarkMode = computed(() => authStore.themeMode === 'dark')

const isOpen = ref(false)
const inputQuery = ref('')
const isThinking = ref(false)
const messagesBody = ref(null)
const copiedIdx = ref(null)

const promptChips = [
  { label: '💡 Phân biệt "Make" & "Do"', prompt: 'Phân biệt cách dùng của Make và Do' },
  { label: '✍️ Sửa lỗi câu tiếng Anh', prompt: 'Hãy sửa và giải thích lỗi sai của câu này giúp tôi: ' },
  { label: '🎯 3 câu ví dụ Hiện tại hoàn thành', prompt: 'Cho tôi 3 câu ví dụ thì Hiện tại hoàn thành kèm giải nghĩa chi tiết' },
  { label: '📖 Tra từ vựng & Collocation', prompt: 'Giải thích từ vựng, phiên âm IPA, các cụm từ đi kèm (collocations) của từ: ' }
]

const messages = ref([
  {
    sender: 'bot',
    text: 'Xin chào! Mình là Gia sư AI OxVocab (hỗ trợ bởi Google Gemini). Bạn có câu hỏi nào về từ vựng, cụm từ, cách phân biệt từ đồng nghĩa hay muốn chữa ngữ pháp tiếng Anh không?',
    time: 'Vừa xong'
  }
])

const toggleChat = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value) {
    scrollToBottom()
  }
}

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesBody.value) {
      messagesBody.value.scrollTop = messagesBody.value.scrollHeight
    }
  })
}

const sendChip = (prompt) => {
  inputQuery.value = prompt
  if (prompt.endsWith(': ')) {
    // Focus input so user can append word
    return
  }
  handleSend()
}

const clearMessages = () => {
  messages.value = [
    {
      sender: 'bot',
      text: 'Đã làm mới cuộc hội thoại. Bạn muốn mình giải đáp hoặc hướng dẫn từ vựng nào tiếp theo?',
      time: 'Vừa xong'
    }
  ]
}

const copyText = async (text, idx) => {
  try {
    await navigator.clipboard.writeText(text)
    copiedIdx.value = idx
    toast.success('Đã sao chép nội dung vào bộ nhớ đệm!', 'Sao chép')
    setTimeout(() => {
      if (copiedIdx.value === idx) copiedIdx.value = null
    }, 2000)
  } catch (e) {
    toast.info('Vui lòng chọn bôi đen để sao chép.')
  }
}

const speak = (text) => {
  if (!text || !window.speechSynthesis) return
  window.speechSynthesis.cancel()
  // Clean markdown symbols for natural TTS speech
  const clean = text
    .replace(/[#*`_>~]/g, '')
    .replace(/\(.*?\)/g, '')
    .trim()
  const utter = new SpeechSynthesisUtterance(clean)
  utter.lang = 'en-US'
  utter.rate = 0.92
  window.speechSynthesis.speak(utter)
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
    .replace(/\*\*(.*?)\*\*/g, '<strong class="text-dark-emphasis fw-bold">$1</strong>')
    // Italic
    .replace(/\*(.*?)\*/g, '<em class="fst-italic">$1</em>')
    // Inline code
    .replace(/`([^`]+)`/g, '<code class="bg-body-tertiary px-1 py-0.5 rounded text-teal font-monospace fs-9 border">$1</code>')
    // Bullet list items
    .replace(/^[\*\-\•]\s+(.*$)/gim, '<div class="d-flex align-items-start gap-1.5 ms-1 my-0.5"><span class="text-teal">•</span><span>$1</span></div>')
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

    const reply = res.data?.data?.reply || 'Cảm ơn câu hỏi của bạn. Hãy tiếp tục đặt câu để nâng cao trình độ nhé!'
    messages.value.push({
      sender: 'bot',
      text: reply,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    })
  } catch (err) {
    console.warn('AI Chat API error:', err)
    messages.value.push({
      sender: 'bot',
      text: `Chào bạn! Mình ghi nhận câu hỏi về "${query}". Để nhớ sâu, bạn hãy đặt 1 câu ví dụ thực tế và thêm vào sổ tay ôn tập nhé!`,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    })
  } finally {
    isThinking.value = false
    scrollToBottom()
  }
}

// Global custom event listener so any page or bottom nav button can trigger AI Chat
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
  window.addEventListener('open-ai-chat', handleOpenAiChatEvent)
})

onUnmounted(() => {
  window.removeEventListener('open-ai-chat', handleOpenAiChatEvent)
})
</script>

<style scoped>
.ai-assistant-wrapper {
  position: fixed;
  bottom: 84px;
  right: 18px;
  z-index: 1045;
}

@media (min-width: 992px) {
  .ai-assistant-wrapper {
    bottom: 24px;
    right: 24px;
  }
}

.ai-assistant-btn {
  width: 52px;
  height: 52px;
  background: linear-gradient(135deg, #00b074 0%, #064e40 100%);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.ai-assistant-btn:hover {
  transform: scale(1.08);
  box-shadow: 0 10px 25px rgba(0, 176, 116, 0.45) !important;
}

.ai-chat-window {
  position: absolute;
  bottom: 64px;
  right: 0;
  width: 380px;
  max-width: calc(100vw - 32px);
  height: 520px;
  max-height: calc(100vh - 140px);
  max-height: calc(100dvh - 140px);
  animation: aiWindowPop 0.24s cubic-bezier(0.16, 1, 0.3, 1);
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.message-bubble {
  max-width: 86%;
  line-height: 1.5;
  word-break: break-word;
}

.chat-markdown {
  font-size: 13px;
}

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

@keyframes aiWindowPop {
  from {
    opacity: 0;
    transform: translateY(14px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@media (max-width: 575.98px) {
  .ai-chat-window {
    width: calc(100vw - 24px);
    right: -6px;
    height: 500px;
  }
}
</style>
