<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nis')->unique()->after('id');
            $table->enum('role', ['siswa', 'guru', 'admin'])->default('siswa')->after('nis');
            $table->string('foto_profil')->nullable()->after('password');
            $table->text('bio')->nullable()->after('foto_profil');
        });

        // Buat kolom email jadi nullable (karena login pakai NIS)
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nis', 'role', 'foto_profil', 'bio']);
        });
    }
};