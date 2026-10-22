<template>
  <div class="container py-4 max-w-6xl">
    <div class="text-center mb-5">
      <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">CHUẨN QUỐC TẾ</span>
      <h2 class="fw-black text-teal mt-2 mb-2">Bảng 44 Âm Tiếng Anh IPA Chuẩn Mỹ</h2>
      <p class="text-muted fs-7 max-w-xl mx-auto">
        Bấm vào bất kỳ âm nào để <strong>nghe phát âm ngay lập tức</strong> và mở hộp thoại trung tâm chi tiết về khẩu hình cùng các từ ví dụ phong phú.
      </p>
    </div>

    <!-- Group 1: 12 Nguyên âm đơn (Monophthongs) -->
    <div class="card p-4 rounded-4 border shadow-sm bg-card-custom mb-4">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">12 ÂM</span>
        <h5 class="fw-bold text-teal mb-0">Nguyên Âm Đơn (Monophthongs)</h5>
      </div>

      <div class="row g-3">
        <div v-for="sound in ipaData.monophthongs" :key="sound.id" class="col-6 col-md-4 col-lg-3">
          <div
            @click="openSoundModal(sound)"
            class="card p-3 rounded-4 border shadow-sm text-center cursor-pointer transition-all hover-card bg-body-tertiary"
          >
            <div class="d-flex justify-content-end">
              <button @click.stop="quickPlaySound(sound)" class="btn btn-sm btn-outline-teal rounded-circle p-1" title="Phát âm ngay">
                <i class="bi bi-volume-up-fill fs-8"></i>
              </button>
            </div>
            <div class="display-6 fw-black text-teal mb-1">{{ sound.symbol }}</div>
            <div class="fs-8 text-muted">
              {{ sound.examples_json?.[0]?.word || '' }} ({{ sound.examples_json?.[0]?.meaning || '' }})
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Group 2: 8 Nguyên âm đôi (Diphthongs) -->
    <div class="card p-4 rounded-4 border shadow-sm bg-card-custom mb-4">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1 fs-8">8 ÂM</span>
        <h5 class="fw-bold text-teal mb-0">Nguyên Âm Đôi (Diphthongs)</h5>
      </div>

      <div class="row g-3">
        <div v-for="sound in ipaData.diphthongs" :key="sound.id" class="col-6 col-md-4 col-lg-3">
          <div
            @click="openSoundModal(sound)"
            class="card p-3 rounded-4 border shadow-sm text-center cursor-pointer transition-all hover-card bg-body-tertiary"
          >
            <div class="d-flex justify-content-end">
              <button @click.stop="quickPlaySound(sound)" class="btn btn-sm btn-outline-warning rounded-circle p-1" title="Phát âm ngay">
                <i class="bi bi-volume-up-fill fs-8"></i>
              </button>
            </div>
            <div class="display-6 fw-black text-warning-emphasis mb-1">{{ sound.symbol }}</div>
            <div class="fs-8 text-muted">
              {{ sound.examples_json?.[0]?.word || '' }} ({{ sound.examples_json?.[0]?.meaning || '' }})
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Group 3: 24 Phụ âm (Consonants) -->
    <div class="card p-4 rounded-4 border shadow-sm bg-card-custom mb-4">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fs-8">24 ÂM</span>
        <h5 class="fw-bold text-teal mb-0">Phụ Âm (Consonants: Hữu Thanh & Vô Thanh)</h5>
      </div>

      <div class="row g-4">
        <!-- Vô thanh -->
        <div class="col-12 col-md-6">
          <div class="fw-semibold text-muted fs-8 mb-2">Vô thanh (Unvoiced - Không rung dây thanh quản):</div>
          <div class="row g-2">
            <div v-for="sound in ipaData.consonants_unvoiced" :key="sound.id" class="col-4 col-sm-3">
              <div
                @click="openSoundModal(sound)"
                class="card p-2 rounded-3 border text-center cursor-pointer transition-all hover-card bg-body-tertiary"
              >
                <div class="fs-4 fw-black text-secondary">{{ sound.symbol }}</div>
                <div class="fs-8 text-muted">{{ sound.examples_json?.[0]?.word || '' }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Hữu thanh -->
        <div class="col-12 col-md-6">
          <div class="fw-semibold text-muted fs-8 mb-2">Hữu thanh (Voiced - Rung dây thanh quản):</div>
          <div class="row g-2">
            <div v-for="sound in ipaData.consonants_voiced" :key="sound.id" class="col-4 col-sm-3">
              <div
                @click="openSoundModal(sound)"
                class="card p-2 rounded-3 border text-center cursor-pointer transition-all hover-card bg-body-tertiary"
              >
                <div class="fs-4 fw-black text-teal">{{ sound.symbol }}</div>
                <div class="fs-8 text-muted">{{ sound.examples_json?.[0]?.word || '' }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CENTERED MODAL POPUP FOR IPA SOUND DETAILS -->
    <div
      v-if="activeSound"
      class="modal fade show d-block"
      style="background: rgba(0, 0, 0, 0.65);"
      tabindex="-1"
      @click.self="closeModal"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-card-custom overflow-hidden">
          <div class="modal-header border-bottom py-3" style="background: linear-gradient(135deg, #064e40 0%, #0d7362 100%); color: #fff;">
            <div class="d-flex align-items-center gap-3">
              <div class="display-5 fw-black text-warning bg-white bg-opacity-20 px-3 py-1 rounded-3">
                {{ activeSound.symbol }}
              </div>
              <div>
                <span class="badge bg-white text-dark me-2 text-uppercase fs-8">{{ activeSound.category }}</span>
                <span v-if="activeSound.voiced_type" class="badge" :class="activeSound.voiced_type === 'voiced' ? 'bg-success text-white' : 'bg-warning text-dark'">
                  {{ activeSound.voiced_type === 'voiced' ? 'Hữu thanh (Voiced)' : 'Vô thanh (Unvoiced)' }}
                </span>
                <h5 class="fw-bold mt-1 mb-0 text-white">Hướng Dẫn Khẩu Hình & Ví Dụ</h5>
              </div>
            </div>
            <button @click="closeModal" type="button" class="btn-close btn-close-white" aria-label="Close"></button>
          </div>

          <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
            <!-- Audio Button for the Sound Itself -->
            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-body-tertiary border mb-4">
              <div>
                <strong class="text-teal fs-6">Phát âm âm {{ activeSound.symbol }}:</strong>
                <div class="text-muted fs-8">Nghe trực tiếp âm qua Web Speech API</div>
              </div>
              <button @click="speech.speak(activeSound.examples_json?.[0]?.word || activeSound.symbol)" class="btn btn-teal btn-sm px-4 rounded-pill shadow-sm">
                <i class="bi bi-volume-up-fill me-1"></i> Nghe phát âm
              </button>
            </div>

            <!-- Mouth Guide -->
            <div class="alert alert-light border rounded-3 p-3 fs-7 mb-4">
              <strong class="text-teal"><i class="bi bi-person-bounding-box me-1"></i> Khẩu hình môi, lưỡi và luồng hơi:</strong>
              <p class="mb-0 mt-2 leading-relaxed text-dark-emphasis">{{ activeSound.mouth_guide_vi }}</p>
            </div>

            <!-- Example Words with Audio & Mic Practice -->
            <h6 class="fw-bold text-teal mb-3"><i class="bi bi-card-list me-1"></i> Danh Sách Từ Ví Dụ Minh Họa:</h6>
            <div class="row g-3">
              <div v-for="(ex, eIdx) in activeSound.examples_json || []" :key="eIdx" class="col-12 col-md-6">
                <div class="card p-3 rounded-3 border bg-body-tertiary h-100 d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <span class="fs-5 fw-black text-teal">{{ ex.word }}</span>
                      <span class="font-monospace text-muted fs-7">{{ ex.phonetic }}</span>
                    </div>
                    <div class="fs-7 text-success fw-bold mb-1">{{ ex.meaning }}</div>
                    <div class="fs-8 text-secondary fst-italic mb-3">"{{ ex.example }}"</div>
                  </div>

                  <div class="d-flex gap-2">
                    <button @click="speech.speak(ex.word)" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-8 flex-grow-1">
                      <i class="bi bi-volume-up-fill"></i> Nghe từ
                    </button>
                    <button
                      @click="speech.startListening(ex.word)"
                      class="btn btn-sm rounded-pill px-3 py-1 fs-8 flex-grow-1"
                      :class="speech.isListening.value ? 'btn-danger animate-mic-pulse' : 'btn-outline-success'"
                    >
                      <i class="bi bi-mic-fill"></i> Luyện nói
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Speech Recognition Evaluation Result -->
            <div v-if="speech.transcript.value" class="alert alert-info mt-4 mb-0 py-2.5 px-3 fs-7 text-center rounded-3">
              Bạn vừa đọc: <strong>"{{ speech.transcript.value }}"</strong> (Độ chính xác: <strong>{{ speech.pronunciationScore.value }}%</strong>
              <span :class="speech.pronunciationFeedback.value === 'excellent' ? 'text-success fw-bold' : speech.pronunciationFeedback.value === 'good' ? 'text-warning fw-bold' : 'text-danger fw-bold'">
                {{ speech.pronunciationFeedback.value === 'excellent' ? '• Rất chuẩn! 🎉' : speech.pronunciationFeedback.value === 'good' ? '• Tương đối tốt 👍' : '• Cố gắng đọc lại' }}
              </span>)
            </div>
          </div>

          <div class="modal-footer border-top py-2">
            <button @click="closeModal" class="btn btn-secondary btn-sm px-4 rounded-pill">
              Đóng hộp thoại
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useSpeech } from '@/composables/useSpeech'

const speech = useSpeech()

const ipaData = ref({
  monophthongs: [],
  diphthongs: [],
  consonants_voiced: [],
  consonants_unvoiced: [],
})

const activeSound = ref(null)

const openSoundModal = (sound) => {
  activeSound.value = sound
  // Speak the example word or sound immediately
  if (sound.examples_json && sound.examples_json.length > 0) {
    speech.speak(sound.examples_json[0].word)
  }
}

const quickPlaySound = (sound) => {
  if (sound.examples_json && sound.examples_json.length > 0) {
    speech.speak(sound.examples_json[0].word)
  }
}

const closeModal = () => {
  activeSound.value = null
  speech.stopSpeech()
  speech.stopListening()
}

onMounted(async () => {
  try {
    const res = await axios.get('/api/ipa-sounds')
    ipaData.value = res.data.data
  } catch (err) {
    console.error('Failed to load IPA sounds:', err)
  }
})
</script>
