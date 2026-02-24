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
        Schema::create('spp_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('nominal', 15, 2);
            $table->integer('tanggal_jatuh_tempo'); // contoh: 5 (artinya tanggal 5 tiap bulan)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp_settings');
    }
};
