<template>
  <div class="knowledge-tree-page py-4">
    <div class="container max-w-6xl">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb fs-8 mb-0">
          <li class="breadcrumb-item"><router-link to="/" class="text-decoration-none text-muted">Trang chủ</router-link></li>
          <li class="breadcrumb-item active text-teal fw-bold" aria-current="page">Vườn Cây Tri Thức (30 Cấp Độ)</li>
        </ol>
      </nav>

      <!-- Header & Stats Summary -->
      <div class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom mb-4 text-center position-relative overflow-hidden">
        <div class="position-relative z-1 max-w-2xl mx-auto">
          <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fs-8 fw-bold mb-2">
            🌱 HỆ THỐNG TRỒNG CÂY TRI THỨC 30 CẤP TIẾN HÓA
          </span>
          <h2 class="fw-black text-teal mb-2 display-6">Vườn Cây Tri Thức Của Bạn</h2>
          <p class="text-muted fs-7 mb-4">
            Mỗi từ vựng ghi nhớ, bài tập ngữ pháp và chuỗi ngày học sẽ bồi đắp dưỡng chất cho cây: đào đất, nảy mầm, leo giàn, đâm chồi, nở ngàn hoa rực rỡ và đơm hoa kết trái ngọt ngào!
          </p>

          <!-- 4 Stats Cards -->
          <div class="row g-3 justify-content-center">
            <div class="col-6 col-md-3">
              <div class="p-3 rounded-4 bg-body-tertiary border text-center shadow-2xs">
                <div class="fs-8 text-muted">Cấp độ sinh trưởng</div>
                <div class="fw-black text-teal fs-4 mt-0.5">Lv.{{ currentLevel.level }}/30</div>
                <div class="fs-8 text-secondary text-truncate">{{ currentLevel.name }}</div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="p-3 rounded-4 bg-body-tertiary border text-center shadow-2xs">
                <div class="fs-8 text-muted">Tổng tích lũy</div>
                <div class="fw-black text-warning fs-4 mt-0.5">{{ userXp }} XP</div>
                <div class="fs-8 text-secondary">Kinh nghiệm học tập</div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="p-3 rounded-4 bg-body-tertiary border text-center shadow-2xs">
                <div class="fs-8 text-muted">Chuỗi ngày học</div>
                <div class="fw-black text-danger fs-4 mt-0.5">🔥 {{ userStreak }} Ngày</div>
                <div class="fs-8 text-secondary">Học tập liên tục</div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="p-3 rounded-4 bg-body-tertiary border text-center shadow-2xs">
                <div class="fs-8 text-muted">Trái ngọt thu hoạch</div>
                <div class="fw-black text-success fs-4 mt-0.5">🍎 {{ harvestedFruits }} Quả</div>
                <div class="fs-8 text-secondary">Tổng quả đã hái</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Tree Garden & Level Roadmap -->
      <div class="row g-4 mb-4">
        <!-- Interactive Tree Graphic Column -->
        <div class="col-12 col-lg-8">
          <div
            class="card rounded-4 shadow-sm border bg-card-custom overflow-hidden h-100 position-relative d-flex flex-column justify-content-between"
            :style="{
              minHeight: '520px',
              background: authStore.themeMode === 'dark'
                ? 'linear-gradient(180deg, #0b192c 0%, #0f2936 45%, #064e3b 85%, #065f46 100%)'
                : 'linear-gradient(180deg, #e0f2fe 0%, #bae6fd 35%, #dcfce7 75%, #86efac 100%)'
            }"
          >
            
            <!-- Sun & Cloud Elements -->
            <div class="position-absolute top-0 end-0 p-4 pointer-events-none">
              <div class="sun-glow"></div>
            </div>

            <!-- Stage & Level Badge Overlay + History Switcher -->
            <div class="p-3 z-2 w-100 d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-card-custom text-dark-emphasis shadow-sm px-3 py-1.5 rounded-pill fw-bold fs-7 border">
                  ⭐ Cấp {{ displayedLevel.level }}: {{ displayedLevel.name }}
                </span>
                <span class="badge bg-success text-white px-2.5 py-1.5 rounded-pill fs-8 shadow-sm">
                  {{ displayedLevel.stageDesc }}
                </span>
              </div>

              <!-- History Mode Indicator -->
              <div v-if="isPreviewingPast" class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold shadow-sm d-flex align-items-center gap-1.5">
                <i class="bi bi-clock-history"></i>
                <span>Đang xem lại Cấp {{ displayedLevel.level }}</span>
                <button @click="resetToCurrentLevel" class="btn btn-sm btn-dark py-0 px-2 rounded-pill fs-8 text-white ms-1 border-0">
                  Về cấp hiện tại
                </button>
              </div>
            </div>

            <!-- Dynamic SVG Tree Graphic -->
            <div class="d-flex align-items-end justify-content-center flex-grow-1 pb-1 position-relative">
              <svg viewBox="0 0 500 500" class="w-100" style="max-height: 410px;" xmlns="http://www.w3.org/2000/svg">
                <!-- Ground Grass Hill & Soil Mound -->
                <ellipse cx="250" cy="480" rx="240" ry="45" fill="#4ade80" />
                <ellipse cx="250" cy="480" rx="220" ry="36" fill="#22c55e" />
                <path d="M 50 480 Q 250 440 450 480 Z" fill="#15803d" opacity="0.35" />

                <!-- Soil Mound (Đất tơi xốp) -->
                <ellipse cx="250" cy="460" rx="65" ry="18" fill="#78350f" opacity="0.85" />
                <ellipse cx="250" cy="458" rx="45" ry="12" fill="#92400e" />

                <!-- Stage 1-2: Hạt Mầm & Đào Đất -->
                <g v-if="displayedLevel.level <= 2">
                  <circle cx="250" cy="450" r="14" fill="#5c2c16" stroke="#451a03" stroke-width="2" />
                  <!-- Tiny crack or root -->
                  <path d="M 250 450 Q 252 430 250 420" stroke="#84cc16" stroke-width="4" stroke-linecap="round" fill="none" />
                  <circle v-if="displayedLevel.level === 2" cx="250" cy="418" r="5" fill="#a3e635" />
                </g>

                <!-- Stage 3-5: Nứt vỏ, Chồi non & Hai lá mầm -->
                <g v-else-if="displayedLevel.level <= 5">
                  <path d="M 250 460 Q 248 400 250 360" stroke="#65a30d" stroke-width="7" stroke-linecap="round" fill="none" />
                  <!-- 2 or 4 green leaves -->
                  <path d="M 250 360 Q 220 340 215 360 Q 230 370 250 360" fill="#4ade80" />
                  <path d="M 250 360 Q 280 340 285 360 Q 270 370 250 360" fill="#22c55e" />
                  <g v-if="displayedLevel.level >= 4">
                    <path d="M 250 380 Q 225 370 220 385 Q 235 390 250 380" fill="#86efac" />
                    <path d="M 250 380 Q 275 370 280 385 Q 265 390 250 380" fill="#4ade80" />
                  </g>
                </g>

                <!-- Stage 6-30: Growing Tree to Cosmic Tree -->
                <g v-else>
                  <!-- Vines wrapping the trunk (Level 11+) -->
                  <g v-if="displayedLevel.level >= 11">
                    <path
                      d="M 235 450 Q 270 380 240 320 Q 265 260 250 200"
                      stroke="#16a34a"
                      stroke-width="5"
                      fill="none"
                      stroke-linecap="round"
                      opacity="0.85"
                    />
                    <circle cx="245" cy="350" r="6" fill="#86efac" />
                    <circle cx="258" cy="280" r="7" fill="#4ade80" />
                  </g>

                  <!-- Main Trunk (Scales with level) -->
                  <path
                    :d="trunkPath"
                    :fill="displayedLevel.level >= 26 ? '#581c87' : (displayedLevel.level >= 20 ? '#451a03' : '#78350f')"
                    :stroke="displayedLevel.level >= 26 ? '#facc15' : '#451a03'"
                    stroke-width="2.5"
                  />

                  <!-- Foliage / Canopy Clouds -->
                  <g class="tree-foliage">
                    <circle :cx="250" :cy="foliageTop" :r="foliageRadius" :fill="foliageColor1" opacity="0.92" />
                    <circle :cx="200 - foliageSpread" :cy="foliageTop + 35" :r="foliageRadius * 0.85" :fill="foliageColor2" opacity="0.94" />
                    <circle :cx="300 + foliageSpread" :cy="foliageTop + 35" :r="foliageRadius * 0.85" :fill="foliageColor1" opacity="0.94" />
                    <circle :cx="250" :cy="foliageTop + 65" :r="foliageRadius * 0.95" :fill="foliageColor3" opacity="0.96" />
                    <circle :cx="160 - foliageSpread" :cy="foliageTop + 75" :r="foliageRadius * 0.75" :fill="foliageColor2" opacity="0.92" />
                    <circle :cx="340 + foliageSpread" :cy="foliageTop + 75" :r="foliageRadius * 0.75" :fill="foliageColor1" opacity="0.92" />
                  </g>

                  <!-- Blooming Flowers (Level >= 12) -->
                  <g v-if="displayedLevel.level >= 12" class="blooming-flowers">
                    <g v-for="(fl, fIdx) in flowerPositions" :key="'fl-' + fIdx" :transform="`translate(${fl.x}, ${fl.y}) scale(${fl.scale})`">
                      <circle cx="0" cy="0" r="9" fill="#f43f5e" />
                      <circle cx="-5" cy="-5" r="5" fill="#fb7185" />
                      <circle cx="5" cy="-5" r="5" fill="#fb7185" />
                      <circle cx="-5" cy="5" r="5" fill="#fb7185" />
                      <circle cx="5" cy="5" r="5" fill="#fb7185" />
                      <circle cx="0" cy="0" r="3.5" fill="#fde047" />
                    </g>
                  </g>

                  <!-- Ripening & Golden Fruits (Level >= 16) -->
                  <g v-if="displayedLevel.level >= 16" class="ripening-fruits">
                    <g
                      v-for="(fr, frIdx) in fruitPositions"
                      :key="'fr-' + frIdx"
                      :transform="`translate(${fr.x}, ${fr.y})`"
                      class="fruit-item cursor-pointer"
                      @click="harvestSingleFruit(frIdx)"
                    >
                      <line x1="0" y1="-12" x2="0" y2="0" stroke="#15803d" stroke-width="2.5" />
                      <circle
                        cx="0"
                        cy="8"
                        :r="displayedLevel.level >= 24 ? 14 : 12"
                        :fill="fr.type === 'golden' || displayedLevel.level >= 24 ? '#eab308' : '#ef4444'"
                        stroke="#fff"
                        stroke-width="1.5"
                      />
                      <circle cx="-4" cy="5" r="3" fill="#ffffff" opacity="0.65" />
                    </g>
                  </g>

                  <!-- Cosmic / Mythical Aura (Level >= 26) -->
                  <g v-if="displayedLevel.level >= 26" class="cosmic-aura">
                    <circle cx="250" cy="220" r="190" fill="url(#cosmicTreeGlow)" opacity="0.4" />
                  </g>
                </g>

                <!-- SVG Definitions -->
                <defs>
                  <radialGradient id="cosmicTreeGlow" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#fef08a" stop-opacity="0.85" />
                    <stop offset="45%" stop-color="#38bdf8" stop-opacity="0.5" />
                    <stop offset="100%" stop-color="#818cf8" stop-opacity="0" />
                  </radialGradient>
                </defs>
              </svg>
            </div>

            <!-- Bottom Care Action Bar & History Slider (Non-overlapping on Mobile) -->
            <div class="p-3 bg-card-custom border-top d-flex flex-wrap align-items-center justify-content-between gap-2 z-2 mt-auto">
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- 1. Watering Button -->
                <button
                  @click="waterTree"
                  class="btn btn-sm btn-teal rounded-pill px-3 py-2 fw-bold text-white shadow-xs d-flex align-items-center gap-1.5"
                  :disabled="hasWateredToday"
                >
                  <i class="bi bi-droplet-fill text-info"></i>
                  <span>{{ hasWateredToday ? 'Đã tưới nước' : 'Tưới nước (+5 XP)' }}</span>
                </button>

                <!-- 2. Dig Soil Button -->
                <button
                  @click="digSoil"
                  class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-bold shadow-xs d-flex align-items-center gap-1.5"
                  :disabled="hasDugToday"
                >
                  <i class="bi bi-shovel text-warning"></i>
                  <span>{{ hasDugToday ? 'Đã xới đất' : 'Xới đất (+10 XP)' }}</span>
                </button>

                <!-- 3. Fertilize Button -->
                <button
                  @click="fertilizeTree"
                  class="btn btn-sm btn-outline-success rounded-pill px-3 py-2 fw-bold shadow-xs d-flex align-items-center gap-1.5"
                  :disabled="hasFertilizedToday"
                >
                  <i class="bi bi-capsule text-success"></i>
                  <span>{{ hasFertilizedToday ? 'Đã bón phân' : 'Bón phân (+25 XP)' }}</span>
                </button>

                <!-- 4. Harvest Fruit Button -->
                <button
                  v-if="currentLevel.level >= 16"
                  @click="harvestAllFruits"
                  class="btn btn-sm btn-warning text-dark rounded-pill px-3.5 py-2 fw-bold shadow-xs d-flex align-items-center gap-1.5"
                  :disabled="fruitsAvailable === 0"
                >
                  <i class="bi bi-basket-fill text-danger"></i>
                  <span>Thu hoạch ({{ fruitsAvailable }} quả • +150 XP/quả)</span>
                </button>
              </div>

              <!-- History View Trigger Button -->
              <div class="d-flex align-items-center gap-2">
                <button @click="openHistoryModal" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fs-8 fw-bold">
                  <i class="bi bi-clock-history me-1"></i> Xem lại hình dáng cũ (30 Cấp)
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: 30 Evolutionary Stages Milestone List -->
        <div class="col-12 col-lg-4">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <div>
                  <h5 class="fw-black text-teal mb-0">30 Chặng Tiến Hóa</h5>
                  <span class="fs-8 text-muted">Hành trình từ hạt mầm đến cổ thụ</span>
                </div>
                <span class="badge bg-teal text-white rounded-pill px-2.5 py-1 fs-8">Cấp {{ currentLevel.level }}/30</span>
              </div>

              <!-- Progress to Next Level -->
              <div class="mb-3">
                <div class="d-flex justify-content-between fs-8 fw-bold mb-1">
                  <span class="text-muted">Cấp {{ currentLevel.level }}</span>
                  <span class="text-teal">{{ levelProgressPercent }}%</span>
                  <span class="text-muted">Cấp {{ Math.min(30, currentLevel.level + 1) }}</span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 999px;">
                  <div class="progress-bar bg-teal" role="progressbar" :style="{ width: levelProgressPercent + '%' }"></div>
                </div>
                <div class="fs-8 text-muted mt-1.5 text-center">
                  Cần thêm <strong>{{ Math.max(0, nextLevelXp - userXp) }} XP</strong> để đạt mốc tiến hóa kế tiếp!
                </div>
              </div>

              <!-- List of 30 Stages (Scrollable) -->
              <div class="d-flex flex-column gap-2 overflow-auto pe-1" style="max-height: 400px;">
                <div
                  v-for="lvl in levels"
                  :key="lvl.level"
                  @click="previewStage(lvl)"
                  class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between transition-all cursor-pointer"
                  :class="{
                    'bg-teal text-white border-teal shadow-xs': lvl.level === displayedLevel.level,
                    'bg-success-subtle border-success-subtle text-dark': lvl.level < currentLevel.level && lvl.level !== displayedLevel.level,
                    'bg-body-tertiary opacity-75': lvl.level > currentLevel.level
                  }"
                  :title="'Bấm để xem hình dáng cấp ' + lvl.level"
                >
                  <div class="d-flex align-items-center gap-2.5">
                    <div
                      class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-8 shrink-0"
                      style="width: 28px; height: 28px;"
                      :class="lvl.level === displayedLevel.level ? 'bg-white text-dark' : (lvl.level <= currentLevel.level ? 'bg-success text-white' : 'bg-secondary text-white')"
                    >
                      {{ lvl.level }}
                    </div>
                    <div>
                      <div class="fw-bold fs-7 leading-tight" :class="lvl.level === displayedLevel.level ? 'text-white' : 'text-dark-emphasis'">
                        {{ lvl.name }}
                      </div>
                      <div class="fs-8" :class="lvl.level === displayedLevel.level ? 'text-white-50' : 'text-muted'">
                        {{ lvl.stageDesc }}
                      </div>
                    </div>
                  </div>

                  <div class="text-end shrink-0">
                    <span v-if="lvl.level === currentLevel.level" class="badge bg-white text-dark rounded-pill fs-8 fw-bold">
                      Hiện tại
                    </span>
                    <span v-else-if="lvl.level < currentLevel.level" class="badge bg-success text-white rounded-pill fs-8">
                      ✓ Đã mở
                    </span>
                    <span v-else class="fs-8 fw-semibold text-muted">
                      {{ lvl.minXp }} XP
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Helpful tips -->
            <div class="alert alert-info border-0 rounded-3 p-3 mt-3 mb-0 fs-8">
              <i class="bi bi-info-circle-fill me-1"></i> <strong>Mẹo chăm sóc cây:</strong> Hoàn thành mỗi bài dịch câu được +15 XP, thi đấu trắc nghiệm tới +30 XP, giúp cây trổ hoa, kết trái vàng trĩu cành!
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useToast } from '@/composables/useToast'
import confetti from 'canvas-confetti'

