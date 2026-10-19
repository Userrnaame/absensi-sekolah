<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'lampiran',
        'status',
        'reviewed_by',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function dates(): Collection
    {
        $dates = [];
        foreach (CarbonPeriod::create($this->tanggal_mulai, $this->tanggal_selesai) as $date) {
            $dates[] = $date->toDateString();
        }

        return collect($dates);
    }

    public function coversDate(Carbon|string $date): bool
    {
        $date = Carbon::parse($date)->toDateString();

        return $date >= $this->tanggal_mulai->toDateString()
            && $date <= $this->tanggal_selesai->toDateString();
    }

    public function scopeOverlapping(Builder $query, string $start, string $end): Builder
    {
        return $query->where('tanggal_mulai', '<=', $end)
            ->where('tanggal_selesai', '>=', $start);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'disetujui']);
    }

    public static function hasApprovedCovering(int $userId, Carbon|string $date): bool
    {
        $date = Carbon::parse($date)->toDateString();

        return self::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->where('tanggal_mulai', '<=', $date)
            ->where('tanggal_selesai', '>=', $date)
            ->exists();
    }
}
