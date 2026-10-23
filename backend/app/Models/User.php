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
        'permissions_json',
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

    protected $appends = [
        'permissions',
    ];

    public static array $roleDefaultPermissions = [
        'admin' => [
            'manage_vocab', 'ai_generation', 'manage_curriculum', 
            'manage_phrases', 'manage_users', 'manage_roles', 
            'reset_progress', 'view_reports', 'view_students', 'view_student_progress'
        ],
        'moderator' => [
            'manage_vocab', 'ai_generation', 'manage_curriculum', 
            'manage_phrases', 'view_reports'
        ],
        'editor' => [
            'manage_vocab', 'ai_generation', 'manage_phrases'
        ],
        'tutor' => [
            'view_students', 'view_student_progress', 'view_reports'
        ],
        'user' => [
            'learn_curriculum', 'learn_daily', 'practice_voice', 'bookmark_words'
        ],
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
            'permissions_json' => 'array',
        ];
    }

    public function getPermissionsAttribute(): array
    {
        if ($this->isAdmin()) {
            return self::$roleDefaultPermissions['admin'];
        }

        $base = self::$roleDefaultPermissions[$this->role] ?? self::$roleDefaultPermissions['user'];
        if (!empty($this->permissions_json) && is_array($this->permissions_json)) {
            return array_values(array_unique(array_merge($base, $this->permissions_json)));
        }
        return $base;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        return in_array($permission, $this->permissions);
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
