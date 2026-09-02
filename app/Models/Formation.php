<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    
    protected $fillable = [
        'nom', 
        'description', 
        'niveau',
        'creator_id',
        'is_public',
        'prerequis_id',
        'is_featured'
    ];

    
    public function chapitres()
    {
        return $this->hasMany(Chapitre::class);
    }

    
    public function quizzes()
    {
        return $this->hasManyThrough(Quiz::class, Chapitre::class);
    }


    public function apprenants() {
        return $this->belongsToMany(User::class)->where('role', 'apprenant');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    
    public function prerequis() {
        return $this->belongsTo(Formation::class, 'prerequis_id');
    }
}