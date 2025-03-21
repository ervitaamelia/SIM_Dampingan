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
        Schema::create('kabupatens', function (Blueprint $table) {
            $table->string('kode')->primary(); // 'kode' sebagai Primary Key
            $table->string('nama');
            $table->string('kode_provinsi'); // Menggunakan 'kode_provinsi' sebagai Foreign Key
            $table->foreign('kode_provinsi')->references('kode')->on('provinsis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabupatens');
    }
};
