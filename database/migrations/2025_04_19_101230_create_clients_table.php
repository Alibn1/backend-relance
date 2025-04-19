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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('raison_sociale')->nullable()->after('id'); // Name of the client
            $table->string('adresse')->nullable(); // Address field
            $table->string('ville')->nullable(); // City field
            $table->string('pays')->nullable(); // Country field
            $table->string('telephone')->nullable(); // Phone number field
            $table->string('email')->nullable()->unique(); // Unique email field
            $table->string('responsable')->nullable(); // Responsible person
            $table->string('secteur_activite')->nullable(); // Sector of activity
            $table->decimal('solde', 15, 2)->default(0); // Balance field
            $table->decimal('encours_autorise', 15, 2)->default(0); // Authorized credit
            $table->boolean('actif')->default(true); // Active status
            $table->date('date_creation')->nullable(); // Creation date
            $table->date('derniere_relance')->nullable(); // Last reminder date
            $table->string('code_client')->nullable()->unique(); // Unique client code
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
