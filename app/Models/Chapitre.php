<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapitre extends Model
{
    protected $fillable = ['titre', 'description', 'formation_id'];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function sousChapitres()
    {
        return $this->hasMany(SousChapitre::class);
    }

    public function quiz() {
        return $this->hasOne(Quiz::class);
    }
}
