<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực Google Thành Công • OxVocab™</title>
    <style>
        body {
            background: #0f172a;
            color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .card {
            text-align: center;
            padding: 32px;
            background: rgba(30, 41, 59, 0.85);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            max-width: 360px;
            width: 90%;
        }
        .spinner {
            width: 44px;
            height: 44px;
            border: 3.5px solid rgba(16, 185, 129, 0.2);
            border-top-color: #10b981;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h3 {
            margin: 0 0 8px;
            color: #10b981;
            font-size: 19px;
            font-weight: 700;
        }
        p {
            margin: 0;
            color: #94a3b8;
            font-size: 13.5px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="spinner"></div>
        <h3>Xác thực thành công!</h3>
        <p>Đang đồng bộ dữ liệu vào hệ thống và đóng cửa sổ này...</p>
    </div>

    <script>
        (function() {
            const authData = {
                type: 'GOOGLE_AUTH_SUCCESS',
                token: @json($token),
                user: @json($user)
            };

            // Nếu mở bằng Popup Window, gửi dữ liệu về cửa sổ mẹ và tự đóng
            if (window.opener && !window.opener.closed) {
                try {
                    window.opener.postMessage(authData, '*');
                    setTimeout(function() {
                        window.close();
                    }, 250);
                } catch (e) {
                    console.error('Không gửi được postMessage:', e);
                    window.close();
                }
            } else {
                // Nếu người dùng mở bằng toàn tab, chuyển hướng về Frontend
                const frontendUrl = @json($frontendUrl);
                const payload = btoa(unescape(encodeURIComponent(JSON.stringify(authData.user))));
                window.location.href = frontendUrl + '/auth/google/callback?token=' + encodeURIComponent(authData.token) + '&user=' + payload;
            }
        })();
    </script>
</body>
</html>
