<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vocabulary extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'word',
        'phonetic_us',
        'part_of_speech',
        'meaning_vi',
        'example_en',
        'example_vi',
        'audio_url',
        'cefr_level',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserVocabularyProgress::class);
    }
}
