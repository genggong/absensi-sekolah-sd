<?php declare(strict_types=1); ?>
<?php if (in_array($_user['role'], ['admin','guru'], true)): ?>
    <div class="stats">
        <div class="stat"><div class="num"><?= (int)$jumlah_siswa ?></div><div class="lbl">Siswa</div></div>
        <div class="stat"><div class="num"><?= (int)$jumlah_guru ?></div><div class="lbl">Guru</div></div>
        <div class="stat"><div class="num"><?= (int)$jumlah_kelas ?></div><div class="lbl">Kelas</div></div>
        <div class="stat stat-h"><div class="num"><?= $today['h'] ?></div><div class="lbl">Hadir Hari Ini</div></div>
        <div class="stat stat-s"><div class="num"><?= $today['s'] ?></div><div class="lbl">Sakit</div></div>
        <div class="stat stat-i"><div class="num"><?= $today['i'] ?></div><div class="lbl">Izin</div></div>
        <div class="stat stat-a"><div class="num"><?= $today['a'] ?></div><div class="lbl">Alpha</div></div>
    </div>

    <div class="card">
        <h2>Ringkasan 7 Hari Terakhir</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Tanggal</th><th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpha</th><th>Total</th></tr></thead>
                <tbody>
                <?php if (!$rekap_7hari): ?>
                    <tr><td colspan="6" style="text-align:center;color:#94a3b8">Belum ada data absensi.</td></tr>
                <?php endif; ?>
                <?php foreach ($rekap_7hari as $r): ?>
                    <tr>
                        <td><?= e(format_tanggal($r['tanggal'])) ?></td>
                        <td><?= (int)$r['h'] ?></td>
                        <td><?= (int)$r['s'] ?></td>
                        <td><?= (int)$r['i'] ?></td>
                        <td><?= (int)$r['a'] ?></td>
                        <td><?= (int)$r['h'] + (int)$r['s'] + (int)$r['i'] + (int)$r['a'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p style="margin-top:12px"><a class="btn btn-ghost btn-small" href="<?= BASE_URL ?>/absen">Isi Absensi Hari Ini &rarr;</a></p>
    </div>
<?php else: ?>
    <div class="stats">
        <?php
        $map = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];
        foreach ($bulan_ini as $b) { $map[$b['status']] = (int)$b['jml']; }
        ?>
        <div class="stat stat-h"><div class="num"><?= $map['H'] ?></div><div class="lbl">Hadir</div></div>
        <div class="stat stat-s"><div class="num"><?= $map['S'] ?></div><div class="lbl">Sakit</div></div>
        <div class="stat stat-i"><div class="num"><?= $map['I'] ?></div><div class="lbl">Izin</div></div>
        <div class="stat stat-a"><div class="num"><?= $map['A'] ?></div><div class="lbl">Alpha</div></div>
    </div>
    <div class="card">
        <h2>Kehadiran Bulan Ini</h2>
        <?php if ($terakhir): ?>
            <p>Absen terakhir: <strong><?= e(format_tanggal($terakhir['tanggal'])) ?></strong> → <?= status_label($terakhir['status']) ?></p>
        <?php else: ?>
            <p class="alert">Belum ada catatan absensi untuk Anda bulan ini.</p>
        <?php endif; ?>
        <p style="margin-top:12px"><a class="btn btn-ghost btn-small" href="<?= BASE_URL ?>/rekap/siswa">Lihat Rekap Lengkap &rarr;</a></p>
    </div>
<?php endif; ?>