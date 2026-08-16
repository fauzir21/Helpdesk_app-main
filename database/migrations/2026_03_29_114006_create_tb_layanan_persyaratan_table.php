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
        Schema::create('tb_layanan_persyaratan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('tb_layanan')->onDelete('cascade');
            $table->foreignId('persyaratan_id')->constrained('tb_persyaratan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_layanan_persyaratan');
    }
};
