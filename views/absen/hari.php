<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Kehadiran — <?= e(format_tanggal($tanggal)) ?></h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>No</th><th>Nama</th><th>Kelas</th><th>Status</th><th>Keterangan</th></tr></thead>
            <tbody>
            <?php foreach ($data as $i => $d): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($d['nama']) ?></td>
                    <td><?= e($d['kelas'] ?? '-') ?></td>
                    <td><?= $d['status'] ? status_label($d['status']) : '<span class="badge">Belum</span>' ?></td>
                    <td><?= e($d['keterangan'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$data): ?><tr><td colspan="5" style="text-align:center;color:#94a3b8">Tidak ada data.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
    <p style="margin-top:14px"><button class="btn btn-ghost" onclick="window.print()">🖨 Cetak</button></p>
</div>