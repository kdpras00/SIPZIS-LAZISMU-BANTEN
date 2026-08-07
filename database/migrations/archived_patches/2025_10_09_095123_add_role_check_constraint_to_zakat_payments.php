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
        // Add a trigger to ensure received_by can only reference users with admin or staff roles
        DB::unprepared("
            CREATE TRIGGER chk_received_by_role_insert
            BEFORE INSERT ON zakat_payments
            FOR EACH ROW
            BEGIN
                IF NEW.received_by IS NOT NULL THEN
                    IF NOT EXISTS (
                        SELECT 1 FROM users 
                        WHERE users.id = NEW.received_by 
                        AND users.role IN ('admin', 'staff')
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'received_by must reference a user with admin or staff role';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER chk_received_by_role_update
            BEFORE UPDATE ON zakat_payments
            FOR EACH ROW
            BEGIN
                IF NEW.received_by IS NOT NULL THEN
                    IF NOT EXISTS (
                        SELECT 1 FROM users 
                        WHERE users.id = NEW.received_by 
                        AND users.role IN ('admin', 'staff')
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'received_by must reference a user with admin or staff role';
                    END IF;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the triggers
        DB::unprepared("DROP TRIGGER IF EXISTS chk_received_by_role_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS chk_received_by_role_update");
    }
};
