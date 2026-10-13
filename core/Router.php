<?php
declare(strict_types=1);

final class Router
{
    /** Route table: method, pola, [Controller, action, role yg diizinkan] */
    private static array $routes = [
        ['GET',  '/login',        'AuthController', 'loginForm',      ['guest']],
        ['POST', '/login',        'AuthController', 'login',          ['guest']],
        ['GET',  '/logout',       'AuthController', 'logout',         ['*']],

        ['GET',  '/',             'DashboardController', 'index',     ['*']],

        ['GET',  '/kelas',        'KelasController', 'index',         ['admin']],
        ['POST', '/kelas/tambah', 'KelasController', 'tambah',        ['admin']],
        ['POST', '/kelas/hapus',  'KelasController', 'hapus',         ['admin']],

        ['GET',  '/guru',         'GuruController', 'index',          ['admin']],
        ['POST', '/guru/tambah',  'GuruController', 'tambah',         ['admin']],
        ['POST', '/guru/hapus',   'GuruController', 'hapus',          ['admin']],

        ['GET',  '/siswa',        'SiswaController', 'index',         ['admin','guru']],
        ['POST', '/siswa/tambah', 'SiswaController', 'tambah',        ['admin']],
        ['POST', '/siswa/hapus',  'SiswaController', 'hapus',         ['admin']],

        ['GET',  '/absen',        'AbsensiController', 'index',       ['admin','guru']],
        ['POST', '/absen/simpan', 'AbsensiController', 'simpan',      ['admin','guru']],
        ['GET',  '/absen/hari',   'AbsensiController', 'hari',        ['admin','guru','siswa']],

        ['GET',  '/rekap',        'RekapController', 'index',         ['admin','guru']],
        ['GET',  '/rekap/siswa',  'RekapController', 'siswa',         ['admin','guru','siswa']],

        ['GET',  '/profil',       'ProfilController', 'index',        ['*']],
        ['POST', '/profil/ganti-password', 'ProfilController', 'gantiPassword', ['*']],
        ['GET',  '/pengaturan',   'PengaturanController', 'index',    ['admin']],
        ['POST', '/pengaturan/tahun-tambah', 'PengaturanController', 'tahunTambah', ['admin']],
        ['POST', '/pengaturan/tahun-aktif', 'PengaturanController', 'tahunAktif', ['admin']],
    ];

    public static function dispatch(string $method, string $path): void
    {
        Auth::start();
        Flash::start();
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        foreach (self::$routes as [$m, $pattern, $ctrl, $action, $roles]) {
            if ($m !== $method) {
                continue;
            }
            if (!preg_match('#^' . $pattern . '$#', $path)) {
                continue;
            }

            // Otentikasi & otorisasi
            if (in_array('guest', $roles, true)) {
                if (Auth::check()) {
                    redirect('/');
                }
            } else {
                if (!Auth::check()) {
                    redirect('/login');
                }
                if (!in_array('*', $roles, true) && !in_array(Auth::role(), $roles, true)) {
                    Flash::set('error', 'Anda tidak punya akses ke halaman ini.');
                    redirect('/');
                }
            }

            $class = $ctrl;
            if (!class_exists($class)) {
                http_response_code(500);
                exit('Controller tidak ditemukan: ' . $class);
            }
            $instance = new $class();
            if (!method_exists($instance, $action)) {
                http_response_code(500);
                exit('Action tidak ditemukan: ' . $action);
            }
            $instance->$action();
            return;
        }

        http_response_code(404);
        echo '404 - Halaman tidak ditemukan.';
    }
}