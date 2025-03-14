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
            $table->string('nama_grup_dampingan', 100);
            $table->enum('jenis_dampingan', ['Pusat', 'Provinsi', 'Kabupaten', 'Kecamatan']);
            $table->unsignedBigInteger('id_provinsi');
            $table->unsignedBigInteger('id_kabupaten');
            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_bidang');

            $table->foreign('id_provinsi')->references('id_provinsi')->on('provinsi')->onDelete('cascade');
            $table->foreign('id_kabupaten')->references('id_kabupaten')->on('kabupaten')->onDelete('cascade');
            $table->foreign('id_kecamatan')->references('id_kecamatan')->on('kecamatan')->onDelete('cascade');
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
