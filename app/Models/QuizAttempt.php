<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roadmap_node_id',
        'quiz_type',
        'score',
        'total_questions',
        'percentage',
        'time_spent_seconds',
        'is_passed',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'float',
            'is_passed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(RoadmapNode::class, 'roadmap_node_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(QuizAttemptDetail::class);
    }
}
