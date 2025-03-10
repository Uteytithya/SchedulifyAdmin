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
        Schema::table('session_requests', function (Blueprint $table) {
            $table->enum('request_type', ['urgent', 'normal'])->default('normal');
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_requests', function (Blueprint $table) {
            $table->dropColumn('request_type');
            $table->string('status')->change();
        });
    }
};
