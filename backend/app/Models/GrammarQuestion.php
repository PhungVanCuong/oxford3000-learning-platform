<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrammarQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'grammar_id',
        'type',
        'question',
        'options_json',
        'correct_answer',
        'explanation',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'options_json' => 'array',
            'order_index' => 'integer',
        ];
    }

    public function grammar(): BelongsTo
    {
        return $this->belongsTo(Grammar::class);
    }
}
