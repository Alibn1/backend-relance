<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_relances', function (Blueprint $table) {
            $table->id(); 

            $table->string('numero_evenement', 30)->unique()->comment('Numéro Evénement');

            $table->string('numero_relance', 20)->comment('Numéro Relance');

            $table->date('date_evenement')->nullable()->comment('Date Evénement');

            $table->string('statut', 10)->nullable()->comment('Statut de relance (référence à code_statut_dt)');

            $table->date('date_promesse')->nullable()->comment('Date Promesse');

            $table->string('contact', 30)->nullable()->comment('Contact / Interlocuteur');

            $table->string('user_creation', 30)->nullable()->comment('Utilisateur Création');

            $table->string('observation', 50)->nullable()->comment('Observation');

            $table->char('actif', 1)->default('1')->comment('Actif (0/1)');

            $table->string('code_client', 6)->nullable()->comment('Code Client');

            $table->string('solde_a_relancer', 20)->nullable()->comment('Solde à relancer');

            $table->date('date_prochaine_action')->nullable()->comment('Date Prochaine Action');

            // 🔗 Clé étrangère vers etape_relances
            $table->foreign('numero_relance')
                  ->references('numero_relance')
                  ->on('etape_relances')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // 🔗 Clé étrangère vers statut_relance_dt
            $table->foreign('statut')
                  ->references('code')
                  ->on('statut_relance_detail')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('event_relances', function (Blueprint $table) {
            $table->dropForeign(['numero_relance']);
            $table->dropForeign(['statut']);
        });

        Schema::dropIfExists('event_relances');
    }
};
