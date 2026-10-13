<?php
declare(strict_types=1);

abstract class Controller
{
    protected function view(string $template, array $data = []): void
    {
        $data['_user']    = Auth::user();
        $data['_flash']   = Flash::pull();
        $data['_title']   = $data['_title'] ?? 'Absensi SD';
        $data['_active']  = $data['_active'] ?? '';

        extract($data, EXTR_SKIP);
        $layoutFile = BASE_PATH . '/views/layout.php';
        $viewFile   = BASE_PATH . '/views/' . $template . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            exit('View tidak ditemukan: ' . $template);
        }
        require $layoutFile;
    }

    protected function post(string $key, string $default = ''): string
    {
        $v = $_POST[$key] ?? $default;
        return is_string($v) ? trim($v) : $default;
    }
}