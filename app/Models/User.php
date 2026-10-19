<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
protected $fillable = [
    'nis',
    'name',
    'email',
    'password',
    'role',
    'foto_profil',
    'bio',
    // Portal fields
    'angkatan',
    'tingkat',
    'kelas',
    'kode_kelas',
    'jurusan',
    'role_kelas',
    'jabatan_kelas',
    'nomor_absen',
    'foto_siswa',
    'bio_siswa',
    // Lagu favorit
    'lagu_path',
    'lagu_judul',
    'lagu_artis',
    'lagu_start_detik',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi: 1 user punya banyak absensi
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // Helper: cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // Helper: cek role dalam kelas
public function isWaliKelas(): bool
{
    return $this->role_kelas === 'wali_kelas';
}

public function isKetuaKelas(): bool
{
    return $this->role_kelas === 'ketua_kelas';
}

// Scope: filter per kelas
public function scopeByKelas($query, string $kodeKelas)
{
    return $query->where('kode_kelas', $kodeKelas);
}

// Scope: filter per angkatan
public function scopeByAngkatan($query, int $angkatan)
{
    return $query->where('angkatan', $angkatan);
}

// Accessor: URL foto siswa (dengan fallback placeholder)
public function getFotoSiswaUrlAttribute(): string
{
    if ($this->foto_siswa && Storage::disk('public')->exists($this->foto_siswa)) {
        return Storage::url($this->foto_siswa);
    }

    // Fallback: DiceBear avatar
    return 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($this->name) . '&backgroundColor=5865F2';
}

// Accessor: URL lagu favorit
public function getLaguUrlAttribute(): ?string
{
    if ($this->lagu_path && Storage::disk('public')->exists($this->lagu_path)) {
        return Storage::url($this->lagu_path);
    }
    return null;
}

// Helper: cek apakah user punya lagu favorit
public function hasLagu(): bool
{
    return !empty($this->lagu_path);
}

}
