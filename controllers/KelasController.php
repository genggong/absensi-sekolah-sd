<?php
declare(strict_types=1);

class KelasController extends Controller
{
    public function index(): void
    {
        $kelas = Database::all(
            'SELECT k.id, k.nama,
                    (SELECT COUNT(*) FROM users u WHERE u.kelas_id = k.id AND u.role="siswa") jumlah_siswa
             FROM kelas k ORDER BY k.nama'
        );
        $this->view('kelas/index', [
            '_title' => 'Data Kelas', '_active' => 'kelas', 'kelas' => $kelas,
        ]);
    }

    public function tambah(): void
    {
        csrf_verify();
        $nama = $this->post('nama');
        if ($nama === '') {
            Flash::set('error', 'Nama kelas wajib diisi.');
            redirect('/kelas');
        }
        try {
            Database::run('INSERT INTO kelas (nama) VALUES (?)', [$nama]);
            Flash::set('success', 'Kelas "' . e($nama) . '" ditambahkan.');
        } catch (PDOException $e) {
            Flash::set('error', 'Kelas sudah ada atau gagal disimpan.');
        }
        redirect('/kelas');
    }

    public function hapus(): void
    {
        csrf_verify();
        $id = (int)$this->post('id');
        if ($id > 0) {
            $jml = (int)Database::one('SELECT COUNT(*) c FROM users WHERE kelas_id = ?', [$id])['c'];
            if ($jml > 0) {
                Flash::set('error', 'Kelas masih memiliki siswa, tidak bisa dihapus.');
            } else {
                Database::run('DELETE FROM kelas WHERE id = ?', [$id]);
                Flash::set('success', 'Kelas dihapus.');
            }
        }
        redirect('/kelas');
    }
}