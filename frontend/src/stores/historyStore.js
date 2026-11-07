import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useHistoryStore = defineStore('historyStore', () => {
  // Pre-seeded initial sample data khớp 100% với ảnh người dùng cung cấp
  const defaultInitialRecords = [
    {
      id: 'hist_to_be_pronouns',
      title: 'To be & subject pronouns',
      category: 'Ngữ pháp',
      date: '11:30 07/11/2026',
      total_questions: 20,
      score: 0,
      passed: false,
      percentage: 0,
      status_note: '0/20 câu • bị ngắt giữa chừng',
      difficulty: 'Dễ',
      stars: 0,
      questions: [
        {
          num: 1,
          status: 'incorrect', // 'incorrect' (Sai), 'timeout' (Hết giờ), 'unanswered' (Chưa làm), 'correct' (Đúng)
          status_label: 'Sai',
          type_label: 'Chọn bản dịch đúng nhất.',
          prompt: 'Các bạn của bạn có ở thư viện không?',
          user_answer: 'Your friends at the library?',
          correct_answer: 'Are your friends at the library?',
          explanation: 'Are đảo lên trước chủ ngữ your friends, phần còn lại của câu giữ nguyên trật tự. Dạng động từ được lựa chọn cho biết chủ ngữ đang được giới thiệu, miêu tả hoặc xác định trạng thái ở hiện tại.'
        },
        {
          num: 2,
          status: 'timeout',
          status_label: 'Hết giờ',
          type_label: 'Chọn phương án đúng.',
          prompt: '___ am eleven years old.',
          user_answer: null, // Bạn bỏ trống
          correct_answer: 'I',
          explanation: 'Chỉ có I đi được với am; me là đại từ tân ngữ, my và mine chỉ sở hữu. Ở đầu câu này cần một đại từ làm chủ ngữ. Hình thức đại từ được chọn phải phù hợp với người hoặc vật mà câu nói đến.'
        },
        {
          num: 3,
          status: 'incorrect',
          status_label: 'Sai',
          type_label: 'Chọn phương án đúng.',
          prompt: 'She and I ___ friends.',
          user_answer: 'She and I am friends.',
          correct_answer: 'She and I are friends.',
          explanation: 'Câu này có nghĩa là "Cô ấy và tôi là bạn." She and I là chủ ngữ số nhiều, tương đương we, nên dùng are. Khi kết hợp hai chủ ngữ ngôi thứ ba và ngôi thứ nhất bằng liên từ and, động từ to be chia ở dạng số nhiều.'
        },
        {
          num: 4,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Chọn phương án đúng.',
          prompt: 'We ___ students at this international school.',
          user_answer: null,
          correct_answer: 'are',
          explanation: 'Chủ ngữ We là ngôi thứ nhất số nhiều, đi với động từ to be "are".'
        },
        {
          num: 5,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Điền từ thích hợp.',
          prompt: 'The children ___ very excited today.',
          user_answer: null,
          correct_answer: 'are',
          explanation: 'Children là danh từ số nhiều bất quy tắc (số ít là child), đi với động từ to be "are".'
        },
        {
          num: 6,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Chọn phương án đúng.',
          prompt: 'The laboratory instruments ___ expensive.',
          user_answer: null,
          correct_answer: 'are',
          explanation: 'Instruments có đuôi số nhiều "-s", do đó đi với động từ to be "are".'
        },
        {
          num: 7,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Điền từ thích hợp.',
          prompt: 'I ___ happy today.',
          user_answer: null,
          correct_answer: 'am',
          explanation: 'Đại từ nhân xưng ngôi thứ nhất số ít "I" luôn đi với động từ to be "am" ở thì hiện tại đơn.'
        },
        {
          num: 8,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Chọn phương án đúng.',
          prompt: 'Cô ấy là một học sinh.',
          user_answer: null,
          correct_answer: 'She is a student.',
          explanation: 'She đi kèm với động từ to be "is" và mạo từ "a" trước danh từ đếm được số ít.'
        },
        {
          num: 9,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Chọn bản dịch đúng nhất.',
          prompt: 'Trời lạnh và đã gần bảy giờ.',
          user_answer: null,
          correct_answer: "It is cold and it is almost seven o'clock.",
          explanation: 'Trong tiếng Anh, đại từ chủ ngữ giả "It" được dùng cho thời tiết và thời gian.'
        },
        {
          num: 10,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Chọn phương án đúng.',
          prompt: 'Họ đã sẵn sàng.',
          user_answer: null,
          correct_answer: 'They are ready.',
          explanation: 'They là chủ ngữ số nhiều đi với "are".'
        },
        {
          num: 11,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Chọn phương án đúng.',
          prompt: 'I ___ responsible for the summary.',
          user_answer: null,
          correct_answer: 'am',
          explanation: 'Cấu trúc be responsible for (chịu trách nhiệm cho điều gì), chủ ngữ I đi với am.'
        },
        {
          num: 12,
          status: 'unanswered',
          status_label: 'Chưa làm',
          type_label: 'Chọn bản dịch đúng nhất.',
          prompt: 'Bố mẹ tôi là người Đà Nẵng.',
          user_answer: null,
          correct_answer: 'My parents are from Da Nang.',
          explanation: 'My parents là danh từ số nhiều (bố mẹ), đi với are from...'
        }
      ]
    }
  ]

  const records = ref(
    JSON.parse(localStorage.getItem('oxford_study_history') || 'null') || defaultInitialRecords
  )

  const searchKeyword = ref('')

  const saveToStorage = () => {
    localStorage.setItem('oxford_study_history', JSON.stringify(records.value))
  }

  // Thêm một lượt làm bài kiểm tra vào lịch sử
  const addAttemptRecord = (attemptData) => {
    if (!attemptData) return

    const now = new Date()
    const formattedDate = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} ${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()}`

    const total = attemptData.total || (attemptData.questions ? attemptData.questions.length : 20)
    const score = attemptData.score || 0
    const pct = total > 0 ? Math.round((score / total) * 100) : 0
    const passed = pct >= 80

    const newRecord = {
      id: `hist_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`,
      title: attemptData.title || 'Bài tập trắc nghiệm',
      category: attemptData.category || 'Lộ trình',
      date: formattedDate,
      total_questions: total,
      score: score,
      percentage: pct,
      passed: passed,
      status_note: attemptData.status_note || (passed ? `${score}/${total} câu • Đạt chuẩn` : `${score}/${total} câu • Cần cải thiện`),
      difficulty: attemptData.difficulty || 'Trung bình',
      stars: pct >= 90 ? 3 : pct >= 80 ? 2 : pct >= 50 ? 1 : 0,
      questions: attemptData.questions || []
    }

    records.value.unshift(newRecord)
    saveToStorage()
    return newRecord
  }

  const clearHistory = () => {
    records.value = []
    saveToStorage()
  }

  const deleteRecord = (id) => {
    records.value = records.value.filter(r => r.id !== id)
    saveToStorage()
  }

  // 3 Số liệu thống kê ở đầu trang (Lượt đã làm, Lượt đạt, Điểm trung bình)
  const totalAttempts = computed(() => records.value.length)

  const passedAttempts = computed(() => {
    return records.value.filter(r => r.passed || r.percentage >= 80).length
  })

  const averageScore = computed(() => {
    if (records.value.length === 0) return 0
    const sum = records.value.reduce((acc, r) => acc + (r.percentage || 0), 0)
    return Math.round(sum / records.value.length)
  })

  // Danh sách có lọc theo từ khóa tìm kiếm
  const filteredRecords = computed(() => {
    if (!searchKeyword.value.trim()) return records.value
    const q = searchKeyword.value.toLowerCase().trim()
    return records.value.filter(r =>
      r.title.toLowerCase().includes(q) ||
      (r.difficulty && r.difficulty.toLowerCase().includes(q)) ||
      (r.category && r.category.toLowerCase().includes(q))
    )
  })

  return {
    records,
    searchKeyword,
    totalAttempts,
    passedAttempts,
    averageScore,
    filteredRecords,
    addAttemptRecord,
    clearHistory,
    deleteRecord,
    saveToStorage
  }
})
