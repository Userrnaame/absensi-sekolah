<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'radius_meters',
        'jam_masuk',
        'jam_pulang',
    ];

    /**
     * Ambil setting sekolah (selalu baris pertama).
     * Kalau belum ada, otomatis buat default.
     */
    public static function current(): self
    {
        return self::firstOrCreate([], [
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
            'radius_meters' => 100,
            'jam_masuk' => '07:00:00',
            'jam_pulang' => '14:00:00',
        ]);
    }
}