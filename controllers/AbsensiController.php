<?php
declare(strict_types=1);

class AbsensiController extends Controller
{
    public function index(): void
    {
        $tanggal = $_GET['tanggal'] ?? tanggal_hari_ini();
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$tanggal)) {
            $tanggal = tanggal_hari_ini();
        }
        $filterKelas = (int)($_GET['kelas'] ?? 0);

        // Daftar siswa (filter kelas bila admin/guru memilih)
        $params = []; $sqlKelas = '';
        if ($filterKelas > 0) {
            $sqlKelas = 'WHERE u.kelas_id = ?';
            $params[] = $filterKelas;
        }
        $siswa = Database::all(
            'SELECT u.id, u.nama, COALESCE(a.status, "") status, a.keterangan
             FROM users u
             LEFT JOIN absensi a ON a.siswa_id = u.id AND a.tanggal = ?
             ' . $sqlKelas . '
             ORDER BY u.nama',
            array_merge([$tanggal], $params)
        );
        $kelas = Database::all('SELECT id, nama FROM kelas ORDER BY nama');
        $sudahAbsen = $this->sudahAbsenSemua($tanggal);

        $this->view('absen/index', [
            '_title' => 'Absensi Harian', '_active' => 'absen',
            'tanggal' => $tanggal, 'siswa' => $siswa, 'kelas' => $kelas,
            'filterKelas' => $filterKelas, 'sudahAbsen' => $sudahAbsen,
        ]);
    }

    public function simpan(): void
    {
        csrf_verify();
        $tanggal = $this->post('tanggal');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            Flash::set('error', 'Tanggal tidak valid.');
            redirect('/absen');
        }
        $status = $_POST['status'] ?? [];
        $keterangan = $_POST['keterangan'] ?? [];

        $valid = ['H' => true, 'S' => true, 'I' => true, 'A' => true, '' => true];
        $pdo = Database::conn();
        $pdo->beginTransaction();
        try {
            foreach ($status as $siswaIdRaw => $st) {
                $siswaId = (int)$siswaIdRaw;
                if ($siswaId <= 0 || !isset($valid[$st])) {
                    continue;
                }
                $ket = trim((string)($keterangan[$siswaIdRaw] ?? ''));
                if ($ket !== '') {
                    $ket = mb_substr($ket, 0, 200);
                }
                $ada = Database::one('SELECT id FROM absensi WHERE siswa_id = ? AND tanggal = ?', [$siswaId, $tanggal]);
                if ($ada) {
                    Database::run('UPDATE absensi SET status=?, keterangan=? WHERE id=?',
                        [$st, $ket, (int)$ada['id']]);
                } elseif ($st !== '') {
                    Database::run(
                        'INSERT INTO absensi (siswa_id, tanggal, status, keterangan, dicatat_oleh)
                         VALUES (?,?,?,?,?)',
                        [$siswaId, $tanggal, $st, $ket, Auth::id()]
                    );
                }
            }
            $pdo->commit();
            Flash::set('success', 'Absensi tanggal ' . e($tanggal) . ' disimpan.');
        } catch (PDOException $e) {
            $pdo->rollBack();
            Flash::set('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
        redirect('/absen?tanggal=' . rawurlencode($tanggal));
    }

    public function hari(): void
    {
        // Ringkasan kehadiran hari ini (dapat diakses semua role)
        $tanggal = $_GET['tanggal'] ?? tanggal_hari_ini();
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$tanggal)) {
            $tanggal = tanggal_hari_ini();
        }
        $data = Database::all(
            'SELECT u.nama, k.nama kelas, a.status, a.keterangan
             FROM users u
             LEFT JOIN absensi a ON a.siswa_id = u.id AND a.tanggal = ?
             LEFT JOIN kelas k ON k.id = u.kelas_id
             WHERE u.role="siswa"
             ORDER BY k.nama, u.nama',
            [$tanggal]
        );
        $this->view('absen/hari', [
            '_title' => 'Absensi ' . format_tanggal($tanggal),
            '_active' => 'absen',
            'tanggal' => $tanggal, 'data' => $data,
        ]);
    }

    private function sudahAbsenSemua(string $tanggal): bool
    {
        $total = (int)Database::one('SELECT COUNT(*) c FROM users WHERE role="siswa"')['c'];
        if ($total === 0) {
            return false;
        }
        $absen = (int)Database::one('SELECT COUNT(DISTINCT siswa_id) c FROM absensi WHERE tanggal = ?', [$tanggal])['c'];
        return $absen >= $total;
    }
}