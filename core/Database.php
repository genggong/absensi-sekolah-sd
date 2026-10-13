<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $pdo = null;

    public static function conn(): PDO
    {
        if (self::$pdo === null) {
            $dir = BASE_PATH . '/db';
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            $file = $dir . '/absen.db';
            self::$pdo = new PDO('sqlite:' . $file, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            self::$pdo->exec('PRAGMA journal_mode = WAL; PRAGMA foreign_keys = ON;');
            self::migrate(self::$pdo);
        }
        return self::$pdo;
    }

    /** Skema database; idempotent. */
    private static function migrate(PDO $pdo): void
    {
        $sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS kelas (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    nama        TEXT NOT NULL UNIQUE
);
CREATE TABLE IF NOT EXISTS tahun_ajaran (
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    nama    TEXT NOT NULL UNIQUE,
    aktif   INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS users (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    username    TEXT NOT NULL UNIQUE,
    password    TEXT NOT NULL,
    role        TEXT NOT NULL CHECK (role IN ('admin','guru','siswa')),
    nama        TEXT NOT NULL,
    kelas_id    INTEGER,
    tahun_ajaran_id INTEGER,
    aktif       INTEGER NOT NULL DEFAULT 1,
    created_at  TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    FOREIGN KEY (kelas_id) REFERENCES kelas(id),
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id)
);
CREATE TABLE IF NOT EXISTS absensi (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    siswa_id    INTEGER NOT NULL,
    tanggal     TEXT NOT NULL,
    status      TEXT NOT NULL CHECK (status IN ('H','S','I','A')),
    keterangan  TEXT DEFAULT '',
    dicatat_oleh INTEGER,
    created_at  TEXT NOT NULL DEFAULT (datetime('now','localtime')),
    UNIQUE (siswa_id, tanggal),
    FOREIGN KEY (siswa_id) REFERENCES users(id),
    FOREIGN KEY (dicatat_oleh) REFERENCES users(id)
);
CREATE INDEX IF NOT EXISTS idx_absensi_tanggal ON absensi(tanggal);
SQL;
        $pdo->exec($sql);

        // Seed admin default + tahun ajaran aktif bila kosong
        $st = $pdo->query('SELECT COUNT(*) c FROM users WHERE role="admin"');
        if ((int)$st->fetch()['c'] === 0) {
            $ins = $pdo->prepare('INSERT INTO users (username, password, role, nama) VALUES (?,?,?,?)');
            $ins->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT), 'admin', 'Administrator']);
        }
        $st = $pdo->query('SELECT COUNT(*) c FROM tahun_ajaran');
        if ((int)$st->fetch()['c'] === 0) {
            $tahun = (int)date('Y');
            $pdo->prepare('INSERT INTO tahun_ajaran (nama, aktif) VALUES (?, 1)')
                ->execute([$tahun . '/' . ($tahun + 1)]);
        }
    }

    /** Helper: query semua baris. */
    public static function all(string $sql, array $params = []): array
    {
        $st = self::conn()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    /** Helper: satu baris. */
    public static function one(string $sql, array $params = []): ?array
    {
        $st = self::conn()->prepare($sql);
        $st->execute($params);
        $row = $st->fetch();
        return $row === false ? null : $row;
    }

    /** Helper: eksekusi & kembalikan lastInsertId. */
    public static function run(string $sql, array $params = []): int
    {
        $pdo  = self::conn();
        $st   = $pdo->prepare($sql);
        $st->execute($params);
        return (int)$pdo->lastInsertId();
    }
}