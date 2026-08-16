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
            $table->json('input_tambahan')->nullable()->after('user_category');
        });

        Schema::table('tb_pengajuan', function (Blueprint $table) {
            $table->json('detail_tambahan')->nullable()->after('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_layanan', function (Blueprint $table) {
            $table->dropColumn('input_tambahan');
        });

        Schema::table('tb_pengajuan', function (Blueprint $table) {
            $table->dropColumn('detail_tambahan');
        });
    }
};
