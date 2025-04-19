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
        Schema::create('relance_dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('numero_relance_dossier', 8);
            $table->date('date_relance_dossier')->nullable();
            $table->string('code_client', 6)->nullable();
            $table->string('contact_interlocuteur', 25)->nullable();
            $table->string('utilisateur_creation', 25)->nullable();
            $table->string('utilisateur_modification', 25)->nullable();
            $table->time('horodatage_creation')->nullable();
            $table->time('horodatage_fin')->nullable();
            $table->time('horodatage_modification')->nullable();
            $table->string('code_modele', 8)->nullable();
            $table->string('statut', 10); // FK vers statut_relance
            $table->date('date_par_statut')->nullable();
            $table->string('actif', 1)->default('1');
            $table->timestamps();

            $table->foreign('statut')->references('code')->on('statut_relance')->onDelete('restrict');
            $table->foreign('code_client')->references('code_client')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relance_dossiers');
    }
};
