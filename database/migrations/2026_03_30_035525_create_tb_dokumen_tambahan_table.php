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
        Schema::create('tb_dokumen_tambahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('tb_pengajuan')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->string('file');
            $table->enum('status', ['SELESAI', 'BELUM_SELESAI', 'PERBAIKAN'])->default('BELUM_SELESAI');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_dokumen_tambahan');
    }
};
