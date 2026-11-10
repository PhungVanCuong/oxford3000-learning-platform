<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttemptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'question_type',
        'question_prompt',
        'user_answer',
        'correct_answer',
        'is_correct',
        'explanation',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }
}
