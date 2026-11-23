@echo off
chcp 65001 >nul
title OXVOCAB - Nền Tảng Học Tiếng Anh Toàn Diện (Full-Stack)
cd /d "%~dp0"
powershell -NoProfile -ExecutionPolicy Bypass -Command "iex ((Get-Content -LiteralPath '%~f0' -Encoding UTF8 | Select-Object -Skip 6) -join [Environment]::NewLine)"
exit /b %ERRORLEVEL%

# ==============================================================================
# OXVOCAB PROCESS SUPERVISOR (PowerShell Runtime)
# Tự động khởi chạy, giám sát và tắt sạch 100% terminal khi bấm dừng ở VS Code
# ==============================================================================

$host.UI.RawUI.WindowTitle = "OXVOCAB - Nền Tảng Học Tiếng Anh Toàn Diện"

Write-Host "===============================================================================" -ForegroundColor Cyan
Write-Host "         HỆ THỐNG HỌC TIẾNG ANH TOÀN DIỆN OXVOCAB (OXFORD 3000)                " -ForegroundColor Green
Write-Host "         Full-Stack: Vue 3 (Vite + Pinia) + Laravel API + MySQL                " -ForegroundColor White
Write-Host "===============================================================================" -ForegroundColor Cyan
Write-Host ""

$backendProc = $null
$frontendProc = $null

