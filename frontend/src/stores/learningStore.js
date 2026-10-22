import { defineStore } from 'pinia'
import axios from 'axios'

export const useLearningStore = defineStore('learning', {
  state: () => ({
    dashboard: null,
    roadmaps: [],
    currentNode: null,
    stageWords: [],
    viewedCardIds: new Set(),
    isLoading: false,
    error: null,
  }),

  getters: {
    allCardsViewed: (state) => {
      if (state.stageWords.length === 0) return false
      return state.stageWords.every(w => state.viewedCardIds.has(w.id))
    },
    viewedPercentage: (state) => {
      if (state.stageWords.length === 0) return 0
      return Math.round((state.viewedCardIds.size / state.stageWords.length) * 100)
    },
  },

  actions: {
    async fetchDashboard() {
      this.isLoading = true
      try {
        const res = await axios.get('/api/dashboard')
        this.dashboard = res.data.data
      } catch (err) {
        this.error = err.message
      } finally {
        this.isLoading = false
      }
    },

    async fetchRoadmaps() {
      this.isLoading = true
      try {
        const res = await axios.get('/api/roadmaps')
        this.roadmaps = res.data.data
      } catch (err) {
        this.error = err.message
      } finally {
        this.isLoading = false
      }
    },

    async fetchStageWords(nodeId) {
      this.isLoading = true
      this.viewedCardIds = new Set()
      try {
        const res = await axios.get(`/api/vocabulary/node/${nodeId}`)
        this.currentNode = res.data.data.node
        this.stageWords = res.data.data.words
      } catch (err) {
        this.error = err.message
      } finally {
        this.isLoading = false
      }
    },

    markCardAsViewed(wordId) {
      this.viewedCardIds.add(wordId)
    },

    async updateCardStatus(wordId, action) {
      try {
        await axios.post('/api/progress/card-studied', {
          vocabulary_id: wordId,
          action,
        })
        const word = this.stageWords.find(w => w.id === wordId)
        if (word) {
          word.status = action === 'mastered' ? 'mastered' : 'learning'
        }
      } catch (err) {
        console.error('Failed to update card status:', err)
      }
    },
  },
})
