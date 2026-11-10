# Oxford 3000 Learning Platform - Guidelines

## 1. Kiến Trúc Dự Án (BẮT BUỘC TUÂN THỦ 100%)

Dự án này là cấu trúc Monorepo tách biệt hoàn toàn giữa Backend và Frontend:

- **Thư mục `backend/`**: Chứa 100% mã nguồn Backend Laravel API (PHP, Artisan, Models, Controllers, Migrations, Seeders, Routes, Config, Composer, Vendor).
  - Mọi thao tác lệnh PHP / Artisan / Composer BẮT BUỘC thực hiện bên trong thư mục `backend/` (VD: `cd backend && php artisan ...`, `cd backend && composer ...`).
  - **TUYỆT ĐỐI KHÔNG** tạo bất kỳ file hay thư mục nào của Backend ra thư mục gốc (`Web English 3000 từ vựng`).

- **Thư mục `frontend/`**: Chứa 100% mã nguồn Frontend Vue 3 + Vite (Components, Views, Stores, Composables, Router, Assets, package.json, node_modules).
  - Mọi thao tác lệnh Node.js / NPM BẮT BUỘC thực hiện bên trong thư mục `frontend/` (VD: `npm --prefix frontend run dev`, `cd frontend && npm install`).
  - **TUYỆT ĐỐI KHÔNG** tạo bất kỳ file hay thư mục nào của Frontend ra thư mục gốc (`Web English 3000 từ vựng`).

- **Thư mục gốc**: CHỈ chứa:
  - `backend/`
  - `frontend/`
  - `Tài liệu tiếng Anh/`
  - `.editorconfig`, `.gitattributes`, `.gitignore`, `AGENTS.md`, `CLAUDE.md`, `README.md`, `start.bat`
  - Tuyệt đối không để tồn tại `node_modules/`, `vendor/`, `app/`, `resources/`, `public/` ở thư mục gốc.

## 2. Lệnh Khởi Chạy
- **Toàn bộ hệ thống**: Chạy file `start.bat` ở thư mục gốc.
- **Backend API**: `cd backend && php artisan serve --port=8000`
- **Frontend SPA**: `cd frontend && npm run dev`