try {
    # 0. Dọn dẹp tiến trình cũ còn sót trên cổng 8000 và 5173 trước khi khởi động
    Write-Host "[0/4] Kiểm tra và giải phóng cổng 8000 & 5173..." -ForegroundColor Gray
    & taskkill /F /FI "WINDOWTITLE eq OxVocab_Backend_8000*" /T 2>$null | Out-Null
    & taskkill /F /FI "WINDOWTITLE eq OxVocab_Frontend_5173*" /T 2>$null | Out-Null
    Get-NetTCPConnection -LocalPort 8000, 5173 -ErrorAction SilentlyContinue | ForEach-Object {
        Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue
    }

    # 1. Kiểm tra môi trường PHP
    $phpCmd = Get-Command php -ErrorAction SilentlyContinue
    if (-not $phpCmd) {
        Write-Host "[LỖI] Không tìm thấy PHP trên máy tính của bạn!" -ForegroundColor Red
        Write-Host "Vui lòng cài đặt PHP 8.2+ và thêm vào biến môi trường PATH." -ForegroundColor Yellow
        Read-Host "Nhấn Enter để thoát"
        exit 1
    }

    # 2. Kiểm tra môi trường Node.js
    $nodeCmd = Get-Command node -ErrorAction SilentlyContinue
    if (-not $nodeCmd) {
        Write-Host "[LỖI] Không tìm thấy Node.js trên máy tính của bạn!" -ForegroundColor Red
        Write-Host "Vui lòng cài đặt Node.js và thêm vào biến môi trường PATH." -ForegroundColor Yellow
        Read-Host "Nhấn Enter để thoát"
        exit 1
    }

    # 3. Kiểm tra và đảm bảo Database MySQL sẵn sàng
    Write-Host "[1/4] Kiểm tra và khởi tạo Database MySQL..." -ForegroundColor Gray
    Push-Location "backend"
    try {
        & php -r "try { `$pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', ''); `$pdo->exec('CREATE DATABASE IF NOT EXISTS oxford_english CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'); echo 'MySQL Database oxford_english: OK' . PHP_EOL; } catch (Exception `$e) { echo 'Canh bao ket noi MySQL: ' . `$e->getMessage() . PHP_EOL; }"
        & php artisan migrate --force *>$null
        & php artisan db:seed --class=PhrasalVerbSeeder --force *>$null
        & php artisan db:seed --class=CurriculumRoadmapSeeder --force *>$null
    } finally {
        Pop-Location
    }

    # 4. Khởi chạy Backend Laravel API (Port 8000)
    Write-Host "[2/4] Khởi chạy Backend Laravel API trên cổng 8000..." -ForegroundColor Cyan
    $mainPid = $PID
    $backendProc = Start-Process powershell -ArgumentList @(
        "-NoProfile",
        "-ExecutionPolicy", "Bypass",
        "-Command",
        "`$parentPid = $mainPid; Start-Job -ScriptBlock { param(`$p) while (Get-Process -Id `$p -ErrorAction SilentlyContinue) { Start-Sleep -Milliseconds 800 }; Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue | ForEach-Object { Stop-Process -Id `$_.OwningProcess -Force -ErrorAction SilentlyContinue }; taskkill /F /FI 'WINDOWTITLE eq OxVocab_Backend_8000*' /T 2>`$null } -ArgumentList `$parentPid | Out-Null; `$host.UI.RawUI.WindowTitle = 'OxVocab_Backend_8000'; Set-Location 'backend'; php artisan serve --port=8000"
    ) -WindowStyle Minimized -PassThru

    # 5. Khởi chạy Frontend Vue 3 + Vite (Port 5173)
    Write-Host "[3/4] Khởi chạy Frontend Vue 3 tren cổng 5173..." -ForegroundColor Cyan
    $frontendProc = Start-Process powershell -ArgumentList @(
        "-NoProfile",
        "-ExecutionPolicy", "Bypass",
        "-Command",
        "`$parentPid = $mainPid; Start-Job -ScriptBlock { param(`$p) while (Get-Process -Id `$p -ErrorAction SilentlyContinue) { Start-Sleep -Milliseconds 800 }; Get-NetTCPConnection -LocalPort 5173 -ErrorAction SilentlyContinue | ForEach-Object { Stop-Process -Id `$_.OwningProcess -Force -ErrorAction SilentlyContinue }; taskkill /F /FI 'WINDOWTITLE eq OxVocab_Frontend_5173*' /T 2>`$null } -ArgumentList `$parentPid | Out-Null; `$host.UI.RawUI.WindowTitle = 'OxVocab_Frontend_5173'; Set-Location 'frontend'; npm run dev"
    ) -WindowStyle Minimized -PassThru

    # 6. Đợi 2.5s và tự động mở trình duyệt Web
    Write-Host "[4/4] Đang mở trình duyệt Web (http://localhost:5173)..." -ForegroundColor Gray
    Start-Sleep -Milliseconds 2500
    Start-Process "http://localhost:5173"

    # 7. Hiển thị Dashboard quản lý
    Write-Host ""
    Write-Host "===============================================================================" -ForegroundColor Green
    Write-Host "   CHÚC MỪNG! HỆ THỐNG OXVOCAB ĐÃ ĐƯỢC KHỞI CHẠY THÀNH CÔNG!                   " -ForegroundColor Green
    Write-Host "===============================================================================" -ForegroundColor Green
    Write-Host "   • Giao diện Web học tập : http://localhost:5173" -ForegroundColor Cyan
    Write-Host "   • Backend RESTful API   : http://localhost:8000/api" -ForegroundColor Cyan
    Write-Host "   • Cơ sở dữ liệu         : MySQL (oxford_english)" -ForegroundColor White
    Write-Host "-------------------------------------------------------------------------------" -ForegroundColor Gray
    Write-Host "   [TÍNH NĂNG TỰ ĐỘNG TẮT TERMINAL ĐỒNG BỘ]:" -ForegroundColor Yellow
    Write-Host "   - Nhấn phím bất kỳ hoặc phím [Q] / [Enter] để DỪNG và TẮT HẾT TERMINAL." -ForegroundColor Yellow
    Write-Host "   - Khi bạn bấm nút Dừng/Kill terminal trong VS Code hoặc nhấn Ctrl + C," -ForegroundColor Yellow
    Write-Host "     toàn bộ terminal con (Backend & Frontend) sẽ tự động TẮT SẠCH 100%!" -ForegroundColor Yellow
    Write-Host "===============================================================================" -ForegroundColor Green
    Write-Host ""
    Write-Host ">> Hệ thống đang chạy... Nhấn [Q] hoặc [Ctrl+C] để DỪNG: " -NoNewline -ForegroundColor White

    # Chờ người dùng bấm phím hoặc Ctrl+C để dừng
    while ($true) {
        if ([Environment]::UserInteractive -and -not [Console]::IsInputRedirected) {
            if ([Console]::KeyAvailable) {
                $key = [Console]::ReadKey($true)
                break
            }
        } else {
            $line = [Console]::ReadLine()
            break
        }
        Start-Sleep -Milliseconds 250
    }
}
finally {
    # KHỐI CLEANUP TỰ ĐỘNG THỰC THI 100% KHI DỪNG Ở VS CODE HOẶC BẤM CTRL+C / THOÁT
    Write-Host ""
    Write-Host "===============================================================================" -ForegroundColor Yellow
    Write-Host "   [OxVocab] Đang dừng hệ thống và TẮT TOÀN BỘ CÁC TERMINAL LIÊN QUAN...       " -ForegroundColor Yellow
    Write-Host "===============================================================================" -ForegroundColor Yellow

    # 1. Đóng process đối tượng cha-con
    if ($backendProc -and -not $backendProc.HasExited) {
        Stop-Process -Id $backendProc.Id -Force -ErrorAction SilentlyContinue
    }
    if ($frontendProc -and -not $frontendProc.HasExited) {
        Stop-Process -Id $frontendProc.Id -Force -ErrorAction SilentlyContinue
    }

    # 2. Đóng toàn bộ cửa sổ terminal mang title OxVocab
    & taskkill /F /FI "WINDOWTITLE eq OxVocab_Backend_8000*" /T 2>$null | Out-Null
    & taskkill /F /FI "WINDOWTITLE eq OxVocab_Frontend_5173*" /T 2>$null | Out-Null

    # 3. Giải phóng triệt để các tiến trình đang chiếm cổng 8000 và 5173
    Get-NetTCPConnection -LocalPort 8000, 5173 -ErrorAction SilentlyContinue | ForEach-Object {
        Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue
    }

    Write-Host "   [✓] Đã tắt sạch toàn bộ các cửa sổ terminal (Backend & Frontend)." -ForegroundColor Green
    Write-Host "   [✓] Đã giải phóng hoàn toàn cổng mạng 8000 và 5173." -ForegroundColor Green
    Write-Host "   [✓] Toàn bộ hệ thống đã dừng an toàn. Hẹn gặp lại bạn!" -ForegroundColor Green
    Write-Host "===============================================================================" -ForegroundColor Yellow
    Write-Host ""
}
