<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order_index',
    ];

    public function vocabularies(): HasMany
    {
        return $this->hasMany(Vocabulary::class)->orderBy('id');
    }

    public function grammars(): HasMany
    {
        return $this->hasMany(Grammar::class)->orderBy('order_index');
    }
}
