<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_url',
        'birth_date',
        'xp_points',
        'streak_days',
        'last_study_date',
        'words_per_day',
        'theme_mode',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'last_study_date' => 'date',
            'words_per_day' => 'integer',
            'xp_points' => 'integer',
            'streak_days' => 'integer',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->email === 'cuongphung1810@gmail.com';
    }

    public function vocabularyProgress(): HasMany
    {
        return $this->hasMany(UserVocabularyProgress::class);
    }

    public function grammarProgress(): HasMany
    {
        return $this->hasMany(UserGrammarProgress::class);
    }

    public function quizHistories(): HasMany
    {
        return $this->hasMany(QuizHistory::class)->orderBy('created_at', 'desc');
    }
}
