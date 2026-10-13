<?php
declare(strict_types=1);

final class Flash
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $type, string $message): void
    {
        self::start();
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }

    public static function pull(): array
    {
        self::start();
        $items = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $items;
    }
}

/** CSRF token (per session). */
function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('Sesi berakhir atau token tidak valid. Silakan kembali dan coba lagi.');
    }
}

/** Escape output HTML (anti-XSS). */
function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function old(string $key, string $default = ''): string
{
    return (string)($_POST[$key] ?? $default);
}

function tanggal_hari_ini(): string
{
    return date('Y-m-d');
}

function format_tanggal(string $tgl): string
{
    $ts = strtotime($tgl);
    if ($ts === false) {
        return $tgl;
    }
    $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return $hari[(int)date('w', $ts)] . ', ' . date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function status_label(string $status): string
{
    return match ($status) {
        'H' => '<span class="badge badge-h">Hadir</span>',
        'S' => '<span class="badge badge-s">Sakit</span>',
        'I' => '<span class="badge badge-i">Izin</span>',
        'A' => '<span class="badge badge-a">Alpha</span>',
        default => '<span class="badge">-</span>',
    };
}