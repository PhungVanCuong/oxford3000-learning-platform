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
        <h3>Đăng nhập thành công!</h3>
        <p>Đang tự động tắt tab này và đưa bạn về trang chủ...</p>
    </div>

    <script>
        (function() {
            const authData = {
                type: 'GOOGLE_AUTH_SUCCESS',
                token: @json($token),
                user: @json($user)
            };

            // 1. BroadcastChannel
            try {
                const channel = new BroadcastChannel('oxvocab_google_auth');
                channel.postMessage(authData);
                channel.close();
            } catch (e) {}

            // 2. LocalStorage Event
            try {
                localStorage.setItem('oxford_token', authData.token);
                localStorage.setItem('oxford_user', JSON.stringify(authData.user));
                localStorage.setItem('oxford_oauth_sync', JSON.stringify({
                    ...authData,
                    timestamp: Date.now()
                }));
            } catch (e) {}

            // 3. window.opener.postMessage
            if (window.opener && !window.opener.closed) {
                try {
                    window.opener.postMessage(authData, '*');
                } catch (e) {}
            }

            // 4. Tự động đóng tab ngay lập tức
            try {
                window.close();
            } catch (e) {}

            setTimeout(function() {
                try {
                    window.open('', '_self', '');
                    window.close();
                } catch (e) {}
            }, 80);

            // 5. Nếu không đóng được, chuyển hướng về Frontend
            const frontendUrl = @json($frontendUrl);
            setTimeout(function() {
                const payload = btoa(unescape(encodeURIComponent(JSON.stringify(authData.user))));
                window.location.replace(frontendUrl + '/auth/google/callback?token=' + encodeURIComponent(authData.token) + '&user=' + payload);
            }, 300);
        })();
    </script>
</body>
</html>
