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
        Schema::table('tb_layanan_persyaratan', function (Blueprint $table) {
            $table->foreignId('layanan_kategori_id')->nullable()->after('persyaratan_id')->constrained('tb_layanan_kategoris')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_layanan_persyaratan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('layanan_kategori_id');
        });
    }
};
