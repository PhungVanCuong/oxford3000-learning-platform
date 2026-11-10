<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiApiService;

class AiController extends Controller
{
    /**
     * Endpoint chat trực tiếp với Gemini AI dành cho học viên
     * POST /api/ai/chat
     */
    public function chat(Request $request, GeminiApiService $gemini)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'nullable|array|max:20',
        ]);

        $message = trim($validated['message']);
        $history = $validated['history'] ?? [];

        $result = $gemini->chatAssistant($message, $history);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    /**
     * Endpoint giải thích từ vựng hoặc đoạn văn cho học viên
     * POST /api/ai/explain
     */
    public function explain(Request $request, GeminiApiService $gemini)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $result = $gemini->explainOrCorrect($validated['text']);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
