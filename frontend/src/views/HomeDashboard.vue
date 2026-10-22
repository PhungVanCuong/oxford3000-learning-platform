<template>
  <div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Top Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900 border border-indigo-700/40 p-6 md:p-10 mb-8 shadow-2xl">
      <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-indigo-500/30 text-indigo-300 border border-indigo-400/30">
              Oxford 3000™ Master
            </span>
            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
              Cấp độ: {{ dashboard?.user?.current_level || 'A1' }}
            </span>
          </div>
          <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">
            Xin chào, {{ dashboard?.user?.name || 'Học viên Oxford' }}! 👋
          </h1>
          <p class="text-slate-300 text-sm md:text-base max-w-xl">
            Lộ trình học bài bản: <strong>Xây dựng kiến thức nền tảng trước → Thực hành làm bài kiểm tra sau</strong>.
          </p>
        </div>

        <!-- Quick Stats Pill (Streak & XP) -->
        <div class="flex items-center gap-4 bg-slate-950/60 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
          <div class="text-center px-3">
            <div class="text-2xl font-black text-amber-400 flex items-center justify-center gap-1">
              <span>🔥</span>
              <span>{{ dashboard?.user?.streak_days || 1 }}</span>
            </div>
            <div class="text-xs text-slate-400 font-medium">Ngày liên tiếp</div>
          </div>
          <div class="h-8 w-px bg-slate-800"></div>
          <div class="text-center px-3">
            <div class="text-2xl font-black text-purple-400 flex items-center justify-center gap-1">
              <span>⚡</span>
              <span>{{ dashboard?.user?.xp_points || 0 }}</span>
            </div>
            <div class="text-xs text-slate-400 font-medium">Điểm kinh nghiệm</div>
          </div>
        </div>
      </div>
    </div>

    <!-- 3000 Words Progress Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <!-- Total Words -->
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex items-center justify-between">
        <div>
          <span class="text-xs font-semibold text-slate-400 block mb-1">Tổng kho từ vựng</span>
          <span class="text-3xl font-black text-white">{{ dashboard?.stats?.total_words || 3351 }}</span>
          <span class="text-xs text-slate-500 block mt-1">Từ điển chuẩn Oxford</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-xl font-bold">
          📚
        </div>
      </div>

      <!-- Mastered Words -->
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex items-center justify-between">
        <div>
          <span class="text-xs font-semibold text-emerald-400 block mb-1">Đã thuộc nhuần nhuyễn</span>
          <span class="text-3xl font-black text-emerald-400">{{ dashboard?.stats?.mastered_count || 0 }}</span>
          <span class="text-xs text-slate-500 block mt-1">Chuỗi đúng ≥ 3 lần</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl font-bold">
          ⭐
        </div>
      </div>

      <!-- Learning Words -->
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex items-center justify-between">
        <div>
          <span class="text-xs font-semibold text-amber-400 block mb-1">Đang tích cực học</span>
          <span class="text-3xl font-black text-amber-400">{{ dashboard?.stats?.learning_count || 0 }}</span>
          <span class="text-xs text-slate-500 block mt-1">Đang luyện tập</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl font-bold">
          🔄
        </div>
      </div>

      <!-- New Words -->
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex items-center justify-between">
        <div>
          <span class="text-xs font-semibold text-slate-400 block mb-1">Chưa học</span>
          <span class="text-3xl font-black text-slate-300">{{ dashboard?.stats?.new_count || 3351 }}</span>
          <span class="text-xs text-slate-500 block mt-1">Sẵn sàng khám phá</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-800 text-slate-400 flex items-center justify-center text-xl font-bold">
          ⏳
        </div>
      </div>
    </div>

    <!-- Progress Bar Overall -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl mb-8">
      <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-bold text-white">Tiến độ chinh phục 3000 từ vựng Oxford</span>
        <span class="text-sm font-extrabold text-indigo-400">
          {{ ((dashboard?.stats?.mastered_count || 0) + (dashboard?.stats?.learning_count || 0)) }} / {{ dashboard?.stats?.total_words || 3351 }} từ
        </span>
      </div>
      <div class="w-full bg-slate-950 rounded-full h-4 overflow-hidden flex">
        <div
          class="bg-emerald-500 h-4 transition-all duration-500"
          :style="{ width: `${dashboard?.stats?.mastered_percentage || 0.5}%` }"
          title="Đã thuộc"
        ></div>
        <div
          class="bg-amber-500 h-4 transition-all duration-500"
          :style="{ width: `${dashboard?.stats?.learning_percentage || 0.5}%` }"
          title="Đang học"
        ></div>
      </div>
      <div class="flex items-center gap-6 mt-3 text-xs text-slate-400">
        <div class="flex items-center gap-2">
          <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
          <span>Đã thuộc ({{ dashboard?.stats?.mastered_percentage || 0 }}%)</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
          <span>Đang học ({{ dashboard?.stats?.learning_percentage || 0 }}%)</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="w-3 h-3 rounded-full bg-slate-800 inline-block"></span>
          <span>Chưa học</span>
        </div>
      </div>
    </div>

    <!-- Two Columns: Continue Learning & 7-Day Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
      <!-- Continue Card -->
      <div class="p-6 rounded-2xl bg-gradient-to-br from-indigo-950/70 to-slate-900 border border-indigo-500/30 shadow-xl flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Tiếp tục lộ trình học</span>
            <span class="text-xs px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300">Đang học dở</span>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">
            {{ dashboard?.active_node?.title || 'Chặng 1: 20 từ vựng cốt lõi vần A' }}
          </h3>
          <p class="text-xs text-slate-400 line-clamp-2 mb-6">
            {{ dashboard?.active_node?.description || 'Bộ 20 từ vựng đầu tiên trong từ điển Oxford 3000...' }}
          </p>
        </div>

        <button
          @click="continueActiveNode"
          class="w-full py-3.5 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm transition-all shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2"
        >
          <span>Vào bài học ngay</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </button>
      </div>

      <!-- 7-Day Chart -->
      <div class="lg:col-span-2 p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-base font-bold text-white">Thống kê hoạt động 7 ngày qua</h3>
          <span class="text-xs text-slate-500">Số từ vựng đã ôn luyện</span>
        </div>

        <div class="flex items-end justify-between gap-2 h-44 pt-6 px-2">
          <div
            v-for="(day, idx) in dashboard?.weekly_stats || []"
            :key="idx"
            class="flex-1 flex flex-col items-center gap-2 h-full justify-end"
          >
            <div class="text-[11px] font-bold text-indigo-400">{{ day.words_reviewed }}</div>
            <div
              class="w-full max-w-[36px] bg-indigo-600/30 hover:bg-indigo-600 rounded-t-lg transition-all"
              :style="{ height: `${Math.max(15, Math.min(100, day.words_reviewed * 10))}%` }"
            ></div>
            <div class="text-xs text-slate-400 font-medium">{{ day.day }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Smart Review Area (Ôn tập thông minh hôm nay) -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
      <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
        <div>
          <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <span>🧠</span>
            <span>Ôn tập thông minh hôm nay (Spaced Repetition)</span>
          </h3>
          <p class="text-xs text-slate-400">Các từ vựng đến hạn ôn tập hoặc cần củng cố thêm chuỗi ghi nhớ</p>
        </div>
        <router-link
          to="/roadmap"
          class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors"
        >
          Xem toàn bộ Lộ trình →
        </router-link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          v-for="word in dashboard?.smart_review_words || []"
          :key="word.id"
          class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 hover:border-indigo-500/40 transition-colors flex flex-col justify-between"
        >
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-bold text-white">{{ word.word }}</span>
              <button
                @click="speech.speak(word.word)"
                class="p-1.5 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/40 transition-colors"
                title="Phát âm"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
              </button>
            </div>
            <div class="text-xs font-mono text-slate-400 mb-1">{{ word.phonetic_us }}</div>
            <p class="text-xs text-slate-300 line-clamp-2">{{ word.meaning_vi }}</p>
          </div>

          <div class="mt-3 pt-2 border-t border-slate-900 flex items-center justify-between text-[11px] text-slate-500">
            <span>{{ word.part_of_speech }}</span>
            <span class="text-amber-400 font-semibold">Chuỗi: {{ word.correct_streak }} ⭐</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useLearningStore } from '@/stores/learningStore'
import { useSpeech } from '@/composables/useSpeech'

const router = useRouter()
const learningStore = useLearningStore()
const speech = useSpeech()

const dashboard = computed(() => learningStore.dashboard)

const continueActiveNode = () => {
  const node = dashboard.value?.active_node
  if (!node) {
    router.push('/roadmap')
    return
  }

  if (node.node_type === 'grammar') {
    router.push(`/grammar/${node.id}`)
  } else {
    router.push(`/vocabulary/${node.id}`)
  }
}

onMounted(() => {
  learningStore.fetchDashboard()
})
</script>
