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

// 4. Grammar Lessons (101 lessons architecture)
Route::get('/grammars', [GrammarController::class, 'index']);
Route::get('/grammars/{id}', [GrammarController::class, 'show']);
Route::get('/grammars/quiz/{id}', [GrammarController::class, 'getQuestions']);

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

// 11. Admin Dashboard Management & Role Permissions
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
});
