<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vocabulary;
use App\Models\Topic;
use App\Models\Grammar;
use App\Models\GrammarQuestion;
use App\Models\AppSetting;
use Illuminate\Support\Str;
use App\Models\QuizHistory;
use App\Models\UserVocabularyProgress;
use App\Models\CurriculumTopic;
use App\Models\CurriculumTerm;
use App\Models\CurriculumUserProgress;
use App\Models\CurriculumStageProgress;
use App\Models\PhrasalVerb;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private function resolveCurrentUser(Request $request): ?User
    {
        $user = auth('sanctum')->user() ?? $request->user();
        if (!$user) {
            $token = $request->bearerToken();
            if ($token) {
                $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                if ($accessToken) {
                    $user = $accessToken->tokenable;
                }
            }
        }
        return $user;
    }

    private function authorizePermission(Request $request, string $permission)
    {
        $user = $this->resolveCurrentUser($request);
        if (!$user) {
            abort(response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.'
            ], 401));
        }

        if (!$user->hasPermission($permission)) {
            abort(response()->json([
                'status' => 'error',
                'message' => "Tài khoản của bạn ({$user->role}) không có quyền thực hiện chức năng này (yêu cầu quyền: {$permission})."
            ], 403));
        }
        return $user;
    }

    private function authorizePermissionAny(Request $request, array $permissions)
    {
        $user = $this->resolveCurrentUser($request);
        if (!$user) {
            abort(response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.'
            ], 401));
        }

        if ($user->isAdmin()) {
            return $user;
        }

        foreach ($permissions as $perm) {
            if ($user->hasPermission($perm)) {
                return $user;
            }
        }

        abort(response()->json([
            'status' => 'error',
            'message' => "Tài khoản của bạn ({$user->role}) không có quyền thực hiện chức năng này."
        ], 403));
    }

    /**
     * Admin Overview Stats
     */
    public function getDashboardStats(Request $request)
    {
        $this->authorizePermissionAny($request, ['view_reports', 'manage_users']);

        $totalUsers = User::count();
        $totalWords = Vocabulary::count();
        $totalTopics = Topic::count();
        $totalGrammars = Grammar::count();
        $totalQuizzes = QuizHistory::count();

        // Curriculum 25 Topics & Phrasal Verbs stats
        $totalCurriculumTopics = CurriculumTopic::count();
        $totalCurriculumTerms = CurriculumTerm::count();
        $totalCurriculumRemembered = CurriculumUserProgress::where('status', 'remembered')->count();
        $totalCurriculumStagesCompleted = CurriculumStageProgress::where('is_completed', true)->count();
        $totalPhrasalVerbs = PhrasalVerb::count();

        // Learning activities today
        $todayReviews = UserVocabularyProgress::whereDate('last_reviewed_at', now()->toDateString())->count()
            + CurriculumUserProgress::whereDate('last_reviewed_at', now()->toDateString())->count();

        // Top 5 active learners
        $topLearners = User::orderBy('xp_points', 'desc')->take(5)->get()->map(function ($u) {
            $oxfordMastered = $u->vocabularyProgress()->where('status', 'mastered')->count();
            $curriculumMastered = CurriculumUserProgress::where('user_id', $u->id)->where('status', 'remembered')->count();
            $phrasalMastered = \App\Models\UserPhraseProgress::where('user_id', $u->id)->where('status', 'mastered')->count();
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'xp_points' => $u->xp_points ?? 0,
                'streak_days' => $u->streak_days ?? 1,
                'mastered_count' => $oxfordMastered + $curriculumMastered + $phrasalMastered,
                'oxford_count' => $oxfordMastered,
                'curriculum_count' => $curriculumMastered,
                'phrasal_count' => $phrasalMastered,
            ];
        });

        // 25 Curriculum topics overview with learner count
        $curriculumOverview = CurriculumTopic::orderBy('order_num')->get()->map(function ($t) {
            $rememberedInTopic = CurriculumUserProgress::where('topic_id', $t->id)->where('status', 'remembered')->count();
            return [
                'id' => $t->id,
                'order_num' => $t->order_num,
                'title' => $t->title,
                'title_vi' => $t->title_vi,
                'total_terms' => $t->total_terms,
                'total_remembered' => $rememberedInTopic,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_users' => $totalUsers,
                'total_words' => $totalWords,
                'total_topics' => $totalTopics,
                'total_grammars' => $totalGrammars,
                'total_quizzes' => $totalQuizzes,
                'total_curriculum_topics' => $totalCurriculumTopics,
                'total_curriculum_terms' => $totalCurriculumTerms,
                'total_curriculum_remembered' => $totalCurriculumRemembered,
                'total_curriculum_stages_completed' => $totalCurriculumStagesCompleted,
                'total_phrasal_verbs' => $totalPhrasalVerbs,
                'today_reviews' => $todayReviews,
                'top_learners' => $topLearners,
                'curriculum_overview' => $curriculumOverview,
            ],
        ]);
    }

    /**
     * List all Users with learning metrics
     */
    public function getUsers(Request $request)
    {
        $currentUser = $this->resolveCurrentUser($request);
        $this->authorizePermissionAny($request, ['manage_users', 'view_students']);

        $query = User::withCount(['quizHistories'])->orderBy('id');
        if ($currentUser && !$currentUser->hasPermission('manage_users')) {
            $query->where('role', 'user');
        }

        $users = $query->get()->map(function ($u) {
            $masteredVocab = $u->vocabularyProgress()->where('status', 'mastered')->count();
            $curriculumRemembered = CurriculumUserProgress::where('user_id', $u->id)->where('status', 'remembered')->count();
            $masteredPhrases = \App\Models\UserPhraseProgress::where('user_id', $u->id)->where('status', 'mastered')->count();
            $totalMastered = $masteredVocab + $curriculumRemembered + $masteredPhrases;
            $completedGrammars = $u->grammarProgress()->where('is_completed', true)->count();
            $avgAccuracy = $u->quiz_histories_count > 0 ? round($u->quizHistories()->avg('accuracy_percent'), 1) : 0;

            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role,
                'permissions' => $u->permissions,
                'birth_date' => $u->birth_date ? $u->birth_date->format('d/m/Y') : null,
                'xp_points' => $u->xp_points,
                'streak_days' => $u->streak_days,
                'words_per_day' => $u->words_per_day,
                'mastered_words' => $masteredVocab,
                'curriculum_remembered' => $curriculumRemembered,
                'mastered_phrases' => $masteredPhrases,
                'total_mastered_words' => $totalMastered,
                'completed_grammars' => $completedGrammars,
                'total_quizzes' => $u->quiz_histories_count,
                'avg_accuracy' => $avgAccuracy,
                'last_study_date' => $u->last_study_date ? $u->last_study_date->format('d/m/Y') : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    /**
     * Detailed View of a specific student
     */
    public function getUserDetails(Request $request, $id)
    {
        $currentUser = $this->resolveCurrentUser($request);
        $this->authorizePermissionAny($request, ['manage_users', 'view_students']);

        $user = User::findOrFail($id);
        if ($currentUser && !$currentUser->hasPermission('manage_users') && $user->role !== 'user') {
            abort(response()->json([
                'status' => 'error',
                'message' => 'Bạn chỉ có quyền xem thông tin của học viên.'
            ], 403));
        }

        $quizHistories = $user->quizHistories()->take(10)->get();
        $masteredWords = $user->vocabularyProgress()->where('status', 'mastered')->with('vocabulary')->take(20)->get()->pluck('vocabulary');
        $learningWords = $user->vocabularyProgress()->where('status', 'learning')->with('vocabulary')->take(20)->get()->pluck('vocabulary');

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'birth_date' => $user->birth_date ? $user->birth_date->format('d/m/Y') : null,
                    'xp_points' => $user->xp_points,
                    'streak_days' => $user->streak_days,
                    'words_per_day' => $user->words_per_day,
                ],
                'quiz_histories' => $quizHistories,
                'mastered_words' => $masteredWords,
                'learning_words' => $learningWords,
            ],
        ]);
    }

    public function createUser(Request $request)
    {
        $this->authorizePermission($request, 'manage_users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,moderator,editor,tutor,user',
            'birth_date' => 'nullable|date',
            'words_per_day' => 'nullable|integer',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'birth_date' => $validated['birth_date'] ?? null,
            'words_per_day' => $validated['words_per_day'] ?? 15,
            'xp_points' => 0,
            'streak_days' => 1,
            'theme_mode' => 'light',
            'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($validated['name']),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo tài khoản học viên thành công',
            'data' => $user,
        ], 201);
    }

    public function updateUser(Request $request, $id)
    {
        $this->authorizePermission($request, 'manage_users');

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'role' => 'nullable|in:admin,moderator,editor,tutor,user',
            'password' => 'nullable|string|min:6',
            'birth_date' => 'nullable|date',
            'words_per_day' => 'nullable|integer',
            'xp_points' => 'nullable|integer',
            'streak_days' => 'nullable|integer',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->fill($validated);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật tài khoản thành công',
            'data' => $user,
        ]);
    }

    public function deleteUser(Request $request, $id)
    {
        $this->authorizePermission($request, 'manage_users');

        $user = User::findOrFail($id);
        if ($user->email === 'cuongphung1810@gmail.com') {
            return response()->json(['status' => 'error', 'message' => 'Không thể xóa tài khoản Admin chính'], 403);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa tài khoản',
        ]);
    }

    /**
     * Vocabulary CRUD
     */
    public function createVocabulary(Request $request)
    {
        $this->authorizePermission($request, 'manage_vocab');

        $validated = $request->validate([
            'topic_id' => 'nullable|exists:topics,id',
            'word' => 'required|string',
            'phonetic_us' => 'nullable|string',
            'part_of_speech' => 'nullable|string',
            'meaning_vi' => 'required|string',
            'example_en' => 'nullable|string',
            'example_vi' => 'nullable|string',
            'cefr_level' => 'nullable|string',
        ]);

        $vocab = Vocabulary::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm từ vựng thành công',
            'data' => $vocab,
        ], 201);
    }

    public function updateVocabulary(Request $request, $id)
    {
        $this->authorizePermission($request, 'manage_vocab');

        $vocab = Vocabulary::findOrFail($id);

        $validated = $request->validate([
            'topic_id' => 'nullable|exists:topics,id',
            'word' => 'nullable|string',
            'phonetic_us' => 'nullable|string',
            'part_of_speech' => 'nullable|string',
            'meaning_vi' => 'nullable|string',
            'example_en' => 'nullable|string',
            'example_vi' => 'nullable|string',
            'cefr_level' => 'nullable|string',
        ]);

        $vocab->fill($validated);
        $vocab->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật từ vựng thành công',
            'data' => $vocab,
        ]);
    }

    public function deleteVocabulary(Request $request, $id)
    {
        $this->authorizePermission($request, 'manage_vocab');

        $vocab = Vocabulary::findOrFail($id);
        $vocab->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa từ vựng',
        ]);
    }

    /**
     * Admin: Reset learning progress of a user
     */
    public function resetUserProgress(Request $request, $id)
    {
        $this->authorizePermissionAny($request, ['reset_progress', 'manage_users']);

        $user = User::findOrFail($id);

        \App\Models\UserVocabularyProgress::where('user_id', $user->id)->delete();
        \App\Models\UserGrammarProgress::where('user_id', $user->id)->delete();
        \App\Models\QuizHistory::where('user_id', $user->id)->delete();
        CurriculumUserProgress::where('user_id', $user->id)->delete();
        \App\Models\CurriculumStageProgress::where('user_id', $user->id)->delete();
        \App\Models\UserPhraseProgress::where('user_id', $user->id)->delete();

        $user->xp_points = 0;
        $user->streak_days = 1;
        $user->last_study_date = now()->toDateString();
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => "Đã reset toàn bộ quá trình học tập của học viên {$user->name} thành công!",
        ]);
    }

    /**
     * Tự động sinh câu ví dụ song ngữ qua Gemini AI
     */
    public function generateAiExamples(Request $request, \App\Services\GeminiApiService $gemini)
    {
        $this->authorizePermission($request, 'ai_generation');

        $request->validate([
            'word' => 'required|string',
            'part_of_speech' => 'nullable|string',
            'meaning_vi' => 'nullable|string',
        ]);

        $word = $request->word;
        $pos = $request->part_of_speech ?? 'Noun';
        $meaning = $request->meaning_vi ?? '';

        $result = $gemini->generateExamples($word, $pos, $meaning);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    /**
     * Trợ lý AI giải thích ngữ pháp hoặc kiểm tra lỗi chính tả
     */
    public function aiExplainOrCorrect(Request $request, \App\Services\GeminiApiService $gemini)
    {
        $this->authorizePermission($request, 'ai_generation');

        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $result = $gemini->explainOrCorrect($request->text);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    /**
     * Get available roles & permissions definitions with full packaged details
     */
    public function getPermissionsList(Request $request)
    {
        $this->authorizePermission($request, 'manage_roles');

        $roleCounts = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        $roleDefaults = User::getRoleDefaultsMap();

        $roles = [
            [
                'key' => 'admin',
                'name' => 'Quản trị viên Cấp cao (Super Admin)',
                'badge' => 'danger',
                'icon' => 'bi-shield-shaded',
                'description' => 'Toàn quyền điều hành toàn bộ hệ thống, quản lý cơ sở dữ liệu, phân quyền tài khoản, reset tiến độ và giám sát máy chủ.',
                'user_count' => $roleCounts['admin'] ?? 0,
                'default_permissions' => $roleDefaults['admin'] ?? [],
            ],
            [
                'key' => 'moderator',
                'name' => 'Quản trị viên Nội dung (Content Manager)',
                'badge' => 'warning',
                'icon' => 'bi-pencil-square',
                'description' => 'Phụ trách kiểm duyệt nội dung học tập, quản lý lộ trình 25 chủ đề, biên tập câu hỏi trắc nghiệm và cụm từ.',
                'user_count' => $roleCounts['moderator'] ?? 0,
                'default_permissions' => $roleDefaults['moderator'] ?? [],
            ],
            [
                'key' => 'editor',
                'name' => 'Biên tập viên Học thuật (Vocabulary Editor)',
                'badge' => 'primary',
                'icon' => 'bi-journal-code',
                'description' => 'Chuyên môn biên soạn nghĩa tiếng Việt, phiên âm IPA, ví dụ thực tế và tra cứu từ vựng Oxford.',
                'user_count' => $roleCounts['editor'] ?? 0,
                'default_permissions' => $roleDefaults['editor'] ?? [],
            ],
            [
                'key' => 'tutor',
                'name' => 'Trợ giảng / Giáo viên Hướng dẫn (Tutor / Instructor)',
                'badge' => 'info',
                'icon' => 'bi-person-badge',
                'description' => 'Theo dõi quá trình học tập của học viên, giám sát số từ đã thuộc, chuỗi ngày streak và giải đáp thắc mắc.',
                'user_count' => $roleCounts['tutor'] ?? 0,
                'default_permissions' => $roleDefaults['tutor'] ?? [],
            ],
            [
                'key' => 'user',
                'name' => 'Học viên Tiêu chuẩn (Standard Learner)',
                'badge' => 'secondary',
                'icon' => 'bi-mortarboard',
                'description' => 'Truy cập toàn bộ 3.000 từ vựng Oxford, 25 chủ đề thực chiến, luyện phát âm microphone và làm bài kiểm tra.',
                'user_count' => $roleCounts['user'] ?? 0,
                'default_permissions' => $roleDefaults['user'] ?? [],
            ],
        ];

        $permissions = [
            // Từ vựng & AI
            [
                'key' => 'manage_vocab',
                'name' => 'Quản lý kho 3000 từ vựng (Thêm, Sửa, Xóa)',
                'description' => 'Được phép tạo mới, chỉnh sửa nghĩa, phiên âm và xóa từ vựng khỏi từ điển.',
                'category' => 'Từ vựng & AI',
            ],
            [
                'key' => 'ai_generation',
                'name' => 'Tự động tạo câu ví dụ & giải thích qua Gemini AI',
                'description' => 'Gọi API Google Gemini để sinh mẫu câu ví dụ và sửa lỗi câu hỏi.',
                'category' => 'Từ vựng & AI',
            ],
            [
                'key' => 'bookmark_words',
                'name' => 'Đánh dấu từ vựng yêu thích & Sổ tay cá nhân',
                'description' => 'Lưu từ cần ôn tập vào danh mục cá nhân và tra cứu nhanh.',
                'category' => 'Từ vựng & AI',
            ],
            // Lộ trình & Cụm từ & Ngữ pháp
            [
                'key' => 'manage_curriculum',
                'name' => 'Quản lý 25 chủ đề lộ trình & 200 câu trắc nghiệm',
                'description' => 'Chỉnh sửa nội dung 25 chủ đề thực chiến, bộ câu hỏi trắc nghiệm từng chặng.',
                'category' => 'Lộ trình & Cụm từ',
            ],
            [
                'key' => 'manage_grammar',
                'name' => 'Quản lý chuyên đề Ngữ pháp & Câu hỏi ôn tập',
                'description' => 'Thêm, sửa, xóa lý thuyết các thì và chuyên đề ngữ pháp trong hệ thống.',
                'category' => 'Lộ trình & Cụm từ',
            ],
            [
                'key' => 'manage_phrases',
                'name' => 'Quản lý cụm từ & Phrasal Verbs',
                'description' => 'Thêm mới và cập nhật thành ngữ, cụm động từ trong hệ thống.',
                'category' => 'Lộ trình & Cụm từ',
            ],
            [
                'key' => 'manage_arena',
                'name' => 'Quản lý Đấu trường Quiz & Phòng thi trực tiếp',
                'description' => 'Kiểm soát phòng thi Đấu trường, đóng hoặc xóa phòng vi phạm.',
                'category' => 'Lộ trình & Cụm từ',
            ],
            [
                'key' => 'learn_curriculum',
                'name' => 'Học và thi thử trắc nghiệm 25 chủ đề lộ trình',
                'description' => 'Tham gia kiểm tra và mở khóa 10 chặng thử thách của mỗi chủ đề.',
                'category' => 'Lộ trình & Cụm từ',
            ],
            // Học viên & Đào tạo
            [
                'key' => 'view_students',
                'name' => 'Giám sát danh sách học viên & Bảng xếp hạng',
                'description' => 'Xem danh sách tất cả học viên trong hệ thống và điểm XP.',
                'category' => 'Học viên & Đào tạo',
            ],
            [
                'key' => 'view_student_progress',
                'name' => 'Xem chi tiết tiến độ từ vựng & lộ trình của từng học viên',
                'description' => 'Xem thống kê số từ đã thuộc, tỷ lệ chính xác và lịch sử làm bài.',
                'category' => 'Học viên & Đào tạo',
            ],
            [
                'key' => 'reset_progress',
                'name' => 'Quyền Reset tiến độ học tập cho học viên',
                'description' => 'Xóa dữ liệu học tập và đưa tiến độ của học viên về trạng thái ban đầu.',
                'category' => 'Học viên & Đào tạo',
            ],
            // Quản trị & Hệ thống
            [
                'key' => 'manage_users',
                'name' => 'Quản lý tài khoản (Tạo mới, Sửa, Xóa học viên)',
                'description' => 'Tạo tài khoản quản trị mới, chỉnh sửa thông tin hoặc xóa tài khoản.',
                'category' => 'Quản trị & Hệ thống',
            ],
            [
                'key' => 'manage_roles',
                'name' => 'Thiết lập chức vụ & phân quyền hệ thống',
                'description' => 'Đóng gói chức vụ, phân bổ ma trận quyền hạn cho từng cấp bậc.',
                'category' => 'Quản trị & Hệ thống',
            ],
            [
                'key' => 'view_reports',
                'name' => 'Xem Dashboard thống kê & Báo cáo tổng thể',
                'description' => 'Truy cập số liệu phân tích người dùng, từ vựng và biểu đồ tiến độ.',
                'category' => 'Quản trị & Hệ thống',
            ],
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'roles' => $roles,
                'permissions' => $permissions,
            ],
        ]);
    }

    /**
     * Save Role Package default permissions to persistent storage
     */
    public function updateRolePackage(Request $request, string $roleKey)
    {
        $this->authorizePermission($request, 'manage_roles');

        $allowedRoles = array_keys(User::$roleDefaultPermissions);
        if (!in_array($roleKey, $allowedRoles, true)) {
            return response()->json(['status' => 'error', 'message' => 'Gói chức vụ không hợp lệ.'], 422);
        }

        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string',
        ]);

        $overrides = AppSetting::getJson('rbac_role_packages', []);
        $overrides[$roleKey] = array_values(array_unique($validated['permissions']));
        AppSetting::setJson('rbac_role_packages', $overrides);

        return response()->json([
            'status' => 'success',
            'message' => "Đã lưu cấu hình gói chức vụ [{$roleKey}] vào hệ thống!",
            'data' => [
                'role' => $roleKey,
                'default_permissions' => $overrides[$roleKey],
            ],
        ]);
    }

    /**
     * Create a new Grammar lesson
     */
    public function createGrammar(Request $request)
    {
        $this->authorizePermissionAny($request, ['manage_grammar', 'manage_curriculum', 'manage_vocab']);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string|max:20',
            'summary' => 'nullable|string',
            'theory_html' => 'nullable|string',
            'active_passive_html' => 'nullable|string',
            'pronunciation_notes' => 'nullable|string',
            'examples_json' => 'nullable|array',
            'exercises_json' => 'nullable|array',
            'order_index' => 'nullable|integer',
            'badge_color' => 'nullable|string|max:30',
            'icon' => 'nullable|string|max:60',
            'topic_id' => 'nullable|integer|exists:topics,id',
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug ?: ('grammar-' . time());
        if (Grammar::where('slug', $slug)->exists()) {
            $slug .= '-' . rand(100, 999);
        }

        $grammar = Grammar::create([
            'topic_id' => $validated['topic_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'level' => $validated['level'],
            'summary' => $validated['summary'] ?? '',
            'theory_html' => $validated['theory_html'] ?? '<p>Đang cập nhật lý thuyết.</p>',
            'active_passive_html' => $validated['active_passive_html'] ?? '',
            'pronunciation_notes' => $validated['pronunciation_notes'] ?? '',
            'examples_json' => $validated['examples_json'] ?? [],
            'exercises_json' => $validated['exercises_json'] ?? [],
            'order_index' => $validated['order_index'] ?? ((Grammar::max('order_index') ?? 0) + 1),
            'badge_color' => $validated['badge_color'] ?? '#00b074',
            'icon' => $validated['icon'] ?? 'bi-journal-bookmark-fill',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm chuyên đề Ngữ pháp mới!',
            'data' => $grammar,
        ], 201);
    }

    /**
     * Update an existing Grammar lesson
     */
    public function updateGrammar(Request $request, $id)
    {
        $this->authorizePermissionAny($request, ['manage_grammar', 'manage_curriculum', 'manage_vocab']);

        $grammar = Grammar::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'level' => 'sometimes|required|string|max:20',
            'summary' => 'nullable|string',
            'theory_html' => 'nullable|string',
            'active_passive_html' => 'nullable|string',
            'pronunciation_notes' => 'nullable|string',
            'examples_json' => 'nullable|array',
            'exercises_json' => 'nullable|array',
            'order_index' => 'nullable|integer',
            'badge_color' => 'nullable|string|max:30',
            'icon' => 'nullable|string|max:60',
            'topic_id' => 'nullable|integer|exists:topics,id',
        ]);

        $grammar->fill($validated);
        $grammar->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã cập nhật chuyên đề Ngữ pháp!',
            'data' => $grammar,
        ]);
    }

    /**
     * Delete a Grammar lesson
     */
    public function deleteGrammar(Request $request, $id)
    {
        $this->authorizePermissionAny($request, ['manage_grammar', 'manage_curriculum', 'manage_vocab']);

        $grammar = Grammar::findOrFail($id);
        $grammar->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa chuyên đề Ngữ pháp!',
        ]);
    }

    /**
     * Persist CEFR word custom additions / edits / deletions synced across Admin & Learner
     */
    public function saveCefrOverrides(Request $request)
    {
        $this->authorizePermission($request, 'manage_vocab');

        $validated = $request->validate([
            'custom_words' => 'nullable|array',
            'updated_words' => 'nullable|array',
            'deleted_ids' => 'nullable|array',
        ]);

        $payload = [
            'custom_words' => $validated['custom_words'] ?? [],
            'updated_words' => $validated['updated_words'] ?? [],
            'deleted_ids' => $validated['deleted_ids'] ?? [],
        ];
        AppSetting::setJson('cefr_word_overrides', $payload);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã lưu đồng bộ dữ liệu từ vựng CEFR!',
            'data' => $payload,
        ]);
    }

    /**
     * Update User Role and Permissions
     */
    public function updateUserRoleAndPermissions(Request $request, $id)
    {
        $this->authorizePermission($request, 'manage_roles');

        $user = User::findOrFail($id);
        $validated = $request->validate([
            'role' => 'required|string|max:50',
            'permissions' => 'nullable|array',
        ]);

        $user->role = $validated['role'];
        $user->permissions_json = $validated['permissions'] ?? [];
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => "Đã cập nhật chức vụ [{$user->role}] và phân quyền cho tài khoản [{$user->name}] thành công!",
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'permissions' => $user->permissions_json,
            ],
        ]);
    }
}
