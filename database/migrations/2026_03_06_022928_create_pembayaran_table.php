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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            // relasi ke tagihan
            $table->foreignId('tagihan_id')
                  ->constrained('tagihans')
                  ->cascadeOnDelete();

            // relasi ke siswa
            $table->foreignId('siswa_id')
                  ->constrained('siswas')
                  ->cascadeOnDelete();

            // id transaksi dari midtrans
            $table->string('order_id')->unique()->nullable();

            // jumlah pembayaran
            $table->integer('jumlah_bayar');

            // metode pembayaran
            $table->enum('metode_bayar', ['midtrans','tunai'])->nullable();

            // status pembayaran
            $table->enum('status', ['pending','berhasil','gagal'])
                  ->default('pending');

            // waktu pembayaran
            $table->timestamp('tanggal_bayar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};