<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        return response()->json([
            'status' => 'success',
            'message' => 'Ghi nhận hoàn thành câu chính tả!',
            'data' => [
                'earned_xp' => $earnedXp,
                'accuracy' => $validated['accuracy'] ?? 100,
            ]
        ]);
    }
}
