<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Data Siswa</h2>

    <?php if ($_user['role'] === 'guru'): ?>
    <form method="get" action="<?= BASE_URL ?>/siswa" class="form-grid" style="margin-bottom:16px">
        <div class="form-group">
            <label>Filter Kelas</label>
            <select name="kelas">
                <option value="0">Semua Kelas</option>
                <?php foreach ($kelas as $k): ?>
                    <option value="<?= (int)$k['id'] ?>" <?= $filterKelas == $k['id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-ghost" type="submit">Filter</button>
    </form>
    <?php endif; ?>

    <?php if ($_user['role'] === 'admin'): ?>
    <form method="post" action="<?= BASE_URL ?>/siswa/tambah" class="form-grid" style="margin-bottom:16px">
        <?= csrf_field() ?>
        <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama" required></div>
        <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
        <div class="form-group"><label>Password (min 6)</label><input type="password" name="password" required minlength="6"></div>
        <div class="form-group">
            <label>Kelas</label>
            <select name="kelas_id" required>
                <option value="">— pilih kelas —</option>
                <?php foreach ($kelas as $k): ?>
                    <option value="<?= (int)$k['id'] ?>"><?= e($k['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-success" type="submit">Tambah Siswa</button>
    </form>
    <?php endif; ?>

    <div class="table-wrap">
        <table>
            <thead><tr><th>No</th><th>Nama</th><th>Username</th><th>Kelas</th><th>Status</th><?php if ($_user['role'] === 'admin'): ?><th>Aksi</th><?php endif; ?></tr></thead>
            <tbody>
            <?php foreach ($siswa as $i => $s): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($s['nama']) ?></td>
                    <td><?= e($s['username']) ?></td>
                    <td><?= e($s['kelas'] ?? '-') ?></td>
                    <td><?= isset($s['aktif']) ? ($s['aktif'] ? 'Aktif' : 'Nonaktif') : '-' ?></td>
                    <?php if ($_user['role'] === 'admin'): ?>
                    <td>
                        <form method="post" action="<?= BASE_URL ?>/siswa/hapus" onsubmit="return confirm('Hapus <?= e($s['nama']) ?>? Riwayat absennya ikut terhapus.');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                            <button class="btn btn-danger btn-small" type="submit">Hapus</button>
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php if (!$siswa): ?><tr><td colspan="6" style="text-align:center;color:#94a3b8">Belum ada siswa.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>