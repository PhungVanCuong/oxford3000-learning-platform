# 🎓 OXVOCAB™ - HỆ THỐNG HỌC TIẾNG ANH & LỘ TRÌNH TỪ VỰNG TOÀN DIỆN
### *Nền Tảng E-Learning Full-Stack (Vue 3 + Laravel 12 API + MySQL + AI Gemini)*

[![Vue 3](https://img.shields.io/badge/Frontend-Vue%203%20(Vite)-42b883?style=flat-square&logo=vuedotjs)](https://vuejs.org/)
[![Laravel 12](https://img.shields.io/badge/Backend-Laravel%2012%20API-ff2d20?style=flat-square&logo=laravel)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/Database-MySQL-00758f?style=flat-square&logo=mysql)](https://www.mysql.com/)
[![Google Gemini](https://img.shields.io/badge/AI-Google%20Gemini%202.5%20Flash-4285f4?style=flat-square&logo=google)](https://ai.google.dev/)
[![Bootstrap 5](https://img.shields.io/badge/UI-Bootstrap%205.3%20%2B%20Icons-7952b3?style=flat-square&logo=bootstrap)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

---

## 📖 1. GIỚI THIỆU TỔNG QUAN ĐỀ TÀI

### 1.1. Tên Đề Tài Chính Thức
> **"Nghiên Cứu & Xây Dựng Ứng Dụng Web Học Tiếng Anh Toàn Diện Tích Hợp Kho 3.000 Từ Vựng Oxford, Lộ Trình 25 Chủ Đề IELTS (5.000 Từ), 12 Thì Ngữ Pháp Chấm Điểm AI, 1.000 Cụm Từ Thông Dụng & Đấu Trường Trực Tuyến Gamification (OxVocab™)"**

### 1.2. Mục Tiêu & Giá Trị Cốt Lõi
Hầu hết người học tiếng Anh thường gặp khó khăn do học từ vựng rời rạc, mau quên, thiếu lộ trình và không có môi trường luyện tập phản xạ. **OXVOCAB™** được thiết kế nhằm giải quyết triệt để những vấn đề này bằng triết lý sư phạm:
1. **"Xây dựng kiến thức nền tảng trước ➔ Luyện phản xạ & kiểm tra đánh giá sau"**: Không kiểm tra khi học viên chưa nắm vững kiến thức.
2. **Thuật toán Lặp lại ngắt quãng (Spaced Repetition System - SRS)**: Tự động tính toán chuỗi ngày học (`streak`), ghi nhận từ đã thuộc, cần ôn tập và nhắc nhở ôn đúng thời điểm vàng của trí nhớ.
3. **Trợ lý AI Google Gemini 2.5 Flash thông minh**:
   - Chấm điểm bài tập dịch câu Việt ➔ Anh theo thời gian thực (đạt từ 80% trở lên là tính hoàn thành).
   - Tự động hiểu và chấp nhận các cách diễn đạt linh hoạt (ví dụ: dùng dấu phẩy `reading, booking` hoặc từ nối `reading and booking`).
   - Tích hợp bộ **Fallback Grader Offline** nội bộ đảm bảo hệ thống chấm điểm tức thì ngay cả khi mất kết nối mạng.
4. **Gamification (Trò chơi hóa học tập)**:
   - **Vườn Cây Tri Thức (Knowledge Tree)**: 30 cấp độ tiến hóa sinh động từ Hạt mầm đến Đại thụ cổ thụ đơm hoa kết trái.
   - **Đấu Trường Quiz Trực Tuyến (Live Arena)**: Mở phòng thi đua trả lời nhanh giữa các học viên có mật khẩu phòng, xếp hạng trực tiếp.
   - **Bảng tần suất đóng góp GitHub Heatmap**: Thống kê số phút và ngày học liên tục trong tuần, tháng, năm.

---

## 🛠️ 2. KIẾN TRÚC HỆ THỐNG & CÔNG NGHỆ (TECH STACK)

```
                            ┌────────────────────────────────────────┐
                            │           NGƯỜI HỌC / ADMIN            │
                            └────────────────────┬───────────────────┘
                                                 │
                                                 ▼
┌────────────────────────────────────────────────────────────────────────────────────────┐
│ FRONTEND CLIENT: Vue 3 (Composition API <script setup>) + Vite 8                      │
│ - Pinia State Management (authStore, headerNavItems, themeMode)                        │
│ - Vue Router (SPA điều hướng mượt mà, phân quyền Route Guard)                         │
│ - Web Speech API (Text-to-Speech phát âm bản xứ & Speech Recognition chấm phát âm)     │
│ - Bootstrap 5.3 + Bootstrap Icons + Custom Glowing Card System (Dark/Light Mode)      │
│ - HTML5 Canvas Confetti + CSS 3D Transforms (Thẻ Flashcard lật 3 chiều)               │
└────────────────────────────────────────┬───────────────────────────────────────────────┘
                                         │ RESTful API (JSON / Bearer Token)
                                         ▼
┌────────────────────────────────────────────────────────────────────────────────────────┐
│ BACKEND SERVICES: Laravel 12 API (PHP 8.2+)                                            │
│ - Laravel Sanctum Token Authentication & RBAC Middleware (5 Roles & Permissions)       │
│ - Google Gemini API Service (gemini-2.5-flash chấm điểm dịch thuật & giải thích)       │
│ - Automated Quiz Engine (Sinh đề trắc nghiệm ngẫu nhiên & chấm câu linh hoạt)          │
│ - Spaced Repetition Engine (Thuật toán theo dõi tiến độ từ vựng & ngữ pháp)          │
└────────────────────────────────────────┬───────────────────────────────────────────────┘
                                         │ Eloquent ORM / Query Builder
                                         ▼
┌────────────────────────────────────────────────────────────────────────────────────────┐
│ DATABASE: MySQL (utf8mb4_unicode_ci)                                                   │
│ - 3.351 Từ vựng Oxford 3000™ kèm phiên âm IPA, từ loại, câu ví dụ song ngữ             │
│ - 5.000 Mục từ học thuật thuộc Lộ trình 25 Chủ đề IELTS (250 giai đoạn)                │
│ - 945 Cụm từ: 100 Cụm động từ (A-W) + 850 Cụm từ thông dụng PDF (17 phần)              │
│ - 15 Chuyên đề 12 thì ngữ pháp (lý thuyết PDF, công thức, thể bị động, ngữ âm)         │
│ - Phòng đấu trường Quiz Arena, Lịch sử làm bài, Điểm kinh nghiệm XP, Streak            │
└────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 🌟 3. CÁC PHÂN HỆ NGHIỆP VỤ NỔI BẬT

### 3.1. Trang Chủ Dashboard & Biểu Đồ Năng Suất GitHub
- **Thẻ chào mừng cá nhân**: Hiển thị chuỗi ngày học liên tục (Streak 🔥), điểm kinh nghiệm tích lũy (XP ⚡) và tỷ lệ ghi nhớ.
- **Vườn Cây Tri Thức thu nhỏ**: Xem nhanh cấp độ hiện tại của cây học tập (Level 1 - 30).
- **Biểu đồ đóng góp GitHub Activity Heatmap**: Hiển thị số phút học và tần suất học tập theo từng ngày trong tuần, tháng, năm.
- **Tiếp tục học lộ trình**: Nhấp 1-click vào thẳng bài học hoặc chặng từ vựng đang làm dở dang.
- **Ôn tập thông minh Spaced Repetition (SRS)**: Tự động gom các từ vựng đến hạn ôn tập hoặc hay làm sai.

### 3.2. Lộ Trình 25 Chủ Đề IELTS (5.000 Từ Vựng Học Thuật)
- Được trích xuất chuẩn hóa từ tài liệu học thuật IELTS thành **25 Chủ đề lớn** (Môi trường, Kinh tế, Giáo dục, Y tế, Công nghệ, Du lịch...).
- Mỗi chủ đề gồm **10 Chặng / Giai đoạn (mỗi chặng 20 từ)** được phân tầng từ cơ bản đến nâng cao.
- Hai chế độ học:
  - **Chế độ Thẻ Flashcard 3D**: Lật thẻ, nghe phát âm giọng Mỹ, luyện nói qua Microphone và chấm điểm giọng đọc theo thuật toán Levenshtein.
  - **Chế độ Kiểm tra**: Kết hợp trắc nghiệm 4 đáp án và gõ chính tả câu ví dụ. Đạt ≥ 80% để hoàn thành chặng.

### 3.3. Học Theo Ngày (Kho Từ Vựng Oxford 3000™)
- Kho **3.351 từ vựng Oxford** trích xuất từ tài liệu chuẩn, phân chia cấp độ CEFR từ A1, A2 đến B1, B2.
- **Tùy biến số lượng từ học mỗi ngày**: Học viên có thể tự chọn mục tiêu 10, 15, 20, 25, 30 hoặc 50 từ/ngày (tương ứng lộ trình 60 - 300 ngày).
- Hệ thống tự động tính toán lại lộ trình mà **không làm mất tiến độ** các từ đã tích lũy trước đó.

### 3.4. Hệ Thống 12 Thì Ngữ Pháp Thực Chiến (Tích Hợp PDF 49 Trang)
- **Lý thuyết đầy đủ**: Bảng công thức 3 thể Khẳng định (+), Phủ định (-), Nghi vấn (?) kèm quy tắc nhận biết.
- **Thể Bị Động & Ngữ Âm**: Bảng chuyển đổi Chủ động ➔ Bị động (Active & Passive Voice) và quy tắc phát âm đuôi `-ed`, `-s/es`.
- **Luyện dịch câu Việt ➔ Anh có AI chấm điểm**:
  - Học viên gõ câu dịch tiếng Anh, nhấn `Enter` để chấm điểm tức thì.
  - Đạt từ 80% trở lên là tính hoàn thành và cộng ngay **+15 XP**.
  - **Chấm điểm thông minh**: Hiểu linh hoạt giữa việc dùng dấu phẩy hay từ nối (ví dụ: `reading, booking` hay `reading and booking` hay `booking, reading` đều chính xác 100%).
  - **Bộ Grader Offline**: Tự động kích hoạt khi mất mạng hoặc AI bận, đảm bảo không bao giờ bị lỗi trắng màn hình.
  - **Lưu và xem lại kết quả cũ**: Toàn bộ câu trả lời, lời giải thích và điểm số được lưu lại. Thẻ bài tập hiển thị huy hiệu sáng **"✓ ĐÃ XONG"**, kèm nút **"Làm lại bài này"**.
  - **Tiến độ ngoài danh mục**: Mỗi thẻ ngữ pháp hiển thị chi tiết số câu đã làm (VD: `Đã làm: 25/25 câu`) cùng thanh tiến độ % đồng bộ như lộ trình.

### 3.5. Cụm Từ & Phrasal Verbs Toàn Diện (Tài Liệu Word + PDF 20 Trang)
Được cấu trúc thành 5 Tab chuyên sâu:
1. **1.000 Cụm Từ Thông Dụng (PDF)**: 850 mẫu câu giao tiếp chia làm **17 Phần** (50 câu/phần), có bộ lọc phần học, tìm kiếm song ngữ và phát âm bản xứ (TTS).
2. **100 Cụm Động Từ Thường Gặp (Word)**: Phân theo nhóm chữ cái (A - W), tách chi tiết các từ đơn cấu thành, câu ví dụ thực tế và luyện nói microphone.
3. **Phương Pháp Học Đột Phá**: 3 phương pháp từ tác giả (Không nhóm theo gốc động từ, Nhóm theo từ phụ trợ Particle, Học theo ngữ cảnh).
4. **Bài Tập Thực Hành (+15 XP/câu)**: Bài 1 (10 câu điền từ) và Bài 2 (10 câu dạng đúng) kèm đáp án chuẩn và lời giải thích.
5. **Thẻ Flashcard 3D**: Lật thẻ 3 chiều, nghe phát âm và đối chiếu nghĩa song ngữ.

### 3.6. Đấu Trường Quiz Trực Tuyến (Live Arena)
- Quản trị viên hoặc Giảng viên có thể mở phòng thi đấu trắc nghiệm cho cả lớp cùng tham gia.
- Học viên tham gia nhanh chóng bằng **Mã phòng** hoặc **Mật khẩu phòng**.
- Tùy chọn cấu hình phòng phong phú:
  - Nguồn đề: Theo Ngày học, 25 Chủ đề, Kho 3000 từ, 12 Thì ngữ pháp, hoặc Giáo viên tự thêm từ vựng.
  - Tùy chỉnh số lượng câu hỏi, thời gian đếm ngược (hoặc không giới hạn giờ), số điểm XP thưởng mỗi câu, số lượng học viên tối đa.
  - Tự động hủy phòng sau thời gian quy định hoặc duy trì vĩnh viễn (xóa qua trang Admin).
- **Bảng xếp hạng trực tiếp (Real-time Leaderboard)**: Vinh danh học viên có tốc độ và độ chính xác cao nhất.

### 3.7. Vườn Cây Tri Thức (Knowledge Tree - 30 Cấp Độ)
- Cây tri thức tiến hóa qua **30 cấp độ**: Từ Hạt mầm ươm ➔ Mầm non ➔ Cây con ➔ Cây trưởng thành ➔ Đại thụ cổ thụ đơm hoa kết trái.
- Các tính năng tương tác phong phú: Tưới nước cho cây, Đào xới đất, Thu hoạch trái cây chín để nhận điểm thưởng, Xem lại lịch sử các hình dạng cũ của cây qua từng giai đoạn.

### 3.8. Tùy Chỉnh Header 6 Mục Linh Hoạt & Trang Cá Nhân
- Thanh điều hướng Header gồm đúng 6 mục:
  - **Mục 1 (Cố định)**: Trang chủ
  - **4 Mục giữa (Tự chọn)**: Mặc định là Lộ trình, Học theo ngày, Ngữ pháp, Cụm từ.
  - **Mục 6 (Cố định)**: Xem thêm ▾ (chứa các tính năng phụ còn lại).
- Trong **Trang cá nhân (Profile)**, học viên có thể tùy ý chọn 4 mục ưa thích để đưa lên Header kèm khung **Mô phỏng trực tiếp (Live Preview)**.

### 3.9. Cổng Quản Trị Hệ Thống Toàn Diện (Admin & Coordinator Portal)
- **Hệ thống phân quyền RBAC 5 cấp bậc**:
  - `admin`: Toàn quyền quản trị hệ thống, tài khoản, cấu hình và nội dung.
  - `moderator`: Quản lý nội dung học tập, duyệt từ vựng và bài tập.
  - `editor`: Biên soạn, chỉnh sửa nội dung bài học ngữ pháp và từ vựng.
  - `tutor`: Giảng viên mở phòng Đấu trường Quiz và điều phối lớp học.
  - `user`: Học viên học tập, tích lũy XP và thi đua.
- **Cổng đăng nhập duy nhất**: Hệ thống tự động nhận diện quyền hạn sau khi đăng nhập và điều hướng thẳng vào khu vực quản trị nếu tài khoản có quyền.

---

## 💻 4. YÊU CẦU MÔI TRƯỜNG CÀI ĐẶT (PREREQUISITES)

Trước khi tiến hành cài đặt, hãy đảm bảo máy tính của bạn đã cài đặt các công cụ sau:

| Công cụ | Phiên bản khuyến nghị | Mục đích |
| :--- | :--- | :--- |
| **PHP** | `8.2` hoặc `8.3+` | Chạy Backend Laravel 12 API |
| **Composer** | `2.x` | Quản lý gói thư viện PHP |
| **Node.js** | `18.x` hoặc `20.x+` (kèm npm) | Chạy Frontend Vue 3 + Vite |
| **MySQL / MariaDB** | `5.7+` hoặc `8.0+` (XAMPP / Laragon) | Hệ quản trị cơ sở dữ liệu |
| **Git** | Bản mới nhất | Quản lý mã nguồn |

> [!TIP]
> Người dùng Windows được khuyến nghị sử dụng **Laragon** hoặc **XAMPP** để có sẵn PHP 8.2+, MySQL và Apache chỉ với 1 cú nhấp chuột.

---

## 🚀 5. HƯỚNG DẪN CÀI ĐẶT CHI TIẾT TỪ ĐẦU ĐẾN CUỐI

### CÁCH 1: KHỞI CHẠY SIÊU TỐC 1-CLICK VỚI FILE `start.bat` (Dành cho Windows)

Nếu bạn đang dùng Windows và đã bật MySQL (ví dụ qua XAMPP hoặc Laragon):
1. Nhấp đúp chuột vào file **`start.bat`** ở thư mục gốc của dự án.
2. File script sẽ tự động:
   - Kiểm tra môi trường PHP và Node.js.
   - Tự động tạo cơ sở dữ liệu MySQL `oxford_english` nếu chưa có.
   - Chạy migration và nạp dữ liệu seeder đầy đủ.
   - Khởi động đồng thời Laravel Backend (cổng 8000) và Vue Frontend (cổng 5173).
   - Tự động mở trình duyệt web tại địa chỉ: **`http://localhost:5173`**.

---

### CÁCH 2: HƯỚNG DẪN CÀI ĐẶT THỦ CÔNG TỪNG BƯỚC

#### BƯỚC 1: Clone kho mã nguồn về máy
```bash
git clone https://github.com/cuongphung1810/Web-English-3000-tu-vung.git
cd "Web English 3000 từ vựng"
```

#### BƯỚC 2: Cấu hình và khởi chạy Backend (Laravel API)
1. Di chuyển vào thư mục backend:
   ```bash
   cd backend
   ```
2. Cài đặt các thư viện PHP:
   ```bash
   composer install
   ```
3. Tạo file cấu hình môi trường `.env`:
   ```bash
   cp .env.example .env
   ```
4. Mở file `.env` và kiểm tra cấu hình kết nối MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=oxford_english
   DB_USERNAME=root
   DB_PASSWORD=
   
   # Cấu hình khóa Gemini AI để kích hoạt tính năng giải thích và chấm điểm thông minh:
   GEMINI_API_KEY=your_google_gemini_api_key_here
   ```
   *(Nếu bạn chưa có API key của Gemini, hệ thống vẫn hoạt động hoàn hảo với bộ Grader Fallback Offline).*

5. Tạo Application Key cho Laravel:
   ```bash
   php artisan key:generate
   ```

6. Tạo cơ sở dữ liệu MySQL (nếu dùng dòng lệnh MySQL):
   ```sql
   CREATE DATABASE IF NOT EXISTS oxford_english CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

7. Chạy Migration và nạp toàn bộ dữ liệu mẫu (Seeder):
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
   *Quá trình này sẽ nạp trọn bộ:*
   - *3.351 từ vựng Oxford 3000™*
   - *5.000 mục từ thuộc Lộ trình 25 Chủ đề IELTS*
   - *15 bài học 12 Thì Ngữ pháp kèm kho bài tập PDF*
   - *945 Cụm từ và Phrasal Verbs (100 Cụm từ Word + 850 Cụm từ PDF chia 17 phần)*
   - *20 tài khoản người dùng mẫu phân quyền đầy đủ*

8. Khởi chạy máy chủ Backend Laravel API (khi phát triển cục bộ):
   ```bash
   php artisan serve --port=8000
   ```

---

#### 🌐 CƠ CHẾ HOẠT ĐỘNG SONG SONG ĐA MÔI TRƯỜNG (LOCALHOST & DOMAIN)
Hệ thống được thiết kế **tự động nhận diện môi trường theo Hostname trình duyệt**, cho phép chạy đồng thời cả 2 môi trường mà không cần chỉnh sửa cấu hình thủ công:
1. **Môi trường Cục bộ (Localhost):**
   - **Frontend (Web Học Tập)**: `http://localhost:5173` (hoặc `http://127.0.0.1:5173`)
   - **Backend (Laravel API)**: `http://localhost:8000` (hoặc `http://127.0.0.1:8000`)
   - *Khi chạy `npm run dev`, Vite Dev Server tự động chuyển tiếp (Proxy) toàn bộ API sang `http://127.0.0.1:8000`.*
2. **Môi trường Tên Miền Chính Thức (Production):**
   - **Frontend (Web Học Tập)**: `https://oxvocab.deloydz.io.vn`
   - **Backend (Laravel API)**: `https://beoxvocab.deloydz.io.vn`
   - *CORS và Sanctum đã được cấu hình chấp thuận và liên kết trơn tru giữa cả 2 tên miền.*

---

#### BƯỚC 3: Cấu hình và khởi chạy Frontend (Vue 3 + Vite)
1. Mở một cửa sổ Terminal mới và di chuyển vào thư mục frontend:
   ```bash
   cd frontend
   ```
2. Cài đặt các gói phụ thuộc Node.js:
   ```bash
   npm install
   ```
3. Khởi chạy máy chủ phát triển Frontend:
   ```bash
   npm run dev
   ```
4. Mở trình duyệt và truy cập: **`http://localhost:5173`**

---

## 📋 6. HƯỚNG DẪN SỬ DỤNG CHI TIẾT TỪ A ĐẾN Z

### 6.1. Dành Cho Học Viên
1. **Đăng nhập & Bắt đầu**:
   - Truy cập `http://localhost:5173/login`, đăng nhập với tài khoản học viên hoặc đăng ký tài khoản mới trực tiếp trên hệ thống.
2. **Học Lộ Trình 25 Chủ Đề**:
   - Nhấp vào **"Lộ trình"** trên thanh Header.
   - Chọn một trong 25 chủ đề (VD: *Technology & Artificial Intelligence*).
   - Học thẻ Flashcard 3D: Nhấp vào thẻ để lật xem nghĩa tiếng Việt, nhấn nút chiếc loa để nghe phát âm, bấm nút Micro để luyện nói.
   - Làm bài kiểm tra để tích lũy XP và mở khóa chặng tiếp theo.
3. **Học Ngữ Pháp 12 Thì, Thể Bị Động & Luyện Dịch Câu AI**:
   - Nhấp vào **"Ngữ pháp"** trên Header.
   - Chọn thì bạn muốn học (VD: *Thì Hiện tại hoàn thành*).
   - Đọc lý thuyết, công thức, bảng thể bị động và quy tắc ngữ âm.
   - Kéo xuống mục **"Luyện Tập & Chấm Điểm Thực Chiến"**: Gõ câu dịch tiếng Anh hoặc chuyển đổi câu bị động vào ô nhập liệu và nhấn `Enter`. Hệ thống AI sẽ chấm điểm, đưa ra lời giải thích và cấp huy hiệu **"✓ ĐÃ XONG"**.
4. **Học Cụm Từ & Phrasal Verbs**:
   - Nhấp vào **"Cụm từ"** trên Header.
   - Chọn Tab **1000 Cụm Từ Thông Dụng** để học 850 mẫu câu giao tiếp chia theo 17 phần (50 câu/phần).
   - Chọn Tab **100 Cụm Động Từ** để tra cứu cụm từ theo bảng chữ cái A - W.
   - Luyện Thẻ 3D cho từng phần cụ thể.
   - Làm bài tập thực hành Bài 1 & Bài 2 để nhận +15 XP mỗi câu.
5. **Tham Gia Đấu Trường Trực Tuyến**:
   - Nhấp vào biểu tượng hình tròn chiếc cúp **Đấu trường** ở góc phải Header.
   - Nhập mã phòng hoặc mật khẩu phòng do giáo viên cung cấp để vào phòng thi đua.
6. **Chăm Sóc Cây Tri Thức**:
   - Nhấp vào **Xem thêm ▾ ➔ Vườn Cây Tri Thức**.
   - Bấm nút **"Tưới cây"**, **"Xới đất"** hoặc **"Thu hoạch trái"** khi tích lũy đủ điểm kinh nghiệm.
7. **Tùy Chỉnh Giọng Đọc & Menu Header**:
   - Vào **Trang cá nhân (Profile)** ➔ Tìm đến thẻ **"Cài đặt giọng đọc & Phát âm"** để chọn giọng Nam/Nữ và accent ưa thích.
   - Tùy chỉnh thứ tự 4 mục Header và thứ tự danh sách trong **"Xem thêm ▾"** và nhấn **"Lưu Cấu Hình Menu"**.

---

### 6.2. Dành Cho Giảng Viên & Quản Trị Viên
1. **Đăng nhập quản trị**:
   - Đăng nhập với tài khoản có quyền Quản trị viên (`admin`) hoặc Giảng viên (`tutor`).
   - Nhấp vào ảnh đại diện góc phải ➔ Chọn **"Khu vực Quản trị & Điều phối"** (hoặc truy cập `http://localhost:5173/admin`).
2. **Tạo Phòng Đấu Trường Quiz Mới**:
   - Vào **Quản lý Đấu trường** (`/admin/arena`) hoặc tạo trực tiếp từ giao diện Đấu trường.
   - Nhập Tên phòng (VD: *Thi đấu Từ vựng Unit 1 - Cô Thu Trang*), đặt Mật khẩu phòng.
   - Chọn nguồn câu hỏi (Theo ngày, Theo chủ đề, 3000 từ, Ngữ pháp hoặc tự thêm câu hỏi).
   - Cài đặt thời gian đếm ngược, số XP thưởng và giới hạn thành viên tham gia ➔ Nhấn **"Mở phòng thi đấu"**.
3. **Quản Lý Học Viên & Phân Quyền RBAC**:
   - Vào mục **Tài khoản & Phân quyền**: Dễ dàng cấp hoặc thu hồi các quyền hạn (`manage_users`, `manage_vocab`, `manage_curriculum`, `manage_phrases`, `manage_arena`).

---

## 📁 7. CẤU TRÚC THƯ MỤC DỰ ÁN

```
Web English 3000 từ vựng/
├── backend/                                # BACKEND LARAVEL API
│   ├── app/
│   │   ├── Http/Controllers/Api/           # API Controllers
│   │   │   ├── AuthController.php          # Đăng nhập, đăng ký, profile, nav config
│   │   │   ├── DailyStudyController.php    # Học theo ngày (chunking động)
│   │   │   ├── CurriculumRoadmapController # Lộ trình 25 chủ đề IELTS
│   │   │   ├── GrammarController.php       # 12 thì ngữ pháp & kiểm tra dịch câu
│   │   │   ├── PhrasalVerbController.php   # 100 Phrasal Verbs & 1000 cụm từ PDF
│   │   │   ├── ArenaController.php         # Quản lý phòng đấu trường trực tiếp
│   │   │   ├── ActivityController.php      # Heatmap & biểu đồ năng suất
│   │   │   └── AdminController.php         # Quản trị hệ thống & phân quyền RBAC
│   │   ├── Models/                         # Eloquent Models (User, PhrasalVerb, ...)
│   │   └── Services/
│   │       └── GeminiApiService.php        # Dịch vụ AI Gemini & Grader chuẩn hóa
│   ├── database/
│   │   ├── data/
│   │   │   └── phrasal_verbs_and_phrases.json # 945 Cụm từ và mẫu câu nạp DB
│   │   ├── migrations/                     # Định nghĩa cấu trúc bảng CSDL
│   │   └── seeders/                        # Bộ 10 Seeder dữ liệu mẫu đồ sộ
│   ├── routes/
│   │   └── api.php                         # Khai báo toàn bộ Endpoint RESTful API
│   └── composer.json
│
├── frontend/                               # FRONTEND VUE 3 + VITE
│   ├── src/
│   │   ├── assets/                         # CSS, Bootstrap Icons, hình ảnh
│   │   ├── components/                     # Các Modal dùng chung (DailyQuiz, ...)
│   │   ├── composables/
│   │   │   └── useSpeech.js                # Web Speech API (Phát âm TTS & Thu âm STT)
│   │   ├── layouts/
│   │   │   ├── UserLayout.vue              # Layout Header 6 mục động & Footer
│   │   │   └── AdminLayout.vue             # Layout trang Quản trị viên
│   │   ├── stores/
│   │   │   └── authStore.js                # Pinia Store quản lý State toàn cục
│   │   ├── views/
│   │   │   ├── user/                       # Giao diện người học
│   │   │   │   ├── HomeView.vue            # Dashboard, Heatmap, Cây tri thức
│   │   │   │   ├── CurriculumView.vue      # Lộ trình 25 Chủ đề IELTS
│   │   │   │   ├── DailyStudyView.vue      # Học theo ngày Oxford 3000™
│   │   │   │   ├── GrammarView.vue         # Danh sách 12 Thì kèm tiến độ %
│   │   │   │   ├── GrammarDetailView.vue   # Chi tiết thì, dịch câu AI, ✓ ĐÃ XONG
│   │   │   │   ├── PhrasalVerbsView.vue    # 100 Phrasal verbs & 1000 cụm từ 17 phần
│   │   │   │   ├── ArenaView.vue           # Phòng thi đấu trắc nghiệm trực tiếp
│   │   │   │   ├── KnowledgeTreeView.vue   # Vườn Cây Tri Thức 30 Level
│   │   │   │   ├── StatsView.vue           # Báo cáo năng suất học tập
│   │   │   │   ├── DictionaryView.vue      # Tra cứu từ điển 3000 từ
│   │   │   │   ├── IpaView.vue             # Bảng 44 âm IPA tương tác
│   │   │   │   ├── TipsView.vue            # Mẹo học tiếng Anh hiệu quả
│   │   │   │   └── ProfileView.vue         # Trang cá nhân & Tùy chỉnh Header 6 mục
│   │   │   └── admin/                      # Giao diện quản trị viên
│   │   ├── router/index.js                 # Cấu hình Vue Router & Route Guards
│   │   ├── App.vue
│   │   └── main.js
│   ├── package.json
│   └── vite.config.js
│
├── start.bat                               # SCRIPT KHỞI CHẠY 1-CLICK TỰ ĐỘNG
└── README.md                               # TÀI LIỆU HƯỚNG DẪN DỰ ÁN
```

---

## 🔧 8. XỬ LÝ SỰ CỐ THƯỜNG GẶP (TROUBLESHOOTING)

1. **Lỗi không kết nối được Database (`Connection refused` hoặc `Access denied`)**:
   - Kiểm tra MySQL trong XAMPP hoặc Laragon đã được Bật (Start) hay chưa.
   - Kiểm tra cổng kết nối trong `backend/.env` (mặc định là `3306`).
2. **Lỗi microphone luyện nói không nhận diện giọng**:
   - Trình duyệt yêu cầu cấp quyền truy cập Microphone. Hãy nhấp vào biểu tượng ổ khóa bên cạnh thanh địa chỉ trình duyệt và chọn **Cho phép (Allow)** Microphone.
   - Tính năng nhận diện giọng nói hoạt động tối ưu nhất trên trình duyệt Google Chrome, Microsoft Edge hoặc Safari.
3. **Khi mất kết nối Internet hoặc không có Gemini API Key**:
   - Hệ thống được trang bị cơ chế **Fallback Grader thông minh** tự động chấm điểm và phản hồi chuẩn xác cho học viên mà không phát sinh bất kỳ lỗi nào.

---

## 📄 9. BẢN QUYỀN & TÁC GIẢ

- **Tác giả / Nhà phát triển chính**: Phùng Văn Cường ([cuongphung1810@gmail.com](mailto:cuongphung1810@gmail.com))
- **Dự án**: Hệ thống học tiếng Anh toàn diện OxVocab™ (Đồ án tốt nghiệp / Khóa luận học tập).
- **Giấy phép (License)**: Phát hành theo giấy phép mã nguồn mở [MIT License](LICENSE).

---
*Chúc bạn có những trải nghiệm học tập và nghiên cứu tuyệt vời cùng **OXVOCAB™**! 🚀*
