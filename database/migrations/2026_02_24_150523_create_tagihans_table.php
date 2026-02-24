<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();

            // Relasi ke siswa
            $table->foreignId('siswa_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Relasi ke tahun ajaran
            $table->foreignId('tahun_ajaran_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Bulan & Tahun tagihan
            $table->string('bulan'); // contoh: Februari
            $table->year('tahun');   // contoh: 2026

            // Nominal dan jatuh tempo
            $table->decimal('nominal', 15, 2);
            $table->integer('tanggal_jatuh_tempo');

            // Status pembayaran
            $table->enum('status', ['belum_bayar', 'menunggu', 'lunas'])
                  ->default('belum_bayar');

            // Metode pembayaran
            $table->enum('metode_pembayaran', ['manual', 'midtrans'])
                  ->nullable();

            // Tanggal bayar
            $table->dateTime('tanggal_bayar')->nullable();

            $table->timestamps();

            // Mencegah double generate bulan yang sama
            $table->unique(['siswa_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
