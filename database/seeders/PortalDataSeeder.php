<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PortalDataSeeder extends Seeder
{
    /**
     * Struktur kelas & jurusan.
     */
    private array $daftarKelas = [
        // ===== KELAS DATA ASLI =====
        'xii-mplb-1' => ['XII MPLB 1', 12, 'MPLB', 'Endah Kuswandani, S.Pd.'],
        'xii-akl-1'  => ['XII AKL 1',  12, 'AKL',  'Anik Triharjani'],
        'xii-pplg-2' => ['XII PPLG 2', 12, 'PPLG', 'Edi Purwanto'],

        // ===== KELAS DUMMY =====
        'xii-akl-2'  => ['XII AKL 2',  12, 'AKL',  'Dwi Lestari, S.Pd.'],
        'xii-mplb-2' => ['XII MPLB 2', 12, 'MPLB', 'Sri Wahyuni, S.Pd.'],
        'xii-pm-1'   => ['XII PM 1',   12, 'PM',   'Budi Hartono, S.Pd.'],
        'xii-pm-2'   => ['XII PM 2',   12, 'PM',   'Rina Marlina, S.Pd.'],
        'xii-pplg-1' => ['XII PPLG 1', 12, 'PPLG', 'Agus Setiawan, S.Kom.'],
        'xii-tjkt-1' => ['XII TJKT 1', 12, 'TJKT', 'Eko Prasetyo, S.Kom.'],
        'xii-bcf-1'  => ['XII BCF 1',  12, 'BCF',  'Sari Indah, S.Pd.'],
    ];

    /**
     * DATA ASLI: XII MPLB 1
     * Format: [nama, jabatan_kelas, role_kelas]
     */
    private array $mplb1 = [
        ['Alisa Nur Khasanah',         'Ketua Kelas',      'ketua_kelas'],
        ['Tania Devi Artikasari',      'Wakil Ketua',      'anggota'],
        ['Ghina Salsabila Kamila',     'Sekretaris 1',     'anggota'],
        ['Novitalia',                  'Sekretaris 2',     'anggota'],
        ['Zaskia Anandita Putri',      'Bendahara 1',      'anggota'],
        ['Nafisa Silviana',            'Bendahara 2',      'anggota'],
        ['Berliana Zazkia H',          'Sie PHB 1',        'anggota'],
        ['Syifa Della Nur Hafiza',     'Sie PHB 2',        'anggota'],
        ['Dinda Surastika',            'Sie Kebersihan 1', 'anggota'],
        ['Windy Widya Putri',          'Sie Kebersihan 2', 'anggota'],
        ['Salma Widya Rahayu',         'Sie Kebersihan 3', 'anggota'],
        ['Nisa Hapsari',               'Sie Lab 1',        'anggota'],
        ['Sekar Arum Aditia',          'Sie Lab 2',        'anggota'],
        ['Atthiya A.Z',                'Sie Perpus 1',     'anggota'],
        ['Suci Ramadhani',             'Sie Perpus 2',     'anggota'],
        ['Cantika Akmalia M.F',        null, 'anggota'],
        ['Dasya Adinda Permata',       null, 'anggota'],
        ['Dinda Aurel',                null, 'anggota'],
        ['Fadhilah Nasywa Aprilia',    null, 'anggota'],
        ['Isti Nur Hidayati',          null, 'anggota'],
        ['Karista Novalia',            null, 'anggota'],
        ['Latifah Nuri Hazna',         null, 'anggota'],
        ['Luthfiana Melani',           null, 'anggota'],
        ['Meisya Almira Bilqis',       null, 'anggota'],
        ['Mumartina',                  null, 'anggota'],
        ['Nabila Kirana Aprilia',      null, 'anggota'],
        ['Nailah Zaimah Zaparisti',    null, 'anggota'],
        ['Nathania Nirmala Zahra',     null, 'anggota'],
        ['Nurul Utsaila Filza',        null, 'anggota'],
        ['Putri Avril Riyanti',        null, 'anggota'],
        ['Rizqy Febriana',             null, 'anggota'],
        ['Hanida Rahmadani',           null, 'anggota'],
        ['Shafa Aulia Fadhilah',       null, 'anggota'],
        ['Syahrani Ekva Putri Wibawa', null, 'anggota'],
        ['Unzul Umi Nur Alfiah',       null, 'anggota'],
        ['Yolanda Chandra Vinata',     null, 'anggota'],
    ];

    /**
     * DATA ASLI: XII AKL 1
     */
    private array $akl1 = [
        ['Siskarina Cinta',            'Ketua Kelas',  'ketua_kelas'],
        ['Anysa Ramadani',             'Wakil Ketua',  'anggota'],
        ['Neysa Putri',                'Sekretaris 1', 'anggota'],
        ['Annisa Nur',                 'Sekretaris 2', 'anggota'],
        ['Khotijah',                   'Bendahara 1',  'anggota'],
        ['Dinda Tria',                 'Bendahara 2',  'anggota'],
        ['Ade Fitria Royani',          null, 'anggota'],
        ['Alfia Syifa Aprilia',        null, 'anggota'],
        ['Alice Nova Amelia',          null, 'anggota'],
        ['Amalya Farikha Putri',       null, 'anggota'],
        ['Anjani',                     null, 'anggota'],
        ['Antika Nur Azizah',          null, 'anggota'],
        ['Aurelia Caca Garneta',       null, 'anggota'],
        ['Azzahra Cahya Niaga',        null, 'anggota'],
        ['Devita Sari',                null, 'anggota'],
        ['Evika Nur Istiani',          null, 'anggota'],
        ['Fera Novita Sari',           null, 'anggota'],
        ['Haris Mahendra',             null, 'anggota'],
        ['Lavliananda Fadhilah',       null, 'anggota'],
        ['Meisya Zairina Suryaris Putri', null, 'anggota'],
        ['Muhammad Raffy Alfattah',    null, 'anggota'],
        ['Murni Sulistiyowati',        null, 'anggota'],
        ['Nikita Sesha Rahmawati',     null, 'anggota'],
        ['Novita Aurellia Putri',      null, 'anggota'],
        ['Rachma Farralin Amalia',     null, 'anggota'],
        ['Rizki Nur Aisa',             null, 'anggota'],
        ['Rizky Salma Fitriani',       null, 'anggota'],
        ['Rosyida Isnaini Habibah',    null, 'anggota'],
        ['Salwa Miftakhul Jannah',     null, 'anggota'],
        ['Sesha Andrani',              null, 'anggota'],
        ['Silvia Yonix Devinta',       null, 'anggota'],
        ['Siti Nur Qhoir',             null, 'anggota'],
        ['Syifa Raesya Putri Wandayani', null, 'anggota'],
        ['Valentine Putri Febryana',   null, 'anggota'],
        ['Zahrotun Nisa Aulia',        null, 'anggota'],
    ];

    /**
     * DATA ASLI: XII PPLG 2
     * Urut sesuai Google Sheets (nomor absen 1-36)
     * Format: [nama, jabatan_kelas, role_kelas]
     */
    private array $pplg2 = [
        ['Anastasya Ayu Sukmawati Suprobo',  'Bendahara 1',  'anggota'],
        ['Anaya Putri Keylani',              null, 'anggota'],
        ['Annida Khoirunnisa',               null, 'anggota'],
        ['Arbima Aldy Rizaldy',              null, 'anggota'],
        ['Arya Pandhu Satya',                'Wakil Ketua',  'anggota'],
        ['Asyam Muhammad Fathin',            null, 'anggota'],
        ['Bela Nur Jumilah',                 null, 'anggota'],
        ['Berlinda Dwi Kurniansyah',         null, 'anggota'],
        ['Christopher Kevin Perdamaean Siahaan', null, 'anggota'],
        ['Clarisa Oktavia Putri',            null, 'anggota'],
        ['Diah Trinovitasari',               null, 'anggota'],
        ['Elda Restiva Dirly',               null, 'anggota'],
        ['Emi Windiyanti',                   null, 'anggota'],
        ['Fanesya Zulfa Efendi',             null, 'anggota'],
        ['Farisa Shefayan',                  null, 'anggota'],
        ['Felia Tama',                       null, 'anggota'],
        ['Hana Nur Afifah',                  null, 'anggota'],
        ['Ika Salma Naimah',                 null, 'anggota'],
        ['Intan Ayu Ningsih',                null, 'anggota'],
        ['Kinanthi Dewi Maharani',           null, 'anggota'],
        ['Kinara Novita Anggraini',          null, 'anggota'],
        ['Makaila Cikal Fitrah Putri Kristanto', 'Bendahara 2', 'anggota'],
        ['Marlita Syaroh',                   null, 'anggota'],
        ['Mayriska Happy Rahmawati',         null, 'anggota'],
        ['Musa Habibu Rohman',               'Sekretaris 2', 'anggota'],
        ['Muthia Najwa Eva Zahira',          null, 'anggota'],
        ['Nabila Kasih Utami',               null, 'anggota'],
        ['Nadhif Nur Ikhsan',                'Ketua Kelas',  'ketua_kelas'],
        ['Naila Alzena Levia',               null, 'anggota'],
        ['Namira Ayudya Sabilla',            null, 'anggota'],
        ['Noviana Indah Nuraini',            null, 'anggota'],
        ['Pujiastuti Riatini',               null, 'anggota'],
        ['Rhafa Ibnu Kamil',                 null, 'anggota'],
        ['Shanti Handayani Indah Lestari',   null, 'anggota'],
        ['Yusuf Munir Hananto',              null, 'anggota'],
        ['Zacky Ahmad Abdul Malik',          'Sekretaris 1', 'anggota'],
    ];

    /**
     * Nama depan untuk generate siswa dummy.
     */
    private array $namaDepan = [
        'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hendra',
        'Indah', 'Joko', 'Kartika', 'Lina', 'Maya', 'Nanda', 'Oka', 'Putri',
        'Rani', 'Sari', 'Taufik', 'Umi', 'Vina', 'Wahyu', 'Yani', 'Zaki',
        'Anisa', 'Bagus', 'Cahya', 'Dian', 'Erna', 'Fikri', 'Gina', 'Hana',
        'Ika', 'Jihan', 'Kiki', 'Lutfi', 'Mira', 'Nadia', 'Oki', 'Pandu',
    ];

    private array $namaBelakang = [
        'Santoso', 'Wijaya', 'Kusuma', 'Pratiwi', 'Saputra', 'Hidayat',
        'Nugroho', 'Setiawan', 'Rahayu', 'Wulandari', 'Maulana', 'Firdaus',
        'Ramadhan', 'Anggraini', 'Permata', 'Safitri', 'Handoko', 'Kurniawan',
        'Lestari', 'Purnama', 'Siregar', 'Hakim', 'Susanti', 'Fauzi',
        'Mulyani', 'Prabowo', 'Astuti', 'Yuliana', 'Wibowo', 'Kartini',
    ];

    public function run(): void
    {
        $this->command->info('🚀 Mulai seeding Portal Data...');

        $nisCounter = 2026001;
        $totalSiswa = 0;

        foreach ($this->daftarKelas as $kodeKelas => [$namaKelas, $tingkat, $jurusan, $waliKelas]) {
            $this->command->info("📚 Processing {$namaKelas}...");

            // ============ 1. BUAT WALI KELAS ============
            User::create([
                'nis'            => 'G' . $nisCounter,
                'name'           => $waliKelas,
                'email'          => 'guru.' . $kodeKelas . '@smkn1pedan.sch.id',
                'password'       => Hash::make('KadansaGuru'),
                'role'           => 'guru',
                'angkatan'       => 2026,
                'tingkat'        => $tingkat,
                'kelas'          => $namaKelas,
                'kode_kelas'     => $kodeKelas,
                'jurusan'        => $jurusan,
                'role_kelas'     => 'wali_kelas',
                'jabatan_kelas'  => 'Wali Kelas',
                'bio'            => 'Wali kelas ' . $namaKelas,
            ]);

            // ============ 2. PILIH DATA SISWA ============
            if ($kodeKelas === 'xii-mplb-1') {
                $daftarSiswa = $this->mplb1;
            } elseif ($kodeKelas === 'xii-akl-1') {
                $daftarSiswa = $this->akl1;
            } elseif ($kodeKelas === 'xii-pplg-2') {
                $daftarSiswa = $this->pplg2;
            } else {
                $daftarSiswa = $this->generateSiswaDummy(36);
            }

            // ============ 3. BUAT SISWA ============
            $nomorAbsen = 1;
            foreach ($daftarSiswa as $siswa) {
                [$nama, $jabatan, $roleKelas] = $siswa;

                User::create([
                    'nis'            => (string) $nisCounter,
                    'name'           => $nama,
                    'email'          => 'siswa' . $nisCounter . '@smkn1pedan.sch.id',
                    'password'       => Hash::make('kadansa1'),
                    'role'           => 'siswa',
                    'angkatan'       => 2026,
                    'tingkat'        => $tingkat,
                    'kelas'          => $namaKelas,
                    'kode_kelas'     => $kodeKelas,
                    'jurusan'        => $jurusan,
                    'role_kelas'     => $roleKelas,
                    'jabatan_kelas'  => $jabatan,
                    'nomor_absen'    => $nomorAbsen,
                ]);

                $nisCounter++;
                $nomorAbsen++;
                $totalSiswa++;
            }

            $this->command->info("   ✅ {$namaKelas}: " . ($nomorAbsen - 1) . " siswa dibuat");
        }

        $this->command->info('');
        $this->command->info("🎉 Selesai! Total: {$totalSiswa} siswa + 10 guru wali kelas");
        $this->command->info('');
        $this->command->info('📋 Kredensial Login:');
        $this->command->info('   Siswa : NIS 2026001, Password: kadansa1');
        $this->command->info('   Guru  : NIS G2026001, Password: KadansaGuru');
        $this->command->info('   Admin : NIS ADMIN001, Password: admin123');
    }

    /**
     * Generate siswa dummy.
     */
    private function generateSiswaDummy(int $jumlah): array
    {
        $siswa = [];
        for ($i = 0; $i < $jumlah; $i++) {
            $namaDepan    = $this->namaDepan[array_rand($this->namaDepan)];
            $namaBelakang = $this->namaBelakang[array_rand($this->namaBelakang)];
            $nama = $namaDepan . ' ' . $namaBelakang;

            if ($i === 0) {
                $siswa[] = [$nama, 'Ketua Kelas', 'ketua_kelas'];
            } else {
                $siswa[] = [$nama, null, 'anggota'];
            }
        }
        return $siswa;
    }
}