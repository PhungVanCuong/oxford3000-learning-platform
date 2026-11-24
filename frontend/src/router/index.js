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
import CurriculumRoadmapView from '@/views/user/CurriculumRoadmapView.vue'
import CurriculumStageDetailView from '@/views/user/CurriculumStageDetailView.vue'
import KnowledgeTreeView from '@/views/user/KnowledgeTreeView.vue'
import ActivityStatsView from '@/views/user/ActivityStatsView.vue'
import ArenaView from '@/views/user/ArenaView.vue'
import ArenaRoomView from '@/views/user/ArenaRoomView.vue'
import CefrVocabularyView from '@/views/user/CefrVocabularyView.vue'
import ReviewSpacedRepetitionView from '@/views/user/ReviewSpacedRepetitionView.vue'
import HistoryStudyView from '@/views/user/HistoryStudyView.vue'
import VocabularyNotebookView from '@/views/user/VocabularyNotebookView.vue'
import DictationView from '@/views/user/DictationView.vue'
import NotFoundView from '@/views/NotFoundView.vue'

// Admin Views
import AdminDashboardView from '@/views/admin/AdminDashboardView.vue'
import AdminUsersRolesView from '@/views/admin/AdminUsersRolesView.vue'
import AdminRolesPermissionsView from '@/views/admin/AdminRolesPermissionsView.vue'
import AdminVocabulariesView from '@/views/admin/AdminVocabulariesView.vue'
import AdminPhrasalVerbsView from '@/views/admin/AdminPhrasalVerbsView.vue'
import AdminCurriculumView from '@/views/admin/AdminCurriculumView.vue'
import AdminArenaView from '@/views/admin/AdminArenaView.vue'
import AdminGrammarsView from '@/views/admin/AdminGrammarsView.vue'
import AdminCefrView from '@/views/admin/AdminCefrView.vue'

// Auth Views
import LoginView from '@/views/auth/LoginView.vue'
import RegisterView from '@/views/auth/RegisterView.vue'
import ForgotPasswordView from '@/views/auth/ForgotPasswordView.vue'
import GoogleCallbackView from '@/views/auth/GoogleCallbackView.vue'

