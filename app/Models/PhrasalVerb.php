<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhrasalVerb extends Model
{
    use HasFactory;

    protected $fillable = [
        'phrase',
        'type',
        'category',
        'part_number',
        'meaning_vi',
        'example_en',
        'example_vi',
        'component_words_json',
        'cefr_level',
    ];

    protected $casts = [
        'component_words_json' => 'array',
    ];

    public function userProgress()
    {
        return $this->hasMany(UserPhraseProgress::class, 'phrase_id');
    }
}
