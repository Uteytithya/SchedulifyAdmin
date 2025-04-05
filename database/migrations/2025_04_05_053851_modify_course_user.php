<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER before_insert_course_user
        BEFORE INSERT ON course_user
        FOR EACH ROW
        BEGIN
            IF NEW.id IS NULL THEN
                SET NEW.id = UUID();
            END IF;
        END
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_user');
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_course_user');

    }
};
