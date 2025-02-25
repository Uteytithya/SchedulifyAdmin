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
        Schema::create('session_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('session_type_id');
            $table->uuid('course_id');
            $table->uuid('timetable_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('session_type_id')->references('id')->on('session_types');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('timetable_id')->references('id')->on('timetables');
            $table->date('requested_date');
            $table->time('new_start_time');
            $table->time('new_end_time');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_requests');
    }
};
