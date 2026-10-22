<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhrasalVerb;
use App\Models\UserPhraseProgress;
use App\Models\Vocabulary;
use App\Models\UserVocabularyProgress;
use Carbon\Carbon;

class PhrasalVerbController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;

        $q = trim($request->input('q', ''));
        $type = $request->input('type', 'all');
        $status = $request->input('status', 'all');

        $query = PhrasalVerb::query();

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('phrase', 'like', "%{$q}%")
                    ->orWhere('meaning_vi', 'like', "%{$q}%");
            });
        }

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $items = $query->orderBy('id', 'asc')->get();

        // Attach user progress
        $progressMap = [];
        if ($userId) {
            $progressMap = UserPhraseProgress::where('user_id', $userId)
                ->get()
                ->keyBy('phrase_id');
        }

        $transformed = $items->map(function ($p) use ($progressMap) {
            $prog = $progressMap->get($p->id) ?? null;
            return [
                'id' => $p->id,
                'phrase' => $p->phrase,
                'type' => $p->type,
                'meaning_vi' => $p->meaning_vi,
                'example_en' => $p->example_en,
                'example_vi' => $p->example_vi,
                'component_words_json' => $p->component_words_json,
                'cefr_level' => $p->cefr_level,
                'status' => $prog ? $prog->status : 'unlearned',
                'correct_count' => $prog ? $prog->correct_count : 0,
            ];
        });

        if ($status !== 'all') {
            $transformed = $transformed->filter(fn($x) => $x['status'] === $status)->values();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => $transformed->count(),
                'items' => $transformed,
            ],
        ]);
    }

    /**
     * Update phrase status and cascade progress to its component single words
     */
    public function updateStatus(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để lưu tiến độ cụm từ.',
            ], 401);
        }

        $validated = $request->validate([
            'phrase_id' => 'required|integer|exists:phrasal_verbs,id',
            'status' => 'required|in:unlearned,learning,mastered',
        ]);

        $phrase = PhrasalVerb::findOrFail($validated['phrase_id']);

        $prog = UserPhraseProgress::firstOrNew([
            'user_id' => $user->id,
            'phrase_id' => $phrase->id,
        ]);

        $prog->status = $validated['status'];
        if ($validated['status'] === 'mastered') {
            $prog->correct_count = max(3, ($prog->correct_count ?? 0) + 1);
        }
        $prog->last_reviewed_at = Carbon::now();
        $prog->save();

        // Cascade progress to each single word component
        $cascadedWords = [];
        if (!empty($phrase->component_words_json) && is_array($phrase->component_words_json)) {
            foreach ($phrase->component_words_json as $wordStr) {
                $vocab = Vocabulary::where('word', 'like', $wordStr)->first();
                if ($vocab) {
                    $vProg = UserVocabularyProgress::firstOrNew([
                        'user_id' => $user->id,
                        'vocabulary_id' => $vocab->id,
                    ]);
                    // Only upgrade status or sync
                    if ($validated['status'] === 'mastered') {
                        $vProg->status = 'mastered';
                        $vProg->correct_count = max(3, ($vProg->correct_count ?? 0) + 1);
                    } elseif ($validated['status'] === 'learning' && $vProg->status === 'unlearned') {
                        $vProg->status = 'learning';
                    }
                    $vProg->last_reviewed_at = Carbon::now();
                    $vProg->save();
                    $cascadedWords[] = $vocab->word;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã cập nhật trạng thái cụm từ và đồng bộ tiến độ các từ đơn liên quan!',
            'data' => [
                'phrase_id' => $phrase->id,
                'status' => $prog->status,
                'cascaded_words' => $cascadedWords,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phrase' => 'required|string|unique:phrasal_verbs,phrase',
            'type' => 'required|in:phrasal_verb,collocation,idiom',
            'meaning_vi' => 'required|string',
            'example_en' => 'nullable|string',
            'example_vi' => 'nullable|string',
            'component_words_json' => 'nullable|array',
            'cefr_level' => 'nullable|string',
        ]);

        $phrase = PhrasalVerb::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm cụm từ mới thành công!',
            'data' => $phrase,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $phrase = PhrasalVerb::findOrFail($id);

        $validated = $request->validate([
            'phrase' => 'nullable|string',
            'type' => 'nullable|in:phrasal_verb,collocation,idiom',
            'meaning_vi' => 'nullable|string',
            'example_en' => 'nullable|string',
            'example_vi' => 'nullable|string',
            'component_words_json' => 'nullable|array',
            'cefr_level' => 'nullable|string',
        ]);

        $phrase->fill($validated);
        $phrase->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật cụm từ thành công!',
            'data' => $phrase,
        ]);
    }

    public function destroy($id)
    {
        $phrase = PhrasalVerb::findOrFail($id);
        $phrase->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa cụm từ thành công!',
        ]);
    }
}
