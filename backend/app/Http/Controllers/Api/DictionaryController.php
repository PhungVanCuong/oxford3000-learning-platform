<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vocabulary;
use App\Models\Topic;
use App\Models\User;
use App\Models\UserVocabularyProgress;

class DictionaryController extends Controller
{
    public function search(Request $request)
    {
        $user = $request->user() ?? User::where('email', 'cuongphung1810@gmail.com')->first() ?? User::first();
        $userId = $user ? $user->id : 1;

        $q = trim($request->input('q', ''));
        $topicId = $request->input('topic_id', '');
        $pos = $request->input('part_of_speech', '');
        $status = $request->input('status', 'all');
        $level = $request->input('cefr_level', '');
        $letter = strtoupper(trim($request->input('letter', '')));
        $idRange = $request->input('id_range', '');
        $sort = $request->input('sort', 'default');
        $perPage = min(50, max(12, (int) $request->input('per_page', 24)));

        $query = Vocabulary::query();

        // 1. Text Search
        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('word', 'like', "%{$q}%")
                    ->orWhere('meaning_vi', 'like', "%{$q}%")
                    ->orWhere('phonetic_us', 'like', "%{$q}%");
            });
        }

        // 2. Topic Filter
        if (!empty($topicId)) {
            $query->where('topic_id', $topicId);
        }

        // 3. Part of speech
        if (!empty($pos)) {
            $query->where('part_of_speech', 'like', "%{$pos}%");
        }

        // 4. CEFR Level
        if (!empty($level)) {
            $query->where('cefr_level', $level);
        }

        // 5. Alphabetical Letter filter (A-Z)
        if (!empty($letter) && strlen($letter) === 1 && ctype_alpha($letter)) {
            $query->where('word', 'like', "{$letter}%");
        }

        // 6. Sequential ID range (e.g. 1-100, 101-200)
        if (!empty($idRange) && str_contains($idRange, '-')) {
            [$minId, $maxId] = explode('-', $idRange);
            $query->whereBetween('id', [(int) $minId, (int) $maxId]);
        }

        // 7. Status filter
        if ($status !== 'all') {
            if ($status === 'unlearned') {
                $learnedIds = UserVocabularyProgress::where('user_id', $userId)
                    ->whereIn('status', ['learning', 'mastered'])
                    ->pluck('vocabulary_id');
                $query->whereNotIn('id', $learnedIds);
            } else {
                $targetIds = UserVocabularyProgress::where('user_id', $userId)
                    ->where('status', $status)
                    ->pluck('vocabulary_id');
                $query->whereIn('id', $targetIds);
            }
        }

        // 8. Sorting
        switch ($sort) {
            case 'alphabetical':
                $query->orderBy('word', 'asc');
                break;
            case 'alphabetical_desc':
                $query->orderBy('word', 'desc');
                break;
            case 'newest':
                $query->orderBy('id', 'desc');
                break;
            case 'frequency':
                // A1 first, then A2, B1, B2
                $query->orderByRaw("FIELD(cefr_level, 'A1', 'A2', 'B1', 'B2', '') ASC")->orderBy('id', 'asc');
                break;
            case 'default':
            default:
                $query->orderBy('id', 'asc');
                break;
        }

        $results = $query->paginate($perPage);

        // Attach user progress map
        $pageIds = collect($results->items())->pluck('id');
        $progressMap = UserVocabularyProgress::where('user_id', $userId)
            ->whereIn('vocabulary_id', $pageIds)
            ->get()
            ->keyBy('vocabulary_id');

        $transformed = collect($results->items())->map(function ($w) use ($progressMap) {
            $prog = $progressMap->get($w->id);
            return [
                'id' => $w->id,
                'word' => $w->word,
                'phonetic_us' => $w->phonetic_us,
                'part_of_speech' => $w->part_of_speech,
                'meaning_vi' => $w->meaning_vi,
                'example_en' => $w->example_en,
                'example_vi' => $w->example_vi,
                'audio_url' => $w->audio_url,
                'cefr_level' => $w->cefr_level,
                'topic_id' => $w->topic_id,
                'status' => $prog ? $prog->status : 'unlearned',
                'correct_count' => $prog ? $prog->correct_count : 0,
            ];
        });

        // Get topics for quick filter options
        $topics = Topic::select('id', 'name', 'icon')->orderBy('order_index')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'items' => $transformed,
                'topics' => $topics,
            ],
        ]);
    }
}
