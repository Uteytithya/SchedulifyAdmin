<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('schedule_sessions', function (Blueprint $table) {
            $table->dropUnique('unique_room_schedule'); // Drop the unique constraint
        });
    }

    public function down(): void
    {
        Schema::table('schedule_sessions', function (Blueprint $table) {
            $table->unique(['room_id', 'day', 'start_time', 'end_time'], 'unique_room_schedule'); // Restore unique constraint
        });
    }
};
