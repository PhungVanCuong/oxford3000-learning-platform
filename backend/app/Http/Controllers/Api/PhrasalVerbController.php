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
        $category = $request->input('category', 'all');
        $partNumber = $request->input('part_number', 'all');
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

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        if ($partNumber !== 'all') {
            $query->where('part_number', (int)$partNumber);
        }

        $items = $query->orderBy('id', 'asc')->get();

        // Attach user progress
        $progressMap = collect();
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
                'category' => $p->category,
                'part_number' => $p->part_number,
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

    private function authorizeManagePhrases(Request $request)
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
        if (!$user || !$user->hasPermission('manage_phrases')) {
            abort(response()->json([
                'status' => 'error',
                'message' => 'Bạn không có quyền quản lý cụm từ (yêu cầu quyền: manage_phrases).'
            ], 403));
        }
        return $user;
    }

    public function store(Request $request)
    {
        $this->authorizeManagePhrases($request);

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
        $this->authorizeManagePhrases($request);

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

    public function destroy(Request $request, $id)
    {
        $this->authorizeManagePhrases($request);

        $phrase = PhrasalVerb::findOrFail($id);
        $phrase->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa cụm từ thành công!',
        ]);
    }

    /**
     * Learning methodology from Word Document
     */
    public function getMethods()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'title' => 'Phương pháp học cụm động từ tiếng Anh thực chiến',
                'description' => 'Học các động từ thông dụng theo nhóm từ gốc sẽ khiến bạn mau chán, không hiệu quả bằng việc học nhóm từ phụ trợ, nhóm theo chủ đề hoặc đặt trong bối cảnh thực tế.',
                'methods' => [
                    [
                        'id' => 1,
                        'title' => 'Không nhóm theo động từ gốc',
                        'icon' => 'bi-x-circle-fill',
                        'badge' => 'Sai lầm phổ biến',
                        'badge_color' => 'danger',
                        'content' => 'Phương pháp phổ biến nhất trong sách giáo trình là gom các cụm từ bắt đầu bằng chung một động từ (ví dụ get in, get out, get by, get up...). Nếu học theo cách này, bạn sẽ rất mau chán vì không tìm thấy điểm chung ngữ nghĩa, chỉ học vẹt mà không hiểu bản chất.'
                    ],
                    [
                        'id' => 2,
                        'title' => 'Nhóm theo từ phụ trợ (Particle: Preposition / Adverb)',
                        'icon' => 'bi-lightbulb-fill',
                        'badge' => 'Phương pháp cốt lõi',
                        'badge_color' => 'teal',
                        'content' => 'Một cụm động từ gồm động từ (verb) và từ phụ trợ (particle). Các từ phụ trợ thường biểu thị một số nghĩa chính. Chẳng hạn: giới từ "out" mang nghĩa hoàn toàn hết, kết thúc, biến mất (They\'re out of bread, The fire went out, The power went out). Khi nắm rõ nghĩa của từ phụ trợ, bạn có thể dễ dàng suy luận nghĩa của cụm từ mà không cần tra từ điển.'
                    ],
                    [
                        'id' => 3,
                        'title' => 'Học cụm từ theo ngữ cảnh & chủ đề thực tế',
                        'icon' => 'bi-chat-heart-fill',
                        'badge' => 'Ứng dụng giao tiếp',
                        'badge_color' => 'success',
                        'content' => 'Nhóm cụm động từ theo đề tài như cảm xúc, miêu tả bạn bè, tình yêu, mua sắm, công việc. Cách phân chia này giúp người học liên kết các cụm động từ với nhau trong một mạng lưới ngữ nghĩa sinh động và đưa thẳng vào giao tiếp tự nhiên như người bản xứ.'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Practice exercises from Word Document
     */
    public function getExercises()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'part1' => [
                    'title' => 'Bài 1: Điền cụm từ tiếng Anh thích hợp',
                    'instructions' => 'Hoàn thành các câu dưới đây bằng cách điền dạng đúng của cụm từ.',
                    'questions' => [
                        ['id' => 1, 'prompt' => "Don't smoke in the forest. Fires (break) ...... easily at this time of the year.", 'expected' => 'break out', 'explain' => 'break out nghĩa là bùng phát (hỏa hoạn, ngọn lửa).'],
                        ['id' => 2, 'prompt' => 'I (look)………… seeing my friends again.', 'expected' => 'look forward to', 'explain' => 'look forward to + V-ing nghĩa là háo hức chờ đợi điều gì.'],
                        ['id' => 3, 'prompt' => "I'm afraid; we have (run)….. of apple juice. Will orange juice do?", 'expected' => 'run out', 'explain' => 'run out of nghĩa là cạn kiệt, hết sạch.'],
                        ['id' => 4, 'prompt' => 'Your website has helped me a lot to (keep)……. the good work.', 'expected' => 'keep up', 'explain' => 'keep up the good work nghĩa là tiếp tục phát huy phong độ tốt.'],
                        ['id' => 5, 'prompt' => 'A friend of mine has (call) ….. her wedding.', 'expected' => 'called off', 'explain' => 'call off nghĩa là hủy bỏ (đám cưới, sự kiện).'],
                        ['id' => 6, 'prompt' => "His mother can't (put) …… his terrible behavior anymore.", 'expected' => 'put up with', 'explain' => 'put up with nghĩa là chịu đựng điều gì/ai đó.'],
                        ['id' => 7, 'prompt' => 'As an excuse for being late, she (make) …… a whole story.', 'expected' => 'made up', 'explain' => 'make up a story nghĩa là bịa ra, dựng chuyện.'],
                        ['id' => 8, 'prompt' => 'I got (carry) ….. by his enthusiasm.', 'expected' => 'carried away', 'explain' => 'get carried away nghĩa là bị cuốn theo, quá phấn khích.'],
                        ['id' => 9, 'prompt' => 'I just cannot (do) ….. my mobile. I always keep it with me.', 'expected' => 'do without', 'explain' => 'do without nghĩa là sống thiếu, xoay sở mà không có thứ gì.'],
                        ['id' => 10, 'prompt' => 'She was very sad because of her father (pass) ….. last week.', 'expected' => 'passed away', 'explain' => 'pass away nghĩa là qua đời (cách nói lịch sự của die).'],
                    ]
                ],
                'part2' => [
                    'title' => 'Bài 2: Hoàn thành dạng đúng của cụm động từ theo ngữ cảnh',
                    'instructions' => 'Điền từ phụ trợ hoặc dạng đúng của cụm động từ sao cho phù hợp với ý nghĩa của ngữ cảnh.',
                    'questions' => [
                        ['id' => 1, 'prompt' => "I don't know where my book is. I must look …. it.", 'expected' => 'look for', 'explain' => 'look for nghĩa là tìm kiếm.'],
                        ['id' => 2, 'prompt' => 'Fill …. the form, please.', 'expected' => 'fill in', 'explain' => 'fill in the form nghĩa là điền thông tin vào mẫu đơn.'],
                        ['id' => 3, 'prompt' => 'The music is too loud. Could you turn ….. the volume, please?', 'expected' => 'turn down', 'explain' => 'turn down the volume nghĩa là vặn nhỏ âm lượng lại.'],
                        ['id' => 4, 'prompt' => "Quick, get ….. the bus or you'll have to walk home.", 'expected' => 'get on', 'explain' => 'get on the bus nghĩa là bước lên xe buýt.'],
                        ['id' => 5, 'prompt' => 'Turn ….. the lights when you go to bed.', 'expected' => 'turn off', 'explain' => 'turn off the lights nghĩa là tắt đèn.'],
                        ['id' => 6, 'prompt' => "Do you mind if I switch …… the TV? I'd like to watch the news.", 'expected' => 'switch on', 'explain' => 'switch on the TV nghĩa là bật ti vi.'],
                        ['id' => 7, 'prompt' => 'The dinner was ruined. I had to throw it ….. .', 'expected' => 'throw it away', 'explain' => 'throw away nghĩa là vứt bỏ đi.'],
                        ['id' => 8, 'prompt' => 'When you enter the house, take ….. your shoes and put …… your slippers.', 'expected' => 'take off, put away', 'explain' => 'take off (cởi giày) và put away / put on (cất hoặc đi dép trong nhà).'],
                        ['id' => 9, 'prompt' => "If you don't know this word, you can look it …… in a dictionary.", 'expected' => 'look it up', 'explain' => 'look up nghĩa là tra cứu thông tin trong từ điển.'],
                        ['id' => 10, 'prompt' => 'Take ….. your shoes.', 'expected' => 'take off', 'explain' => 'take off nghĩa là cởi giày, cởi áo.'],
                    ]
                ]
            ]
        ]);
    }

    /**
     * Check exercise answer with flexible syntax and smart fallback
     */
    public function checkExercise(Request $request)
    {
        $validated = $request->validate([
            'user_answer' => 'required|string',
            'expected_answer' => 'required|string',
        ]);

        $userAns = mb_strtolower(trim($validated['user_answer']), 'UTF-8');
        $expAns = mb_strtolower(trim($validated['expected_answer']), 'UTF-8');

        // Normalize punctuation, spaces, and connectors like 'and', '&', ',', '-'
        $normalize = function($s) {
            $s = preg_replace('/[.,?!:;"\'`\-_()]/u', ' ', $s);
            $s = preg_replace('/\b(and|or|with)\b/u', ' ', $s);
            $s = preg_replace('/\s+/u', ' ', $s);
            return trim($s);
        };

        $cleanUser = $normalize($userAns);
        $cleanExp = $normalize($expAns);

        // Check exact match after normalization
        $isMatch = ($cleanUser === $cleanExp);

        // Check if multiple parts match (e.g. "take off, put away" or "reading and booking")
        if (!$isMatch) {
            $userTokens = array_filter(explode(' ', $cleanUser));
            $expTokens = array_filter(explode(' ', $cleanExp));
            $diff = array_diff($expTokens, $userTokens);
            if (empty($diff) && count($userTokens) >= count($expTokens)) {
                $isMatch = true;
            } else {
                similar_text($cleanUser, $cleanExp, $pct);
                if ($pct >= 80) {
                    $isMatch = true;
                }
            }
        }

        $user = auth('sanctum')->user();
        if ($isMatch && $user) {
            $user->increment('xp_points', 15);
            if ($user->streak_days == 0) {
                $user->streak_days = 1;
            }
            $user->last_study_date = now()->toDateString();
            $user->save();

            // Record to UserStudyLog
            $log = \App\Models\UserStudyLog::firstOrCreate(
                ['user_id' => $user->id, 'study_date' => now()->toDateString()],
                ['minutes_spent' => 0, 'words_learned' => 0, 'xp_earned' => 0, 'exercises_done' => 0]
            );
            $log->increment('xp_earned', 15);
            $log->increment('exercises_done', 1);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'passed' => $isMatch,
                'user_answer' => $validated['user_answer'],
                'expected_answer' => $validated['expected_answer'],
                'score' => $isMatch ? 100 : 50,
                'awarded_xp' => $isMatch ? 15 : 0,
                'explanation' => $isMatch
                    ? 'Chính xác! Bạn đã vận dụng đúng dạng cụm từ theo ngữ cảnh.'
                    : "Chưa đúng. Đáp án chuẩn là: \"{$validated['expected_answer']}\".",
            ]
        ]);
    }

    /**
     * Overall statistics for phrasal verbs and common phrases
     */
    public function getStats()
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;

        $totalPhrases = PhrasalVerb::count();
        $totalPhrasalVerbs100 = PhrasalVerb::where('category', 'phrasal_verb_100')->count();
        $totalCommonPhrases1000 = PhrasalVerb::where('category', 'common_phrase_1000')->count();

        $mastered = 0;
        $learning = 0;
        if ($userId) {
            $mastered = UserPhraseProgress::where('user_id', $userId)->where('status', 'mastered')->count();
            $learning = UserPhraseProgress::where('user_id', $userId)->where('status', 'learning')->count();
        }

        $unlearned = max(0, $totalPhrases - $mastered - $learning);
        $percent = $totalPhrases > 0 ? min(100, round(($mastered / $totalPhrases) * 100, 1)) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_phrases' => $totalPhrases,
                'total_phrasal_verbs' => $totalPhrasalVerbs100,
                'total_common_phrases' => $totalCommonPhrases1000,
                'mastered' => $mastered,
                'learning' => $learning,
                'unlearned' => $unlearned,
                'percent' => $percent,
            ]
        ]);
    }
}
