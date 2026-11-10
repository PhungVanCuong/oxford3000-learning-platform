<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LearningTip;

class LearningTipController extends Controller
{
    public function index()
    {
        $tips = LearningTip::orderBy('order_index')->get();

        $categories = $tips->groupBy('category');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => $tips->count(),
                'categories' => $categories,
                'tips' => $tips,
            ],
        ]);
    }
}
