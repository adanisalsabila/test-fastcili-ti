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
        Schema::create('kriteria_penilaian', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->unsignedBigInteger('id_laporan');
            $table->integer('tingkat_kerusakan');
            $table->integer('frekuensi_digunakan');
            $table->integer('dampak');
            $table->integer('estimasi_biaya');
            $table->integer('potensi_bahaya');
            $table->timestamps();
            
            $table->foreign('id_laporan')->references('id_laporan')->on('laporan_kerusakan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_penilaian');
    }
};
