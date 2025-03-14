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
            $table->string('judul', 150);
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('tempat', 100);
            $table->integer('jumlah_peserta');
            $table->varchar('laporan')->nullable();
            $table->unsignedBigInteger('id_grup_dampingan');

            $table->foreign('id_grup_dampingan')->references('id_grup_dampingan')->on('grup_dampingan');
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
