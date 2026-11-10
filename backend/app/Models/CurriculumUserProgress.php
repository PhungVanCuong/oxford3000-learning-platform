<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurriculumUserProgress extends Model
{
    use HasFactory;

    protected $table = 'curriculum_user_progress';

    protected $fillable = [
        'user_id',
        'topic_id',
        'term_id',
        'status',
        'last_reviewed_at',
    ];

    protected $casts = [
        'last_reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(CurriculumTopic::class, 'topic_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(CurriculumTerm::class, 'term_id');
    }
}
