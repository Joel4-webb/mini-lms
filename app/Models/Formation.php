<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = ['nom', 'description', 'niveau'];

    // Une formation a plusieurs chapitres
    public function chapitres()
    {
        return $this->hasMany(Chapitre::class);
    }

    // Optionnel : permet d'accéder aux quiz via la formation directement
    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Chapitre::class);
    }

    public function apprenants() {
        return $this->belongsToMany(User::class)->where('role', 'apprenant');
    }
}