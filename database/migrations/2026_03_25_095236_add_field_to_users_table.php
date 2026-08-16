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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipe', ['pegawai', 'users', 'admin'])->default('users')->after('email');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('tipe');
            $table->integer('tim_kerja_id')->nullable()->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'status', 'tim_kerja_id']);
        });
    }
};
