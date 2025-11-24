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
        Schema::create('penugasan_teknisi', function (Blueprint $table) {
            $table->id('id_penugasan');
            $table->unsignedBigInteger('id_laporan');
            $table->unsignedBigInteger('id_user');
            $table->date('tenggat')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status_perbaikan');
            $table->text('catatan_teknisi')->nullable();
            $table->text('komentar_sarpras')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->integer('skor_kinerja')->nullable();
            $table->timestamps();

            $table->foreign('id_laporan')->references('id_laporan')->on('laporan_kerusakan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_teknisi');
    }
};
