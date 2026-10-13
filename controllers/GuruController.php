<?php
declare(strict_types=1);

class GuruController extends Controller
{
    public function index(): void
    {
        $guru = Database::all('SELECT id, username, nama, aktif FROM users WHERE role="guru" ORDER BY nama');
        $this->view('guru/index', [
            '_title' => 'Data Guru', '_active' => 'guru', 'guru' => $guru,
        ]);
    }

    public function tambah(): void
    {
        csrf_verify();
        $nama     = $this->post('nama');
        $username = $this->post('username');
        $password = $_POST['password'] ?? '';

        if ($nama === '' || $username === '' || $password === '') {
            Flash::set('error', 'Nama, username, dan password wajib diisi.');
            redirect('/guru');
        }
        if (strlen($password) < 6) {
            Flash::set('error', 'Password minimal 6 karakter.');
            redirect('/guru');
        }
        try {
            Database::run(
                'INSERT INTO users (username, password, role, nama, aktif)
                 VALUES (?,?,?,?,1)',
                [$username, password_hash($password, PASSWORD_DEFAULT), 'guru', $nama]
            );
            Flash::set('success', 'Guru "' . e($nama) . '" ditambahkan.');
        } catch (PDOException $e) {
            Flash::set('error', 'Username sudah dipakai.');
        }
        redirect('/guru');
    }

    public function hapus(): void
    {
        csrf_verify();
        $id = (int)$this->post('id');
        if ($id > 0) {
            Database::run('DELETE FROM users WHERE id = ? AND role="guru"', [$id]);
            Flash::set('success', 'Guru dihapus.');
        }
        redirect('/guru');
    }
}