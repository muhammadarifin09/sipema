<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('logs_aktivitas', function (Blueprint $table) {
            $table->string('action')->nullable()->after('user_id');
            $table->string('module')->nullable()->after('action');
            $table->json('data')->nullable()->after('aktivitas');
        });
    }

    public function down(): void
    {
        Schema::table('logs_aktivitas', function (Blueprint $table) {
            $table->dropColumn(['action', 'module', 'data']);
        });
    }
};