<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vocabulary;
use App\Models\RoadmapNode;
use App\Models\User;
use App\Models\UserVocabularyProgress;
use Illuminate\Support\Str;

class VocabularyLearningController extends Controller
{
    /**
     * Get words for Phase A (Flashcards 3D / Knowledge Building)
     */
    public function getWordsByNode(Request $request, $nodeId)
    {
        $user = $request->user() ?? User::first();
        $userId = $user ? $user->id : 1;

        $node = RoadmapNode::findOrFail($nodeId);
        $words = Vocabulary::where('roadmap_node_id', $nodeId)->get();

        // If node has no assigned words, grab first 20 words for demonstration
        if ($words->isEmpty()) {
            $words = Vocabulary::take(20)->get();
        }

        $vocabIds = $words->pluck('id');
        $progresses = UserVocabularyProgress::where('user_id', $userId)
            ->whereIn('vocabulary_id', $vocabIds)
            ->get()
            ->keyBy('vocabulary_id');

        $result = $words->map(function ($w) use ($progresses) {
            $prog = $progresses->get($w->id);
            return [
                'id' => $w->id,
                'word' => $w->word,
                'phonetic_us' => $w->phonetic_us,
                'part_of_speech' => $w->part_of_speech,
                'meaning_vi' => $w->meaning_vi,
                'audio_url' => $w->audio_url,
                'example_en' => $w->example_en,
                'example_vi' => $w->example_vi,
                'cefr_level' => $w->cefr_level,
                'status' => $prog ? $prog->status : 'new',
                'correct_streak' => $prog ? $prog->correct_streak : 0,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'node' => [
                    'id' => $node->id,
                    'title' => $node->title,
                    'description' => $node->description,
                    'passing_score' => $node->passing_score,
                ],
                'words' => $result,
            ],
        ]);
    }

    /**
     * Search dictionary across all 3000 words
     */
    public function search(Request $request)
    {
        $q = $request->query('q', '');
        $page = $request->query('page', 1);

        $query = Vocabulary::query();
        if ($q) {
            $query->where('word', 'like', "%{$q}%")
                  ->orWhere('meaning_vi', 'like', "%{$q}%");
        }

        $results = $query->paginate(24);

        return response()->json([
            'status' => 'success',
            'data' => $results,
        ]);
    }

