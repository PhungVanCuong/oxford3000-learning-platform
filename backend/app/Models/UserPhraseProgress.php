<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhraseProgress extends Model
{
    use HasFactory;

    protected $table = 'user_phrase_progress';

    protected $fillable = [
        'user_id',
        'phrase_id',
        'status',
        'correct_count',
        'last_reviewed_at',
    ];

    protected $casts = [
        'last_reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phrase()
    {
        return $this->belongsTo(PhrasalVerb::class, 'phrase_id');
    }
}
