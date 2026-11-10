<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IpaSound;

class IpaController extends Controller
{
    public function index()
    {
        $all = IpaSound::orderBy('order_index')->get();

        $monophthongs = $all->where('category', 'monophthongs')->values();
        $diphthongs = $all->where('category', 'diphthongs')->values();
        $consonantsVoiced = $all->where('category', 'consonants')->where('voiced_type', 'voiced')->values();
        $consonantsUnvoiced = $all->where('category', 'consonants')->where('voiced_type', 'unvoiced')->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => $all->count(),
                'monophthongs' => $monophthongs,
                'diphthongs' => $diphthongs,
                'consonants_voiced' => $consonantsVoiced,
                'consonants_unvoiced' => $consonantsUnvoiced,
                'all' => $all,
            ],
        ]);
    }
}
