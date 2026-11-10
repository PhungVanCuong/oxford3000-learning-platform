<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vocabulary;
use App\Models\User;
use App\Models\UserVocabularyProgress;

class DailyStudyController extends Controller
{
    /**
     * Get all Days based on dynamic words_per_day setting (100% Unlocked)
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;
        $wordsPerDay = $user && $user->words_per_day ? $user->words_per_day : 15;
        if ($wordsPerDay <= 0) $wordsPerDay = 15;

        $totalWords = Vocabulary::count();
        $totalDays = (int)ceil($totalWords / $wordsPerDay);

        // Fetch user's mastered and learning vocabulary IDs if logged in
        $masteredIds = [];
        $learningIds = [];
        if ($userId) {
            $progress = UserVocabularyProgress::where('user_id', $userId)
                ->whereIn('status', ['mastered', 'learning'])
                ->get(['vocabulary_id', 'status'])
                ->groupBy('status');

            $masteredIds = isset($progress['mastered']) ? $progress['mastered']->pluck('vocabulary_id')->flip()->toArray() : [];
            $learningIds = isset($progress['learning']) ? $progress['learning']->pluck('vocabulary_id')->flip()->toArray() : [];
        }

        $days = [];
        for ($d = 1; $d <= $totalDays; $d++) {
            $startId = ($d - 1) * $wordsPerDay + 1;
            $endId = min($totalWords, $d * $wordsPerDay);
            $dayCount = ($endId - $startId) + 1;

            $masteredInDay = 0;
            $learningInDay = 0;
            if ($userId) {
                for ($id = $startId; $id <= $endId; $id++) {
                    if (isset($masteredIds[$id])) {
                        $masteredInDay++;
                    } elseif (isset($learningIds[$id])) {
                        $learningInDay++;
                    }
                }
            }

            $progressPercent = $dayCount > 0 ? round(($masteredInDay / $dayCount) * 100) : 0;

            $days[] = [
                'day' => $d,
                'total_words' => $dayCount,
                'mastered_words' => $masteredInDay,
                'learning_words' => $learningInDay,
                'progress_percent' => $progressPercent,
                'is_completed' => $progressPercent >= 80,
                'start_id' => $startId,
                'end_id' => $endId,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'words_per_day' => $wordsPerDay,
                'total_words' => $totalWords,
                'total_days' => $totalDays,
                'days' => $days,
            ],
        ]);
    }

    /**
     * Get words for a specific Day (for Flashcard learning)
     */
    public function showDay(Request $request, $day)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;
        $wordsPerDay = $user && $user->words_per_day ? $user->words_per_day : 15;
        if ($wordsPerDay <= 0) $wordsPerDay = 15;

        $offset = ($day - 1) * $wordsPerDay;
        $words = Vocabulary::skip($offset)->take($wordsPerDay)->get();

        if ($words->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy ngày học này'], 404);
        }

        $vocabIds = $words->pluck('id');
        $progressMap = [];
        if ($userId) {
            $progressMap = UserVocabularyProgress::where('user_id', $userId)
                ->whereIn('vocabulary_id', $vocabIds)
                ->get()
                ->keyBy('vocabulary_id');
        }

