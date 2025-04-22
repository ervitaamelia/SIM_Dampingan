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
        Schema::create('grup_kegiatan', function (Blueprint $table) {
            $table->id('id_grup_kegiatan');
            $table->unsignedBigInteger('id_grup_dampingan');
            $table->unsignedBigInteger('id_kegiatan');

            $table->foreign('id_grup_dampingan')->references('id_grup_dampingan')->on('grup_dampingan');
            $table->foreign('id_kegiatan')->references('id_kegiatan')->on('kegiatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_kegiatan');
    }
};
