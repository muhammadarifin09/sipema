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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();

            // relasi ke users
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('judul');
            $table->text('pesan');

            // status notifikasi
            $table->enum('status', ['unread', 'read'])->default('unread');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};