        $wordsData = $words->map(function ($w) use ($progressMap) {
            $prog = isset($progressMap[$w->id]) ? $progressMap[$w->id] : null;
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
                'status' => $prog ? $prog->status : 'unlearned',
                'correct_count' => $prog ? $prog->correct_count : 0,
                'wrong_count' => $prog ? $prog->wrong_count : 0,
            ];
        });

        $masteredCount = $wordsData->where('status', 'mastered')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'day' => (int)$day,
                'words_per_day' => $wordsPerDay,
                'total_words' => $words->count(),
                'mastered_words' => $masteredCount,
                'progress_percent' => $words->count() > 0 ? round(($masteredCount / $words->count()) * 100) : 0,
                'words' => $wordsData,
            ],
        ]);
    }

    /**
     * Generate the 15-question Quiz for a specific Day (100% Multiple Choice)
     */
    public function generateDayQuiz(Request $request, $day)
    {
        $user = auth('sanctum')->user();
        $wordsPerDay = $user && $user->words_per_day ? $user->words_per_day : 15;
        if ($wordsPerDay <= 0) $wordsPerDay = 15;

        $offset = ($day - 1) * $wordsPerDay;
        $words = Vocabulary::skip($offset)->take($wordsPerDay)->get();

        if ($words->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy từ vựng của ngày này'], 404);
        }

        $allOtherWords = Vocabulary::whereNotIn('id', $words->pluck('id'))
            ->inRandomOrder()
            ->take(60)
            ->get();

        $questions = $this->buildQuizQuestions($words, $allOtherWords);

        return response()->json([
            'status' => 'success',
            'data' => [
                'quiz_type' => 'daily_vocab',
                'reference_info' => "Ngày {$day}",
                'total_questions' => count($questions),
                'passing_score' => 80,
                'questions' => $questions,
            ],
        ]);
    }

    /**
     * Multi-Day Custom Review (100% Multiple Choice)
     */
    public function customMultiDay(Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;
        $wordsPerDay = $user && $user->words_per_day ? $user->words_per_day : 15;
        if ($wordsPerDay <= 0) $wordsPerDay = 15;

        $daysParam = $request->input('days', '');
        if (is_string($daysParam)) {
            $days = array_filter(array_map('intval', explode(',', $daysParam)));
        } else {
            $days = (array)$daysParam;
        }

        if (empty($days)) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng chọn ít nhất 1 ngày'], 400);
        }

        // Collect words belonging to the selected days
        $allSelectedWords = collect();
        foreach ($days as $d) {
            $offset = ($d - 1) * $wordsPerDay;
            $dayWords = Vocabulary::skip($offset)->take($wordsPerDay)->get();
            $allSelectedWords = $allSelectedWords->concat($dayWords);
        }

        $mode = $request->input('mode', 'flashcard');

        if ($mode === 'quiz') {
            $allOtherWords = Vocabulary::whereNotIn('id', $allSelectedWords->pluck('id'))
                ->inRandomOrder()
                ->take(60)
                ->get();

            $sampleWords = $allSelectedWords->shuffle()->take(15);
            $questions = $this->buildQuizQuestions($sampleWords, $allOtherWords);

            $dayLabels = implode(', ', $days);
            return response()->json([
                'status' => 'success',
                'data' => [
                    'quiz_type' => 'multi_day_review',
                    'reference_info' => "Các ngày: {$dayLabels}",
                    'total_questions' => count($questions),
                    'questions' => $questions,
                ],
            ]);
        }

        // Return for Flashcard
        $vocabIds = $allSelectedWords->pluck('id');
        $progressMap = [];
        if ($userId) {
            $progressMap = UserVocabularyProgress::where('user_id', $userId)
                ->whereIn('vocabulary_id', $vocabIds)
                ->get()
                ->keyBy('vocabulary_id');
        }

        $wordsData = $allSelectedWords->map(function ($w) use ($progressMap) {
            $prog = isset($progressMap[$w->id]) ? $progressMap[$w->id] : null;
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
                'status' => $prog ? $prog->status : 'unlearned',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'selected_days' => $days,
                'total_words' => $wordsData->count(),
                'words' => $wordsData,
            ],
        ]);
    }

    /**
     * Helper: 100% Multiple Choice Questions (No Typing / Fill-in-the-blank)
     */
    private function buildQuizQuestions($words, $distractorPool)
    {
        $shuffled = $words->shuffle();
        $questions = [];

        foreach ($shuffled as $idx => $word) {
            // Half English to Vietnamese, Half Vietnamese to English
            $isEnToVi = $idx % 2 === 0;

            if ($isEnToVi) {
                // Prompt: English word -> Pick Vietnamese meaning
                $distractors = $distractorPool->random(min(3, $distractorPool->count()))->pluck('meaning_vi')->toArray();
                $options = array_values(array_unique(array_merge([$word->meaning_vi], $distractors)));
                while (count($options) < 4) {
                    $options[] = 'Nghĩa khác của từ vựng';
                }
                shuffle($options);

                $questions[] = [
                    'id' => 'q_' . ($idx + 1),
                    'vocabulary_id' => $word->id,
                    'type' => 'multiple_choice',
                    'title' => 'Chọn nghĩa tiếng Việt chính xác',
                    'prompt' => $word->word,
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'audio_text' => $word->word,
                    'options' => array_slice($options, 0, 4),
                    'correct_answer' => $word->meaning_vi,
                    'explanation' => "Từ \"{$word->word}\" ({$word->phonetic_us}) có nghĩa tiếng Việt là: {$word->meaning_vi}.",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            } else {
                // Prompt: Vietnamese meaning -> Pick English word
                $distractors = $distractorPool->random(min(3, $distractorPool->count()))->pluck('word')->toArray();
                $options = array_values(array_unique(array_merge([$word->word], $distractors)));
                while (count($options) < 4) {
                    $options[] = 'word_' . mt_rand(1, 99);
                }
                shuffle($options);

                $questions[] = [
                    'id' => 'q_' . ($idx + 1),
                    'vocabulary_id' => $word->id,
                    'type' => 'multiple_choice',
                    'title' => 'Chọn từ tiếng Anh phù hợp với nghĩa',
                    'prompt' => $word->meaning_vi,
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'audio_text' => $word->word,
                    'options' => array_slice($options, 0, 4),
                    'correct_answer' => $word->word,
                    'explanation' => "Nghĩa \"{$word->meaning_vi}\" tương ứng với từ tiếng Anh: \"{$word->word}\" ({$word->phonetic_us}).",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            }
        }

        return $questions;
    }
}
