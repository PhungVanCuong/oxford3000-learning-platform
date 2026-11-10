<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGrammarProgress extends Model
{
    use HasFactory;

    protected $table = 'user_grammar_progress';

    protected $fillable = [
        'user_id',
        'grammar_id',
        'is_completed',
        'best_score',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'best_score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grammar(): BelongsTo
    {
        return $this->belongsTo(Grammar::class);
    }
}
