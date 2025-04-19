<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Créer la fonction de génération du code client
        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_client_code()
            RETURNS TRIGGER AS \$\$
            DECLARE
                last_number INT;
                new_code TEXT;
            BEGIN
                SELECT MAX(CAST(SUBSTRING(code_client FROM 2) AS INTEGER)) INTO last_number
                FROM clients
                WHERE code_client ~ '^CLT[0-9]+$';

                IF last_number IS NULL THEN
                    last_number := 0;
                END IF;

                new_code := 'CLT' || LPAD((last_number + 1)::TEXT, 3, '0');
                NEW.code_client := new_code;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        // 2. Attacher le trigger à la table clients
        DB::unprepared("
            CREATE TRIGGER before_insert_client
            BEFORE INSERT ON clients
            FOR EACH ROW
            EXECUTE FUNCTION generate_client_code();
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS before_insert_client ON clients;");
        DB::unprepared("DROP FUNCTION IF EXISTS generate_client_code();");
    }
};
