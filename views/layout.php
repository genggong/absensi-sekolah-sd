<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($_title ?? 'Absensi SD') ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body>
<?php if ($_user): ?>
<header class="topbar">
    <div class="brand"><a href="<?= BASE_URL ?>/">📋 Absensi SD</a></div>
    <nav class="nav">
        <a href="<?= BASE_URL ?>/" class="<?= $_active === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <?php if (in_array($_user['role'], ['admin','guru'], true)): ?>
            <a href="<?= BASE_URL ?>/siswa" class="<?= $_active === 'siswa' ? 'active' : '' ?>">Siswa</a>
            <a href="<?= BASE_URL ?>/absen" class="<?= $_active === 'absen' ? 'active' : '' ?>">Absensi</a>
            <a href="<?= BASE_URL ?>/rekap" class="<?= $_active === 'rekap' ? 'active' : '' ?>">Rekap</a>
        <?php endif; ?>
        <?php if ($_user['role'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>/kelas" class="<?= $_active === 'kelas' ? 'active' : '' ?>">Kelas</a>
            <a href="<?= BASE_URL ?>/guru" class="<?= $_active === 'guru' ? 'active' : '' ?>">Guru</a>
            <a href="<?= BASE_URL ?>/pengaturan" class="<?= $_active === 'pengaturan' ? 'active' : '' ?>">Pengaturan</a>
        <?php endif; ?>
        <?php if ($_user['role'] === 'siswa'): ?>
            <a href="<?= BASE_URL ?>/rekap/siswa" class="<?= $_active === 'rekap' ? 'active' : '' ?>">Rekap Saya</a>
        <?php endif; ?>
    </nav>
    <div class="userbox">
        <span class="role-badge role-<?= e($_user['role']) ?>"><?= e($_user['role']) ?></span>
        <a href="<?= BASE_URL ?>/profil"><?= e($_user['nama']) ?></a>
        <a href="<?= BASE_URL ?>/logout" class="btn btn-small btn-danger">Keluar</a>
    </div>
</header>
<?php endif; ?>

<main class="container">
    <?php foreach ($_flash as $item): ?>
        <div class="flash flash-<?= e($item['type']) ?>"><?= e($item['message']) ?></div>
    <?php endforeach; ?>
    <?php require $viewFile; ?>
</main>

<footer class="footer">Absensi SD &mdash; <?= date('Y') ?></footer>
</body>
</html>