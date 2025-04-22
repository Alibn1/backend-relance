<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations pour créer la table des créances de relance.
     */
    public function up()
    {
        Schema::create('creance_relances', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY auto-incrémentée
            // Référence Créance (clé primaire) - 12 caractères dans la migration vs 8 dans la doc
            $table->string('ref_creance')->unique()->comment('Réf Créance');
            
            // Titre de la créance (optionnel, 30 caractères max)
            $table->string('titre_creance')->nullable()->comment('Titre Créance');
            
            // Ordre de la créance (optionnel, 2 caractères)
            $table->string('ordre_creance')->nullable()->comment('Ordre Créance');
            
            // Date de la créance (optionnelle)
            $table->date('date_creance')->nullable()->comment('Date Créance');
            
            // État actif/inactif (1 par défaut pour actif)
            $table->char('actif', 1)->default('1')->comment('Actif (0/1)');
            
            // Timestamps automatiques de Laravel (created_at et updated_at)
            $table->timestamps();
        });
    }

    /**
     * Annule les migrations en supprimant la table.
     */
    public function down()
    {
        Schema::dropIfExists('creance_relances');
    }
};