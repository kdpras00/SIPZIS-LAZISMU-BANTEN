# 🕌 Sistem Zakat Terintegrasi - Laravel Web Application

## 🎯 Ringkasan

**Sistem Zakat Terintegrasi** adalah platform digital canggih berbasis web untuk pengelolaan zakat, infak, dan sedekah yang dibangun dengan **Laravel 12**. Sistem ini mendukung pencatatan, pelaporan, hingga distribusi secara online yang transparan, realtime, dan patuh syariah.

---

## 🚀 Fitur Unggulan

### 📈 Dashboard Zakat

- Statistik harian, bulanan, dan total pembayaran serta distribusi zakat
- Grafik pembayaran, tren program, dan notifikasi dashboard
- Widget ringkasan muzakki, mustahik, dan dana terkumpul

### 👥 Manajemen Pengguna & Hak Akses

- Autentikasi berbasis role: Admin dan Muzakki
- Manajemen akun muzakki, verifikasi mustahik (dengan upload dokumen)
- Edit profil pengguna, reset password, aktivitas log
- Login & registrasi dengan OTP dan integrasi email

### 💳 Pengelolaan Zakat, Infaq, dan Sedekah

- Dukungan banyak jenis zakat: mal, fitrah, profesi, pertanian, perdagangan, saham, tabungan
- Fitur kalkulator zakat otomatis berbasis harga emas/nisab terkini
- Sistem pembayaran multi-channel: Midtrans, QRIS, transfer manual
- Pembayaran dan donasi tanpa akun (guest), bukti/kwitansi digital (PDF)

### 🎯 Distribusi Zakat

- Data penyaluran by mustahik (8 asnaf), dengan tracking status distribusi
- Penyaluran dalam bentuk dana, barang, atau voucher digital
- Riwayat penerimaan bagi mustahik (dokumentasi receipt & lokasi distribusi)
- Fitur batch distribusi via upload excel/csv

### 📣 Manajemen Program & Campaign

- Campaign donasi terjadwal, sistem target dana, visualisasi progres
- Management program (pendidikan, kesehatan, sosial, ekonomi, kemanusiaan)
- Publikasi berita & artikel (with image upload)

### 📊 Laporan & Audit

- Laporan komprehensif semua aktivitas, filter periode, ekspor PDF/XLS
- Audit trail (histori akses, perubahan, aktivitas seluruh user)
- Fitur pelacakan dana dan pendistribusian secara detail

### 🔔 Sistem Notifikasi

- Notifikasi realtime pembayaran, distribusi, approval data
- Email reminder status pembayaran, approval, dan update campaign

---

## 🛠️ Stack Teknologi

| Layer         | Teknologi                                           |
| ------------- | --------------------------------------------------- |
| Backend       | Laravel 12, PHP 8.2+, REST API                      |
| Frontend      | Blade, Tailwind CSS 4, Bootstrap 5, Vite, Alpine    |
| Database      | MySQL, MariaDB                                      |
| Auth          | Laravel Auth, OTP (email/SMS), Socialite (opsional) |
| Payment       | Midtrans, dukungan QRIS & Transfer Manual           |
| PDF & Excel   | DomPDF, Spout                                       |
| Visualization | Chart.js, ApexCharts                                |
| Hosting       | Shared/VPS/Docker-ready                             |

---

## 📋 Kebutuhan Sistem

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 5.7+/MariaDB
- Web Server (Apache/Nginx)
- Akses Midtrans (opsional: SMTP/email gateway untuk OTP & notif)

---

