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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('healthcare_professional_id');
            $table->timestamp('appointment_start_time');
            $table->timestamp('appointment_end_time');
            $table->tinyInteger('status')->comment('0-booked, 1-completed, 2-cancelled')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('healthcare_professional_id')->references('id')->on('healthcare_professionals');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
