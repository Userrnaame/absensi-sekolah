<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 7)->default(-6.2000000);
            $table->decimal('longitude', 10, 7)->default(106.8166667);
            $table->integer('radius_meters')->default(100);
            $table->time('jam_masuk')->default('07:00:00');
            $table->time('jam_pulang')->default('14:00:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};