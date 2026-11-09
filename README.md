# 🎓 OXVOCAB™ - NỀN TẢNG HỌC TIẾNG ANH & LỘ TRÌNH TỪ VỰNG TOÀN DIỆN
### *Full-Stack EdTech Architecture: Vue 3 (Vite 8) + Laravel 12 API + MySQL + AI Google Gemini + Google OAuth 2.0*

[![Vue 3](https://img.shields.io/badge/Frontend-Vue%203%20(Vite%208)-42b883?style=for-the-badge&logo=vuedotjs&logoColor=white)](https://vuejs.org/)
[![Laravel 12](https://img.shields.io/badge/Backend-Laravel%2012%20API-ff2d20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/Database-MySQL%208.0-00758f?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Google OAuth 2.0](https://img.shields.io/badge/Auth-Google%20OAuth%202.0-4285f4?style=for-the-badge&logo=google&logoColor=white)](https://developers.google.com/identity)
[![Google Gemini AI](https://img.shields.io/badge/AI-Google%20Gemini%202.5%20Flash-4285f4?style=for-the-badge&logo=googlegemini&logoColor=white)](https://ai.google.dev/)
[![Bootstrap 5](https://img.shields.io/badge/UI-Bootstrap%205.3%20%2B%20Icons-7952b3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-emerald.svg?style=for-the-badge)](LICENSE)

---

## 📌 MỤC LỤC CHI TIẾT
1. [Giới Thiệu Tổng Quan & Triết Lý Sư Phạm](#1-giới-thiệu-tổng-quan--triết-lý-sư-phạm)
2. [Bản Đồ Tính Năng Đột Phá](#2-bản-đồ-tính-năng-đột-phá)
3. [Kiến Trúc Kỹ Thuật & Công Nghệ (Tech Stack)](#3-kiến-trúc-kỹ-thuật--công-nghệ-tech-stack)
4. [Mô Hình Dữ Liệu & Kho Tài Nguyên Tri Thức](#4-mô-hình-dữ-liệu--kho-tài-nguyên-tri-thức)
5. [Cổng Xác Thực Google OAuth 2.0 & Toast Notifications](#5-cổng-xác-thực-google-oauth-20--toast-notifications)
6. [Yêu Cầu Môi Trường & Hướng Dẫn Cài Đặt A-Z](#6-yêu-cầu-môi-trường--hướng-dẫn-cài-đặt-a-z)
7. [Cấu Hình Môi Trường (.env) & Google Cloud Console](#7-cấu-hình-môi-trường-env--google-cloud-console)
8. [Cấu Trúc Thư Mục Toàn Bộ Dự Án](#8-cấu-trúc-thư-mục-toàn-bộ-dự-án)
9. [Danh Mục API Endpoints Cốt Lõi](#9-danh-mục-api-endpoints-cốt-lõi)
10. [Hướng Dẫn Dành Cho Quản Trị Viên (Admin & RBAC)](#10-hướng-dẫn-dành-cho-quản-trị-viên-admin--rbac)
11. [Xử Lý Sự Cố Thường Gặp (Troubleshooting)](#11-xử-lý-sự-cố-thường-gặp-troubleshooting)
12. [Bản Quyền & Thông Tin Tác Giả](#12-bản-quyền--thông-tin-tác-giả)

---

## 📖 1. GIỚI THIỆU TỔNG QUAN & TRIẾT LÝ SƯ PHẠM

### 1.1. Tên Đề Tài & Sứ Mệnh
> **OXVOCAB™** là nền tảng học tiếng Anh trực tuyến toàn diện, tích hợp công nghệ AI và Spaced Repetition System (SRS). Dự án được thiết kế chuyên biệt để giải quyết dứt điểm các rào cản lớn nhất của người học: học vẹt, mau quên, thiếu tính ứng dụng thực chiến và cảm giác nhàm chán khi tự học một mình.

### 1.2. Ba Trụ Cột Triết Lý Sư Phạm Cốt Lõi
1. **"Xây dựng kiến thức nền tảng trước ➔ Luyện tập & Đánh giá phản xạ sau"**:
   Học viên được tiếp cận từ vựng qua thẻ Flashcard 3D trực quan, nghe phát âm giọng Mỹ (US Speech), đối chiếu ngữ cảnh ví dụ song ngữ và luyện nói qua Microphone trước khi bước vào các bài kiểm tra trắc nghiệm hay bài thi vượt cấp.
2. **Thuật Toán Lặp Lại Ngắt Quãng (Spaced Repetition System - SRS)**:
   Hệ thống tự động ghi nhận trạng thái từ vựng (*"Đã nhớ"*, *"Cần ôn"*), tính toán chu kỳ suy giảm trí nhớ (Forgetting Curve) và đưa từ vào **Bộ thẻ ôn tập** đúng vào thời điểm vàng để biến từ vựng ngắn hạn thành phản xạ dài hạn.
3. **Gamification & Trợ Lý Trí Tuệ Nhân Tạo AI (Google Gemini 2.5 Flash)**:
   - **Vườn Cây Tri Thức (Knowledge Tree)**: 30 cấp độ tiến hóa từ Hạt mầm đến Đại thụ thần thoại Yggdrasil theo số điểm XP tích lũy.
   - **Đấu Trường Quiz Trực Tuyến (Live Arena)**: Tạo phòng thi đấu trắc nghiệm thời gian thực giữa các học viên, bảng xếp hạng Top 1 - 2 - 3 vinh danh.
   - **Chấm Điểm Dịch Thuật AI Thông Minh**: Nhận diện ngữ nghĩa, chấp nhận cách viết linh hoạt và hỗ trợ bộ Fallback Grader chấm điểm tức thì ngay cả khi ngoại tuyến.

---

## 🌟 2. BẢN ĐỒ TÍNH NĂNG ĐỘT PHÁ

```
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                                HỆ THỐNG OXVOCAB™ ECOSYSTEM                             │
└───────────────────────────────────────────┬────────────────────────────────────────────┘
                                            │
       ┌────────────────────────────────────┼────────────────────────────────────┐
       ▼                                    ▼                                    ▼
┌──────────────┐                     ┌──────────────┐                     ┌──────────────┐
│  TỪ VỰNG &   │                     │   NGỮ PHÁP   │                     │ GAMIFICATION │
│   LỘ TRÌNH   │                     │  & LUYỆN NÓI │                     │  & XÁC THỰC  │
└──────┬───────┘                     └──────┬───────┘                     └──────┬───────┘
       │                                    │                                    │
       ├─► 3 Thẻ Chọn Nội Dung Học          ├─► 12 Thì Ngữ Pháp Thực Chiến       ├─► Google OAuth 2.0
       ├─► Lộ Trình 25 Chủ Đề IELTS         ├─► Luyện Dịch Câu Chấm Điểm AI      ├─► Toast Toàn Cục
       ├─► Lộ Trình CEFR 6 Bậc (A1-C2)      ├─► Bảng Thể Bị Động & Ngữ Âm        ├─► Đấu Trường Quiz
       ├─► Học Theo Ngày (Oxford 3000)      ├─► 1.000 Cụm Từ Thông Dụng          ├─► Vườn Cây Tri Thức
       ├─► Flashcard 3D Active Recall       ├─► 100 Phrasal Verbs (A-W)          ├─► Bộ Thẻ Ôn Tập
       └─► Tra Cứu & Bảng 44 Âm IPA         └─► Web Speech API Luyện Nói         └─► Lịch Sử Làm Bài
```

### 2.1. Cụm 3 Thẻ "Chọn Nội Dung Học" (Tâm Điểm Trang Chủ)
Ngay tại giao diện chính, học viên được tiếp cận 3 phân hệ trụ cột với thiết kế Dark Teal sang trọng:
1. **Ngữ pháp theo bậc CEFR**: 101 bài học trải đều từ A1 đến C2 kèm lý thuyết ngắn gọn, công thức và bài tập 3 mức độ Dễ - Vừa - Khó.
2. **Từ vựng theo chủ đề**: Hơn 5.000 từ học thuật chia theo 25 chủ đề IELTS thực chiến (Kinh tế, Y tế, Môi trường, Công nghệ...).
3. **Từ vựng theo bậc CEFR**: Phân tầng 6 bậc chuẩn khung tham chiếu Châu Âu từ A1 đến C2, xây chắc gốc từ vựng nền tảng.

### 2.2. Thẻ Flashcard 3D Toàn Diện & Tính Năng Gõ Lại Từ (Active Recall)
* **Hiệu ứng lật 3 chiều CSS 3D**: Lật mượt mà không xung đột layout, bảo toàn tỷ lệ ảnh, không giật lag.
* **Tích hợp thanh Active Recall**: Cho phép học viên gõ chính tả từ vựng tiếng Anh ngay trên thẻ để kiểm tra phản xạ trí nhớ.
* **Nút "Xem kết quả" thông minh**: Điền ngay đáp án chuẩn xác và đọc phát âm mà tuyệt đối không bị lật ngược thẻ, không ép buộc phải gõ trước.
* **Phím tắt chuẩn Desktop**: Space (Lật thẻ), Phím `Q` (Từ trước), Phím `E` (Từ tiếp theo), Phím `R` (Nghe phát âm US), Phím `T` (Luyện nói AI), Phím `S` (Đã nhớ), Phím `X` (Cần ôn).

### 2.3. Lộ Trình CEFR 6 Bậc (A1 - C2) Chuẩn Hóa
* Tích hợp trọn vẹn dữ liệu từ 6 tập tin chuẩn Châu Âu: **A1** (890 từ / 45 bài), **A2** (792 từ / 40 bài), **B1** (688 từ / 34 bài), **B2** (530 từ / 27 bài), **C1** (1.300 từ / 65 bài), **C2** (722 từ / 36 bài).
* Cấu trúc tịnh tiến thông minh: Bài 1 (12 từ), Bài 2 (13 từ) tăng dần đều đến Bài 36+ (28 từ).
* Tích hợp 4 chế độ rèn luyện: Thẻ Flashcard 3D, Luyện Nghe & Viết, Luyện Phát âm AI Microphone và Trắc nghiệm tính điểm XP.

### 2.4. Học Theo Ngày (Kho Từ Vựng Oxford 3000™)
* Tự do điều chỉnh khối lượng học: **10, 15, 20, 25, 30 hoặc 50 từ/ngày**.
* Thuật toán phân trang động tự động tính toán tổng số ngày hoàn thành mà **không bao giờ làm mất** tiến độ của các từ đã học trước đó.

### 2.5. 12 Thì Ngữ Pháp & Luyện Dịch Câu AI Gemini
* Hệ thống 15 chuyên đề bao quát 12 thì ngữ pháp, cấu trúc câu chủ động / bị động và quy tắc phát âm đuôi `-ed`, `-s/es`.
* **AI Gemini 2.5 Flash Grader**: Đánh giá câu dịch tự nhiên, phân tích ngữ cảnh, chấp nhận các cách diễn đạt tương đương (ví dụ: `reading, booking` hoặc `reading and booking`).
* **Offline Grader Fallback**: Luôn có bộ máy chấm điểm nội bộ dự phòng, đảm bảo ứng dụng không bao giờ bị đứng hình khi mất mạng.

### 2.6. Cụm Từ & 100 Phrasal Verbs
* **1.000 Cụm từ thông dụng**: 850 mẫu câu giao tiếp chia đều 17 phần (50 câu/phần).
* **100 Cụm động từ A-W**: Tách chi tiết các từ cấu thành (Verb + Particle), giải nghĩa song ngữ và bài tập điền từ (+15 XP/câu).

### 2.7. Bộ Thẻ Ôn Tập (Review Deck) & Lịch Sử Làm Bài (History)
* **Bộ thẻ ôn tập**: Quản lý các từ được đánh dấu *"Cần ôn"*, hỗ trợ tạo thư mục riêng, ôn tập Spaced Repetition theo lịch trình tới hạn.
* **Lịch sử làm bài**: Lưu trữ mọi lượt làm bài trắc nghiệm và kiểm tra ngữ pháp, thống kê Lượt đã làm, Lượt đạt, Điểm trung bình % và cho phép xem lại chi tiết từng câu sai.

---

## 🛠️ 3. KIẾN TRÚC KỸ THUẬT & CÔNG NGHỆ (TECH STACK)

### 3.1. Frontend Stack
| Công nghệ | Phiên bản | Vai trò & Đặc điểm |
| :--- | :--- | :--- |
| **Vue 3** | `^3.5.x` | Reactive UI với Composition API (`<script setup>`) |
| **Vite** | `^8.3.x` | Công cụ đóng gói siêu tốc, Hot Module Replacement (HMR) tức thì |
| **Pinia** | `^3.0.x` | Quản lý trạng thái toàn cục (`authStore`, `dailyStore`, `reviewStore`, `toastStore`) |
| **Vue Router** | `^4.6.x` | SPA routing, lazy-loading views, authentication guards |
| **Axios** | `^1.7.x` | HTTP client giao tiếp API, interceptor tự động đính kèm Bearer Token |
| **Bootstrap 5.3 & Icons**| `5.3.3` | Grid system responsive, giao diện chuẩn Dark/Light Mode, bộ icon phong phú |
| **Canvas Confetti** | `^1.9.x` | Hiệu ứng pháo hoa chúc mừng khi hoàn thành bài học và lên cấp |
| **Web Speech API** | Native | Text-to-Speech (US/UK accent) & Speech Recognition luyện nói |

### 3.2. Backend Stack
| Công nghệ | Phiên bản | Vai trò & Đặc điểm |
| :--- | :--- | :--- |
| **Laravel Framework** | `12.x / 13.x` | RESTful API backend, Controller, Request Validation, Middleware |
| **PHP** | `8.2+ / 8.3+` | Ngôn ngữ máy chủ hiện đại, typed properties, match expressions |
| **Laravel Sanctum** | `^4.0` | Cấp phát và xác thực API Token an toàn, stateless SPA token |
| **Google Gemini API** | `gemini-2.5-flash` | Trí tuệ nhân tạo chấm điểm dịch thuật, gợi ý ngữ pháp và ví dụ |
| **MySQL** | `8.0+` | Cơ sở dữ liệu quan hệ, bảng mã `utf8mb4_unicode_ci` |
| **Eloquent ORM** | Native | Quan hệ dữ liệu, eager loading, query scopes tối ưu tốc độ |

---

## 💾 4. MÔ HÌNH DỮ LIỆU & KHO TÀI NGUYÊN TRI THỨC

Hệ thống được thiết kế với cơ sở dữ liệu quy mô hơn 10.000 mục dữ liệu giáo dục chuẩn mực:

```
┌────────────────────────────────────────────────────────────────────────┐
│                          SƠ ĐỒ THỰC THỂ CHÍNH                          │
├─────────────────┬───────────────────┬──────────────────┬───────────────┤
│     users       │   vocabularies    │ curriculum_terms │   grammars    │
│  (google_id)    │   (Oxford 3000)   │   (25 Topics)    │   (12 Tenses) │
├─────────────────┼───────────────────┼──────────────────┼───────────────┤
│  user_progress  │  phrasal_verbs    │  arena_rooms     │  study_logs   │
│   (SRS Status)  │  (1000 Phrases)   │  (Live Quiz)     │  (Heatmap)    │
└─────────────────┴───────────────────┴──────────────────┴───────────────┘
```

* **3.351 từ vựng Oxford 3000™**: Đầy đủ phiên âm IPA Mỹ, định nghĩa tiếng Việt, phân loại từ, cấp độ CEFR và câu ví dụ song ngữ.
* **5.000 từ vựng Lộ trình IELTS**: Chia đều cho 25 chủ đề lớn x 10 chặng bài học chuyên sâu.
* **4.922 mục từ CEFR 6 Bậc**: Khung A1 (890 từ), A2 (792 từ), B1 (688 từ), B2 (530 từ), C1 (1.300 từ), C2 (722 từ).
* **945 Cụm từ & Phrasal Verbs**: 100 Cụm động từ A-W + 850 mẫu câu giao tiếp 17 phần.
* **15 Chuyên đề Ngữ pháp**: 12 thì thực chiến, thể bị động, ngữ âm đuôi `-ed`, `-s/es`.

---

## 🔐 5. CỔNG XÁC THỰC GOOGLE OAUTH 2.0 & TOAST NOTIFICATIONS

### 5.1. Cơ Chế Đăng Nhập Google Popup Độc Lập
Hệ thống triển khai cơ chế xác thực không gián đoạn:
1. **Popup Window (500x620)**: Mở cửa sổ đăng nhập Google giữa màn hình, giữ nguyên tiến độ thẻ học và bài kiểm tra ở trình duyệt ban đầu.
2. **Cơ chế Đồng bộ Đa kênh (Multi-Channel Cross-Tab Sync)**:
   - **Kênh 1 - `BroadcastChannel('oxvocab_google_auth')`**: Truyền token và thông tin người dùng ngay lập tức giữa các tab cùng origin mà không phụ thuộc vào `window.opener`.
   - **Kênh 2 - `LocalStorage Event ('oxford_oauth_sync')`**: Kích hoạt sự kiện `storage` ở trình duyệt ban đầu.
   - **Kênh 3 - `window.opener.postMessage`**: Dự phòng cho popup truyền thống.
3. **Tự động đóng tab Google & Chuyển hướng**:
   - Khi đăng nhập thành công, tab Google tự động đóng lại trong vòng 80ms.
   - Trình duyệt ban đầu nhận diện phiên đăng nhập, nạp Avatar Google + Điểm XP và tự động đưa học viên về Trang chủ (`/`).
4. **Đăng xuất an toàn**: Nhấp "Đăng xuất" sẽ xóa phiên và đưa học viên bay thẳng về màn hình Đăng nhập (`/login`).

### 5.2. Hệ Thống Toast Notifications Toàn Cục
Thay thế hoàn toàn 100% các hộp thoại `alert()` truyền thống bằng các thẻ thông báo Glassmorphism hiện đại ở góc phải trên cùng (PC) và căn giữa trên cùng (Mobile):
* `toast.success`: Đăng nhập thành công, trả lời đúng chính tả Active Recall (+10 XP).
* `toast.mastered`: Đánh dấu *"Đã nhớ"* thẻ từ vựng (+5 XP).
* `toast.review`: Đánh dấu *"Cần ôn tập"* và đưa vào Bộ thẻ ôn tập.
* `toast.saved`: Đã lưu thẻ vào bộ thẻ, lưu cài đặt cấu hình menu Header.
* `toast.error`: Thông báo lỗi mạng, sai đáp án trắc nghiệm.
* `toast.warning`: Cảnh báo giới hạn ký tự, thiếu dữ liệu form.
* `toast.streak`: Vinh danh chuỗi ngày học liên tục.

---

## 💻 6. YÊU CẦU MÔI TRƯỜNG & HƯỚNG DẪN CÀI ĐẶT A-Z

### 6.1. Yêu Cầu Tiên Quyết
* **PHP**: `8.2` hoặc `8.3+` (kèm các extension: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`).
* **Composer**: Phiên bản `2.x`.
* **Node.js**: Phiên bản `18.x`, `20.x` hoặc `22.x` (kèm `npm`).
* **MySQL / MariaDB**: Phiên bản `8.0+` (Khuyến nghị dùng qua **XAMPP**, **Laragon**, hoặc MySQL Service).
* **Git**: Quản lý mã nguồn.

---

### 6.2. Cách 1: Khởi Chạy Siêu Tốc 1-Click Với `start.bat` (Windows)
1. Bật máy chủ **MySQL** (trong Laragon hoặc XAMPP).
2. Nhấp đúp chuột vào file **`start.bat`** tại thư mục gốc của dự án.
3. Script sẽ tự động:
   - Kiểm tra môi trường PHP, Composer, Node.js và npm.
   - Khởi tạo Database MySQL `oxford_english` nếu chưa tồn tại.
   - Chạy Migration và nạp bộ Seeder dữ liệu đồ sộ.
   - Bật đồng thời Backend API (`http://localhost:8000`) và Frontend SPA (`http://localhost:5173`).
   - Tự động mở trình duyệt web đến địa chỉ: `http://localhost:5173`.

---

### 6.3. Cách 2: Hướng Dẫn Cài Đặt Thủ Công Từng Bước

#### Bước 1: Tải mã nguồn về máy
```bash
git clone https://github.com/PhungVanCuong/oxford3000-learning-platform.git
cd "Web English 3000 từ vựng"
```

#### Bước 2: Thiết lập Backend (Laravel API)
```bash
# 1. Di chuyển vào thư mục backend
cd backend

# 2. Cài đặt các gói phụ thuộc PHP
composer install

# 3. Tạo file cấu hình môi trường .env từ bản mẫu
cp .env.example .env

# 4. Khởi tạo khóa ứng dụng Laravel
php artisan key:generate

# 5. Mở file .env và cấu hình kết nối MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=oxford_english
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Chạy Migration và nạp dữ liệu mẫu
php artisan migrate --force
php artisan db:seed --force

# 7. Khởi chạy máy chủ Backend API
php artisan serve --port=8000
```

#### Bước 3: Thiết lập Frontend (Vue 3 + Vite)
```bash
# Mở một cửa sổ Terminal mới tại thư mục gốc của dự án:
cd frontend

# 1. Cài đặt các thư viện Node.js
npm install

# 2. Khởi chạy máy chủ phát triển Vite
npm run dev
```
Truy cập ứng dụng trên trình duyệt tại: **`http://localhost:5173`**.

---

## ⚙️ 7. CẤU HÌNH MÔI TRƯỜNG (.ENV) & GOOGLE CLOUD CONSOLE

### 7.1. Cấu Hình Biến Môi Trường Backend (`backend/.env`)
```env
APP_NAME=OxVocab
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5173

# CƠ SỞ DỮ LIỆU MYSQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oxford_english
DB_USERNAME=root
DB_PASSWORD=

# GOOGLE OAUTH 2.0 CREDENTIALS
GOOGLE_CLIENT_ID=your_google_client_id_here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# GOOGLE GEMINI AI KEY (Chấm điểm dịch câu & giải thích ngữ pháp)
GEMINI_API_KEY=your_gemini_api_key_here
```

### 7.2. Cấu Hình Biến Môi Trường Frontend (`frontend/.env`)
```env
VITE_API_BASE_URL=http://localhost:8000/api
VITE_GOOGLE_CLIENT_ID=your_google_client_id_here.apps.googleusercontent.com
VITE_APP_NAME="OXVOCAB - Nền Tảng Học Tiếng Anh Toàn Diện"
```

### 7.3. Thiết Lập Trên Google Cloud Console (Google Auth Platform)
1. Truy cập [Google Cloud Console](https://console.cloud.google.com/) và tạo project: `OxVocab English Platform`.
2. Vào **Google Auth Platform** ➜ Nhấp **Get started**:
   * **App name**: `OxVocab English Platform`
   * **User support email**: Chọn email của bạn.
   * **Audience**: Chọn **External**.
3. Vào menu **Clients** ở cột trái ➜ Chọn **Create Client**:
   * **Application type**: Chọn **Web application**.
   * **Authorized JavaScript origins**:
     ```
     http://localhost:5173
     http://localhost:8000
     https://oxvocab.deloydz.io.vn
     https://beoxvocab.deloydz.io.vn
     ```
   * **Authorized redirect URIs**:
     ```
     http://localhost:8000/auth/google/callback
     http://localhost:5173/auth/google/callback
     https://beoxvocab.deloydz.io.vn/auth/google/callback
     https://oxvocab.deloydz.io.vn/auth/google/callback
     ```
4. Sao chép **Client ID** và **Client Secret** dán vào các file `.env` tương ứng.

---

## 📁 8. CẤU TRÚC THƯ MỤC TOÀN BỘ DỰ ÁN

```
Web English 3000 từ vựng/
├── backend/                                   # MÁY CHỦ API LARAVEL 12/13
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   │   ├── AuthController.php             # Xác thực truyền thống & quản lý tài khoản
│   │   │   ├── GoogleAuthController.php       # Xác thực Google OAuth 2.0 & Token
│   │   │   ├── DailyStudyController.php       # Học theo ngày (Chunking 10-50 từ/ngày)
│   │   │   ├── CurriculumRoadmapController.php# Lộ trình 25 Chủ đề IELTS
│   │   │   ├── GrammarController.php          # 12 Thì ngữ pháp & chấm câu AI
│   │   │   ├── PhrasalVerbController.php      # 100 Phrasal Verbs & 1000 cụm từ
│   │   │   ├── ArenaController.php            # Đấu trường trực tuyến thời gian thực
│   │   │   ├── ActivityController.php         # Thống kê năng suất & GitHub Heatmap
│   │   │   └── AdminController.php            # Quản trị hệ thống & phân quyền RBAC
│   │   ├── Models/
│   │   │   ├── User.php                       # Model người dùng (hỗ trợ google_id)
│   │   │   ├── Vocabulary.php                 # Model từ vựng Oxford 3000
│   │   │   ├── CurriculumTopic.php            # Model chủ đề IELTS
│   │   │   ├── CurriculumTerm.php             # Model mục từ lộ trình
│   │   │   └── Grammar.php                    # Model chuyên đề ngữ pháp
│   │   └── Services/
│   │       └── GeminiApiService.php           # Tích hợp AI Gemini & Grader dự phòng
│   ├── database/
│   │   ├── migrations/                        # Các tệp tạo bảng CSDL
│   │   └── seeders/                           # 10 bộ Seeder nạp dữ liệu đồ sộ
│   ├── resources/views/auth/
│   │   └── google-popup-callback.blade.php    # Cầu nối đóng popup Google OAuth
│   └── routes/
│       ├── api.php                            # Endpoints RESTful API
│       └── web.php                            # Web routes chuyển hướng OAuth
│
├── frontend/                                  # ỨNG DỤNG CLIENT VUE 3 + VITE
│   ├── public/
│   │   └── images/learning-content/           # Hình minh họa 3 thẻ Chọn nội dung học
│   ├── src/
│   │   ├── assets/                            # Stylesheets & Vector graphics
│   │   ├── components/
│   │   │   ├── AuthHeaderWidget.vue           # Widget Header đăng nhập / Avatar dropdown
│   │   │   ├── GoogleLoginButton.vue          # Nút đăng nhập Google đa năng
│   │   │   ├── LearningContentCards.vue       # Cụm 3 Thẻ Chọn Nội Dung Học
│   │   │   ├── FlashcardPlayer.vue            # Trình lật thẻ 3D & Active Recall
│   │   │   ├── SmartPagination.vue            # Bộ phân trang thông minh 50 mục/trang
│   │   │   └── toast/                         # Hệ thống thông báo Toast toàn cục
│   │   │       ├── ToastContainer.vue         # Khung chứa thông báo nổi
│   │   │       └── ToastItem.vue              # Thẻ thông báo kính mờ Glassmorphism
│   │   ├── composables/
│   │   │   ├── useGoogleAuth.js               # Quản lý Popup OAuth & Multi-channel Sync
│   │   │   ├── useToast.js                    # Bắn thông báo: success, mastered, review...
│   │   │   └── useSpeech.js                   # Web Speech API (Phát âm & Luyện nói)
│   │   ├── layouts/
│   │   │   ├── UserLayout.vue                 # Layout học viên (Header 6 mục & Mobile Nav)
│   │   │   └── AdminLayout.vue                # Layout quản trị viên
│   │   ├── stores/
│   │   │   ├── authStore.js                   # Pinia store xác thực & phân quyền
│   │   │   ├── reviewStore.js                 # Pinia store Bộ thẻ ôn tập SRS
│   │   │   └── toastStore.js                  # Pinia store danh sách Toasts
│   │   ├── views/
│   │   │   ├── user/                          # Giao diện học viên
│   │   │   │   ├── HomeView.vue               # Trang chủ, Dashboard & Cây tri thức
│   │   │   │   ├── CurriculumView.vue         # 25 Chủ đề IELTS
│   │   │   │   ├── DailyStudyView.vue         # Học theo ngày Oxford 3000™
│   │   │   │   ├── GrammarView.vue            # Danh sách 12 thì ngữ pháp
│   │   │   │   ├── GrammarDetailView.vue      # Chi tiết thì & dịch câu AI
│   │   │   │   ├── ReviewSpacedRepetitionView # Bộ thẻ ôn tập SRS
│   │   │   │   ├── HistoryView.vue            # Lịch sử bài học & bài thi
│   │   │   │   ├── ArenaView.vue              # Phòng thi đấu trực tiếp
│   │   │   │   └── ProfileView.vue            # Trang cá nhân & cấu hình
│   │   │   ├── admin/                         # Giao diện quản trị viên
│   │   │   └── auth/                          # Đăng nhập, Đăng ký, Callback Google
│   │   ├── router/index.js                    # Vue Router & Navigation Guards
│   │   └── App.vue                            # Gốc ứng dụng & Mount Toast toàn cục
│   ├── package.json
│   └── vite.config.js
│
├── start.bat                                  # SCRIPT KHỞI CHẠY SIÊU TỐC 1-CLICK (WINDOWS)
└── README.md                                  # TÀI LIỆU HƯỚNG DẪN DỰ ÁN CHI TIẾT
```

---

## 📡 9. DANH MỤC API ENDPOINTS CỐT LÕI

| Phương thức | Endpoint API | Mô tả chức năng | Quyền hạn |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/auth/register` | Đăng ký tài khoản học viên mới | Public |
| `POST` | `/api/auth/login` | Đăng nhập tài khoản truyền thống | Public |
| `GET` | `/api/auth/google/url` | Lấy URL ủy quyền Google OAuth 2.0 có CSRF State | Public |
| `POST` | `/api/auth/google/callback` | Trao đổi Authorization Code lấy Sanctum Token | Public |
| `POST` | `/api/auth/logout` | Đăng xuất và thu hồi API Token | Authenticated |
| `GET` | `/api/auth/me` | Lấy thông tin tài khoản hiện tại | Authenticated |
| `GET` | `/api/daily/study` | Lấy danh sách từ vựng theo ngày (Chunking 10-50 từ) | Authenticated |
| `POST` | `/api/daily/progress` | Cập nhật trạng thái từ (*"mastered"* / *"learning"*) | Authenticated |
| `GET` | `/api/curriculum/topics` | Danh sách 25 Chủ đề IELTS kèm tiến độ % | Authenticated |
| `GET` | `/api/curriculum/topics/{id}/stage/{stage}` | Lấy từ vựng & bài tập chặng tương ứng | Authenticated |
| `GET` | `/api/grammars` | Danh sách 12 thì ngữ pháp & thể bị động | Authenticated |
| `POST` | `/api/grammars/{id}/check-sentence` | Chấm điểm câu dịch bằng Google Gemini AI | Authenticated |
| `GET` | `/api/phrasal-verbs` | Danh sách 100 Phrasal Verbs & 1000 cụm từ | Authenticated |
| `GET` | `/api/arena/rooms` | Danh sách phòng đấu trường Quiz đang mở | Authenticated |
| `POST` | `/api/arena/rooms` | Mở phòng đấu trường mới | Admin/Tutor |
| `POST` | `/api/arena/rooms/{id}/join` | Tham gia phòng thi đấu bằng mật khẩu | Authenticated |
| `GET` | `/api/admin/users` | Quản lý danh sách tài khoản & phân quyền RBAC | Admin |

---

## 👑 10. HƯỚNG DẪN DÀNH CHO QUẢN TRỊ VIÊN (ADMIN & RBAC)

Hệ thống tích hợp cơ chế phân quyền RBAC 5 cấp bậc nghiêm ngặt:
1. **`admin` (Quản trị viên Cấp cao)**: Toàn quyền quản trị tài khoản, phân quyền, cấu hình hệ thống, xóa bài tập, quản lý toàn bộ dữ liệu.
2. **`moderator` (Quản trị Nội dung)**: Kiểm duyệt và chỉnh sửa kho từ vựng CEFR, câu ví dụ, chủ đề lộ trình.
3. **`editor` (Biên tập viên)**: Thêm mới hoặc hiệu chỉnh các bài học ngữ pháp, cụm từ và đề thi.
4. **`tutor` (Giảng viên / Trợ giảng)**: Mở các phòng thi Đấu Trường Quiz, tạo đề kiểm tra riêng cho lớp và giám sát học viên.
5. **`user` (Học viên tiêu chuẩn)**: Học tập tự do, tham gia thi đấu, tích lũy XP và nuôi Cây Tri Thức.

> **Tài khoản Quản trị viên mặc định:**
> * **Email:** `cuongphung1810@gmail.com`
> * **Mật khẩu:** `password` (hoặc đăng nhập trực tiếp qua nút **Đăng nhập Google**).

---

## ❓ 11. XỬ LÝ SỰ CỐ THƯỜNG GẶP (TROUBLESHOOTING)

#### 1. Lỗi kết nối Cơ sở dữ liệu (`Connection refused` hoặc `Access denied`)
* **Nguyên nhân**: Dịch vụ MySQL chưa được khởi động hoặc sai thông tin đăng nhập trong file `backend/.env`.
* **Khắc phục**: Mở XAMPP hoặc Laragon và nhấp **Start** tại mục MySQL; kiểm tra lại `DB_PORT=3306`, `DB_USERNAME` và `DB_PASSWORD`.

#### 2. Lỗi đăng nhập Google Popup bị chặn (`Popup Blocker`)
* **Nguyên nhân**: Trình duyệt kích hoạt tính năng tự động chặn cửa sổ bật lên.
* **Khắc phục**: Nhấp vào biểu tượng ngăn chặn popup trên thanh địa chỉ của trình duyệt và chọn **"Luôn cho phép cửa sổ bật lên từ trang web này"**. Hệ thống cũng có sẵn cơ chế tự động chuyển hướng tab trực tiếp nếu popup bị chặn.

#### 3. Microphone không thu âm được giọng nói khi luyện phát âm
* **Nguyên nhân**: Trình duyệt chưa được cấp quyền sử dụng thiết bị ghi âm.
* **Khắc phục**: Nhấp vào biểu tượng ổ khóa cạnh thanh địa chỉ ➔ Chuyển quyền **Microphone** sang **Cho phép (Allow)**. Khuyến khích sử dụng Google Chrome, Microsoft Edge hoặc Safari.

#### 4. Giao diện bị thiếu tính năng hoặc chưa nhận mã nguồn mới
* **Khắc phục**: Chạy lệnh xóa cache tối ưu của Laravel:
  ```bash
  cd backend
  php artisan optimize:clear
  ```

---

## 📜 12. BẢN QUYỀN & THÔNG TIN TÁC GIẢ

* **Tác giả / Kỹ sư phát triển**: Phùng Văn Cường
* **Email liên hệ**: [cuongphung1810@gmail.com](mailto:cuongphung1810@gmail.com)
* **GitHub Repository**: [oxford3000-learning-platform](https://github.com/PhungVanCuong/oxford3000-learning-platform)
* **Giấy phép (License)**: Dự án được phân phối theo giấy phép mã nguồn mở **[MIT License](LICENSE)**.

---
<p align="center">
  <strong>OXVOCAB™ — Bứt phá giới hạn, kiến tạo phản xạ tiếng Anh toàn diện cùng AI thông minh! 🚀</strong>
</p>
