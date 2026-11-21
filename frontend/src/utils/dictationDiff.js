/**
 * Reverse-Engineered Dictation Validation & Word Diffing Engine
 * Based on DailyDictation.com's Core Algorithm
 */

// Characters to strip from word boundaries
const PUNCTUATION_REGEX = /^[.,:;?!'‘’"`()\[\]{}«»“”„]+|[.,:;?!'‘’"`()\[\]{}«»“”„]+$/g
const INNER_PUNCTUATION_REGEX = /[()[\]{}"“”„«»]/g

/**
 * Normalize a word for comparison:
 * - lowercase
 * - trim outer punctuation (commas, periods, question marks, quotes)
 * - normalize smart quotes and hyphens
 */
export function normalizeWord(word) {
  if (!word) return ''
  let s = String(word).trim().toLowerCase()
  s = s.replace(INNER_PUNCTUATION_REGEX, '')
  s = s.replace(PUNCTUATION_REGEX, '')
  // Normalize contractions and quotes
  s = s.replace(/[’`]/g, "'")
  // Normalize em/en dashes to hyphen
  s = s.replace(/[—–]/g, '-')
  return s
}

/**
 * Mask word with asterisks or dots, preserving word length
 */
export function maskWord(word) {
  return word.replace(/\S/g, '*')
}

/**
 * Validate user input against target sentence and solution alternatives
 *
 * @param {string} userInput - The text typed by the learner
 * @param {string} targetSentence - The original sentence (e.g. "Today is November 26th.")
 * @param {Array<Array<string>>} solutionArray - Array of token alternatives per word position
 *        e.g. [["Today"], ["is"], ["November"], ["26th.", "twenty-sixth."]]
 * @returns {Object} Result of validation
 */
export function validateSentence(userInput = '', targetSentence = '', solutionArray = null) {
  const cleanInput = (userInput || '').trim()
  
  // If no solution array provided, generate from target sentence
  const rawWords = targetSentence.trim().split(/\s+/)
  const solutions = solutionArray && solutionArray.length > 0 
    ? solutionArray 
    : rawWords.map(w => [w])

  const totalWords = solutions.length

  // Tokenize user input into words with their character offsets
  const userWordTokens = []
  const regex = /\S+/g
  let match
  while ((match = regex.exec(userInput)) !== null) {
    userWordTokens.push({
      text: match[0],
      norm: normalizeWord(match[0]),
      start: match.index,
      end: match.index + match[0].length
    })
  }

  // Check matching word by word
  let matchedCount = 0
  let firstErrorWordIndex = -1
  let firstErrorInputToken = null
  let matchedSolutionWords = []

  for (let i = 0; i < totalWords; i++) {
    const slotAlternatives = solutions[i].map(alt => ({
      raw: alt,
      norm: normalizeWord(alt)
    }))

    const userToken = userWordTokens[i]

    if (!userToken) {
      // User hasn't typed this word yet
      if (firstErrorWordIndex === -1) {
        firstErrorWordIndex = i
      }
      break
    }

    // Check if userToken matches any alternative in this slot
    const isMatch = slotAlternatives.some(alt => alt.norm === userToken.norm)

    if (isMatch) {
      matchedCount++
      matchedSolutionWords.push(slotAlternatives[0].raw)
    } else {
      if (firstErrorWordIndex === -1) {
        firstErrorWordIndex = i
        firstErrorInputToken = userToken
      }
      break
    }
  }

  // Check if fully correct: all solution words matched and user didn't type extra words
  const isFullyMatched = matchedCount === totalWords && userWordTokens.length === totalWords

  // Calculate error highlights on user input
  let highlightFrom = -1
  let highlightTo = -1
  let hasExtraWords = false

  if (!isFullyMatched) {
    if (firstErrorInputToken) {
      // User made a mistake at this token
      highlightFrom = firstErrorInputToken.start
      highlightTo = firstErrorInputToken.end
    } else if (userWordTokens.length > totalWords) {
      // User typed extra words beyond sentence length
      hasExtraWords = true
      const extraToken = userWordTokens[totalWords]
      highlightFrom = extraToken.start
      highlightTo = userInput.length
    } else if (userWordTokens.length < totalWords) {
      // Missing words - highlight position is at end of typed text
      highlightFrom = userInput.length
      highlightTo = userInput.length + 1
    }
  }

  // Build HTML highlight for user input overlay
  let userHighlightedHtml = ''
  if (highlightFrom >= 0 && highlightTo > highlightFrom) {
    const before = userInput.slice(0, highlightFrom)
    const errText = userInput.slice(highlightFrom, highlightTo) || '&nbsp;'
    const after = userInput.slice(highlightTo)
    userHighlightedHtml = `${escapeHtml(before)}<span class="dictation__input-highlight-text">${escapeHtml(errText)}</span>${escapeHtml(after)}`
  }

  // Build target correction HTML:
  // Green bold for matched words, masked / plain for remaining
  const correctionParts = []
  for (let i = 0; i < totalWords; i++) {
    const primaryWord = solutions[i][0]
    if (i < matchedCount) {
      correctionParts.push(`<b class="text-success fw-bold">${escapeHtml(primaryWord)}</b>`)
    } else {
      correctionParts.push(escapeHtml(primaryWord))
    }
  }
  const htmlCorrectionFull = correctionParts.join(' ')

  // Masked version (unrevealed words masked with asterisks)
  const maskedParts = []
  for (let i = 0; i < totalWords; i++) {
    const primaryWord = solutions[i][0]
    if (i < matchedCount) {
      maskedParts.push(`<b class="text-success fw-bold">${escapeHtml(primaryWord)}</b>`)
    } else if (i === matchedCount) {
      // Next word to guess
      maskedParts.push(`<span class="text-warning text-decoration-underline">${escapeHtml(primaryWord)}</span>`)
    } else {
      maskedParts.push(`<span class="text-muted">${maskWord(primaryWord)}</span>`)
    }
  }
  const htmlCorrectionMasked = maskedParts.join(' ')

  // Alternatives display if any slot has multiple spellings
  const alternatives = []
  for (let i = 0; i < totalWords; i++) {
    if (solutions[i].length > 1) {
      alternatives.push(solutions[i].join(' hoặc '))
    }
  }

  // Accuracy score
  const accuracy = totalWords > 0 ? Math.round((matchedCount / totalWords) * 100) : 0

  return {
    isCorrect: isFullyMatched,
    matchedCount,
    totalWords,
    accuracy,
    firstErrorWordIndex,
    highlightFrom,
    highlightTo,
    hasExtraWords,
    userHighlightedHtml,
    htmlCorrectionFull,
    htmlCorrectionMasked,
    alternatives: alternatives.length > 0 ? alternatives.join(', ') : null,
    nextWord: solutions[matchedCount] ? solutions[matchedCount][0] : ''
  }
}

function escapeHtml(str) {
  if (!str) return ''
  return str
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;')
}
