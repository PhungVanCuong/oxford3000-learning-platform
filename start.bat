@echo off
chcp 65001 >nul
title Oxford 3000 - Khoi Dong He Thong Hoc Tieng Anh Toan Dien
color 0B

echo ===============================================================================
echo            HE THONG HOC TIENG ANH TOAN DIEN OXFORD 3000 VA NGU PHAP
echo            Full-Stack: Vue 3 (Vite + Pinia) + Laravel API + MySQL
echo ===============================================================================
echo.

:: 0. Don dep moi tien trinh cu tren cong 8000 va 5173 truoc khi khoi dong
echo [0/4] Kiem tra va giai phong cong 8000, 5173...
taskkill /F /FI "WINDOWTITLE eq Backend API (Laravel)*" /T >nul 2>nul
taskkill /F /FI "WINDOWTITLE eq Frontend (Vue 3 + Vite)*" /T >nul 2>nul
powershell -NoProfile -Command "Get-NetTCPConnection -LocalPort 8000, 5173 -ErrorAction SilentlyContinue | ForEach-Object { Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue }" >nul 2>nul

:: 1. Kiem tra moi truong PHP
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [LOI] Khong tim thay PHP tren may tinh cua ban!
    echo Vui long cai dat PHP 8.2+ va them vao bien moi truong PATH.
    pause
    exit /b 1
)

:: 2. Kiem tra moi truong Node.js
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [LOI] Khong tim thay Node.js tren may tinh cua ban!
    echo Vui long cai dat Node.js va them vao bien moi truong PATH.
    pause
    exit /b 1
)

echo [1/4] Kiem tra va dam bao Database MySQL san sang...
cd backend
php -r "try { $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', ''); $pdo->exec('CREATE DATABASE IF NOT EXISTS oxford_english CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'); echo 'MySQL Database oxford_english: OK' . PHP_EOL; } catch (Exception $e) { echo 'Canh bao ket noi MySQL: ' . $e->getMessage() . PHP_EOL; }"

:: Kiem tra neu can chay migration va seeder
php artisan migrate --force >nul 2>nul
php artisan db:seed --class=PhrasalVerbSeeder --force >nul 2>nul
php artisan db:seed --class=CurriculumRoadmapSeeder --force >nul 2>nul
cd ..

echo.
echo [2/4] Khoi chay Backend Laravel API tren cong 8000...
start "Backend API (Laravel)" /min cmd /c "cd backend && php artisan serve --port=8000"

echo.
echo [3/4] Khoi chay Frontend Vue 3 tren cong 5173...
start "Frontend (Vue 3 + Vite)" /min cmd /c "cd frontend && npm run dev"

echo.
echo [4/4] Dang mo trinh duyet Web...
timeout /t 3 /nobreak >nul 2>nul || ping 127.0.0.1 -n 4 >nul
start http://localhost:5173

echo.
echo ===============================================================================
echo   CHUC MUNG! HE THONG DA DUOC KHOI CHAY THANH CONG!
echo.
echo   - Giao dien Web hoc tap : http://localhost:5173
echo   - Backend RESTful API   : https://beoxvocab.deloydz.io.vn (hoac http://localhost:8000/api)
echo.
echo   Luu y: Cua so nay dang quan ly he thong.
echo   -----------------------------------------------------------------------------
echo   [HUONG DAN DUNG HE THONG]:
echo   - Nhan [Enter] hoac phim bat ky de DUNG he thong va TU DONG TAT HET TERMINAL!
echo   - Hoac an Ctrl + C de dung va dong het terminal!
echo ===============================================================================
echo.
pause

echo.
echo ===============================================================================
echo   Dang tat cac terminal va giai phong he thong...
echo ===============================================================================
taskkill /F /FI "WINDOWTITLE eq Backend API (Laravel)*" /T >nul 2>nul
taskkill /F /FI "WINDOWTITLE eq Frontend (Vue 3 + Vite)*" /T >nul 2>nul
powershell -NoProfile -Command "Get-NetTCPConnection -LocalPort 8000, 5173 -ErrorAction SilentlyContinue | ForEach-Object { Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue }" >nul 2>nul
echo.
echo   [OK] Da tat toan bo cac terminal Backend, Frontend va tien trinh chay ngam!
echo   He thong da duoc giai phong an toan. Hen gap lai!
echo ===============================================================================
echo.
timeout /t 2 /nobreak >nul 2>nul || ping 127.0.0.1 -n 3 >nul
exit /b 0
