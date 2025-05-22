<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('etape_releve', function (Blueprint $table) {
            $table->id();

            $table->string('numero_relance'); // FK vers etape_relances.numero_relance
            $table->string('code_releve');    // FK vers releves.code_releve

            // Index pour performance
            $table->index(['numero_relance', 'code_releve']);

            // Clés étrangères avec suppression en cascade
            $table->foreign('numero_relance')
                ->references('numero_relance')
                ->on('etape_relances')
                ->onDelete('cascade');

            $table->foreign('code_releve')
                ->references('code_releve')
                ->on('releves')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etape_releve');
    }
};
