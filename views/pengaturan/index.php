<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Tahun Ajaran</h2>
    <form method="post" action="<?= BASE_URL ?>/pengaturan/tahun-tambah" class="form-grid" style="margin-bottom:16px;max-width:480px">
        <?= csrf_field() ?>
        <div class="form-group"><label>Tahun Ajaran Baru (format: 2025/2026)</label><input type="text" name="nama" required pattern="\d{4}/\d{4}" placeholder="2025/2026"></div>
        <button class="btn btn-success" type="submit">Tambah</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Tahun Ajaran</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($tahun as $t): ?>
                <tr>
                    <td><strong><?= e($t['nama']) ?></strong></td>
                    <td><?= $t['aktif'] ? '<span class="badge badge-h">Aktif</span>' : '<span class="badge">Nonaktif</span>' ?></td>
                    <td>
                        <?php if (!$t['aktif']): ?>
                        <form method="post" action="<?= BASE_URL ?>/pengaturan/tahun-aktif">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                            <button class="btn btn-small btn-ghost" type="submit">Jadikan Aktif</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>