import { ref, onMounted, onUnmounted } from 'vue'

export function useSpeech() {
  const isSpeaking = ref(false)
  const isListening = ref(false)
  const speechError = ref(null)
  const transcript = ref('')
  const pronunciationScore = ref(null) // 0 to 100
  const pronunciationFeedback = ref('') // 'excellent', 'good', 'poor'

  let synth = null
  let recognition = null
  let currentTargetText = ''
  let micPermissionGranted = false

  // Initialize SpeechSynthesis
  if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
    synth = window.speechSynthesis
  }

  // Pre-load voices
  if (synth && synth.onvoiceschanged !== undefined) {
    synth.onvoiceschanged = () => {
      // Voices loaded into cache
    }
  }

  // Get preferred English US voice
  const getUSVoice = () => {
    if (!synth) return null
    const voices = synth.getVoices()
    return (
      voices.find(v => v.lang === 'en-US' && (v.name.includes('Google') || v.name.includes('Natural') || v.name.includes('Samantha') || v.name.includes('David') || v.name.includes('Jenny') || v.name.includes('Guy'))) ||
      voices.find(v => v.lang === 'en-US') ||
      voices.find(v => v.lang.startsWith('en')) ||
      null
    )
  }

  /**
   * Speak English text with configurable speed
   * @param {string} text - text to read
   * @param {number} rate - 1.0 (normal) or 0.75 (slow)
   */
  const speak = (text, rate = 1.0) => {
    if (!synth || !text) return

    try {
      synth.cancel() // Stop previous speaking immediately
    } catch (e) {}

    // Clean brackets and underscores
    const cleanText = text.replace(/\[.*?\]/g, '').replace(/___+/g, '').replace(/\(.*?\)/g, '')
    const utterance = new SpeechSynthesisUtterance(cleanText)
    utterance.lang = 'en-US'
    utterance.rate = rate
    utterance.pitch = 1.0

    const voice = getUSVoice()
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
   * Safe warm up of microphone permission to ensure the very first word works seamlessly
   */
  const ensureMicrophonePermission = async () => {
    if (micPermissionGranted) return true
    if (typeof navigator !== 'undefined' && navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true })
        // Immediately release tracks
        stream.getTracks().forEach(track => track.stop())
        micPermissionGranted = true
        return true
      } catch (e) {
        console.warn('Microphone permission not pre-granted:', e)
        return false
      }
    }
    return true
  }

  /**
   * Start Speech-To-Text to evaluate pronunciation
   */
  const startListening = async (targetText) => {
    speechError.value = null
    transcript.value = ''
    pronunciationScore.value = null
    pronunciationFeedback.value = ''
    currentTargetText = targetText || ''

    const SpeechRecognition = typeof window !== 'undefined' ? (window.SpeechRecognition || window.webkitSpeechRecognition) : null

    if (!SpeechRecognition) {
      speechError.value = 'Trình duyệt chưa hỗ trợ Web Speech API. Vui lòng dùng Chrome, Edge hoặc Brave.'
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

              if (score >= 80) {
                pronunciationFeedback.value = 'excellent'
              } else if (score >= 55) {
                pronunciationFeedback.value = 'good'
              } else {
                pronunciationFeedback.value = 'poor'
              }
            }
          }
        }
      }

      recognition.onerror = (event) => {
        isListening.value = false
        if (event.error !== 'no-speech' && event.error !== 'aborted') {
          speechError.value = 'Lỗi thu âm: ' + event.error
        }
      }

      recognition.onend = () => {
        isListening.value = false
      }

      recognition.start()
    } catch (err) {
      isListening.value = false
      speechError.value = err.message
    }
  }

  const stopListening = () => {
    if (recognition) {
      try {
        recognition.stop()
      } catch (e) {}
      isListening.value = false
    }
  }

  onUnmounted(() => {
    stopSpeech()
    stopListening()
  })

  return {
    isSpeaking,
    isListening,
    speechError,
    transcript,
    pronunciationScore,
    pronunciationFeedback,
    speak,
    stopSpeech,
    startListening,
    stopListening,
  }
}