const authStore = useAuthStore()
const toast = useToast()

const userXp = computed(() => authStore.user?.xp_points || 0)
const userStreak = computed(() => authStore.user?.streak_days || 0)

const hasWateredToday = ref(false)
const hasDugToday = ref(false)
const hasFertilizedToday = ref(false)

const harvestedFruits = ref(Number(localStorage.getItem('ox_harvested_fruits') || 0))
const fruitsAvailable = ref(6)

// Selected preview level (null means follow current actual level)
const previewLevelNum = ref(null)

// 30 Progressive Evolutionary Levels
const levels = [
  { level: 1, minXp: 0, name: 'Hạt Mầm Ngủ Say', stageDesc: 'Hạt giống nằm sâu trong lòng đất mẹ' },
  { level: 2, minXp: 30, name: 'Đào Đất Xới Gốc', stageDesc: 'Đất tơi xốp, hạt giống hấp thụ sương mai' },
  { level: 3, minXp: 70, name: 'Nứt Vỏ Nảy Mầm', stageDesc: 'Rễ non cắm sâu, vỏ hạt tách nở' },
  { level: 4, minXp: 120, name: 'Đâm Chồi Mơn Mởn', stageDesc: 'Chồi non nhú lên khỏi mặt đất' },
  { level: 5, minXp: 180, name: 'Hai Lá Mầm Xanh', stageDesc: 'Hai chiếc lá non xanh biếc đón ánh nắng' },
  { level: 6, minXp: 250, name: 'Cây Con Bén Rễ', stageDesc: 'Thân cây cứng cáp, thêm 4 lá nhỏ' },
  { level: 7, minXp: 330, name: 'Vươn Ngọn Đón Nắng', stageDesc: 'Thân cao thêm 15cm, rễ bám vững' },
  { level: 8, minXp: 420, name: 'Đâm Chồi Nảy Lộc', stageDesc: 'Xuất hiện nhánh con đầu tiên' },
  { level: 9, minXp: 520, name: 'Cành Nhánh Tỏa Rộng', stageDesc: '3 cành lớn vươn về hướng đông' },
  { level: 10, minXp: 630, name: 'Tán Lá Xanh Rì', stageDesc: 'Tán cây che mát một góc vườn' },
  { level: 11, minXp: 750, name: 'Dây Leo Sum Suê', stageDesc: 'Dây leo quấn quanh thân tạo sinh cảnh' },
  { level: 12, minXp: 880, name: 'Đơm Nụ E Ấp', stageDesc: 'Những nụ hồng đầu tiên e ấp trên cành' },
  { level: 13, minXp: 1020, name: 'Hoa Chớm Nở', stageDesc: 'Cánh hoa hé lộ sắc thắm rực rỡ' },
  { level: 14, minXp: 1170, name: 'Vườn Hoa Khoe Sắc', stageDesc: 'Ngàn cánh hoa bung nở ngát hương' },
  { level: 15, minXp: 1330, name: 'Hương Thơm Ngào Ngạt', stageDesc: 'Hương hoa thu hút ong bướm bay về' },
  { level: 16, minXp: 1500, name: 'Ong Bướm Thụ Phấn', stageDesc: 'Cánh hoa rụng để lộ những đài quả non' },
  { level: 17, minXp: 1680, name: 'Kết Trái Non Xanh', stageDesc: 'Những quả xanh tròn trĩnh bắt đầu nhú' },
  { level: 18, minXp: 1870, name: 'Quả Mọng Lớn Dần', stageDesc: 'Trái căng tròn hấp thụ tinh hoa đất trời' },
  { level: 19, minXp: 2070, name: 'Trái Chín Ửng Hồng', stageDesc: 'Vỏ quả chuyển sang màu đỏ tươi rực' },
  { level: 20, minXp: 2280, name: 'Mùa Quả Ngọt Đầu Mùa', stageDesc: 'Những trái đỏ chín mọng có thể hái' },
  { level: 21, minXp: 2500, name: 'Tán Xòe Sum Sê', stageDesc: 'Tán cây rộng gấp đôi, bóng mát ngút ngàn' },
  { level: 22, minXp: 2750, name: 'Rễ Sâu Bám Đá', stageDesc: 'Hệ rễ đan xen vững chắc trước phong ba' },
  { level: 23, minXp: 3050, name: 'Cổ Thụ Trăm Năm', stageDesc: 'Vỏ cây ngả màu thời gian huyền bí' },
  { level: 24, minXp: 3400, name: 'Trĩu Nặng Quả Vàng', stageDesc: 'Xuất hiện những quả vàng kim thần kỳ' },
  { level: 25, minXp: 3800, name: 'Đại Thụ Ngàn Năm', stageDesc: 'Cây cổ thụ hùng vĩ sừng sững giữa trời' },
  { level: 26, minXp: 4300, name: 'Thần Mộc Tỏa Sáng', stageDesc: 'Thân cây phát ra ánh sáng ngọc bích' },
  { level: 27, minXp: 4900, name: 'Hào Quang Vĩnh Cửu', stageDesc: 'Vầng hào quang vũ trụ bao bọc tán lá' },
  { level: 28, minXp: 5600, name: 'Vũ Trụ Sinh Sôi', stageDesc: 'Tán lá hòa cùng các chòm sao lấp lánh' },
  { level: 29, minXp: 6400, name: 'Trí Tuệ Vô Song', stageDesc: 'Đỉnh cao tích lũy tri thức nhân loại' },
  { level: 30, minXp: 7500, name: 'Cây Thần Vĩ Đại Yggdrasil', stageDesc: 'Cây tri thức thần thoại vĩnh hằng' }
]

