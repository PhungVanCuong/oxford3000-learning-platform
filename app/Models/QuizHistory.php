<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_type',
        'reference_info',
        'total_questions',
        'correct_answers',
        'accuracy_percent',
        'xp_earned',
        'details_json',
    ];

    protected function casts(): array
    {
        return [
            'details_json' => 'array',
            'accuracy_percent' => 'float',
            'total_questions' => 'integer',
            'correct_answers' => 'integer',
            'xp_earned' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
