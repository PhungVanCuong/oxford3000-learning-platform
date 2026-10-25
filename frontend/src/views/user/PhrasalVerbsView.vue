<template>
  <div class="container py-4 max-w-6xl">
    <!-- 1. Header Banner -->
    <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4" style="border-left: 6px solid #00b074;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
            <span class="badge bg-teal text-white rounded-pill px-3 py-1 fs-8">
              CỤM TỪ & PHRASAL VERBS TOÀN DIỆN
            </span>
            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-8 fw-bold">
              100 Cụm Động Từ + 850 Cụm Từ Thông Dụng (PDF)
            </span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-8">
              Đồng bộ XP & Tiến độ tự động
            </span>
          </div>
          <h2 class="fw-black text-teal mb-1">Kho Cụm Từ & Phương Pháp Học Thực Chiến</h2>
          <p class="text-muted fs-7 mb-0">
            Học trọn bộ 100 cụm động từ thông dụng chia theo nhóm chữ cái (A-W), 3 phương pháp học đột phá, bài tập thực hành kèm đáp án từ giáo trình và 850 mẫu câu đàm thoại chia làm 17 phần.
          </p>
        </div>

        <!-- Progress Overview Badge -->
        <div class="d-flex flex-column align-items-md-end gap-1 shrink-0">
          <div class="d-flex align-items-center gap-2">
            <div class="card px-3 py-2 rounded-3 border bg-white text-center shadow-xs">
              <div class="text-muted fs-8 fw-semibold text-uppercase">Tổng số cụm</div>
              <div class="fs-5 fw-black text-teal">{{ stats.total_phrases || 945 }}</div>
            </div>
            <div class="card px-3 py-2 rounded-3 border bg-white text-center shadow-xs">
              <div class="text-muted fs-8 fw-semibold text-uppercase">Đã thuộc</div>
              <div class="fs-5 fw-black text-success">{{ stats.mastered || 0 }}</div>
            </div>
            <div class="card px-3 py-2 rounded-3 border bg-white text-center shadow-xs">
              <div class="text-muted fs-8 fw-semibold text-uppercase">Tiến độ</div>
              <div class="fs-5 fw-black text-warning-emphasis">{{ stats.percent || 0 }}%</div>
            </div>
          </div>
          <div class="progress w-100 mt-1" style="height: 6px; border-radius: 999px;">
            <div
              class="progress-bar rounded-pill bg-teal"
              role="progressbar"
              :style="{ width: `${stats.percent || 0}%` }"
            ></div>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. Main Tab Navigation -->
    <div class="card p-2 rounded-4 shadow-sm border bg-card-custom mb-4">
      <ul class="nav nav-pills nav-fill gap-2 flex-column flex-sm-row">
        <!-- Tab 1: 1000 Cụm từ thông dụng -->
        <li class="nav-item">
          <button
            @click="activeMainTab = 'pdf_phrases'"
            class="nav-link py-2.5 px-3 rounded-3 fw-bold fs-7 d-flex align-items-center justify-content-center gap-2 text-nowrap transition-all"
            :class="activeMainTab === 'pdf_phrases' ? 'active-pill-teal' : 'text-dark-emphasis'"
          >
            <i class="bi bi-collection-fill"></i>
            <span>1000 Cụm Từ Thông Dụng (17 Phần)</span>
            <span class="badge rounded-pill bg-light text-dark ms-1" style="font-size: 10px;">850 câu</span>
          </button>
        </li>

        <!-- Tab 2: 100 Cụm động từ A-W -->
        <li class="nav-item">
          <button
            @click="activeMainTab = 'phrasal_verbs_100'"
            class="nav-link py-2.5 px-3 rounded-3 fw-bold fs-7 d-flex align-items-center justify-content-center gap-2 text-nowrap transition-all"
            :class="activeMainTab === 'phrasal_verbs_100' ? 'active-pill-teal' : 'text-dark-emphasis'"
          >
            <i class="bi bi-puzzle-fill"></i>
            <span>100 Cụm Động Từ (A-W)</span>
            <span class="badge rounded-pill bg-light text-dark ms-1" style="font-size: 10px;">95 cụm</span>
          </button>
        </li>

        <!-- Tab 3: Phương pháp học -->
        <li class="nav-item">
          <button
            @click="activeMainTab = 'methods'"
            class="nav-link py-2.5 px-3 rounded-3 fw-bold fs-7 d-flex align-items-center justify-content-center gap-2 text-nowrap transition-all"
            :class="activeMainTab === 'methods' ? 'active-pill-teal' : 'text-dark-emphasis'"
          >
            <i class="bi bi-lightbulb-fill"></i>
            <span>Phương Pháp Học Đột Phá</span>
          </button>
        </li>

        <!-- Tab 4: Bài tập thực hành -->
        <li class="nav-item">
          <button
            @click="activeMainTab = 'exercises'"
            class="nav-link py-2.5 px-3 rounded-3 fw-bold fs-7 d-flex align-items-center justify-content-center gap-2 text-nowrap transition-all"
            :class="activeMainTab === 'exercises' ? 'active-pill-teal' : 'text-dark-emphasis'"
          >
            <i class="bi bi-pencil-square"></i>
            <span>Bài Tập Thực Hành (+XP)</span>
          </button>
        </li>

        <!-- Tab 5: Thẻ Flashcard 3D -->
        <li class="nav-item">
          <button
            @click="activeMainTab = 'flashcard'"
            class="nav-link py-2.5 px-3 rounded-3 fw-bold fs-7 d-flex align-items-center justify-content-center gap-2 text-nowrap transition-all"
            :class="activeMainTab === 'flashcard' ? 'active-pill-teal' : 'text-dark-emphasis'"
          >
            <i class="bi bi-card-text"></i>
            <span>Thẻ Flashcard 3D</span>
          </button>
        </li>
      </ul>
    </div>

    <!-- TAB 1: 1000 CỤM TỪ THÔNG DỤNG (CHIA 17 PHẦN) -->
    <div v-if="activeMainTab === 'pdf_phrases'">
      <!-- Part Selector Carousel / Pills -->
      <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fs-8 fw-bold text-uppercase text-muted d-flex align-items-center gap-1">
            <i class="bi bi-signpost-2-fill text-teal"></i>
            <span>Chọn Phần Học (Mỗi phần 50 câu đàm thoại thông dụng):</span>
          </span>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-teal-subtle text-teal fw-bold fs-8">
              Đang xem: {{ selectedPartNumber ? `Phần ${selectedPartNumber}` : 'Tất cả 17 phần' }}
            </span>
            <button
              v-if="selectedPartNumber"
              @click="startFlashcardForPart(selectedPartNumber)"
              class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1 fw-bold fs-8 d-inline-flex align-items-center gap-1 shadow-2xs text-nowrap"
            >
              <i class="bi bi-card-text"></i>
              <span>Luyện Thẻ 3D Phần {{ selectedPartNumber }}</span>
            </button>
          </div>
        </div>

        <div class="d-flex gap-1.5 overflow-x-auto pb-1" style="scrollbar-width: thin;">
          <button
            @click="selectPart(null)"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap"
            :class="selectedPartNumber === null ? 'btn-teal text-white' : 'btn-outline-secondary'"
          >
            Tất cả (850 câu)
          </button>
          <button
            v-for="part in 17"
            :key="part"
            @click="selectPart(part)"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap d-flex align-items-center gap-1"
            :class="selectedPartNumber === part ? 'btn-teal text-white' : 'btn-outline-secondary'"
          >
            <span>Phần {{ part }}</span>
            <span class="badge rounded-pill bg-white text-dark" style="font-size: 9px;">
              {{ (part - 1) * 50 + 1 }}-{{ Math.min(850, part * 50) }}
            </span>
          </button>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-3">
        <div class="row g-2 align-items-center">
          <div class="col-12 col-md-6">
            <div class="input-group">
              <span class="input-group-text bg-body-tertiary border-end-0">
                <i class="bi bi-search text-teal"></i>
              </span>
              <input
                v-model="searchQuery"
                @input="debouncedFetch"
                type="text"
                class="form-control border-start-0 py-2 fs-7"
                placeholder="Tìm cụm từ, tiếng Anh hoặc nghĩa tiếng Việt (VD: How are you, agree with, have a seat...)..."
              />
              <button v-if="searchQuery" @click="clearSearch" class="btn btn-outline-secondary border-start-0" type="button">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
          </div>

          <div class="col-6 col-md-3">
            <select v-model="filterStatus" @change="fetchPhrases" class="form-select py-2 fs-7">
              <option value="all">Tất cả tiến độ</option>
              <option value="mastered">Đã nhớ (Mastered)</option>
              <option value="learning">Cần ôn (Learning)</option>
              <option value="unlearned">Chưa học</option>
            </select>
          </div>

          <div class="col-6 col-md-3 text-end">
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-7">
              Hiển thị: <strong>{{ phrases.length }}</strong> câu
            </span>
          </div>
        </div>
      </div>

      <!-- Table View for 1000 Phrases -->
      <div class="card rounded-4 border shadow-sm overflow-hidden bg-card-custom mb-4">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light fs-8 text-uppercase">
              <tr>
                <th style="width: 50px;" class="text-center">STT</th>
                <th>Cụm từ / Mẫu câu tiếng Anh</th>
                <th>Dịch nghĩa tiếng Việt</th>
                <th style="width: 140px;" class="text-center">Phần học</th>
                <th style="width: 120px;" class="text-center">Trạng thái</th>
                <th style="width: 130px;" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody class="fs-7">
              <tr v-if="isLoading">
                <td colspan="6" class="text-center py-5">
                  <div class="spinner-border text-teal" role="status"></div>
                  <div class="text-muted fs-8 mt-2">Đang tải danh sách cụm từ...</div>
                </td>
              </tr>
              <tr v-else-if="phrases.length === 0">
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                  Không tìm thấy cụm từ nào phù hợp với bộ lọc hiện tại.
                </td>
              </tr>
              <tr v-for="(item, idx) in phrases" :key="item.id" :class="{ 'table-success-subtle': item.status === 'mastered' }">
                <td class="text-center text-muted fw-bold">
                  {{ item.id }}
                </td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <button
                      @click="speech.speak(item.phrase)"
                      class="btn btn-sm btn-outline-teal rounded-circle p-0 d-flex align-items-center justify-content-center shrink-0"
                      style="width: 28px; height: 28px;"
                      title="Phát âm"
                    >
                      <i class="bi bi-volume-up-fill fs-8"></i>
                    </button>
                    <div>
                      <div class="fw-bold text-teal fs-6">{{ item.phrase }}</div>
                      <div v-if="item.example_en" class="text-muted fs-8 fst-italic">{{ item.example_en }}</div>
                    </div>
                  </div>
                </td>
                <td class="fw-semibold text-dark-emphasis">
                  {{ item.meaning_vi }}
                </td>
                <td class="text-center">
                  <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-8">
                    Phần {{ item.part_number || Math.ceil(item.id / 50) }}
                  </span>
                </td>
                <td class="text-center">
                  <span
                    class="badge px-2.5 py-1 rounded-pill fw-bold fs-8"
                    :class="item.status === 'mastered' ? 'bg-success text-white' : item.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary text-white'"
                  >
                    {{ item.status === 'mastered' ? '✓ Đã nhớ' : item.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                  </span>
                </td>
                <td class="text-center">
                  <div class="d-inline-flex align-items-center justify-content-center gap-1.5 text-nowrap">
                    <button
                      @click="markPhraseStatus(item, item.status === 'mastered' ? 'unlearned' : 'mastered')"
                      class="btn btn-sm rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1 text-nowrap shadow-2xs"
                      :class="item.status === 'mastered' ? 'btn-success text-white' : 'btn-outline-success'"
                      title="Đánh dấu đã thuộc"
                    >
                      <i class="bi" :class="item.status === 'mastered' ? 'bi-check2-circle' : 'bi-check2'"></i>
                      <span class="fs-8 fw-semibold text-nowrap">{{ item.status === 'mastered' ? 'Đã thuộc' : 'Thuộc' }}</span>
                    </button>
                    <button
                      @click="markPhraseStatus(item, 'learning')"
                      class="btn btn-sm btn-outline-warning rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1 text-nowrap shadow-2xs"
                      title="Cần ôn lại"
                    >
                      <i class="bi bi-arrow-clockwise"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB 2: 100 CỤM ĐỘNG TỪ (A-W) -->
    <div v-if="activeMainTab === 'phrasal_verbs_100'">
      <!-- Alphabet Navigator Bar -->
      <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fs-8 fw-bold text-uppercase text-muted d-flex align-items-center gap-1">
            <i class="bi bi-sort-alpha-down text-teal"></i>
            <span>Chọn Bảng Chữ Cái (A - W):</span>
          </span>
          <span class="badge bg-teal text-white fw-bold fs-8">
            {{ selectedLetter ? `Nhóm chữ [${selectedLetter}]` : 'Tất cả bảng chữ cái' }}
          </span>
        </div>

        <div class="d-flex gap-1.5 overflow-x-auto pb-1" style="scrollbar-width: thin;">
          <button
            @click="selectedLetter = null; fetchPhrasalVerbs100()"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap"
            :class="selectedLetter === null ? 'btn-teal text-white' : 'btn-outline-secondary'"
          >
            Tất cả (95 cụm)
          </button>
          <button
            v-for="letter in alphabetLetters"
            :key="letter"
            @click="selectedLetter = letter; fetchPhrasalVerbs100()"
            class="btn btn-sm rounded-circle p-0 fw-bold d-flex align-items-center justify-content-center shrink-0"
            style="width: 32px; height: 32px;"
            :class="selectedLetter === letter ? 'btn-teal text-white shadow-xs' : 'btn-outline-secondary'"
          >
            {{ letter }}
          </button>
        </div>
      </div>

      <!-- Phrasal Verbs Cards Grid -->
      <div class="row g-3">
        <div v-for="pv in phrasalVerbs100List" :key="pv.id" class="col-12 col-md-6 col-lg-4">
          <div
            class="card p-3.5 rounded-4 border h-100 shadow-sm transition-all bg-card-custom hover-card"
            :class="{ 'border-success bg-success-subtle': pv.status === 'mastered' }"
          >
            <!-- Card Header -->
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-teal-subtle text-teal fw-bold fs-8 px-2 py-0.5 rounded-pill">
                Chữ [{{ (pv.phrase || 'A')[0].toUpperCase() }}]
              </span>
              <span
                class="badge px-2 py-0.5 rounded-pill fs-8 fw-semibold"
                :class="pv.status === 'mastered' ? 'bg-success text-white' : pv.status === 'learning' ? 'bg-warning text-dark' : 'bg-light text-muted border'"
              >
                {{ pv.status === 'mastered' ? '✓ Đã nhớ' : pv.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
              </span>
            </div>

            <!-- Phrase & Sound -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
              <h5 class="fw-black text-teal mb-0">{{ pv.phrase }}</h5>
              <button
                @click="speech.speak(pv.phrase)"
                class="btn btn-sm btn-outline-teal rounded-circle p-0 d-flex align-items-center justify-content-center shrink-0"
                style="width: 30px; height: 30px;"
                title="Phát âm"
              >
                <i class="bi bi-volume-up-fill"></i>
              </button>
            </div>

            <!-- Meaning -->
            <div class="fw-bold text-dark-emphasis fs-7 mb-2 pb-2 border-bottom">
              {{ pv.meaning_vi }}
            </div>

            <!-- Component Words Decomposed -->
            <div v-if="pv.component_words_json && pv.component_words_json.length" class="mb-2.5">
              <div class="text-muted fs-8 fw-semibold mb-1">Ghép từ các từ đơn:</div>
              <div class="d-flex gap-1 flex-wrap">
                <span
                  v-for="cw in pv.component_words_json"
                  :key="cw"
                  class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2 py-0.5 rounded-pill fs-8"
                >
                  + {{ cw }}
                </span>
              </div>
            </div>

            <!-- Example EN & VI -->
            <div v-if="pv.example_en" class="p-2.5 rounded-3 bg-body-tertiary border mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge bg-light text-dark border fs-8">Ví dụ thực tế:</span>
                <button @click="speech.speak(pv.example_en)" class="btn btn-sm btn-link p-0 text-teal fs-8 text-decoration-none">
                  <i class="bi bi-volume-up"></i> Nghe
                </button>
              </div>
              <div class="fs-8 fw-medium text-dark-emphasis mb-1">"{{ pv.example_en }}"</div>
              <div class="fs-8 text-muted fst-italic">→ {{ pv.example_vi }}</div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-auto pt-2 border-top">
              <div class="d-flex justify-content-between align-items-center">
                <button
                  @click="speech.startListening(pv.phrase, 'pv_' + pv.id)"
                  class="btn btn-sm px-2.5 py-1 rounded-pill fs-8 fw-semibold d-inline-flex align-items-center gap-1 text-nowrap"
                  :class="speech.listeningId.value === ('pv_' + pv.id) ? 'btn-danger animate-mic-pulse' : 'btn-outline-info'"
                >
                  <i class="bi" :class="speech.listeningId.value === ('pv_' + pv.id) ? 'bi-record-fill' : 'bi-mic-fill'"></i>
                  <span>{{ speech.listeningId.value === ('pv_' + pv.id) ? 'Đang nghe...' : 'Luyện nói' }}</span>
                </button>

                <div class="d-inline-flex align-items-center gap-1 text-nowrap">
                  <button
                    @click="markPhraseStatus(pv, pv.status === 'mastered' ? 'unlearned' : 'mastered')"
                    class="btn btn-sm rounded-pill px-3 py-1 fw-bold fs-8 d-inline-flex align-items-center gap-1 text-nowrap"
                    :class="pv.status === 'mastered' ? 'btn-success' : 'btn-teal text-white'"
                  >
                    <i class="bi bi-check2"></i>
                    <span>{{ pv.status === 'mastered' ? 'Đã thuộc' : 'Thuộc từ' }}</span>
                  </button>
                </div>
              </div>

              <!-- Scoped Mic Results for this Card -->
              <div v-if="speech.itemResults.value['pv_' + pv.id]" class="mt-2 p-2 rounded-3 bg-white border fs-8">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="text-muted">Bạn đã đọc:</span>
                  <span class="badge" :class="speech.itemResults.value['pv_' + pv.id].score >= 80 ? 'bg-success' : speech.itemResults.value['pv_' + pv.id].score >= 55 ? 'bg-warning text-dark' : 'bg-danger'">
                    {{ speech.itemResults.value['pv_' + pv.id].score }}%
                  </span>
                </div>
                <div class="fw-semibold text-dark-emphasis">"{{ speech.itemResults.value['pv_' + pv.id].transcript }}"</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 3: PHƯƠNG PHÁP HỌC CỤM TỪ ĐỘT PHÁ -->
    <div v-if="activeMainTab === 'methods'">
      <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4">
        <div class="text-center max-w-2xl mx-auto mb-4">
          <span class="badge bg-teal-subtle text-teal fw-bold px-3 py-1.5 rounded-pill fs-8 mb-2">
            BÍ QUYẾT TỪ GIÁO TRÌNH
          </span>
          <h3 class="fw-black text-teal mb-2">{{ methodsData?.title || 'Phương Pháp Học Cụm Động Từ Tiếng Anh Thực Chiến' }}</h3>
          <p class="text-muted fs-7 mb-0">{{ methodsData?.description }}</p>
        </div>

        <div class="row g-4">
          <div v-for="method in methodsData?.methods || []" :key="method.id" class="col-12 col-lg-4">
            <div class="card p-4 rounded-4 border h-100 shadow-sm bg-white hover-card" style="border-top: 4px solid #00b074 !important;">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge rounded-pill px-2.5 py-1 fs-8" :class="`bg-${method.badge_color || 'teal'} text-white`">
                  {{ method.badge }}
                </span>
                <span class="fs-4 text-teal fw-bold">#0{{ method.id }}</span>
              </div>
              <h5 class="fw-bold text-dark-emphasis mb-2.5">{{ method.title }}</h5>
              <p class="fs-7 text-muted leading-relaxed mb-0">{{ method.content }}</p>
            </div>
          </div>
        </div>

        <!-- Deep Dive Study Tips -->
        <div class="p-4 rounded-4 border bg-body-tertiary mt-4">
          <h6 class="fw-bold text-teal mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-compass-fill"></i>
            <span>Bảng Ý Nghĩa Cốt Lõi Của Một Số Từ Phụ Trợ (Particles) Thường Gặp:</span>
          </h6>
          <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0 bg-white fs-7">
              <thead class="table-light">
                <tr>
                  <th style="width: 120px;">Tiểu từ (Particle)</th>
                  <th>Ý nghĩa cốt lõi</th>
                  <th>Ví dụ minh họa</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="fw-bold text-teal">OUT</td>
                  <td>Hết sạch, hoàn toàn biến mất, ra ngoài hoặc bùng phát dữ dội</td>
                  <td><em>run out of</em> (hết sạch), <em>break out</em> (bùng phát), <em>put out</em> (dập tắt lửa)</td>
                </tr>
                <tr>
                  <td class="fw-bold text-teal">OFF</td>
                  <td>Tách rời, gián đoạn, hủy bỏ, rời khỏi vị trí ban đầu</td>
                  <td><em>take off</em> (cất cánh / cởi đồ), <em>call off</em> (hủy bỏ), <em>turn off</em> (tắt thiết bị)</td>
                </tr>
                <tr>
                  <td class="fw-bold text-teal">UP</td>
                  <td>Hoàn tất, triệt để, gia tăng hoặc hướng lên trên</td>
                  <td><em>use up</em> (dùng cạn kiệt), <em>cheer up</em> (vui lên), <em>turn up</em> (vặn to lên / xuất hiện)</td>
                </tr>
                <tr>
                  <td class="fw-bold text-teal">DOWN</td>
                  <td>Giảm bớt, suy giảm, hạ thấp hoặc ghi chép lại</td>
                  <td><em>turn down</em> (vặn nhỏ lại / từ chối), <em>write down</em> (ghi lại), <em>calm down</em> (bình tĩnh lại)</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 4: BÀI TẬP THỰC HÀNH & CHẤM ĐIỂM -->
    <div v-if="activeMainTab === 'exercises'">
      <!-- Exercise Part 1 -->
      <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <div>
            <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 fs-8 mb-1">BÀI 1 (+15 XP / CÂU)</span>
            <h5 class="fw-bold text-teal mb-0">{{ exercisesData?.part1?.title || 'Bài 1: Điền cụm từ tiếng Anh thích hợp' }}</h5>
            <div class="text-muted fs-8">{{ exercisesData?.part1?.instructions }}</div>
          </div>
          <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fs-7 fw-bold">
            10 Câu hỏi thực chiến
          </span>
        </div>

        <div class="space-y-3">
          <div
            v-for="(q, idx) in exercisesData?.part1?.questions || []"
            :key="q.id"
            class="p-3 rounded-4 border transition-all mb-3"
            :class="qResults['p1_' + q.id]?.is_correct ? 'border-success bg-success-subtle' : 'border-light-subtle bg-white'"
          >
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="fw-bold text-muted fs-7">Câu {{ idx + 1 }}:</span>
              <span v-if="qResults['p1_' + q.id]" class="badge rounded-pill px-2.5 py-1 fs-8" :class="qResults['p1_' + q.id]?.is_correct ? 'bg-success text-white' : 'bg-danger text-white'">
                {{ qResults['p1_' + q.id]?.is_correct ? '✓ CHÍNH XÁC (+15 XP)' : '✕ CHƯA ĐÚNG' }}
              </span>
            </div>

            <p class="fs-6 fw-semibold text-dark-emphasis mb-2.5">{{ q.prompt }}</p>

            <!-- Input & Check Button -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <input
                v-model="userAnswers['p1_' + q.id]"
                @keyup.enter="submitAnswer('p1_' + q.id, q.expected)"
                type="text"
                class="form-control form-control-sm rounded-pill px-3 py-1.5 fs-7"
                style="max-width: 320px;"
                placeholder="Nhập cụm từ đúng (VD: break out)..."
                :disabled="qResults['p1_' + q.id]?.is_correct"
              />
              <button
                @click="submitAnswer('p1_' + q.id, q.expected)"
                class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8"
                :disabled="qResults['p1_' + q.id]?.is_correct || !userAnswers['p1_' + q.id]"
              >
                Kiểm tra
              </button>
              <button
                v-if="!qResults['p1_' + q.id]?.is_correct"
                @click="showHint['p1_' + q.id] = !showHint['p1_' + q.id]"
                class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1.5 fs-8"
              >
                {{ showHint['p1_' + q.id] ? 'Ẩn đáp án' : 'Xem đáp án gợi ý' }}
              </button>
            </div>

            <!-- Answer Feedback & Explanation -->
            <div v-if="qResults['p1_' + q.id] || showHint['p1_' + q.id]" class="mt-2.5 pt-2 border-top fs-8">
              <div class="fw-bold text-success mb-0.5">
                Đáp án chuẩn: <span class="font-monospace text-teal fs-7">{{ q.expected }}</span>
              </div>
              <div class="text-muted">
                <i class="bi bi-info-circle me-1"></i>Giải thích: {{ q.explain }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Exercise Part 2 -->
      <div class="card p-4 rounded-4 shadow-sm border bg-card-custom mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <div>
            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-8 mb-1">BÀI 2 (+15 XP / CÂU)</span>
            <h5 class="fw-bold text-teal mb-0">{{ exercisesData?.part2?.title || 'Bài 2: Hoàn thành dạng đúng của cụm động từ theo ngữ cảnh' }}</h5>
            <div class="text-muted fs-8">{{ exercisesData?.part2?.instructions }}</div>
          </div>
          <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fs-7 fw-bold">
            10 Câu ngữ cảnh
          </span>
        </div>

        <div class="space-y-3">
          <div
            v-for="(q, idx) in exercisesData?.part2?.questions || []"
            :key="q.id"
            class="p-3 rounded-4 border transition-all mb-3"
            :class="qResults['p2_' + q.id]?.is_correct ? 'border-success bg-success-subtle' : 'border-light-subtle bg-white'"
          >
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="fw-bold text-muted fs-7">Câu {{ idx + 1 }}:</span>
              <span v-if="qResults['p2_' + q.id]" class="badge rounded-pill px-2.5 py-1 fs-8" :class="qResults['p2_' + q.id]?.is_correct ? 'bg-success text-white' : 'bg-danger text-white'">
                {{ qResults['p2_' + q.id]?.is_correct ? '✓ CHÍNH XÁC (+15 XP)' : '✕ CHƯA ĐÚNG' }}
              </span>
            </div>

            <p class="fs-6 fw-semibold text-dark-emphasis mb-2.5">{{ q.prompt }}</p>

            <!-- Input & Check Button -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <input
                v-model="userAnswers['p2_' + q.id]"
                @keyup.enter="submitAnswer('p2_' + q.id, q.expected)"
                type="text"
                class="form-control form-control-sm rounded-pill px-3 py-1.5 fs-7"
                style="max-width: 320px;"
                placeholder="Nhập cụm từ đúng (VD: look for)..."
                :disabled="qResults['p2_' + q.id]?.is_correct"
              />
              <button
                @click="submitAnswer('p2_' + q.id, q.expected)"
                class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8"
                :disabled="qResults['p2_' + q.id]?.is_correct || !userAnswers['p2_' + q.id]"
              >
                Kiểm tra
              </button>
              <button
                v-if="!qResults['p2_' + q.id]?.is_correct"
                @click="showHint['p2_' + q.id] = !showHint['p2_' + q.id]"
                class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1.5 fs-8"
              >
                {{ showHint['p2_' + q.id] ? 'Ẩn đáp án' : 'Xem đáp án gợi ý' }}
              </button>
            </div>

            <!-- Answer Feedback & Explanation -->
            <div v-if="qResults['p2_' + q.id] || showHint['p2_' + q.id]" class="mt-2.5 pt-2 border-top fs-8">
              <div class="fw-bold text-success mb-0.5">
                Đáp án chuẩn: <span class="font-monospace text-teal fs-7">{{ q.expected }}</span>
              </div>
              <div class="text-muted">
                <i class="bi bi-info-circle me-1"></i>Giải thích: {{ q.explain }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 5: FLASHCARD 3D PLAYER -->
    <div v-if="activeMainTab === 'flashcard'">
      <!-- Part Selector for Flashcards -->
      <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-3 max-w-3xl mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fs-8 fw-bold text-uppercase text-muted d-flex align-items-center gap-1">
            <i class="bi bi-filter-circle text-teal"></i>
            <span>Chọn Nội Dung Luyện Thẻ 3D:</span>
          </span>
          <span class="badge bg-teal text-white fw-bold fs-8">
            {{ getFlashPartLabel() }}
          </span>
        </div>

        <div class="d-flex gap-1.5 overflow-x-auto pb-1" style="scrollbar-width: thin;">
          <button
            @click="setFlashPart('all_1000')"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap"
            :class="selectedFlashPart === 'all_1000' ? 'btn-teal text-white' : 'btn-outline-secondary'"
          >
            Tất cả 1000 cụm từ
          </button>
          <button
            @click="setFlashPart('pv_100')"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap"
            :class="selectedFlashPart === 'pv_100' ? 'btn-teal text-white' : 'btn-outline-secondary'"
          >
            100 Phrasal Verbs (A-W)
          </button>
          <button
            v-for="part in 17"
            :key="part"
            @click="setFlashPart(part)"
            class="btn btn-sm rounded-pill px-2.5 py-1.5 fw-semibold text-nowrap"
            :class="selectedFlashPart === part ? 'btn-teal text-white' : 'btn-outline-secondary'"
          >
            Phần {{ part }}
          </button>
        </div>
      </div>

      <div v-if="flashcardItems.length > 0" class="flashcard-player w-100 max-w-3xl mx-auto my-3">
        <!-- Progress Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted fs-7">
            Cụm từ số <strong>{{ currentFlashIndex + 1 }}</strong> / {{ flashcardItems.length }}
          </span>
          <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 fs-8">
              {{ currentFlashItem?.category === 'phrasal_verb_100' ? '100 Phrasal Verbs' : '1000 Cụm từ thông dụng' }}
            </span>
          </div>
        </div>

        <!-- 3D Card Box -->
        <div
          class="perspective-1000 w-100 cursor-pointer select-none mb-3"
          style="min-height: 380px;"
          @click="isFlipped = !isFlipped"
        >
          <div
            class="position-relative w-100 h-100 rounded-4 shadow-lg border border-2 transform-style-preserve-3d flashcard-3d-inner"
            :class="{ 'rotate-y-180': isFlipped }"
            style="min-height: 380px;"
          >
            <!-- FRONT SIDE -->
            <div
              class="position-absolute top-0 start-0 w-100 h-100 backface-hidden rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between"
              :class="authStore.themeMode === 'dark' ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
            >
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-teal text-white px-3 py-1.5 rounded-pill fs-8">
                  MẶT TRƯỚC • TIẾNG ANH & CÁC TỪ GHÉP
                </span>
                <span class="badge" :class="currentFlashItem?.status === 'mastered' ? 'bg-success' : currentFlashItem?.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary'">
                  {{ currentFlashItem?.status === 'mastered' ? 'Đã nhớ' : currentFlashItem?.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                </span>
              </div>

              <div class="text-center my-auto py-3">
                <h1 class="display-5 fw-black text-teal mb-3 tracking-tight">
                  {{ currentFlashItem?.phrase }}
                </h1>

                <!-- Component Words Indicator -->
                <div v-if="currentFlashItem?.component_words_json && currentFlashItem?.component_words_json.length" class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-3">
                  <span class="fs-8 text-muted fw-semibold">Từ ghép thành:</span>
                  <span
                    v-for="word in currentFlashItem?.component_words_json"
                    :key="word"
                    class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fs-7 font-monospace"
                  >
                    + {{ word }}
                  </span>
                </div>

                <!-- Example EN -->
                <div v-if="currentFlashItem?.example_en" class="bg-body-tertiary p-3 rounded-3 text-start border my-2" @click.stop>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-teal text-white fs-8">CÂU VÍ DỤ</span>
                    <button @click="speech.speak(currentFlashItem?.example_en)" class="btn btn-sm btn-outline-teal py-0 px-2 rounded-pill fs-8">
                      <i class="bi bi-volume-up-fill"></i> Nghe câu
                    </button>
                  </div>
                  <p class="fs-6 fw-semibold mb-0 text-teal">{{ currentFlashItem?.example_en }}</p>
                </div>
              </div>

              <div class="text-center pt-2 border-top text-muted fs-8">
                <i class="bi bi-arrow-repeat me-1"></i>Bấm <strong>Space</strong> hoặc nhấp chuột để lật thẻ
              </div>
            </div>

            <!-- BACK SIDE -->
            <div
              class="position-absolute top-0 start-0 w-100 h-100 backface-hidden rotate-y-180 rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between"
              :class="authStore.themeMode === 'dark' ? 'bg-dark text-white border-secondary' : 'bg-white text-dark border-light-subtle'"
            >
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fs-8">
                  MẶT SAU • NGHĨA TIẾNG VIỆT
                </span>
                <span class="badge" :class="currentFlashItem?.status === 'mastered' ? 'bg-success' : currentFlashItem?.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary'">
                  {{ currentFlashItem?.status === 'mastered' ? 'Đã nhớ' : currentFlashItem?.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                </span>
              </div>

              <div class="my-auto py-3 text-center">
                <div class="text-muted fs-7 mb-1">Dịch nghĩa tiếng Việt:</div>
                <h2 class="display-6 fw-bold text-success mb-3">
                  {{ currentFlashItem?.meaning_vi }}
                </h2>

                <div v-if="currentFlashItem?.example_vi" class="bg-body-tertiary p-3 rounded-3 text-start border my-2">
                  <span class="badge bg-secondary text-white fs-8 mb-2">BẢN DỊCH CÂU VÍ DỤ</span>
                  <p class="fs-6 text-dark-emphasis mb-0 fst-italic">
                    "{{ currentFlashItem?.example_vi }}"
                  </p>
                </div>
              </div>

              <div class="text-center pt-2 border-top text-muted fs-8">
                <i class="bi bi-arrow-repeat me-1"></i>Bấm <strong>Space</strong> hoặc nhấp chuột để lật lại mặt trước
              </div>
            </div>
          </div>
        </div>

        <!-- Strict Single-Row Action Controls -->
        <div class="d-flex align-items-center justify-content-center gap-1.5 flex-nowrap overflow-x-auto py-1 w-100 flashcard-controls-row">
          <!-- [← / Q] Prev -->
          <button
            @click="prevFlash"
            :disabled="currentFlashIndex === 0"
            class="btn btn-outline-secondary btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
            title="Lùi thẻ (Phím ← hoặc Q)"
          >
            <i class="bi bi-chevron-left"></i>
            <span>[Q] Trước</span>
          </button>

          <!-- [Space] Flip -->
          <button
            @click="isFlipped = !isFlipped"
            class="btn btn-teal btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
            title="Lật thẻ (Phím Space)"
          >
            <i class="bi bi-arrow-repeat"></i>
            <span>[Space] Lật</span>
          </button>

          <!-- [A] Listen -->
          <button
            @click="speech.speak(currentFlashItem?.phrase)"
            class="btn btn-primary btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
            title="Nghe phát âm chuẩn (Phím A)"
          >
            <i class="bi bi-volume-up-fill"></i>
            <span>[A] Nghe</span>
          </button>

          <!-- [D] Speak -->
          <button
            @click="speech.startListening(currentFlashItem?.phrase, 'flash_' + currentFlashItem?.id)"
            class="btn btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
            :class="speech.listeningId.value === ('flash_' + currentFlashItem?.id) ? 'btn-danger animate-mic-pulse' : 'btn-info text-white'"
            title="Luyện nói Micro (Phím D)"
          >
            <i class="bi" :class="speech.listeningId.value === ('flash_' + currentFlashItem?.id) ? 'bi-record-fill' : 'bi-mic-fill'"></i>
            <span>[D] {{ speech.listeningId.value === ('flash_' + currentFlashItem?.id) ? 'Đang nghe...' : 'Luyện nói' }}</span>
          </button>

          <!-- [S] Mastered -->
          <button
            @click="markPhraseStatus(currentFlashItem, 'mastered')"
            class="btn btn-success btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
            title="Đã nhớ cụm này (Phím S)"
          >
            <i class="bi bi-check-circle-fill"></i>
            <span>[S] Đã nhớ</span>
          </button>

          <!-- [X] Need Review -->
          <button
            @click="markPhraseStatus(currentFlashItem, 'learning')"
            class="btn btn-warning btn-sm text-white px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
            title="Cần ôn lại cụm này (Phím X)"
          >
            <i class="bi bi-arrow-clockwise"></i>
            <span>[X] Cần ôn</span>
          </button>

          <!-- [→ / E] Next -->
          <button
            @click="nextFlash"
            :disabled="currentFlashIndex === flashcardItems.length - 1"
            class="btn btn-outline-secondary btn-sm px-2 px-md-2.5 py-1.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0 fs-8"
            title="Tiếp thẻ (Phím → hoặc E)"
          >
            <span>[E] Tiếp</span>
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>

        <!-- Mic result under card -->
        <div v-if="speech.itemResults.value['flash_' + currentFlashItem?.id]" class="mt-2 p-2 rounded-3 bg-light border text-center fs-8">
          <span class="text-muted">Bạn vừa đọc:</span> <strong>"{{ speech.itemResults.value['flash_' + currentFlashItem?.id].transcript }}"</strong>
          <span class="badge ms-2" :class="speech.itemResults.value['flash_' + currentFlashItem?.id].score >= 80 ? 'bg-success' : 'bg-warning text-dark'">
            {{ speech.itemResults.value['flash_' + currentFlashItem?.id].score }}%
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useSpeech } from '@/composables/useSpeech'

const authStore = useAuthStore()
const speech = useSpeech()

// Tabs
const activeMainTab = ref('pdf_phrases') // 'pdf_phrases' | 'phrasal_verbs_100' | 'methods' | 'exercises' | 'flashcard'

// Stats
const stats = ref({
  total_phrases: 945,
  total_phrasal_verbs: 95,
  total_common_phrases: 850,
  mastered: 0,
  learning: 0,
  unlearned: 945,
  percent: 0,
})

// Tab 1: 1000 Phrases (850 items from PDF)
const phrases = ref([])
const selectedPartNumber = ref(null)
const searchQuery = ref('')
const filterStatus = ref('all')
const isLoading = ref(false)

// Tab 2: 100 Phrasal Verbs
const phrasalVerbs100List = ref([])
const selectedLetter = ref(null)
const alphabetLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'K', 'L', 'M', 'P', 'R', 'S', 'T', 'W']

// Tab 3: Methods
const methodsData = ref(null)

// Tab 4: Exercises
const exercisesData = ref(null)
const userAnswers = ref({})
const qResults = ref({})
const showHint = ref({})

// Tab 5: Flashcard
const currentFlashIndex = ref(0)
const isFlipped = ref(false)
const selectedFlashPart = ref('all_1000') // 'all_1000' | 'pv_100' | number 1-17

const setFlashPart = (part) => {
  selectedFlashPart.value = part
  currentFlashIndex.value = 0
  isFlipped.value = false
}

const getFlashPartLabel = () => {
  if (selectedFlashPart.value === 'all_1000') return 'Tất cả 1000 cụm từ'
  if (selectedFlashPart.value === 'pv_100') return '100 Phrasal Verbs'
  return `Phần ${selectedFlashPart.value}`
}

const startFlashcardForPart = (part) => {
  activeMainTab.value = 'flashcard'
  selectedFlashPart.value = part
  currentFlashIndex.value = 0
  isFlipped.value = false
}

const flashcardItems = computed(() => {
  if (selectedFlashPart.value === 'pv_100') {
    return phrasalVerbs100List.value
  }
  if (typeof selectedFlashPart.value === 'number') {
    const list = phrases.value.filter(p => (p.part_number === selectedFlashPart.value) || (Math.ceil(p.id / 50) === selectedFlashPart.value))
    return list.length > 0 ? list : phrases.value
  }
  return phrases.value.length > 0 ? phrases.value : phrasalVerbs100List.value
})

const currentFlashItem = computed(() => {
  if (flashcardItems.value.length === 0) return null
  return flashcardItems.value[currentFlashIndex.value] || null
})

// Fetch Overview Stats
const fetchStats = async () => {
  try {
    const res = await axios.get('/api/phrasal-verbs/stats')
    if (res.data?.data) {
      stats.value = res.data.data
    }
  } catch (e) {
    console.error('Lỗi tải thống kê cụm từ:', e)
  }
}

// Fetch 1000 Phrases (PDF)
const fetchPhrases = async () => {
  isLoading.value = true
  try {
    const params = {
      category: 'common_phrase_1000',
      part_number: selectedPartNumber.value || undefined,
      q: searchQuery.value || undefined,
      status: filterStatus.value !== 'all' ? filterStatus.value : undefined,
    }
    const res = await axios.get('/api/phrasal-verbs', { params })
    phrases.value = res.data.data?.items || []
  } catch (err) {
    console.error('Failed to load common phrases:', err)
  } finally {
    isLoading.value = false
  }
}

// Fetch 100 Phrasal Verbs (Word doc)
const fetchPhrasalVerbs100 = async () => {
  try {
    const params = {
      category: 'phrasal_verb_100',
      type: 'phrasal_verb',
    }
    const res = await axios.get('/api/phrasal-verbs', { params })
    let items = res.data.data?.items || []
    if (selectedLetter.value) {
      items = items.filter(i => (i.phrase || '').toUpperCase().startsWith(selectedLetter.value))
    }
    phrasalVerbs100List.value = items
  } catch (e) {
    console.error('Failed to load 100 phrasal verbs:', e)
  }
}

// Fetch Methods
const fetchMethods = async () => {
  try {
    const res = await axios.get('/api/phrasal-verbs/methods')
    if (res.data?.data) {
      methodsData.value = res.data.data
    }
  } catch (e) {
    console.error('Failed to load methods:', e)
  }
}

// Fetch Exercises
const fetchExercises = async () => {
  try {
    const res = await axios.get('/api/phrasal-verbs/exercises')
    if (res.data?.data) {
      exercisesData.value = res.data.data
    }
  } catch (e) {
    console.error('Failed to load exercises:', e)
  }
}

// Submit Exercise Answer
const submitAnswer = async (key, expected) => {
  const userAns = (userAnswers.value[key] || '').trim()
  if (!userAns) return

  try {
    const res = await axios.post('/api/phrasal-verbs/check-exercise', {
      user_answer: userAns,
      expected_answer: expected,
    })
    qResults.value[key] = res.data.data
    if (res.data.data.is_correct) {
      await fetchStats()
      await authStore.fetchMe()
    }
  } catch (e) {
    // Local fallback matching
    const norm = (s) => (s || '').toLowerCase().replace(/[.,?!:;"'_\-()]/g, ' ').replace(/\b(and|or|with)\b/g, ' ').replace(/\s+/g, ' ').trim()
    const cleanUser = norm(userAns)
    const cleanExp = norm(expected)
    const isCorrect = cleanUser === cleanExp || cleanExp.includes(cleanUser)
    qResults.value[key] = {
      is_correct: isCorrect,
      user_answer: userAns,
      expected_answer: expected,
      similarity_percent: isCorrect ? 100 : 50,
      xp_awarded: isCorrect ? 15 : 0,
    }
  }
}

let searchTimer = null
const debouncedFetch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    fetchPhrases()
  }, 350)
}

const clearSearch = () => {
  searchQuery.value = ''
  fetchPhrases()
}

const selectPart = (part) => {
  selectedPartNumber.value = part
  fetchPhrases()
}

const markPhraseStatus = async (phrase, status) => {
  if (!authStore.isAuthenticated) {
    authStore.promptLogin('Vui lòng đăng nhập để lưu tiến độ học cụm từ và tích lũy điểm số!')
    return
  }

  try {
    await axios.post('/api/phrasal-verbs/status', {
      phrase_id: phrase.id,
      status,
    })
    phrase.status = status
    await fetchStats()
    await authStore.fetchMe()
  } catch (err) {
    console.error('Failed to mark phrase status:', err)
  }
}

const prevFlash = () => {
  if (currentFlashIndex.value > 0) {
    currentFlashIndex.value--
    isFlipped.value = false
  }
}

const nextFlash = () => {
  if (currentFlashIndex.value < flashcardItems.value.length - 1) {
    currentFlashIndex.value++
    isFlipped.value = false
  }
}

const handleKeyDown = (e) => {
  if (activeMainTab.value !== 'flashcard') return
  const tag = e.target.tagName.toLowerCase()
  if (tag === 'input' || tag === 'textarea' || tag === 'select') return
  if (document.querySelector('.modal.show')) return

  if (e.code === 'Space') {
    e.preventDefault()
    isFlipped.value = !isFlipped.value
  } else if (e.key === 'ArrowLeft' || e.key === 'q' || e.key === 'Q') {
    e.preventDefault()
    prevFlash()
  } else if (e.key === 'ArrowRight' || e.key === 'e' || e.key === 'E') {
    e.preventDefault()
    nextFlash()
  } else if (e.key === 'a' || e.key === 'A') {
    e.preventDefault()
    if (currentFlashItem.value?.phrase) {
      speech.speak(currentFlashItem.value.phrase)
    }
  } else if (e.key === 'd' || e.key === 'D') {
    e.preventDefault()
    if (currentFlashItem.value?.phrase) {
      speech.startListening(currentFlashItem.value.phrase, 'flash_' + currentFlashItem.value.id)
    }
  } else if (e.key === 's' || e.key === 'S') {
    e.preventDefault()
    if (currentFlashItem.value) {
      markPhraseStatus(currentFlashItem.value, 'mastered')
    }
  } else if (e.key === 'x' || e.key === 'X') {
    e.preventDefault()
    if (currentFlashItem.value) {
      markPhraseStatus(currentFlashItem.value, 'learning')
    }
  }
}

onMounted(async () => {
  window.addEventListener('keydown', handleKeyDown)
  await Promise.all([
    fetchStats(),
    fetchPhrases(),
    fetchPhrasalVerbs100(),
    fetchMethods(),
    fetchExercises(),
  ])
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
  speech.stopSpeech()
  speech.stopListening()
})
</script>

<style scoped>
.active-pill-teal {
  background: linear-gradient(135deg, #00b074 0%, #064e40 100%) !important;
  color: #ffffff !important;
  box-shadow: 0 4px 12px rgba(0, 176, 116, 0.3) !important;
}

.perspective-1000 {
  perspective: 1000px;
}

.transform-style-preserve-3d {
  transform-style: preserve-3d;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.backface-hidden {
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}

.rotate-y-180 {
  transform: rotateY(180deg);
}
</style>
