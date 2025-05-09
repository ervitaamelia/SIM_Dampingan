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
        Schema::create('masyarakat', function (Blueprint $table) {
            $table->string('nomor_anggota', 20)->primary();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('agama', ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->string('nomor_telepon', 15);
            $table->text('alamat');
            $table->enum('status', ['Aktif', 'Non Aktif']);
            $table->string('foto', 255);
            $table->string('qr', 255);
            $table->unsignedBigInteger('id_pekerjaan');
            $table->unsignedBigInteger('id_bidang');
            $table->unsignedBigInteger('id_grup_dampingan');
            
            $table->foreign('id_pekerjaan')->references('id_pekerjaan')->on('pekerjaan');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang');
            $table->foreign('id_grup_dampingan')->references('id_grup_dampingan')->on('grup_dampingan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masyarakat');
    }
};
