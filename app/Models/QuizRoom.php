<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_code',
        'title',
        'host_user_id',
        'status',
        'category',
        'password',
        'max_players',
        'time_per_question',
        'xp_per_question',
        'questions_data',
        'expires_at',
        'current_question_index',
    ];

    protected function casts(): array
    {
        return [
            'questions_data' => 'array',
            'max_players' => 'integer',
            'time_per_question' => 'integer',
            'xp_per_question' => 'integer',
            'expires_at' => 'datetime',
            'current_question_index' => 'integer',
        ];
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_user_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(QuizRoomParticipant::class, 'room_id')->orderByDesc('score')->orderBy('total_time_ms');
    }
}
