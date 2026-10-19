<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Path rembg.exe (Windows Laragon)
     */
    private string $rembgPath = 'D:\laragon\bin\python\python-3.10\Scripts\rembg.exe';

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'bio'         => 'nullable|string|max:500',
            'bio_siswa'   => 'nullable|string|max:500',
            'foto'        => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'foto_siswa'  => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'password'    => 'nullable|string|min:6|confirmed',
        ], [
            'foto.image'         => 'File foto profil harus berupa gambar.',
            'foto.mimes'         => 'Format foto profil harus JPG, JPEG, atau PNG.',
            'foto.max'           => 'Ukuran foto profil maksimal 2 MB.',
            'foto_siswa.image'   => 'File foto portal harus berupa gambar.',
            'foto_siswa.mimes'   => 'Format foto portal harus JPG, JPEG, atau PNG.',
            'foto_siswa.max'     => 'Ukuran foto portal maksimal 5 MB.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $data = [
            'name'      => $validated['name'],
            'bio'       => $validated['bio'] ?? null,
            'bio_siswa' => $validated['bio_siswa'] ?? null,
        ];

        // ============ FOTO PROFIL (avatar navbar) ============
        if ($request->hasFile('foto')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto')->store('profile', 'public');
        }

        // ============ FOTO SISWA (portal - opsi auto remove BG) ============
        if ($request->hasFile('foto_siswa')) {
            // Hapus foto lama kalau ada
            if ($user->foto_siswa) {
                Storage::disk('public')->delete($user->foto_siswa);
            }

            $folder = 'siswa/' . ($user->angkatan ?? 'umum') . '/' . ($user->kode_kelas ?? 'lainnya');
            $fotoFile     = $request->file('foto_siswa');
            $autoRemoveBg = $request->boolean('auto_remove_bg'); // ← cek checkbox

            // Pastikan folder output ada
            $outputDir = storage_path('app/public/' . $folder);
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            if ($autoRemoveBg) {
                // ====== PROSES REMOVE BACKGROUND ======
                Log::info("User {$user->nis} upload foto dengan auto-remove-bg");

                // Simpan file asli ke temp
                $tempDir = storage_path('app/temp');
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }

                $tempName = Str::random(16) . '.' . $fotoFile->getClientOriginalExtension();
                $tempPath = $tempDir . DIRECTORY_SEPARATOR . $tempName;
                $fotoFile->move($tempDir, $tempName);

                // Output PNG transparan
                $outputName = Str::random(20) . '.png';
                $outputPath = $outputDir . DIRECTORY_SEPARATOR . $outputName;

                // Panggil rembg
                $removedOk = $this->removeBackground($tempPath, $outputPath);

                if ($removedOk && file_exists($outputPath)) {
                    $data['foto_siswa'] = $folder . '/' . $outputName;
                    Log::info("Rembg sukses untuk user {$user->nis}");
                } else {
                    // Fallback: simpan foto asli
                    $fallbackName = Str::random(20) . '.' . $fotoFile->getClientOriginalExtension();
                    $fallbackPath = $outputDir . DIRECTORY_SEPARATOR . $fallbackName;

                    if (file_exists($tempPath)) {
                        copy($tempPath, $fallbackPath);
                        $data['foto_siswa'] = $folder . '/' . $fallbackName;
                    }
                    Log::warning("Rembg GAGAL untuk user {$user->nis}, pakai foto asli");
                }

                // Hapus temp
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
            } else {
                // ====== SIMPAN FOTO ASLI (TANPA REMOVE BG) ======
                Log::info("User {$user->nis} upload foto tanpa auto-remove-bg");

                $namaFile = Str::random(20) . '.' . $fotoFile->getClientOriginalExtension();
                $fotoFile->move($outputDir, $namaFile);
                $data['foto_siswa'] = $folder . '/' . $namaFile;
            }
        }


        // ============ LAGU FAVORIT ============
        if ($request->hasFile('lagu')) {
            $request->validate([
                'lagu' => 'nullable|file|mimes:mp3,mpeg,wav,ogg,m4a|max:10240', // max 10MB
                'lagu_judul' => 'nullable|string|max:255',
                'lagu_artis' => 'nullable|string|max:255',
                'lagu_start_detik' => 'nullable|integer|min:0|max:600',
            ], [
                'lagu.mimes' => 'Format lagu harus MP3, WAV, OGG, atau M4A.',
                'lagu.max' => 'Ukuran lagu maksimal 10 MB.',
            ]);

            // Hapus lagu lama
            if ($user->lagu_path) {
                Storage::disk('public')->delete($user->lagu_path);
            }

            // Simpan file upload ke temp
            $uploadedFile = $request->file('lagu');
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $tempName = 'lagu_' . $user->id . '_' . time() . '.' . $uploadedFile->getClientOriginalExtension();
            $tempPath = $tempDir . DIRECTORY_SEPARATOR . $tempName;
            $uploadedFile->move($tempDir, $tempName);

            // Path output 30-detik clip
            $laguDir = storage_path('app/public/lagu');
            if (!file_exists($laguDir)) {
                mkdir($laguDir, 0755, true);
            }

            $clipName = 'user_' . $user->id . '_' . time() . '.mp3';
            $clipPath = $laguDir . DIRECTORY_SEPARATOR . $clipName;

            // Ambil start detik (default 30)
            $startDetik = (int) $request->input('lagu_start_detik', 30);

            // Potong 30 detik dari start detik menggunakan FFmpeg
            $trimmed = $this->trimAudio($tempPath, $clipPath, $startDetik, 30);

            if ($trimmed && file_exists($clipPath)) {
                $data['lagu_path'] = 'lagu/' . $clipName;
                $data['lagu_judul'] = $validated['lagu_judul'] ?? null;
                $data['lagu_artis'] = $validated['lagu_artis'] ?? null;
                $data['lagu_start_detik'] = $startDetik;
                Log::info("Lagu user {$user->nis} berhasil di-trim dari detik {$startDetik}");
            } else {
                // Fallback: pakai file asli kalau trim gagal
                $fallbackName = 'user_' . $user->id . '_' . time() . '.' . $uploadedFile->getClientOriginalExtension();
                $fallbackPath = $laguDir . DIRECTORY_SEPARATOR . $fallbackName;

                if (file_exists($tempPath)) {
                    copy($tempPath, $fallbackPath);
                    $data['lagu_path'] = 'lagu/' . $fallbackName;
                    $data['lagu_judul'] = $validated['lagu_judul'] ?? null;
                    $data['lagu_artis'] = $validated['lagu_artis'] ?? null;
                    $data['lagu_start_detik'] = 0;
                }
                Log::warning("Trim lagu GAGAL untuk user {$user->nis}, pakai file asli");
            }

            // Hapus file temp
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }


        // ============ PASSWORD ============
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // Pesan sukses
        $pesan = 'Profil berhasil diperbarui.';
        if ($request->hasFile('foto_siswa')) {
            if ($request->boolean('auto_remove_bg')) {
                $pesan .= ' Foto portal sudah diproses (background dihapus otomatis).';
            } else {
                $pesan .= ' Foto portal disimpan tanpa hapus background.';
            }
        }

        return redirect()->route('profile.edit')->with('success', $pesan);
    }

    /**
     * Remove background pakai rembg.exe
     */
    private function removeBackground(string $input, string $output): bool
    {
        if (!file_exists($this->rembgPath)) {
            Log::error("rembg.exe tidak ditemukan di: {$this->rembgPath}");
            return false;
        }

        if (!file_exists($input)) {
            Log::error("Input file tidak ada: {$input}");
            return false;
        }

        $cmd = '"' . $this->rembgPath . '" i "' . $input . '" "' . $output . '" 2>&1';
        $result = shell_exec($cmd);

        Log::info("Rembg cmd: {$cmd}");
        Log::info("Rembg result: " . ($result ?? 'null'));

        return file_exists($output) && filesize($output) > 0;
    }

       /**
     * Potong audio 30 detik dari detik tertentu pakai FFmpeg
     */
    private function trimAudio(string $input, string $output, int $startDetik, int $durasi = 30): bool
    {
        $ffmpegPath = 'D:\laragon\bin\ffmpeg\bin\ffmpeg.exe';

        if (!file_exists($ffmpegPath)) {
            Log::error("FFmpeg tidak ditemukan di: {$ffmpegPath}");
            return false;
        }

        if (!file_exists($input)) {
            Log::error("Input audio tidak ada: {$input}");
            return false;
        }

        // Format: ffmpeg -i input -ss 00:00:30 -t 00:00:30 -acodec libmp3lame -b:a 128k output
        $cmd = '"' . $ffmpegPath . '" -i "' . $input . '" -ss ' . $startDetik . ' -t ' . $durasi . ' -acodec libmp3lame -b:a 128k -y "' . $output . '" 2>&1';

        $result = shell_exec($cmd);

        Log::info("FFmpeg trim cmd: {$cmd}");
        Log::info("FFmpeg result: " . ($result ?? 'null'));

        return file_exists($output) && filesize($output) > 0;
    }

}