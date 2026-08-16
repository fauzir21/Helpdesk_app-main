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
        Schema::create('tb_riwayat_disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('tb_pengajuan')->onDelete('cascade');
            $table->string('nomor_tiket', 50);
            $table->string('status', 50);
            $table->timestamp('tanggal_disposisi');
            $table->text('keterangan')->nullable();
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat_disposisi');
    }
};
