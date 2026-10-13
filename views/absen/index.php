<?php declare(strict_types=1); ?>
<div class="card">
    <h2>Absensi Harian — <?= e(format_tanggal($tanggal)) ?></h2>

    <form method="get" action="<?= BASE_URL ?>/absen" class="form-grid" style="margin-bottom:16px">
        <div class="form-group">
            <label>Pilih Tanggal</label>
            <input type="date" name="tanggal" value="<?= e($tanggal) ?>" max="<?= e(tanggal_hari_ini()) ?>">
        </div>
        <div class="form-group">
            <label>Filter Kelas</label>
            <select name="kelas">
                <option value="0">Semua Kelas</option>
                <?php foreach ($kelas as $k): ?>
                    <option value="<?= (int)$k['id'] ?>" <?= $filterKelas == $k['id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-ghost" type="submit">Tampilkan</button>
    </form>

    <?php if ($sudahAbsen): ?>
        <div class="alert" style="margin-bottom:12px">✳ Semua siswa sudah diabsen pada tanggal ini. Simpan lagi untuk mengubah.</div>
    <?php endif; ?>

    <?php if (!$siswa): ?>
        <p class="alert">Tidak ada siswa untuk ditampilkan. Tambahkan siswa dulu di menu Siswa.</p>
    <?php else: ?>
    <form method="post" action="<?= BASE_URL ?>/absen/simpan">
        <?= csrf_field() ?>
        <input type="hidden" name="tanggal" value="<?= e($tanggal) ?>">
        <div class="table-wrap">
            <table class="absen-table">
                <thead>
                    <tr><th>No</th><th>Nama</th><th>Status</th><th>Keterangan (S/I)</th></tr>
                </thead>
                <tbody>
                <?php foreach ($siswa as $i => $s): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($s['nama']) ?></td>
                        <td>
                            <select name="status[<?= (int)$s['id'] ?>]">
                                <option value="" <?= $s['status'] === '' ? 'selected' : '' ?>>— belum —</option>
                                <option value="H" <?= $s['status'] === 'H' ? 'selected' : '' ?>>Hadir</option>
                                <option value="S" <?= $s['status'] === 'S' ? 'selected' : '' ?>>Sakit</option>
                                <option value="I" <?= $s['status'] === 'I' ? 'selected' : '' ?>>Izin</option>
                                <option value="A" <?= $s['status'] === 'A' ? 'selected' : '' ?>>Alpha</option>
                            </select>
                        </td>
                        <td><input type="text" class="ket" name="keterangan[<?= (int)$s['id'] ?>]" value="<?= e($s['keterangan'] ?? '') ?>" maxlength="200" placeholder="opsional"></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p style="margin-top:14px">
            <button class="btn btn-success" type="submit">💾 Simpan Absensi</button>
            <button class="btn btn-ghost" type="button" onclick="window.print()">🖨 Cetak</button>
        </p>
    </form>
    <?php endif; ?>
</div>