const currentLevel = computed(() => {
  const xp = userXp.value
  for (let i = levels.length - 1; i >= 0; i--) {
    if (xp >= levels[i].minXp) {
      return levels[i]
    }
  }
  return levels[0]
})

const displayedLevel = computed(() => {
  if (previewLevelNum.value !== null) {
    const found = levels.find(l => l.level === previewLevelNum.value)
    if (found) return found
  }
  return currentLevel.value
})

const isPreviewingPast = computed(() => {
  return previewLevelNum.value !== null && previewLevelNum.value !== currentLevel.value.level
})

const nextLevelXp = computed(() => {
  const lvl = currentLevel.value
  if (lvl.level >= 30) return 7500
  const nextLvl = levels.find(l => l.level === lvl.level + 1)
  return nextLvl ? nextLvl.minXp : 7500
})

const levelProgressPercent = computed(() => {
  const lvl = currentLevel.value
  if (lvl.level >= 30) return 100
  const range = nextLevelXp.value - lvl.minXp
  const current = userXp.value - lvl.minXp
  return Math.min(100, Math.max(0, Math.round((current / range) * 100)))
})

// Dynamic SVG Tree Dimensions & Shapes
const foliageTop = computed(() => {
  const lvl = displayedLevel.value.level
  return Math.max(100, 270 - lvl * 5)
})

