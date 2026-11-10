<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Roadmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'level',
        'order_index',
    ];

    public function nodes(): HasMany
    {
        return $this->hasMany(RoadmapNode::class)->orderBy('order_index');
    }
}
