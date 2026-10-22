import { defineStore } from 'pinia'
import axios from 'axios'

export const useQuizStore = defineStore('quiz', {
  state: () => ({
    nodeId: null,
    nodeTitle: '',
    passingScore: 80,
    questions: [],
    currentIndex: 0,
    userAnswers: [], // { question_id, question_prompt, question_type, user_answer, correct_answer, is_correct, explanation, vocabulary_id }
    currentSelectedOption: null,
    currentTypedAnswer: '',
    isAnswerChecked: false,
    isCurrentCorrect: false,
    quizResult: null,
    isLoading: false,
    timeSpentSeconds: 0,
    timerInterval: null,
  }),

  getters: {
    currentQuestion: (state) => state.questions[state.currentIndex] || null,
    isLastQuestion: (state) => state.currentIndex === state.questions.length - 1,
    progressPercentage: (state) => {
      if (state.questions.length === 0) return 0
      return Math.round(((state.currentIndex + 1) / state.questions.length) * 100)
    },
    correctCount: (state) => state.userAnswers.filter(a => a.is_correct).length,
  },

  actions: {
    startTimer() {
      this.stopTimer()
      this.timeSpentSeconds = 0
      this.timerInterval = setInterval(() => {
        this.timeSpentSeconds++
      }, 1000)
    },

    stopTimer() {
      if (this.timerInterval) {
        clearInterval(this.timerInterval)
        this.timerInterval = null
      }
    },

    async loadVocabularyQuiz(nodeId) {
      this.isLoading = true
      this.resetState()
      try {
        const res = await axios.get(`/api/vocabulary/quiz/${nodeId}`)
        this.nodeId = res.data.data.node_id
        this.nodeTitle = res.data.data.node_title
        this.passingScore = res.data.data.passing_score
        this.questions = res.data.data.questions
        this.startTimer()
      } catch (err) {
        console.error('Failed to load vocabulary quiz:', err)
      } finally {
        this.isLoading = false
      }
    },

    async loadGrammarQuiz(nodeId) {
      this.isLoading = true
      this.resetState()
      try {
        const res = await axios.get(`/api/grammar/quiz/${nodeId}`)
        this.nodeId = nodeId
        this.nodeTitle = res.data.data.title
        this.passingScore = res.data.data.passing_score
        this.questions = res.data.data.questions
        this.startTimer()
      } catch (err) {
        console.error('Failed to load grammar quiz:', err)
      } finally {
        this.isLoading = false
      }
    },

    checkAnswer(answerText) {
      if (this.isAnswerChecked) return

      const q = this.currentQuestion
      if (!q) return

      const cleanUser = (answerText || '').trim().toLowerCase()
      const cleanCorrect = (q.correct_answer || '').trim().toLowerCase()

      // Exact match or punctuation-tolerant match
      const isCorrect = cleanUser === cleanCorrect || cleanUser.replace(/[.,/#!$%^&*;:{}=\-_`~()]/g, '') === cleanCorrect.replace(/[.,/#!$%^&*;:{}=\-_`~()]/g, '')

      this.isAnswerChecked = true
      this.isCurrentCorrect = isCorrect
      this.currentSelectedOption = answerText

      this.userAnswers.push({
        question_id: q.id,
        question_prompt: q.prompt,
        question_type: q.question_type,
        user_answer: answerText,
        correct_answer: q.correct_answer,
        is_correct: isCorrect,
        explanation: q.explanation || '',
        vocabulary_id: q.vocabulary_id || null,
      })
    },

    nextQuestion() {
      if (this.currentIndex < this.questions.length - 1) {
        this.currentIndex++
        this.isAnswerChecked = false
        this.isCurrentCorrect = false
        this.currentSelectedOption = null
        this.currentTypedAnswer = ''
      }
    },

    async submitQuiz(quizType = 'vocabulary') {
      this.stopTimer()
      this.isLoading = true
      try {
        const payload = {
          node_id: this.nodeId,
          quiz_type: quizType,
          time_spent_seconds: this.timeSpentSeconds,
          answers: this.userAnswers,
        }
        const res = await axios.post('/api/progress/quiz-submit', payload)
        this.quizResult = res.data.data
      } catch (err) {
        console.error('Failed to submit quiz:', err)
      } finally {
        this.isLoading = false
      }
    },

    resetState() {
      this.stopTimer()
      this.questions = []
      this.currentIndex = 0
      this.userAnswers = []
      this.currentSelectedOption = null
      this.currentTypedAnswer = ''
      this.isAnswerChecked = false
      this.isCurrentCorrect = false
      this.quizResult = null
      this.timeSpentSeconds = 0
    },
  },
})
