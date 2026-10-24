<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DailyStudyController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\GrammarController;
use App\Http\Controllers\Api\IpaController;
use App\Http\Controllers\Api\LearningTipController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\DictionaryController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PhrasalVerbController;
use App\Http\Controllers\Api\CurriculumRoadmapController;
use App\Http\Controllers\Api\ArenaController;
use App\Http\Controllers\Api\ActivityController;

// 1. Authentication & Profile
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/register-send-otp', [AuthController::class, 'sendRegisterOtp']);
Route::post('/auth/register-with-otp', [AuthController::class, 'registerWithOtp']);
Route::post('/auth/forgot-password-send-otp', [AuthController::class, 'sendForgotPasswordOtp']);
Route::post('/auth/reset-password-with-otp', [AuthController::class, 'resetPasswordWithOtp']);
Route::post('/auth/reset-progress', [AuthController::class, 'resetMyProgress']);
Route::get('/auth/me', [AuthController::class, 'me']);
Route::post('/auth/profile', [AuthController::class, 'updateProfile']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

// 2. Dynamic Daily Chunking (15 words = 200 days, 20 words = 150 days, 100% unlocked)
Route::get('/daily-study', [DailyStudyController::class, 'index']);
Route::get('/daily-study/day/{day}', [DailyStudyController::class, 'showDay']);
Route::get('/daily-study/quiz/{day}', [DailyStudyController::class, 'generateDayQuiz']);
Route::get('/daily-study/custom', [DailyStudyController::class, 'customMultiDay']);

// 3. Topic-based Roadmap (100% unlocked)
Route::get('/topics', [TopicController::class, 'index']);
Route::get('/topics/{idOrSlug}', [TopicController::class, 'show']);
Route::get('/topics/quiz/{idOrSlug}', [TopicController::class, 'generateTopicQuiz']);

// 4. Grammar Lessons (Chuyên đề Ngữ pháp)
Route::get('/grammars', [GrammarController::class, 'index']);
Route::post('/grammars/check-translation', [GrammarController::class, 'checkTranslation']);
Route::get('/grammars/{id}', [GrammarController::class, 'show']);
Route::post('/grammars/{id}/quiz', [GrammarController::class, 'submitQuiz']);
Route::get('/grammars/quiz/{id}', [GrammarController::class, 'getQuestions']);

// 4.1 Classroom Quiz Arena (Đấu trường Trắc nghiệm Trực tiếp)
Route::get('/arena/rooms', [ArenaController::class, 'index']);
Route::post('/arena/rooms', [ArenaController::class, 'store']);
Route::post('/arena/rooms/join', [ArenaController::class, 'join']);
Route::get('/arena/rooms/{id}', [ArenaController::class, 'show']);
Route::post('/arena/rooms/{id}/start', [ArenaController::class, 'start']);
Route::post('/arena/rooms/{id}/answer', [ArenaController::class, 'submitAnswer']);

// 4.2 User Activity & Productivity Charts (Weekly frequency & GitHub heatmap)
Route::get('/user/study-stats', [ActivityController::class, 'getStudyStats']);
Route::get('/user/contribution-heatmap', [ActivityController::class, 'getContributionHeatmap']);

// 5. 44 IPA Sounds
Route::get('/ipa-sounds', [IpaController::class, 'index']);

// 6. Learning Tips
Route::get('/learning-tips', [LearningTipController::class, 'index']);

// 7. Progress & Quiz Submissions
Route::post('/progress/quiz-submit', [ProgressController::class, 'submitQuiz']);
Route::post('/progress/word-status', [ProgressController::class, 'updateWordStatus']);

// 8. Phrasal Verbs & Collocations (Học theo Cụm từ)
Route::get('/phrasal-verbs', [PhrasalVerbController::class, 'index']);
Route::post('/phrasal-verbs/status', [PhrasalVerbController::class, 'updateStatus']);
Route::post('/phrasal-verbs', [PhrasalVerbController::class, 'store']);
Route::put('/phrasal-verbs/{id}', [PhrasalVerbController::class, 'update']);
Route::delete('/phrasal-verbs/{id}', [PhrasalVerbController::class, 'destroy']);

// 9. Dictionary Search (3000+ words) & Aliases
Route::get('/dictionary/search', [DictionaryController::class, 'search']);
Route::get('/vocabulary/search', [DictionaryController::class, 'search']);

// 10. Gemini AI Integration
Route::post('/ai/generate-examples', [AdminController::class, 'generateAiExamples']);
Route::post('/ai/explain', [AdminController::class, 'aiExplainOrCorrect']);

// 11. NEW CURRICULUM ROADMAP (Lộ trình 25 Chủ đề Chuyên sâu từ 25 files CSV x 10 Giai đoạn x 200 câu)
Route::get('/curriculum', [CurriculumRoadmapController::class, 'index']);
Route::get('/curriculum/{topicId}/all-terms', [CurriculumRoadmapController::class, 'getAllTopicTerms']);
Route::get('/curriculum/{idOrSlug}', [CurriculumRoadmapController::class, 'show']);
Route::get('/curriculum/{topicId}/stage/{stageNum}', [CurriculumRoadmapController::class, 'getStage']);
Route::post('/curriculum/stage-submit', [CurriculumRoadmapController::class, 'submitStage']);
Route::post('/curriculum/term-status', [CurriculumRoadmapController::class, 'updateTermStatus']);

// 12. Admin Dashboard Management & Role Permissions
Route::prefix('admin')->group(function () {
    Route::get('/stats', [AdminController::class, 'getDashboardStats']);
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::get('/users/{id}', [AdminController::class, 'getUserDetails']);
    Route::post('/users', [AdminController::class, 'createUser']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/users/{id}/reset-progress', [AdminController::class, 'resetUserProgress']);
    Route::get('/permissions', [AdminController::class, 'getPermissionsList']);
    Route::put('/users/{id}/role-permissions', [AdminController::class, 'updateUserRoleAndPermissions']);

    Route::post('/vocabularies', [AdminController::class, 'createVocabulary']);
    Route::put('/vocabularies/{id}', [AdminController::class, 'updateVocabulary']);
    Route::delete('/vocabularies/{id}', [AdminController::class, 'deleteVocabulary']);

    // Admin Curriculum Management
    Route::get('/curriculum/topics', [CurriculumRoadmapController::class, 'adminGetTopics']);
    Route::put('/curriculum/topics/{id}', [CurriculumRoadmapController::class, 'adminUpdateTopic']);
    Route::get('/curriculum/topics/{id}/terms', [CurriculumRoadmapController::class, 'adminGetTerms']);
    Route::post('/curriculum/terms', [CurriculumRoadmapController::class, 'adminCreateTerm']);
    Route::put('/curriculum/terms/{id}', [CurriculumRoadmapController::class, 'adminUpdateTerm']);
    Route::delete('/curriculum/terms/{id}', [CurriculumRoadmapController::class, 'adminDeleteTerm']);

    // Admin Arena Quiz Room Management
    Route::get('/arena/rooms', [ArenaController::class, 'adminIndex']);
    Route::post('/arena/rooms/{id}/close', [ArenaController::class, 'closeRoom']);
    Route::delete('/arena/rooms/{id}', [ArenaController::class, 'destroy']);
});
