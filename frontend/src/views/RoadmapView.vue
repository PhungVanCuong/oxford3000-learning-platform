<template>
  <div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-10">
      <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
        Lộ trình học theo chuẩn CEFR
      </span>
      <h1 class="text-3xl md:text-4xl font-black text-white mt-2 mb-2 tracking-tight">
        Sơ Đồ Chinh Phục Oxford 3000 & Ngữ Pháp
      </h1>
      <p class="text-slate-400 text-sm max-w-xl mx-auto">
        Triết lý đào tạo: <strong>Học kiến thức nền tảng trước → Làm bài kiểm tra đạt ≥ 80% điểm để mở khóa bài tiếp theo</strong>.
      </p>
    </div>

    <!-- Roadmaps List -->
    <div v-for="roadmap in learningStore.roadmaps" :key="roadmap.id" class="mb-12">
      <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-800">
        <div>
          <h2 class="text-xl font-bold text-white">{{ roadmap.title }}</h2>
          <p class="text-xs text-slate-400">{{ roadmap.description }}</p>
        </div>
        <span class="text-xs font-bold px-3 py-1 rounded-lg bg-indigo-950 text-indigo-300 border border-indigo-800">
          Cấp độ: {{ roadmap.level }}
        </span>
      </div>

      <!-- Stepping Stones / Stages Timeline -->
      <div class="relative pl-6 md:pl-10 space-y-6">
        <!-- Connecting Line -->
        <div class="absolute left-3 md:left-5 top-4 bottom-4 w-1 bg-gradient-to-b from-indigo-500 via-purple-500 to-slate-800 -translate-x-1/2 rounded-full"></div>

        <!-- Node Item -->
        <div
          v-for="(node, index) in roadmap.nodes"
          :key="node.id"
          class="relative flex items-start gap-4 md:gap-6 group"
        >
          <!-- Stepping Stone Circle Indicator -->
          <div
            class="relative z-10 w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm shrink-0 shadow-lg border transition-all"
            :class="{
              'bg-emerald-600 text-white border-emerald-400 ring-4 ring-emerald-500/20': node.best_attempt?.is_passed,
              'bg-indigo-600 text-white border-indigo-400 ring-4 ring-indigo-500/30 animate-pulse': !node.is_locked && !node.best_attempt?.is_passed,
              'bg-slate-900 text-slate-500 border-slate-800': node.is_locked
            }"
          >
            <span v-if="node.best_attempt?.is_passed">✓</span>
            <span v-else-if="node.is_locked">🔒</span>
            <span v-else>{{ index + 1 }}</span>
          </div>

          <!-- Stage Card -->
          <div
            class="flex-1 p-5 md:p-6 rounded-2xl border transition-all shadow-xl"
            :class="{
              'bg-slate-900/90 border-slate-700/80 hover:border-indigo-500/60 cursor-pointer': !node.is_locked,
              'bg-slate-950/60 border-slate-900 opacity-60 cursor-not-allowed': node.is_locked,
              'ring-1 ring-emerald-500/50 bg-emerald-950/10': node.best_attempt?.is_passed
            }"
            @click="!node.is_locked && openNode(node)"
          >
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-2">
              <div class="flex items-center gap-2 flex-wrap">
                <span
                  class="text-xs font-bold px-2.5 py-0.5 rounded-full"
                  :class="node.node_type === 'vocabulary' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30' : 'bg-purple-500/20 text-purple-400 border border-purple-500/30'"
                >
                  {{ node.node_type === 'vocabulary' ? '📖 BỘ TỪ VỰNG' : '📐 CHUYÊN ĐỀ NGỮ PHÁP' }}
                </span>

                <span v-if="node.best_attempt?.is_passed" class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                  Đã vượt qua ({{ node.best_attempt.percentage }}%)
                </span>
                <span v-else-if="!node.is_locked" class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">
                  Đang mở khóa
                </span>
                <span v-else class="text-xs font-medium text-slate-500">
                  Cần vượt qua bài trước (≥ {{ node.passing_score }}%)
                </span>
              </div>

              <span class="text-xs font-medium text-slate-400">
                {{ node.node_type === 'vocabulary' ? `${node.word_count} từ vựng` : `${node.grammar_questions_count} câu bài tập` }}
              </span>
            </div>

            <h3 class="text-lg md:text-xl font-bold text-white mb-1.5 group-hover:text-indigo-300 transition-colors">
              {{ node.title }}
            </h3>
            <p class="text-xs md:text-sm text-slate-400 mb-4 leading-relaxed">
              {{ node.description }}
            </p>

            <!-- Bottom Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800/80">
              <div class="text-xs text-slate-500">
                Yêu cầu: Học kiến thức → Đạt ≥ {{ node.passing_score }}% kiểm tra
              </div>

              <button
                v-if="!node.is_locked"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition-colors shadow-lg shadow-indigo-600/30"
              >
                <span>Vào học</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
              <div v-else class="flex items-center gap-1 text-xs text-slate-600 font-medium">
                <span>Khóa</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useLearningStore } from '@/stores/learningStore'

const router = useRouter()
const learningStore = useLearningStore()

const openNode = (node) => {
  if (node.node_type === 'grammar') {
    router.push(`/grammar/${node.id}`)
  } else {
    router.push(`/vocabulary/${node.id}`)
  }
}

onMounted(() => {
  learningStore.fetchRoadmaps()
})
</script>
