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
            $table->string('numero_relance', 8)->unique();
            $table->string('numero_relance_dossier', 8);
            $table->string('code_client');
            $table->string('code_sous_modele', 8)->nullable();
            $table->string('titre_sous_modele', 30)->nullable();
            $table->string('ordre', 2)->nullable();
            $table->string('statut_detail')->nullable()->default('BROUILLON')->index(); // FK vers statut_relance_detail
            $table->date('date_par_statut')->nullable();
            $table->string('etat_relance_actif', 1)->default('1');
            $table->date('date_rappel')->nullable();
            $table->integer('nombre_jour_rappel')->nullable();
            $table->string('methode_envoi', 30)->nullable();
            $table->string('executant_envoi', 25)->nullable();
            $table->date('date_creation_debut')->nullable();
            $table->date('date_creation_fin')->nullable();
            $table->string('etape_actif', 1)->default('1');
            $table->string('objet_relance_1', 50)->nullable();
            $table->string('objet_relance_2', 50)->nullable();
            $table->timestamps();

            $table->index('numero_relance_dossier');
            $table->index('code_client');
            $table->index('date_rappel');

            $table->foreign('numero_relance_dossier')->references('numero_relance_dossier')->on('relance_dossiers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('statut_detail')->references('code')->on('statut_relance_detail')->onDelete('restrict');
            $table->foreign('code_client')->references('code_client')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etape_relances', function (Blueprint $table) {
            $table->dropForeign(['numero_relance_dossier']);
            $table->dropForeign(['code_client']);
            $table->dropForeign(['statut_detail']);
            //$table->dropForeign(['code_sous_modele']);
        });

        Schema::dropIfExists('etape_relances');
    }
};
