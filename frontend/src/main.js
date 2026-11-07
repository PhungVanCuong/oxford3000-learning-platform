import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import router from './router'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import './style.css'
import App from './App.vue'
import { vDragScroll, initGlobalDragScroll } from './directives/vDragScroll'

// Tự động nhận diện URL Backend thông minh đa môi trường (Localhost & Domain deloydz.io.vn):
function resolveApiBaseUrl() {
  const envUrl = import.meta.env.VITE_API_BASE_URL
  if (envUrl && envUrl.trim() !== '') {
    return envUrl.trim().replace(/\/+$/, '')
  }

  if (typeof window !== 'undefined' && window.location) {
    const hostname = window.location.hostname

    // 1. Khi truy cập từ domain chính thức: FE oxvocab.deloydz.io.vn -> BE beoxvocab.deloydz.io.vn
    if (hostname === 'oxvocab.deloydz.io.vn' || hostname.endsWith('.deloydz.io.vn')) {
      const protocol = window.location.protocol === 'http:' ? 'http:' : 'https:'
      return `${protocol}//beoxvocab.deloydz.io.vn`
    }

    // 2. Khi chạy trên localhost / 127.0.0.1 tại máy cá nhân:
    if (hostname === 'localhost' || hostname === '127.0.0.1' || hostname === '0.0.0.0') {
      if (import.meta.env.DEV) {
        return '' // Sử dụng Vite proxy (/api -> http://127.0.0.1:8000)
      }
      return 'http://127.0.0.1:8000'
    }
  }

  return 'https://beoxvocab.deloydz.io.vn'
}

const apiBaseUrl = resolveApiBaseUrl()
if (apiBaseUrl) {
  axios.defaults.baseURL = apiBaseUrl
}

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.directive('drag-scroll', vDragScroll)

app.mount('#app')

// Kích hoạt tính năng kéo lướt ngang bằng chuột trái toàn hệ thống
initGlobalDragScroll()
