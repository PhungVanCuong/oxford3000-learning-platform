<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grammar;
use App\Models\RoadmapNode;

class GrammarLearningController extends Controller
{
    /**
     * Get Grammar theory for Phase A (Knowledge Building)
     */
    public function show($nodeId)
    {
        // Find by roadmap_node_id or directly by id
        $grammar = Grammar::where('roadmap_node_id', $nodeId)->first();
        if (!$grammar) {
            $grammar = Grammar::find($nodeId);
        }

        if (!$grammar) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy bài học ngữ pháp cho chặng này.',
            ], 404);
        }

        $node = $grammar->node;

        return response()->json([
            'status' => 'success',
            'data' => [
                'grammar' => [
                    'id' => $grammar->id,
                    'roadmap_node_id' => $grammar->roadmap_node_id,
                    'title' => $grammar->title,
                    'slug' => $grammar->slug,
                    'level' => $grammar->level,
                    'summary' => $grammar->summary,
                    'theory_content' => $grammar->theory_content,
                    'examples' => $grammar->examples_json,
                ],
                'node' => $node ? [
                    'id' => $node->id,
                    'title' => $node->title,
                    'description' => $node->description,
                    'passing_score' => $node->passing_score,
                ] : null,
            ],
        ]);
    }

    /**
     * Get Grammar Practice Questions for Phase B (Testing)
     */
    public function getQuestions($nodeId)
    {
        $grammar = Grammar::where('roadmap_node_id', $nodeId)->first();
        if (!$grammar) {
            $grammar = Grammar::find($nodeId);
        }

        if (!$grammar) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy bài tập ngữ pháp cho chặng này.',
            ], 404);
        }

        $questions = $grammar->questions()->orderBy('order_index')->get()->map(function ($q) {
            return [
                'id' => $q->id,
                'grammar_id' => $q->grammar_id,
                'question_type' => $q->question_type,
                'sub_type' => $q->question_type === 'multiple_choice' ? 'grammar_mc' : 'grammar_fill',
                'title' => $q->question_type === 'multiple_choice' ? 'Trắc nghiệm ngữ pháp' : 'Điền dạng đúng của từ',
                'prompt' => $q->question_text,
                'options' => $q->options_json,
                'correct_answer' => $q->correct_answer,
                'explanation' => $q->explanation,
                'audio_text' => str_replace('___', $q->correct_answer, $q->question_text),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'grammar_id' => $grammar->id,
                'title' => $grammar->title,
                'passing_score' => $grammar->node ? $grammar->node->passing_score : 80,
                'total_questions' => $questions->count(),
                'questions' => $questions,
            ],
        ]);
    }
}
