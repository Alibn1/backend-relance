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
        Schema::create('sous_modeles', function (Blueprint $table) {
            $table->id();
            $table->string('code_sous_modele', 32)->unique(); // Code modèle unique
            $table->string('titre');
            $table->json('texte');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_modeles');
    }
};
