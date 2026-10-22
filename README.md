# Oxford 3000™ - Hệ Thống Học Tiếng Anh Toàn Diện (Full-Stack)

Hệ thống web học tiếng Anh toàn diện phong cách Quizlet kết hợp lộ trình sư phạm bài bản: **"Xây dựng kiến thức nền tảng trước -> Thực hành làm bài kiểm tra sau"**.

Dự án tích hợp trực tiếp toàn bộ dữ liệu từ tài liệu **Oxford 3000 Từ Vựng** (3,351 từ vựng trích xuất từ file PDF, phiên âm chuẩn Mỹ, từ loại, nghĩa tiếng Việt, câu ví dụ song ngữ, phân cấp CEFR A1 - B2) và chuyên đề **Ngữ pháp theo cấp độ**.

---

## 🚀 CÁCH KHỞI CHẠY 1-CLICK (START.BAT)

Chỉ cần nhấp đúp vào file **`start.bat`** ở thư mục gốc:
1. Hệ thống tự động kiểm tra môi trường PHP và Node.js.
2. Tự động kiểm tra và khởi tạo database MySQL `oxford_english`.
3. Khởi động Backend Laravel API trên cổng `8000`.
4. Khởi động Frontend Vue 3 + Vite trên cổng `5173`.
5. Tự động bật trình duyệt web tại địa chỉ: **`http://localhost:5173`**!

---

## 🛠️ CÔNG NGHỆ SỬ DỤNG (TECH STACK)

### 1. Backend (Thư mục `backend`)
* **PHP Laravel 12 (RESTful API)**: Kiến trúc Controller - Model - Migration - Seeder chuẩn mực.
* **Database MySQL**: Chuẩn hóa quan hệ, đánh index cho tìm kiếm và theo dõi Spaced Repetition (SRS).
* **Sanctum Authentication**: Xác thực API token.
* **Quiz Generator Engine**: Thuật toán tự động sinh đề thi trắc nghiệm (lấy đáp án nhiễu ngẫu nhiên từ kho 3000 từ trong DB) và điền khuyết / chép chính tả.

### 2. Frontend (Thư mục `frontend`)
* **Vue 3 (Composition API, `<script setup>`)**: Hiện đại, mượt mà và tối ưu hiệu năng.
* **Vite**: Build tool siêu tốc.
* **Pinia**: Quản lý state luồng học từ vựng, lộ trình và bài kiểm tra (`useLearningStore`, `useQuizStore`).
* **Vue Router**: Điều hướng các trang SPA không tải lại trang.
* **Tailwind CSS v4**: Giao diện tối Dark Mode hiện đại, hiệu ứng thẻ 3D xoay chiều (`perspective-1000`, `rotate-y-180`).
* **Web Speech API**:
  * **Nghe (Text-to-Speech)**: `window.speechSynthesis` giọng chuẩn Mỹ (`en-US`), tùy chỉnh tốc độ 1.0x và 0.75x.
  * **Nói (Speech-to-Text)**: `webkitSpeechRecognition` thu âm giọng đọc của học viên, đối chiếu với từ gốc bằng thuật toán **Levenshtein Distance**, chấm điểm chính xác (%) và phản hồi màu sắc trực quan (Xanh lá / Vàng / Đỏ).
* **Canvas Confetti**: Hiệu ứng pháo hoa chúc mừng khi vượt qua bài thi đạt chuẩn ≥ 80%.

---

## 🗄️ CẤU TRÚC CƠ SỞ DỮ LIỆU (DATABASE SCHEMA)

1. **`users`**: Quản lý tài khoản, chuỗi ngày học liên tục (`streak_days`), điểm kinh nghiệm tích lũy (`xp_points`), cấp độ CEFR hiện tại (`current_level`).
2. **`roadmaps`**: Quản lý các lộ trình học bài bản từ A1 đến B2.
3. **`roadmap_nodes`**: Từng chặng học cụ thể trong lộ trình, luân phiên đan xen giữa Bộ từ vựng và Chuyên đề ngữ pháp, có trạng thái mở khóa (`is_locked`) và điểm số vượt qua (`passing_score` = 80%).
4. **`vocabularies`**: Kho 3,351 từ vựng Oxford (Từ vựng, phiên âm Mỹ, từ loại, nghĩa tiếng Việt, câu ví dụ tiếng Anh, dịch câu ví dụ, cấp độ CEFR).
5. **`grammars`**: Bài học lý thuyết ngữ pháp (Công thức Khẳng định / Phủ định / Nghi vấn, cách dùng, dấu hiệu nhận biết, ví dụ có âm thanh).
6. **`grammar_questions`**: Bộ câu hỏi trắc nghiệm A-B-C-D và điền khuyết chia động từ kèm giải thích chi tiết.
7. **`user_vocabulary_progress`**: Thuật toán lặp lại ngắt quãng (Spaced Repetition System - SRS) theo dõi trạng thái `new` -> `learning` -> `mastered`, chuỗi trả lời đúng (`correct_streak`), ngày cần ôn tập tiếp theo (`next_review_at`).
8. **`quiz_attempts` & `quiz_attempt_details`**: Lưu lịch sử bài thi, tỷ lệ phần trăm đúng, thời gian làm bài, chi tiết câu làm đúng/sai.

