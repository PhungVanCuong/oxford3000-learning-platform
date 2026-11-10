<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grammar extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'title',
        'slug',
        'level',
        'summary',
        'theory_html',
        'active_passive_html',
        'pronunciation_notes',
        'examples_json',
        'exercises_json',
        'order_index',
        'badge_color',
        'icon',
    ];

    protected function casts(): array
    {
        return [
            'examples_json' => 'array',
            'exercises_json' => 'array',
            'order_index' => 'integer',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(GrammarQuestion::class)->orderBy('order_index');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserGrammarProgress::class);
    }
}
