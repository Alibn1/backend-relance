<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations pour créer la table de situation des étapes de relance.
     */
    public function up()
    {
        Schema::create('situation_relances', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY auto-incrémentée
            // N° Etape Relance (partie de la clé primaire composée et clé étrangère)
            $table->string('numero_etape_relance')->comment('N° Relance Etape');
            
            // Référence Créance (partie de la clé primaire composée)
            $table->string('ref_creance')->comment('Réf Créance');
            
            // Date de la situation (optionnelle)
            $table->date('date')->nullable()->comment('Date');
            
            // Montant débit (optionnel)
            $table->decimal('debit', 11, 2)->nullable()->comment('Débit');
            
            // Montant crédit (optionnel)
            $table->decimal('credit', 11, 2)->nullable()->comment('Crédit');
            
            // Valeur (optionnel)
            $table->decimal('valeur', 11, 2)->nullable()->comment('Valeur');
            
            // Observations (optionnelles)
            $table->string('observation', 30)->nullable()->comment('Observation');
            
            // Ordre (optionnel)
            $table->string('ordre', 2)->nullable()->comment('Ordre');

            // Timestamps Laravel
            $table->timestamps();

            // Clé primaire composée
            $table->primary(['numero_etape_relance', 'ref_creance']);

            // Clés étrangères
            $table->foreign('numero_etape_relance')
                  ->references('numero_relance')
                  ->on('etape_relances')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                
            $table->foreign('ref_creance')
                  ->references('ref_creance')
                  ->on('creance_relances')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Annule les migrations en supprimant la table.
     */
    public function down()
    {
        Schema::dropIfExists('situation_relances');
    }
};