<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Vocabulary;
use App\Models\User;
use App\Models\UserVocabularyProgress;
use App\Models\UserGrammarProgress;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;

        $topics = Topic::withCount(['vocabularies', 'grammars'])->orderBy('order_index')->get();

        $data = $topics->map(function ($t) use ($userId) {
            $masteredVocab = 0;
            $completedGrammar = 0;

            if ($userId) {
                $vocabIds = $t->vocabularies()->pluck('id');
                $masteredVocab = UserVocabularyProgress::where('user_id', $userId)
                    ->whereIn('vocabulary_id', $vocabIds)
                    ->where('status', 'mastered')
                    ->count();

                $grammarIds = $t->grammars()->pluck('id');
                $completedGrammar = UserGrammarProgress::where('user_id', $userId)
                    ->whereIn('grammar_id', $grammarIds)
                    ->where('is_completed', true)
                    ->count();
            }

            $vocabPercent = $t->vocabularies_count > 0 ? round(($masteredVocab / $t->vocabularies_count) * 100) : 0;
            $grammarPercent = $t->grammars_count > 0 ? round(($completedGrammar / $t->grammars_count) * 100) : 0;

            return [
                'id' => $t->id,
                'name' => $t->name,
                'slug' => $t->slug,
                'icon' => $t->icon,
                'description' => $t->description,
                'vocabularies_count' => $t->vocabularies_count,
                'mastered_vocabularies_count' => $masteredVocab,
                'vocab_progress_percent' => $vocabPercent,
                'grammars_count' => $t->grammars_count,
                'completed_grammars_count' => $completedGrammar,
                'grammar_progress_percent' => $grammarPercent,
                'is_unlocked' => true,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function show(Request $request, $idOrSlug)
    {
        $user = auth('sanctum')->user();
        $userId = $user ? $user->id : null;

        $topic = Topic::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->with(['grammars.questions'])->firstOrFail();

        $vocabularies = $topic->vocabularies()->get();
        $vocabIds = $vocabularies->pluck('id');
        $progressMap = [];
        if ($userId) {
            $progressMap = UserVocabularyProgress::where('user_id', $userId)
                ->whereIn('vocabulary_id', $vocabIds)
                ->get()
                ->keyBy('vocabulary_id');
        }

        $wordsData = $vocabularies->map(function ($w) use ($progressMap) {
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
                'topic' => $topic,
                'vocabularies' => $wordsData,
                'grammars' => $topic->grammars,
            ],
        ]);
    }

    public function generateTopicQuiz(Request $request, $idOrSlug)
    {
        $topic = Topic::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->firstOrFail();
        $words = $topic->vocabularies()->take(15)->get();

        $allOtherWords = Vocabulary::whereNotIn('id', $words->pluck('id'))
            ->inRandomOrder()
            ->take(50)
            ->get();

        $questions = [];
        $shuffled = $words->shuffle();

        foreach ($shuffled as $idx => $word) {
            $isEnToVi = $idx % 2 === 0;

            if ($isEnToVi) {
                $distractors = $allOtherWords->random(min(3, $allOtherWords->count()))->pluck('meaning_vi')->toArray();
                $options = array_values(array_unique(array_merge([$word->meaning_vi], $distractors)));
                while (count($options) < 4) {
                    $options[] = 'Nghĩa khác';
                }
                shuffle($options);

                $questions[] = [
                    'id' => 'tq_' . ($idx + 1),
                    'vocabulary_id' => $word->id,
                    'type' => 'multiple_choice',
                    'title' => 'Chọn nghĩa tiếng Việt đúng',
                    'prompt' => $word->word,
                    'phonetic' => $word->phonetic_us,
                    'part_of_speech' => $word->part_of_speech,
                    'audio_text' => $word->word,
                    'options' => array_slice($options, 0, 4),
                    'correct_answer' => $word->meaning_vi,
                    'explanation' => "Từ \"{$word->word}\" ({$word->phonetic_us}) có nghĩa là: {$word->meaning_vi}.",
                    'example_en' => $word->example_en,
                    'example_vi' => $word->example_vi,
                ];
            } else {
                $distractors = $allOtherWords->random(min(3, $allOtherWords->count()))->pluck('word')->toArray();
                $options = array_values(array_unique(array_merge([$word->word], $distractors)));
                while (count($options) < 4) {
                    $options[] = 'word_' . mt_rand(1, 99);
                }
                shuffle($options);

                $questions[] = [
                    'id' => 'tq_' . ($idx + 1),
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

        return response()->json([
            'status' => 'success',
            'data' => [
                'quiz_type' => 'topic_vocab',
                'reference_info' => "Chủ đề: {$topic->name}",
                'total_questions' => count($questions),
                'questions' => $questions,
            ],
        ]);
    }
}
