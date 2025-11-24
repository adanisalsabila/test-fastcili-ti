<p align="center">
  <img src="public\banner.png" alt="Fastcili-TI Banner">
</p>

<h1 align="center">Fastcili-TI</h1>
<p align="center"><i>“Sistem Manajemen Pelaporan dan Perbaikan Fasilitas Kampus Politeknik Negeri Malang”</i></p>

<p align="center">
  <img src="https://img.shields.io/github/license/koctaa04/PBL_Fastcili-TI?style=flat-square" alt="License">
  <img src="https://img.shields.io/github/languages/top/koctaa04/PBL_Fastcili-TI?style=flat-square" alt="Top Language">
  <img src="https://img.shields.io/badge/laravel-10-red?style=flat-square" alt="Laravel">
  <img src="https://img.shields.io/badge/status-active-brightgreen?style=flat-square" alt="Status">
</p>

---

## 📝 Deskripsi Singkat

**Fastcili-TI** adalah sistem manajemen pelaporan dan perbaikan fasilitas kampus berbasis web. Sistem ini dibangun untuk meningkatkan efisiensi pelaporan, pengelolaan fasilitas, serta pengambilan keputusan prioritas perbaikan berdasarkan laporan yang masuk.

Sistem ini dapat digunakan oleh seluruh warga kampus, termasuk mahasiswa, dosen, dan tenaga kependidikan.

---

## 🚀 Fitur Utama

-   ✅ Manajemen data **gedung**, **ruangan**, dan **fasilitas** kampus dengan mudah.
-   📢 Semua user kampus dapat **melaporkan kerusakan** atau **mendukung laporan yang sudah ada**.
-   🔔 Notifikasi untuk pelapor saat status laporan mereka diperbarui.
-   ⚖️ Penentuan **prioritas perbaikan** menggunakan metode SPK **WASPAS**.
-   🛠️ Laporan akan **diverifikasi** oleh petugas sarpras dan ditugaskan ke teknisi.
-   📊 Dashboard admin dengan statistik laporan, grafik perbulan, grafik tiap gedung, dan status laporan.
-   📱 **Responsive design**: nyaman digunakan di perangkat mobile maupun desktop.

---

## 🧰 Tech Stack

-   **Backend**: Laravel 10
-   **Frontend Template**: [Paper Dashboard Laravel](https://www.creative-tim.com/live/paper-dashboard-laravel)
-   **Database**: MySQL (phpMyAdmin)
-   **Library Tambahan**:
    -   `yajra/laravel-datatables-oracle`
    -   `phpoffice/phpspreadsheet`

---

## 🛠️ Installation

### 1. Clone Repository

```bash
git clone https://github.com/koctaa04/PBL_Fastcili-TI.git
cd fastcili-ti
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan database:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fastcili_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeder

```bash
php artisan migrate:fresh --seed
```

---

## 👨‍💻 Demo

Akun demo yang dapat digunakan untuk login:

-   **Email**: `admin@jti.com`
-   **Password**: `password`

---

## 📁 Struktur Folder Penting

```bash
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   ├── Models/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│
├── public/
├── resources/
│   ├── views/
│
├── routes/
│   ├── web.php
```

---

## 📚 Additional Info

### 📦 Paper Dashboard Laravel

Kami menggunakan template gratis dari Creative Tim. Anda dapat menyesuaikan tampilannya dengan mengikuti dokumentasi resmi:

👉 [https://www.creative-tim.com/live/paper-dashboard-laravel](https://www.creative-tim.com/live/paper-dashboard-laravel)

---

## 🤝 Kontribusi

Kami terbuka terhadap kontribusi dari siapa pun yang ingin membantu pengembangan proyek ini.

Cara kontribusi:

1. Fork repository ini.
2. Buat branch fitur/bugfix baru.
3. Commit perubahan yang kamu buat.
4. Push ke branch tersebut.
5. Buat pull request di GitHub.

### 👥 Anggota Kelompok Fastcili-TI:

-   Annisa Eka Puspita [![GitHub](https://img.shields.io/badge/annisaeka123-181717?style=flat&logo=github)](https://github.com/annisaeka123)
-   Dwi Ahmad Khairy [![GitHub](https://img.shields.io/badge/Archin0-181717?style=flat&logo=github)](https://github.com/Archin0)
-   Muhammad Fahreza Rohmansy [![GitHub](https://img.shields.io/badge/rezfahreza-181717?style=flat&logo=github)](https://github.com/rezfahreza)
-   Rafi Ody Prasetyo [![GitHub](https://img.shields.io/badge/rafiody16-181717?style=flat&logo=github)](https://github.com/rafiody16)
-   Yefta Octavianus Santo [![GitHub](https://img.shields.io/badge/koctaa04-181717?style=flat&logo=github)](https://github.com/koctaa04)

---

## 📄 License

This project is open-source and licensed under the **MIT License**.

See the [LICENSE](LICENSE) file for more information.

---
