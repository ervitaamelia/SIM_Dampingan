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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id('id_kegiatan');
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->text('masalah')->nullable();
            $table->text('solusi')->nullable();
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('tempat', 50);
            $table->tinyInteger('jumlah_peserta');
            $table->string('laporan', 100)->nullable();
            $table->unsignedBigInteger('id_user');
            $table->string('kode_kecamatan');
            $table->unsignedBigInteger('id_bidang');

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('kode_kecamatan')->references('kode')->on('kecamatans');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