---

## 📚 4 PHÂN HỆ NGHIỆP VỤ CHÍNH

### Phân hệ 1: Trang chủ Dashboard (`HomeDashboard.vue`)
* Tổng quan tiến trình 3,351 từ vựng Oxford (Đã thuộc / Đang học / Chưa học).
* Chuỗi ngày học liên tục (Streak 🔥) và Điểm kinh nghiệm (XP ⚡).
* Biểu đồ trực quan số từ vựng ôn luyện trong 7 ngày qua.
* Nút **"Tiếp tục lộ trình"** dẫn thẳng vào bài học đang dang dở.
* Khu vực **"Ôn tập thông minh hôm nay"**: Tự động gom các từ đến hạn ôn tập SRS hoặc hay làm sai.

### Phân hệ 2: Lộ trình học bài bản (`RoadmapView.vue`)
* Hiển thị dạng sơ đồ bậc thang (Stepping stones / Timeline) trực quan.
* Đan xen nhịp nhàng giữa **Chặng từ vựng (20 từ/chặng)** và **Chuyên đề Ngữ pháp**.
* Quy tắc mở khóa: Phải học hết thẻ ở Bước 1 và đạt tối thiểu **80% điểm** ở Bước 2 để mở khóa bài tiếp theo.

### Phân hệ 3: Học từ vựng Flashcard 3D & Kiểm tra 2 cấp độ (`VocabularyStudyView.vue`)
* **Giai đoạn A - Xây dựng kiến thức:**
  * Thẻ lật 3D phong cách Quizlet (Click hoặc phím `Space` để lật, phím mũi tên `←` `→` để chuyển thẻ).
  * Mặt trước: Từ, phiên âm Mỹ, loại từ, nút Nghe (1.0x & 0.75x), nút Micro thu âm chấm điểm phát âm trực tiếp.
  * Mặt sau: Nghĩa tiếng Việt, câu ví dụ tiếng Anh in đậm từ khóa, câu dịch tiếng Việt, nút nghe câu.
  * Nút "Cần học lại" / "Đã nhớ". Bắt buộc xem hết thẻ mới mở khóa nút "Bắt đầu làm bài kiểm tra".
* **Giai đoạn B - Làm bài kiểm tra củng cố (`QuizEngine.vue`):**
  * **50% Trắc nghiệm:** Cho từ -> Chọn nghĩa tiếng Việt (đáp án nhiễu ngẫu nhiên); Nghe âm thanh ẩn chữ -> Chọn từ; Điền từ vào câu ví dụ.
  * **50% Điền vào ô trống:** Gõ từ tiếng Anh theo nghĩa + từ loại; Nghe chép chính tả (Dictation); Điền khuyết vào câu ví dụ. Hỗ trợ nút "Gợi ý chữ cái đầu", phím `Enter` kiểm tra tức thì.
  * **Tính năng sư phạm đặc biệt:** Nếu gõ sai, hệ thống hiển thị đáp án đúng và **bắt buộc người học phải gõ lại đúng vào ô sửa sai** để khắc sâu trí nhớ trước khi sang câu tiếp theo.

### Phân hệ 4: Học Ngữ pháp Thực chiến (`GrammarStudyView.vue`)
* **Giai đoạn A - Lý thuyết trực quan:** Bảng công thức, cách dùng, dấu hiệu nhận biết. Từng ví dụ có nút nghe phát âm và nút micro đọc lại câu để luyện ngữ điệu.
* **Giai đoạn B - Bài tập thực hành:** Trắc nghiệm và điền dạng đúng của động từ vào ô trống. Mỗi câu đều có phần **Giải thích chi tiết** bản chất ngữ pháp.

---

## 💻 HƯỚNG DẪN KHỞI CHẠY THỦ CÔNG (NẾU KHÔNG DÙNG START.BAT)

### Khởi chạy Backend:
```bash
cd backend
php artisan serve --port=8000
```
API sẽ chạy tại: `http://localhost:8000/api`

### Khởi chạy Frontend:
```bash
cd frontend
npm run dev
```
Giao diện sẽ chạy tại: `http://localhost:5173`