    /**
     * Automatic Quiz Generator Engine for Phase B (Testing & Practice)
     * 50% Multiple Choice (with distractors from database) + 50% Typing / Fill-in-the-blank
     */
    public function generateQuiz(Request $request, $nodeId)
    {
        $node = RoadmapNode::findOrFail($nodeId);
        $words = Vocabulary::where('roadmap_node_id', $nodeId)->get();

        if ($words->isEmpty()) {
            $words = Vocabulary::take(20)->get();
        }

        $allOtherWords = Vocabulary::whereNotIn('id', $words->pluck('id'))
            ->inRandomOrder()
            ->take(100)
            ->get();

        $shuffledWords = $words->shuffle();
        $totalQuestions = min(10, $words->count());
        $mcCount = (int)ceil($totalQuestions / 2);
        $typingCount = $totalQuestions - $mcCount;

        $questions = [];

        // 1. Generate Multiple Choice Questions (50%)
        for ($i = 0; $i < $mcCount; $i++) {
            $word = $shuffledWords[$i];
            $typeVariant = $i % 3;

            // Distractor meanings
            $distractorMeanings = $allOtherWords->random(3)->pluck('meaning_vi')->toArray();
            $mcOptions = array_merge([$word->meaning_vi], $distractorMeanings);
            shuffle($mcOptions);

            // Distractor words
            $distractorWords = $allOtherWords->random(3)->pluck('word')->toArray();
            $wordOptions = array_merge([$word->word], $distractorWords);
            shuffle($wordOptions);

            if ($typeVariant === 0) {
                // Form 1: Given English Word -> Pick correct Vietnamese Meaning
                $questions[] = [
                    'id' => 'q_' . ($i + 1),
                    'vocabulary_id' => $word->id,
                    'question_type' => 'multiple_choice',
                    'sub_type' => 'word_to_meaning',
                    'title' => 'Chọn nghĩa tiếng Việt đúng',
                    'prompt' => $word->word,
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'audio_text' => $word->word,
                    'options' => $mcOptions,
                    'correct_answer' => $word->meaning_vi,
                    'explanation' => "Từ \"{$word->word}\" ({$word->phonetic_us}) có nghĩa là: {$word->meaning_vi}.",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            } elseif ($typeVariant === 1) {
                // Form 2: Audio Only -> Pick English Word
                $questions[] = [
                    'id' => 'q_' . ($i + 1),
                    'vocabulary_id' => $word->id,
                    'question_type' => 'multiple_choice',
                    'sub_type' => 'listen_to_word',
                    'title' => 'Nghe phát âm và chọn từ tiếng Anh đúng',
                    'prompt' => 'Bấm loa để nghe phát âm và chọn từ đúng:',
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'audio_text' => $word->word,
                    'options' => $wordOptions,
                    'correct_answer' => $word->word,
                    'explanation' => "Phát âm là của từ: \"{$word->word}\" ({$word->meaning_vi}).",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            } else {
                // Form 3: Example Sentence Blank -> Pick English Word
                $maskedSentence = $this->maskWordInSentence($word->example_en, $word->word);
                $questions[] = [
                    'id' => 'q_' . ($i + 1),
                    'vocabulary_id' => $word->id,
                    'question_type' => 'multiple_choice',
                    'sub_type' => 'sentence_completion',
                    'title' => 'Chọn từ thích hợp điền vào câu',
                    'prompt' => $maskedSentence,
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'audio_text' => $word->example_en,
                    'options' => $wordOptions,
                    'correct_answer' => $word->word,
                    'explanation' => "Câu hoàn chỉnh: \"{$word->example_en}\"\nDịch nghĩa: {$word->example_vi}",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            }
        }

        // 2. Generate Fill-in-the-blank / Typing Questions (50%)
        for ($j = 0; $j < $typingCount; $j++) {
            $wordIdx = $mcCount + $j;
            $word = $shuffledWords[$wordIdx % $shuffledWords->count()];
            $typeVariant = $j % 3;

            $hintLetter = strtoupper(substr($word->word, 0, 1));

            if ($typeVariant === 0) {
                // Type Form 1: Meaning + POS -> Type English word
                $questions[] = [
                    'id' => 'q_' . ($mcCount + $j + 1),
                    'vocabulary_id' => $word->id,
                    'question_type' => 'fill_in_blank',
                    'sub_type' => 'type_word',
                    'title' => 'Gõ từ tiếng Anh theo nghĩa',
                    'prompt' => $word->meaning_vi,
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'hint' => "Bắt đầu bằng chữ cái [ {$hintLetter} ] - {$word->part_of_speech}",
                    'first_letter' => $hintLetter,
                    'audio_text' => $word->word,
                    'correct_answer' => strtolower($word->word),
                    'explanation' => "Đáp án đúng là: \"{$word->word}\" ({$word->phonetic_us}) - {$word->meaning_vi}.",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            } elseif ($typeVariant === 1) {
                // Type Form 2: Dictation (Listen & Type)
                $questions[] = [
                    'id' => 'q_' . ($mcCount + $j + 1),
                    'vocabulary_id' => $word->id,
                    'question_type' => 'fill_in_blank',
                    'sub_type' => 'dictation',
                    'title' => 'Nghe chính tả và gõ lại từ',
                    'prompt' => 'Nghe phát âm từ vựng và gõ chính xác từ tiếng Anh:',
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'hint' => "Gợi ý chữ cái đầu: [ {$hintLetter} ] ({$word->meaning_vi})",
                    'first_letter' => $hintLetter,
                    'audio_text' => $word->word,
                    'correct_answer' => strtolower($word->word),
                    'explanation' => "Từ được phát âm là \"{$word->word}\" ({$word->meaning_vi}).",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            } else {
                // Type Form 3: Missing word in example sentence
                $maskedSentence = $this->maskWordInSentence($word->example_en, $word->word);
                $questions[] = [
                    'id' => 'q_' . ($mcCount + $j + 1),
                    'vocabulary_id' => $word->id,
                    'question_type' => 'fill_in_blank',
                    'sub_type' => 'sentence_blank',
                    'title' => 'Điền từ còn thiếu vào câu ví dụ',
                    'prompt' => $maskedSentence,
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'hint' => "Dịch nghĩa: {$word->example_vi} (Bắt đầu bằng '{$hintLetter}')",
                    'first_letter' => $hintLetter,
                    'audio_text' => $word->example_en,
                    'correct_answer' => strtolower($word->word),
                    'explanation' => "Câu đầy đủ: \"{$word->example_en}\"\nÝ nghĩa: {$word->example_vi}",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            }
        }

        // Shuffle questions order so MC and Typing are evenly distributed
        shuffle($questions);

        return response()->json([
            'status' => 'success',
            'data' => [
                'node_id' => $node->id,
                'node_title' => $node->title,
                'passing_score' => $node->passing_score,
                'total_questions' => count($questions),
                'questions' => $questions,
            ],
        ]);
    }

    private function maskWordInSentence($sentence, $word)
    {
        if (!$sentence) {
            return "Please type: [ _____ ]";
        }

        // Case-insensitive replacement of word
        $cleanWord = preg_quote(trim($word), '/');
        $masked = preg_replace("/\b{$cleanWord}\b/i", " [ _____ ] ", $sentence);

        // Fallback if not replaced by regex
        if ($masked === $sentence) {
            $masked = str_ireplace($word, " [ _____ ] ", $sentence);
        }

        return $masked;
    }
}
