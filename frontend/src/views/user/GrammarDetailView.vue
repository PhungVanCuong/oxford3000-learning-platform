<template>
  <div class="grammar-detail-page py-4">
    <div class="container max-w-5xl">
      <!-- Top Quick Navigation & Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb fs-8 mb-0">
          <li class="breadcrumb-item"><router-link to="/" class="text-decoration-none text-muted">Trang chủ</router-link></li>
          <li class="breadcrumb-item"><router-link to="/grammar" class="text-decoration-none text-muted">Ngữ pháp</router-link></li>
          <li class="breadcrumb-item active text-teal fw-bold" aria-current="page">{{ grammar?.title || 'Chi tiết Ngữ pháp' }}</li>
        </ol>
      </nav>

      <!-- Sticky Quick-Jump Bar -->
      <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-4 sticky-top" style="top: 72px; z-index: 1020;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
          <div class="d-flex align-items-center gap-2.5">
            <router-link to="/grammar" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
              <i class="bi bi-arrow-left"></i>
            </router-link>
            <div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge px-2 py-1 rounded-pill fw-bold text-white fs-8" :style="{ backgroundColor: grammar?.badge_color || '#00b074' }">
                  {{ grammar?.level || 'A1-B2' }}
                </span>
                <h5 class="fw-black text-teal mb-0 text-truncate" style="max-width: 360px;">{{ grammar?.title }}</h5>
              </div>
            </div>
          </div>

          <!-- Quick Jump Buttons (Vuốt ngang mượt mà trên điện thoại) -->
          <div class="d-flex flex-nowrap gap-1.5 mobile-scroll-x no-scrollbar py-1">
            <button @click="scrollToSection('theory-section')" class="btn btn-sm btn-outline-teal rounded-pill px-2.5 py-1 fs-8 fw-semibold">
              <i class="bi bi-book-half me-1"></i> Lý thuyết
            </button>
            <button v-if="grammar?.active_passive_html" @click="scrollToSection('passive-section')" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 fs-8 fw-semibold">
              <i class="bi bi-arrow-left-right me-1"></i> Bị động
            </button>
            <button v-if="grammar?.pronunciation_notes" @click="scrollToSection('pronunciation-section')" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-2.5 py-1 fs-8 fw-semibold">
              <i class="bi bi-volume-up-fill me-1"></i> Ngữ âm
            </button>
            <button @click="scrollToSection('translation-section')" class="btn btn-sm btn-teal rounded-pill px-3 py-1 fs-8 fw-bold text-white shadow-sm">
              <i class="bi bi-stars me-1"></i> Dịch câu AI ({{ translationExercises.length }})
            </button>
            <button @click="startQuiz" class="btn btn-sm btn-warning text-dark rounded-pill px-3 py-1 fs-8 fw-bold shadow-sm">
              <i class="bi bi-patch-question-fill me-1"></i> Làm trắc nghiệm
            </button>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-teal" role="status"></div>
        <div class="text-muted fs-7 mt-2">Đang tải toàn bộ dữ liệu ngữ pháp chi tiết...</div>
      </div>

      <div v-else-if="grammar" class="d-flex flex-column gap-4">
        <!-- 1. HERO OVERVIEW CARD -->
        <div class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom position-relative overflow-hidden">
          <div class="position-absolute top-0 end-0 p-4 opacity-10 d-none d-md-block pointer-events-none">
            <i class="bi" :class="grammar.icon || 'bi-journal-code'" style="font-size: 140px; color: #00b074;"></i>
          </div>
          <div class="position-relative z-1 max-w-2xl">
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="badge rounded-pill px-3 py-1 fw-bold text-white fs-8" :style="{ backgroundColor: grammar.badge_color || '#00b074' }">
                {{ grammar.tense_group || 'Ngữ pháp thực chiến' }}
              </span>
              <span v-if="grammar.topic_name" class="badge bg-secondary-subtle text-secondary border rounded-pill px-2.5 py-1 fs-8">
                {{ grammar.topic_name }}
              </span>
            </div>
            <h2 class="fw-black text-teal mb-3 display-6">{{ grammar.title }}</h2>
            <p class="text-muted fs-6 mb-4 leading-relaxed">{{ grammar.summary || grammar.description }}</p>

            <div class="d-flex flex-wrap gap-3 align-items-center">
              <div class="p-2.5 px-3 rounded-3 bg-body-tertiary border d-flex align-items-center gap-2">
                <i class="bi bi-translate text-teal fs-5"></i>
                <div>
                  <div class="fs-8 text-muted">Luyện dịch câu</div>
                  <div class="fw-bold text-dark-emphasis fs-7">{{ completedCount }}/{{ translationExercises.length }} câu hoàn thành</div>
                </div>
              </div>
              <div class="p-2.5 px-3 rounded-3 bg-body-tertiary border d-flex align-items-center gap-2">
                <i class="bi bi-trophy-fill text-warning fs-5"></i>
                <div>
                  <div class="fs-8 text-muted">Điểm thưởng tối đa</div>
                  <div class="fw-bold text-dark-emphasis fs-7">+{{ translationExercises.length * 15 }} XP</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 2. SECTION: THEORY & FORMULAS (PDF COMPREHENSIVE) -->
        <section id="theory-section" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom">
          <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-8 mb-1">MỤC 01</span>
              <h4 class="fw-black text-teal mb-0">
                <i class="bi bi-book-fill me-2"></i> Lý thuyết & Công thức chi tiết (Theo tài liệu PDF)
              </h4>
            </div>
          </div>

          <!-- Direct Theory HTML from Seeder (Rendered with styles) -->
          <div class="theory-html-content" v-html="grammar.theory_html"></div>

          <!-- Formula Boxes (Positive, Negative, Question) -->
          <div class="row g-3 mt-3">
            <div class="col-12 col-md-4">
              <div class="p-3.5 rounded-4 border h-100" style="background-color: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.3) !important;">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge bg-success rounded-circle p-1.5"><i class="bi bi-plus-lg text-white"></i></span>
                  <span class="fw-black text-success fs-7 text-uppercase">Khẳng định (+)</span>
                </div>
                <code class="d-block fs-7 p-2 rounded-3 bg-white text-dark fw-bold border mb-2">{{ grammar.formula_positive || 'S + V...' }}</code>
                <div class="fs-8 text-muted">Chủ ngữ phù hợp với trợ động từ và chia thì chuẩn.</div>
              </div>
            </div>

            <div class="col-12 col-md-4">
              <div class="p-3.5 rounded-4 border h-100" style="background-color: rgba(239, 68, 68, 0.08); border-color: rgba(239, 68, 68, 0.3) !important;">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge bg-danger rounded-circle p-1.5"><i class="bi bi-dash text-white"></i></span>
                  <span class="fw-black text-danger fs-7 text-uppercase">Phủ định (-)</span>
                </div>
                <code class="d-block fs-7 p-2 rounded-3 bg-white text-dark fw-bold border mb-2">{{ grammar.formula_negative || 'S + not + V...' }}</code>
                <div class="fs-8 text-muted">Thêm not sau trợ động từ hoặc động từ To Be.</div>
              </div>
            </div>

            <div class="col-12 col-md-4">
              <div class="p-3.5 rounded-4 border h-100" style="background-color: rgba(14, 165, 233, 0.08); border-color: rgba(14, 165, 233, 0.3) !important;">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge bg-info rounded-circle p-1.5"><i class="bi bi-question text-white"></i></span>
                  <span class="fw-black text-info-emphasis fs-7 text-uppercase">Nghi vấn (?)</span>
                </div>
                <code class="d-block fs-7 p-2 rounded-3 bg-white text-dark fw-bold border mb-2">{{ grammar.formula_question || 'Aux + S + V...?' }}</code>
                <div class="fs-8 text-muted">Đảo trợ động từ lên đầu câu đứng trước chủ ngữ.</div>
              </div>
            </div>
          </div>
        </section>

        <!-- 3. SECTION: ACTIVE & PASSIVE VOICE (PDF SPECIAL SECTION) -->
        <section v-if="grammar.active_passive_html" id="passive-section" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom">
          <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
              <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle fs-8 mb-1">MỤC 02</span>
              <h4 class="fw-black text-teal mb-0">
                <i class="bi bi-arrow-left-right me-2"></i> Chuyển đổi Chủ động → Bị động (Active & Passive Voice)
              </h4>
            </div>
          </div>
          <div class="passive-html-content" v-html="grammar.active_passive_html"></div>
        </section>

        <!-- 4. SECTION: PRONUNCIATION & PHONETICS (PDF SPECIAL SECTION) -->
        <section v-if="grammar.pronunciation_notes" id="pronunciation-section" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom">
          <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
              <span class="badge bg-warning-subtle text-dark border border-warning-subtle fs-8 mb-1">MỤC 03</span>
              <h4 class="fw-black text-teal mb-0">
                <i class="bi bi-volume-up-fill me-2"></i> Quy tắc Ngữ âm & Phát âm đuôi (Phonetics & Pronunciation)
              </h4>
            </div>
          </div>
          <div class="pronunciation-html-content" v-html="grammar.pronunciation_notes"></div>
        </section>

        <!-- 5. SECTION: BILINGUAL EXAMPLES & AUDIO SPEECH -->
        <section v-if="grammar.examples && grammar.examples.length" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom">
          <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
              <span class="badge bg-success-subtle text-success border border-success-subtle fs-8 mb-1">MỤC 04</span>
              <h4 class="fw-black text-teal mb-0">
                <i class="bi bi-soundwave me-2"></i> Ví dụ thực tế & Luyện đọc lại Microphone
              </h4>
            </div>
          </div>

          <div class="row g-3">
            <div v-for="(ex, exIdx) in grammar.examples" :key="exIdx" class="col-12 col-md-6">
              <div class="p-3.5 rounded-4 border bg-body-tertiary h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="fs-6 fw-bold text-teal mb-1">{{ ex.en }}</div>
                  <div class="fs-7 text-muted">{{ ex.vi }}</div>
                </div>
                <div class="d-flex gap-2 mt-3 pt-2 border-top">
                  <button @click="speech.speak(ex.en)" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-8">
                    <i class="bi bi-volume-up-fill me-1"></i> Nghe câu
                  </button>
                  <button
                    @click="speech.startListening(ex.en, 'grammar_ex_' + exIdx)"
                    class="btn btn-sm rounded-pill px-3 py-1 fs-8"
                    :class="speech.listeningId.value === ('grammar_ex_' + exIdx) ? 'btn-danger animate-pulse' : 'btn-outline-success'"
                  >
                    <i class="bi" :class="speech.listeningId.value === ('grammar_ex_' + exIdx) ? 'bi-record-fill' : 'bi-mic-fill'"></i>
                    <span class="ms-1">{{ speech.listeningId.value === ('grammar_ex_' + exIdx) ? 'Đang nghe...' : 'Đọc lại' }}</span>
                  </button>
                </div>

                <!-- Per-item mic result -->
                <div v-if="speech.itemResults.value['grammar_ex_' + exIdx]" class="mt-2 p-2 rounded-3 bg-white border fs-8">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted">Bạn đã đọc:</span>
                    <span class="badge" :class="speech.itemResults.value['grammar_ex_' + exIdx].score >= 80 ? 'bg-success' : speech.itemResults.value['grammar_ex_' + exIdx].score >= 55 ? 'bg-warning text-dark' : 'bg-danger'">
                      {{ speech.itemResults.value['grammar_ex_' + exIdx].score }}%
                    </span>
                  </div>
                  <div class="fw-semibold text-dark-emphasis">"{{ speech.itemResults.value['grammar_ex_' + exIdx].transcript }}"</div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- 6. SECTION: AI-POWERED TRANSLATION & ALL PDF EXERCISES -->
        <section id="translation-section" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom">
          <!-- Exercise Phase Header -->
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 border-bottom pb-3">
            <div>
              <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <span class="badge bg-teal text-white rounded-pill px-2.5 py-1 fs-8">TẤT CẢ DẠNG BÀI TẬP TRONG PDF</span>
                <span class="badge bg-warning-subtle text-dark border rounded-pill px-2.5 py-1 fs-8">AI CHẤM ĐIỂM TỰ ĐỘNG</span>
                <span v-if="isAllCompleted" class="badge bg-success text-white rounded-pill px-2.5 py-1 fs-8 fw-bold">
                  <i class="bi bi-check-circle-fill me-1"></i> ✓ ĐÃ XONG TOÀN BỘ
                </span>
              </div>
              <h4 class="fw-black text-teal mb-0">
                <i class="bi bi-stars text-warning me-2"></i> Luyện Tập & Chấm Điểm Thực Chiến (49 Trang PDF)
              </h4>
            </div>
            <div class="text-end d-flex flex-column align-items-md-end gap-1">
              <div class="d-flex align-items-center gap-2">
                <span class="fs-7 fw-bold text-muted">Tiến độ bài làm:</span>
                <span class="badge bg-teal text-white fs-7 px-3 py-1.5 rounded-pill shadow-xs">
                  {{ completedCount }}/{{ totalExercisesCount }} câu đạt chuẩn ({{ progressPercent }}%)
                </span>
              </div>
              <div class="d-flex align-items-center gap-2 mt-1">
                <button
                  v-if="completedCount > 0"
                  @click="resetAllExercises"
                  class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fs-8 fw-semibold"
                  title="Xóa kết quả để làm lại từ đầu"
                >
                  <i class="bi bi-arrow-counterclockwise me-1"></i> Làm lại bài này
                </button>
              </div>
            </div>
          </div>

          <!-- Overall Progress Bar Banner -->
          <div class="p-3 rounded-4 mb-4" :class="isAllCompleted ? 'bg-success-subtle border border-success' : 'bg-body-tertiary border'">
            <div class="d-flex justify-content-between align-items-center mb-1.5 fs-7">
              <span class="fw-bold d-flex align-items-center gap-1.5">
                <i :class="isAllCompleted ? 'bi bi-check-circle-fill text-success' : 'bi bi-award-fill text-teal'"></i>
                <span>{{ isAllCompleted ? 'Chúc mừng! Bạn đã hoàn thành 100% tất cả bài tập thì này.' : 'Tiến độ hoàn thành các câu bài tập:' }}</span>
              </span>
              <span class="fw-black" :class="isAllCompleted ? 'text-success' : 'text-teal'">{{ completedCount }} / {{ totalExercisesCount }} câu ({{ progressPercent }}%)</span>
            </div>
            <div class="progress" style="height: 8px; border-radius: 999px;">
              <div
                class="progress-bar rounded-pill"
                role="progressbar"
                :class="isAllCompleted ? 'bg-success' : 'bg-teal'"
                :style="{ width: `${progressPercent}%` }"
              ></div>
            </div>
          </div>

          <!-- Segmented Tab Navigation for Exercise Types -->
          <div class="d-flex gap-2 flex-wrap mb-4 p-1.5 rounded-4 bg-body-tertiary border">
            <button
              @click="activeExerciseTab = 'translations'"
              class="btn btn-sm rounded-pill px-3 py-2 fw-bold fs-7 transition-all flex-grow-1"
              :class="activeExerciseTab === 'translations' ? 'btn-teal text-white shadow-xs' : 'btn-link text-muted text-decoration-none'"
            >
              <i class="bi bi-translate me-1.5"></i>
              <span>1. Luyện Dịch Câu Việt → Anh ({{ translationExercises.length }})</span>
            </button>

            <button
              v-if="verbConjugationExercises.length"
              @click="activeExerciseTab = 'verb_conjugations'"
              class="btn btn-sm rounded-pill px-3 py-2 fw-bold fs-7 transition-all flex-grow-1"
              :class="activeExerciseTab === 'verb_conjugations' ? 'btn-teal text-white shadow-xs' : 'btn-link text-muted text-decoration-none'"
            >
              <i class="bi bi-pencil-fill me-1.5"></i>
              <span>2. Chia Động Từ Trong Ngoặc ({{ verbConjugationExercises.length }})</span>
            </button>

            <button
              v-if="sentenceBuildingExercises.length"
              @click="activeExerciseTab = 'sentence_buildings'"
              class="btn btn-sm rounded-pill px-3 py-2 fw-bold fs-7 transition-all flex-grow-1"
              :class="activeExerciseTab === 'sentence_buildings' ? 'btn-teal text-white shadow-xs' : 'btn-link text-muted text-decoration-none'"
            >
              <i class="bi bi-puzzle-fill me-1.5"></i>
              <span>3. Hoàn Thành Câu Từ Từ Gợi Ý ({{ sentenceBuildingExercises.length }})</span>
            </button>

            <button
              v-if="passiveConversionsExercises.length"
              @click="activeExerciseTab = 'passive_conversions'"
              class="btn btn-sm rounded-pill px-3 py-2 fw-bold fs-7 transition-all flex-grow-1"
              :class="activeExerciseTab === 'passive_conversions' ? 'btn-teal text-white shadow-xs' : 'btn-link text-muted text-decoration-none'"
            >
              <i class="bi bi-arrow-left-right me-1.5"></i>
              <span>4. Chuyển Đổi Câu Bị Động (Passive Voice - {{ passiveConversionsExercises.length }} Câu)</span>
            </button>
          </div>

          <!-- TAB 1: TRANSLATIONS (VIETNAMESE -> ENGLISH) -->
          <div v-if="activeExerciseTab === 'translations'">
            <!-- Description notice -->
            <div class="alert alert-success bg-opacity-10 border-success-subtle p-3 rounded-4 mb-4 fs-7">
              <div class="d-flex gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5 shrink-0"></i>
                <div>
                  <strong>Quy định chấm điểm thông minh:</strong> Bạn chỉ cần dịch đúng cấu trúc và đúng nghĩa từ <strong>80% trở lên</strong> là hệ thống tự động cộng <strong>+15 XP</strong> vào hồ sơ. Nhấn phím <kbd class="px-1.5 py-0.5 bg-dark text-white rounded">Enter</kbd> để chấm điểm tức thì!
                </div>
              </div>
            </div>

            <!-- List of Translation Cards -->
            <div class="d-flex flex-column gap-3.5">
              <div
                v-for="(item, idx) in translationExercises"
                :key="item.id || idx"
                class="card p-3.5 p-md-4 rounded-4 border bg-body-tertiary transition-all"
                :class="{
                  'border-success shadow-xs bg-success-subtle bg-opacity-10': results[idx]?.passed,
                  'border-warning': results[idx] && !results[idx]?.passed
                }"
              >
                <!-- Question Header -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill px-2.5 py-1 fw-bold fs-8" :class="results[idx]?.passed ? 'bg-success text-white' : 'bg-secondary text-white'">
                      Câu {{ String(idx + 1).padStart(2, '0') }}
                    </span>
                    <span class="fs-8 text-muted">{{ grammar.title }}</span>
                  </div>

                  <!-- Status Badge -->
                  <div v-if="results[idx]">
                    <span v-if="results[idx].passed" class="badge bg-success text-white rounded-pill px-3 py-1.5 fs-8 shadow-xs fw-bold">
                      <i class="bi bi-check-circle-fill me-1"></i> ✓ ĐÃ XONG ({{ results[idx].similarity_score }}%)
                    </span>
                    <span v-else class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fs-8">
                      <i class="bi bi-exclamation-triangle me-1"></i> Chưa đạt ({{ results[idx].similarity_score }}%)
                    </span>
                  </div>
                </div>

                <!-- Vietnamese Prompt (Big & Bold) -->
                <div class="fs-6 fw-bold text-dark-emphasis mb-3">
                  <span class="text-teal me-1">🇻🇳</span> {{ item.vi || item.active }}
                </div>

                <!-- Input Area (Sleek pill input with Enter shortcut) -->
                <div class="mb-2">
                  <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted rounded-start-pill ps-3"><i class="bi bi-pencil-square"></i></span>
                    <input
                      type="text"
                      v-model="userAnswers[idx]"
                      @keydown.enter.prevent="checkAnswer(idx, item)"
                      class="form-control form-control-lg fs-7 border-start-0 border-end-0"
                      :placeholder="'Gõ câu dịch tiếng Anh... (VD: ' + getPlaceholder(item) + ')'"
                      :disabled="loadingCheck[idx]"
                    />
                    <button
                      @click="checkAnswer(idx, item)"
                      class="btn btn-teal px-4 fw-bold text-white fs-7 rounded-end-pill d-flex align-items-center gap-1.5 shadow-sm"
                      :disabled="loadingCheck[idx] || !userAnswers[idx]?.trim()"
                    >
                      <span v-if="loadingCheck[idx]" class="spinner-border spinner-border-sm" role="status"></span>
                      <i v-else class="bi bi-stars"></i>
                      <span>{{ loadingCheck[idx] ? 'AI đang chấm...' : 'Chấm điểm' }}</span>
                    </button>
                  </div>
                </div>

                <!-- AI Feedback Card (When Checked) -->
                <div v-if="results[idx]" class="p-3.5 rounded-4 mt-2 transition-all shadow-xs" :class="results[idx].passed ? 'bg-white border border-success' : 'bg-white border border-warning'">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge rounded-pill px-2.5 py-1 fs-8 fw-bold" :class="results[idx].passed ? 'bg-success text-white' : 'bg-warning text-dark'">
                        {{ results[idx].passed ? '🎉 CHÍNH XÁC (ĐẠT CHUẨN)' : '💡 GÓP Ý HOÀN THIỆN' }}
                      </span>
                      <span class="fs-8 text-muted">Độ tương đồng: <strong>{{ results[idx].similarity_score }}%</strong></span>
                    </div>
                    <!-- Audio Listen to standard English -->
                    <button @click="speech.speak(results[idx].ai_result?.corrected_answer || item.expected)" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-8">
                      <i class="bi bi-volume-up-fill me-1"></i> Nghe câu chuẩn
                    </button>
                  </div>

                  <!-- Standard Expected Answer -->
                  <div class="fs-7 text-dark-emphasis mb-2">
                    <span class="fw-bold text-success"><i class="bi bi-check-lg me-1"></i> Đáp án chuẩn:</span>
                    <span class="fw-semibold fst-italic ms-1 text-teal">{{ results[idx].ai_result?.corrected_answer || item.expected }}</span>
                  </div>

                  <!-- AI Simple Friendly Explanation -->
                  <div class="fs-8 text-muted p-2.5 rounded-3 bg-light border">
                    <strong class="text-teal"><i class="bi bi-chat-quote-fill me-1"></i> Lời giải thích:</strong>
                    {{ results[idx].ai_result?.simple_explanation || 'Bạn đã vận dụng rất tốt cấu trúc ngữ pháp này!' }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 2: VERB CONJUGATIONS (CHIA ĐỘNG TỪ TRONG NGOẶC) -->
          <div v-else-if="activeExerciseTab === 'verb_conjugations'">
            <div class="alert alert-info bg-opacity-10 border-info-subtle p-3 rounded-4 mb-4 fs-7">
              <i class="bi bi-info-circle-fill text-info me-1"></i>
              <strong>Dạng bài chia động từ trong ngoặc:</strong> Điền dạng đúng của động từ để hoàn thiện câu theo đúng thì ngữ pháp. Nhấn <kbd class="px-1.5 py-0.5 bg-dark text-white rounded">Enter</kbd> để kiểm tra!
            </div>

            <div class="d-flex flex-column gap-3">
              <div
                v-for="(vItem, vIdx) in verbConjugationExercises"
                :key="vItem.id || vIdx"
                class="card p-3.5 p-md-4 rounded-4 border bg-body-tertiary"
                :class="{
                  'border-success bg-success-subtle bg-opacity-10': verbResults[vIdx]?.passed,
                  'border-danger bg-danger-subtle bg-opacity-10': verbResults[vIdx] && !verbResults[vIdx]?.passed
                }"
              >
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge bg-secondary text-white rounded-pill px-2.5 py-1 fs-8">
                    Câu {{ String(vIdx + 1).padStart(2, '0') }}
                  </span>
                  <div v-if="verbResults[vIdx]">
                    <span v-if="verbResults[vIdx].passed" class="badge bg-success text-white rounded-pill px-3 py-1 fs-8 fw-bold shadow-xs">
                      <i class="bi bi-check-circle-fill me-1"></i> ✓ ĐÃ XONG (+10 XP)
                    </span>
                    <span v-else class="badge bg-danger text-white rounded-pill px-3 py-1 fs-8">
                      ✗ Chưa đúng
                    </span>
                  </div>
                </div>

                <div class="fs-6 fw-bold text-dark-emphasis mb-3">
                  {{ vItem.prompt }}
                </div>

                <div class="input-group mb-2">
                  <input
                    type="text"
                    v-model="verbAnswers[vIdx]"
                    @keydown.enter.prevent="checkVerbConjugation(vIdx, vItem)"
                    class="form-control rounded-start-pill ps-3"
                    :placeholder="'Nhập động từ đã chia cho từ (' + vItem.verb + ')...'"
                  />
                  <button
                    @click="checkVerbConjugation(vIdx, vItem)"
                    class="btn btn-teal px-4 fw-bold text-white rounded-end-pill"
                    :disabled="!verbAnswers[vIdx]?.trim()"
                  >
                    Kiểm tra
                  </button>
                </div>

                <div v-if="verbResults[vIdx]" class="p-3 rounded-3 bg-white border mt-2">
                  <div class="fs-7 mb-1">
                    <span class="fw-bold text-success">Đáp án:</span>
                    <strong class="text-teal ms-1">{{ vItem.expected }}</strong>
                  </div>
                  <div class="fs-8 text-muted">
                    <i class="bi bi-lightbulb-fill text-warning me-1"></i> {{ vItem.explain }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 3: SENTENCE BUILDINGS (HOÀN THÀNH CÂU VỚI TỪ CHO SẴN) -->
          <div v-else-if="activeExerciseTab === 'sentence_buildings'">
            <div class="alert alert-warning bg-opacity-10 border-warning-subtle p-3 rounded-4 mb-4 fs-7">
              <i class="bi bi-lightbulb-fill text-warning me-1"></i>
              <strong>Dạng bài viết lại câu từ từ gợi ý:</strong> Dựa vào các từ cho sẵn, viết thành câu hoàn chỉnh đúng trật tự từ và dạng thì. Nhấn <kbd class="px-1.5 py-0.5 bg-dark text-white rounded">Enter</kbd> để kiểm tra!
            </div>

            <div class="d-flex flex-column gap-3">
              <div
                v-for="(bItem, bIdx) in sentenceBuildingExercises"
                :key="bItem.id || bIdx"
                class="card p-3.5 p-md-4 rounded-4 border bg-body-tertiary"
                :class="{
                  'border-success bg-success-subtle bg-opacity-10': buildResults[bIdx]?.passed,
                  'border-danger bg-danger-subtle bg-opacity-10': buildResults[bIdx] && !buildResults[bIdx]?.passed
                }"
              >
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge bg-secondary text-white rounded-pill px-2.5 py-1 fs-8">
                    Câu {{ String(bIdx + 1).padStart(2, '0') }}
                  </span>
                  <div v-if="buildResults[bIdx]">
                    <span v-if="buildResults[bIdx].passed" class="badge bg-success text-white rounded-pill px-3 py-1 fs-8 fw-bold shadow-xs">
                      <i class="bi bi-check-circle-fill me-1"></i> ✓ ĐÃ XONG (+15 XP)
                    </span>
                    <span v-else class="badge bg-danger text-white rounded-pill px-3 py-1 fs-8">
                      ✗ Cần sửa lại
                    </span>
                  </div>
                </div>

                <div class="fs-6 fw-bold text-dark-emphasis mb-3">
                  <i class="bi bi-puzzle text-teal me-1"></i> {{ bItem.prompt }}
                </div>

                <div class="input-group mb-2">
                  <input
                    type="text"
                    v-model="buildAnswers[bIdx]"
                    @keydown.enter.prevent="checkSentenceBuilding(bIdx, bItem)"
                    class="form-control rounded-start-pill ps-3"
                    placeholder="Gõ lại câu hoàn chỉnh..."
                  />
                  <button
                    @click="checkSentenceBuilding(bIdx, bItem)"
                    class="btn btn-teal px-4 fw-bold text-white rounded-end-pill"
                    :disabled="!buildAnswers[bIdx]?.trim()"
                  >
                    Kiểm tra
                  </button>
                </div>

                <div v-if="buildResults[bIdx]" class="p-3 rounded-3 bg-white border mt-2">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="fs-7">
                      <span class="fw-bold text-success">Đáp án chuẩn:</span>
                      <strong class="text-teal ms-1">{{ bItem.expected }}</strong>
                    </div>
                    <button @click="speech.speak(bItem.expected)" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0.5 fs-8">
                      <i class="bi bi-volume-up-fill me-1"></i> Nghe
                    </button>
                  </div>
                  <div class="fs-8 text-muted">
                    <i class="bi bi-chat-left-dots-fill text-teal me-1"></i> {{ bItem.explain }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 4: PASSIVE CONVERSIONS (CHUYỂN SANG THỂ BỊ ĐỘNG) -->
          <div v-else-if="activeExerciseTab === 'passive_conversions'">
            <div class="alert alert-primary bg-opacity-10 border-primary-subtle p-3.5 rounded-4 mb-4 fs-7">
              <div class="d-flex gap-2">
                <i class="bi bi-arrow-left-right text-primary fs-5 shrink-0"></i>
                <div>
                  <strong>Dạng bài tập chuyển đổi sang thể bị động (Trang 3 PDF):</strong> Đọc câu chủ động ban đầu và viết lại câu sang đúng cấu trúc thể bị động (S + be + V3/ed + by O). Chấm điểm thông minh hoặc đạt từ <strong>80%</strong> là nhận ngay <strong>+15 XP</strong>! Nhấn <kbd class="px-1.5 py-0.5 bg-dark text-white rounded">Enter</kbd> để kiểm tra tức thì!
                </div>
              </div>
            </div>

            <div class="d-flex flex-column gap-3.5">
              <div
                v-for="(pItem, pIdx) in passiveConversionsExercises"
                :key="pItem.id || pIdx"
                class="card p-3.5 p-md-4 rounded-4 border bg-body-tertiary transition-all"
                :class="{
                  'border-success shadow-xs bg-success-subtle bg-opacity-10': passiveResults[pIdx]?.passed,
                  'border-warning': passiveResults[pIdx] && !passiveResults[pIdx]?.passed
                }"
              >
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill px-2.5 py-1 fw-bold fs-8" :class="passiveResults[pIdx]?.passed ? 'bg-success text-white' : 'bg-secondary text-white'">
                      Câu {{ String(pIdx + 1).padStart(2, '0') }}
                    </span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fs-8">Active → Passive</span>
                  </div>

                  <div v-if="passiveResults[pIdx]">
                    <span v-if="passiveResults[pIdx].passed" class="badge bg-success text-white rounded-pill px-3 py-1.5 fs-8 shadow-xs fw-bold">
                      <i class="bi bi-check-circle-fill me-1"></i> ✓ ĐÃ XONG ({{ passiveResults[pIdx].similarity_score }}%)
                    </span>
                    <span v-else class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fs-8">
                      <i class="bi bi-exclamation-triangle me-1"></i> Chưa đạt ({{ passiveResults[pIdx].similarity_score }}%)
                    </span>
                  </div>
                </div>

                <!-- Active sentence -->
                <div class="p-3 rounded-3 bg-white border mb-3">
                  <div class="text-muted fs-8 fw-bold text-uppercase mb-1">🔤 Câu chủ động ban đầu:</div>
                  <div class="fs-6 fw-bold text-teal">"{{ pItem.active }}"</div>
                </div>

                <!-- Input area -->
                <div class="mb-2">
                  <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted rounded-start-pill ps-3"><i class="bi bi-pencil-square"></i></span>
                    <input
                      type="text"
                      v-model="passiveAnswers[pIdx]"
                      @keydown.enter.prevent="checkPassiveConversion(pIdx, pItem)"
                      class="form-control form-control-lg fs-7 border-start-0 border-end-0"
                      :placeholder="'Chuyển sang bị động: ' + (pItem.expected ? pItem.expected.split(' ').slice(0, 3).join(' ') + '...' : '')"
                      :disabled="loadingPassiveCheck[pIdx]"
                    />
                    <button
                      @click="checkPassiveConversion(pIdx, pItem)"
                      class="btn btn-teal px-4 fw-bold text-white fs-7 rounded-end-pill d-flex align-items-center gap-1.5 shadow-sm"
                      :disabled="loadingPassiveCheck[pIdx] || !passiveAnswers[pIdx]?.trim()"
                    >
                      <span v-if="loadingPassiveCheck[pIdx]" class="spinner-border spinner-border-sm" role="status"></span>
                      <i v-else class="bi bi-stars"></i>
                      <span>{{ loadingPassiveCheck[pIdx] ? 'AI đang chấm...' : 'Chấm điểm' }}</span>
                    </button>
                  </div>
                </div>

                <!-- Feedback -->
                <div v-if="passiveResults[pIdx]" class="p-3.5 rounded-4 mt-2 transition-all shadow-xs" :class="passiveResults[pIdx].passed ? 'bg-white border border-success' : 'bg-white border border-warning'">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge rounded-pill px-2.5 py-1 fs-8 fw-bold" :class="passiveResults[pIdx].passed ? 'bg-success text-white' : 'bg-warning text-dark'">
                        {{ passiveResults[pIdx].passed ? '🎉 CHÍNH XÁC (ĐẠT CHUẨN +15 XP)' : '💡 GÓP Ý HOÀN THIỆN' }}
                      </span>
                      <span class="fs-8 text-muted">Độ tương đồng: <strong>{{ passiveResults[pIdx].similarity_score }}%</strong></span>
                    </div>
                    <button @click="speech.speak(passiveResults[pIdx].ai_result?.corrected_answer || pItem.expected)" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-8">
                      <i class="bi bi-volume-up-fill me-1"></i> Nghe câu bị động
                    </button>
                  </div>

                  <div class="fs-7 text-dark-emphasis mb-2">
                    <span class="fw-bold text-success"><i class="bi bi-check-lg me-1"></i> Cấu trúc bị động chuẩn:</span>
                    <span class="fw-semibold fst-italic ms-1 text-teal">{{ passiveResults[pIdx].ai_result?.corrected_answer || pItem.expected }}</span>
                  </div>

                  <div class="fs-8 text-muted p-2.5 rounded-3 bg-light border">
                    <strong class="text-teal"><i class="bi bi-chat-quote-fill me-1"></i> Hướng dẫn cấu trúc:</strong>
                    {{ passiveResults[pIdx].ai_result?.simple_explanation || 'Bạn đã chuyển đổi câu chủ động sang bị động rất chính xác!' }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- 7. SECTION: PRACTICE QUIZ (MULTIPLE CHOICE) -->
        <section id="quiz-section" class="card p-4 p-md-5 rounded-4 shadow-sm border bg-card-custom text-center">
          <div class="max-w-lg mx-auto">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3 shadow" style="width: 64px; height: 64px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%);">
              <i class="bi bi-patch-question-fill fs-3"></i>
            </div>
            <h4 class="fw-black text-teal mb-2">Làm Trắc Nghiệm Củng Cố Kiến Thức</h4>
            <p class="text-muted fs-7 mb-4">
              Làm 10 câu hỏi trắc nghiệm phản xạ nhanh với đồng hồ bấm giờ để kiểm tra độ hiểu sâu về các dấu hiệu nhận biết và cách chia động từ của thì này.
            </p>
            <button @click="startQuiz" class="btn btn-teal btn-lg rounded-pill px-5 py-2.5 fw-bold text-white shadow">
              <i class="bi bi-play-circle-fill me-2"></i> Bắt đầu Trắc nghiệm Ngữ pháp
            </button>
          </div>
        </section>
      </div>
    </div>

    <!-- DailyQuizModal for multiple-choice quiz -->
    <DailyQuizModal
      :quizData="grammarQuizData"
      @quiz-completed="handleGrammarQuizCompleted"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useSpeech } from '@/composables/useSpeech'
