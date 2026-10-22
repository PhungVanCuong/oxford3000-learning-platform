<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVocabularyProgress extends Model
{
    use HasFactory;

    protected $table = 'user_vocabulary_progress';

    protected $fillable = [
        'user_id',
        'vocabulary_id',
        'status',
        'correct_count',
        'wrong_count',
        'last_reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'correct_count' => 'integer',
            'wrong_count' => 'integer',
            'last_reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vocabulary(): BelongsTo
    {
        return $this->belongsTo(Vocabulary::class);
    }
}
