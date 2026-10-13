<?php
/**
 * Instalasi cepat Aplikasi Absensi SD.
 * Cukup jalankan dari CLI:  php install.php
 * atau akses lewat browser:  <url>/install.php
 *
 * Setelah sukses, HAPUS file ini.
 */
declare(strict_types=1);

define('BASE_PATH', __DIR__);
require BASE_PATH . '/core/Database.php';

if (PHP_SAPI === 'cli') {
    echo "Menjalankan instalasi...\n";
}

try {
    $pdo = Database::conn(); // memicu migrasi + seed admin default

    $admin = Database::one('SELECT username FROM users WHERE role="admin" LIMIT 1');
    $kelas = (int)Database::one('SELECT COUNT(*) c FROM kelas')['c'];
    $ta    = Database::one('SELECT nama FROM tahun_ajaran WHERE aktif = 1 LIMIT 1');

    echo "==============================================\n";
    echo " Instalasi Absensi SD berhasil!\n";
    echo "----------------------------------------------\n";
    echo " Admin    : " . ($admin['username'] ?? '-') . "\n";
    echo " Password : admin123  (segera ganti setelah login)\n";
    echo " Kelas    : " . $kelas . "\n";
    echo " Tahun ajaran aktif: " . ($ta['nama'] ?? '-') . "\n";
    echo " Database : " . BASE_PATH . '/db/absen.db' . "\n";
    echo "==============================================\n";
    echo "Hapus file install.php sebelum dipakai.\n";

    if (PHP_SAPI !== 'cli') {
        echo '<p>Instalasi selesai. Kembali ke aplikasi: <a href="index.php" style="font-weight:bold">Buka Absensi SD &rarr;</a></p>';
    }
} catch (Throwable $e) {
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, "GAGAL: " . $e->getMessage() . "\n");
        exit(1);
    }
    http_response_code(500);
    echo 'Instalasi gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}