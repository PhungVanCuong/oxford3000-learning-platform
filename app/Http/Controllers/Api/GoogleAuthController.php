<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Lấy URL chuyển hướng OAuth 2.0 của Google (dành cho Frontend gọi API)
     */
    public function getAuthUrl(Request $request)
    {
        $clientId = config('services.google.client_id');
        if (empty($clientId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hệ thống chưa được cấu hình GOOGLE_CLIENT_ID trong file .env. Vui lòng kiểm tra lại cấu hình máy chủ.',
            ], 500);
        }

        $redirectUri = $this->resolveRedirectUri($request);
        $state = Str::random(40);

        // Lưu state trong Cache 15 phút để chống tấn công giả mạo CSRF
        Cache::put('google_oauth_state_' . $state, [
            'created_at' => now()->timestamp,
            'ip' => $request->ip(),
        ], now()->addMinutes(15));

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'state' => $state,
            'prompt' => 'select_account',
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

        return response()->json([
            'status' => 'success',
            'data' => [
                'url' => $authUrl,
                'state' => $state,
                'redirect_uri' => $redirectUri,
            ],
        ]);
    }

    /**
     * Chuyển hướng trực tiếp trình duyệt sang Google OAuth
     */
    public function redirect(Request $request)
    {
        $clientId = config('services.google.client_id');
        if (empty($clientId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chưa cấu hình GOOGLE_CLIENT_ID trong .env',
            ], 500);
        }

        $redirectUri = $this->resolveRedirectUri($request);
        $state = Str::random(40);

        Cache::put('google_oauth_state_' . $state, [
            'created_at' => now()->timestamp,
            'ip' => $request->ip(),
        ], now()->addMinutes(15));

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'state' => $state,
            'prompt' => 'select_account',
        ];

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
    }

    /**
     * Xử lý Callback chuyển hướng từ Google (Web Route: GET /auth/google/callback)
     */
    public function handleWebCallback(Request $request)
    {
        $frontendUrl = $this->resolveFrontendUrl();

        // 1. Kiểm tra lỗi do người dùng từ chối hoặc Google trả về
        if ($request->has('error')) {
            $error = $request->query('error');
            Log::warning('Google OAuth callback error', ['error' => $error]);
            return redirect("{$frontendUrl}/auth/google/callback?error=" . urlencode($error));
        }

        $code = $request->query('code');
        $state = $request->query('state');

        if (empty($code)) {
            return redirect("{$frontendUrl}/auth/google/callback?error=missing_code");
        }

        // 2. Xác thực state chống CSRF
        if ($state) {
            $cached = Cache::pull('google_oauth_state_' . $state);
            if (!$cached && config('app.env') === 'production') {
                Log::warning('Google OAuth invalid state received', ['state' => $state]);
                return redirect("{$frontendUrl}/auth/google/callback?error=invalid_state");
            }
        }

        // 3. Đổi code lấy Google User info
        $redirectUri = $this->resolveRedirectUri($request);
        $result = $this->exchangeCodeAndFetchUser($code, $redirectUri);

        if (!$result['success']) {
            return redirect("{$frontendUrl}/auth/google/callback?error=" . urlencode($result['message']));
        }

        $googleData = $result['user'];

        // 4. Đồng bộ / Khởi tạo tài khoản
        $user = $this->findOrCreateUser($googleData);

        // 5. Cấp phát Sanctum Token
        /** @var User $user */
        $token = $user->createToken('auth_token')->plainTextToken;

        $userPayload = base64_encode(json_encode($user));

        return redirect("{$frontendUrl}/auth/google/callback?token={$token}&user={$userPayload}");
    }

    /**
     * Xử lý Callback dạng API (POST /api/auth/google/callback)
     * Dành cho Frontend SPA trực tiếp tiếp nhận code hoặc popup
     */
    public function handleApiCallback(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string',
            'state' => 'nullable|string',
            'credential' => 'nullable|string', // Hỗ trợ Google One Tap / GIS ID Token
            'redirect_uri' => 'nullable|string',
        ]);

        // Trường hợp 1: Google One-Tap / ID Token (Credential)
        if ($request->filled('credential')) {
            return $this->handleOneTapCredential($request->credential);
        }

        // Trường hợp 2: Authorization Code Flow
        $code = $request->input('code');
        if (empty($code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thiếu authorization code từ Google.',
            ], 422);
        }

        $state = $request->input('state');
        if ($state) {
            $cached = Cache::pull('google_oauth_state_' . $state);
            if (!$cached && config('app.env') === 'production') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Phiên xác thực Google không hợp lệ hoặc đã hết hạn (Invalid state).',
                ], 422);
            }
        }

        $redirectUri = $request->input('redirect_uri') ?: $this->resolveRedirectUri($request);
        $result = $this->exchangeCodeAndFetchUser($code, $redirectUri);

        if (!$result['success']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
            ], 400);
        }

        $googleData = $result['user'];
        $user = $this->findOrCreateUser($googleData);

        /** @var User $user */
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng nhập bằng tài khoản Google thành công!',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Hỗ trợ Google One Tap / Sign in with Google (GIS) qua ID Token
     */
    public function handleOneTapCredential(string $credential)
    {
        try {
            $verifyRes = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$credential}");
            if ($verifyRes->failed()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mã xác thực Google One Tap không hợp lệ.',
                ], 422);
            }

            $payload = $verifyRes->json();
            $clientId = config('services.google.client_id');

            // Kiểm tra audience
            if (!empty($clientId) && ($payload['aud'] ?? '') !== $clientId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token Google không khớp với Client ID của ứng dụng.',
                ], 422);
            }

            $googleData = [
                'sub' => $payload['sub'] ?? null,
                'email' => $payload['email'] ?? null,
                'name' => $payload['name'] ?? null,
                'picture' => $payload['picture'] ?? null,
                'email_verified' => filter_var($payload['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ];

            if (empty($googleData['email'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy thông tin email từ Google Token.',
                ], 422);
            }

            $user = $this->findOrCreateUser($googleData);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập Google One Tap thành công!',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Google One Tap verification exception', ['msg' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Xác thực Google One Tap thất bại: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Gửi code lên Google Token Endpoint và lấy UserInfo
     */
    protected function exchangeCodeAndFetchUser(string $code, string $redirectUri): array
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return [
                'success' => false,
                'message' => 'Chưa cấu hình GOOGLE_CLIENT_ID hoặc GOOGLE_CLIENT_SECRET trong .env',
            ];
        }

        try {
            // Bước A: Trao đổi code lấy access_token
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirectUri,
            ]);

            if ($tokenResponse->failed()) {
                $errBody = $tokenResponse->json();
                Log::error('Google token exchange failed', [
                    'status' => $tokenResponse->status(),
                    'body' => $errBody,
                    'redirect_uri' => $redirectUri,
                ]);

                $errorDescription = $errBody['error_description'] ?? ($errBody['error'] ?? 'Trao đổi mã code với Google thất bại.');
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối Google: ' . $errorDescription,
                ];
            }

            $tokens = $tokenResponse->json();
            $accessToken = $tokens['access_token'] ?? null;

            if (empty($accessToken)) {
                return [
                    'success' => false,
                    'message' => 'Google không trả về access_token hợp lệ.',
                ];
            }

            // Bước B: Gọi Google UserInfo API
            $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');
            if ($userResponse->failed()) {
                Log::error('Google userinfo fetch failed', ['body' => $userResponse->json()]);
                return [
                    'success' => false,
                    'message' => 'Không thể lấy thông tin người dùng từ Google.',
                ];
            }

            $userData = $userResponse->json();

            return [
                'success' => true,
                'user' => [
                    'sub' => $userData['sub'] ?? null,
                    'email' => $userData['email'] ?? null,
                    'name' => $userData['name'] ?? null,
                    'picture' => $userData['picture'] ?? null,
                    'email_verified' => filter_var($userData['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN),
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Google OAuth exchange exception', ['msg' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Lỗi ngoại lệ khi giao tiếp với máy chủ Google: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Logic đồng bộ tài khoản:
     * 1. Có google_id -> đăng nhập
     * 2. Có email -> liên kết google_id
     * 3. Email mới -> tạo mới
     */
    protected function findOrCreateUser(array $googleData): User
    {
        $googleId = $googleData['sub'];
        $email = trim($googleData['email']);
        $name = trim($googleData['name'] ?: 'Học viên Google');
        $picture = $googleData['picture'];

        // 1. Kiểm tra theo google_id
        if (!empty($googleId)) {
            $user = User::where('google_id', $googleId)->first();
            if ($user) {
                // Cập nhật ảnh đại diện mới nhất nếu cần
                if ($picture && (!$user->avatar_url || str_contains($user->avatar_url, 'googleusercontent.com') || str_contains($user->avatar_url, 'dicebear.com'))) {
                    $user->avatar_url = $picture;
                    $user->save();
                }
                return $user;
            }
        }

        // 2. Kiểm tra theo email (người dùng trước đó đã đăng ký bằng mật khẩu thường)
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->google_id = $googleId;
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
            }
            if ($picture && (!$user->avatar_url || str_contains($user->avatar_url, 'dicebear.com'))) {
                $user->avatar_url = $picture;
            }
            $user->save();
            return $user;
        }

        // 3. Khởi tạo tài khoản hoàn toàn mới
        return User::create([
            'name' => $name,
            'email' => $email,
            'google_id' => $googleId,
            'password' => Hash::make(Str::random(32)),
            'role' => ($email === 'cuongphung1810@gmail.com') ? 'admin' : 'user',
            'avatar_url' => $picture ?: ('https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($name)),
            'xp_points' => 50,
            'streak_days' => 1,
            'words_per_day' => 15,
            'theme_mode' => 'light',
            'email_verified_at' => now(),
            'last_study_date' => now()->toDateString(),
        ]);
    }

    /**
     * Tự động nhận diện URI callback phù hợp giữa Localhost và Production
     */
    protected function resolveRedirectUri(Request $request): string
    {
        $configUri = config('services.google.redirect_uri');
        if (!empty($configUri)) {
            return $configUri;
        }

        $appUrl = rtrim(config('app.url') ?: 'http://localhost:8000', '/');
        return "{$appUrl}/auth/google/callback";
    }

    /**
     * Tự động nhận diện URL Frontend để chuyển hướng sau khi đăng nhập thành công
     */
    protected function resolveFrontendUrl(): string
    {
        $frontendUrl = env('FRONTEND_URL');
        if (!empty($frontendUrl)) {
            return rtrim($frontendUrl, '/');
        }

        // Nếu backend đang ở domain production deloydz.io.vn
        if (str_contains(config('app.url') ?? '', 'deloydz.io.vn')) {
            return 'https://oxvocab.deloydz.io.vn';
        }

        return 'http://localhost:5173';
    }
}
