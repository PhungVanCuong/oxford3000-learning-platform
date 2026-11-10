import { defineStore } from 'pinia'
import axios from 'axios'

export const useDailyStudyStore = defineStore('dailyStudy', {
  state: () => ({
    days: [],
    wordsPerDay: 15,
    totalWords: 3351,
    totalDays: 224,
    currentDay: 1,
    currentDayWords: [],
    selectedMultiDays: [],
    multiDayWords: [],
    dailyQuiz: null,
    isLoading: false,
    error: null,
  }),

  actions: {
    async fetchDays() {
      this.isLoading = true
      try {
        const res = await axios.get('/api/daily-study')
        this.days = res.data.data.days
        this.wordsPerDay = res.data.data.words_per_day
        this.totalWords = res.data.data.total_words
        this.totalDays = res.data.data.total_days
      } catch (err) {
        this.error = err.message
      } finally {
        this.isLoading = false
      }
    },

    async fetchDayWords(day) {
      this.isLoading = true
      this.currentDay = day
      try {
        const res = await axios.get(`/api/daily-study/day/${day}`)
        this.currentDayWords = res.data.data.words
      } catch (err) {
        this.error = err.message
      } finally {
        this.isLoading = false
      }
    },

    async loadDayQuiz(day) {
      this.isLoading = true
      try {
        const res = await axios.get(`/api/daily-study/quiz/${day}`)
        this.dailyQuiz = res.data.data
        return this.dailyQuiz
      } catch (err) {
        this.error = err.message
      } finally {
        this.isLoading = false
      }
    },

    async loadMultiDay(mode = 'flashcard') {
      if (this.selectedMultiDays.length === 0) return null
      this.isLoading = true
      try {
        const daysParam = this.selectedMultiDays.join(',')
        const res = await axios.get('/api/daily-study/custom', {
          params: { days: daysParam, mode },
        })
        if (mode === 'quiz') {
          this.dailyQuiz = res.data.data
          return this.dailyQuiz
        } else {
          this.multiDayWords = res.data.data.words
          return this.multiDayWords
        }
      } catch (err) {
        this.error = err.message
      } finally {
        this.isLoading = false
      }
    },

    async markWordStatus(wordId, status) {
      try {
        await axios.post('/api/progress/word-status', {
          vocabulary_id: wordId,
          status,
        })
        // Update in currentDayWords
        const w = this.currentDayWords.find(item => item.id === wordId)
        if (w) {
          w.status = status
        }
        // Update in multiDayWords
        const mw = this.multiDayWords.find(item => item.id === wordId)
        if (mw) {
          mw.status = status
        }

        // Immediately update this day in days grid so cards reflect new percentage
        const activeDayNum = typeof this.currentDay === 'number' ? this.currentDay : parseInt(this.currentDay)
        if (activeDayNum) {
          const d = this.days.find(item => item.day === activeDayNum)
          if (d) {
            const masteredCount = this.currentDayWords.filter(item => item.status === 'mastered').length
            d.mastered_words = masteredCount
            d.progress_percent = d.total_words > 0 ? Math.round((masteredCount / d.total_words) * 100) : 0
          }
        }
      } catch (err) {
        console.error('Failed to update word status:', err)
      }
    },

    toggleSelectDay(dayNumber) {
      const idx = this.selectedMultiDays.indexOf(dayNumber)
      if (idx > -1) {
        this.selectedMultiDays.splice(idx, 1)
      } else {
        this.selectedMultiDays.push(dayNumber)
      }
    },

    clearSelectedDays() {
      this.selectedMultiDays = []
    },
  },
})
