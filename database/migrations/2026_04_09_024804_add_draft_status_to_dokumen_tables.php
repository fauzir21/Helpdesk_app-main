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
        Schema::table('tb_dokumen', function (Blueprint $table) {
            $table->enum('status', ['DRAFT', 'SELESAI', 'BELUM_SELESAI', 'PERBAIKAN'])->default('DRAFT')->change();
        });

        Schema::table('tb_dokumen_tambahan', function (Blueprint $table) {
            $table->enum('status', ['DRAFT', 'SELESAI', 'BELUM_SELESAI', 'PERBAIKAN'])->default('DRAFT')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dokumen', function (Blueprint $table) {
            $table->enum('status', ['SELESAI', 'BELUM_SELESAI', 'PERBAIKAN'])->change();
        });

        Schema::table('tb_dokumen_tambahan', function (Blueprint $table) {
            $table->enum('status', ['SELESAI', 'BELUM_SELESAI', 'PERBAIKAN'])->change();
        });
    }
};
