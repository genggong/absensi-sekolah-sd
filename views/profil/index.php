<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Profil Saya</h2>
    <table style="max-width:480px">
        <tr><th style="width:140px">Nama</th><td><?= e($_user['nama']) ?></td></tr>
        <tr><th>Username</th><td><?= e($_user['username']) ?></td></tr>
        <tr><th>Role</th><td><span class="role-badge role-<?= e($_user['role']) ?>"><?= e($_user['role']) ?></span></td></tr>
    </table>
</div>

<div class="card" style="max-width:480px">
    <h2>Ganti Password</h2>
    <form method="post" action="<?= BASE_URL ?>/profil/ganti-password">
        <?= csrf_field() ?>
        <div class="form-group" style="margin-bottom:12px"><label>Password Lama</label><input type="password" name="password_lama" required></div>
        <div class="form-group" style="margin-bottom:12px"><label>Password Baru (min 6)</label><input type="password" name="password_baru" required minlength="6"></div>
        <div class="form-group" style="margin-bottom:12px"><label>Ulangi Password Baru</label><input type="password" name="password_ulangi" required></div>
        <button class="btn" type="submit">Simpan Password</button>
    </form>
</div>