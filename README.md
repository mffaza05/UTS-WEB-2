# UTS Pemorgraman Web 2 - Sistem Manajemen Akademik

## Informasi Mahasiswa
- **Nama:** MUHAMMAD HAFIZH FAZA QODAMA
- **NPM:** 2410010546
- **Mata Kuliah:** Pemrograman Web 2 (UTS)

---

## Deskripsi Aplikasi

Aplikasi Sistem Manajemen Akademik adalah sebuah aplikasi CRUD (Create, Read, Update, Delete) yang dibangun menggunakan PHP Native (tanpa framework) dan MySQL sebagai database. 

Aplikasi ini dirancang untuk mengelola data akademik dengan fitur-fitur berikut:
- **Manajemen Mahasiswa** - Tambah, edit, hapus, dan lihat data mahasiswa
- **Manajemen Program Studi** - Kelola program studi yang tersedia
- **Manajemen KRS** - Kelola Kartu Rencana Studi mahasiswa
- **Relasi Database** - Implementasi relasi antar tabel dengan Foreign Key
- **JOIN Query** - Menampilkan data terkoneksi dari beberapa tabel

---

## Struktur Database

Aplikasi menggunakan 3 tabel utama dengan relasi berikut:

### Tabel: Prodi (Program Studi)
```
id (INT, Primary Key)
nama_prodi (VARCHAR)
kode_prodi (VARCHAR)
created_at (TIMESTAMP)
```

### Tabel: Mahasiswa
```
id (INT, Primary Key)
npm (VARCHAR, Unique)
nama (VARCHAR)
email (VARCHAR)
no_telp (VARCHAR)
alamat (TEXT)
id_prodi (INT, Foreign Key) → Prodi.id
created_at (TIMESTAMP)
```

**Relasi:** Mahasiswa.id_prodi → Prodi.id (Many-to-One)

### Tabel: KRS (Kartu Rencana Studi)
```
id (INT, Primary Key)
id_mahasiswa (INT, Foreign Key) → Mahasiswa.id
mata_kuliah (VARCHAR)
kode_mk (VARCHAR)
semester (INT)
sks (INT)
nilai (VARCHAR)
created_at (TIMESTAMP)
```

**Relasi:** KRS.id_mahasiswa → Mahasiswa.id (One-to-Many)

---

## Struktur Folder

```
crud-php-native/
├── docker-compose.yml           # File Docker Compose
├── copilot-readme.md            # Spesifikasi tugas
├── Dockerfile                   # Docker image build
├── .dockerignore                # Docker ignore list
└── src/
    ├── config/
    │   └── koneksi.php          # Konfigurasi koneksi database
    ├── mahasiswa/
    │   ├── index.php            # Tampil data mahasiswa
    │   ├── tambah.php           # Form tambah mahasiswa
    │   └── edit.php             # Form edit mahasiswa
    ├── prodi/
    │   ├── index.php            # Tampil data program studi
    │   ├── tambah.php           # Form tambah program studi
    │   └── edit.php             # Form edit program studi
    ├── krs/
    │   ├── index.php            # Tampil data KRS
    │   ├── tambah.php           # Form tambah KRS
    │   └── edit.php             # Form edit KRS
    ├── assets/
    │   └── style.css            # Stylesheet (Bootstrap-like)
    ├── index.php                # Halaman utama
    ├── database.sql             # File SQL untuk setup database
    ├── README.md                # File dokumentasi ini
    └── config/koneksi.php
```

---

## Cara Menjalankan

### Prasyarat / System Requirements
- **Web Server:** Apache atau Nginx
- **PHP:** PHP 7.4 atau lebih tinggi
- **Database:** MySQL atau MariaDB
- **Browser:** Chrome, Firefox, Safari, Edge, atau sejenisnya

### Langkah-Langkah Setup

#### 1. **Clone atau Download Project**
```bash
git clone https://github.com/mffaza05/UTS-WEB-2
cd crud-php-native
```

#### 2. **Import Database**
- Buka phpMyAdmin atau MySQL client
- Buat database atau import file `src/database.sql`:
```bash
mysql -u root -p < src/database.sql
```

Atau melalui phpMyAdmin:
- Import file `src/database.sql` dari folder project
- Database akan otomatis terbuat dengan nama: `uniska_2026_utsweb2_2410010546`
- Tabel dan data sample akan otomatis dibuat

#### 3. **Setup Koneksi Database**
Edit file `config/koneksi.php` jika perlu, tetapi pengaturan default sudah menggunakan variable lingkungan (Docker friendly):
```php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_DATABASE') ?: 'uniska_2026_utsweb2_2410010546';
```

#### 5. **Jalankan Aplikasi**
Jika tidak menggunakan Docker, Anda dapat tetap menjalankan aplikasi secara manual:
- Konfigurasikan server web ke folder `src/` sebagai web root
- Atau gunakan PHP built-in server dari folder `src/`:
```bash
cd crud-php-native/src
php -S localhost:8000
# Akses: http://localhost:8000
```

#### 6. **Login/Akses**
- Aplikasi tidak memerlukan login
- Akses langsung melalui halaman utama
- Navigasi menggunakan menu di navbar

---

## Panduan Penggunaan

### 🏠 Halaman Utama
- Menampilkan statistik data (jumlah mahasiswa, prodi, KRS)
- Informasi tentang aplikasi
- Fitur-fitur yang tersedia

### 👨‍🎓 Manajemen Mahasiswa
1. **Lihat Data:**
   - Klik menu "Mahasiswa"
   - Tabel menampilkan semua data mahasiswa dengan nama prodi (JOIN query)

2. **Tambah Data:**
   - Klik tombol "Tambah Mahasiswa"
   - Isi form: NPM, Nama, Program Studi, Email, No. Telp, Alamat
   - Klik "Simpan"

3. **Edit Data:**
   - Klik tombol "Edit" pada baris data
   - Ubah data sesuai kebutuhan
   - Klik "Ubah"

4. **Hapus Data:**
   - Klik tombol "Hapus" pada baris data
   - Konfirmasi penghapusan
   - Data akan dihapus beserta KRS-nya (CASCADE delete)

### 📖 Manajemen Program Studi
1. **Lihat Data:**
   - Tampilkan semua program studi dengan jumlah mahasiswa

2. **Tambah/Edit Program Studi:**
   - Isi nama prodi dan kode prodi

3. **Hapus Program Studi:**
   - Tidak bisa menghapus jika masih ada mahasiswa terdaftar

### 📋 Manajemen KRS
1. **Lihat Data:**
   - Tampilkan semua KRS dengan nama mahasiswa dan program studi (JOIN query)

2. **Tambah KRS:**
   - Pilih mahasiswa dari dropdown
   - Isi mata kuliah, kode MK, semester, SKS
   - Opsional: isi nilai
   - Klik "Simpan"

3. **Edit/Hapus KRS:**
   - Sama seperti manajemen mahasiswa
