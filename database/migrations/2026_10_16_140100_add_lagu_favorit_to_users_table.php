<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lagu_path')->nullable()->after('bio_siswa');
            $table->string('lagu_judul')->nullable()->after('lagu_path');
            $table->string('lagu_artis')->nullable()->after('lagu_judul');
            $table->integer('lagu_start_detik')->nullable()->default(30)->after('lagu_artis');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lagu_path', 'lagu_judul', 'lagu_artis', 'lagu_start_detik']);
        });
    }
};