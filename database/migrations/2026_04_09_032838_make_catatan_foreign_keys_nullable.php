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
        Schema::table('tb_catatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dokumen')->nullable()->change();
            $table->unsignedBigInteger('id_dokumen_tambahan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_catatan', function (Blueprint $table) {
            // Cannot easily revert nullable change in SQLite or some MySQL versions without knowing previous state,
            // but for this app we want them nullable.
        });
    }
};
