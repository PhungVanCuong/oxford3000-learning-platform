<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurriculumTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'order_in_topic',
        'stage_num',
        'term',
        'definition',
        'part_of_speech',
        'pattern',
        'examples',
        'audio_url',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(CurriculumTopic::class, 'topic_id');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(CurriculumUserProgress::class, 'term_id');
    }
}
