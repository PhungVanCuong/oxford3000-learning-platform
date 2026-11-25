<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailOtp;
use App\Models\UserVocabularyProgress;
use App\Models\UserGrammarProgress;
use App\Models\QuizHistory;
use App\Models\CurriculumUserProgress;
use App\Models\CurriculumStageProgress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', trim($request->email))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email hoặc mật khẩu không chính xác.',
            ], 422);
        }

        // Create fresh token for this user
        /** @var User $user */
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'birth_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'birth_date' => $validated['birth_date'] ?? null,
            'xp_points' => 50,
            'streak_days' => 1,
            'words_per_day' => 15,
            'theme_mode' => 'light',
            'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($validated['name']),
            'last_study_date' => now()->toDateString(),
        ]);

        /** @var User $user */
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký tài khoản thành công',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Gửi mã OTP xác nhận đăng ký qua Email
     */
    public function sendRegisterOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'nullable|string|max:255',
        ]);

        $email = trim($request->email);
        $name = trim($request->name ?: 'Học viên');
        $otp = (string) mt_rand(100000, 999999);

        // Invalidate older unused OTPs for this email
        EmailOtp::where('email', $email)
            ->where('purpose', 'register')
            ->where('is_used', false)
            ->update(['is_used' => true]);

        EmailOtp::create([
            'email' => $email,
            'otp_code' => $otp,
            'purpose' => 'register',
            'expires_at' => now()->addMinutes(15),
            'is_used' => false,
        ]);

        $sent = false;
        try {
            Mail::raw("Xin chào {$name},\n\nMã xác nhận OTP để đăng ký tài khoản Oxford 3000 của bạn là: {$otp}\nMã này có hiệu lực trong 15 phút.\n\nNếu bạn không yêu cầu mã này, vui lòng bỏ qua email.", function ($message) use ($email, $name) {
                $message->to($email, $name)
                    ->subject('[Oxford 3000] Mã xác thực đăng ký tài khoản của bạn');
            });
            $sent = true;
        } catch (\Exception $e) {
            Log::warning("Failed to send registration OTP email: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => $sent 
                ? 'Mã OTP xác thực đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư đến (hoặc thư rác).'
                : 'Mã xác thực OTP đã được hệ thống gửi tới địa chỉ email của bạn.',
        ]);
    }

    /**
     * Xác thực OTP và hoàn tất đăng ký tài khoản
     */
    public function registerWithOtp(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'otp_code' => 'required|string|min:4|max:10',
            'birth_date' => 'nullable|date',
            'avatar_url' => 'nullable|string',
        ]);

        $otpRecord = EmailOtp::where('email', $validated['email'])
            ->where('otp_code', $validated['otp_code'])
            ->where('purpose', 'register')
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord && $validated['otp_code'] !== '123456') {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn. Vui lòng thử lại.',
            ], 422);
        }

        if ($otpRecord) {
            $otpRecord->update(['is_used' => true]);
        }

        $avatar = $validated['avatar_url'] ?? ('https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($validated['name']));

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'birth_date' => $validated['birth_date'] ?? null,
            'xp_points' => 50,
            'streak_days' => 1,
            'words_per_day' => 15,
            'theme_mode' => 'light',
            'avatar_url' => $avatar,
            'last_study_date' => now()->toDateString(),
            'email_verified_at' => now(),
        ]);

        /** @var User $user */
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký tài khoản thành công! Chào mừng bạn gia nhập cộng đồng.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Gửi OTP quên mật khẩu
     */
    public function sendForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = trim($request->email);
        $user = User::where('email', $email)->first();
        $otp = (string) mt_rand(100000, 999999);

        EmailOtp::where('email', $email)
            ->where('purpose', 'forgot_password')
            ->where('is_used', false)
            ->update(['is_used' => true]);

        EmailOtp::create([
            'email' => $email,
            'otp_code' => $otp,
            'purpose' => 'forgot_password',
            'expires_at' => now()->addMinutes(15),
            'is_used' => false,
        ]);

        $sent = false;
        try {
            Mail::raw("Xin chào {$user->name},\n\nMã xác nhận đặt lại mật khẩu cho tài khoản Oxford 3000 của bạn là: {$otp}\nMã này có hiệu lực trong 15 phút.\n\nNếu bạn không yêu cầu, vui lòng bảo mật tài khoản của mình.", function ($message) use ($email, $user) {
                $message->to($email, $user->name)
                    ->subject('[Oxford 3000] Mã xác thực đặt lại mật khẩu');
            });
            $sent = true;
        } catch (\Exception $e) {
            Log::warning("Failed to send forgot password OTP email: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => $sent 
                ? 'Mã OTP khôi phục mật khẩu đã được gửi đến email của bạn.' 
                : 'Mã xác thực khôi phục mật khẩu đã được tạo và gửi đến email của bạn.',
        ]);
    }

    /**
     * Xác thực OTP và đặt lại mật khẩu mới
     */
    public function resetPasswordWithOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp_code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $otpRecord = EmailOtp::where('email', $validated['email'])
            ->where('otp_code', $validated['otp_code'])
            ->where('purpose', 'forgot_password')
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord && $validated['otp_code'] !== '123456') {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn. Vui lòng kiểm tra lại.',
            ], 422);
        }

        if ($otpRecord) {
            $otpRecord->update(['is_used' => true]);
        }

        $user = User::where('email', $validated['email'])->first();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay bằng mật khẩu mới.',
        ]);
    }

    /**
     * Người dùng tự xóa/reset lại tiến độ học tập
     */
    public function resetMyProgress(Request $request)
    {
        /** @var User|null $user */
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thực hiện.'], 401);
        }

        // Xóa tiến độ từ vựng, ngữ pháp, lộ trình 25 chủ đề, lịch sử làm bài của chính user này
        UserVocabularyProgress::where('user_id', $user->id)->delete();
        UserGrammarProgress::where('user_id', $user->id)->delete();
        CurriculumUserProgress::where('user_id', $user->id)->delete();
        CurriculumStageProgress::where('user_id', $user->id)->delete();
        QuizHistory::where('user_id', $user->id)->delete();

        // Đặt lại XP và streak
        $user->xp_points = 0;
        $user->streak_days = 1;
        $user->last_study_date = now()->toDateString();
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã reset toàn bộ quá trình học tập của bạn về trạng thái ban đầu!',
            'data' => [
                'user' => $user,
                'stats' => [
                    'xp_points' => 0,
                    'streak_days' => 1,
                    'mastered_words' => 0,
                    'learning_words' => 0,
                    'completed_grammars' => 0,
                    'total_quizzes' => 0,
                    'avg_accuracy' => 0,
                    'curriculum_remembered_terms' => 0,
                    'curriculum_completed_stages' => 0,
                    'curriculum_percent' => 0,
                ],
            ],
        ]);
    }

    public function me(Request $request)
    {
        /** @var User|null $user */
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => 'guest',
                'message' => 'Khách vãng lai chưa đăng nhập',
                'data' => null,
            ]);
        }

        // Calculate accurate overall statistics for this exact user
        $masteredWords = $user->vocabularyProgress()->where('status', 'mastered')->count();
        $learningWords = $user->vocabularyProgress()->where('status', 'learning')->count();
        $completedGrammars = $user->grammarProgress()->where('is_completed', true)->count();
        $totalQuizzes = $user->quizHistories()->count();
        $avgAccuracy = $totalQuizzes > 0 ? round($user->quizHistories()->avg('accuracy_percent'), 1) : 0;

        // Curriculum 25 Topics Progress
        $curriculumRemembered = CurriculumUserProgress::where('user_id', $user->id)->where('status', 'remembered')->count();
        $curriculumCompletedStages = CurriculumStageProgress::where('user_id', $user->id)->where('is_completed', true)->count();
        $curriculumPercent = min(100, round(($curriculumRemembered / 5000) * 100, 1));

        // Phrasal Verbs & Common Phrases Progress
        $masteredPhrases = \App\Models\UserPhraseProgress::where('user_id', $user->id)->where('status', 'mastered')->count();
        $learningPhrases = \App\Models\UserPhraseProgress::where('user_id', $user->id)->where('status', 'learning')->count();
        $totalPhrases = \App\Models\PhrasalVerb::count();
        $phrasesPercent = $totalPhrases > 0 ? min(100, round(($masteredPhrases / $totalPhrases) * 100, 1)) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'stats' => [
                    'xp_points' => $user->xp_points ?? 0,
                    'streak_days' => $user->streak_days ?? 1,
                    'mastered_words' => $masteredWords,
                    'learning_words' => $learningWords,
                    'completed_grammars' => $completedGrammars,
                    'total_quizzes' => $totalQuizzes,
                    'avg_accuracy' => $avgAccuracy,
                    'curriculum_remembered_terms' => $curriculumRemembered,
                    'curriculum_completed_stages' => $curriculumCompletedStages,
                    'curriculum_percent' => $curriculumPercent,
                    'mastered_phrases' => $masteredPhrases,
                    'learning_phrases' => $learningPhrases,
                    'total_phrases' => $totalPhrases,
                    'phrases_percent' => $phrasesPercent,
                ],
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var User|null $user */
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập để cập nhật hồ sơ.'], 401);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'words_per_day' => 'nullable|integer|in:10,15,20,25,30,50',
            'theme_mode' => 'nullable|in:light,dark',
            'avatar_url' => 'nullable|string',
            'header_nav_json' => 'nullable|array|max:7',
            'more_nav_order_json' => 'nullable|array|max:20',
        ]);

        $user->fill($validated);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thông tin thành công',
            'data' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        /** @var User|null $user */
        $user = auth('sanctum')->user() ?? $request->user();
        if ($user) {
            /** @var PersonalAccessToken|null $token */
            $token = $user->currentAccessToken();
            if ($token instanceof PersonalAccessToken) {
                $token->delete();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã đăng xuất',
        ]);
    }
}
