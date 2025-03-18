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
        Schema::create('bidang_dampingan', function (Blueprint $table) {
            $table->id('id_bidang_dampingan');
            $table->unsignedBigInteger('id_bidang');
            $table->unsignedBigInteger('id_user');

            $table->foreign('id_bidang')->references('id_bidang')->on('bidang');
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang_dampingan');
    }
};
