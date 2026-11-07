<template>
  <div class="ai-assistant-wrapper">
    <!-- Floating Trigger Button (Bottom Right) -->
    <button
      @click="isOpen = !isOpen"
      class="ai-assistant-btn shadow-lg rounded-circle d-flex align-items-center justify-content-center text-white border-0 transition-all"
      :class="{ 'btn-open': isOpen }"
      title="Gia sư AI Tiếng Anh 24/7"
      aria-label="Open AI Assistant"
    >
      <i v-if="!isOpen" class="bi bi-robot fs-5"></i>
      <i v-else class="bi bi-x-lg fs-5"></i>
      <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-warning border border-light rounded-circle animate-pulse" style="transform: translate(-30%, 25%) !important;"></span>
    </button>

    <!-- AI Chat Window -->
    <div
      v-if="isOpen"
      class="ai-chat-window shadow-2xl rounded-4 border d-flex flex-column"
      :class="isDarkMode ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
    >
      <!-- Chat Header -->
      <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-teal text-white rounded-top-4">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <div class="rounded-circle bg-white text-teal d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
            <i class="bi bi-stars fs-6"></i>
          </div>
          <div class="min-w-0">
            <div class="fw-bold fs-7 leading-tight text-truncate">OxVocab AI Tutor 24/7</div>
            <div class="fs-9 opacity-75 text-truncate">Trợ lý Tiếng Anh thông minh & Chữa ngữ pháp</div>
          </div>
        </div>
        <div class="d-flex align-items-center gap-1.5">
          <button @click="clearMessages" class="btn btn-sm btn-link text-white text-decoration-none p-1 fs-8" title="Xóa cuộc trò chuyện">
            <i class="bi bi-trash3"></i>
          </button>
          <button @click="isOpen = false" class="btn btn-sm btn-link text-white text-decoration-none p-1 fs-7" title="Đóng">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>

      <!-- Quick Prompt Suggestion Chips -->
      <div class="px-2.5 py-2 border-bottom overflow-x-auto no-scrollbar d-flex gap-1.5 bg-body-tertiary">
        <button
          v-for="(chip, idx) in promptChips"
          :key="idx"
          @click="sendChip(chip)"
          class="btn btn-xs rounded-pill px-2.5 py-1 text-nowrap fs-9 border bg-white shadow-2xs hover-shadow"
        >
          {{ chip }}
        </button>
      </div>

      <!-- Messages Body -->
      <div ref="messagesBody" class="flex-grow-1 p-3 overflow-y-auto d-flex flex-column gap-2.5 messages-area">
        <div
          v-for="(msg, idx) in messages"
          :key="idx"
          class="d-flex"
          :class="msg.sender === 'user' ? 'justify-content-end' : 'justify-content-start'"
        >
          <div
            class="p-2.5 rounded-3 max-w-xs fs-8 message-bubble"
            :class="msg.sender === 'user' ? 'bg-teal text-white rounded-br-0' : (isDarkMode ? 'bg-secondary text-white' : 'bg-body-secondary text-dark rounded-bl-0')"
          >
            <div class="message-content" style="white-space: pre-wrap;">{{ msg.text }}</div>
            <div class="d-flex justify-content-between align-items-center mt-1 pt-1 opacity-75 fs-9 border-top border-white/20">
              <span>{{ msg.time }}</span>
              <button
                v-if="msg.sender === 'bot'"
                @click="speak(msg.text)"
                class="btn btn-xs btn-link text-white p-0 text-decoration-none ms-2"
                title="Nghe AI đọc"
              >
                <i class="bi bi-volume-up-fill"></i>
              </button>
            </div>
          </div>
        </div>

        <div v-if="isThinking" class="d-flex justify-content-start">
          <div class="p-2.5 rounded-3 bg-body-secondary text-muted fs-8 d-flex align-items-center gap-2">
            <div class="spinner-grow spinner-grow-sm text-teal" role="status"></div>
            <span>Gia sư AI đang soạn câu trả lời...</span>
          </div>
        </div>
      </div>

      <!-- Chat Footer Input -->
      <div class="p-2.5 border-top d-flex gap-2 align-items-center">
        <input
          v-model="inputQuery"
          @keyup.enter="handleSend"
          type="text"
          class="form-control rounded-pill fs-8 py-2 px-3"
          placeholder="Hỏi từ vựng, chữa lỗi ngữ pháp, dịch câu..."
          :disabled="isThinking"
        />
        <button
          @click="handleSend"
          :disabled="!inputQuery.trim() || isThinking"
          class="btn btn-teal text-white rounded-circle d-flex align-items-center justify-content-center p-2 flex-shrink-0 shadow-xs"
          style="width: 36px; height: 36px;"
        >
          <i class="bi bi-send-fill fs-8"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const isDarkMode = computed(() => authStore.themeMode === 'dark')

