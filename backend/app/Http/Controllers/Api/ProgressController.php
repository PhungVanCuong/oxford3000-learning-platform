<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserVocabularyProgress;
use App\Models\UserGrammarProgress;
use App\Models\QuizHistory;
use Carbon\Carbon;

class ProgressController extends Controller
{
    /**
     * Submit Quiz results (Daily, Multi-day, Topic, or Grammar)
     */
    public function submitQuiz(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để lưu kết quả bài kiểm tra và nhận điểm thưởng XP.',
            ], 401);
        }

        $validated = $request->validate([
            'quiz_type' => 'required|in:daily_vocab,multi_day_review,topic_vocab,grammar',
            'reference_info' => 'required|string',
            'answers' => 'required|array',
            'answers.*.prompt' => 'nullable|string',
            'answers.*.user_answer' => 'nullable|string',
            'answers.*.correct_answer' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
            'answers.*.explanation' => 'nullable|string',
            'answers.*.vocabulary_id' => 'nullable|integer',
            'answers.*.grammar_id' => 'nullable|integer',
        ]);

        $answers = $validated['answers'];
        $totalQuestions = count($answers);
        $correctCount = collect($answers)->where('is_correct', true)->count();
        $accuracyPercent = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 1) : 0;

        // XP Calculation
        $xpEarned = ($correctCount * 10);
        if ($accuracyPercent >= 80) {
            $xpEarned += 50; // Bonus for high accuracy
        }

        // 1. Save Quiz History
        $history = QuizHistory::create([
            'user_id' => $user->id,
            'quiz_type' => $validated['quiz_type'],
            'reference_info' => $validated['reference_info'],
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'accuracy_percent' => $accuracyPercent,
            'xp_earned' => $xpEarned,
            'details_json' => $answers,
        ]);

        // 2. Update Vocabulary or Grammar Progress for this exact user
        $now = Carbon::now();
        foreach ($answers as $ans) {
            if (!empty($ans['vocabulary_id'])) {
                $prog = UserVocabularyProgress::firstOrNew([
                    'user_id' => $user->id,
                    'vocabulary_id' => $ans['vocabulary_id'],
                ]);

                if ($ans['is_correct']) {
                    $prog->correct_count = ($prog->correct_count ?? 0) + 1;
                    // If answered correctly in quiz, mark as mastered if >= 2 correct, or promote from unlearned
                    if ($prog->correct_count >= 2 || $accuracyPercent >= 80) {
                        $prog->status = 'mastered';
                    } elseif ($prog->status === 'unlearned') {
                        $prog->status = 'learning';
                    }
                } else {
                    $prog->wrong_count = ($prog->wrong_count ?? 0) + 1;
                    $prog->status = 'learning';
                }
                $prog->last_reviewed_at = $now;
                $prog->save();
            }

            if (!empty($ans['grammar_id']) && $validated['quiz_type'] === 'grammar') {
                $gProg = UserGrammarProgress::firstOrNew([
                    'user_id' => $user->id,
                    'grammar_id' => $ans['grammar_id'],
                ]);

                $gProg->best_score = max($gProg->best_score ?? 0, (int)$accuracyPercent);
                if ($accuracyPercent >= 80) {
                    $gProg->is_completed = true;
                }
                $gProg->save();
            }
        }

        // 3. Update User XP & Streak
        $user->xp_points = ($user->xp_points ?? 0) + $xpEarned;

        $today = Carbon::today()->toDateString();
        $lastStudy = $user->last_study_date ? Carbon::parse($user->last_study_date)->toDateString() : null;

        if ($lastStudy !== $today) {
            if ($lastStudy === Carbon::yesterday()->toDateString()) {
                $user->streak_days = ($user->streak_days ?? 0) + 1;
            } else {
                $user->streak_days = 1;
            }
            $user->last_study_date = $today;
        }
        $user->save();

        return response()->json([
            'status' => 'success',
            'data' => [
                'history_id' => $history->id,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctCount,
                'accuracy_percent' => $accuracyPercent,
                'xp_earned' => $xpEarned,
                'current_total_xp' => $user->xp_points,
                'streak_days' => $user->streak_days,
                'details' => $answers,
            ],
        ]);
    }

    /**
     * Update Word status (Flashcard shortcuts: [S] Mastered, [X] Learning)
     */
    public function updateWordStatus(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để lưu trạng thái từ vựng.',
            ], 401);
        }

        $validated = $request->validate([
            'vocabulary_id' => 'required|integer|exists:vocabularies,id',
            'status' => 'required|in:unlearned,learning,mastered',
        ]);

        $prog = UserVocabularyProgress::firstOrNew([
            'user_id' => $user->id,
            'vocabulary_id' => $validated['vocabulary_id'],
        ]);

        $prog->status = $validated['status'];
        if ($validated['status'] === 'mastered') {
            $prog->correct_count = max(3, ($prog->correct_count ?? 0) + 1);
            // Award a small XP for mastering
            $user->xp_points = ($user->xp_points ?? 0) + 5;
        }
        $prog->last_reviewed_at = Carbon::now();
        $prog->save();

        // Update streak if today is first activity
        $today = Carbon::today()->toDateString();
        $lastStudy = $user->last_study_date ? Carbon::parse($user->last_study_date)->toDateString() : null;
        if ($lastStudy !== $today) {
            if ($lastStudy === Carbon::yesterday()->toDateString()) {
                $user->streak_days = ($user->streak_days ?? 0) + 1;
            } else {
                $user->streak_days = 1;
            }
            $user->last_study_date = $today;
        }
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã lưu trạng thái từ vựng thành công!',
            'data' => [
                'vocabulary_id' => $validated['vocabulary_id'],
                'status' => $prog->status,
                'xp_points' => $user->xp_points,
                'streak_days' => $user->streak_days,
            ],
        ]);
    }
}