const foliageRadius = computed(() => {
  const lvl = displayedLevel.value.level
  return 38 + lvl * 3
})

const foliageSpread = computed(() => {
  const lvl = displayedLevel.value.level
  return lvl * 3.5
})

const trunkPath = computed(() => {
  const lvl = displayedLevel.value.level
  const topY = foliageTop.value + 40
  const widthBottom = 16 + lvl * 1.8
  const widthTop = 8 + lvl * 1.2
  return `M ${250 - widthBottom} 460 L ${250 - widthTop} ${topY} Q 250 ${topY - 10} ${250 + widthTop} ${topY} L ${250 + widthBottom} 460 Z`
})

const foliageColor1 = computed(() => {
  if (displayedLevel.value.level >= 26) return '#10b981'
  if (displayedLevel.value.level >= 18) return '#15803d'
  return '#22c55e'
})

const foliageColor2 = computed(() => {
  if (displayedLevel.value.level >= 26) return '#065f46'
  if (displayedLevel.value.level >= 18) return '#166534'
  return '#16a34a'
})

const foliageColor3 = computed(() => {
  if (displayedLevel.value.level >= 26) return '#047857'
  return '#15803d'
})

// Flower coordinates
const flowerPositions = [
  { x: 210, y: 190, scale: 0.9 },
  { x: 280, y: 180, scale: 1 },
  { x: 180, y: 230, scale: 0.8 },
  { x: 310, y: 220, scale: 0.9 },
  { x: 250, y: 150, scale: 1.1 },
  { x: 240, y: 220, scale: 0.85 }
]

