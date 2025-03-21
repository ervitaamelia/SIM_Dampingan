<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->string('kode')->primary(); // 'kode' sebagai Primary Key
            $table->string('nama');
            $table->string('kode_kabupaten'); // Menggunakan 'kode_kabupaten' sebagai Foreign Key
            $table->foreign('kode_kabupaten')->references('kode')->on('kabupatens')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
    }
};
