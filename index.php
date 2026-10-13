<?php
/**
 * Aplikasi Absensi Sekolah Dasar (Guru & Siswa)
 * Arsitektur: PHP native + SQLite (file db/absen.db), tanpa dependensi eksternal.
 *
 * Alur:
 *  - index.php         router utama (front controller)
 *  - core/             engine: DB, Session, Auth, Helper, Controller base
 *  - controllers/      logika per halaman
 *  - views/            template HTML (layout + partial)
 *  - db/               file SQLite
 *  - assets/           css/js
 *
 * Keamanan: PDO prepared statement (anti SQLi), password_hash (bcrypt),
 * session regenerate, CSRF token, output escaping.
 */
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');            // produksi: jangan tampilkan error mentah
ini_set('log_errors', '1');

date_default_timezone_set('Asia/Makassar');

define('BASE_PATH', __DIR__);
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\') ?: '');

require BASE_PATH . '/core/Database.php';
require BASE_PATH . '/core/Auth.php';
require BASE_PATH . '/core/Helper.php';
require BASE_PATH . '/core/Controller.php';
require BASE_PATH . '/core/Router.php';

// --- Autoload controllers (nama file = nama class) ---
foreach (glob(BASE_PATH . '/controllers/*.php') ?: [] as $file) {
    require_once $file;
}

Router::dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));