<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Tampilkan halaman absensi.
     */
    public function index()
    {
        $user = Auth::user();
        $setting = SchoolSetting::current();

        // Cek absensi hari ini
        $today = Attendance::where('user_id', $user->id)
            ->where('tanggal', Carbon::today())
            ->first();

        $onLeave = ($today && $today->keterangan !== 'hadir')
            || LeaveRequest::hasApprovedCovering($user->id, Carbon::today());

        $leaveKeterangan = $onLeave
            ? ($today?->keterangan && $today->keterangan !== 'hadir'
                ? $today->keterangan
                : optional(
                    LeaveRequest::where('user_id', $user->id)
                        ->where('status', 'disetujui')
                        ->where('tanggal_mulai', '<=', Carbon::today())
                        ->where('tanggal_selesai', '>=', Carbon::today())
                        ->first()
                )->jenis)
            : null;

        return view('attendance.index', compact('user', 'setting', 'today', 'onLeave', 'leaveKeterangan'));
    }

    /**
     * Simpan absensi (masuk atau pulang).
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'required|string',      // base64 dari canvas
            'tipe' => 'required|in:masuk,pulang',
        ]);

        $user = Auth::user();
        $setting = SchoolSetting::current();

        $todayLeave = Attendance::where('user_id', $user->id)
            ->where('tanggal', Carbon::today())
            ->whereIn('keterangan', ['izin', 'sakit', 'cuti'])
            ->first();

        if ($todayLeave || LeaveRequest::hasApprovedCovering($user->id, Carbon::today())) {
            $jenis = $todayLeave?->keterangan ?? 'izin';
            return response()->json([
                'success' => false,
                'message' => 'Anda sedang ' . $jenis . ' hari ini. Absensi GPS dikunci.',
            ], 422);
        }

        // Hitung jarak dengan rumus Haversine
        $jarak = $this->hitungJarak(
            $setting->latitude,
            $setting->longitude,
            $request->latitude,
            $request->longitude
        );

        // Cek apakah dalam radius
        $dalamRadius = $jarak <= $setting->radius_meters;
        $status = $dalamRadius ? 'valid' : 'luar_area';

        // Cari atau buat attendance hari ini
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => Carbon::today(),
            ],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'jarak_meter' => $jarak,
            ]
        );

        // Simpan foto base64 ke file
        $fotoPath = $this->simpanFotoBase64($request->foto, $user->id, $request->tipe);

        // Kalau di luar area, tolak
        if (!$dalamRadius) {
            return response()->json([
                'success' => false,
                'message' => "Anda berada di luar area sekolah. Jarak Anda: {$jarak} meter (maksimal {$setting->radius_meters} meter).",
                'jarak' => $jarak,
            ], 422);
        }

        // Proses absen masuk / pulang
        if ($request->tipe === 'masuk') {
            if ($attendance->waktu_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah absen masuk hari ini.',
                ], 422);
            }
            $attendance->waktu_masuk = now();
            $attendance->foto_masuk = $fotoPath;
            $attendance->status_masuk = $status;
            $attendance->keterangan = 'hadir';
            $attendance->latitude = $request->latitude;
            $attendance->longitude = $request->longitude;
            $attendance->jarak_meter = $jarak;
        } else {
            if (!$attendance->waktu_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum absen masuk hari ini.',
                ], 422);
            }
            if ($attendance->waktu_pulang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah absen pulang hari ini.',
                ], 422);
            }
            $attendance->waktu_pulang = now();
            $attendance->foto_pulang = $fotoPath;
            $attendance->status_pulang = $status;
        }

        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => $request->tipe === 'masuk'
                ? 'Absen masuk berhasil! Jarak: ' . $jarak . ' meter.'
                : 'Absen pulang berhasil! Jarak: ' . $jarak . ' meter.',
            'jarak' => $jarak,
        ]);
    }


    /**
     * Tampilkan riwayat absensi user yang login.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $query = Attendance::where('user_id', $user->id);

        // Filter by bulan (opsional)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $attendances = $query->orderBy('tanggal', 'desc')->paginate(15);

        $base = Attendance::where('user_id', $user->id);
        $totalHadir = (clone $base)->where('keterangan', 'hadir')->whereNotNull('waktu_masuk')->count();
        $totalTerlambat = (clone $base)->where('keterangan', 'hadir')
            ->whereNotNull('waktu_masuk')
            ->whereTime('waktu_masuk', '>', SchoolSetting::current()->jam_masuk)->count();
        $totalIzin = (clone $base)->where('keterangan', 'izin')->count();
        $totalSakit = (clone $base)->where('keterangan', 'sakit')->count();
        $totalCuti = (clone $base)->where('keterangan', 'cuti')->count();

        return view('attendance.history', compact(
            'attendances',
            'totalHadir',
            'totalTerlambat',
            'totalIzin',
            'totalSakit',
            'totalCuti'
        ));
    }



    /**
     * Hitung jarak antara 2 koordinat (meter) — Rumus Haversine.
     */
    private function hitungJarak($lat1, $lon1, $lat2, $lon2): int
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round($earthRadius * $c);
    }

    /**
     * Simpan foto base64 ke storage.
     */
    private function simpanFotoBase64(string $base64, int $userId, string $tipe): string
    {
        // Buang prefix "data:image/jpeg;base64,"
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        $filename = 'absensi/' . $userId . '_' . now()->format('Ymd_His') . '_' . $tipe . '.jpg';

        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }
}