// Fruit coordinates
const fruitPositions = [
  { x: 190, y: 210, type: 'red' },
  { x: 290, y: 200, type: 'golden' },
  { x: 230, y: 260, type: 'red' },
  { x: 270, y: 250, type: 'golden' },
  { x: 160, y: 250, type: 'red' },
  { x: 330, y: 240, type: 'red' }
]

const previewStage = (lvl) => {
  previewLevelNum.value = lvl.level
}

const resetToCurrentLevel = () => {
  previewLevelNum.value = null
}

const openHistoryModal = () => {
  // Toggle through past levels or open selector
  const prevLvl = (displayedLevel.value.level > 1) ? displayedLevel.value.level - 1 : currentLevel.value.level
  previewLevelNum.value = prevLvl
}

const syncTreeActionBackend = async (action, localXpFallback, extraPayload = {}) => {
  if (authStore.isAuthenticated) {
    try {
      const res = await axios.post('/api/user/study-action', { action, ...extraPayload })
      if (authStore.user && res.data?.data?.total_xp !== undefined) {
        authStore.user.xp_points = res.data.data.total_xp
        if (res.data.data.streak_days !== undefined) {
          authStore.user.streak_days = res.data.data.streak_days
        }
      }
      return res.data?.data?.xp_awarded ?? localXpFallback
    } catch (e) {}
  }
  if (authStore.user) {
    authStore.user.xp_points = (authStore.user.xp_points || 0) + localXpFallback
  }
  return localXpFallback
}