const isOpen = ref(false)
const inputQuery = ref('')
const isThinking = ref(false)
const messagesBody = ref(null)

const promptChips = [
  '💡 Phân biệt "make" và "do"',
  '✍️ Chữa giúp tôi câu này',
  '🎯 3 câu ví dụ về Present Perfect',
  '🗣️ Mẹo luyện phản xạ giao tiếp'
]

const messages = ref([
  {
    sender: 'bot',
    text: 'Xin chào! Mình là Gia sư AI OxVocab. Bạn có câu hỏi nào về từ vựng, công thức ngữ pháp hay muốn chữa câu tiếng Anh không?',
    time: 'Vừa xong'
  }
])

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesBody.value) {
      messagesBody.value.scrollTop = messagesBody.value.scrollHeight
    }
  })
}

const sendChip = (chip) => {
  inputQuery.value = chip
  handleSend()
}

const clearMessages = () => {
  messages.value = [
    {
      sender: 'bot',
      text: 'Đã làm mới cuộc hội thoại. Bạn muốn mình hỗ trợ gì tiếp theo?',
      time: 'Vừa xong'
    }
  ]
}

const speak = (text) => {
  if (!text || !window.speechSynthesis) return
  window.speechSynthesis.cancel()
  const clean = text.replace(/[^\w\s.,?!']/gi, ' ')
  const utter = new SpeechSynthesisUtterance(clean)
  utter.lang = 'en-US'
  utter.rate = 0.9
  window.speechSynthesis.speak(utter)
}

const handleSend = async () => {
  const query = inputQuery.value.trim()
  if (!query || isThinking.value) return

  messages.value.push({
    sender: 'user',
    text: query,
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  })
  inputQuery.value = ''
  isThinking.value = true
  scrollToBottom()

  // Generate contextual AI answer
  setTimeout(() => {
    let reply = ''
    const lower = query.toLowerCase()

    if (lower.includes('make') && lower.includes('do')) {
      reply = `📌 Quy tắc phân biệt "Make" và "Do":\n• "Do" thường đi với hành động, công việc, bổn phận (không tạo ra sản phẩm mới):\n  - do homework, do research, do business, do the dishes.\n• "Make" thường chỉ việc tạo ra, sản xuất, hoặc gây ra một kết quả cụ thể:\n  - make a cake, make a mistake, make friends, make a decision.`
    } else if (lower.includes('chữa') || lower.includes('check') || lower.includes('sai')) {
      reply = `Để mình phân tích câu của bạn:\n• Cấu trúc: Nên chú ý chia thì của động từ chính và sự hòa hợp chủ vị (Subject-Verb Agreement).\n• Gợi ý diễn đạt tự nhiên hơn: Hãy dùng collocation chuẩn bản xứ để câu văn mượt mà hơn!`
    } else if (lower.includes('present perfect') || lower.includes('hiện tại hoàn thành')) {
      reply = `🎯 3 câu ví dụ thì Hiện tại hoàn thành (S + have/has + V3/ed):\n1. "I have studied English for 5 years." (Hành động bắt đầu trong quá khứ và vẫn tiếp diễn ở hiện tại)\n2. "She has already finished her project." (Hành động vừa mới hoàn tất)\n3. "Have you ever traveled to London?" (Hỏi về trải nghiệm sống)`
    } else {
      reply = `Cảm ơn câu hỏi của bạn về "${query}"!\n• Giải nghĩa: Đây là một chủ đề rất hay trong tiếng Anh.\n• Ví dụ ứng dụng: Bạn có thể đưa từ này vào câu ví dụ cụ thể để ghi nhớ lâu hơn nhé.\n• Mẹo học: Hãy lưu từ vào mục "Sổ tay từ vựng" để ôn lại hàng ngày!`
    }

    messages.value.push({
      sender: 'bot',
      text: reply,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    })
    isThinking.value = false
    scrollToBottom()
  }, 700)
}
</script>

<style scoped>
.ai-assistant-wrapper {
  position: fixed;
  bottom: 80px;
  right: 20px;
  z-index: 1045;
}

@media (min-width: 768px) {
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
  box-shadow: 0 8px 24px rgba(0, 176, 116, 0.45) !important;
}

.ai-chat-window {
  position: absolute;
  bottom: 64px;
  right: 0;
  width: 360px;
  max-width: calc(100vw - 32px);
  height: 480px;
  max-height: calc(100vh - 120px);
  animation: aiWindowPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  overflow: hidden;
}

.message-bubble {
  max-width: 82%;
  line-height: 1.45;
}

@keyframes aiWindowPop {
  from {
    opacity: 0;
    transform: translateY(12px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>
