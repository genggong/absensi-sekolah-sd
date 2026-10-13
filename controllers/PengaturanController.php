<?php
declare(strict_types=1);

class PengaturanController extends Controller
{
    public function index(): void
    {
        $tahun = Database::all('SELECT * FROM tahun_ajaran ORDER BY nama DESC');
        $this->view('pengaturan/index', [
            '_title' => 'Pengaturan', '_active' => 'pengaturan', 'tahun' => $tahun,
        ]);
    }

    public function tahunTambah(): void
    {
        csrf_verify();
        $nama = $this->post('nama');
        if ($nama === '' || !preg_match('/^\d{4}\/\d{4}$/', $nama)) {
            Flash::set('error', 'Format tahun ajaran: 2024/2025.');
            redirect('/pengaturan');
        }
        try {
            Database::run('INSERT INTO tahun_ajaran (nama, aktif) VALUES (?, 0)', [$nama]);
            Flash::set('success', 'Tahun ajaran ' . e($nama) . ' ditambahkan.');
        } catch (PDOException $e) {
            Flash::set('error', 'Tahun ajaran sudah ada.');
        }
        redirect('/pengaturan');
    }

    public function tahunAktif(): void
    {
        csrf_verify();
        $id = (int)$this->post('id');
        $ada = Database::one('SELECT id FROM tahun_ajaran WHERE id = ?', [$id]);
        if ($ada) {
            Database::run('UPDATE tahun_ajaran SET aktif = 0');
            Database::run('UPDATE tahun_ajaran SET aktif = 1 WHERE id = ?', [$id]);
            Flash::set('success', 'Tahun ajaran aktif diubah.');
        }
        redirect('/pengaturan');
    }
}