import { useAuthStore } from '@/stores/authStore'
import DailyQuizModal from '@/components/DailyQuizModal.vue'
import * as bootstrap from 'bootstrap'

const route = useRoute()
const speech = useSpeech()
const authStore = useAuthStore()

const isLoading = ref(true)
const grammar = ref(null)
const grammarQuizData = ref(null)
const activeExerciseTab = ref('translations')

// User translation states
const userAnswers = ref({})
const loadingCheck = ref({})
const results = ref({})

// Verb conjugation states
const verbAnswers = ref({})
const verbResults = ref({})

// Sentence building states
const buildAnswers = ref({})
const buildResults = ref({})

// Passive voice conversion states (Page 3 PDF)
const passiveAnswers = ref({})
const loadingPassiveCheck = ref({})
const passiveResults = ref({})

const defaultPassive10 = [
  { id: 1, active: 'My father waters this flower every morning.', expected: 'This flower is watered by my father every morning' },
  { id: 2, active: 'John invited Fiona to his birthday party last night.', expected: 'Fiona was invited to John\'s birthday party last night' },
  { id: 3, active: 'Her mother is preparing the dinner in the kitchen.', expected: 'The dinner is being prepared in the kitchen by her mother' },
  { id: 4, active: 'We should clean our teeth twice a day.', expected: 'Our teeth should be cleaned twice a day' },
  { id: 5, active: 'Our teachers have explained the English grammar.', expected: 'The English grammar has been explained by our teachers' },
  { id: 6, active: 'Some drunk drivers caused the accident in this city.', expected: 'The accident was caused in this city by some drunk drivers' },
  { id: 7, active: 'Tom will visit Mary next month.', expected: 'Mary will be visited by Tom next month' },
  { id: 8, active: 'The manager didn\'t phone the secretary this morning.', expected: 'The secretary was not phoned by the manager this morning' },
  { id: 9, active: 'The students have discussed the pollution problems since last week.', expected: 'The pollution problems have been discussed by the students since last week' },
  { id: 10, active: 'Some people will interview the new president on TV.', expected: 'The new president will be interviewed on TV' }
]

