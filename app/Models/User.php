<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'points_balance',
        'formations_created',
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
        ];
    }

    public function isAdmin() {
        return $this->role === 'admin'; 
    }


    public function formations() {
        return $this->belongsToMany(Formation::class);
    }

    public function createdFormations() {
        return $this->hasMany(Formation::class, 'creator_id');
    }

    public function completedQuizzes() {
        return $this->belongsToMany(Quiz::class, 'quiz_completions')
                    ->withPivot('score', 'is_passed')
                    ->withTimestamps();
    }
}