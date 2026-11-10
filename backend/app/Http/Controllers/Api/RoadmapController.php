<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roadmap;
use App\Models\RoadmapNode;
use App\Models\User;
use App\Models\QuizAttempt;
use App\Models\UserVocabularyProgress;

class RoadmapController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user() ?? User::first();
        $userId = $user ? $user->id : 1;

        $roadmaps = Roadmap::with(['nodes' => function ($q) {
            $q->orderBy('order_index', 'asc')->with(['grammar', 'vocabularies']);
        }])->orderBy('order_index', 'asc')->get();

        $data = $roadmaps->map(function ($roadmap) use ($userId) {
            $nodesData = $roadmap->nodes->map(function ($node) use ($userId) {
                // Get user's best quiz attempt
                $bestAttempt = QuizAttempt::where('user_id', $userId)
                    ->where('roadmap_node_id', $node->id)
                    ->orderBy('percentage', 'desc')
                    ->first();

                $wordCount = $node->vocabularies->count();
                $grammarTitle = $node->grammar ? $node->grammar->title : null;
                $grammarQuestionsCount = $node->grammar ? $node->grammar->questions()->count() : 0;

                // For vocab nodes: calculate mastered words
                $masteredCount = 0;
                if ($node->node_type === 'vocabulary' && $wordCount > 0) {
                    $vocabIds = $node->vocabularies->pluck('id');
                    $masteredCount = UserVocabularyProgress::where('user_id', $userId)
                        ->whereIn('vocabulary_id', $vocabIds)
                        ->where('status', 'mastered')
                        ->count();
                }

                return [
                    'id' => $node->id,
                    'title' => $node->title,
                    'description' => $node->description,
                    'node_type' => $node->node_type,
                    'order_index' => $node->order_index,
                    'is_locked' => (bool)$node->is_locked,
                    'passing_score' => $node->passing_score,
                    'word_count' => $wordCount,
                    'mastered_count' => $masteredCount,
                    'grammar_title' => $grammarTitle,
                    'grammar_questions_count' => $grammarQuestionsCount,
                    'best_attempt' => $bestAttempt ? [
                        'score' => $bestAttempt->score,
                        'total_questions' => $bestAttempt->total_questions,
                        'percentage' => $bestAttempt->percentage,
                        'is_passed' => (bool)$bestAttempt->is_passed,
                    ] : null,
                ];
            });

            return [
                'id' => $roadmap->id,
                'title' => $roadmap->title,
                'description' => $roadmap->description,
                'level' => $roadmap->level,
                'nodes' => $nodesData,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $node = RoadmapNode::with(['roadmap', 'grammar.questions', 'vocabularies'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $node,
        ]);
    }
}
