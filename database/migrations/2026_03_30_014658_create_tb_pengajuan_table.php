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
        Schema::create('tb_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_layanan')->constrained('tb_layanan')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->enum('status_pengajuan', ['DRAFT', 'MENUNGGU_DIPROSES', 'DIPROSES', 'DITOLAK', 'PERBAIKAN', 'SELESAI'])->default('DRAFT');
            $table->string('nomor_tiket', 50)->unique();
            $table->date('tanggal_pengajuan')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pengajuan');
    }
};
