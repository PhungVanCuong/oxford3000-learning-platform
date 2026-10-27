import { ref, onMounted, onUnmounted } from 'vue'

// Module-level singleton state so all views share the same voice settings immediately
const availableVoices = ref([])
const selectedVoiceURI = ref(typeof localStorage !== 'undefined' ? (localStorage.getItem('oxford_voice_uri') || '') : '')
const selectedSpeed = ref(typeof localStorage !== 'undefined' ? parseFloat(localStorage.getItem('oxford_speech_rate') || '1.0') : 1.0)
let synth = null

if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
  synth = window.speechSynthesis
}

const loadVoices = () => {
  if (!synth) return []
  const all = synth.getVoices()
  if (!all || all.length === 0) return []

  const enVoices = all.filter(v => v.lang && v.lang.toLowerCase().startsWith('en'))
  const list = enVoices.length > 0 ? enVoices : all

  availableVoices.value = list.map(v => {
    const nameLower = v.name.toLowerCase()
    let gender = 'female' // default
    if (nameLower.includes('male') && !nameLower.includes('female')) {
      gender = 'male'
    } else if (
      nameLower.includes('david') ||
      nameLower.includes('guy') ||
      nameLower.includes('george') ||
      nameLower.includes('mark') ||
      nameLower.includes('james') ||
      nameLower.includes('paul') ||
      nameLower.includes('richard') ||
      nameLower.includes('christopher') ||
      nameLower.includes('eric') ||
      nameLower.includes('steffan')
    ) {
      gender = 'male'
    } else if (
      nameLower.includes('zira') ||
      nameLower.includes('jenny') ||
      nameLower.includes('samantha') ||
      nameLower.includes('victoria') ||
      nameLower.includes('karen') ||
      nameLower.includes('aria') ||
      nameLower.includes('susan') ||
      nameLower.includes('catherine') ||
      nameLower.includes('hazel') ||
      nameLower.includes('linda')
    ) {
      gender = 'female'
    }

    let region = 'US'
    if (v.lang.includes('GB') || nameLower.includes('uk') || nameLower.includes('british')) region = 'UK'
    else if (v.lang.includes('AU') || nameLower.includes('australia')) region = 'AU'
    else if (v.lang.includes('CA') || nameLower.includes('canada')) region = 'CA'
    else if (v.lang.includes('IN') || nameLower.includes('india')) region = 'IN'

    return {
      name: v.name,
      voiceURI: v.voiceURI,
      lang: v.lang,
      gender, // 'male' | 'female'
      region,
      label: `${v.name} (${region} - ${gender === 'male' ? 'Nam 👨' : 'Nữ 👩'})`,
      default: v.default
    }
  })

  return availableVoices.value
}

// Pre-load voices on module import
if (synth) {
  if (synth.onvoiceschanged !== undefined) {
    synth.onvoiceschanged = () => {
      loadVoices()
    }
  }
  loadVoices()
}

// Get preferred voice (selected by user or fallback)
const getPreferredVoice = () => {
  if (!synth) return null
  const voices = synth.getVoices()
  if (!voices || voices.length === 0) return null

  // 1. Try user selected voice (from ref or localStorage)
  const currentTargetURI = selectedVoiceURI.value || (typeof localStorage !== 'undefined' ? (localStorage.getItem('oxford_voice_uri') || '') : '')
  if (currentTargetURI) {
    const found = voices.find(v => v.voiceURI === currentTargetURI || v.name === currentTargetURI)
    if (found) return found
  }

  // 2. Default high quality US voice
  return (
    voices.find(v => v.lang === 'en-US' && (v.name.includes('Google') || v.name.includes('Natural') || v.name.includes('Samantha') || v.name.includes('David') || v.name.includes('Jenny') || v.name.includes('Guy'))) ||
    voices.find(v => v.lang === 'en-US') ||
    voices.find(v => v.lang.startsWith('en')) ||
    voices[0] ||
    null
  )
}

