import { createRouter, createWebHistory } from 'vue-router'
import UserLayout from '@/layouts/UserLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

// User Views
import HomeView from '@/views/user/HomeView.vue'
import DailyStudyView from '@/views/user/DailyStudyView.vue'
import TopicRoadmapView from '@/views/user/TopicRoadmapView.vue'
import TopicDetailView from '@/views/user/TopicDetailView.vue'
import PhrasalVerbsView from '@/views/user/PhrasalVerbsView.vue'
import GrammarView from '@/views/user/GrammarView.vue'
import GrammarDetailView from '@/views/user/GrammarDetailView.vue'
import IpaView from '@/views/user/IpaView.vue'
import DictionaryView from '@/views/user/DictionaryView.vue'
import TipsView from '@/views/user/TipsView.vue'
import ProfileView from '@/views/user/ProfileView.vue'

// Admin Views
import AdminDashboardView from '@/views/admin/AdminDashboardView.vue'
import AdminUsersRolesView from '@/views/admin/AdminUsersRolesView.vue'
import AdminVocabulariesView from '@/views/admin/AdminVocabulariesView.vue'
import AdminPhrasalVerbsView from '@/views/admin/AdminPhrasalVerbsView.vue'

// Auth Views
import LoginView from '@/views/auth/LoginView.vue'
import RegisterView from '@/views/auth/RegisterView.vue'
import ForgotPasswordView from '@/views/auth/ForgotPasswordView.vue'

const routes = [
  // User Portal Routes (with UserLayout)
  {
    path: '/',
    component: UserLayout,
    children: [
      { path: '', name: 'home', component: HomeView },
      { path: 'daily', name: 'daily', component: DailyStudyView },
      { path: 'topics', name: 'topics', component: TopicRoadmapView },
      { path: 'topics/:id', name: 'topic-detail', component: TopicDetailView },
      { path: 'phrasal-verbs', name: 'phrasal-verbs', component: PhrasalVerbsView },
      { path: 'grammar', name: 'grammar', component: GrammarView },
      { path: 'grammar/:id', name: 'grammar-detail', component: GrammarDetailView },
      { path: 'ipa', name: 'ipa', component: IpaView },
      { path: 'dictionary', name: 'dictionary', component: DictionaryView },
      { path: 'tips', name: 'tips', component: TipsView },
      { path: 'profile', name: 'profile', component: ProfileView },
    ],
  },

  // Admin Portal Routes (with AdminLayout)
  {
    path: '/admin',
    component: AdminLayout,
    beforeEnter: (to, from, next) => {
      const token = localStorage.getItem('oxford_token')
      const storedUser = localStorage.getItem('oxford_user')
      let user = null
      try {
        user = storedUser ? JSON.parse(storedUser) : null
      } catch (e) {}

      const isAdmin = user && (user.role === 'admin' || user.role === 'moderator' || user.email === 'cuongphung1810@gmail.com')

      if (!token) {
        next('/login?portal=admin')
      } else if (!isAdmin) {
        alert('Tài khoản của bạn không có quyền truy cập vào Khu Vực Quản Trị Hệ Thống.')
        next('/')
      } else {
        next()
      }
    },
    children: [
      { path: '', name: 'admin-dashboard', component: AdminDashboardView },
      { path: 'users', name: 'admin-users', component: AdminUsersRolesView },
      { path: 'vocabularies', name: 'admin-vocabularies', component: AdminVocabulariesView },
      { path: 'phrasal-verbs', name: 'admin-phrasal-verbs', component: AdminPhrasalVerbsView },
    ],
  },

  // Auth Standalone Routes (Dedicated Custom Auth Layout)
  { path: '/login', name: 'login', component: LoginView },
  { path: '/register', name: 'register', component: RegisterView },
  { path: '/forgot-password', name: 'forgot-password', component: ForgotPasswordView },

  // Catch-all redirect to Home
  { path: '/:pathMatch(.*)*', redirect: '/' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

export default router
