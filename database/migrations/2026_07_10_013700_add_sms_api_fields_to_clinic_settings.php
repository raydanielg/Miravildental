<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->string('sms_provider')->nullable()->after('sender_id');
            $table->string('sms_api_username')->nullable()->after('sms_provider');
            $table->string('sms_api_password')->nullable()->after('sms_api_username');
            $table->string('sms_api_key')->nullable()->after('sms_api_password');
            $table->string('sms_api_secret')->nullable()->after('sms_api_key');
            $table->string('sms_api_url')->nullable()->after('sms_api_secret');
            $table->string('sms_test_phone')->nullable()->after('sms_api_url');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->dropColumn([
                'sms_provider',
                'sms_api_username',
                'sms_api_password',
                'sms_api_key',
                'sms_api_secret',
                'sms_api_url',
                'sms_test_phone',
            ]);
        });
    }
};
