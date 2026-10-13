<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Rekap Kehadiran Saya — <?= e(date('F Y', strtotime($bulan . '-01'))) ?></h2>
    <form method="get" action="<?= BASE_URL ?>/rekap/siswa" class="form-grid" style="margin-bottom:16px;max-width:320px">
        <div class="form-group"><label>Pilih Bulan</label><input type="month" name="bulan" value="<?= e($bulan) ?>"></div>
        <button class="btn btn-ghost" type="submit">Tampilkan</button>
    </form>

    <div class="stats" style="margin-bottom:16px">
        <?php
        $warna = ['H'=>'stat-h','S'=>'stat-s','I'=>'stat-i','A'=>'stat-a'];
        $nama  = ['H'=>'Hadir','S'=>'Sakit','I'=>'Izin','A'=>'Alpha'];
        foreach ($nama as $k => $label):
        ?>
        <div class="stat <?= $warna[$k] ?>"><div class="num"><?= (int)($ringkas[$k] ?? 0) ?></div><div class="lbl"><?= $label ?></div></div>
        <?php endforeach; ?>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>No</th><th>Tanggal</th><th>Status</th><th>Keterangan</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $i => $r): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e(format_tanggal($r['tanggal'])) ?></td>
                    <td><?= status_label($r['status']) ?></td>
                    <td><?= e($r['keterangan']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td colspan="4" style="text-align:center;color:#94a3b8">Belum ada catatan bulan ini.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>