<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Créer la fonction de génération du code_releve
        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_code_releve()
            RETURNS TRIGGER AS \$\$
            DECLARE
                last_number INT;
                new_code TEXT;
            BEGIN
                -- Récupérer le dernier numéro de code_releve (format REL25000, REL25001, ...)
                SELECT MAX(CAST(SUBSTRING(code_releve FROM 4) AS INTEGER)) INTO last_number
                FROM releves
                WHERE code_releve ~ '^RLV[0-9]+$';

                IF last_number IS NULL THEN
                    last_number := 25000;  -- Si aucun numéro n'est trouvé, commencer à REL25000
                END IF;

                -- Générer le nouveau code
                new_code := 'RLV' || (last_number + 1);  -- Incrémenter de 1
                NEW.code_releve := new_code;  -- Assigner le code généré à la nouvelle ligne
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        // 2. Attacher le trigger à la table releves
        DB::unprepared("
            CREATE TRIGGER before_insert_releve
            BEFORE INSERT ON releves
            FOR EACH ROW
            EXECUTE FUNCTION generate_code_releve();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer le trigger et la fonction si la migration est annulée
        DB::unprepared("DROP TRIGGER IF EXISTS before_insert_releve ON releves;");
        DB::unprepared("DROP FUNCTION IF EXISTS generate_code_releve();");
    }
};
