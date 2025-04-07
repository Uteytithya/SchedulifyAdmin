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
        Schema::table('session_requests', function (Blueprint $table) {
            $table->string('room_id')->nullable(); 
        });
    }
    
    public function down()
    {
        Schema::table('session_requests', function (Blueprint $table) {
            $table->dropColumn('room_id');
        });
    }
    
};
