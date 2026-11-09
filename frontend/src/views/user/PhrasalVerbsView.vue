<template>
  <div class="container py-3 py-md-4 max-w-6xl">
    <!-- 1. Header Banner -->
    <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-3 mb-md-4" style="border-left: 6px solid #00b074;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-1.5 mb-1.5 flex-wrap">
            <span class="badge bg-teal text-white rounded-pill px-2.5 py-1 fs-8">
              CỤM TỪ & PHRASAL VERBS TOÀN DIỆN
            </span>
            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fs-8 fw-bold">
              100 Cụm Động Từ + 850 Cụm Từ Thông Dụng
            </span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 fs-8">
              Đồng bộ XP & Tiến độ
            </span>
          </div>
          <h2 class="fs-4 fs-md-3 fw-black text-teal mb-1">Kho Cụm Từ & Phương Pháp Học Thực Chiến</h2>
          <p class="text-muted fs-7 mb-0">
            Học trọn bộ 100 cụm động từ thông dụng chia theo nhóm chữ cái (A-W), 3 phương pháp học đột phá, bài tập thực hành kèm đáp án từ giáo trình và 850 mẫu câu đàm thoại chia làm 17 phần.
          </p>
        </div>

        <!-- Progress Overview Badge (Chống vỡ chữ ĐÃ THUỘC trên mọi kích cỡ màn hình) -->
        <div class="d-flex flex-column align-items-md-end gap-1 w-100 w-md-auto shrink-0">
          <div class="d-flex align-items-center gap-2 w-100 justify-content-between justify-content-md-end">
            <!-- Stat 1: Tổng số cụm -->
            <div class="card px-2.5 px-md-3 py-2 rounded-3 border bg-white text-center shadow-xs flex-fill flex-md-initial" style="min-width: 90px;">
              <div class="text-muted fs-8 fw-bold text-uppercase text-nowrap">Tổng số cụm</div>
              <div class="fs-5 fw-black text-teal text-nowrap">{{ stats.total_phrases || 945 }}</div>
            </div>

            <!-- Stat 2: Đã thuộc (text-nowrap triệt để) -->
            <div class="card px-2.5 px-md-3 py-2 rounded-3 border bg-white text-center shadow-xs flex-fill flex-md-initial" style="min-width: 90px;">
              <div class="text-muted fs-8 fw-bold text-uppercase text-nowrap">Đã thuộc</div>
              <div class="fs-5 fw-black text-success text-nowrap">{{ stats.mastered || 0 }}</div>
            </div>

            <!-- Stat 3: Tiến độ -->
            <div class="card px-2.5 px-md-3 py-2 rounded-3 border bg-white text-center shadow-xs flex-fill flex-md-initial" style="min-width: 90px;">
              <div class="text-muted fs-8 fw-bold text-uppercase text-nowrap">Tiến độ</div>
              <div class="fs-5 fw-black text-warning-emphasis text-nowrap">{{ stats.percent || 0 }}%</div>
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

    <!-- 2. Main Tab Navigation (Vuốt ngang mượt mà trên điện thoại, hàng ngang đầy đủ trên desktop) -->
    <div class="card p-2 rounded-4 shadow-sm border bg-card-custom mb-3 mb-md-4">
      <ul v-drag-scroll class="nav nav-pills gap-2 flex-nowrap overflow-x-auto pb-1 no-scrollbar drag-scroll-x w-100">
        <!-- Tab 1: 1000 Cụm từ thông dụng -->
        <li class="nav-item flex-shrink-0">
          <button
            @click="activeMainTab = 'pdf_phrases'"
            class="nav-link py-2 px-3 rounded-pill fw-bold fs-7 d-inline-flex align-items-center gap-2 text-nowrap transition-all border"
            :class="activeMainTab === 'pdf_phrases' ? 'active-pill-teal border-teal' : 'border-light-subtle bg-white text-dark-emphasis'"
          >
            <i class="bi bi-collection-fill"></i>
            <span>1000 Cụm Từ Thông Dụng</span>
            <span class="badge rounded-pill bg-light text-dark ms-1" style="font-size: 10px;">850 câu</span>
          </button>
        </li>

        <!-- Tab 2: 100 Cụm động từ A-W -->
        <li class="nav-item flex-shrink-0">
          <button
            @click="activeMainTab = 'phrasal_verbs_100'"
            class="nav-link py-2 px-3 rounded-pill fw-bold fs-7 d-inline-flex align-items-center gap-2 text-nowrap transition-all border"
            :class="activeMainTab === 'phrasal_verbs_100' ? 'active-pill-teal border-teal' : 'border-light-subtle bg-white text-dark-emphasis'"
          >
            <i class="bi bi-puzzle-fill"></i>
            <span>100 Cụm Động Từ (A-W)</span>
            <span class="badge rounded-pill bg-light text-dark ms-1" style="font-size: 10px;">95 cụm</span>
          </button>
        </li>

        <!-- Tab 3: Phương pháp học -->
        <li class="nav-item flex-shrink-0">
          <button
            @click="activeMainTab = 'methods'"
            class="nav-link py-2 px-3 rounded-pill fw-bold fs-7 d-inline-flex align-items-center gap-2 text-nowrap transition-all border"
            :class="activeMainTab === 'methods' ? 'active-pill-teal border-teal' : 'border-light-subtle bg-white text-dark-emphasis'"
          >
            <i class="bi bi-lightbulb-fill"></i>
            <span>Phương Pháp Đột Phá</span>
          </button>
        </li>

        <!-- Tab 4: Bài tập thực hành -->
        <li class="nav-item flex-shrink-0">
          <button
            @click="activeMainTab = 'exercises'"
            class="nav-link py-2 px-3 rounded-pill fw-bold fs-7 d-inline-flex align-items-center gap-2 text-nowrap transition-all border"
            :class="activeMainTab === 'exercises' ? 'active-pill-teal border-teal' : 'border-light-subtle bg-white text-dark-emphasis'"
          >
            <i class="bi bi-pencil-square"></i>
            <span>Bài Tập Thực Hành (+XP)</span>
          </button>
        </li>

        <!-- Tab 5: Thẻ Flashcard 3D -->
        <li class="nav-item flex-shrink-0">
          <button
            @click="activeMainTab = 'flashcard'"
            class="nav-link py-2 px-3 rounded-pill fw-bold fs-7 d-inline-flex align-items-center gap-2 text-nowrap transition-all border"
            :class="activeMainTab === 'flashcard' ? 'active-pill-teal border-teal' : 'border-light-subtle bg-white text-dark-emphasis'"
          >
            <i class="bi bi-card-text"></i>
            <span>Thẻ Flashcard 3D</span>
          </button>
        </li>
      </ul>
    </div>

    <!-- ================= TAB 1: 1000 CỤM TỪ THÔNG DỤNG (CHIA 17 PHẦN) ================= -->
    <div v-if="activeMainTab === 'pdf_phrases'">
      <!-- Part Selector Carousel / Pills -->
      <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-3">
        <!-- Part Header: Chống vỡ chữ dọc thành 1 chữ/dòng trên mobile -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2.5">
          <span class="fs-8 fw-bold text-uppercase text-muted d-flex align-items-center gap-1.5 text-nowrap">
            <i class="bi bi-signpost-2-fill text-teal fs-7"></i>
            <span>Chọn Phần Học (50 câu / phần):</span>
          </span>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge bg-teal-subtle text-teal fw-bold fs-8 px-2.5 py-1.5 rounded-pill text-nowrap">
              Đang xem: {{ selectedPartNumber ? `Phần ${selectedPartNumber}` : 'Tất cả 17 phần' }}
            </span>
            <button
              v-if="selectedPartNumber"
              @click="startFlashcardForPart(selectedPartNumber)"
              class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8 d-inline-flex align-items-center gap-1.5 shadow-2xs text-nowrap"
            >
              <i class="bi bi-card-text"></i>
              <span>Luyện Thẻ 3D Phần {{ selectedPartNumber }}</span>
            </button>
          </div>
        </div>

        <!-- Part Pills: Nhấn giữ chuột trái kéo qua & vuốt ngang mượt mà (không hiện thanh cuộn thô) -->
        <div v-drag-scroll class="d-flex gap-1.5 overflow-x-auto pb-1 no-scrollbar drag-scroll-x">
          <button
            @click="selectPart(null)"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap border"
            :class="selectedPartNumber === null ? 'btn-teal text-white border-teal' : 'btn-outline-secondary'"
          >
            Tất cả (850 câu)
          </button>
          <button
            v-for="part in 17"
            :key="part"
            @click="selectPart(part)"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap d-flex align-items-center gap-1.5 border"
            :class="selectedPartNumber === part ? 'btn-teal text-white border-teal' : 'btn-outline-secondary'"
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

      <!-- State: Loading -->
      <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-teal" role="status"></div>
        <div class="text-muted fs-8 mt-2">Đang tải danh sách cụm từ...</div>
      </div>

      <!-- State: Empty -->
      <div v-else-if="phrases.length === 0" class="card p-5 rounded-4 text-center border bg-card-custom text-muted mb-4">
        <i class="bi bi-inbox fs-1 d-block mb-2 text-teal"></i>
        <h6 class="fw-bold text-dark">Không tìm thấy cụm từ nào</h6>
        <p class="fs-8 text-muted mb-0">Hãy thử đổi từ khóa tìm kiếm hoặc chọn lại phần học khác.</p>
      </div>

      <!-- GIAO DIỆN THẺ DẠNG TRA CỨU HIỆN ĐẠI (THAY THẾ BẢNG CŨ, ĐÁNH SỐ TỪ #1, MICRO ĐỘC LẬP TỪNG THẺ) -->
      <div v-else class="row g-3 mb-4">
        <div
          v-for="(item, idx) in phrases"
          :key="item.id"
          class="col-12 col-md-6"
        >
          <div
            class="card p-3 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between hover-card transition-all"
            :class="{ 'border-success-subtle bg-success-subtle/10': item.status === 'mastered' }"
          >
            <div>
              <!-- Card Header: Số thứ tự chuẩn từ #1, Phần học, Trạng thái & Nút Nghe / Mic -->
              <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                  <!-- Đánh số thứ tự tuần tự #1, #2... chuẩn xác, loại bỏ hoàn toàn lỗi hiển thị STT 96 -->
                  <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 fs-8 fw-bold">
                    #{{ (selectedPartNumber ? (selectedPartNumber - 1) * 50 : 0) + idx + 1 }}
                  </span>
                  <span class="badge bg-teal-subtle text-teal rounded-pill px-2 py-0.5 fs-8 fw-semibold">
                    Phần {{ item.part_number || Math.ceil(item.id / 50) }}
                  </span>
                  <span
                    class="badge rounded-pill px-2.5 py-1 fs-8 fw-bold"
                    :class="item.status === 'mastered' ? 'bg-success text-white' : item.status === 'learning' ? 'bg-warning text-dark' : 'bg-secondary-subtle text-secondary-emphasis'"
                  >
                    {{ item.status === 'mastered' ? '✓ Đã nhớ' : item.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                  </span>
                </div>

                <!-- Quick Sound (Nghe US) & Independent Mic (Luyện nói riêng từng thẻ) -->
                <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                  <button
                    type="button"
                    @click="speech.speak(item.phrase)"
                    class="btn btn-sm btn-outline-teal rounded-circle p-1.5 d-flex align-items-center justify-content-center shadow-2xs"
                    style="width: 32px; height: 32px;"
                    title="Nghe phát âm chuẩn US"
                  >
                    <i class="bi bi-volume-up-fill fs-7"></i>
                  </button>
                  <button
                    type="button"
                    @click.stop="toggleSpeechForPhrase(item)"
                    class="btn btn-sm rounded-circle p-1.5 d-flex align-items-center justify-content-center shadow-2xs transition-all"
                    :class="activeListeningPhraseId === item.id && speech.isListening.value ? 'btn-danger animate-mic-pulse text-white' : 'btn-outline-info'"
                    style="width: 32px; height: 32px;"
                    :title="activeListeningPhraseId === item.id && speech.isListening.value ? 'Đang lắng nghe... bấm để dừng' : 'Luyện nói câu này'"
                  >
                    <i class="bi" :class="activeListeningPhraseId === item.id && speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
                  </button>
                </div>
              </div>

              <!-- Hộp cảnh báo Đang lắng nghe (Chỉ hiển thị DUY NHẤT trên thẻ người dùng vừa bấm Micro) -->
              <div
                v-if="activeListeningPhraseId === item.id && speech.isListening.value"
                class="alert alert-warning py-1.5 px-2.5 rounded-3 my-2 fs-8 d-flex align-items-center gap-2 border-warning shadow-xs"
              >
                <span class="spinner-grow spinner-grow-sm text-danger flex-shrink-0" role="status"></span>
                <span class="fw-semibold text-dark">
                  Đang nghe... Hãy đọc to: <strong class="text-danger">"{{ item.phrase }}"</strong>
                </span>
              </div>

              <!-- Hộp kết quả chấm điểm phát âm (Chỉ hiển thị trên đúng thẻ đó) -->
              <div
                v-else-if="phraseScoreResults[item.id]"
                class="alert py-1.5 px-2.5 rounded-3 my-2 fs-8 border shadow-xs"
                :class="phraseScoreResults[item.id].score >= 80 ? 'alert-success border-success' : phraseScoreResults[item.id].score >= 50 ? 'alert-warning border-warning' : 'alert-danger border-danger'"
              >
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center gap-1.5 text-truncate me-2">
                    <i class="bi" :class="phraseScoreResults[item.id].score >= 80 ? 'bi-check-circle-fill text-success' : 'bi-exclamation-circle-fill text-warning'"></i>
                    <span class="text-truncate">
                      Bạn đọc: <strong>"{{ phraseScoreResults[item.id].transcript || '...' }}"</strong>
                    </span>
                  </div>
                  <span
                    class="badge flex-shrink-0"
                    :class="phraseScoreResults[item.id].score >= 80 ? 'bg-success text-white' : 'bg-warning text-dark'"
                  >
                    {{ phraseScoreResults[item.id].score }}%
                  </span>
                </div>
              </div>

              <!-- Tiêu đề Cụm từ tiếng Anh & Dịch nghĩa tiếng Việt -->
              <div class="mb-2">
                <h5 class="fw-black text-teal mb-1 leading-snug">{{ item.phrase }}</h5>
                <div class="fw-bold text-success fs-7 mb-1">{{ item.meaning_vi }}</div>
              </div>

              <!-- Câu ví dụ minh họa (nếu có) -->
              <div v-if="item.example_en" class="p-2.5 rounded-3 bg-body-tertiary border mb-2 fs-8">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="badge bg-light text-muted border">Ví dụ thực tế:</span>
                  <button @click="speech.speak(item.example_en)" class="btn btn-sm btn-link p-0 text-teal fs-8 text-decoration-none">
                    <i class="bi bi-volume-up"></i> Nghe
                  </button>
                </div>
                <div class="fw-medium text-dark-emphasis mb-0.5">"{{ item.example_en }}"</div>
                <div v-if="item.example_vi" class="text-muted fst-italic">→ {{ item.example_vi }}</div>
              </div>
            </div>

            <!-- Thao tác đánh dấu: Nút bấm có viền màu bo góc rõ ràng dễ hiểu -->
            <div class="mt-auto pt-2.5 border-top d-flex align-items-center justify-content-between gap-2">
              <button
                @click="markPhraseStatus(item, item.status === 'mastered' ? 'unlearned' : 'mastered')"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 d-inline-flex align-items-center gap-1.5 shadow-2xs transition-all border"
                :class="item.status === 'mastered' ? 'btn-success text-white border-success' : 'btn-outline-success border-success-subtle'"
                title="Đánh dấu đã thuộc cụm từ này"
              >
                <i class="bi" :class="item.status === 'mastered' ? 'bi-check2-circle' : 'bi-check2'"></i>
                <span>{{ item.status === 'mastered' ? 'Đã thuộc ✓' : 'Đánh dấu thuộc' }}</span>
              </button>

              <button
                @click="markPhraseStatus(item, 'learning')"
                class="btn btn-sm rounded-pill px-2.5 py-1.5 fw-semibold fs-8 d-inline-flex align-items-center gap-1 text-nowrap shadow-2xs border"
                :class="item.status === 'learning' ? 'btn-warning text-dark border-warning' : 'btn-outline-warning border-warning-subtle'"
                title="Cần ôn lại cụm từ này"
              >
                <i class="bi bi-arrow-clockwise"></i>
                <span>{{ item.status === 'learning' ? 'Đang ôn' : 'Cần ôn' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= TAB 2: 100 CỤM ĐỘNG TỪ (A-W) ================= -->
    <div v-if="activeMainTab === 'phrasal_verbs_100'">
      <!-- Alphabet Navigator Bar -->
      <div class="card p-3 rounded-4 shadow-sm border bg-card-custom mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fs-8 fw-bold text-uppercase text-muted d-flex align-items-center gap-1.5">
            <i class="bi bi-sort-alpha-down text-teal fs-7"></i>
            <span>Chọn Bảng Chữ Cái (A - W):</span>
          </span>
          <span class="badge bg-teal text-white fw-bold fs-8">
            {{ selectedLetter ? `Nhóm chữ [${selectedLetter}]` : 'Tất cả bảng chữ cái' }}
          </span>
        </div>

        <div v-drag-scroll class="d-flex gap-1.5 overflow-x-auto pb-1 no-scrollbar drag-scroll-x">
          <button
            @click="selectedLetter = null; fetchPhrasalVerbs100()"
            class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap border"
            :class="selectedLetter === null ? 'btn-teal text-white border-teal' : 'btn-outline-secondary'"
          >
            Tất cả (95 cụm)
          </button>
          <button
            v-for="letter in alphabetLetters"
            :key="letter"
            @click="selectedLetter = letter; fetchPhrasalVerbs100()"
            class="btn btn-sm rounded-circle p-0 fw-bold d-flex align-items-center justify-content-center shrink-0 border"
            style="width: 32px; height: 32px;"
            :class="selectedLetter === letter ? 'btn-teal text-white border-teal shadow-xs' : 'btn-outline-secondary'"
          >
            {{ letter }}
          </button>
        </div>
      </div>

      <!-- Phrasal Verbs Cards Grid -->
      <div class="row g-3">
        <div v-for="(pv, idx) in phrasalVerbs100List" :key="pv.id" class="col-12 col-md-6 col-lg-4">
          <div
            class="card p-3 rounded-4 border h-100 shadow-sm transition-all bg-card-custom hover-card d-flex flex-column justify-content-between"
            :class="{ 'border-success bg-success-subtle/10': pv.status === 'mastered' }"
          >
            <div>
              <!-- Card Header: Số thứ tự #1, Chữ cái & Trạng thái -->
              <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                <div class="d-flex align-items-center gap-1.5">
                  <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5 fs-8 fw-bold">
                    #{{ idx + 1 }}
                  </span>
                  <span class="badge bg-teal-subtle text-teal fw-bold fs-8 px-2 py-0.5 rounded-pill">
                    Chữ [{{ (pv.phrase || 'A')[0].toUpperCase() }}]
                  </span>
                </div>
                <span
                  class="badge px-2 py-0.5 rounded-pill fs-8 fw-semibold"
                  :class="pv.status === 'mastered' ? 'bg-success text-white' : pv.status === 'learning' ? 'bg-warning text-dark' : 'bg-light text-muted border'"
                >
                  {{ pv.status === 'mastered' ? '✓ Đã nhớ' : pv.status === 'learning' ? 'Cần ôn' : 'Chưa học' }}
                </span>
              </div>

              <!-- Phrase & Sound -->
              <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <h5 class="fw-black text-teal mb-0 leading-tight">{{ pv.phrase }}</h5>
                <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                  <button
                    @click="speech.speak(pv.phrase)"
                    class="btn btn-sm btn-outline-teal rounded-circle p-1 d-flex align-items-center justify-content-center shadow-2xs"
                    style="width: 30px; height: 30px;"
                    title="Nghe phát âm chuẩn US"
                  >
                    <i class="bi bi-volume-up-fill fs-8"></i>
                  </button>
                  <button
                    type="button"
                    @click.stop="toggleSpeechForPhrase(pv)"
                    class="btn btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center shadow-2xs transition-all"
                    :class="activeListeningPhraseId === pv.id && speech.isListening.value ? 'btn-danger animate-mic-pulse text-white' : 'btn-outline-info'"
                    style="width: 30px; height: 30px;"
                    :title="activeListeningPhraseId === pv.id && speech.isListening.value ? 'Đang lắng nghe... bấm để dừng' : 'Luyện nói cụm từ này'"
                  >
                    <i class="bi" :class="activeListeningPhraseId === pv.id && speech.isListening.value ? 'bi-record-fill' : 'bi-mic-fill'"></i>
                  </button>
                </div>
              </div>

              <!-- Scoped Mic Alert & Result for this Card -->
              <div
                v-if="activeListeningPhraseId === pv.id && speech.isListening.value"
                class="alert alert-warning py-1 px-2 rounded-3 my-1.5 fs-8 d-flex align-items-center gap-2 border-warning"
              >
                <span class="spinner-grow spinner-grow-sm text-danger flex-shrink-0" role="status"></span>
                <span class="fw-semibold text-dark">Đang nghe: <strong class="text-danger">"{{ pv.phrase }}"</strong></span>
              </div>

              <div
                v-else-if="phraseScoreResults[pv.id]"
                class="alert py-1 px-2 rounded-3 my-1.5 fs-8 border"
                :class="phraseScoreResults[pv.id].score >= 80 ? 'alert-success border-success' : 'alert-warning border-warning'"
              >
                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-truncate me-1">Bạn đọc: <strong>"{{ phraseScoreResults[pv.id].transcript || '...' }}"</strong></span>
                  <span class="badge" :class="phraseScoreResults[pv.id].score >= 80 ? 'bg-success text-white' : 'bg-warning text-dark'">
                    {{ phraseScoreResults[pv.id].score }}%
                  </span>
                </div>
              </div>

              <!-- Meaning -->
              <div class="fw-bold text-success fs-7 mb-2 pb-1.5 border-bottom">
                {{ pv.meaning_vi }}
              </div>

              <!-- Component Words Decomposed -->
              <div v-if="pv.component_words_json && pv.component_words_json.length" class="mb-2">
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
              <div v-if="pv.example_en" class="p-2 rounded-3 bg-body-tertiary border mb-2.5 fs-8">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="badge bg-light text-muted border fs-8">Ví dụ:</span>
                  <button @click="speech.speak(pv.example_en)" class="btn btn-sm btn-link p-0 text-teal fs-8 text-decoration-none">
                    <i class="bi bi-volume-up"></i> Nghe
                  </button>
                </div>
                <div class="fw-medium text-dark-emphasis mb-0.5">"{{ pv.example_en }}"</div>
                <div class="text-muted fst-italic">→ {{ pv.example_vi }}</div>
              </div>
            </div>

            <!-- Action Buttons with colored borders -->
            <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between gap-2">
              <button
                @click="markPhraseStatus(pv, pv.status === 'mastered' ? 'unlearned' : 'mastered')"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold fs-8 d-inline-flex align-items-center gap-1.5 text-nowrap shadow-2xs border"
                :class="pv.status === 'mastered' ? 'btn-success text-white border-success' : 'btn-outline-success border-success-subtle'"
              >
                <i class="bi" :class="pv.status === 'mastered' ? 'bi-check2-circle' : 'bi-check2'"></i>
                <span>{{ pv.status === 'mastered' ? 'Đã thuộc ✓' : 'Đánh dấu thuộc' }}</span>
              </button>

              <button
                @click="markPhraseStatus(pv, 'learning')"
                class="btn btn-sm rounded-pill px-2.5 py-1.5 fw-semibold fs-8 d-inline-flex align-items-center gap-1 text-nowrap shadow-2xs border"
                :class="pv.status === 'learning' ? 'btn-warning text-dark border-warning' : 'btn-outline-warning border-warning-subtle'"
              >
                <i class="bi bi-arrow-clockwise"></i>
                <span>{{ pv.status === 'learning' ? 'Đang ôn' : 'Cần ôn' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= TAB 3: PHƯƠNG PHÁP HỌC CỤM TỪ ĐỘT PHÁ ================= -->
    <div v-if="activeMainTab === 'methods'">
      <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
        <div class="text-center max-w-2xl mx-auto mb-4">
          <span class="badge bg-teal-subtle text-teal fw-bold px-3 py-1.5 rounded-pill fs-8 mb-2">
            BÍ QUYẾT TỪ GIÁO TRÌNH
          </span>
          <h3 class="fs-4 fs-md-3 fw-black text-teal mb-2">{{ methodsData?.title || 'Phương Pháp Học Cụm Động Từ Tiếng Anh Thực Chiến' }}</h3>
          <p class="text-muted fs-7 mb-0">{{ methodsData?.description }}</p>
        </div>

        <div class="row g-3 g-md-4">
          <div v-for="method in methodsData?.methods || []" :key="method.id" class="col-12 col-lg-4">
            <div class="card p-3.5 p-md-4 rounded-4 border h-100 shadow-sm bg-white hover-card" style="border-top: 4px solid #00b074 !important;">
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
        <div class="p-3 p-md-4 rounded-4 border bg-body-tertiary mt-4">
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
                  <td>Tách rời, gián đoạn, rời khỏi một vị trí</td>
                  <td><em>take off</em> (cất cánh / cởi đồ), <em>turn off</em> (tắt máy), <em>call off</em> (hủy bỏ)</td>
                </tr>
                <tr>
                  <td class="fw-bold text-teal">UP</td>
                  <td>Hoàn thành triệt để, tiến lên phía trước, gia tăng</td>
                  <td><em>eat up</em> (ăn hết sạch), <em>give up</em> (từ bỏ hoàn toàn), <em>cheer up</em> (vui lên)</td>
                </tr>
                <tr>
                  <td class="fw-bold text-teal">DOWN</td>
                  <td>Giảm bớt, lắng xuống, ghi chép lại hoặc hỏng hóc</td>
                  <td><em>turn down</em> (vặn nhỏ / từ chối), <em>calm down</em> (bình tĩnh lại), <em>break down</em> (hỏng)</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= TAB 4: BÀI TẬP THỰC HÀNH ================= -->
    <div v-if="activeMainTab === 'exercises'">
      <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <div>
            <h4 class="fw-black text-teal mb-1">{{ exercisesData?.title || 'Bài Tập Thực Hành Điền Khuyết Cụm Từ' }}</h4>
            <p class="text-muted fs-7 mb-0">Hoàn thành các câu bài tập để nhận ngay +15 XP cho mỗi câu trả lời chính xác!</p>
          </div>
          <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold fs-7 shadow-xs">
            +15 XP / câu đúng
          </span>
        </div>

        <div class="d-flex flex-column gap-3">
          <div
            v-for="(q, idx) in exercisesData?.questions || []"
            :key="q.id"
            class="p-3.5 rounded-4 border bg-white shadow-2xs"
          >
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="badge bg-teal text-white rounded-pill px-2.5 py-0.5 fs-8 fw-bold">
                Câu {{ idx + 1 }}
              </span>
              <span v-if="qResults[q.id]?.is_correct" class="badge bg-success text-white px-2.5 py-1 rounded-pill fs-8">
                ✓ Chính xác (+15 XP)
              </span>
              <span v-else-if="qResults[q.id]" class="badge bg-danger text-white px-2.5 py-1 rounded-pill fs-8">
                ✕ Chưa chính xác
              </span>
            </div>

            <!-- Question Sentence -->
            <div class="fs-6 fw-bold text-dark-emphasis mb-1">
              {{ q.sentence_en }}
            </div>
            <div class="fs-8 text-muted fst-italic mb-2.5">
              Dịch nghĩa: {{ q.sentence_vi }}
            </div>

            <!-- Input & Check Form -->
            <div class="d-flex gap-2 flex-wrap">
              <input
                v-model="userAnswers[q.id]"
                @keyup.enter="submitAnswer(q.id, q.correct_phrase)"
                type="text"
                class="form-control form-control-sm rounded-pill px-3 py-1.5 fs-7 flex-grow-1"
                placeholder="Nhập cụm từ phù hợp (VD: run out of, give up...)"
                :disabled="qResults[q.id]?.is_correct"
              />
              <button
                @click="submitAnswer(q.id, q.correct_phrase)"
                class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8 d-inline-flex align-items-center gap-1 shadow-2xs text-nowrap"
                :disabled="qResults[q.id]?.is_correct || !userAnswers[q.id]"
              >
                <i class="bi bi-send-fill"></i>
                <span>Kiểm tra</span>
              </button>
              <button
                @click="showHint[q.id] = !showHint[q.id]"
                class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1.5 fs-8 text-nowrap"
                type="button"
              >
                <i class="bi bi-lightbulb"></i>
                <span>Gợi ý</span>
              </button>
            </div>

            <!-- Hint Display -->
            <div v-if="showHint[q.id]" class="mt-2 p-2 rounded-3 bg-warning-subtle text-warning-emphasis fs-8 border border-warning-subtle">
              <i class="bi bi-info-circle me-1"></i>
              Gợi ý: Cụm từ bắt đầu bằng chữ cái <strong>"{{ (q.correct_phrase || '')[0]?.toUpperCase() }}"</strong> (Gồm {{ (q.correct_phrase || '').split(' ').length }} từ).
            </div>

            <!-- Answer Feedback -->
            <div v-if="qResults[q.id]" class="mt-2.5 p-2.5 rounded-3 fs-8" :class="qResults[q.id].is_correct ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'">
              <div v-if="qResults[q.id].is_correct">
                <strong>Tuyệt vời!</strong> Đáp án đúng là: <u>{{ q.correct_phrase }}</u>.
              </div>
              <div v-else>
                <strong>Chưa đúng rồi!</strong> Đáp án chính xác là: <u>{{ q.correct_phrase }}</u>.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= TAB 5: THẺ FLASHCARD 3D TƯƠNG TÁC ================= -->
    <div v-if="activeMainTab === 'flashcard'">
      <div class="card p-3 p-md-4 rounded-4 shadow-sm border bg-card-custom mb-4">
        <!-- Flashcard Header & Part Switcher -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
          <div class="d-flex align-items-center gap-2">
            <h4 class="fw-black text-teal mb-0">Luyện Phản Xạ Thẻ 3D</h4>
            <span class="badge bg-teal text-white rounded-pill px-2.5 py-1 fs-8">
              {{ getFlashPartLabel() }}
            </span>
          </div>

          <div class="dropdown">
            <button
              class="btn btn-sm btn-outline-teal dropdown-toggle rounded-pill px-3 py-1.5 fw-semibold fs-8"
              type="button"
              data-bs-toggle="dropdown"
            >
              <i class="bi bi-filter me-1"></i> Đổi nguồn thẻ ({{ flashcardItems.length }} thẻ)
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-8">
              <li><button class="dropdown-item" @click="setFlashPart('all_1000')">Toàn bộ 1000 cụm từ</button></li>
              <li><button class="dropdown-item" @click="setFlashPart('pv_100')">100 Cụm Động Từ (A-W)</button></li>
              <li><hr class="dropdown-divider"></li>
              <li><h6 class="dropdown-header">Chia theo từng phần (50 câu):</h6></li>
              <li v-for="part in 17" :key="part">
                <button class="dropdown-item" @click="setFlashPart(part)">Phần {{ part }} (50 câu)</button>
              </li>
            </ul>
          </div>
        </div>

        <!-- Unified 3D Flashcard Player (Single Source of Truth) -->
        <FlashcardPlayer
          :words="formattedPhrasalWords"
          :categoryTitle="getFlashPartLabel()"
          :initialIndex="currentFlashIndex"
          @status-updated="handleFlashcardStatusUpdated"
          @update-status="handleFlashcardStatusUpdated"
          @index-changed="idx => currentFlashIndex = idx"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import { useSpeech } from '@/composables/useSpeech'
import { useReviewStore } from '@/stores/reviewStore'
import FlashcardPlayer from '@/components/FlashcardPlayer.vue'

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

// Independent Speech State for individual cards (Fix: không bao giờ bị nhảy mic toàn bộ các thẻ)
const activeListeningPhraseId = ref(null)
const phraseScoreResults = ref({})

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
const flashScoreResult = ref(null)

// Flashcard Part management
const setFlashPart = (part) => {
  selectedFlashPart.value = part
  currentFlashIndex.value = 0
  isFlipped.value = false
  flashScoreResult.value = null
}

const getFlashPartLabel = () => {
  if (selectedFlashPart.value === 'all_1000') return 'Toàn bộ 1000 cụm từ'
  if (selectedFlashPart.value === 'pv_100') return '100 Phrasal Verbs (A-W)'
  return `Phần ${selectedFlashPart.value} (50 câu)`
}

const startFlashcardForPart = (part) => {
  activeMainTab.value = 'flashcard'
  selectedFlashPart.value = part
  currentFlashIndex.value = 0
  isFlipped.value = false
  flashScoreResult.value = null
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

// Unified words format for FlashcardPlayer (Single Source of Truth)
const formattedPhrasalWords = computed(() => {
  const partLabel = getFlashPartLabel()
  return (flashcardItems.value || []).map(p => ({
    id: p.id,
    word: p.phrase,
    term: p.phrase,
    phrase: p.phrase,
    phonetic_us: `/${(p.phrase || '').toLowerCase()}/`,
    meaning_vi: p.meaning_vi || '',
    definition: p.meaning_vi || '',
    example_en: p.example_en || '',
    example_vi: p.example_vi || '',
    part_of_speech: p.part_of_speech || 'Cụm từ',
    category_label: partLabel,
    status: p.status || 'unlearned',
    is_remembered: p.status === 'mastered'
  }))
})

// Unified status update handler emitted from FlashcardPlayer
const handleFlashcardStatusUpdated = ({ wordId, status }) => {
  const item = flashcardItems.value.find(p => p.id === wordId)
  if (item) {
    markPhraseStatus(item, status)
  }
}

// Toggle Speech Recognition for individual Phrase Card
const toggleSpeechForPhrase = (item) => {
  if (activeListeningPhraseId.value === item.id && speech.isListening.value) {
    speech.stopListening()
    activeListeningPhraseId.value = null
    return
  }
  activeListeningPhraseId.value = item.id
  speech.startListening(item.phrase, 'phrase_' + item.id)
}

// Toggle Speech Recognition for 3D Flashcard
const toggleSpeechForFlashcard = () => {
  if (!currentFlashItem.value) return
  if (speech.isListening.value) {
    speech.stopListening()
    return
  }
  speech.startListening(currentFlashItem.value.phrase, 'flash_' + currentFlashItem.value.id)
}

// Watch Speech State to harvest results accurately
watch(() => speech.isListening.value, (isListening) => {
  if (!isListening) {
    // 1. If listening to an individual phrase card
    if (activeListeningPhraseId.value) {
      const phraseId = activeListeningPhraseId.value
      const key = 'phrase_' + phraseId
      const res = speech.itemResults.value[key]
      if (res) {
        phraseScoreResults.value[phraseId] = res
      }
      activeListeningPhraseId.value = null
    }

    // 2. If listening to 3D flashcard
    if (currentFlashItem.value) {
      const flashKey = 'flash_' + currentFlashItem.value.id
      const res = speech.itemResults.value[flashKey]
      if (res) {
        flashScoreResult.value = res
      }
    }
  }
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
  if (!phrase) return
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

    if (status === 'learning') {
      try {
        const reviewStore = useReviewStore()
        reviewStore.addWordToReview({
          word: phrase.phrase,
          meaning_vi: phrase.meaning_vi,
          part_of_speech: 'Cụm từ',
          level: 'Phrasal Verbs',
          example_en: phrase.example_en || '',
          example_vi: phrase.example_vi || '',
        })
      } catch (e) {
        console.warn('Failed to sync phrase to reviewStore:', e)
      }
    }

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
    flashScoreResult.value = null
  }
}

const nextFlash = () => {
  if (currentFlashIndex.value < flashcardItems.value.length - 1) {
    currentFlashIndex.value++
    isFlipped.value = false
    flashScoreResult.value = null
  }
}

const handleKeyDown = (e) => {
  // FlashcardPlayer already handles keyboard shortcuts in flashcard mode
  if (activeMainTab.value === 'flashcard') return
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
    toggleSpeechForFlashcard()
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

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