const routes = [
  // User Portal Routes (with UserLayout)
  {
    path: '/',
    component: UserLayout,
    children: [
      { path: '', name: 'home', component: HomeView },
      { path: 'curriculum', name: 'curriculum', component: CurriculumRoadmapView },
      { path: 'curriculum/:topicId/stage/:stageNum', name: 'curriculum-stage', component: CurriculumStageDetailView },
      { path: 'daily', name: 'daily', component: DailyStudyView },
      { path: 'topics', name: 'topics', component: TopicRoadmapView },
      { path: 'topics/:id', name: 'topic-detail', component: TopicDetailView },
      { path: 'phrasal-verbs', name: 'phrasal-verbs', component: PhrasalVerbsView },
      { path: 'grammar', name: 'grammar', component: GrammarView },
      { path: 'grammar/:id', name: 'grammar-detail', component: GrammarDetailView },
      { path: 'ipa', name: 'ipa', component: IpaView },
      { path: 'dictionary', name: 'dictionary', component: DictionaryView },
      { path: 'tips', name: 'tips', component: TipsView },
      { path: 'tree', name: 'knowledge-tree', component: KnowledgeTreeView },
      { path: 'stats', name: 'activity-stats', component: ActivityStatsView },
      { path: 'arena', name: 'arena', component: ArenaView },
      { path: 'arena/:idOrCode', name: 'arena-room', component: ArenaRoomView },
      { path: 'cefr', name: 'cefr-vocabulary', component: CefrVocabularyView },
      { path: 'cefr/:levelId/lesson/:lessonNum', name: 'cefr-lesson-detail', component: CefrVocabularyView },
      { path: 'review', name: 'review', component: ReviewSpacedRepetitionView },
      { path: 'history', name: 'history', component: HistoryStudyView },
      { path: 'dictation', name: 'dictation', component: DictationView },
      { path: 'dictation/:topicId', name: 'dictation-topic', component: DictationView },
      { path: 'vocabulary-notebook', name: 'vocabulary-notebook', component: VocabularyNotebookView },
      { path: 'notebook', redirect: '/vocabulary-notebook' },
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

      const isStaffOrAdmin = user && (['admin', 'moderator', 'editor', 'tutor'].includes(user.role) || user.email === 'cuongphung1810@gmail.com')

      if (!token) {
        next('/login')
      } else if (!isStaffOrAdmin) {
        alert('Tài khoản của bạn không có quyền truy cập vào Khu Vực Quản Trị Hệ Thống.')
        next('/')
      } else {
        next()
      }
    },
    children: [
      { path: '', name: 'admin-dashboard', component: AdminDashboardView },
      {
        path: 'curriculum',
        name: 'admin-curriculum',
        component: AdminCurriculumView,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem('oxford_user') || '{}')
          const canAccess = user.role === 'admin' || user.email === 'cuongphung1810@gmail.com' || (user.permissions || []).includes('manage_curriculum')
          if (!canAccess) {
            alert('Bạn không có quyền truy cập vào mục Lộ trình 25 Chủ đề.')
            next('/admin')
          } else {
            next()
          }
        }
      },
      {
        path: 'users',
        name: 'admin-users',
        component: AdminUsersRolesView,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem('oxford_user') || '{}')
          const canAccess = user.role === 'admin' || user.email === 'cuongphung1810@gmail.com' || (user.permissions || []).includes('manage_users') || (user.permissions || []).includes('view_students')
          if (!canAccess) {
            alert('Bạn không có quyền truy cập vào mục Quản lý Học viên.')
            next('/admin')
          } else {
            next()
          }
        }
      },
      {
        path: 'roles',
        name: 'admin-roles',
        component: AdminRolesPermissionsView,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem('oxford_user') || '{}')
          const canAccess = user.role === 'admin' || user.email === 'cuongphung1810@gmail.com' || (user.permissions || []).includes('manage_roles')
          if (!canAccess) {
            alert('Chỉ Quản trị viên Cấp cao mới có quyền truy cập vào Trung tâm Phân quyền Chức vụ.')
            next('/admin')
          } else {
            next()
          }
        }
      },
      {
        path: 'vocabularies',
        name: 'admin-vocabularies',
        component: AdminVocabulariesView,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem('oxford_user') || '{}')
          const canAccess = user.role === 'admin' || user.email === 'cuongphung1810@gmail.com' || (user.permissions || []).includes('manage_vocab')
          if (!canAccess) {
            alert('Bạn không có quyền truy cập vào mục Kho từ vựng & AI.')
            next('/admin')
          } else {
            next()
          }
        }
      },
      {
        path: 'phrasal-verbs',
        name: 'admin-phrasal-verbs',
        component: AdminPhrasalVerbsView,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem('oxford_user') || '{}')
          const canAccess = user.role === 'admin' || user.email === 'cuongphung1810@gmail.com' || (user.permissions || []).includes('manage_phrases')
          if (!canAccess) {
            alert('Bạn không có quyền truy cập vào mục Cụm từ & Phrasal Verbs.')
            next('/admin')
          } else {
            next()
          }
        }
      },
      {
        path: 'arena',
        name: 'admin-arena',
        component: AdminArenaView,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem('oxford_user') || '{}')
          const canAccess = user.role === 'admin' || user.email === 'cuongphung1810@gmail.com' || (user.permissions || []).includes('manage_arena')
          if (!canAccess) {
            alert('Bạn không có quyền truy cập vào mục Quản lý Đấu trường.')
            next('/admin')
          } else {
            next()
          }
        }
      },
      {
        path: 'grammars',
        name: 'admin-grammars',
        component: AdminGrammarsView,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem('oxford_user') || '{}')
          const canAccess = user.role === 'admin' || user.email === 'cuongphung1810@gmail.com' || (user.permissions || []).includes('manage_curriculum')
          if (!canAccess) {
            alert('Bạn không có quyền truy cập vào mục Quản lý Ngữ pháp.')
            next('/admin')
          } else {
            next()
          }
        }
      },
      {
        path: 'cefr',
        name: 'admin-cefr',
        component: AdminCefrView,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem('oxford_user') || '{}')
          const canAccess = user.role === 'admin' || user.email === 'cuongphung1810@gmail.com' || (user.permissions || []).includes('manage_vocab')
          if (!canAccess) {
            alert('Bạn không có quyền truy cập vào mục Quản lý Từ vựng CEFR.')
            next('/admin')
          } else {
            next()
          }
        }
      },
    ],
  },

  // Auth Standalone Routes (Dedicated Custom Auth Layout)
  { path: '/login', name: 'login', component: LoginView },
  { path: '/register', name: 'register', component: RegisterView },
  { path: '/forgot-password', name: 'forgot-password', component: ForgotPasswordView },
  { path: '/auth/google/callback', name: 'google-callback', component: GoogleCallbackView },

  // Catch-all 404 Route (Biển báo đường dẫn bị lỗi kèm nút quay lại trang chủ)
  {
    path: '/:pathMatch(.*)*',
    component: UserLayout,
    children: [
      { path: '', name: 'not-found', component: NotFoundView }
    ]
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

export default router
