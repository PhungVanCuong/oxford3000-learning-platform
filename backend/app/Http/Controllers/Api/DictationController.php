<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\UserStudyLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class DictationController extends Controller
{
    /**
     * Load raw dataset from JSON file.
     */
    protected function getDataset(): array
    {
        $path = database_path('data/dictation_dataset.json');
        if (!File::exists($path)) {
            $path = base_path('../frontend/src/data/dictation_dataset.json');
        }

        if (File::exists($path)) {
            return json_decode(File::get($path), true) ?: ['categories' => [], 'lessons' => [], 'tips' => []];
        }

        return ['categories' => [], 'lessons' => [], 'tips' => []];
    }

    /**
     * GET /api/dictation/categories
     */
    public function getCategories()
    {
        $data = $this->getDataset();
        $categories = $data['categories'] ?? [];
        $lessons = $data['lessons'] ?? [];

        // Count lessons per category
        foreach ($categories as &$cat) {
            $cat['lessonCount'] = count(array_filter($lessons, fn($l) => ($l['categoryId'] ?? '') === $cat['id']));
            $cat['totalChallenges'] = array_sum(array_map(
                fn($l) => ($l['categoryId'] ?? '') === $cat['id'] ? ($l['totalChallenges'] ?? 0) : 0,
                $lessons
            ));
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
                'tips' => $data['tips'] ?? [],
                'totalLessons' => count($lessons),
            ]
        ]);
    }

    /**
     * GET /api/dictation/lessons
     */
    public function getLessons(Request $request)
    {
        $data = $this->getDataset();
        $lessons = $data['lessons'] ?? [];
        $categoryId = $request->query('category');

        if ($categoryId && $categoryId !== 'all') {
            $lessons = array_values(array_filter($lessons, fn($l) => ($l['categoryId'] ?? '') === $categoryId));
        }

        // Return summary without heavy challenge arrays for fast listing
        $summary = array_map(function($l) {
            return [
                'id' => $l['id'],
                'slug' => $l['slug'],
                'categoryId' => $l['categoryId'],
                'categoryName' => $l['categoryName'],
                'title' => $l['title'],
                'level' => $l['level'],
                'color' => $l['color'],
                'totalChallenges' => $l['totalChallenges'],
            ];
        }, $lessons);

        return response()->json([
            'status' => 'success',
            'data' => $summary
        ]);
    }

    /**
     * GET /api/dictation/lesson/{slug}
     */
    public function getLesson($slug)
    {
        $data = $this->getDataset();
        $lessons = $data['lessons'] ?? [];

        $found = null;
        foreach ($lessons as $l) {
            if ($l['slug'] === $slug || $l['id'] === $slug) {
                $found = $l;
                break;
            }
        }

        if (!$found && !empty($lessons)) {
            $found = $lessons[0];
        }

        if (!$found) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'lesson' => $found,
                'tips' => $data['tips'] ?? []
            ]
        ]);
    }

    /**
     * POST /api/dictation/submit-challenge
     */
    public function submitChallenge(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|string',
            'challenge_id' => 'required',
            'accuracy' => 'nullable|numeric',
            'attempts' => 'nullable|integer',
            'time_spent' => 'nullable|numeric'
        ]);

        $earnedXp = 20;
        $user = auth('sanctum')->user() ?? $request->user();
        if (!$user && ($token = $request->bearerToken())) {
            $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if ($accessToken) {
                $user = $accessToken->tokenable;
            }
        }

        $totalXp = $user ? $user->xp_points : 0;
        $xpAwardedNow = 0;

        if ($user) {
            $today = Carbon::now()->toDateString();
            $cacheKey = "dictation_reward_{$user->id}_{$validated['lesson_id']}_{$validated['challenge_id']}";
            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, true, now()->addDays(7));
                $xpAwardedNow = $earnedXp;
                $user->xp_points = ($user->xp_points ?? 0) + $xpAwardedNow;
                $user->last_study_date = $today;
                $user->save();
            }

            $log = UserStudyLog::firstOrNew([
                'user_id' => $user->id,
                'study_date' => $today,
            ]);
            $log->minutes_spent = ($log->minutes_spent ?? 0) + max(1, (int) round(($validated['time_spent'] ?? 30) / 60));
            $log->xp_earned = ($log->xp_earned ?? 0) + $xpAwardedNow;
            $log->exercises_done = ($log->exercises_done ?? 0) + 1;
            $log->save();

            $totalXp = $user->xp_points;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ghi nhận hoàn thành câu chính tả!',
            'data' => [
                'earned_xp' => $xpAwardedNow ?: $earnedXp,
                'xp_awarded_now' => $xpAwardedNow,
                'total_xp' => $totalXp,
                'accuracy' => $validated['accuracy'] ?? 100,
            ]
        ]);
    }
}
