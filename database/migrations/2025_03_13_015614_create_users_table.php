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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_lengkap', 100);
            $table->string('nomor_telepon', 15);
            $table->string('domisili', 50);
            $table->string('email')->unique();
            $table->string('password', 255);
            $table->enum('role', ['superadmin', 'admin', 'fasilitator']);
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
        Schema::dropIfExists('users');
    }
};
