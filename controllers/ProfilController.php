<?php
declare(strict_types=1);

class ProfilController extends Controller
{
    public function index(): void
    {
        $this->view('profil/index', ['_title' => 'Profil Saya', '_active' => 'profil']);
    }

    public function gantiPassword(): void
    {
        csrf_verify();
        $lama   = $_POST['password_lama'] ?? '';
        $baru   = $_POST['password_baru'] ?? '';
        $ulangi = $_POST['password_ulangi'] ?? '';

        if ($baru !== $ulangi) {
            Flash::set('error', 'Password baru tidak sama.');
            redirect('/profil');
        }
        if (strlen($baru) < 6) {
            Flash::set('error', 'Password baru minimal 6 karakter.');
            redirect('/profil');
        }

        $user = Database::one('SELECT password FROM users WHERE id = ?', [Auth::id()]);
        if ($user === null || !password_verify($lama, $user['password'])) {
            Flash::set('error', 'Password lama salah.');
            redirect('/profil');
        }

        Database::run('UPDATE users SET password = ? WHERE id = ?',
            [password_hash($baru, PASSWORD_DEFAULT), Auth::id()]);
        Flash::set('success', 'Password berhasil diganti.');
        redirect('/profil');
    }
}