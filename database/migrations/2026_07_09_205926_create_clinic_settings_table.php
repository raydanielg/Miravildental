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
        Schema::create('clinic_settings', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name')->default('Miravil Specialised Dental Centre');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('sender_id')->default('MIRAVIL');
            $table->integer('default_appointment_duration')->default(30);
            $table->integer('reminder_24h_before')->default(24);
            $table->integer('reminder_2h_before')->default(2);
            $table->integer('recall_after_days')->default(180);
            $table->string('currency')->default('TZS');
            $table->string('timezone')->default('Africa/Dar_es_Salaam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_settings');
    }
};
