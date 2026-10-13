<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Rekap Absensi — Bulan <?= e(date('F Y', strtotime($bulan . '-01'))) ?></h2>

    <form method="get" action="<?= BASE_URL ?>/rekap" class="form-grid" style="margin-bottom:16px">
        <div class="form-group">
            <label>Bulan</label>
            <input type="month" name="bulan" value="<?= e($bulan) ?>">
        </div>
        <div class="form-group">
            <label>Kelas</label>
            <select name="kelas">
                <option value="0">Semua Kelas</option>
                <?php foreach ($kelas as $k): ?>
                    <option value="<?= (int)$k['id'] ?>" <?= $filterKelas == $k['id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-ghost" type="submit">Tampilkan</button>
        <button class="btn btn-ghost" type="button" onclick="window.print()">🖨 Cetak</button>
    </form>

    <p style="margin-bottom:14px;font-size:.85rem;color:#64748b">
        Hari absensi tercatat bulan ini: <strong><?= (int)$totalHari ?> hari</strong> &nbsp;•&nbsp; H=Hadir, S=Sakit, I=Izin, A=Alpha
    </p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>No</th><th>Nama</th><th>Kelas</th><th>H</th><th>S</th><th>I</th><th>A</th><th>Total</th></tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $i => $r): ?>
                <?php $tot = (int)$r['h'] + (int)$r['s'] + (int)$r['i'] + (int)$r['a']; ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($r['nama']) ?></td>
                    <td><?= e($r['kelas'] ?? '-') ?></td>
                    <td class="rekap-num h"><?= (int)$r['h'] ?></td>
                    <td class="rekap-num s"><?= (int)$r['s'] ?></td>
                    <td class="rekap-num i"><?= (int)$r['i'] ?></td>
                    <td class="rekap-num a"><?= (int)$r['a'] ?></td>
                    <td><strong><?= $tot ?></strong></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td colspan="8" style="text-align:center;color:#94a3b8">Belum ada data.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>