const translationExercises = computed(() => {
  const ex = grammar.value?.exercises || grammar.value?.exercises_json || {}
  return ex.translations || []
})

const verbConjugationExercises = computed(() => {
  const ex = grammar.value?.exercises || grammar.value?.exercises_json || {}
  return ex.verb_conjugations || []
})

const sentenceBuildingExercises = computed(() => {
  const ex = grammar.value?.exercises || grammar.value?.exercises_json || {}
  return ex.sentence_buildings || []
})

const passiveConversionsExercises = computed(() => {
  const ex = grammar.value?.exercises || grammar.value?.exercises_json || {}
  if (ex.passive_conversions && ex.passive_conversions.length > 0) {
    return ex.passive_conversions
  }
  const slug = grammar.value?.slug || ''
  const title = grammar.value?.title || ''
  if (slug.includes('bi-dong') || slug.includes('passive') || title.includes('Bị Động') || title.includes('Passive')) {
    return defaultPassive10
  }
  return []
})

const totalExercisesCount = computed(() => {
  return translationExercises.value.length + verbConjugationExercises.value.length + sentenceBuildingExercises.value.length + passiveConversionsExercises.value.length
})

const progressPercent = computed(() => {
  if (totalExercisesCount.value === 0) return 0
  return Math.min(100, Math.round((completedCount.value / totalExercisesCount.value) * 100))
})

