<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurriculumStageProgress extends Model
{
    use HasFactory;

    protected $table = 'curriculum_stage_progress';

    protected $fillable = [
        'user_id',
        'topic_id',
        'stage_num',
        'is_completed',
        'score',
        'total_questions',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(CurriculumTopic::class, 'topic_id');
    }
}
