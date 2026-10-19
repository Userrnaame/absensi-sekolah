<?php
$rembg  = 'D:\laragon\bin\python\python-3.10\Scripts\rembg.exe';
$input  = 'D:\laragon\www\absensi-sekolah\tos.jpg';
$output = 'D:\laragon\www\absensi-sekolah\test-php-nobg.png';

echo "<h2>Test rembg v4</h2>";
echo "<pre>";
echo "Rembg exe : {$rembg}<br>";
echo "Rembg ada? " . (file_exists($rembg) ? '✅ YA' : '❌ TIDAK') . "<br>";
echo "Input     : {$input}<br>";
echo "Input ada? " . (file_exists($input) ? '✅ YA' : '❌ TIDAK') . "<br>";
echo "</pre>";

if (!file_exists($rembg)) {
    echo "<h3>❌ rembg.exe tidak ditemukan.</h3>";
    exit;
}
if (!file_exists($input)) {
    echo "<h3>❌ File tos.jpg tidak ada di folder project.</h3>";
    exit;
}

// Jalankan rembg.exe
$cmd = '"' . $rembg . '" i "' . $input . '" "' . $output . '" 2>&1';
echo "<pre>Menjalankan: " . htmlspecialchars($cmd) . "</pre>";

$start = microtime(true);
$result = shell_exec($cmd);
$elapsed = round(microtime(true) - $start, 2);

echo "<pre>Output:<br>" . htmlspecialchars($result ?? '') . "</pre>";
echo "<pre>Waktu: {$elapsed} detik</pre>";
echo "<h3>";
if (file_exists($output)) {
    echo "✅ BERHASIL! File dibuat: " . filesize($output) . " bytes";
    echo "<br><img src='/test-php-nobg.png?v=" . time() . "' style='max-width:400px;margin-top:20px;background:#eee;'>";
} else {
    echo "❌ GAGAL — File tidak dibuat";
}
echo "</h3>";