<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // Absensi hari ini
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('tanggal', Carbon::today())
            ->first();

        // Statistik bulan ini
        $stats = [
            'hadir' => Attendance::where('user_id', $user->id)
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('keterangan', 'hadir')
                ->whereNotNull('waktu_masuk')
                ->count(),
            'izin' => Attendance::where('user_id', $user->id)
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('keterangan', 'izin')
                ->count(),
            'sakit' => Attendance::where('user_id', $user->id)
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('keterangan', 'sakit')
                ->count(),
            'cuti' => Attendance::where('user_id', $user->id)
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where('keterangan', 'cuti')
                ->count(),
        ];

        // ============ HITUNG HARI SEKOLAH BULAN INI (Senin-Jumat) ============
        $hariSekolahBulanIni = $this->hitungHariSekolah(
            Carbon::now()->startOfMonth(),
            Carbon::now()
        );

        // Persentase kehadiran
        $persentaseKehadiran = $hariSekolahBulanIni > 0
            ? round(($stats['hadir'] / $hariSekolahBulanIni) * 100)
            : 0;

        // ============ TIMELINE 7 HARI TERAKHIR ============
        $timeline7Hari = $this->buildTimeline7Hari($user->id);

        // ============ PERINGKAT & PODIUM KELAS ============
        $myHadir = $stats['hadir'];
        
        // Query dasar untuk siswa di kelas yang sama
        $queryKelas = User::where('role', 'siswa')->where('kelas', $user->kelas);
        
        // 1. Hitung total siswa di kelas
        $totalSiswaKelas = $queryKelas->count();

        // 2. Hitung peringkat user di kelasnya sendiri
        $siswaLebihRajin = User::where('role', 'siswa')
            ->where('kelas', $user->kelas) // Filter kelas
            ->where('id', '!=', $user->id)
            ->withCount(['attendances as total_hadir' => function ($query) use ($bulanIni, $tahunIni) {
                $query->whereMonth('tanggal', $bulanIni)
                      ->whereYear('tanggal', $tahunIni)
                      ->where('keterangan', 'hadir')
                      ->whereNotNull('waktu_masuk');
            }])
            ->having('total_hadir', '>', $myHadir)
            ->count();

        $peringkatKelas = $siswaLebihRajin + 1;

        // 3. Ambil Top 3 Siswa di kelas untuk Podium
        $top3Kelas = User::where('role', 'siswa')
            ->where('kelas', $user->kelas) // Filter kelas
            ->withCount(['attendances as total_hadir' => function ($query) use ($bulanIni, $tahunIni) {
                $query->whereMonth('tanggal', $bulanIni)
                      ->whereYear('tanggal', $tahunIni)
                      ->where('keterangan', 'hadir')
                      ->whereNotNull('waktu_masuk');
            }])
            ->orderBy('total_hadir', 'desc')
            ->take(3)
            ->get();

        // Statistik admin
        $adminStats = null;
        if ($user->role === 'admin') {
            $adminStats = [
                'total_siswa'    => User::where('role', 'siswa')->count(),
                'total_guru'     => User::where('role', 'guru')->count(),
                'izin_pending'   => LeaveRequest::where('status', 'pending')->count(),
                'hadir_hari_ini' => Attendance::where('tanggal', Carbon::today())
                    ->where('keterangan', 'hadir')
                    ->whereNotNull('waktu_masuk')
                    ->count(),
                'izin_hari_ini'  => Attendance::where('tanggal', Carbon::today())
                    ->whereIn('keterangan', ['izin', 'sakit', 'cuti'])
                    ->count(),
            ];
        }

        // ============ LOGIKA BADGE / LENCANA ============
        $badges = [];

        // 1. Early Bird (Pernah absen sebelum jam 07:00 bulan ini)
        $earlyBird = Attendance::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->whereTime('waktu_masuk', '<', '07:00:00')
            ->exists();
        if ($earlyBird) {
            $badges[] = ['name' => 'Early Bird', 'icon' => '🌅', 'color' => 'amber', 'desc' => 'Absen sebelum jam 07:00'];
        }

        // 2. Perfect Attendance (Kehadiran 100% bulan ini)
        if ($persentaseKehadiran == 100 && $hariSekolahBulanIni > 0) {
            $badges[] = ['name' => 'Perfect Attendance', 'icon' => '🔥', 'color' => 'emerald', 'desc' => '100% kehadiran bulan ini'];
        }

        // 3. Music Addict (Punya lagu favorit)
        if ($user->hasLagu()) {
            $badges[] = ['name' => 'Music Addict', 'icon' => '🎵', 'color' => 'pink', 'desc' => 'Menambahkan lagu favorit'];
        }

        // 4. Top of the Class (Juara 1 di kelas)
        if ($peringkatKelas === 1) {
            $badges[] = ['name' => 'Top of the Class', 'icon' => '👑', 'color' => 'yellow', 'desc' => 'Peringkat 1 di kelas'];
        }


               return view('dashboard', compact(
            'user',
            'todayAttendance',
            'stats',
            'adminStats',
            'hariSekolahBulanIni',
            'persentaseKehadiran',
            'timeline7Hari',
            'peringkatKelas', 
            'totalSiswaKelas', 
            'top3Kelas',
            'badges' // <--- Tambahkan ini
        ));
    }

    /**
     * Hitung jumlah hari sekolah (Senin-Jumat) antara 2 tanggal.
     */
    private function hitungHariSekolah(Carbon $start, Carbon $end): int
    {
        $count = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            if ($current->isWeekday()) { // Senin-Jumat
                $count++;
            }
            $current->addDay();
        }

        return $count;
    }

    /**
     * Build timeline 7 hari terakhir.
     */
    private function buildTimeline7Hari(int $userId): array
    {
        $timeline = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);

            // Cek absensi hari itu
            $absen = Attendance::where('user_id', $userId)
                ->where('tanggal', $tanggal)
                ->first();

            // Tentukan status
            $status = 'tidak_ada';  // default
            $icon   = '—';
            $color  = 'slate';
            $label  = 'Kosong';

            if ($tanggal->isWeekend()) {
                $status = 'weekend';
                $icon   = '—';
                $color  = 'slate';
                $label  = 'Libur';
            } elseif ($absen) {
                $ket = $absen->keterangan ?? 'hadir';
                switch ($ket) {
                    case 'hadir':
                        if ($absen->waktu_masuk) {
                            $status = 'hadir';
                            $icon   = '✓';
                            $color  = 'emerald';
                            $label  = 'Hadir';
                        }
                        break;
                    case 'izin':
                        $status = 'izin';
                        $icon   = '📝';
                        $color  = 'amber';
                        $label  = 'Izin';
                        break;
                    case 'sakit':
                        $status = 'sakit';
                        $icon   = '🤒';
                        $color  = 'red';
                        $label  = 'Sakit';
                        break;
                    case 'cuti':
                        $status = 'alpha';
                        $icon   = '✗';
                        $color  = 'slate';
                        $label  = 'Alpha';
                        break;
                }
            } elseif ($tanggal->isToday()) {
                $status = 'hari_ini';
                $icon   = '⏰';
                $color  = 'blue';
                $label  = 'Hari ini';
            } else {
                // Hari kerja lampau, tidak ada absen
                $status = 'alpa';
                $icon   = '✗';
                $color  = 'slate';
                $label  = 'Tidak absen';
            }

            $timeline[] = [
                'tanggal' => $tanggal,
                'hari'    => $tanggal->translatedFormat('D'),      // Sen, Sel, ...
                'tanggal_num' => $tanggal->format('d'),
                'status'  => $status,
                'icon'    => $icon,
                'color'   => $color,
                'label'   => $label,
            ];
        }

        return $timeline;
    }
}