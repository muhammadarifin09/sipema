<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pembayarans', function (Blueprint $table) {
    $table->id();

    $table->foreignId('siswa_id')->constrained()->cascadeOnDelete();
    $table->foreignId('tagihan_id')->constrained()->cascadeOnDelete();

    $table->unsignedBigInteger('pembayaran_id')->nullable();

    $table->integer('bulan');
    $table->integer('tahun');

    $table->decimal('nominal', 10, 2);
    $table->string('metode_pembayaran')->nullable();

    $table->timestamp('tanggal_bayar');

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pembayarans');
    }
};