<?php
declare(strict_types=1);

class SiswaController extends Controller
{
    public function index(): void
    {
        $role = Auth::role();
        if ($role === 'guru') {
            $filterKelas = (int)($_GET['kelas'] ?? 0);
            $params = [];
            $sql = 'SELECT u.id, u.username, u.nama, k.nama kelas FROM users u
                    LEFT JOIN kelas k ON k.id = u.kelas_id
                    WHERE u.role="siswa"';
            if ($filterKelas > 0) {
                $sql .= ' AND u.kelas_id = ?';
                $params[] = $filterKelas;
            }
            $sql .= ' ORDER BY k.nama, u.nama';
            $siswa = Database::all($sql, $params);
        } else {
            $siswa = Database::all(
                'SELECT u.id, u.username, u.nama, k.nama kelas, u.aktif
                 FROM users u LEFT JOIN kelas k ON k.id = u.kelas_id
                 WHERE u.role="siswa" ORDER BY k.nama, u.nama'
            );
        }
        $kelas = Database::all('SELECT id, nama FROM kelas ORDER BY nama');

        $this->view('siswa/index', [
            '_title'  => 'Data Siswa',
            '_active' => 'siswa',
            'siswa'   => $siswa,
            'kelas'   => $kelas,
            'filterKelas' => $filterKelas ?? 0,
        ]);
    }

    public function tambah(): void
    {
        csrf_verify();
        $nama     = $this->post('nama');
        $username = $this->post('username');
        $password = $_POST['password'] ?? '';
        $kelasId  = (int)$this->post('kelas_id');

        if ($nama === '' || $username === '' || $password === '' || $kelasId <= 0) {
            Flash::set('error', 'Nama, username, password, dan kelas wajib diisi.');
            redirect('/siswa');
        }
        if (strlen($password) < 6) {
            Flash::set('error', 'Password minimal 6 karakter.');
            redirect('/siswa');
        }
        // Ambil tahun ajaran aktif
        $ta = Database::one('SELECT id FROM tahun_ajaran WHERE aktif = 1 LIMIT 1');

        try {
            Database::run(
                'INSERT INTO users (username, password, role, nama, kelas_id, tahun_ajaran_id, aktif)
                 VALUES (?,?,?,?,?,?,1)',
                [$username, password_hash($password, PASSWORD_DEFAULT), 'siswa', $nama,
                 $kelasId, $ta ? (int)$ta['id'] : null]
            );
            Flash::set('success', 'Siswa "' . e($nama) . '" ditambahkan.');
        } catch (PDOException $e) {
            Flash::set('error', 'Username sudah dipakai.');
        }
        redirect('/siswa');
    }

    public function hapus(): void
    {
        csrf_verify();
        $id = (int)$this->post('id');
        if ($id > 0) {
            Database::run('DELETE FROM absensi WHERE siswa_id = ?', [$id]);
            Database::run('DELETE FROM users WHERE id = ? AND role="siswa"', [$id]);
            Flash::set('success', 'Siswa dihapus.');
        }
        redirect('/siswa');
    }
}