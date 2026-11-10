<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizRoomParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'score',
        'correct_count',
        'total_time_ms',
        'answers_json',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'correct_count' => 'integer',
            'total_time_ms' => 'integer',
            'answers_json' => 'array',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(QuizRoom::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
