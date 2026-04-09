<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sous_chapitres', function (Blueprint $table) {
            $table->id();
        $table->string('titre');
        $table->longText('contenu')->nullable();
        $table->text('resume')->nullable(); 
        $table->string('lien_ressource')->nullable();
        $table->foreignId('chapitre_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_chapitres');
    }
};
