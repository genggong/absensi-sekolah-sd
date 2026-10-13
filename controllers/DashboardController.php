<?php
declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        $role = $user['role'];
        $data = ['_title' => 'Dashboard', '_active' => 'dashboard'];

        if ($role === 'admin' || $role === 'guru') {
            $data['jumlah_siswa'] = (int)Database::one('SELECT COUNT(*) c FROM users WHERE role="siswa"')['c'];
            $data['jumlah_guru']  = (int)Database::one('SELECT COUNT(*) c FROM users WHERE role="guru"')['c'];
            $data['jumlah_kelas'] = (int)Database::one('SELECT COUNT(*) c FROM kelas')['c'];

            $st = Database::one(
                'SELECT
                    SUM(status=\'H\') h, SUM(status=\'S\') s, SUM(status=\'I\') i, SUM(status=\'A\') a
                 FROM absensi WHERE tanggal = ?',
                [tanggal_hari_ini()]
            );
            $data['today'] = [
                'h' => (int)($st['h'] ?? 0),
                's' => (int)($st['s'] ?? 0),
                'i' => (int)($st['i'] ?? 0),
                'a' => (int)($st['a'] ?? 0),
            ];

            $data['rekap_7hari'] = Database::all(
                'SELECT tanggal,
                        SUM(status=\'H\') h, SUM(status=\'S\') s,
                        SUM(status=\'I\') i, SUM(status=\'A\') a
                 FROM absensi
                 WHERE tanggal >= date(?, "-6 days")
                 GROUP BY tanggal ORDER BY tanggal DESC',
                [tanggal_hari_ini()]
            );
        } else {
            // Siswa: ringkasan absen pribadi bulan ini
            $data['bulan_ini'] = Database::all(
                'SELECT status, COUNT(*) jml FROM absensi
                 WHERE siswa_id = ? AND strftime("%Y-%m", tanggal) = strftime("%Y-%m", "now", "localtime")
                 GROUP BY status',
                [Auth::id()]
            );
            $data['terakhir'] = Database::one(
                'SELECT tanggal, status FROM absensi
                 WHERE siswa_id = ? ORDER BY tanggal DESC LIMIT 1',
                [Auth::id()]
            );
        }

        $this->view('dashboard/index', $data);
    }
}