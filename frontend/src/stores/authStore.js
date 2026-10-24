import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: (() => {
      try {
        return JSON.parse(localStorage.getItem('oxford_user')) || null
      } catch (e) {
        return null
      }
    })(),
    token: localStorage.getItem('oxford_token') || null,
    stats: null,
    themeMode: localStorage.getItem('oxford_theme') || 'light',
    isLoading: false,
    error: null,
    showLoginModal: false,
    loginModalMessage: '',
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
    isAdmin: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || state.user.email === 'cuongphung1810@gmail.com'
    },
    isModerator: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || state.user.role === 'moderator' || state.user.email === 'cuongphung1810@gmail.com'
    },
    isStaff: (state) => {
      if (!state.user) return false
      if (state.user.role === 'user') return false
      return ['admin', 'moderator', 'editor', 'tutor'].includes(state.user.role) || state.user.email === 'cuongphung1810@gmail.com'
    },
    hasPermission: (state) => (permKey) => {
      if (!state.user) return false
      if (state.user.role === 'admin' || state.user.email === 'cuongphung1810@gmail.com') return true
      return (state.user.permissions || []).includes(permKey)
    },
    canManageRoles: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || state.user.email === 'cuongphung1810@gmail.com' || (state.user.permissions || []).includes('manage_roles')
    },
    canManageUsers: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || (state.user.permissions || []).includes('manage_users')
    },
    canViewStudents: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || (state.user.permissions || []).includes('view_students') || (state.user.permissions || []).includes('manage_users')
    },
    canManageVocab: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || (state.user.permissions || []).includes('manage_vocab')
    },
    canManageCurriculum: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || (state.user.permissions || []).includes('manage_curriculum')
    },
    canManagePhrases: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || (state.user.permissions || []).includes('manage_phrases')
    },
    canViewReports: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || (state.user.permissions || []).includes('view_reports')
    },
    canManageArena: (state) => {
      if (!state.user) return false
      return state.user.role === 'admin' || state.user.email === 'cuongphung1810@gmail.com' || (state.user.permissions || []).includes('manage_arena')
    },
    userName: (state) => state.user?.name || 'Khách vãng lai',
    userEmail: (state) => state.user?.email || '',
    userAvatar: (state) => state.user?.avatar_url || 'https://api.dicebear.com/7.x/bottts/svg?seed=CuongPhung',
  },

  actions: {
    initAuth() {
      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
      }
      this.applyTheme(this.themeMode)
      if (this.token) {
        this.fetchMe()
      }
    },

    applyTheme(theme) {
      this.themeMode = theme
      localStorage.setItem('oxford_theme', theme)
      document.documentElement.setAttribute('data-bs-theme', theme)
    },

    toggleTheme() {
      const newTheme = this.themeMode === 'light' ? 'dark' : 'light'
      this.applyTheme(newTheme)
      if (this.user) {
        this.updateProfile({ theme_mode: newTheme })
      }
    },

    promptLogin(message = 'Bạn cần đăng nhập để lưu tiến độ học tập và sử dụng tính năng này!') {
      this.loginModalMessage = message
      this.showLoginModal = true
    },

    closeLoginModal() {
      this.showLoginModal = false
    },

    async login(email, password) {
      this.isLoading = true
      this.error = null
      try {
        const res = await axios.post('/api/auth/login', { email, password })
        this.token = res.data.data.token
        this.user = res.data.data.user
        localStorage.setItem('oxford_token', this.token)
        localStorage.setItem('oxford_user', JSON.stringify(this.user))
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        if (this.user.theme_mode) {
          this.applyTheme(this.user.theme_mode)
        }
        await this.fetchMe()
        return true
      } catch (err) {
        this.error = err.response?.data?.message || 'Đăng nhập không thành công. Vui lòng kiểm tra lại email hoặc mật khẩu.'
        return false
      } finally {
        this.isLoading = false
      }
    },

    async register(data) {
      this.isLoading = true
      this.error = null
      try {
        const res = await axios.post('/api/auth/register-with-otp', data)
        this.token = res.data.data.token
        this.user = res.data.data.user
        localStorage.setItem('oxford_token', this.token)
        localStorage.setItem('oxford_user', JSON.stringify(this.user))
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        await this.fetchMe()
        return true
      } catch (err) {
        this.error = err.response?.data?.message || 'Đăng ký không thành công. Vui lòng thử lại.'
        return false
      } finally {
        this.isLoading = false
      }
    },

    async fetchMe() {
      if (!this.token) {
        this.user = null
        this.stats = null
        return
      }
      try {
        const res = await axios.get('/api/auth/me')
        if (res.data.status === 'success' && res.data.data?.user) {
          this.user = res.data.data.user
          this.stats = res.data.data.stats
          localStorage.setItem('oxford_user', JSON.stringify(this.user))
          if (this.user?.theme_mode) {
            this.applyTheme(this.user.theme_mode)
          }
        } else {
          this.user = null
          this.stats = null
          this.token = null
          localStorage.removeItem('oxford_token')
          localStorage.removeItem('oxford_user')
          delete axios.defaults.headers.common['Authorization']
        }
      } catch (err) {
        if (err.response?.status === 401) {
          this.user = null
          this.stats = null
          this.token = null
          localStorage.removeItem('oxford_token')
          localStorage.removeItem('oxford_user')
          delete axios.defaults.headers.common['Authorization']
        }
      }
    },

    async updateProfile(formData) {
      this.isLoading = true
      try {
        const res = await axios.post('/api/auth/profile', formData)
        this.user = res.data.data
        localStorage.setItem('oxford_user', JSON.stringify(this.user))
        await this.fetchMe()
        return true
      } catch (err) {
        this.error = err.response?.data?.message || 'Lỗi cập nhật'
        return false
      } finally {
        this.isLoading = false
      }
    },

    async logout() {
      try {
        await axios.post('/api/auth/logout')
      } catch (e) {}
      this.user = null
      this.stats = null
      this.token = null
      localStorage.removeItem('oxford_token')
      localStorage.removeItem('oxford_user')
      delete axios.defaults.headers.common['Authorization']
    },
  },
})
