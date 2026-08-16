<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create pivot table
        Schema::create('tb_tim_kerja_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('tim_kerja_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tim_kerja_id')->references('id_tim_kerja')->on('tb_tim_kerja')->onDelete('cascade');
        });

        // Migrate existing data
        $usersWithTimKerja = DB::table('users')
            ->whereNotNull('tim_kerja_id')
            ->where('tim_kerja_id', '>', 0)
            ->get();

        foreach ($usersWithTimKerja as $user) {
            DB::table('tb_tim_kerja_user')->insert([
                'user_id' => $user->id,
                'tim_kerja_id' => $user->tim_kerja_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop tim_kerja_id from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tim_kerja_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add tim_kerja_id back to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('tim_kerja_id')->nullable()->default(0)->after('status');
        });

        // Migrate data back (picking the first team if multiple exist)
        $pivotData = DB::table('tb_tim_kerja_user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('user_id');

        foreach ($pivotData as $userId => $teams) {
            DB::table('users')
                ->where('id', $userId)
                ->update(['tim_kerja_id' => $teams->first()->tim_kerja_id]);
        }

        Schema::dropIfExists('tb_tim_kerja_user');
    }
};
