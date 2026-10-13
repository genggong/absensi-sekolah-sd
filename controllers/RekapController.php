<?php
declare(strict_types=1);

class RekapController extends Controller
{
    public function index(): void
    {
        $bulan = $_GET['bulan'] ?? date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', (string)$bulan)) {
            $bulan = date('Y-m');
        }
        $filterKelas = (int)($_GET['kelas'] ?? 0);

        $params = []; $sqlKelas = '';
        if ($filterKelas > 0) {
            $sqlKelas = 'AND u.kelas_id = ?';
            $params[] = $filterKelas;
        }

        $rows = Database::all(
            'SELECT u.id, u.nama, k.nama kelas,
                    SUM(a.status=\'H\') h, SUM(a.status=\'S\') s,
                    SUM(a.status=\'I\') i, SUM(a.status=\'A\') a
             FROM users u
             LEFT JOIN absensi a ON a.siswa_id = u.id AND strftime("%Y-%m", a.tanggal) = ?
             LEFT JOIN kelas k ON k.id = u.kelas_id
             WHERE u.role="siswa" ' . $sqlKelas . '
             GROUP BY u.id
             ORDER BY k.nama, u.nama',
            array_merge([$bulan], $params)
        );

        // Total hari efektif bulan ini (dari tanggal yang pernah diinput)
        $totalHari = (int)Database::one(
            'SELECT COUNT(DISTINCT tanggal) c FROM absensi WHERE strftime("%Y-%m", tanggal) = ?',
            [$bulan]
        )['c'];

        $kelas = Database::all('SELECT id, nama FROM kelas ORDER BY nama');

        $this->view('rekap/index', [
            '_title' => 'Rekap Absensi', '_active' => 'rekap',
            'bulan' => $bulan, 'rows' => $rows, 'kelas' => $kelas,
            'filterKelas' => $filterKelas, 'totalHari' => $totalHari,
        ]);
    }

    public function siswa(): void
    {
        // Rekap untuk siswa login: riwayat dirinya sendiri
        $bulan = $_GET['bulan'] ?? date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', (string)$bulan)) {
            $bulan = date('Y-m');
        }
        $rows = Database::all(
            'SELECT tanggal, status, keterangan FROM absensi
             WHERE siswa_id = ? AND strftime("%Y-%m", tanggal) = ?
             ORDER BY tanggal',
            [Auth::id(), $bulan]
        );
        $ringkas = [];
        foreach ($rows as $r) {
            $ringkas[$r['status']] = ($ringkas[$r['status']] ?? 0) + 1;
        }
        $this->view('rekap/siswa', [
            '_title' => 'Rekap Saya', '_active' => 'rekap',
            'bulan' => $bulan, 'rows' => $rows, 'ringkas' => $ringkas,
        ]);
    }
}