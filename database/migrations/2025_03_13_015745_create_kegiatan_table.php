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
            $table->text('masalah');
            $table->text('solusi');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('tempat', 50);
            $table->tinyInteger('jumlah_peserta');
            $table->string('laporan', 100)->nullable();
            $table->unsignedBigInteger('id_fasilitator');
            $table->string('kode_kecamatan');

            $table->foreign('id_fasilitator')->references('id_fasilitator')->on('fasilitator');
            $table->foreign('kode_kecamatan')->references('kode')->on('kecamatans');
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
