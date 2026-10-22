<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurriculumTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_num',
        'title',
        'title_vi',
        'slug',
        'icon',
        'color',
        'description',
        'csv_filename',
        'total_terms',
        'total_stages',
    ];

    public function terms(): HasMany
    {
        return $this->hasMany(CurriculumTerm::class, 'topic_id')->orderBy('order_in_topic');
    }

    public function stageProgress(): HasMany
    {
        return $this->hasMany(CurriculumStageProgress::class, 'topic_id');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(CurriculumUserProgress::class, 'topic_id');
    }
}
