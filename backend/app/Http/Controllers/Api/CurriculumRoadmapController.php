<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurriculumTopic;
use App\Models\CurriculumTerm;
use App\Models\CurriculumUserProgress;
use App\Models\CurriculumStageProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurriculumRoadmapController extends Controller
{
    /**
     * Map of stage number to terms count and descriptive label
     */
    private const STAGE_CONFIG = [
        1 => ['count' => 10, 'title' => 'Giai đoạn 1: Khởi động - Học 10 thẻ từ', 'type' => 'flashcards', 'description' => 'Học phát âm, mẫu câu và nghĩa của 10 từ đầu tiên qua Flashcard tương tác.'],
        2 => ['count' => 12, 'title' => 'Giai đoạn 2: Tăng tốc - Bài kiểm tra 12 câu', 'type' => 'quiz', 'description' => 'Bài trắc nghiệm 12 câu kiểm tra phản xạ từ vựng và nghĩa tiếng Việt.'],
        3 => ['count' => 14, 'title' => 'Giai đoạn 3: Đột phá - Bài kiểm tra 14 câu', 'type' => 'quiz', 'description' => 'Bài trắc nghiệm 14 câu củng cố cụm từ nâng cao.'],
        4 => ['count' => 16, 'title' => 'Giai đoạn 4: Vượt chướng ngại vật - Bài kiểm tra 16 câu', 'type' => 'quiz', 'description' => 'Trắc nghiệm 16 câu mở rộng ngữ cảnh thực tế.'],
        5 => ['count' => 20, 'title' => 'Giai đoạn 5: Về đích chặng 1 - Bài kiểm tra 20 câu', 'type' => 'quiz', 'description' => 'Bài kiểm tra cột mốc 20 câu tích lũy năng lực.'],
        6 => ['count' => 24, 'title' => 'Giai đoạn 6: Chuyên sâu 1 - Bài kiểm tra 24 câu', 'type' => 'quiz', 'description' => 'Bài kiểm tra chuyên đề 24 câu nâng tầm từ vựng học thuật.'],
        7 => ['count' => 24, 'title' => 'Giai đoạn 7: Chuyên sâu 2 - Bài kiểm tra 24 câu', 'type' => 'quiz', 'description' => 'Bài trắc nghiệm 24 câu làm chủ các mẫu câu nâng cao.'],
        8 => ['count' => 26, 'title' => 'Giai đoạn 8: Nâng cao 1 - Bài kiểm tra 26 câu', 'type' => 'quiz', 'description' => 'Bài kiểm tra 26 câu thử thách tư duy phản xạ.'],
        9 => ['count' => 26, 'title' => 'Giai đoạn 9: Nâng cao 2 - Bài kiểm tra 26 câu', 'type' => 'quiz', 'description' => 'Bài kiểm tra 26 câu bứt phá chuẩn bị cho chặng cuối.'],
        10 => ['count' => 28, 'title' => 'Giai đoạn 10: Về đích - Bài kiểm tra tổng hợp 28 câu', 'type' => 'quiz', 'description' => 'Bài kiểm tra toàn diện 28 câu hoàn tất 200 từ vựng chủ đề!'],
    ];

    /**
     * List all 25 curriculum topics with user progress
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;

        $topics = CurriculumTopic::orderBy('order_num')->get();

        // If user logged in, fetch remembered terms count and stage completion grouped by topic & stage
        $userTermsCountByTopic = [];
        $userStagesByTopic = [];
        $rememberedByStageMap = [];

        if ($userId) {
            $userTermsCountByTopic = CurriculumUserProgress::where('user_id', $userId)
                ->where('status', 'remembered')
                ->select('topic_id', DB::raw('count(*) as count'))
                ->groupBy('topic_id')
                ->pluck('count', 'topic_id')
                ->toArray();

            // Group remembered terms by [topic_id][stage_num]
            $stageTermsData = DB::table('curriculum_user_progress')
                ->join('curriculum_terms', 'curriculum_user_progress.term_id', '=', 'curriculum_terms.id')
                ->where('curriculum_user_progress.user_id', $userId)
                ->where('curriculum_user_progress.status', 'remembered')
                ->select('curriculum_terms.topic_id', 'curriculum_terms.stage_num', DB::raw('count(*) as cnt'))
                ->groupBy('curriculum_terms.topic_id', 'curriculum_terms.stage_num')
                ->get();

            foreach ($stageTermsData as $row) {
                $rememberedByStageMap[$row->topic_id][$row->stage_num] = (int)$row->cnt;
            }

            $completedStages = CurriculumStageProgress::where('user_id', $userId)
                ->get()
                ->groupBy('topic_id');

            foreach ($completedStages as $tId => $stages) {
                $userStagesByTopic[$tId] = $stages->keyBy('stage_num');
            }
        }

        $data = $topics->map(function ($topic) use ($userTermsCountByTopic, $userStagesByTopic, $rememberedByStageMap) {
            $rememberedCount = $userTermsCountByTopic[$topic->id] ?? 0;
            $progressPercent = $topic->total_terms > 0 
                ? min(100, round(($rememberedCount / $topic->total_terms) * 100))
                : 0;

            $topicStagesProgress = $userStagesByTopic[$topic->id] ?? collect();
            $topicRememberedStages = $rememberedByStageMap[$topic->id] ?? [];

            $stages = [];
            $completedStagesCount = 0;

            foreach (self::STAGE_CONFIG as $sNum => $cfg) {
                $stProgress = $topicStagesProgress->get($sNum);
                $remInStage = $topicRememberedStages[$sNum] ?? 0;
                $quizScore = $stProgress ? (int)$stProgress->score : 0;
                
                // Effective score: highest between quiz score and remembered count
                $effectiveScore = max($quizScore, $remInStage);
                $isCompleted = ($remInStage >= $cfg['count']) || ($stProgress && (bool)$stProgress->is_completed);

                if ($isCompleted) {
                    $completedStagesCount++;
                }

                $stages[] = [
                    'stage_num' => $sNum,
                    'title' => $cfg['title'],
                    'type' => $cfg['type'],
                    'count' => $cfg['count'],
                    'description' => $cfg['description'],
                    'is_completed' => (bool)$isCompleted,
                    'score' => $effectiveScore,
                    'remembered_count' => $remInStage,
                    'total_questions' => $cfg['count'],
                ];
            }

            return [
                'id' => $topic->id,
                'order_num' => $topic->order_num,
                'title' => $topic->title,
                'title_vi' => $topic->title_vi,
                'slug' => $topic->slug,
                'icon' => $topic->icon,
                'color' => $topic->color,
                'description' => $topic->description,
                'total_terms' => $topic->total_terms,
                'total_stages' => $topic->total_stages,
                'remembered_terms_count' => $rememberedCount,
                'progress_percent' => $progressPercent,
                'completed_stages_count' => $completedStagesCount,
                'stages' => $stages,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Show topic detail with all 10 stages overview
     */
    public function show(Request $request, $idOrSlug)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;

        $topic = CurriculumTopic::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->firstOrFail();

        $userRememberedCount = 0;
        $userStages = collect();
        $stageTermsMap = [];

        if ($userId) {
            $userRememberedCount = CurriculumUserProgress::where('user_id', $userId)
                ->where('topic_id', $topic->id)
                ->where('status', 'remembered')
                ->count();

            $stageTermsData = DB::table('curriculum_user_progress')
                ->join('curriculum_terms', 'curriculum_user_progress.term_id', '=', 'curriculum_terms.id')
                ->where('curriculum_user_progress.user_id', $userId)
                ->where('curriculum_user_progress.status', 'remembered')
                ->where('curriculum_terms.topic_id', $topic->id)
                ->select('curriculum_terms.stage_num', DB::raw('count(*) as cnt'))
                ->groupBy('curriculum_terms.stage_num')
                ->pluck('cnt', 'stage_num')
                ->toArray();

            $stageTermsMap = $stageTermsData;

            $userStages = CurriculumStageProgress::where('user_id', $userId)
                ->where('topic_id', $topic->id)
                ->get()
                ->keyBy('stage_num');
        }

        $stages = [];
        $completedStagesCount = 0;

        foreach (self::STAGE_CONFIG as $sNum => $cfg) {
            $stProgress = $userStages->get($sNum);
            $remInStage = (int)($stageTermsMap[$sNum] ?? 0);
            $quizScore = $stProgress ? (int)$stProgress->score : 0;
            $effectiveScore = max($quizScore, $remInStage);
            $isCompleted = ($remInStage >= $cfg['count']) || ($stProgress && (bool)$stProgress->is_completed);

            if ($isCompleted) {
                $completedStagesCount++;
            }

            $stages[] = [
                'stage_num' => $sNum,
                'title' => $cfg['title'],
                'type' => $cfg['type'],
                'count' => $cfg['count'],
                'description' => $cfg['description'],
                'is_completed' => (bool)$isCompleted,
                'score' => $effectiveScore,
                'remembered_count' => $remInStage,
                'total_questions' => $cfg['count'],
            ];
        }

        $progressPercent = $topic->total_terms > 0
            ? min(100, round(($userRememberedCount / $topic->total_terms) * 100))
            : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $topic->id,
                'order_num' => $topic->order_num,
                'title' => $topic->title,
                'title_vi' => $topic->title_vi,
                'slug' => $topic->slug,
                'icon' => $topic->icon,
                'color' => $topic->color,
                'description' => $topic->description,
                'total_terms' => $topic->total_terms,
                'total_stages' => $topic->total_stages,
                'remembered_terms_count' => $userRememberedCount,
                'progress_percent' => $progressPercent,
                'completed_stages_count' => $completedStagesCount,
                'stages' => $stages,
            ],
        ]);
    }

    /**
     * Get specific stage details, terms list, and 100% multiple-choice quiz questions
     */
    public function getStage(Request $request, $topicId, $stageNum)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;

        $topic = CurriculumTopic::findOrFail($topicId);
        $stageNum = (int)$stageNum;

        if ($stageNum < 1 || $stageNum > 10) {
            return response()->json(['status' => 'error', 'message' => 'Giai đoạn không hợp lệ (1-10)'], 400);
        }

        $stageConfig = self::STAGE_CONFIG[$stageNum] ?? [
            'count' => 10,
            'title' => "Giai đoạn {$stageNum}",
            'type' => $stageNum === 1 ? 'flashcards' : 'quiz',
            'description' => '',
        ];

        // Fetch terms in this stage
        $terms = CurriculumTerm::where('topic_id', $topic->id)
            ->where('stage_num', $stageNum)
            ->orderBy('order_in_topic')
            ->get();

        // Get user progress for these terms
        $userTermStatuses = [];
        if ($userId) {
            $userTermStatuses = CurriculumUserProgress::where('user_id', $userId)
                ->whereIn('term_id', $terms->pluck('id'))
                ->pluck('status', 'term_id')
                ->toArray();
        }

        // Get distractors pool from other terms in database for multiple-choice questions
        $distractorsPool = CurriculumTerm::where('topic_id', $topic->id)
            ->where('stage_num', '!=', $stageNum)
            ->inRandomOrder()
            ->limit(100)
            ->pluck('definition')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Fallback if not enough distractors from same topic
        if (count($distractorsPool) < 15) {
            $moreDistractors = CurriculumTerm::where('topic_id', '!=', $topic->id)
                ->inRandomOrder()
                ->limit(50)
                ->pluck('definition')
                ->filter()
                ->toArray();
            $distractorsPool = array_merge($distractorsPool, $moreDistractors);
        }

        $formattedTerms = [];
        $quizQuestions = [];

        foreach ($terms as $idx => $t) {
            $userStatus = $userTermStatuses[$t->id] ?? null;

            $formattedTerms[] = [
                'id' => $t->id,
                'order_in_topic' => $t->order_in_topic,
                'stage_num' => $t->stage_num,
                'term' => $t->term,
                'definition' => $t->definition,
                'part_of_speech' => $t->part_of_speech,
                'pattern' => $t->pattern,
                'examples' => $t->examples,
                'is_remembered' => $userStatus === 'remembered',
                'is_learning' => $userStatus === 'learning',
            ];

            // Build Multiple-choice question:
            // Mode: Choose correct Vietnamese meaning of English term
            $correctOption = $t->definition;
            $distractors = [];
            
            // Pick 3 distractors not equal to correct option
            $available = array_values(array_filter($distractorsPool, fn($d) => $d !== $correctOption));
            shuffle($available);
            $distractors = array_slice($available, 0, 3);

            // If still less than 3 distractors, pick from sibling terms in current stage
            if (count($distractors) < 3) {
                foreach ($terms as $sibling) {
                    if ($sibling->id !== $t->id && !in_array($sibling->definition, $distractors)) {
                        $distractors[] = $sibling->definition;
                        if (count($distractors) >= 3) break;
                    }
                }
            }

            $options = array_merge([$correctOption], array_slice($distractors, 0, 3));
            shuffle($options);
            $correctIndex = array_search($correctOption, $options);

            // Clean example sentence to display as context
            $exampleParts = array_filter(array_map('trim', explode('|', $t->examples ?? '')));
            $sampleExample = !empty($exampleParts) ? $exampleParts[0] : '';

            $quizQuestions[] = [
                'question_id' => $t->id,
                'question_num' => $idx + 1,
                'term' => $t->term,
                'part_of_speech' => $t->part_of_speech,
                'pattern' => $t->pattern,
                'sample_example' => $sampleExample,
                'question_text' => "Chọn nghĩa chính xác nhất của từ / cụm từ sau:",
                'options' => $options,
                'correct_index' => $correctIndex !== false ? $correctIndex : 0,
                'correct_meaning' => $correctOption,
            ];
        }

        // Get stage status & remembered terms count for this exact stage
        $stageProgress = null;
        $remInCurrentStage = 0;
        if ($userId) {
            $stageProgress = CurriculumStageProgress::where('user_id', $userId)
                ->where('topic_id', $topic->id)
                ->where('stage_num', $stageNum)
                ->first();

            $remInCurrentStage = count(array_filter($userTermStatuses, fn($s) => $s === 'remembered'));
        }

        $effectiveScore = max($stageProgress ? (int)$stageProgress->score : 0, $remInCurrentStage);
        $totalInStage = count($formattedTerms);
        $isStageCompleted = ($remInCurrentStage >= $totalInStage && $totalInStage > 0) 
            || ($stageProgress && (bool)$stageProgress->is_completed);

        return response()->json([
            'status' => 'success',
            'data' => [
                'topic' => [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'title_vi' => $topic->title_vi,
                    'order_num' => $topic->order_num,
                    'slug' => $topic->slug,
                    'icon' => $topic->icon,
                    'color' => $topic->color,
                ],
                'stage' => [
                    'stage_num' => $stageNum,
                    'title' => $stageConfig['title'],
                    'type' => $stageConfig['type'],
                    'count' => $totalInStage,
                    'description' => $stageConfig['description'],
                    'is_completed' => (bool)$isStageCompleted,
                    'score' => $effectiveScore,
                    'remembered_count' => $remInCurrentStage,
                ],
                'terms' => $formattedTerms,
                'quiz_questions' => $quizQuestions,
            ],
        ]);
    }

    /**
     * Submit Stage Quiz result & auto-update term mastery
     */
    public function submitStage(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để lưu kết quả'], 401);
        }

        $validated = $request->validate([
            'topic_id' => 'required|exists:curriculum_topics,id',
            'stage_num' => 'required|integer|min:1|max:10',
            'score' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
            'mastered_term_ids' => 'nullable|array',
            'mastered_term_ids.*' => 'integer',
        ]);

        $topicId = $validated['topic_id'];
        $stageNum = $validated['stage_num'];
        $score = $validated['score'];
        $totalQuestions = $validated['total_questions'];
        $masteredTermIds = $validated['mastered_term_ids'] ?? [];

        // Mark answered correctly terms as 'remembered'
        if (!empty($masteredTermIds)) {
            foreach ($masteredTermIds as $tId) {
                CurriculumUserProgress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'term_id' => $tId,
                    ],
                    [
                        'topic_id' => $topicId,
                        'status' => 'remembered',
                        'last_reviewed_at' => now(),
                    ]
                );
            }
        }

        // Count total remembered in this stage
        $stageRememberedCount = DB::table('curriculum_user_progress')
            ->join('curriculum_terms', 'curriculum_user_progress.term_id', '=', 'curriculum_terms.id')
            ->where('curriculum_user_progress.user_id', $user->id)
            ->where('curriculum_user_progress.status', 'remembered')
            ->where('curriculum_terms.topic_id', $topicId)
            ->where('curriculum_terms.stage_num', $stageNum)
            ->count();

        $finalScore = max($score, $stageRememberedCount);

        // Record stage progress
        $stageProgress = CurriculumStageProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'topic_id' => $topicId,
                'stage_num' => $stageNum,
            ],
            [
                'is_completed' => true,
                'score' => $finalScore,
                'total_questions' => $totalQuestions,
                'completed_at' => now(),
            ]
        );

        // Recalculate total topic progress
        $topic = CurriculumTopic::find($topicId);
        $rememberedCount = CurriculumUserProgress::where('user_id', $user->id)
            ->where('topic_id', $topicId)
            ->where('status', 'remembered')
            ->count();

        $progressPercent = $topic->total_terms > 0 
            ? min(100, round(($rememberedCount / $topic->total_terms) * 100))
            : 0;

        return response()->json([
            'status' => 'success',
            'message' => 'Lưu kết quả giai đoạn thành công!',
            'data' => [
                'stage_num' => $stageNum,
                'score' => $finalScore,
                'total_questions' => $totalQuestions,
                'is_completed' => true,
                'topic_progress_percent' => $progressPercent,
                'remembered_terms_count' => $rememberedCount,
            ],
        ]);
    }

    /**
     * Mark an individual term as remembered / learning
     */
    public function updateTermStatus(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập'], 401);
        }

        $validated = $request->validate([
            'term_id' => 'required|exists:curriculum_terms,id',
            'status' => 'required|in:remembered,learning',
        ]);

        $term = CurriculumTerm::findOrFail($validated['term_id']);

        $prog = CurriculumUserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'term_id' => $term->id,
            ],
            [
                'topic_id' => $term->topic_id,
                'status' => $validated['status'],
                'last_reviewed_at' => now(),
            ]
        );

        // Count how many terms in this stage are now remembered
        $stageCount = self::STAGE_CONFIG[$term->stage_num]['count'] ?? 10;
        $stageRememberedCount = DB::table('curriculum_user_progress')
            ->join('curriculum_terms', 'curriculum_user_progress.term_id', '=', 'curriculum_terms.id')
            ->where('curriculum_user_progress.user_id', $user->id)
            ->where('curriculum_user_progress.status', 'remembered')
            ->where('curriculum_terms.topic_id', $term->topic_id)
            ->where('curriculum_terms.stage_num', $term->stage_num)
            ->count();

        $stageProgress = CurriculumStageProgress::firstOrNew([
            'user_id' => $user->id,
            'topic_id' => $term->topic_id,
            'stage_num' => $term->stage_num,
        ]);

        $stageProgress->total_questions = $stageCount;

        if ($validated['status'] === 'remembered') {
            $stageProgress->score = max($stageProgress->score ?? 0, $stageRememberedCount);
            if ($stageRememberedCount >= $stageCount) {
                $stageProgress->is_completed = true;
                $stageProgress->score = $stageCount;
                $stageProgress->completed_at = now();
            }
        } else {
            // When user changes to learning
            $stageProgress->score = $stageRememberedCount;
            if ($stageRememberedCount < $stageCount) {
                // If previously marked completed by flashcard, demote
                $stageProgress->is_completed = false;
            }
        }
        $stageProgress->save();

        // Compute updated progress % for this topic
        $topic = CurriculumTopic::find($term->topic_id);
        $rememberedCount = CurriculumUserProgress::where('user_id', $user->id)
            ->where('topic_id', $topic->id)
            ->where('status', 'remembered')
            ->count();

        $progressPercent = $topic->total_terms > 0 
            ? min(100, round(($rememberedCount / $topic->total_terms) * 100))
            : 0;

        return response()->json([
            'status' => 'success',
            'message' => 'Đã cập nhật trạng thái từ vựng!',
            'data' => [
                'term_id' => $term->id,
                'status' => $validated['status'],
                'topic_id' => $topic->id,
                'stage_num' => $term->stage_num,
                'stage_score' => $stageProgress->score,
                'stage_completed' => (bool)$stageProgress->is_completed,
                'stage_remembered_count' => $stageRememberedCount,
                'stage_total_count' => $stageCount,
                'topic_progress_percent' => $progressPercent,
                'remembered_terms_count' => $rememberedCount,
            ],
        ]);
    }

    // ================= ADMIN ENDPOINTS ================= //

    /**
     * Admin: List all curriculum topics with stats
     */
    public function adminGetTopics(Request $request)
    {
        $topics = CurriculumTopic::withCount('terms')->orderBy('order_num')->get();

        $data = $topics->map(function ($t) {
            return [
                'id' => $t->id,
                'order_num' => $t->order_num,
                'title' => $t->title,
                'title_vi' => $t->title_vi,
                'slug' => $t->slug,
                'icon' => $t->icon,
                'color' => $t->color,
                'description' => $t->description,
                'terms_count' => $t->terms_count,
                'total_stages' => $t->total_stages,
                'csv_filename' => $t->csv_filename,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Admin: Get terms for a topic with stage filter, search filter, paginated
     */
    public function adminGetTerms(Request $request, $topicId)
    {
        $query = CurriculumTerm::where('topic_id', $topicId);

        if ($request->filled('stage_num')) {
            $query->where('stage_num', $request->stage_num);
        }

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(function ($q) use ($kw) {
                $q->where('term', 'like', "%{$kw}%")
                  ->orWhere('definition', 'like', "%{$kw}%");
            });
        }

        $perPage = $request->input('per_page', 25);
        if ($perPage === 'all' || $perPage == 200) {
            $perPage = 250;
        }
        $terms = $query->orderBy('order_in_topic')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $terms,
        ]);
    }

    /**
     * Get all 200 terms of a topic (for "Xem toan bo 200 the" mode)
     */
    public function getAllTopicTerms(Request $request, $topicId)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;

        $topic = CurriculumTopic::findOrFail($topicId);
        $terms = CurriculumTerm::where('topic_id', $topic->id)
            ->orderBy('order_in_topic')
            ->get();

        $userTermStatuses = [];
        if ($userId) {
            $userTermStatuses = CurriculumUserProgress::where('user_id', $userId)
                ->whereIn('term_id', $terms->pluck('id'))
                ->pluck('status', 'term_id')
                ->toArray();
        }

        $formattedTerms = $terms->map(function ($t) use ($userTermStatuses) {
            $userStatus = $userTermStatuses[$t->id] ?? null;
            return [
                'id' => $t->id,
                'order_in_topic' => $t->order_in_topic,
                'stage_num' => $t->stage_num,
                'term' => $t->term,
                'definition' => $t->definition,
                'part_of_speech' => $t->part_of_speech,
                'pattern' => $t->pattern,
                'examples' => $t->examples,
                'is_remembered' => $userStatus === 'remembered',
                'is_learning' => $userStatus === 'learning',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'topic' => [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'title_vi' => $topic->title_vi,
                    'order_num' => $topic->order_num,
                    'slug' => $topic->slug,
                    'icon' => $topic->icon,
                    'color' => $topic->color,
                    'description' => $topic->description,
                    'total_terms' => $topic->total_terms,
                ],
                'terms' => $formattedTerms,
                'total_terms' => count($formattedTerms),
            ],
        ]);
    }

    /**
     * Admin: Create a curriculum term
     */
    public function adminCreateTerm(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|exists:curriculum_topics,id',
            'stage_num' => 'required|integer|min:1|max:10',
            'term' => 'required|string|max:255',
            'definition' => 'required|string',
            'part_of_speech' => 'nullable|string|max:100',
            'pattern' => 'nullable|string',
            'examples' => 'nullable|string',
        ]);

        $topic = CurriculumTopic::findOrFail($validated['topic_id']);
        $maxOrder = CurriculumTerm::where('topic_id', $topic->id)->max('order_in_topic') ?? 0;

        $newTerm = CurriculumTerm::create(array_merge($validated, [
            'order_in_topic' => $maxOrder + 1,
        ]));

        $topic->increment('total_terms');

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm từ vựng lộ trình thành công!',
            'data' => $newTerm,
        ]);
    }

    /**
     * Admin: Update term
     */
    public function adminUpdateTerm(Request $request, $id)
    {
        $term = CurriculumTerm::findOrFail($id);

        $validated = $request->validate([
            'stage_num' => 'sometimes|integer|min:1|max:10',
            'term' => 'sometimes|string|max:255',
            'definition' => 'sometimes|string',
            'part_of_speech' => 'nullable|string|max:100',
            'pattern' => 'nullable|string',
            'examples' => 'nullable|string',
        ]);

        $term->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật từ vựng thành công!',
            'data' => $term,
        ]);
    }

    /**
     * Admin: Delete term
     */
    public function adminDeleteTerm($id)
    {
        $term = CurriculumTerm::findOrFail($id);
        $topic = CurriculumTopic::find($term->topic_id);
        $term->delete();

        if ($topic && $topic->total_terms > 0) {
            $topic->decrement('total_terms');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa từ vựng thành công!',
        ]);
    }

    /**
     * Admin: Update topic information
     */
    public function adminUpdateTopic(Request $request, $id)
    {
        $topic = CurriculumTopic::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'title_vi' => 'sometimes|string|max:255',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $topic->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thông tin chủ đề thành công!',
            'data' => $topic,
        ]);
    }
}
