import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useReviewStore = defineStore('reviewStore', () => {
  // Folders list: mặc định 'Chưa xếp'
  const folders = ref(
    JSON.parse(localStorage.getItem('oxford_review_folders') || '["Chưa xếp"]')
  )

  // Danh sách thẻ ôn tập
  const defaultInitialCards = [
    {
      id: 'aberration_c2',
      word: 'aberration',
      phonetic_us: '/ˌæb.əˈreɪ.ʃən/',
      part_of_speech: 'Danh từ',
      meaning_vi: 'sự sai lệch, điều bất thường (lệch khỏi chuẩn mực hay trạng thái vốn có); (quang học/sinh học) hiện tượng sai lệch, quang sai',
      example_en: 'In a moment of aberration, she agreed to their proposal.',
      example_vi: 'Trong một phút lơ đãng sai sót, cô ấy đã đồng ý lời đề nghị của họ.',
      level: 'Từ vựng cơ bản C2',
      folder: 'Chưa xếp',
      starred: false,
      status: 'due', // 'due' (tới hạn), 'learning' (đang học), 'mastered' (đã thuộc)
      due_date: new Date().toISOString().split('T')[0],
      interval_days: 1,
      review_count: 0,
      created_at: new Date().toISOString()
    }
  ]

  const cards = ref(
    JSON.parse(localStorage.getItem('oxford_review_cards') || 'null') || defaultInitialCards
  )

  const activeFolder = ref('all') // 'all' | 'starred' | folderName
  const searchKeyword = ref('')
  const sortOption = ref('due_first') // 'due_first' | 'newest' | 'alphabet'
  const studyDirection = ref(localStorage.getItem('oxford_review_direction') || 'vi_to_en') // 'vi_to_en' | 'en_to_vi'

  const saveToStorage = () => {
    localStorage.setItem('oxford_review_cards', JSON.stringify(cards.value))
    localStorage.setItem('oxford_review_folders', JSON.stringify(folders.value))
    localStorage.setItem('oxford_review_direction', studyDirection.value)
  }

  // Thêm từ vào bộ thẻ ôn tập (tự động kích hoạt khi bấm nút "Cần ôn" / phím X trên toàn website)
  const addWordToReview = (wordData, customFolder = 'Chưa xếp') => {
    if (!wordData || (!wordData.word && !wordData.term)) return null

    const wordText = (wordData.word || wordData.term || '').trim()
    const existing = cards.value.find(c => c.word.toLowerCase() === wordText.toLowerCase())

    if (existing) {
      existing.status = 'due'
      existing.due_date = new Date().toISOString().split('T')[0]
      existing.review_count = (existing.review_count || 0) + 1
      saveToStorage()
      return existing
    }

    const newCard = {
      id: wordData.id ? `rev_${wordData.id}` : `rev_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`,
      word: wordText,
      phonetic_us: wordData.phonetic_us || wordData.phonetic || '',
      part_of_speech: wordData.part_of_speech || wordData.pos || 'Từ vựng',
      meaning_vi: wordData.meaning_vi || wordData.definition || wordData.meaning || '',
      example_en: wordData.example_en || '',
      example_vi: wordData.example_vi || '',
      level: wordData.level_code ? `Từ vựng cơ bản ${wordData.level_code}` : (wordData.cefr_level ? `CEFR ${wordData.cefr_level}` : 'Oxford 3000™'),
      folder: customFolder || 'Chưa xếp',
      starred: false,
      status: 'due',
      due_date: new Date().toISOString().split('T')[0],
      interval_days: 1,
      review_count: 0,
      created_at: new Date().toISOString()
    }

    cards.value.unshift(newCard)
    saveToStorage()
    return newCard
  }

  const removeCard = (id) => {
    cards.value = cards.value.filter(c => c.id !== id)
    saveToStorage()
  }

  const toggleStar = (id) => {
    const card = cards.value.find(c => c.id === id)
    if (card) {
      card.starred = !card.starred
      saveToStorage()
    }
  }

  const updateCard = (id, updates) => {
    const card = cards.value.find(c => c.id === id)
    if (card) {
      Object.assign(card, updates)
      saveToStorage()
    }
  }

  const setCardFolder = (id, folderName) => {
    const card = cards.value.find(c => c.id === id)
    if (card) {
      card.folder = folderName
      saveToStorage()
    }
  }

  const addFolder = (name) => {
    const trimmed = (name || '').trim()
    if (!trimmed) return false
    if (!folders.value.includes(trimmed)) {
      folders.value.push(trimmed)
      saveToStorage()
      return true
    }
    return false
  }

  const deleteFolder = (name) => {
    if (name === 'Chưa xếp') return // Không xóa thư mục mặc định
    folders.value = folders.value.filter(f => f !== name)
    // Chuyển các từ thuộc thư mục này về 'Chưa xếp'
    cards.value.forEach(c => {
      if (c.folder === name) {
        c.folder = 'Chưa xếp'
      }
    })
    if (activeFolder.value === name) {
      activeFolder.value = 'all'
    }
    saveToStorage()
  }

  // Đánh dấu kết quả ôn tập (Spaced Repetition Algorithm)
  const recordReviewResult = (id, isSuccess) => {
    const card = cards.value.find(c => c.id === id)
    if (!card) return

    if (isSuccess) {
      card.interval_days = Math.max(1, (card.interval_days || 1) * 2)
      card.status = card.interval_days >= 4 ? 'mastered' : 'learning'
      const nextDate = new Date()
      nextDate.setDate(nextDate.getDate() + card.interval_days)
      card.due_date = nextDate.toISOString().split('T')[0]
    } else {
      card.interval_days = 1
      card.status = 'due'
      card.due_date = new Date().toISOString().split('T')[0]
    }
    card.review_count = (card.review_count || 0) + 1
    saveToStorage()
  }

  // Computed metrics
  const totalCardsCount = computed(() => cards.value.length)

  const dueCardsCount = computed(() => {
    const today = new Date().toISOString().split('T')[0]
    return cards.value.filter(c => c.status === 'due' || !c.due_date || c.due_date <= today).length
  })

  const masteredCardsCount = computed(() => {
    return cards.value.filter(c => c.status === 'mastered').length
  })

  const learningCardsCount = computed(() => {
    return cards.value.filter(c => c.status !== 'mastered').length
  })

  const starredCardsCount = computed(() => {
    return cards.value.filter(c => c.starred).length
  })

  const getFolderCount = (folderName) => {
    if (folderName === 'all') return cards.value.length
    if (folderName === 'starred') return starredCardsCount.value
    return cards.value.filter(c => c.folder === folderName).length
  }

  // Filtered Cards List
  const filteredCards = computed(() => {
    let result = [...cards.value]

    // Lọc theo thư mục
    if (activeFolder.value === 'starred') {
      result = result.filter(c => c.starred)
    } else if (activeFolder.value !== 'all') {
      result = result.filter(c => c.folder === activeFolder.value)
    }

    // Tìm kiếm
    if (searchKeyword.value.trim()) {
      const q = searchKeyword.value.toLowerCase().trim()
      result = result.filter(c =>
        c.word.toLowerCase().includes(q) ||
        (c.meaning_vi && c.meaning_vi.toLowerCase().includes(q))
      )
    }

    // Sắp xếp
    if (sortOption.value === 'due_first') {
      result.sort((a, b) => {
        if (a.status === 'due' && b.status !== 'due') return -1
        if (a.status !== 'due' && b.status === 'due') return 1
        return (a.due_date || '').localeCompare(b.due_date || '')
      })
    } else if (sortOption.value === 'newest') {
      result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    } else if (sortOption.value === 'alphabet') {
      result.sort((a, b) => a.word.localeCompare(b.word))
    }

    return result
  })

  return {
    cards,
    folders,
    activeFolder,
    searchKeyword,
    sortOption,
    studyDirection,
    totalCardsCount,
    dueCardsCount,
    masteredCardsCount,
    learningCardsCount,
    starredCardsCount,
    filteredCards,
    getFolderCount,
    addWordToReview,
    removeCard,
    toggleStar,
    updateCard,
    setCardFolder,
    addFolder,
    deleteFolder,
    recordReviewResult,
    saveToStorage
  }
})
