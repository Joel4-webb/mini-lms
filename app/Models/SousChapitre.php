<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SousChapitre extends Model
{
    protected $fillable = ['titre', 'contenu', 'chapitre_id', 'resume', 'lien_ressource'];

    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class, 'sous_chapitre_id');
    }
}
