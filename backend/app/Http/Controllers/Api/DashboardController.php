<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vocabulary;
use App\Models\UserVocabularyProgress;
use App\Models\RoadmapNode;
use App\Models\QuizAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // For development/demo, default to first user if unauthenticated
        $user = $request->user() ?? User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Học viên Oxford 3000',
                'email' => 'learner@oxford.com',
                'password' => bcrypt('password123'),
                'streak_days' => 1,
                'xp_points' => 100,
                'current_level' => 'A1',
                'last_active_date' => now()->toDateString(),
            ]);
        }

        $totalWords = Vocabulary::count();
        $progress = UserVocabularyProgress::where('user_id', $user->id)->get();

        $masteredCount = $progress->where('status', 'mastered')->count();
        $learningCount = $progress->where('status', 'learning')->count();
        $newCount = max(0, $totalWords - ($masteredCount + $learningCount));

        $masteredPercentage = $totalWords > 0 ? round(($masteredCount / $totalWords) * 100, 1) : 0;
        $learningPercentage = $totalWords > 0 ? round(($learningCount / $totalWords) * 100, 1) : 0;

        // Next active node to continue
        $activeNode = RoadmapNode::where('is_locked', false)
            ->orderBy('order_index', 'desc')
            ->first();

        if (!$activeNode) {
            $activeNode = RoadmapNode::orderBy('order_index', 'asc')->first();
        }

        // 7-day stats
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $dayLabel = $date->isoFormat('ddd');

            $attemptsCount = QuizAttempt::where('user_id', $user->id)
                ->whereDate('created_at', $dateStr)
                ->count();

            $wordsReviewed = UserVocabularyProgress::where('user_id', $user->id)
                ->whereDate('last_reviewed_at', $dateStr)
                ->count();

            $weeklyStats[] = [
                'date' => $dateStr,
                'day' => $dayLabel,
                'quiz_count' => $attemptsCount,
                'words_reviewed' => max($wordsReviewed, $attemptsCount * 5),
            ];
        }

        // Smart review words (Spaced repetition due or need practice)
        $reviewWords = UserVocabularyProgress::with('vocabulary')
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('next_review_at', '<=', now())
                  ->orWhere('status', 'learning')
                  ->orWhere('correct_streak', '<', 2);
            })
            ->orderBy('correct_streak', 'asc')
            ->take(8)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->vocabulary->id,
                    'word' => $p->vocabulary->word,
                    'phonetic_us' => $p->vocabulary->phonetic_us,
                    'part_of_speech' => $p->vocabulary->part_of_speech,
                    'meaning_vi' => $p->vocabulary->meaning_vi,
                    'status' => $p->status,
                    'correct_streak' => $p->correct_streak,
                ];
            });

        // If user has few review words, suggest random unmastered words
        if ($reviewWords->isEmpty()) {
            $reviewWords = Vocabulary::inRandomOrder()->take(6)->get()->map(function ($v) {
                return [
                    'id' => $v->id,
                    'word' => $v->word,
                    'phonetic_us' => $v->phonetic_us,
                    'part_of_speech' => $v->part_of_speech,
                    'meaning_vi' => $v->meaning_vi,
                    'status' => 'new',
                    'correct_streak' => 0,
                ];
            });
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'streak_days' => $user->streak_days,
                    'xp_points' => $user->xp_points,
                    'current_level' => $user->current_level,
                ],
                'stats' => [
                    'total_words' => $totalWords,
                    'mastered_count' => $masteredCount,
                    'learning_count' => $learningCount,
                    'new_count' => $newCount,
                    'mastered_percentage' => $masteredPercentage,
                    'learning_percentage' => $learningPercentage,
                ],
                'active_node' => $activeNode ? [
                    'id' => $activeNode->id,
                    'title' => $activeNode->title,
                    'description' => $activeNode->description,
                    'node_type' => $activeNode->node_type,
                    'order_index' => $activeNode->order_index,
                ] : null,
                'weekly_stats' => $weeklyStats,
                'smart_review_words' => $reviewWords,
            ],
        ]);
    }
}
