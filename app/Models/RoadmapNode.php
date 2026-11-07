<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RoadmapNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'roadmap_id',
        'title',
        'description',
        'node_type',
        'order_index',
        'is_locked',
        'passing_score',
    ];

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
            'passing_score' => 'integer',
        ];
    }

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function vocabularies(): HasMany
    {
        return $this->hasMany(Vocabulary::class);
    }

    public function grammar(): HasOne
    {
        return $this->hasOne(Grammar::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