export function useSpeech() {
  const isSpeaking = ref(false)
  const isListening = ref(false)
  const listeningId = ref(null) // ID of current listening item (e.g. 'pv_12' or 'grammar_ex_3')
  const itemResults = ref({}) // map { [id]: { transcript, score, feedback } }
  const speechError = ref(null)
  const transcript = ref('')
  const pronunciationScore = ref(null) // 0 to 100
  const pronunciationFeedback = ref('') // 'excellent', 'good', 'poor'

  let recognition = null
  let currentTargetText = ''
  let currentItemId = null
  let micPermissionGranted = false

  // Refresh voices on component mount if empty
  if (availableVoices.value.length === 0 && synth) {
    loadVoices()
  }

  const setVoice = (voiceURI) => {
    selectedVoiceURI.value = voiceURI
    if (typeof localStorage !== 'undefined') {
      localStorage.setItem('oxford_voice_uri', voiceURI)
    }
  }

  const setSpeed = (rate) => {
    selectedSpeed.value = rate
    if (typeof localStorage !== 'undefined') {
      localStorage.setItem('oxford_speech_rate', rate.toString())
    }
  }

  /**
   * Speak English text with configurable speed and selected voice
   * @param {string} text - text to read
   * @param {number|null} rate - 1.0 (normal) or 0.75 (slow)
   */
  const speak = (text, rate = null) => {
    if (!synth || !text) return

    try {
      synth.cancel() // Stop previous speaking immediately
    } catch (e) {}

    // Clean brackets and underscores
    const cleanText = text.replace(/\[.*?\]/g, '').replace(/___+/g, '').replace(/\(.*?\)/g, '')
    const utterance = new SpeechSynthesisUtterance(cleanText)
    utterance.lang = 'en-US'
    utterance.rate = rate !== null ? rate : (selectedSpeed.value || 1.0)
    utterance.pitch = 1.0

    const voice = getPreferredVoice()
    if (voice) {
      utterance.voice = voice
    }

    utterance.onstart = () => {
      isSpeaking.value = true
    }

    utterance.onend = () => {
      isSpeaking.value = false
    }

    utterance.onerror = () => {
      isSpeaking.value = false
    }

    synth.speak(utterance)
  }

  const testVoice = (voiceURI, testText = 'Hello! Welcome to Oxford English learning system.') => {
    if (!synth) return
    try {
      synth.cancel()
    } catch (e) {}

    const utterance = new SpeechSynthesisUtterance(testText)
    utterance.rate = selectedSpeed.value || 1.0
    utterance.pitch = 1.0

    const voices = synth.getVoices()
    const voice = voices.find(v => v.voiceURI === voiceURI || v.name === voiceURI)
    if (voice) {
      utterance.voice = voice
    }

    utterance.onstart = () => { isSpeaking.value = true }
    utterance.onend = () => { isSpeaking.value = false }
    utterance.onerror = () => { isSpeaking.value = false }

    synth.speak(utterance)
  }

  const stopSpeech = () => {
    if (synth) {
      try {
        synth.cancel()
      } catch (e) {}
      isSpeaking.value = false
    }
  }

  /**
   * Calculate Levenshtein Distance
   */
  const levenshteinDistance = (str1, str2) => {
    const s1 = str1.toLowerCase().trim()
    const s2 = str2.toLowerCase().trim()

    const track = Array(s2.length + 1)
      .fill(null)
      .map(() => Array(s1.length + 1).fill(null))

    for (let i = 0; i <= s1.length; i += 1) {
      track[0][i] = i
    }
    for (let j = 0; j <= s2.length; j += 1) {
      track[j][0] = j
    }

    for (let j = 1; j <= s2.length; j += 1) {
      for (let i = 1; i <= s1.length; i += 1) {
        const indicator = s1[i - 1] === s2[j - 1] ? 0 : 1
        track[j][i] = Math.min(
          track[j][i - 1] + 1,
          track[j - 1][i] + 1,
          track[j - 1][i - 1] + indicator
        )
      }
    }

    return track[s2.length][s1.length]
  }

  /**
   * Calculate similarity percentage (0 - 100%)
   */
  const calculateScore = (spoken, target) => {
    const cleanSpoken = spoken.toLowerCase().replace(/[^a-z0-9\s]/gi, '').trim()
    const cleanTarget = target.toLowerCase().replace(/[^a-z0-9\s]/gi, '').trim()

    if (!cleanSpoken || !cleanTarget) return 0
    if (cleanSpoken === cleanTarget) return 100

    const targetWords = cleanTarget.split(/\s+/).filter(Boolean)
    const spokenWords = cleanSpoken.split(/\s+/).filter(Boolean)

    let matchedCount = 0
    targetWords.forEach(tw => {
      if (spokenWords.some(sw => sw === tw || levenshteinDistance(sw, tw) <= 1)) {
        matchedCount++
      }
    })

    const wordMatchRatio = targetWords.length > 0 ? (matchedCount / targetWords.length) : 0
    const maxLen = Math.max(cleanSpoken.length, cleanTarget.length)
    const dist = levenshteinDistance(cleanSpoken, cleanTarget)
    const charScore = maxLen > 0 ? Math.max(0, 1 - dist / maxLen) : 0

    const finalScore = Math.round((wordMatchRatio * 0.65 + charScore * 0.35) * 100)
    return Math.min(100, Math.max(0, finalScore))
  }

  /**
   * Start Speech-To-Text to evaluate pronunciation
   * @param {string} targetText
   * @param {string|number|null} id - Unique item ID so only this item reacts
   */
  const startListening = async (targetText, id = null) => {
    speechError.value = null
    transcript.value = ''
    pronunciationScore.value = null
    pronunciationFeedback.value = ''
    currentTargetText = targetText || ''
    currentItemId = id
    listeningId.value = id

    const SpeechRecognition = typeof window !== 'undefined' ? (window.SpeechRecognition || window.webkitSpeechRecognition) : null

    if (!SpeechRecognition) {
      speechError.value = 'Trình duyệt chưa hỗ trợ Web Speech API. Vui lòng dùng Chrome, Edge hoặc Brave.'
      listeningId.value = null
      return
    }

    // Safely cleanup any active instance without triggering false error states
    if (recognition) {
      try {
        recognition.onresult = null
        recognition.onerror = null
        recognition.onend = null
        recognition.abort()
      } catch (e) {}
      recognition = null
    }

    try {
      recognition = new SpeechRecognition()
      recognition.lang = 'en-US'
      recognition.continuous = false
      recognition.interimResults = true
      recognition.maxAlternatives = 1

      recognition.onstart = () => {
        isListening.value = true
        listeningId.value = currentItemId
        speechError.value = null
      }

      recognition.onresult = (event) => {
        if (event.results && event.results.length > 0) {
          const latestResult = event.results[event.results.length - 1]
          if (latestResult && latestResult[0]) {
            const spoken = latestResult[0].transcript
            transcript.value = spoken

            if (latestResult.isFinal) {
              const score = calculateScore(spoken, currentTargetText)
              pronunciationScore.value = score

              let feedback = 'poor'
              if (score >= 80) {
                feedback = 'excellent'
              } else if (score >= 55) {
                feedback = 'good'
              }
              pronunciationFeedback.value = feedback

              if (currentItemId !== null) {
                itemResults.value[currentItemId] = {
                  transcript: spoken,
                  score,
                  feedback
                }
              }
            }
          }
        }
      }

      recognition.onerror = (event) => {
        isListening.value = false
        listeningId.value = null
        if (event.error !== 'no-speech' && event.error !== 'aborted') {
          speechError.value = 'Lỗi thu âm: ' + event.error
        }
      }

      recognition.onend = () => {
        isListening.value = false
        listeningId.value = null
      }

      recognition.start()
    } catch (err) {
      isListening.value = false
      listeningId.value = null
      speechError.value = err.message
    }
  }

  const stopListening = () => {
    if (recognition) {
      try {
        recognition.stop()
      } catch (e) {}
      isListening.value = false
      listeningId.value = null
    }
  }

  onUnmounted(() => {
    stopSpeech()
    stopListening()
  })

  return {
    isSpeaking,
    isListening,
    listeningId,
    itemResults,
    speechError,
    transcript,
    pronunciationScore,
    pronunciationFeedback,
    availableVoices,
    selectedVoiceURI,
    selectedSpeed,
    loadVoices,
    setVoice,
    setSpeed,
    testVoice,
    speak,
    stopSpeech,
    startListening,
    stopListening,
  }
}
