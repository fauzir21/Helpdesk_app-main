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
        Schema::table('tb_layanan', function (Blueprint $table) {
            $table->enum('user_category', ['umum', 'pemerintah', 'semua'])->default('semua')->after('durasi_hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_layanan', function (Blueprint $table) {
            $table->dropColumn('user_category');
        });
    }
};
