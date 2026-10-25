<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grammar;
use App\Models\User;
use App\Models\UserGrammarProgress;
use App\Models\UserStudyLog;
use App\Services\GeminiApiService;

class GrammarController extends Controller
{
    private function resolveCurrentUser(Request $request): ?User
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
        return $user;
    }

    public function index(Request $request)
    {
        $user = $this->resolveCurrentUser($request) ?? User::first();
        $userId = $user ? $user->id : 1;

        $grammars = Grammar::withCount('questions')->with('topic')->orderBy('order_index')->get();
        $userProgress = UserGrammarProgress::where('user_id', $userId)->get()->keyBy('grammar_id');

        $data = $grammars->map(function ($g) use ($userProgress) {
            $prog = $userProgress->get($g->id);
            $exercises = $g->exercises_json ?? [];
            $transCount = isset($exercises['translations']) ? count($exercises['translations']) : 0;
            $passiveCount = isset($exercises['passive_conversions']) ? count($exercises['passive_conversions']) : 0;
            $wordsCount = isset($exercises['practice_30_words']) ? count($exercises['practice_30_words']) : 0;
            $totalExercises = $g->questions_count + $transCount + $passiveCount + $wordsCount;

            return [
                'id' => $g->id,
                'title' => $g->title,
                'slug' => $g->slug,
                'level' => $g->level,
                'summary' => $g->summary,
                'badge_color' => $g->badge_color ?? '#00b074',
                'icon' => $g->icon ?? 'bi-journal-bookmark-fill',
                'topic_name' => $g->topic ? $g->topic->name : '12 Thì Tiếng Anh',
                'questions_count' => $g->questions_count,
                'total_exercises' => $totalExercises,
                'is_completed' => $prog ? (bool)$prog->is_completed : false,
                'best_score' => $prog ? $prog->best_score : 0,
                'order_index' => $g->order_index,
                'is_unlocked' => true,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $this->resolveCurrentUser($request) ?? User::first();
        $userId = $user ? $user->id : 1;

        $grammar = Grammar::with(['topic', 'questions'])->findOrFail($id);
        $prog = UserGrammarProgress::where('user_id', $userId)->where('grammar_id', $grammar->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $grammar->id,
                'title' => $grammar->title,
                'slug' => $grammar->slug,
                'level' => $grammar->level,
                'summary' => $grammar->summary,
                'badge_color' => $grammar->badge_color ?? '#00b074',
                'icon' => $grammar->icon ?? 'bi-journal-bookmark-fill',
                'topic_name' => $grammar->topic ? $grammar->topic->name : '12 Thì Tiếng Anh',
                'theory_html' => $grammar->theory_html,
                'active_passive_html' => $grammar->active_passive_html,
                'pronunciation_notes' => $grammar->pronunciation_notes,
                'examples' => $grammar->examples_json ?? [],
                'exercises' => $grammar->exercises_json ?? [],
                'exercises_json' => $grammar->exercises_json ?? [],
                'formula_positive' => $grammar->formula_positive,
                'formula_negative' => $grammar->formula_negative,
                'formula_question' => $grammar->formula_question,
                'questions_count' => $grammar->questions->count(),
                'is_completed' => $prog ? (bool)$prog->is_completed : false,
                'best_score' => $prog ? $prog->best_score : 0,
            ],
        ]);
    }

    public function getQuestions($id)
    {
        $grammar = Grammar::with('questions')->findOrFail($id);

        $questions = $grammar->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'type' => $q->type,
                'title' => $q->type === 'multiple_choice' ? 'Trắc nghiệm ngữ pháp' : 'Điền dạng đúng của từ',
                'prompt' => $q->question,
                'options' => $q->options_json,
                'correct_answer' => $q->correct_answer,
                'explanation' => $q->explanation,
                'audio_text' => str_replace('___', $q->correct_answer, $q->question),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'quiz_type' => 'grammar',
                'reference_info' => $grammar->title,
                'total_questions' => $questions->count(),
                'questions' => $questions,
            ],
        ]);
    }

    /**
     * Check translation via AI and Fuzzy matching (Pass if >= 80% accuracy)
     */
    public function checkTranslation(Request $request, GeminiApiService $gemini)
    {
        $request->validate([
            'grammar_id' => 'required|integer',
            'vi_prompt' => 'required|string',
            'user_answer' => 'required|string',
            'expected_answer' => 'required|string',
        ]);

        $grammar = Grammar::findOrFail($request->grammar_id);
        $result = $gemini->checkTranslation(
            $request->vi_prompt,
            $request->user_answer,
            $request->expected_answer,
            $grammar->title
        );

        $user = $this->resolveCurrentUser($request);
        $awardedXp = 0;

        if ($user && !empty($result['is_passed'])) {
            $awardedXp = 15;
            $user->increment('xp_points', $awardedXp);
            if ($user->streak_days == 0) {
                $user->streak_days = 1;
            }
            $user->last_study_date = now()->toDateString();
            $user->save();

            // Progress record
            $prog = UserGrammarProgress::firstOrCreate(
                ['user_id' => $user->id, 'grammar_id' => $grammar->id],
                ['is_completed' => false, 'best_score' => 0]
            );
            $prog->best_score = max($prog->best_score, $result['score']);
            if ($result['score'] >= 80) {
                $prog->is_completed = true;
            }
            $prog->save();

            // Study log for today
            $studyLog = UserStudyLog::firstOrCreate(
                ['user_id' => $user->id, 'study_date' => now()->toDateString()],
                ['minutes_spent' => 0, 'words_learned' => 0, 'xp_earned' => 0, 'exercises_done' => 0]
            );
            $studyLog->increment('xp_earned', $awardedXp);
            $studyLog->increment('exercises_done', 1);
            $studyLog->increment('minutes_spent', 1);
        }

        $score = $result['score'] ?? 0;
        $isPassed = !empty($result['is_passed']) || $score >= 80;

        $payload = array_merge($result, [
            'passed' => $isPassed,
            'is_passed' => $isPassed,
            'similarity_score' => $score,
            'score' => $score,
            'awarded_xp' => $awardedXp,
            'user_total_xp' => $user ? $user->xp_points : 0,
            'user_xp' => $user ? $user->xp_points : 0,
            'ai_result' => [
                'score' => $score,
                'is_passed' => $isPassed,
                'passed' => $isPassed,
                'corrected_answer' => $result['corrected'] ?? $request->expected_answer,
                'simple_explanation' => $result['explanation'] ?? '',
                'tips' => $result['tips'] ?? '',
            ],
        ]);

        return response()->json(array_merge([
            'status' => 'success',
            'data' => $payload,
        ], $payload));
    }

    /**
     * Complete grammar quiz
     */
    public function submitQuiz(Request $request, $id)
    {
        $request->validate([
            'score_percent' => 'required|numeric|min:0|max:100',
        ]);

        $grammar = Grammar::findOrFail($id);
        $user = $this->resolveCurrentUser($request);

        if ($user) {
            $score = round($request->score_percent);
            $prog = UserGrammarProgress::firstOrCreate(
                ['user_id' => $user->id, 'grammar_id' => $grammar->id],
                ['is_completed' => false, 'best_score' => 0]
            );
            $prog->best_score = max($prog->best_score, $score);
            if ($score >= 80) {
                $prog->is_completed = true;
            }
            $prog->save();

            $earnedXp = ($score >= 80) ? 30 : 10;
            $user->increment('xp_points', $earnedXp);
            if ($user->streak_days == 0) {
                $user->streak_days = 1;
            }
            $user->last_study_date = now()->toDateString();
            $user->save();

            $studyLog = UserStudyLog::firstOrCreate(
                ['user_id' => $user->id, 'study_date' => now()->toDateString()],
                ['minutes_spent' => 0, 'words_learned' => 0, 'xp_earned' => 0, 'exercises_done' => 0]
            );
            $studyLog->increment('xp_earned', $earnedXp);
            $studyLog->increment('exercises_done', 1);
            $studyLog->increment('minutes_spent', 3);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lưu tiến độ bài học ngữ pháp thành công!',
        ]);
    }
}
