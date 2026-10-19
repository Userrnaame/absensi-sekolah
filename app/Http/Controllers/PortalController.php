<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon; // Import Carbon di atas biar rapi

class PortalController extends Controller
{
    /**
     * Daftar 10 kelas untuk angkatan 2026/2027.
     * Format: [kode_kelas => [nama_kelas, jurusan]]
     */
    private array $daftarKelas = [
        'xii-akl-1'  => ['XII AKL 1',  'AKL'],
        'xii-akl-2'  => ['XII AKL 2',  'AKL'],
        'xii-mplb-1' => ['XII MPLB 1', 'MPLB'],
        'xii-mplb-2' => ['XII MPLB 2', 'MPLB'],
        'xii-pm-1'   => ['XII PM 1',   'PM'],
        'xii-pm-2'   => ['XII PM 2',   'PM'],
        'xii-pplg-1' => ['XII PPLG 1', 'PPLG'],
        'xii-pplg-2' => ['XII PPLG 2', 'PPLG'],
        'xii-tjkt-1' => ['XII TJKT 1', 'TJKT'],
        'xii-bcf-1'  => ['XII BCF 1',  'BCF'],
    ];

    /**
     * Halaman utama portal — daftar kelas & Hall of Fame.
     */
    public function index()
    {
        $kelasDenganStat = [];

        foreach ($this->daftarKelas as $kode => [$nama, $jurusan]) {
            $jumlahSiswa = User::byKelas($kode)->where('role', 'siswa')->count();

            $waliKelas = User::byKelas($kode)
                ->where('role_kelas', 'wali_kelas')
                ->first();

            $ketuaKelas = User::byKelas($kode)
                ->where('role_kelas', 'ketua_kelas')
                ->first();

            $kelasDenganStat[] = [
                'nama'         => $nama,
                'kode'         => $kode,
                'jurusan'      => $jurusan,
                'jumlah_siswa' => $jumlahSiswa,
                'wali_kelas'   => $waliKelas,
                'ketua_kelas'  => $ketuaKelas,
            ];
        }

        // ============ QUERY UNTUK HALL OF FAME (PODIUM) ============
        // (Dipindahkan ke sini, karena ini untuk halaman utama)
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $topSiswa = User::where('role', 'siswa')
            ->withCount(['attendances as total_hadir' => function ($query) use ($bulanIni, $tahunIni) {
                $query->whereMonth('tanggal', $bulanIni)
                      ->whereYear('tanggal', $tahunIni)
                      ->where('keterangan', 'hadir')
                      ->whereNotNull('waktu_masuk');
            }])
            ->orderBy('total_hadir', 'desc')
            ->take(5)
            ->get();

        return view('portal.index', [
            'daftarKelas'  => $kelasDenganStat,
            'angkatan'     => 2026,
            'tahunAjaran'  => '2026/2027',
            'topSiswa'     => $topSiswa, 
        ]);
    }

    /**
     * Halaman detail kelas — struktur kepengurusan + anggota.
     */
    public function kelas(string $kodeKelas)
    {
        if (!isset($this->daftarKelas[$kodeKelas])) {
            abort(404, 'Kelas tidak ditemukan');
        }

        [$namaKelas, $jurusan] = $this->daftarKelas[$kodeKelas];

        $waliKelas = User::byKelas($kodeKelas)
            ->where('role_kelas', 'wali_kelas')
            ->first();

        // Semua siswa — urut nomor absen
        $semuaSiswa = User::byKelas($kodeKelas)
            ->where('role', 'siswa')
            ->orderBy('nomor_absen')
            ->get();

        // Pengurus — yang punya jabatan_kelas
        $pengurus = User::byKelas($kodeKelas)
            ->where('role', 'siswa')
            ->whereNotNull('jabatan_kelas')
            ->orderBy('nomor_absen')
            ->get();

        // Ketua khusus — untuk highlight
        $ketuaKelas = $pengurus->where('role_kelas', 'ketua_kelas')->first();

        return view('portal.kelas', compact(
            'namaKelas',
            'kodeKelas',
            'jurusan',
            'waliKelas',
            'ketuaKelas',
            'semuaSiswa',
            'pengurus'
        ));
    }
}