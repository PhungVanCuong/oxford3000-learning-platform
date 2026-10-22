<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vocabulary;
use App\Models\Topic;
use App\Models\Grammar;
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
    /**
     * Admin Overview Stats
     */
    public function getDashboardStats()
    {
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
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'xp_points' => $u->xp_points ?? 0,
                'streak_days' => $u->streak_days ?? 1,
                'mastered_count' => $oxfordMastered + $curriculumMastered,
                'oxford_count' => $oxfordMastered,
                'curriculum_count' => $curriculumMastered,
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
        $users = User::withCount(['quizHistories'])->orderBy('id')->get()->map(function ($u) {
            $masteredCount = $u->vocabularyProgress()->where('status', 'mastered')->count();
            $completedGrammars = $u->grammarProgress()->where('is_completed', true)->count();
            $avgAccuracy = $u->quiz_histories_count > 0 ? round($u->quizHistories()->avg('accuracy_percent'), 1) : 0;

            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role,
                'permissions' => $u->permissions_json ?? [],
                'birth_date' => $u->birth_date ? $u->birth_date->format('d/m/Y') : null,
                'xp_points' => $u->xp_points,
                'streak_days' => $u->streak_days,
                'words_per_day' => $u->words_per_day,
                'mastered_words' => $masteredCount,
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
    public function getUserDetails($id)
    {
        $user = User::findOrFail($id);

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
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
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'role' => 'nullable|in:admin,user',
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

    public function deleteUser($id)
    {
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

    public function deleteVocabulary($id)
    {
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
    public function resetUserProgress($id)
    {
        $user = User::findOrFail($id);

        \App\Models\UserVocabularyProgress::where('user_id', $user->id)->delete();
        \App\Models\UserGrammarProgress::where('user_id', $user->id)->delete();
        \App\Models\QuizHistory::where('user_id', $user->id)->delete();

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
     * Get available roles & permissions definitions
     */
    public function getPermissionsList()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'roles' => [
                    ['key' => 'admin', 'name' => 'Quản trị viên cấp cao (Admin)', 'badge' => 'danger'],
                    ['key' => 'moderator', 'name' => 'Điều hành viên / Giáo viên', 'badge' => 'warning'],
                    ['key' => 'user', 'name' => 'Học viên tiêu chuẩn', 'badge' => 'secondary'],
                ],
                'permissions' => [
                    ['key' => 'manage_vocab', 'name' => 'Quản lý kho từ vựng (Thêm, Sửa, Xóa & AI)', 'category' => 'Từ vựng'],
                    ['key' => 'manage_phrases', 'name' => 'Quản lý cụm từ & Phrasal Verbs', 'category' => 'Cụm từ'],
                    ['key' => 'manage_users', 'name' => 'Quản lý tài khoản & Phân quyền', 'category' => 'Hệ thống'],
                    ['key' => 'reset_progress', 'name' => 'Quyền Reset tiến độ học viên', 'category' => 'Học viên'],
                    ['key' => 'view_reports', 'name' => 'Xem thống kê toàn hệ thống', 'category' => 'Báo cáo'],
                ],
            ],
        ]);
    }

    /**
     * Update User Role and Permissions
     */
    public function updateUserRoleAndPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'role' => 'required|in:admin,moderator,user',
            'permissions' => 'nullable|array',
        ]);

        $user->role = $validated['role'];
        $user->permissions_json = $validated['permissions'] ?? [];
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => "Đã cập nhật chức vụ và phân quyền cho học viên {$user->name} thành công!",
            'data' => [
                'id' => $user->id,
                'role' => $user->role,
                'permissions' => $user->permissions_json,
            ],
        ]);
    }
}
