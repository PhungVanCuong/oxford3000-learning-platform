export const NAV_CATALOG = [
  { id: 'curriculum', label: 'Lộ trình', to: '/curriculum', icon: 'bi bi-signpost-split-fill', iconClass: 'icon-curriculum', color: 'text-success', desc: '5.000+ từ & 25 chủ đề thực chiến' },
  { id: 'phrasal_verbs', label: 'Cụm từ', to: '/phrasal-verbs', icon: 'bi bi-puzzle-fill', iconClass: 'icon-phrasal', color: 'text-info', desc: '1.000+ Phrasal Verbs & Collocations' },
  { id: 'dictation', label: 'Chép chính tả', to: '/dictation', icon: 'bi bi-headphones text-teal', iconClass: '', color: 'text-teal', desc: 'Nghe chép chính tả DailyDictation' },
  { id: 'daily', label: 'Học theo ngày', to: '/daily', icon: 'bi bi-calendar-check-fill', iconClass: 'icon-daily', color: 'text-primary', desc: 'Chia từ vựng thông minh theo ngày' },
  { id: 'arena', label: 'Đấu trường', to: '/arena', icon: 'bi bi-trophy-fill text-warning', iconClass: '', color: 'text-warning', desc: 'Phòng thi trực tiếp & Xếp hạng XP' },
  { id: 'grammar', label: 'Ngữ pháp', to: '/grammar', icon: 'bi bi-journal-bookmark-fill', iconClass: 'icon-grammar', color: 'text-primary', desc: '12 thì tiếng Anh & chuyên đề chuẩn' },
  { id: 'cefr', label: 'Từ vựng CEFR', to: '/cefr', icon: 'bi bi-award-fill text-success', iconClass: 'icon-cefr', color: 'text-success', desc: 'Lộ trình chuẩn khung châu Âu A1-C2' },
  { id: 'vocabulary_notebook', label: 'Sổ tay từ vựng', to: '/vocabulary-notebook', icon: 'bi bi-journal-text text-teal', iconClass: '', color: 'text-teal', desc: 'Ghi chú & flashcard từ vựng cá nhân' },
  { id: 'review', label: 'Ôn tập', to: '/review', icon: 'bi bi-clock-history text-warning', iconClass: '', color: 'text-warning', desc: 'Ôn tập ngắt quãng Spaced Repetition' },
  { id: 'history', label: 'Lịch sử', to: '/history', icon: 'bi bi-journal-check text-info', iconClass: '', color: 'text-info', desc: 'Nhật ký làm bài & kết quả quiz' },
  { id: 'dictionary', label: 'Tra cứu', to: '/dictionary', icon: 'bi bi-search-heart-fill', iconClass: 'icon-dict', color: 'text-danger', desc: 'Từ điển Oxford 3000+ & phát âm AI' },
  { id: 'ipa', label: '44 Âm IPA', to: '/ipa', icon: 'bi bi-soundwave', iconClass: 'icon-ipa', color: 'text-purple', desc: 'Bảng phiên âm quốc tế chuẩn Anh-Mỹ' },
  { id: 'tree', label: 'Cây tri thức', to: '/tree', icon: 'bi bi-tree-fill text-success', iconClass: '', color: 'text-success', desc: 'Nuôi dưỡng cây tri thức 30 cấp độ' },
  { id: 'stats', label: 'Năng suất', to: '/stats', icon: 'bi bi-bar-chart-fill text-primary', iconClass: '', color: 'text-primary', desc: 'Thống kê tuần & GitHub Heatmap' },
  { id: 'tips', label: 'Mẹo học', to: '/tips', icon: 'bi bi-lightbulb-fill text-warning', iconClass: '', color: 'text-warning', desc: 'Bí kíp ghi nhớ & phát âm thực chiến' },
]

export const DEFAULT_HEADER_NAV_IDS = ['curriculum', 'phrasal_verbs', 'dictation', 'daily', 'arena']

export const MAX_HEADER_NAV_ITEMS = 5
export const MIN_HEADER_NAV_ITEMS = 1

export const sanitizeHeaderNavIds = (ids) => {
  const validSet = new Set(NAV_CATALOG.map(i => i.id))
  if (!Array.isArray(ids)) return [...DEFAULT_HEADER_NAV_IDS]
  const filtered = [...new Set(ids)].filter(id => validSet.has(id)).slice(0, MAX_HEADER_NAV_ITEMS)
  return filtered.length >= MIN_HEADER_NAV_ITEMS ? filtered : [...DEFAULT_HEADER_NAV_IDS]
}

export const buildOrderedMoreItems = (headerIds, savedMoreOrder = []) => {
  const headerSet = new Set(headerIds)
  const remaining = NAV_CATALOG.filter(item => !headerSet.has(item.id))
  if (!Array.isArray(savedMoreOrder) || savedMoreOrder.length === 0) {
    return remaining
  }
  const orderMap = new Map(savedMoreOrder.map((id, idx) => [id, idx]))
  return [...remaining].sort((a, b) => {
    const idxA = orderMap.has(a.id) ? orderMap.get(a.id) : 999
    const idxB = orderMap.has(b.id) ? orderMap.get(b.id) : 999
    if (idxA !== idxB) return idxA - idxB
    return NAV_CATALOG.findIndex(n => n.id === a.id) - NAV_CATALOG.findIndex(n => n.id === b.id)
  })
}