const waterTree = async () => {
  if (hasWateredToday.value) return
  hasWateredToday.value = true
  localStorage.setItem('ox_last_watered_date', new Date().toDateString())
  const xp = await syncTreeActionBackend('tree_water', 5)
  toast.success(`Đã tưới nước cho Cây Tri Thức! (+${xp} XP)`, 'Chăm sóc cây')
  confetti({ particleCount: 40, spread: 60, origin: { y: 0.7 } })
}

const digSoil = async () => {
  if (hasDugToday.value) return
  hasDugToday.value = true
  localStorage.setItem('ox_last_dug_date', new Date().toDateString())
  const xp = await syncTreeActionBackend('tree_dig', 10)
  toast.success(`Đã đào đất xới gốc giúp rễ hấp thụ! (+${xp} XP)`, 'Chăm sóc cây')
  confetti({ particleCount: 45, spread: 60, origin: { y: 0.8 } })
}

const fertilizeTree = async () => {
  if (hasFertilizedToday.value) return
  hasFertilizedToday.value = true
  localStorage.setItem('ox_last_fertilized_date', new Date().toDateString())
  const xp = await syncTreeActionBackend('tree_fertilize', 25)
  toast.success(`Đã bón phân hữu cơ cho cây xanh tốt! (+${xp} XP)`, 'Chăm sóc cây')
  confetti({ particleCount: 60, spread: 80, origin: { y: 0.7 } })
}

