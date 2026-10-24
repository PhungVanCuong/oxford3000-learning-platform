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

      <!-- SEEDLING TREE & OVERALL PROGRESS SECTION (Tính năng Cây Hạt Giống) -->
      <div class="row g-4 mb-5">
        <!-- Cây Hạt Giống Widget -->
        <div class="col-12 col-lg-7">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 p-2 bg-success-subtle text-success fs-4">
                  {{ currentTreeStage.icon }}
                </div>
                <div>
                  <h5 class="fw-bold text-teal mb-0">Cây Tri Thức: {{ currentTreeStage.title }}</h5>
                  <span class="text-muted fs-8">Giai đoạn {{ currentTreeStage.stageNumber }}/6 • {{ authStore.user?.xp_points || 0 }} XP</span>
                </div>
              </div>
              <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fs-8">
                {{ currentTreeStage.badgeText }}
              </span>
            </div>

            <p class="text-muted fs-7 mb-3">
              {{ currentTreeStage.description }}
            </p>

            <!-- XP Progress Bar towards next stage -->
            <div class="mb-2">
              <div class="d-flex justify-content-between align-items-center fs-8 fw-semibold mb-1">
                <span class="text-teal">Tiến độ phát triển giai đoạn</span>
                <span class="text-success">{{ stageProgressPercent }}%</span>
              </div>
              <div class="progress" style="height: 12px; border-radius: 8px;">
                <div
                  class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                  role="progressbar"
                  :style="{ width: stageProgressPercent + '%' }"
                ></div>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center text-muted fs-8 mt-2">
              <span>{{ authStore.user?.xp_points || 0 }} / {{ currentTreeStage.nextThreshold }} XP</span>
              <span class="text-warning-emphasis fw-bold">
                Còn {{ Math.max(0, currentTreeStage.nextThreshold - (authStore.user?.xp_points || 0)) }} XP nữa để lên cấp cây tiếp theo!
              </span>
            </div>
          </div>
        </div>

        <!-- Overall Course Progress Card -->
        <div class="col-12 col-lg-5">
          <div class="card p-4 rounded-4 shadow-sm border bg-card-custom h-100 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fs-8 fw-semibold text-uppercase">Tiến độ toàn bộ 3000 từ</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fs-8">
                  3.351 từ gốc
                </span>
              </div>
              <div class="d-flex align-items-baseline gap-2 mb-2">
                <span class="display-6 fw-black text-teal">{{ overallMasteredWords }}</span>
                <span class="fs-6 text-muted">/ 3351 từ đã thuộc</span>
                <span class="badge bg-success ms-auto fs-7">{{ overallPercent }}%</span>
              </div>
              <div class="progress mb-4" style="height: 10px; border-radius: 6px;">
                <div
                  class="progress-bar rounded-pill"
                  role="progressbar"
                  :style="{ width: overallPercent + '%', backgroundColor: '#00b074' }"
                ></div>
              </div>

              <!-- Secondary grammar metric -->
              <div class="d-flex justify-content-between align-items-center fs-7 mb-1">
                <span class="text-muted">Ngữ pháp (12 thì & chuyên đề):</span>
                <span class="fw-bold text-teal">{{ completedGrammars }}/15 bài</span>
              </div>
              <div class="progress" style="height: 6px; border-radius: 6px;">
                <div
                  class="progress-bar bg-teal rounded-pill"
                  role="progressbar"
                  :style="{ width: (completedGrammars / 15 * 100) + '%' }"
                ></div>
              </div>
            </div>

            <div class="pt-3 border-top mt-3 text-center">
              <router-link to="/daily" class="btn btn-sm btn-outline-teal rounded-pill px-4 py-2 w-100 fw-bold">
                Tiếp tục bài học hôm nay →
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useDailyStudyStore } from '@/stores/dailyStudyStore'

const authStore = useAuthStore()
const dailyStore = useDailyStudyStore()

const currentStreak = computed(() => {
  return authStore.user?.streak_days || 1
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

const stages = [
  {
    stageNumber: 1,
    title: 'Hạt Giống Ươm Mầm',
    icon: '🌱',
    badgeText: 'Cấp 1: Hạt Giống',
    description: 'Hạt mầm tri thức đầu tiên vừa được gieo xuống mảnh đất 3000 từ vựng. Hãy chăm chỉ học mỗi ngày để hạt mầm nảy chồi!',
    minXp: 0,
    nextThreshold: 150,
  },
  {
    stageNumber: 2,
    title: 'Mầm Non Nảy Chồi',
    icon: '🌿',
    badgeText: 'Cấp 2: Mầm Non',
    description: 'Mầm xanh đã vươn lên đón ánh sáng mặt trời! Vốn từ vựng của bạn bắt đầu hình thành phản xạ cơ bản.',
    minXp: 150,
    nextThreshold: 400,
  },
  {
    stageNumber: 3,
    title: 'Cây Con Phát Triển',
    icon: '🪴',
    badgeText: 'Cấp 3: Cây Con',
    description: 'Rễ cây đã bám sâu vào nền tảng ngữ pháp và phát âm IPA. Cây con đang lớn nhanh từng ngày!',
    minXp: 400,
    nextThreshold: 800,
  },
  {
    stageNumber: 4,
    title: 'Thân Cây Vươn Cành',
    icon: '🌳',
    badgeText: 'Cấp 4: Cây Trưởng Thành',
    description: 'Thân cây vững chắc giúp bạn tự tin giao tiếp các chủ đề thông dụng và làm bài trắc nghiệm với độ chính xác cao.',
    minXp: 800,
    nextThreshold: 1500,
  },
  {
    stageNumber: 5,
    title: 'Tán Lá Xanh Mướt',
    icon: '🌲',
    badgeText: 'Cấp 5: Tán Lá Rộng',
    description: 'Tán cây râm mát che phủ toàn bộ bộ từ vựng Oxford 3000! Khả năng nghe nói và phản xạ tiếng Anh rất tự nhiên.',
    minXp: 1500,
    nextThreshold: 2500,
  },
  {
    stageNumber: 6,
    title: 'Đơm Hoa Kết Trái (Master)',
    icon: '🍎',
    badgeText: 'Cấp 6: Đại Thụ Trĩu Quả',
    description: 'Tuyệt đỉnh! Bạn đã hoàn thành toàn bộ 3000 từ vựng và ngữ pháp. Cây đại thụ tri thức đã đơm hoa kết trái rực rỡ.',
    minXp: 2500,
    nextThreshold: 5000,
  },
]

const currentTreeStage = computed(() => {
  const xp = authStore.user?.xp_points || 0
  for (let i = stages.length - 1; i >= 0; i--) {
    if (xp >= stages[i].minXp) {
      return stages[i]
    }
  }
  return stages[0]
})

const stageProgressPercent = computed(() => {
  const xp = authStore.user?.xp_points || 0
  const cur = currentTreeStage.value
  const range = cur.nextThreshold - cur.minXp
  const gained = xp - cur.minXp
  if (range <= 0) return 100
  return Math.min(100, Math.max(0, Math.round((gained / range) * 100)))
})

onMounted(() => {
  if (authStore.isAuthenticated) {
    dailyStore.fetchDays()
  }
})
</script>
