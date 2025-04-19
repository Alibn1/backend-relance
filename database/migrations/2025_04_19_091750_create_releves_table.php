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
        Schema::create('releves', function (Blueprint $table) {
            $table->id();
            $table->string('code_releve')->unique();
            $table->string('code_client'); // Assure-toi que clients.code_client existe
            $table->date('date_releve');
            $table->decimal('solde_initiale', 15, 2)->default(0);
            $table->decimal('solde_finale', 15, 2)->default(0);
            $table->string('statut')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->foreign('code_client')->references('code_client')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releves');
    }
};
