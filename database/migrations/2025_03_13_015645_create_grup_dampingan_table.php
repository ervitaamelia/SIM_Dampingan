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
        Schema::create('grup_dampingan', function (Blueprint $table) {
            $table->id('id_grup_dampingan');
            $table->string('nama_grup_dampingan')->unique();
            $table->enum('jenis_dampingan', ['Pusat', 'Provinsi', 'Kabupaten', 'Kecamatan']);

            // Menggunakan kode wilayah, bukan id
            $table->string('kode_provinsi')->nullable();
            $table->string('kode_kabupaten')->nullable();
            $table->string('kode_kecamatan')->nullable();
            $table->unsignedBigInteger('id_bidang');

            // Relasi ke tabel wilayah yang menggunakan kode
            $table->foreign('kode_provinsi')->references('kode')->on('provinsis')->onDelete('cascade');
            $table->foreign('kode_kabupaten')->references('kode')->on('kabupatens')->onDelete('cascade');
            $table->foreign('kode_kecamatan')->references('kode')->on('kecamatans')->onDelete('cascade');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_dampingan');
    }
};
