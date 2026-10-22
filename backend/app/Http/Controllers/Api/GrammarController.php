<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grammar;
use App\Models\User;
use App\Models\UserGrammarProgress;

class GrammarController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user() ?? User::where('email', 'cuongphung1810@gmail.com')->first() ?? User::first();
        $userId = $user ? $user->id : 1;

        $grammars = Grammar::withCount('questions')->with('topic')->orderBy('order_index')->get();

        $userProgress = UserGrammarProgress::where('user_id', $userId)->get()->keyBy('grammar_id');

        $data = $grammars->map(function ($g) use ($userProgress) {
            $prog = $userProgress->get($g->id);
            return [
                'id' => $g->id,
                'title' => $g->title,
                'level' => $g->level,
                'topic_name' => $g->topic ? $g->topic->name : 'Tổng quát',
                'questions_count' => $g->questions_count,
                'is_completed' => $prog ? (bool)$prog->is_completed : false,
                'best_score' => $prog ? $prog->best_score : 0,
                'order_index' => $g->order_index,
                'is_unlocked' => true, // 100% unlocked policy
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $grammar = Grammar::with(['topic', 'questions'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $grammar->id,
                'title' => $grammar->title,
                'level' => $grammar->level,
                'topic_name' => $grammar->topic ? $grammar->topic->name : 'Tổng quát',
                'theory_html' => $grammar->theory_html,
                'examples' => $grammar->examples_json,
                'questions_count' => $grammar->questions->count(),
            ],
        ]);
    }

    public function getQuestions($id)
    {
        $grammar = Grammar::with('questions')->findOrFail($id);

        $questions = $grammar->questions->map(function ($q, $idx) {
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
}
