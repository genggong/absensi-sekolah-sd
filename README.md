# 📋 Aplikasi Absensi Sekolah Dasar

Aplikasi absensi **guru & siswa SD** berbasis web — dibuat dengan **PHP native + SQLite** (tanpa framework, tanpa dependensi eksternal). Cukup upload ke hosting, langsung jalan.

## ✨ Fitur

| Role | Kemampuan |
|---|---|
| **Admin** | Kelola kelas, guru, siswa, tahun ajaran; isi absensi; rekap; lihat semua data |
| **Guru** | Lihat & filter siswa, isi absensi harian, rekap bulanan |
| **Siswa** | Dashboard kehadiran pribadi, rekap & riwayat sendiri |

- Absensi harian: **Hadir (H)**, **Sakit (S)**, **Izin (I)**, **Alpha (A)** + keterangan
- Rekap per bulan per kelas (jumlah H/S/I/A)
- Dashboard ringkasan 7 hari terakhir
- Filter kelas & tanggal
- Cetak (print) rekap & absen harian
- Ganti password sendiri
- Tahun ajaran aktif (contoh: 2025/2026)

## 🔐 Keamanan

- PDO prepared statement → anti **SQL injection**
- `password_hash()` bcrypt → password tidak pernah tersimpan plain
- Session regenerate saat login + cookie `httponly`/`samesite`
- CSRF token di semua form POST
- Output escaping `htmlspecialchars()` → anti **XSS**
- Proteksi `.htaccess`: file DB & folder internal diblokir

## 🚀 Instalasi (hosting mana pun — shared hosting OK)

1. **Download / clone repo**: `git clone https://github.com/genggong/absensi-sekolah-sd.git`
2. **Upload** isi folder ke web root (public_html / htdocs / www).
3. Buka **`https://domain-anda/install.php`** di browser (atau jalankan `php install.php` di CLI) → database & akun admin dibuat otomatis.
4. **Hapus `install.php`**.
5. Login:
   - **Username:** `admin`
   - **Password:** `admin123` → ganti segera via menu Profil.

> Butuh PHP 8.0+ dengan ekstensi **pdo_sqlite** (biasanya sudah aktif di hosting modern). Aktifkan ekstensi bila belum.

## 📁 Struktur

```
absen-sekolah-sd/
├── index.php          # front controller + router
├── install.php        # instalasi satu-klik (hapus setelah dipakai)
├── .htaccess          # rewrite + proteksi folder sensitif
├── core/              # Database, Auth, Router, Helper, Controller
├── controllers/       # Auth, Dashboard, Kelas, Guru, Siswa, Absensi, Rekap, Profil, Pengaturan
├── views/             # template HTML (layout + halaman)
├── assets/style.css   # styling
└── db/                # database SQLite (dibuat otomatis)
```

## 🧪 Verifikasi

- Syntax PHP lint: ✅ semua file bersih
- Migrasi DB + seed admin: ✅
- Smoke test HTTP (login → dashboard → kelas/guru/siswa → absen → rekap): ✅
- Query rekap H/S/I/A: ✅ terverifikasi

## ⚖️ Lisensi

Dibuat untuk keperluan sekolah. Bebas dipakai & dimodifikasi.