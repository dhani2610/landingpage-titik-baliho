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
        Schema::create('balihos', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('titik');
            $table->longText('foto_kecil'); // Diset longText untuk menyimpan Base64 image
            $table->longText('foto_besar');
            $table->string('status')->default('AVAILABLE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balihos');
    }
};
