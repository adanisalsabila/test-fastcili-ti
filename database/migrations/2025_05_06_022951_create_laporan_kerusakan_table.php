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
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_fasilitas');
            $table->text('deskripsi');
            $table->string('jumlah_kerusakan')->default(1);;
            $table->string('foto_kerusakan');
            $table->date('tanggal_lapor');
            $table->date('tanggal_selesai')->nullable();
            $table->unsignedBigInteger('id_status');
            $table->timestamps();

            $table->foreign('id_fasilitas')->references('id_fasilitas')->on('fasilitas')->onDelete('cascade');
            $table->foreign('id_status')->references('id_status')->on('status_laporan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
};
