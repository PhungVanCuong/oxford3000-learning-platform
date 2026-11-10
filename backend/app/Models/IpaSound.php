<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpaSound extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'category',
        'voiced_type',
        'mouth_guide_vi',
        'video_or_image_url',
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
}
