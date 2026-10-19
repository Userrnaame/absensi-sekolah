<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Data portal sekolah
            $table->integer('angkatan')->nullable()->after('role');           // 2026
            $table->integer('tingkat')->nullable()->after('angkatan');        // 12, 11, 10
            $table->string('kelas')->nullable()->after('tingkat');            // "XII AKL 1"
            $table->string('kode_kelas')->nullable()->after('kelas');         // "xii-akl-1" (untuk URL)
            $table->enum('jurusan', ['AKL', 'MPLB', 'PM', 'PPLG', 'TJKT', 'BCF'])
                  ->nullable()->after('kode_kelas');

            // Peran di dalam kelas
            $table->enum('role_kelas', ['wali_kelas', 'ketua_kelas', 'anggota'])
                  ->default('anggota')->after('jurusan');

            // Nomor absen di kelas (1-36)
            $table->integer('nomor_absen')->nullable()->after('role_kelas');

            // Foto setengah badan untuk portal
            $table->string('foto_siswa')->nullable()->after('foto_profil');

            // Bio untuk portal
            $table->text('bio_siswa')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'angkatan', 'tingkat', 'kelas', 'kode_kelas', 'jurusan',
                'role_kelas', 'nomor_absen', 'foto_siswa', 'bio_siswa'
            ]);
        });
    }
};