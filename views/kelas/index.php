<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Data Kelas</h2>
    <form method="post" action="<?= BASE_URL ?>/kelas/tambah" class="form-grid" style="margin-bottom:16px">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Nama Kelas (contoh: 1A, 2B, 3C)</label>
            <input type="text" name="nama" required placeholder="cth: 4A">
        </div>
        <button class="btn btn-success" type="submit">Tambah Kelas</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>No</th><th>Kelas</th><th>Jumlah Siswa</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($kelas as $i => $k): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= e($k['nama']) ?></strong></td>
                    <td><?= (int)$k['jumlah_siswa'] ?> siswa</td>
                    <td>
                        <form method="post" action="<?= BASE_URL ?>/kelas/hapus" onsubmit="return confirm('Hapus kelas <?= e($k['nama']) ?>?');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$k['id'] ?>">
                            <button class="btn btn-danger btn-small" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$kelas): ?><tr><td colspan="4" style="text-align:center;color:#94a3b8">Belum ada kelas.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>