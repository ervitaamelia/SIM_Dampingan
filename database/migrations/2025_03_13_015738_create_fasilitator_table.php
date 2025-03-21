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
        Schema::create('fasilitator', function (Blueprint $table) {
            $table->id('id_fasilitator');
            $table->unsignedBigInteger('id_grup_dampingan');
            $table->unsignedBigInteger('id_user');

            $table->foreign('id_grup_dampingan')->references('id_grup_dampingan')->on('grup_dampingan');
            $table->foreign('id_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitator');
    }
};
