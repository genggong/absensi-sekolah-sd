<?php
declare(strict_types=1);

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->view('auth/login', ['_title' => 'Login']);
    }

    public function login(): void
    {
        csrf_verify();
        $username = $this->post('username');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            Flash::set('error', 'Username dan password wajib diisi.');
            redirect('/login');
        }
        if (!Auth::attempt($username, $password)) {
            Flash::set('error', 'Username atau password salah.');
            redirect('/login');
        }
        Flash::set('success', 'Selamat datang, ' . (Auth::user()['nama'] ?? '') . '!');
        redirect('/');
    }

    public function logout(): void
    {
        Auth::logout();
        Flash::set('success', 'Anda telah keluar.');
        redirect('/login');
    }
}