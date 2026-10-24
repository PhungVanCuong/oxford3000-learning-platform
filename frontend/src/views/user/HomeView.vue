<template>
  <div>
    <!-- ============================================================== -->
    <!-- VIEW A: GUEST LANDING PAGE (Matches Image media_1792672183705_bc785297.png) -->
    <!-- ============================================================== -->
    <div v-if="!authStore.isAuthenticated" class="landing-page">
      <!-- 1. HERO SECTION (Side-by-side matching Image 1) -->
      <section class="py-5 py-lg-6" style="background: linear-gradient(180deg, #F5F7F8 0%, #FFFFFF 100%);">
        <div class="container max-w-7xl">
          <div class="row align-items-center g-5">
            <!-- Left Column: Headline, Subtitle, CTA buttons, Checks -->
            <div class="col-12 col-lg-7">
              <!-- Top Pill Badge -->
              <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill mb-3" style="background-color: #E6F7F3; border: 1px solid #A3E7D8;">
                <span style="color: #00b074;">✨</span>
                <span class="fw-bold fs-8 uppercase" style="color: #087F75; letter-spacing: 0.5px;">
                  NỀN TẢNG CHINH PHỤC 3000 TỪ VỰNG OXFORD & NGỮ PHÁP 1:1
                </span>
              </div>

              <!-- Huge Bold Headline with Teal Highlight -->
              <h1 class="display-4 fw-black text-dark mb-3 tracking-tight" style="line-height: 1.15; font-size: calc(2rem + 2vw);">
                Bứt phá giới hạn,<br/>
                <span style="color: #087F75;">kiến tạo phản xạ</span> cùng AI<br/>
                thông minh.
              </h1>

              <!-- Subtitle -->
              <p class="fs-6 text-secondary mb-4 leading-relaxed" style="max-width: 580px;">
                Lộ trình học tập và kiểm tra được cá nhân hóa hoàn toàn theo trình độ của bạn. Kết hợp trực tiếp cùng kho 3.351 từ vựng chuẩn Mỹ, 12 thì ngữ pháp và công nghệ AI chấm điểm phát âm để chinh phục mục tiêu nhanh nhất.
              </p>

              <!-- Two Action CTA Buttons -->
              <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
                <router-link
                  to="/register"
                  class="btn text-white rounded-pill px-4 py-3 fw-bold shadow d-flex align-items-center gap-2"
                  style="background-color: #00b074; font-size: 15px;"
                >
                  <span>Đăng ký học viên ngay</span>
                  <i class="bi bi-arrow-right"></i>
                </router-link>

                <router-link
                  to="/login"
                  class="btn btn-outline-secondary bg-white text-dark border px-4 py-3 rounded-pill shadow-sm d-flex align-items-center gap-2 fw-semibold fs-7"
                >
                  <i class="bi bi-shield-check text-success"></i>
                  <span>Đã có tài khoản</span>
                </router-link>
              </div>

              <!-- 3 Green Checkmarks Row (From image) -->
              <div class="d-flex align-items-center gap-3 gap-md-4 flex-wrap fs-8 text-secondary fw-semibold">
                <div class="d-flex align-items-center gap-1.5">
                  <i class="bi bi-check-circle-fill text-success"></i>
                  <span>Giáo án chuẩn Oxford 3000</span>
                </div>
                <div class="d-flex align-items-center gap-1.5">
                  <i class="bi bi-check-circle-fill text-success"></i>
                  <span>Theo dõi tiến độ liên tục</span>
                </div>
                <div class="d-flex align-items-center gap-1.5">
                  <i class="bi bi-check-circle-fill text-success"></i>
                  <span>Trợ lý Gemini AI tận tâm</span>
                </div>
              </div>

              <!-- Quick Jump Pills to Sections: Tính năng, Quy trình, Lợi ích -->
              <div class="d-flex align-items-center gap-2 flex-wrap mt-4 pt-3 border-top border-light-subtle">
                <span class="fs-8 fw-bold text-muted text-uppercase">Khám phá chi tiết:</span>
                <a href="#tinh-nang" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 fs-8 d-inline-flex align-items-center gap-1.5 shadow-xs">
                  <i class="bi bi-grid-fill"></i>
                  <span>Tính năng nổi bật</span>
                </a>
                <a href="#quy-trinh" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 fs-8 d-inline-flex align-items-center gap-1.5 shadow-xs">
                  <i class="bi bi-diagram-3-fill"></i>
                  <span>Quy trình 4 bước</span>
                </a>
                <a href="#loi-ich" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1.5 fs-8 d-inline-flex align-items-center gap-1.5 shadow-xs">
                  <i class="bi bi-trophy-fill"></i>
                  <span>Lợi ích học viên</span>
                </a>
              </div>
            </div>

            <!-- Right Column: Floating Preview Card (Matches Image 1) -->
            <div class="col-12 col-lg-5 position-relative">
              <!-- Top-Left Floating Pill Badge -->
              <div
                class="position-absolute z-2 bg-white px-3 py-1.5 rounded-pill shadow-lg border d-flex align-items-center gap-1.5"
                style="top: -15px; left: 15px;"
              >
                <span class="text-warning">⚡</span>
                <span class="fw-bold fs-8 text-dark">100% Cá nhân hóa</span>
              </div>

              <!-- Main White Card -->
              <div class="card p-4 rounded-4 border shadow-lg bg-white position-relative" style="border-color: #E2E8F0;">
                <!-- Header inside card: User Info + Status Pill -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 42px; height: 42px; background-color: #00b074;">
                      <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <div>
                      <div class="fw-bold fs-7 text-dark">Hồ sơ Học tập Thông minh</div>
                      <div class="text-muted fs-8">Lộ trình: Chinh phục 3000 từ & Giao tiếp</div>
                    </div>
                  </div>

                  <span class="badge px-3 py-1.5 rounded-pill fw-semibold fs-8" style="background-color: #E6F7F3; color: #087F75;">
                    ● Đang học tập
                  </span>
                </div>

                <!-- Progress Section (Target 90 days - 82% completed) -->
                <div class="mb-4">
                  <div class="d-flex justify-content-between align-items-center fs-8 fw-semibold mb-1">
                    <span class="text-secondary">Mục tiêu 90 ngày</span>
                    <span class="fw-bold" style="color: #087F75;">82% Hoàn thành</span>
                  </div>
                  <div class="progress" style="height: 8px; border-radius: 999px; background-color: #E5E9EA;">
                    <div class="progress-bar rounded-pill" role="progressbar" style="width: 82%; background-color: #00b074;"></div>
                  </div>
                </div>

                <!-- 2 Stats Boxes Row -->
                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <div class="p-3 rounded-3 border bg-light-subtle d-flex align-items-center gap-2.5">
                      <div class="fs-4 text-danger">🔥</div>
                      <div>
                        <div class="fw-black fs-6 text-dark leading-tight">2,450 từ</div>
                        <div class="text-muted fs-8">Đã ghi nhớ</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-6">
                    <div class="p-3 rounded-3 border bg-light-subtle d-flex align-items-center gap-2.5">
                      <div class="fs-4 text-primary">📅</div>
                      <div>
                        <div class="fw-black fs-6 text-dark leading-tight">24 ngày</div>
                        <div class="text-muted fs-8">Chuỗi học liên tục</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Highlight Box: AI & Native Accent -->
                <div class="p-3 rounded-3 border d-flex align-items-center gap-3" style="background-color: #E6F7F3; border-color: #A3E7D8;">
                  <div class="rounded-circle d-flex align-items-center justify-content-center text-white shrink-0" style="width: 36px; height: 36px; background-color: #00b074;">
                    <i class="bi bi-award-fill"></i>
                  </div>
                  <div>
                    <div class="fw-bold fs-7" style="color: #087F75;">Trợ Lý AI & Giọng Mỹ Chuẩn Bản Xứ</div>
                    <div class="text-secondary fs-8">Đánh giá 5.0 ★ • Chứng chỉ Oxford C1</div>
                  </div>
                </div>
              </div>

              <!-- Bottom-Right Floating Pill Badge -->
              <div
                class="position-absolute z-2 bg-white px-3 py-1.5 rounded-pill shadow-lg border d-flex align-items-center gap-1.5"
                style="bottom: -15px; right: 15px;"
              >
                <span class="text-danger">❤️</span>
                <span class="fw-bold fs-8 text-dark">Tự tin giao tiếp & Thi cử</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- 2. TÍNH NĂNG NỔI BẬT (#tinh-nang) -->
      <section id="tinh-nang" class="py-5 bg-white border-top border-bottom">
        <div class="container max-w-7xl">
          <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-1.5 fw-semibold mb-2" style="background-color: #E6F7F3; color: #087F75;">
              HỆ SINH THÁI TOÀN DIỆN
            </span>
            <h2 class="display-6 fw-black text-dark mb-2">Tất Cả Công Cụ Bạn Cần Để Làm Chủ Tiếng Anh</h2>
            <p class="text-muted fs-7 max-w-xl mx-auto">
              Không dàn trải, hệ thống tập trung vào đúng 3.000 từ vựng cốt lõi chiếm 90% giao tiếp thường nhật cùng ngữ pháp thực chiến.
            </p>
          </div>

          <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-12 col-md-4">
              <div class="card p-4 rounded-4 border shadow-sm h-100 hover-card">
                <div class="rounded-3 d-inline-flex p-3 text-white mb-3 shadow-sm" style="background-color: #00b074; width: 48px; height: 48px;">
                  <i class="bi bi-calendar3 fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">3.351 Từ Vựng Theo Ngày</h5>
                <p class="text-muted fs-7 mb-3">
                  Tự chọn 10, 15, 20 từ mỗi ngày. Học qua thẻ Flashcard 3D lật 2 mặt, câu ví dụ song ngữ và phát âm bản xứ.
                </p>
                <router-link to="/daily" class="text-teal fw-bold fs-7 text-decoration-none mt-auto">
                  Khám phá lộ trình theo ngày →
                </router-link>
              </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-12 col-md-4">
              <div class="card p-4 rounded-4 border shadow-sm h-100 hover-card">
                <div class="rounded-3 d-inline-flex p-3 text-white mb-3 shadow-sm" style="background-color: #087F75; width: 48px; height: 48px;">
                  <i class="bi bi-diagram-3 fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">25 Chủ Đề Thực Chiến</h5>
                <p class="text-muted fs-7 mb-3">
                  Học từ vựng theo ngữ cảnh du lịch, công sở, thương mại, sức khỏe và công nghệ. Tăng khả năng ghi nhớ theo cụm.
                </p>
                <router-link to="/topics" class="text-teal fw-bold fs-7 text-decoration-none mt-auto">
                  Xem 25 chủ đề →
                </router-link>
              </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-12 col-md-4">
              <div class="card p-4 rounded-4 border shadow-sm h-100 hover-card">
                <div class="rounded-3 d-inline-flex p-3 text-white mb-3 shadow-sm" style="background-color: #059669; width: 48px; height: 48px;">
                  <i class="bi bi-puzzle fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Học Theo Cụm Từ & Phrasal Verbs</h5>
                <p class="text-muted fs-7 mb-3">
                  Ghép từ đơn thành cụm từ mang nghĩa hoàn chỉnh. Khi thuộc cụm từ, hệ thống tự động cộng điểm cho các từ đơn.
                </p>
                <router-link to="/phrasal-verbs" class="text-teal fw-bold fs-7 text-decoration-none mt-auto">
                  Học cụm từ ngay →
                </router-link>
              </div>
            </div>

            <!-- Feature 4 -->
            <div class="col-12 col-md-4">
              <div class="card p-4 rounded-4 border shadow-sm h-100 hover-card">
                <div class="rounded-3 d-inline-flex p-3 text-white mb-3 shadow-sm" style="background-color: #2563eb; width: 48px; height: 48px;">
                  <i class="bi bi-soundwave fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">44 Âm Chuẩn Quốc Tế IPA</h5>
                <p class="text-muted fs-7 mb-3">
                  Bảng nguyên âm đơn, đôi và phụ âm chuẩn Mỹ. Hướng dẫn khẩu hình môi lưỡi và thu âm chấm điểm qua Micro.
                </p>
                <router-link to="/ipa" class="text-teal fw-bold fs-7 text-decoration-none mt-auto">
                  Luyện 44 âm IPA →
                </router-link>
              </div>
            </div>

            <!-- Feature 5 -->
            <div class="col-12 col-md-4">
              <div class="card p-4 rounded-4 border shadow-sm h-100 hover-card">
                <div class="rounded-3 d-inline-flex p-3 text-white mb-3 shadow-sm" style="background-color: #d97706; width: 48px; height: 48px;">
                  <i class="bi bi-patch-check-fill fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">100% Trắc Nghiệm Thông Minh</h5>
                <p class="text-muted fs-7 mb-3">
                  Bỏ hoàn toàn việc gõ phím mệt mỏi. Bài thi 15 câu trắc nghiệm 4 lựa chọn (A, B, C, D) có phản hồi và giải thích tức thì.
                </p>
                <router-link to="/daily" class="text-teal fw-bold fs-7 text-decoration-none mt-auto">
                  Thử làm bài kiểm tra →
                </router-link>
              </div>
            </div>

            <!-- Feature 6 -->
            <div class="col-12 col-md-4">
              <div class="card p-4 rounded-4 border shadow-sm h-100 hover-card">
                <div class="rounded-3 d-inline-flex p-3 text-white mb-3 shadow-sm" style="background-color: #7c3aed; width: 48px; height: 48px;">
                  <i class="bi bi-stars fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Trợ Lý Gemini AI Đồng Hành</h5>
                <p class="text-muted fs-7 mb-3">
                  Tự động sinh câu ví dụ song ngữ tự nhiên, phân tích ngữ cảnh sử dụng và sửa lỗi chính tả ngữ pháp chi tiết.
                </p>
                <router-link to="/dictionary" class="text-teal fw-bold fs-7 text-decoration-none mt-auto">
                  Khám phá AI giải thích →
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- 3. QUY TRÌNH HỌC TẬP (#quy-trinh) -->
      <section id="quy-trinh" class="py-5" style="background-color: #F5F7F8;">
        <div class="container max-w-7xl">
          <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-1.5 fw-semibold mb-2" style="background-color: #E6F7F3; color: #087F75;">
              TRIẾT LÝ HỌC TẬP
            </span>
            <h2 class="display-6 fw-black text-dark mb-2">Xây Dựng Kiến Thức Trước → Kiểm Tra Sau</h2>
            <p class="text-muted fs-7 max-w-xl mx-auto">
              Quy trình 4 bước khoa học giúp bạn ghi nhớ từ vựng sâu vào tiềm thức mà không bị quên sau vài ngày.
            </p>
          </div>

          <div class="row g-4">
            <div class="col-6 col-lg-3">
              <div class="card p-4 rounded-4 border shadow-sm bg-white text-center h-100">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3 mx-auto" style="width: 44px; height: 44px; background-color: #00b074;">
                  1
                </div>
                <h6 class="fw-bold text-dark mb-2">Thẻ Flashcard 3D</h6>
                <p class="text-muted fs-8 mb-0">Học từ tiếng Anh, phiên âm US và ví dụ song ngữ trước khi lật mặt sau xem nghĩa.</p>
              </div>
            </div>

            <div class="col-6 col-lg-3">
              <div class="card p-4 rounded-4 border shadow-sm bg-white text-center h-100">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3 mx-auto" style="width: 44px; height: 44px; background-color: #087F75;">
                  2
                </div>
                <h6 class="fw-bold text-dark mb-2">Luyện Phát Âm Chuẩn</h6>
                <p class="text-muted fs-8 mb-0">Nghe giọng đọc chuẩn bản xứ US và bật Micro luyện nói chấm điểm độ chính xác %.</p>
              </div>
            </div>

            <div class="col-6 col-lg-3">
              <div class="card p-4 rounded-4 border shadow-sm bg-white text-center h-100">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3 mx-auto" style="width: 44px; height: 44px; background-color: #2563eb;">
                  3
                </div>
                <h6 class="fw-bold text-dark mb-2">Trắc Nghiệm 15 Câu</h6>
                <p class="text-muted fs-8 mb-0">Làm bài kiểm tra trắc nghiệm 4 lựa chọn (A, B, C, D) củng cố phản xạ tức thì.</p>
              </div>
            </div>

            <div class="col-6 col-lg-3">
              <div class="card p-4 rounded-4 border shadow-sm bg-white text-center h-100">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3 mx-auto" style="width: 44px; height: 44px; background-color: #d97706;">
                  4
                </div>
                <h6 class="fw-bold text-dark mb-2">Nuôi Cây Tri Thức</h6>
                <p class="text-muted fs-8 mb-0">Tích lũy điểm XP, nuôi lớn Cây Hạt Giống qua 6 giai đoạn và giữ chuỗi Streak mỗi ngày.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- 4. LỢI ÍCH & CTA CUỐI TRANG (#loi-ich) -->
      <section id="loi-ich" class="py-5 bg-white border-top">
        <div class="container max-w-7xl">
          <div class="card rounded-4 border-0 p-5 text-white position-relative overflow-hidden shadow-lg" style="background: linear-gradient(135deg, #042f2e 0%, #064e40 60%, #00b074 100%);">
            <div class="row align-items-center position-relative z-1">
              <div class="col-12 col-lg-8">
                <span class="badge rounded-pill px-3 py-1.5 fw-semibold mb-2" style="background-color: rgba(94, 234, 212, 0.2); color: #5eead4;">
                  SẴN SÀNG CHINH PHỤC TIẾNG ANH?
                </span>
                <h2 class="display-6 fw-black mb-3">Tất Cả 3.351 Từ Vựng Đang Đợi Bạn Chinh Phục</h2>
                <p class="fs-7 text-white-75 mb-4" style="max-width: 600px;">
                  Không cần trả phí hay chờ đợi mở từng bài. Hãy bắt đầu ngay hôm nay để xây dựng vốn từ vựng nền tảng vững chắc nhất!
                </p>
                <div class="d-flex gap-3 flex-wrap">
                  <router-link
                    to="/register"
                    class="btn btn-light rounded-pill px-4 py-2.5 fw-bold shadow"
                    style="color: #064e40;"
                  >
                    Tạo tài khoản học viên miễn phí →
                  </router-link>
                  <router-link
                    to="/dictionary"
                    class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-semibold fs-7"
                  >
                    Xem thử kho từ vựng Oxford
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- ============================================================== -->
    <!-- VIEW B: AUTHENTICATED LEARNER DASHBOARD (When Logged In) -->
    <!-- ============================================================== -->
    <div v-else class="container py-4 max-w-6xl">
      <!-- Hero Banner with Deep Teal styling -->
      <div class="rounded-4 p-4 p-md-5 mb-4 shadow-sm text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #064e40 0%, #085346 50%, #00b074 100%);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 position-relative z-1">
          <div>
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="badge rounded-pill px-3 py-1 fs-8 fw-bold" style="background-color: rgba(94, 234, 212, 0.2); color: #5eead4; border: 1px solid rgba(94, 234, 212, 0.4);">
                OXFORD 3000™ • HỌC THÔNG MINH QUIZLET
              </span>
              <span class="badge bg-white text-dark rounded-pill px-2.5 py-0.5 fs-8 fw-bold">
                MỞ KHÓA 100%
              </span>
            </div>
            <h1 class="display-5 fw-black mb-2 tracking-tight">
              Xin chào, {{ authStore.userName }}! 👋
            </h1>
            <p class="fs-6 text-white-50 max-w-xl mb-4 leading-relaxed">
              Triết lý cốt lõi: <strong>Xây dựng kiến thức trước → Làm bài kiểm tra sau</strong>. Học qua Flashcard 3D, Nghe giọng Mỹ, Luyện nói chấm điểm và trắc nghiệm 15 câu.
            </p>
            <div class="d-flex gap-3 flex-wrap">
              <router-link to="/curriculum" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-bold shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-signpost-split-fill fs-5"></i>
                <span>Lộ trình 25 Chủ đề Mới (5.000 từ)</span>
              </router-link>
              <router-link to="/daily" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold shadow-sm d-flex align-items-center gap-2" style="color: #064e40;">
                <i class="bi bi-play-circle-fill fs-5"></i>
                <span>Học theo Ngày (3000 từ)</span>
              </router-link>
              <router-link to="/dictionary" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-semibold fs-7 d-flex align-items-center gap-2">
                <i class="bi bi-search"></i>
                <span>Tra cứu Từ Vựng</span>
              </router-link>
            </div>
          </div>

          <!-- Right Quick Pill: Accurate Streak & Role -->
          <div class="bg-black bg-opacity-25 backdrop-blur p-4 rounded-4 border border-white-50 text-center shadow" style="min-width: 210px;">
            <div class="fs-1 fw-black text-warning mb-1">
              🔥 {{ currentStreak }} ngày
            </div>
            <div class="fs-7 text-white-50 fw-semibold mb-2">Chuỗi ngày học liên tục</div>
            <div class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
              {{ authStore.isAdmin ? 'Quản trị viên (Admin)' : 'Học viên Oxford' }}
            </div>
          </div>
        </div>
      </div>

      <!-- SPOTLIGHT: LỘ TRÌNH 25 CHỦ ĐỀ CHUYÊN SÂU (MỚI) -->
      <div class="card border-0 rounded-4 shadow-sm mb-5 text-white overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 40%, #087F75 100%);">
        <div class="card-body p-4 p-md-5 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
          <div style="max-width: 660px;">
            <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-20 px-3 py-1 rounded-pill mb-3 fs-8 fw-bold">
              <i class="bi bi-stars text-warning"></i>
              <span>LỘ TRÌNH 25 CHỦ ĐỀ CHUYÊN SÂU • 5.000 TỪ VỰNG & CỤM TỪ</span>
            </div>
            <h2 class="display-6 fw-black mb-2 text-white">Chinh Phục 25 Chủ Đề (10 Giai Đoạn)</h2>
            <p class="fs-7 text-white-75 mb-0" style="line-height: 1.6;">
              Mỗi chủ đề chia làm 10 giai đoạn tăng dần: Chặng 1 học 10 thẻ từ -> Chặng 2-10 làm bài kiểm tra trắc nghiệm 12 đến 28 câu (đúng đủ 200 câu/chủ đề). Mở khóa 100% tự do học bài!
            </p>
          </div>
          <div class="d-flex align-items-center gap-3 flex-shrink-0">
            <router-link to="/curriculum" class="btn btn-warning text-dark px-4 py-3 rounded-pill fw-bold shadow-lg d-flex align-items-center gap-2 fs-7 text-nowrap">
              <i class="bi bi-signpost-split-fill"></i>
              <span>Vào Lộ trình 25 Chủ đề</span>
              <i class="bi bi-arrow-right"></i>
            </router-link>
          </div>
        </div>
      </div>

      <!-- ROW 1: MINI CÂY TRI THỨC (30 CẤP ĐỘ) & TẦN SUẤT HỌC TRONG TUẦN (SỐ PHÚT & SỐ LẦN HỌC) -->
      <div class="row g-4 mb-4">
        <!-- 1. CÂY TRI THỨC THU NHỎ (SVG 30 CẤP ĐỘ) -->
        <div class="col-12 col-lg-6">
          <div class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 position-relative overflow-hidden d-flex flex-column justify-content-between">
            <div>
              <!-- Mini Header -->
              <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-xs" style="width: 32px; height: 32px; background: linear-gradient(135deg, #00b074 0%, #087F75 100%); font-size: 14px;">
                    {{ currentTreeLevel.level }}
                  </div>
                  <div>
                    <h6 class="fw-black text-teal mb-0">Cây Tri Thức: {{ currentTreeLevel.name }}</h6>
                    <span class="text-muted fs-8">Cấp {{ currentTreeLevel.level }}/30 • {{ userXp }} XP</span>
                  </div>
                </div>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-8 fw-semibold">
                  {{ currentTreeLevel.stageDesc }}
                </span>
              </div>

              <!-- Mini SVG Tree Visualizer -->
              <div class="rounded-3 p-2 mb-3 position-relative overflow-hidden text-center" style="background: linear-gradient(180deg, #e0f2fe 0%, #dcfce7 65%, #bbf7d0 100%); height: 160px;">
                <svg viewBox="0 0 500 480" class="w-100 h-100" style="max-height: 150px;" xmlns="http://www.w3.org/2000/svg">
                  <!-- Ground Mound -->
                  <ellipse cx="250" cy="460" rx="220" ry="35" fill="#4ade80" />
                  <ellipse cx="250" cy="460" rx="190" ry="25" fill="#22c55e" />
                  <ellipse cx="250" cy="445" rx="60" ry="15" fill="#78350f" opacity="0.85" />

                  <!-- Stage 1-2: Hạt mầm -->
                  <g v-if="currentTreeLevel.level <= 2">
                    <circle cx="250" cy="435" r="16" fill="#5c2c16" stroke="#451a03" stroke-width="2" />
                    <path d="M 250 435 Q 252 415 250 405" stroke="#84cc16" stroke-width="5" stroke-linecap="round" fill="none" />
                    <circle v-if="currentTreeLevel.level === 2" cx="250" cy="403" r="6" fill="#a3e635" />
                  </g>

                  <!-- Stage 3-5: Chồi non & 2 lá -->
                  <g v-else-if="currentTreeLevel.level <= 5">
                    <path d="M 250 445 Q 248 385 250 340" stroke="#65a30d" stroke-width="8" stroke-linecap="round" fill="none" />
                    <path d="M 250 340 Q 215 320 210 340 Q 225 350 250 340" fill="#4ade80" />
                    <path d="M 250 340 Q 285 320 290 340 Q 275 350 250 340" fill="#22c55e" />
                    <g v-if="currentTreeLevel.level >= 4">
                      <path d="M 250 365 Q 220 355 215 370 Q 230 375 250 365" fill="#86efac" />
                      <path d="M 250 365 Q 280 355 285 370 Q 270 375 250 365" fill="#4ade80" />
                    </g>
                  </g>

                  <!-- Stage 6-30: Growing Tree to Cosmic Tree -->
                  <g v-else>
                    <!-- Vines (Level >= 11) -->
                    <g v-if="currentTreeLevel.level >= 11">
                      <path d="M 235 440 Q 270 370 240 310 Q 265 250 250 190" stroke="#16a34a" stroke-width="6" fill="none" stroke-linecap="round" opacity="0.9" />
                      <circle cx="245" cy="340" r="7" fill="#86efac" />
                      <circle cx="258" cy="270" r="8" fill="#4ade80" />
                    </g>

                    <!-- Trunk -->
                    <path
                      :d="treeTrunkPath"
                      :fill="currentTreeLevel.level >= 26 ? '#581c87' : (currentTreeLevel.level >= 20 ? '#451a03' : '#78350f')"
                      :stroke="currentTreeLevel.level >= 26 ? '#facc15' : '#451a03'"
                      stroke-width="3"
                    />

                    <!-- Canopy Foliage -->
                    <circle :cx="250" :cy="treeFoliageTop" :r="treeFoliageRadius" :fill="treeFoliageColor1" opacity="0.92" />
                    <circle :cx="200 - treeFoliageSpread" :cy="treeFoliageTop + 35" :r="treeFoliageRadius * 0.85" :fill="treeFoliageColor2" opacity="0.94" />
                    <circle :cx="300 + treeFoliageSpread" :cy="treeFoliageTop + 35" :r="treeFoliageRadius * 0.85" :fill="treeFoliageColor1" opacity="0.94" />
                    <circle :cx="250" :cy="treeFoliageTop + 65" :r="treeFoliageRadius * 0.95" :fill="treeFoliageColor3" opacity="0.96" />

                    <!-- Blooming Flowers (Level >= 12) -->
                    <g v-if="currentTreeLevel.level >= 12">
                      <circle cx="210" cy="180" r="9" fill="#f43f5e" />
                      <circle cx="210" cy="180" r="4" fill="#fef08a" />
                      <circle cx="280" cy="170" r="9" fill="#ec4899" />
                      <circle cx="280" cy="170" r="4" fill="#fef08a" />
                      <circle cx="240" cy="210" r="8" fill="#f43f5e" />
                      <circle cx="240" cy="210" r="3" fill="#fef08a" />
                    </g>

                    <!-- Hanging Red/Golden Fruits (Level >= 17) -->
                    <g v-if="currentTreeLevel.level >= 17">
                      <circle cx="190" cy="200" r="10" fill="#dc2626" stroke="#991b1b" stroke-width="1.5" />
                      <circle cx="290" cy="190" r="10" :fill="currentTreeLevel.level >= 24 ? '#eab308' : '#ef4444'" stroke="#b45309" stroke-width="1.5" />
                      <circle cx="230" cy="250" r="10" fill="#dc2626" stroke="#991b1b" stroke-width="1.5" />
                      <circle cx="270" cy="240" r="10" :fill="currentTreeLevel.level >= 24 ? '#f59e0b' : '#dc2626'" stroke="#b45309" stroke-width="1.5" />
                    </g>
                  </g>
                </svg>

                <div class="position-absolute bottom-0 start-0 w-100 px-3 pb-1.5 d-flex justify-content-between align-items-center fs-8 text-dark-emphasis fw-bold bg-white bg-opacity-75">
                  <span>{{ currentTreeLevel.name }}</span>
                  <span class="text-teal">{{ userXp }} / {{ nextLevelThresholdXp }} XP</span>
                </div>
              </div>

              <!-- XP Progress Bar towards next level -->
              <div class="mb-2">
                <div class="d-flex justify-content-between align-items-center fs-8 fw-semibold mb-1">
                  <span class="text-teal">Tiến độ lên Cấp {{ Math.min(30, currentTreeLevel.level + 1) }}</span>
                  <span class="text-success fw-bold">{{ treeLevelProgressPercent }}%</span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 999px;">
                  <div
                    class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                    role="progressbar"
                    :style="{ width: treeLevelProgressPercent + '%' }"
                  ></div>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center text-muted fs-8 mb-3">
                <span>🔥 Chuỗi: <strong>{{ currentStreak }} ngày</strong></span>
                <span class="text-warning-emphasis fw-bold">
                  Còn {{ Math.max(0, nextLevelThresholdXp - userXp) }} XP để tiến hóa!
                </span>
              </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="d-flex gap-2 pt-2 border-top">
              <button
                @click="quickWaterTree"
                type="button"
                class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-bold fs-8 d-flex align-items-center gap-1.5 shadow-xs"
                :disabled="quickWatered"
              >
                <span>💧</span>
                <span>{{ quickWatered ? 'Đã tưới nước' : 'Tưới nước (+5 XP)' }}</span>
              </button>
              <router-link to="/tree" class="btn btn-sm btn-teal text-white rounded-pill px-3 py-1.5 fw-bold fs-8 flex-grow-1 text-center shadow-xs">
                🌱 Vào Vườn Cây Tri Thức (30 Cấp) →
              </router-link>
            </div>
          </div>
        </div>

        <!-- 2. TẦN SUẤT HỌC & SỐ LẦN HỌC TRONG TUẦN (MATCHING ACTIVITY STATS) -->
        <div class="col-12 col-lg-6">
          <div class="card p-3.5 p-md-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
            <div>
              <!-- Header with Filters -->
              <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div>
                  <h6 class="fw-black text-dark-emphasis mb-0">Tần Suất & Thời Lượng Học</h6>
                  <span class="text-muted fs-8">So sánh thời gian học T2 - CN giữa 2 tuần</span>
                </div>

                <!-- Filter Buttons -->
                <div class="btn-group btn-group-sm p-0.5 rounded-pill bg-body-tertiary border" role="group">
                  <button
                    v-for="flt in statsFilterOptions"
                    :key="flt.key"
                    @click="activeStatsFilter = flt.key"
                    type="button"
                    class="btn rounded-pill px-2 py-0.5 fs-8 fw-bold border-0"
                    :class="activeStatsFilter === flt.key ? 'btn-teal text-white shadow-2xs' : 'btn-link text-muted text-decoration-none'"
                  >
                    {{ flt.label }}
                  </button>
                </div>
              </div>

              <!-- Indicators: Số phút, XP, Số lượt học -->
              <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                <div class="d-flex align-items-baseline gap-2">
                  <span class="fs-4 fw-black text-teal">{{ currentStatsMinutes }}</span>
                  <span class="fs-8 fw-bold text-muted">Phút</span>
                  <span class="text-secondary">•</span>
                  <span class="fs-5 fw-bold text-warning">{{ currentStatsXp }}</span>
                  <span class="fs-8 fw-bold text-muted">XP</span>
                  <span class="text-secondary">•</span>
                  <span class="fs-6 fw-bold text-primary">{{ currentStatsSessions }}</span>
                  <span class="fs-8 fw-bold text-muted">Lượt học</span>
                </div>

                <!-- Legend -->
                <div class="d-flex align-items-center gap-2 fs-8 fw-semibold">
                  <div class="d-flex align-items-center gap-1">
                    <span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: #cbd5e1;"></span>
                    <span class="text-muted">Tuần trước</span>
                  </div>
                  <div class="d-flex align-items-center gap-1">
                    <span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: #00b074;"></span>
                    <span class="text-teal fw-bold">Tuần này</span>
                  </div>
                </div>
              </div>

              <!-- Dual-Bar Chart Container (Compact height 135px) -->
              <div class="weekly-bars-container d-flex align-items-end justify-content-between pt-3 pb-1 px-1 bg-body-tertiary rounded-3 border mb-2" style="height: 145px;">
                <div
                  v-for="item in weeklyStatsBars"
                  :key="item.day_code"
                  class="day-bar-column d-flex flex-column align-items-center h-100 justify-content-end"
                  style="width: 13%;"
                >
                  <!-- Bars Pair -->
                  <div class="bars-pair d-flex align-items-end justify-content-center gap-1 h-100 w-100">
                    <!-- Last Week Bar (Gray) -->
                    <div
                      class="bar-single bg-secondary-subtle rounded-top transition-all"
                      :style="{ height: getStatsBarHeight(item.last_week_minutes) + '%' }"
                      :title="'Tuần trước: ' + item.last_week_minutes + ' phút'"
                      style="width: 10px; background-color: #cbd5e1 !important;"
                    ></div>

                    <!-- This Week Bar (Teal) -->
                    <div
                      class="bar-single rounded-top transition-all shadow-xs"
                      :style="{ height: getStatsBarHeight(item.this_week_minutes) + '%' }"
                      :title="'Tuần này: ' + item.this_week_minutes + ' phút'"
                      style="width: 10px; background-color: #00b074 !important;"
                    ></div>
                  </div>

                  <!-- Day Label -->
                  <div class="day-label mt-1 fs-8 fw-bold" :class="item.day_code === currentDayCode ? 'text-teal' : 'text-muted'">
                    {{ item.day_code }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer Link to Full Activity Report -->
            <div class="d-flex justify-content-between align-items-center pt-2 border-top fs-8">
              <span class="text-muted">Đóng góp GitHub: <strong>214 ngày</strong></span>
              <router-link to="/stats" class="text-teal fw-bold text-decoration-none">
                Xem Báo Cáo Năng Suất Chi Tiết →
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- ROW 2: TIẾN ĐỘ KHÓA HỌC & NGỮ PHÁP (DỜI XUỐNG DƯỚI THEO YÊU CẦU CỦA BẠN) -->
      <div class="row g-4 mb-5">
        <!-- Tiến độ 3000 từ vựng Oxford -->
        <div class="col-12 col-lg-6">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fs-8 fw-semibold text-uppercase">Tiến độ Bộ từ vựng Oxford 3000™</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-8">
                  3.351 từ gốc
                </span>
              </div>
              <div class="d-flex align-items-baseline gap-2 mb-2">
                <span class="display-6 fw-black text-teal">{{ overallMasteredWords }}</span>
                <span class="fs-6 text-muted">/ 3.351 từ đã thuộc</span>
                <span class="badge bg-success ms-auto fs-7">{{ overallPercent }}%</span>
              </div>
              <div class="progress mb-3" style="height: 10px; border-radius: 6px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: overallPercent + '%', backgroundColor: '#00b074' }"
                ></div>
              </div>
              <div class="d-flex justify-content-between text-muted fs-8 mb-2">
                <span>Mục tiêu mỗi ngày: <strong>20 từ</strong></span>
                <span>Còn lại: <strong>{{ Math.max(0, 3351 - overallMasteredWords) }} từ</strong></span>
              </div>
            </div>

            <div class="pt-3 border-top mt-2">
              <router-link to="/daily" class="btn btn-sm btn-outline-teal rounded-pill px-4 py-2 w-100 fw-bold shadow-xs">
                Tiếp tục bài học hôm nay →
              </router-link>
            </div>
          </div>
        </div>

        <!-- Tiến độ Ngữ pháp & Luyện Dịch Thực Chiến -->
        <div class="col-12 col-lg-6">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fs-8 fw-semibold text-uppercase">Ngữ Pháp & Luyện Dịch AI</span>
                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2.5 py-1 fs-8">
                  12 Thì & Chuyên đề
                </span>
              </div>
              <div class="d-flex align-items-baseline gap-2 mb-2">
                <span class="display-6 fw-black text-teal">{{ completedGrammars }}</span>
                <span class="fs-6 text-muted">/ 15 bài học & dịch câu</span>
                <span class="badge bg-teal text-white ms-auto fs-7">{{ Math.round((completedGrammars / 15) * 100) }}%</span>
              </div>
              <div class="progress mb-3" style="height: 10px; border-radius: 6px;">
                <div
                  class="progress-bar bg-teal rounded-pill"
                  role="progressbar"
                  :style="{ width: ((completedGrammars / 15) * 100) + '%' }"
                ></div>
              </div>
              <div class="d-flex justify-content-between text-muted fs-8 mb-2">
                <span>AI Chấm điểm: <strong>Đạt >=80% tính điểm</strong></span>
                <span>Bài tập PDF: <strong>245+ câu dịch</strong></span>
              </div>
            </div>

            <div class="pt-3 border-top mt-2">
              <router-link to="/grammar" class="btn btn-sm btn-outline-teal rounded-pill px-4 py-2 w-100 fw-bold shadow-xs">
                Luyện ngữ pháp & dịch câu ngay →
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useDailyStudyStore } from '@/stores/dailyStudyStore'
import confetti from 'canvas-confetti'

const authStore = useAuthStore()
const dailyStore = useDailyStudyStore()

const currentStreak = computed(() => {
  return authStore.user?.streak_days || 0
})

const userXp = computed(() => {
  return authStore.user?.xp_points || 0
})

const overallMasteredWords = computed(() => {
  return authStore.stats?.mastered_words || 0
})

const completedGrammars = computed(() => {
  return authStore.stats?.completed_grammars || 0
})

const overallPercent = computed(() => {
  const comp = overallMasteredWords.value
  return Math.min(100, Math.round((comp / 3351) * 100))
})

// 30 Levels Synchronized with Knowledge Tree
const treeLevels = [
  { level: 1, minXp: 0, name: 'Hạt Mầm Ngủ Say', stageDesc: 'Gieo hạt trong lòng đất mẹ' },
  { level: 2, minXp: 30, name: 'Đào Đất Xới Gốc', stageDesc: 'Đất tơi xốp, hấp thụ sương mai' },
  { level: 3, minXp: 70, name: 'Nứt Vỏ Nảy Mầm', stageDesc: 'Rễ non cắm sâu, vỏ hạt tách nở' },
  { level: 4, minXp: 120, name: 'Đâm Chồi Mơn Mởn', stageDesc: 'Chồi non nhú lên đón nắng' },
  { level: 5, minXp: 180, name: 'Hai Lá Mầm Xanh', stageDesc: 'Hai lá non xanh biếc đón bình minh' },
  { level: 6, minXp: 250, name: 'Cây Con Bén Rễ', stageDesc: 'Thân cứng cáp, thêm 4 lá nhỏ' },
  { level: 7, minXp: 330, name: 'Vươn Ngọn Đón Nắng', stageDesc: 'Thân cao thêm 15cm, rễ bám đất' },
  { level: 8, minXp: 420, name: 'Đâm Chồi Nảy Lộc', stageDesc: 'Nhánh con đầu tiên xuất hiện' },
  { level: 9, minXp: 520, name: 'Cành Nhánh Tỏa Rộng', stageDesc: '3 cành lớn vươn sang các phía' },
  { level: 10, minXp: 630, name: 'Tán Lá Xanh Rì', stageDesc: 'Tán cây che mát một góc vườn' },
  { level: 11, minXp: 750, name: 'Dây Leo Sum Suê', stageDesc: 'Dây leo quấn quanh thân cổ kính' },
  { level: 12, minXp: 880, name: 'Đơm Nụ E Ấp', stageDesc: 'Nụ hồng đầu tiên e ấp trên cành' },
  { level: 13, minXp: 1020, name: 'Hoa Chớm Nở', stageDesc: 'Cánh hoa hé lộ sắc thắm rực rỡ' },
  { level: 14, minXp: 1170, name: 'Vườn Hoa Khoe Sắc', stageDesc: 'Ngàn cánh hoa bung nở ngát hương' },
  { level: 15, minXp: 1330, name: 'Hương Thơm Ngào Ngạt', stageDesc: 'Hương hoa thu hút ong bướm bay về' },
  { level: 16, minXp: 1500, name: 'Ong Bướm Thụ Phấn', stageDesc: 'Hoa kết nụ đài quả non' },
  { level: 17, minXp: 1680, name: 'Kết Trái Non Xanh', stageDesc: 'Những quả xanh tròn nhú trên cành' },
  { level: 18, minXp: 1870, name: 'Quả Mọng Lớn Dần', stageDesc: 'Trái căng tròn hấp thụ tinh hoa' },
  { level: 19, minXp: 2070, name: 'Trái Chín Ửng Hồng', stageDesc: 'Vỏ quả chuyển sang đỏ tươi' },
  { level: 20, minXp: 2280, name: 'Mùa Quả Ngọt Đầu Mùa', stageDesc: 'Trái chín mọng có thể thu hoạch' },
  { level: 21, minXp: 2500, name: 'Tán Xòe Sum Sê', stageDesc: 'Tán cây rộng gấp đôi che rợp trời' },
  { level: 22, minXp: 2750, name: 'Rễ Sâu Bám Đá', stageDesc: 'Rễ bám sâu vững vàng trước bão giông' },
  { level: 23, minXp: 3050, name: 'Cổ Thụ Trăm Năm', stageDesc: 'Vỏ ngả màu thời gian huyền bí' },
  { level: 24, minXp: 3400, name: 'Trĩu Nặng Quả Vàng', stageDesc: 'Xuất hiện trái vàng kim thần kỳ' },
  { level: 25, minXp: 3800, name: 'Đại Thụ Ngàn Năm', stageDesc: 'Cổ thụ hùng vĩ sừng sững giữa trời' },
  { level: 26, minXp: 4300, name: 'Thần Mộc Tỏa Sáng', stageDesc: 'Thân cây phát ánh sáng ngọc bích' },
  { level: 27, minXp: 4900, name: 'Hào Quang Vĩnh Cửu', stageDesc: 'Hào quang vũ trụ bao bọc tán lá' },
  { level: 28, minXp: 5600, name: 'Vũ Trụ Sinh Sôi', stageDesc: 'Tán lá hòa cùng chòm sao rực rỡ' },
  { level: 29, minXp: 6400, name: 'Trí Tuệ Vô Song', stageDesc: 'Đỉnh cao tri thức nhân loại' },
  { level: 30, minXp: 7500, name: 'Cây Thần Vĩ Đại Yggdrasil', stageDesc: 'Cây tri thức thần thoại vĩnh hằng' }
]

const currentTreeLevel = computed(() => {
  const xp = userXp.value
  for (let i = treeLevels.length - 1; i >= 0; i--) {
    if (xp >= treeLevels[i].minXp) {
      return treeLevels[i]
    }
  }
  return treeLevels[0]
})

const nextLevelThresholdXp = computed(() => {
  const lvl = currentTreeLevel.value
  if (lvl.level >= 30) return 7500
  const nextLvl = treeLevels.find(l => l.level === lvl.level + 1)
  return nextLvl ? nextLvl.minXp : 7500
})

const treeLevelProgressPercent = computed(() => {
  const lvl = currentTreeLevel.value
  if (lvl.level >= 30) return 100
  const range = nextLevelThresholdXp.value - lvl.minXp
  const gained = userXp.value - lvl.minXp
  if (range <= 0) return 100
  return Math.min(100, Math.max(0, Math.round((gained / range) * 100)))
})

// SVG Tree Geometry Helpers
const treeFoliageTop = computed(() => {
  const lvl = currentTreeLevel.value.level
  return Math.max(100, 270 - lvl * 5)
})

const treeFoliageRadius = computed(() => {
  const lvl = currentTreeLevel.value.level
  return 38 + lvl * 3
})

const treeFoliageSpread = computed(() => {
  const lvl = currentTreeLevel.value.level
  return lvl * 3.5
})

const treeTrunkPath = computed(() => {
  const lvl = currentTreeLevel.value.level
  const topY = treeFoliageTop.value + 40
  const widthBottom = 16 + lvl * 1.8
  const widthTop = 8 + lvl * 1.2
  return `M ${250 - widthBottom} 460 L ${250 - widthTop} ${topY} Q 250 ${topY - 10} ${250 + widthTop} ${topY} L ${250 + widthBottom} 460 Z`
})

const treeFoliageColor1 = computed(() => {
  if (currentTreeLevel.value.level >= 26) return '#10b981'
  if (currentTreeLevel.value.level >= 18) return '#15803d'
  return '#22c55e'
})

const treeFoliageColor2 = computed(() => {
  if (currentTreeLevel.value.level >= 26) return '#065f46'
  if (currentTreeLevel.value.level >= 18) return '#166534'
  return '#16a34a'
})

const treeFoliageColor3 = computed(() => {
  if (currentTreeLevel.value.level >= 26) return '#047857'
  return '#15803d'
})

const quickWatered = ref(false)
const quickWaterTree = () => {
  quickWatered.value = true
  localStorage.setItem('ox_last_watered_date', new Date().toDateString())
  if (authStore.user) {
    authStore.user.xp_points = (authStore.user.xp_points || 0) + 5
  }
  confetti({
    particleCount: 35,
    spread: 50,
    origin: { y: 0.65 }
  })
}

// WEEKLY STATS ACTIVITY CHART (Tuần trước vs Tuần này)
const activeStatsFilter = ref('week')
const statsFilterOptions = [
  { key: 'today', label: 'Hôm nay' },
  { key: 'week', label: 'Tuần này' },
  { key: 'month', label: 'Tháng' },
]

const weeklyStatsBars = ref([
  { day_code: 'T2', this_week_minutes: 25, last_week_minutes: 15 },
  { day_code: 'T3', this_week_minutes: 35, last_week_minutes: 20 },
  { day_code: 'T4', this_week_minutes: 20, last_week_minutes: 25 },
  { day_code: 'T5', this_week_minutes: 42, last_week_minutes: 30 },
  { day_code: 'T6', this_week_minutes: 30, last_week_minutes: 22 },
  { day_code: 'T7', this_week_minutes: 45, last_week_minutes: 20 },
  { day_code: 'CN', this_week_minutes: 15, last_week_minutes: 15 }
])

const currentDayCode = computed(() => {
  const day = new Date().getDay()
  const map = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7']
  return map[day]
})

const currentStatsMinutes = computed(() => {
  if (activeStatsFilter.value === 'today') return 46
  if (activeStatsFilter.value === 'week') return 207
  if (activeStatsFilter.value === 'month') return 840
  return 3420
})

const currentStatsXp = computed(() => {
  if (activeStatsFilter.value === 'today') return 180
  if (activeStatsFilter.value === 'week') return 950
  if (activeStatsFilter.value === 'month') return 3600
  return authStore.user?.xp_points || 3850
})

const currentStatsSessions = computed(() => {
  if (activeStatsFilter.value === 'today') return 3
  if (activeStatsFilter.value === 'week') return 18
  if (activeStatsFilter.value === 'month') return 64
  return 214
})

const maxStatsMinutes = computed(() => {
  const vals = weeklyStatsBars.value.flatMap(b => [b.this_week_minutes, b.last_week_minutes])
  return Math.max(50, ...vals)
})

const getStatsBarHeight = (mins) => {
  return Math.min(100, Math.max(10, Math.round((mins / maxStatsMinutes.value) * 100)))
}

onMounted(() => {
  if (authStore.isAuthenticated) {
    dailyStore.fetchDays()
    const lastWatered = localStorage.getItem('ox_last_watered_date')
    if (lastWatered === new Date().toDateString()) {
      quickWatered.value = true
    }
  }
})
</script>
