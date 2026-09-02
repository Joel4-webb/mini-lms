<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Mise à jour de la table 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points_balance')->default(0)->after('role');
            $table->integer('formations_created')->default(0)->after('points_balance');
        });

        // 2. Mise à jour de la table 'formations'
        Schema::table('formations', function (Blueprint $table) {
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete()->after('id');
            $table->boolean('is_public')->default(false)->after('description');
            $table->foreignId('prerequis_id')->nullable()->constrained('formations')->nullOnDelete()->after('is_public');
        });

        // 3. Création de la table de progression (Suivi des Quiz)
        Schema::create('quiz_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->float('score');
            $table->boolean('is_passed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_completions');

        Schema::table('formations', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['prerequis_id']);
            $table->dropColumn(['creator_id', 'is_public', 'prerequis_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points_balance', 'formations_created']);
        });
    }
};