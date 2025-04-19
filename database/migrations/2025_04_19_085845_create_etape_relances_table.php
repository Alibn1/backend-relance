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
        Schema::create('etape_relances', function (Blueprint $table) {
            $table->id();
            $table->string('numero_relance', 8);
            $table->string('numero_relance_dossier', 8);
            $table->string('code_sous_modele', 8)->nullable();
            $table->string('titre_sous_modele', 30)->nullable();
            $table->string('ordre', 2)->nullable();
            $table->string('statut_detail', 10); // FK vers statut_relance_detail
            $table->date('date_par_statut')->nullable();
            $table->string('etat_relance_actif', 1)->default('1');
            $table->date('date_rappel')->nullable();
            $table->integer('nombre_jour_rappel')->nullable();
            $table->string('methode_envoi', 30)->nullable();
            $table->string('executant_envoi', 25)->nullable();
            $table->date('date_creation_debut')->nullable();
            $table->date('date_creation_fin')->nullable();
            $table->string('etat_etape_relance_actif', 1)->default('1');
            $table->string('objet_relance_1', 50)->nullable();
            $table->string('objet_relance_2', 50)->nullable();
            $table->timestamps();

            $table->foreign('statut_detail')->references('code')->on('statut_relance_detail')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etape_relances');
    }
};
