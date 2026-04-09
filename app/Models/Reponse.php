<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reponse extends Model
{
    use HasFactory;

    protected $fillable = ['texte_reponse', 'est_correcte', 'question_id'];

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
