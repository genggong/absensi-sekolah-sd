<?php
declare(strict_types=1);

final class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function attempt(string $username, string $password): bool
    {
        $user = Database::one('SELECT * FROM users WHERE username = ? AND aktif = 1', [$username]);
        if ($user === null || !password_verify($password, $user['password'])) {
            return false;
        }
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'     => (int)$user['id'],
            'username' => $user['username'],
            'role'   => $user['role'],
            'nama'   => $user['nama'],
            'kelas_id' => $user['kelas_id'] !== null ? (int)$user['kelas_id'] : null,
        ];
        return true;
    }

    public static function user(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function id(): int
    {
        return (int)($_SESSION['user']['id'] ?? 0);
    }

    public static function role(): string
    {
        return $_SESSION['user']['role'] ?? '';
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function requireLogin(string $redirect = '/login'): void
    {
        if (!self::check()) {
            header('Location: ' . BASE_URL . $redirect);
            exit;
        }
    }

    public static function requireRole(array $roles, string $redirect = '/'): void
    {
        self::requireLogin('/login');
        if (!in_array(self::role(), $roles, true)) {
            Flash::set('error', 'Anda tidak punya akses ke halaman ini.');
            header('Location: ' . BASE_URL . $redirect);
            exit;
        }
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }
}