## 🚦 Panduan Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd sistem-zakat
```

### 2. Instalasi Dependency

```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk database & payment:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_zakat
DB_USERNAME=root
DB_PASSWORD=
MIDTRANS_SERVER_KEY=isi_key
MIDTRANS_CLIENT_KEY=isi_key
MIDTRANS_IS_PRODUCTION=false
```

### 4. Migrasi & Seeder

```bash
php artisan migrate:fresh --seed
```

### 5. Build Asset

```bash
npm run build
# untuk development:
npm run dev
```

### 6. Jalankan Aplikasi

```bash
php artisan serve
# Buka http://localhost:8000
```

---

## 👤 Akun Default Testing

| Role    | Email           | Password | Hak Akses            |
| ------- | --------------- | -------- | -------------------- |
| Admin   | admin@zakat.com | password | Semua fitur          |
| Muzakki | user@zakat.com  | password | Riwayat & pembayaran |

---

## 🗄️ Struktur Database Inti

| Table              | Keterangan                    |
| ------------------ | ----------------------------- |
| `users`            | Data & role user              |
| `muzakki`          | Data wajib zakat              |
| `mustahik`         | Data penerima zakat (8 asnaf) |
| `zakat_payments`   | Pembayaran zakat              |
| `distributions`    | Distribusi zakat              |
| `programs`         | Daftar program/campaign       |
| `campaigns`        | Campaign dana                 |
| `notifications`    | Notifikasi sistem             |
| `articles`, `news` | Berita, artikel & dokumentasi |
| `audit_logs`       | Audit trail aktivitas         |

---

## 🔒 Keamanan

- Proteksi CSRF & XSS
- Hashing password Bcrypt
- Two-factor login (opsional)
- Validasi input & sanitasi file upload
- Role & permission multi-level
- Otentikasi & otorisasi via policy/gate

---

## 🧮 Kalkulator Zakat Otomatis

Jenis zakat yang didukung & rumus (dapat di-update pada menu Admin):

| Jenis Zakat               | Nisab                               | Tarif                        |
| ------------------------- | ----------------------------------- | ---------------------------- |
| Zakat Mal (Emas/Perak)    | 85 gram emas / 595 gram perak       | 2.5%                         |
| Zakat Penghasilan/Profesi | Setara 85 gram emas per tahun/bulan | 2.5%                         |
| Zakat Fitrah              | Sesuai ketentuan setempat           | Paket beras/uang             |
| Zakat Pertanian           | 653 kg gabah kering                 | 5%/10% (irigasi/tadah hujan) |
| Zakat Perdagangan         | Setara 85 gram emas                 | 2.5%                         |
| Zakat Saham/Tabungan      | Setara 85 gram emas                 | 2.5%                         |

---

## 🤲 8 Asnaf Mustahik

1. **Fakir** — Tak punya harta/penghasilan
2. **Miskin** — Penghasilan sangat terbatas
3. **Amil** — Pengelola zakat
4. **Muallaf** — Mualaf/dibimbing Islam
5. **Riqab** — Memerdekakan hamba/budak
6. **Gharim** — Terlilit utang syar'i
7. **Fisabilillah** — Dakwah/jalan Allah
8. **Ibnu Sabil** — Musafir kehabisan bekal

---

## 🔗 API Endpoint Terbaru

### Public

| Method | Endpoint                | Keterangan              |
| ------ | ----------------------- | ----------------------- |
| GET    | `/api/gold-price`       | Harga emas terbaru      |
| POST   | `/api/zakat/calculate`  | Hitung zakat otomatis   |
| GET    | `/api/programs`         | Daftar program/campaign |
| GET    | `/api/campaigns/{type}` | Campaign per kategori   |

### Authenticated

| Method | Endpoint                            | Keterangan           |
| ------ | ----------------------------------- | -------------------- |
| GET    | `/api/dashboard`                    | Data stats dashboard |
| GET    | `/api/mustahik/group`               | List mustahik group  |
| GET    | `/api/distributions/mustahik-group` | Penyaluran by group  |
| GET    | `/api/muzakki/find`                 | Cari data muzakki    |
| GET    | `/api/payments/search`              | Cari pembayaran      |

---

## 📱 Desain Responsive

- Mendukung perangkat mobile & desktop
- Navigasi sidebar collapse, menu sticky
- Form dan tabel optimize untuk layar kecil
- Preview dokumen & upload mobile-friendly

---

## ✨ Fitur UI/UX Baru

- Desain fresh, mode terang/gelap
- Status badge (pending/success/gagal)
- Loader animasi, progress bar distribusi
- Notifikasi web push (opsional)
- Preview kwitansi sebelum download/cetak

---

## 🔍 Testing & QA

```bash
php artisan test
php artisan test --coverage
npm run test
```

---

## 🚀 Deployment

1. **Konfig Proksi & Env**

   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-anda.com
   ```

2. **Keamanan dan Optimasi**

   ```bash
   php artisan key:generate
   php artisan optimize
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Migrasi & Seed Data**

   ```bash
   php artisan migrate --force
   ```

4. **Permission Folder**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

---

## 🙌 Kontribusi

1. Fork repo
2. Branch feature: `git checkout -b fitur/BerikanNamaFitur`
3. Commit: `git commit -m 'fitur: Tambah fitur baru'`
4. Push: `git push origin fitur/BerikanNamaFitur`
5. Pull Request via GitHub

---

## 📄 Lisensi

Dirilis di bawah [MIT License](LICENSE) — lihat file LICENSE untuk detail.

---

## 💬 Bantuan & Kontak

- Buat issue pada repo
- Hubungi tim via kontak di dokumentasi
- Baca panduan & wiki pengguna

---

**Dibangun dengan ketulusan untuk komunitas, memudahkan urusan zakat, infak, dan sedekah secara modern, amanah, dan efisien.**
# SIPZIS-LAZISMU-BANTEN
# SIPZIS-LAZISMU-BANTEN
