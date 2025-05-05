<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('etape_relances', function (Blueprint $table) {
            $table->string('pdf_path')->nullable()->after('objet_relance_2');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etape_relances', function (Blueprint $table) {
            $table->dropColumn('pdf_path'); 
        });
    }
};
