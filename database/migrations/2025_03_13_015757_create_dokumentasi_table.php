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
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id('id_dokumentasi');
            $table->string('path_dokumentasi', 255);
            $table->enum('jenis_dokumentasi', ['foto', 'video']);
            $table->unsignedBigInteger('id_kegiatan');

            $table->foreign('id_kegiatan')->references('id_kegiatan')->on('kegiatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi');
    }
};