const isAllCompleted = computed(() => {
  return totalExercisesCount.value > 0 && completedCount.value >= totalExercisesCount.value
})

const completedCount = computed(() => {
  const transPassed = Object.values(results.value).filter(r => r?.passed).length
  const verbPassed = Object.values(verbResults.value).filter(r => r?.passed).length
  const buildPassed = Object.values(buildResults.value).filter(r => r?.passed).length
  const passivePassed = Object.values(passiveResults.value).filter(r => r?.passed).length
  return transPassed + verbPassed + buildPassed + passivePassed
})

// Guaranteed Local & Offline Grader
const evaluateTranslationLocally = (userAns, expected) => {
  const norm = (s) => (s || '')
    .toLowerCase()
    .replace(/[.,?!:;"'`\-_()]/g, ' ')
    .replace(/\s+/g, ' ')
    .trim()

  const cleanUser = norm(userAns)
  const cleanExp = norm(expected)

  // 1. Direct match
  if (cleanUser === cleanExp) {
    return {
      passed: true,
      similarity_score: 100,
      ai_result: {
        corrected_answer: expected,
        simple_explanation: 'Chính xác 100%! Bạn đã dịch rất đúng và chuẩn xác cấu trúc ngữ pháp.'
      }
    }
  }

  // 2. Token sets (handles connectors like "and", ",", "&", "-", order-insensitive)
  const stripConnectors = (tokens) => {
    const stopWords = ['and', '&', 'or', 'a', 'an', 'the', 'with', 'to']
    return tokens.filter(t => !stopWords.includes(t))
  }
  const uTokens = stripConnectors(cleanUser.split(' ').filter(Boolean))
  const eTokens = stripConnectors(cleanExp.split(' ').filter(Boolean))

  const uSorted = [...uTokens].sort().join(' ')
  const eSorted = [...eTokens].sort().join(' ')

  if (uSorted && eSorted && uSorted === eSorted) {
    return {
      passed: true,
      similarity_score: 100,
      ai_result: {
        corrected_answer: expected,
        simple_explanation: 'Xuất sắc! Bạn đã điền đúng tất cả các từ cần thiết dù cách dùng từ nối (dấu phẩy hoặc từ and) có khác một chút.'
      }
    }
  }

  // 3. Substring / Token matching
  let matchedTokens = 0
  const eSet = new Set(eTokens)
  uTokens.forEach(t => {
    if (eSet.has(t)) matchedTokens++
  })

  const tokenScore = eTokens.length > 0 ? Math.round((matchedTokens / eTokens.length) * 100) : 0

  // 4. Character overlap
  const maxLen = Math.max(cleanUser.length, cleanExp.length)
  let commonChars = 0
  for (let i = 0; i < Math.min(cleanUser.length, cleanExp.length); i++) {
    if (cleanUser[i] === cleanExp[i]) commonChars++
  }
  const charScore = maxLen > 0 ? Math.round((commonChars / maxLen) * 100) : 0
  const finalScore = Math.min(100, Math.max(tokenScore, charScore))

  const isPassed = finalScore >= 80

  return {
    passed: isPassed,
    similarity_score: finalScore,
    ai_result: {
      corrected_answer: expected,
      simple_explanation: isPassed
        ? `Đạt yêu cầu (${finalScore}%)! Câu dịch của bạn đã thể hiện đúng nghĩa cốt lõi của thì.`
        : `Chưa đạt (${finalScore}%). Hãy đối chiếu với đáp án chuẩn bên dưới để hoàn thiện nhé.`
    }
  }
}

// Save & Restore localStorage Progress
const getProgressStorageKey = () => `ox_grammar_${grammar.value?.id}_progress`

const saveLocalProgress = () => {
  if (!grammar.value?.id) return
  const data = {
    userAnswers: userAnswers.value,
    results: results.value,
    verbAnswers: verbAnswers.value,
    verbResults: verbResults.value,
    buildAnswers: buildAnswers.value,
    buildResults: buildResults.value,
    passiveAnswers: passiveAnswers.value,
    passiveResults: passiveResults.value,
    completedCount: completedCount.value,
    totalExercisesCount: totalExercisesCount.value,
    savedAt: new Date().toISOString()
  }
  localStorage.setItem(getProgressStorageKey(), JSON.stringify(data))

  // Update global grammar overview storage for GrammarView.vue
  try {
    const allProgress = JSON.parse(localStorage.getItem('ox_all_grammar_progress') || '{}')
    allProgress[grammar.value.id] = {
      completedCount: completedCount.value,
      totalCount: totalExercisesCount.value,
      isFinished: completedCount.value >= totalExercisesCount.value && totalExercisesCount.value > 0,
      percent: totalExercisesCount.value > 0 ? Math.round((completedCount.value / totalExercisesCount.value) * 100) : 0,
      updatedAt: new Date().toISOString()
    }
    localStorage.setItem('ox_all_grammar_progress', JSON.stringify(allProgress))
  } catch (e) {}
}

const loadLocalProgress = () => {
  if (!grammar.value?.id) return
  try {
    const saved = localStorage.getItem(getProgressStorageKey())
    if (saved) {
      const data = JSON.parse(saved)
      if (data.userAnswers) userAnswers.value = data.userAnswers
      if (data.results) results.value = data.results
      if (data.verbAnswers) verbAnswers.value = data.verbAnswers
      if (data.verbResults) verbResults.value = data.verbResults
      if (data.buildAnswers) buildAnswers.value = data.buildAnswers
      if (data.buildResults) buildResults.value = data.buildResults
      if (data.passiveAnswers) passiveAnswers.value = data.passiveAnswers
      if (data.passiveResults) passiveResults.value = data.passiveResults
    }
  } catch (e) {
    console.error('Lỗi tải tiến độ cũ:', e)
  }
}

const resetAllExercises = () => {
  if (!confirm('Bạn có chắc chắn muốn làm lại bài tập của thì này? Toàn bộ kết quả đã làm sẽ được làm mới.')) return
  userAnswers.value = {}
  results.value = {}
  verbAnswers.value = {}
  verbResults.value = {}
  buildAnswers.value = {}
  buildResults.value = {}
  passiveAnswers.value = {}
  passiveResults.value = {}
  if (grammar.value?.id) {
    localStorage.removeItem(getProgressStorageKey())
    saveLocalProgress()
  }
}

const getPlaceholder = (item) => {
  if (!item?.expected) return '...'
  const words = item.expected.split(' ')
  return words.slice(0, 2).join(' ') + '...'
}

const scrollToSection = (sectionId) => {
  const el = document.getElementById(sectionId)
  if (el) {
    el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const checkPassiveConversion = async (idx, item) => {
  const ans = (passiveAnswers.value[idx] || '').trim()
  if (!ans) return

  loadingPassiveCheck.value[idx] = true

  try {
    const res = await axios.post('/api/grammars/check-translation', {
      grammar_id: grammar.value.id,
      vi_prompt: 'Chuyển sang bị động: ' + item.active,
      user_answer: ans,
      expected_answer: item.expected,
      tense: 'Thể bị động (Passive Voice)'
    })

    const payload = res.data.data || res.data
    const isPassed = Boolean(payload.passed ?? payload.is_passed ?? (payload.similarity_score >= 80) ?? (payload.score >= 80))
    const score = payload.similarity_score ?? payload.score ?? (isPassed ? 100 : 0)

    passiveResults.value[idx] = {
      passed: isPassed,
      similarity_score: score,
      ai_result: payload.ai_result || {
        corrected_answer: payload.corrected_answer || item.expected,
        simple_explanation: payload.explanation || (isPassed ? 'Chính xác! Bạn đã chuyển sang cấu trúc bị động rất chuẩn.' : 'Chưa chính xác, hãy đối chiếu với cấu trúc bị động chuẩn bên dưới.')
      }
    }

    if (isPassed && authStore.user) {
      authStore.user.xp_points = payload.user_xp || ((authStore.user.xp_points || 0) + 15)
    }
    saveLocalProgress()
  } catch (err) {
    console.warn('API chấm điểm không khả dụng, sử dụng bộ chấm điểm thông minh cục bộ:', err)
    const fallback = evaluateTranslationLocally(ans, item.expected)
    passiveResults.value[idx] = fallback
    if (fallback.passed && authStore.user) {
      authStore.user.xp_points = (authStore.user.xp_points || 0) + 15
    }
    saveLocalProgress()
  } finally {
    loadingPassiveCheck.value[idx] = false
  }
}

const checkAnswer = async (idx, item) => {
  const ans = userAnswers.value[idx]?.trim()
  if (!ans) return

  loadingCheck.value[idx] = true

  try {
    const res = await axios.post('/api/grammars/check-translation', {
      grammar_id: grammar.value.id,
      vi_prompt: item.vi || item.active,
      user_answer: ans,
      expected_answer: item.expected,
      tense: item.tense || grammar.value.title
    })

    const payload = res.data.data || res.data
    const isPassed = Boolean(payload.passed ?? payload.is_passed ?? (payload.similarity_score >= 80) ?? (payload.score >= 80))
    const score = payload.similarity_score ?? payload.score ?? (isPassed ? 100 : 0)

    results.value[idx] = {
      passed: isPassed,
      similarity_score: score,
      ai_result: payload.ai_result || {
        corrected_answer: payload.corrected_answer || item.expected,
        simple_explanation: payload.explanation || (isPassed ? 'Chính xác! Bạn đã dịch đúng cấu trúc ngữ pháp và từ vựng chuẩn.' : 'Chưa chính xác lắm, hãy đối chiếu với đáp án chuẩn bên dưới.')
      }
    }

    if (isPassed && authStore.user) {
      authStore.user.xp_points = payload.user_xp || ((authStore.user.xp_points || 0) + 15)
    }
    saveLocalProgress()
  } catch (err) {
    console.warn('API chấm điểm dịch câu không khả dụng, sử dụng bộ chấm điểm thông minh cục bộ:', err)
    // Guaranteed Offline / Local Fallback Grader
    const fallback = evaluateTranslationLocally(ans, item.expected)
    results.value[idx] = fallback
    if (fallback.passed && authStore.user) {
      authStore.user.xp_points = (authStore.user.xp_points || 0) + 15
    }
    saveLocalProgress()
  } finally {
    loadingCheck.value[idx] = false
  }
}

const checkVerbConjugation = (idx, item) => {
  const ans = (verbAnswers.value[idx] || '').trim().toLowerCase()
  if (!ans) return
  const expected = (item.expected || '').trim().toLowerCase()
  const cleanAns = ans.replace(/[.,?!:;"'\-]/g, '').replace(/\s+/g, ' ')
  const cleanExp = expected.replace(/[.,?!:;"'\-]/g, '').replace(/\s+/g, ' ')
  const isMatch = cleanAns === cleanExp || cleanAns.includes(cleanExp)
  
  verbResults.value[idx] = {
    passed: isMatch,
    userAnswer: ans,
    expected: item.expected,
    explain: item.explain
  }
  if (isMatch && authStore.user) {
    authStore.user.xp_points = (authStore.user.xp_points || 0) + 10
  }
  saveLocalProgress()
}

const checkSentenceBuilding = (idx, item) => {
  const ans = (buildAnswers.value[idx] || '').trim().toLowerCase()
  if (!ans) return
  const expected = (item.expected || '').trim().toLowerCase()
  const cleanAns = ans.replace(/[.,?!:;"'\-]/g, '').replace(/\s+/g, ' ')
  const cleanExp = expected.replace(/[.,?!:;"'\-]/g, '').replace(/\s+/g, ' ')
  const isMatch = cleanAns === cleanExp || cleanAns.includes(cleanExp.substring(0, 15))
  
  buildResults.value[idx] = {
    passed: isMatch,
    userAnswer: ans,
    expected: item.expected,
    explain: item.explain
  }
  if (isMatch && authStore.user) {
    authStore.user.xp_points = (authStore.user.xp_points || 0) + 15
  }
  saveLocalProgress()
}

const startQuiz = async () => {
  try {
    const res = await axios.get(`/api/grammars/quiz/${route.params.id}`)
    grammarQuizData.value = res.data.data
    const modalEl = document.getElementById('dailyQuizModal')
    if (modalEl) {
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl)
      modal.show()
    }
  } catch (err) {
    console.error('Failed to load grammar quiz:', err)
  }
}

const handleGrammarQuizCompleted = () => {
  // refresh user data
  authStore.fetchMe()
}

onMounted(async () => {
  isLoading.value = true
  try {
    const res = await axios.get(`/api/grammars/${route.params.id}`)
    grammar.value = res.data.data
    loadLocalProgress()
  } catch (err) {
    console.error('Failed to load grammar detail:', err)
  } finally {
    isLoading.value = false
  }
})
</script>

<style scoped>
.grammar-detail-page {
  min-height: 80vh;
}

.theory-html-content :deep(table),
.passive-html-content :deep(table),
.pronunciation-html-content :deep(table) {
  width: 100%;
  margin-bottom: 1rem;
  border-collapse: collapse;
}

.theory-html-content :deep(table th),
.theory-html-content :deep(table td),
.passive-html-content :deep(table th),
.passive-html-content :deep(table td),
.pronunciation-html-content :deep(table th),
.pronunciation-html-content :deep(table td) {
  padding: 0.6rem 0.8rem;
  border: 1px solid #e2e8f0;
  font-size: 0.875rem;
}

.theory-html-content :deep(table thead),
.passive-html-content :deep(table thead),
.pronunciation-html-content :deep(table thead) {
  background-color: #f8fafc;
  font-weight: 700;
}

.animate-pulse {
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.85; transform: scale(1.05); }
}
</style>
