<template>
  <div class="container py-4 max-w-5xl">
    <div class="text-center mb-5">
      <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">BÍ QUYẾT HỌC NHANH</span>
      <h2 class="fw-black text-teal mt-2 mb-2">Mẹo Học Tiếng Anh Hiệu Quả</h2>
      <p class="text-muted fs-7 max-w-xl mx-auto">
        Tổng hợp các phương pháp khoa học giúp ghi nhớ từ vựng sâu sắc, mẹo loại suy trắc nghiệm và phát âm chuẩn bản ngữ.
      </p>
    </div>

    <!-- Category Filters -->
    <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap">
      <button
        @click="selectedCategory = 'all'"
        class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
        :class="selectedCategory === 'all' ? 'btn-teal' : 'btn-outline-secondary'"
      >
        Tất cả mẹo
      </button>
      <button
        v-for="cat in Object.keys(tipsCategories)"
        :key="cat"
        @click="selectedCategory = cat"
        class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold"
        :class="selectedCategory === cat ? 'btn-teal' : 'btn-outline-secondary'"
      >
        {{ cat }}
      </button>
    </div>

    <!-- Tips Cards -->
    <div class="row g-4">
      <div v-for="tip in filteredTips" :key="tip.id" class="col-12 col-md-6">
        <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-8">
              {{ tip.category }}
            </span>
          </div>

          <h5 class="fw-bold text-teal mb-3">{{ tip.title }}</h5>
          <div class="tip-content fs-7 text-muted leading-relaxed" v-html="tip.content_html"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const allTips = ref([])
const tipsCategories = ref({})
const selectedCategory = ref('all')

const filteredTips = computed(() => {
  if (selectedCategory.value === 'all') return allTips.value
  return allTips.value.filter(t => t.category === selectedCategory.value)
})

onMounted(async () => {
  try {
    const res = await axios.get('/api/learning-tips')
    allTips.value = res.data.data.tips
    tipsCategories.value = res.data.data.categories
  } catch (err) {
    console.error('Failed to load tips:', err)
  }
})
</script>
