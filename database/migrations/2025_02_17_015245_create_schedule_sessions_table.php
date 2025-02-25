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
        Schema::create('schedule_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_type_id');
            $table->uuid('course_id');
            $table->uuid('timetable_id');
            $table->uuid('room_id');
            $table->foreign('session_type_id')->references('id')->on('session_types');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('timetable_id')->references('id')->on('timetables');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
