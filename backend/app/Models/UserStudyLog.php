<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStudyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'study_date',
        'minutes_spent',
        'words_learned',
        'xp_earned',
        'exercises_done',
    ];

    protected function casts(): array
    {
        return [
            'study_date' => 'date:Y-m-d',
            'minutes_spent' => 'integer',
            'words_learned' => 'integer',
            'xp_earned' => 'integer',
            'exercises_done' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
