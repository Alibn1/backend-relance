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
        Schema::create('statut_relance', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->primary();
            $table->string('libelle', 30)->nullable();
            $table->string('couleur', 10)->nullable();
            $table->string('champ_interface', 20)->nullable();
            $table->string('actif', 1)->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statut_relance');
    }
};