const harvestSingleFruit = async (idx) => {
  if (fruitsAvailable.value <= 0) return
  harvestedFruits.value++
  localStorage.setItem('ox_harvested_fruits', String(harvestedFruits.value))
  fruitsAvailable.value--
  const xp = await syncTreeActionBackend('tree_harvest', 150, { slot_id: idx + 1 })
  toast.success(`Thu hoạch trái tri thức thành công! (+${xp} XP)`, 'Mùa gặt tri thức')
  confetti({ particleCount: 60, spread: 70, origin: { y: 0.6 } })
}

const harvestAllFruits = async () => {
  if (fruitsAvailable.value === 0) return
  const count = fruitsAvailable.value
  harvestedFruits.value += count
  fruitsAvailable.value = 0
  localStorage.setItem('ox_harvested_fruits', String(harvestedFruits.value))
  const xp = await syncTreeActionBackend('tree_harvest', count * 150, { words_count: count })
  toast.success(`Đã thu hoạch toàn bộ ${count} quả chín! (+${xp} XP)`, 'Mùa gặt bội thu')
  confetti({ particleCount: 120, spread: 100, origin: { y: 0.5 } })
}

onMounted(async () => {
  const todayStr = new Date().toDateString()
  if (localStorage.getItem('ox_last_watered_date') === todayStr) hasWateredToday.value = true
  if (localStorage.getItem('ox_last_dug_date') === todayStr) hasDugToday.value = true
  if (localStorage.getItem('ox_last_fertilized_date') === todayStr) hasFertilizedToday.value = true

  if (authStore.isAuthenticated) {
    try {
      const res = await axios.get('/api/user/tree-state')
      const s = res.data?.data
      if (s) {
        hasWateredToday.value = !!s.watered_today
        hasDugToday.value = !!s.dug_today
        hasFertilizedToday.value = !!s.fertilized_today
        if (Array.isArray(s.harvested_slots)) {
          fruitsAvailable.value = Math.max(0, 6 - s.harvested_slots.length)
        }
      }
    } catch (e) {}
  }
})
</script>

<style scoped>
.sun-glow {
  width: 70px;
  height: 70px;
  background: radial-gradient(circle, #fde047 30%, rgba(253, 224, 71, 0) 70%);
  border-radius: 50%;
  box-shadow: 0 0 40px #facc15;
}

.fruit-item:hover circle {
  transform: scale(1.15);
  transform-origin: center;
  transition: transform 0.2s ease;
}

.backdrop-blur {
  backdrop-filter: blur(8px);
}
</style>
