<?php declare(strict_types=1); ?>
<div class="login-wrap">
    <div class="login-box">
        <h1>📋 Absensi SD</h1>
        <p class="sub">Aplikasi absensi guru &amp; siswa sekolah dasar</p>
        <form method="post" action="<?= BASE_URL ?>/login">
            <?= csrf_field() ?>
            <div class="form-group" style="margin-bottom:12px">
                <label>Username</label>
                <input type="text" name="username" required autofocus autocomplete="username">
            </div>
            <div class="form-group" style="margin-bottom:12px">
                <label>Password</label>
                <input type="password" name="password" required autocomplete="current-password">
            </div>
            <button class="btn" type="submit">Masuk</button>
        </form>
        <p style="margin-top:14px;font-size:.75rem;color:#94a3b8">Akun default: admin / admin123</p>
    </div>
</div>