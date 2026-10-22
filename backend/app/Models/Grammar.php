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
        'level',
        'theory_html',
        'examples_json',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'examples_json' => 'array',
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
