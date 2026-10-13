<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Data Guru</h2>
    <form method="post" action="<?= BASE_URL ?>/guru/tambah" class="form-grid" style="margin-bottom:16px">
        <?= csrf_field() ?>
        <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama" required></div>
        <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
        <div class="form-group"><label>Password (min 6)</label><input type="password" name="password" required minlength="6"></div>
        <button class="btn btn-success" type="submit">Tambah Guru</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>No</th><th>Nama</th><th>Username</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($guru as $i => $g): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($g['nama']) ?></td>
                    <td><?= e($g['username']) ?></td>
                    <td><?= $g['aktif'] ? 'Aktif' : 'Nonaktif' ?></td>
                    <td>
                        <form method="post" action="<?= BASE_URL ?>/guru/hapus" onsubmit="return confirm('Hapus <?= e($g['nama']) ?>?');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$g['id'] ?>">
                            <button class="btn btn-danger btn-small" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$guru): ?><tr><td colspan="5" style="text-align:center;color:#94a3b8">Belum ada guru.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>