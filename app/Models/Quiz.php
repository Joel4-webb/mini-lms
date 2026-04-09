<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'sous_chapitre_id', 'chapitre_id'];

    public function chapitre() {
        return $this->belongsTo(Chapitre::class);
    }
    
   public function questions() {
        return $this->hasMany(Question::class);
    }

    public function sousChapitre(): BelongsTo
    {
        return $this->belongsTo(SousChapitre::class, 'sous_chapitre_id');
    }
}