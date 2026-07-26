# Sistem Manajemen Toko Roti

Sistem manajemen toko roti berbasis Laravel dengan autentikasi Laravel Breeze untuk mengelola produk, stok, karyawan, dan absensi.

## Fitur Utama

### Untuk Pemilik/Pengelola
- Dashboard dengan ringkasan operasional
- Manajemen karyawan (CRUD, aktivasi/deaktivasi)
- Manajemen kategori produk
- Manajemen produk dengan upload gambar
- Monitoring stok rendah/habis
- Rekap absensi karyawan
- Koreksi data absensi
- Riwayat pergerakan stok

### Untuk Karyawan
- Absensi check-in/check-out
- Pencatatan stok masuk/keluar
- Lihat riwayat absensi sendiri
- Lihat riwayat pergerakan stok

### API untuk Aplikasi React (Future)
- GET /api/v1/products - List produk aktif
- GET /api/v1/products/{id} - Detail produk
- GET /api/v1/categories - List kategori

## Teknologi

- Laravel 13
- Laravel Breeze (Authentication)
- SQLite Database
- Tailwind CSS
- Blade Templates

## Instalasi

1. Clone atau navigasi ke direktori project:
```bash
cd /home/rizz/Documents/bakerrr/bakery-system
```

2. Install dependencies:
```bash
composer install
npm install
npm run build
```

3. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Jalankan migrasi dan seeder:
```bash
php artisan migrate:fresh --seed
```

5. Buat storage link untuk gambar produk:
```bash
php artisan storage:link
```

6. Jalankan server development:
```bash
php artisan serve
```

7. Akses aplikasi di http://localhost:8000

## Akun Default

**Pemilik:**
- Email: owner@bakery.com
- Password: password

**Karyawan:**
- Email: employee@bakery.com
- Password: password

## Struktur Database

### Users
- Menyimpan data pengguna (Owner & Employee)
- Role: owner, employee
- Status: is_active

### Employee Profiles
- Data tambahan karyawan
- Kode karyawan, telepon, alamat, tanggal bergabung

### Categories
- Kategori produk roti
- Slug, deskripsi, status aktif

### Products
- Data produk
- Kategori, nama, harga, gambar, stok, threshold stok rendah

### Stock Movements
- Audit trail pergerakan stok
- Tipe: in, out, adjustment
- Mencatat stok sebelum dan sesudah

### Attendances
- Absensi karyawan
- Check-in, check-out, status
- Koreksi oleh owner

## Route Structure

### Web Routes (Authenticated)

**Dashboard:**
- GET /dashboard

**Employees (Owner only):**
- GET /employees
- GET /employees/create
- POST /employees
- GET /employees/{id}
- GET /employees/{id}/edit
- PUT /employees/{id}
- POST /employees/{id}/deactivate
- POST /employees/{id}/activate

**Categories (Owner only):**
- GET /categories
- GET /categories/create
- POST /categories
- GET /categories/{id}/edit
- PUT /categories/{id}
- DELETE /categories/{id}

**Products (Owner only):**
- GET /products
- GET /products/create
- POST /products
- GET /products/{id}
- GET /products/{id}/edit
- PUT /products/{id}
- DELETE /products/{id}

**Stock Movements (All authenticated):**
- GET /stock-movements
- GET /stock-movements/create
- POST /stock-movements

**Attendances (All authenticated):**
- GET /attendances
- POST /attendance/check-in
- POST /attendance/check-out
- GET /my-attendance
- GET /attendances/{id}/edit (Owner only)
- PUT /attendances/{id} (Owner only)

### API Routes

**Products:**
- GET /api/v1/products
- GET /api/v1/products/{id}
- GET /api/v1/categories

## Keamanan

- CSRF protection pada semua form
- Password hashing dengan bcrypt
- Role-based authorization (middleware)
- Active user validation
- Input validation pada semua form
- File upload validation (image only, max 2MB)

## Validasi

### Employee
- Email unique
- Employee code unique
- Password minimal sesuai Laravel rules
- Phone & address optional

### Product
- Category harus exist
- Price minimal 0
- Image: jpeg, png, jpg, gif max 2MB
- Low stock threshold minimal 0

### Stock Movement
- Product harus exist
- Quantity minimal 1
- Validasi stok cukup untuk tipe 'out'
- Atomic transaction untuk update stok

### Attendance
- Tidak boleh check-in 2x dalam sehari
- Harus check-in dulu sebelum check-out
- Check-out harus setelah check-in

## Fitur Keamanan

1. **Middleware EnsureUserIsActive**: Logout otomatis jika akun dinonaktifkan
2. **Middleware EnsureUserIsOwner**: Proteksi route owner-only
3. **Soft Delete**: Deaktivasi tanpa hapus data historis
4. **Audit Trail**: Stock movement mencatat siapa, kapan, berapa

## Data Dummy

Seeder sudah menyertakan:
- 1 Owner account
- 1 Employee account dengan profile
- 4 Kategori produk
- 10 Produk roti

## Development

### Menambah Migration Baru
```bash
php artisan make:migration nama_migration
```

### Menambah Controller
```bash
php artisan make:controller NamaController
```

### Menambah Model
```bash
php artisan make:model NamaModel
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Catatan Penting

1. Gambar produk disimpan di `storage/app/public/products`
2. Pastikan storage link sudah dibuat
3. Database menggunakan SQLite di `database/database.sqlite`
4. Stok tidak bisa negatif (validasi di controller)
5. Pergerakan stok menggunakan database transaction

## TODO / Fase Lanjutan

- [ ] Aplikasi React untuk pelanggan
- [ ] Pemesanan online
- [ ] Integrasi payment gateway
- [ ] Multi-cabang support
- [ ] Export laporan PDF/Excel
- [ ] Notifikasi stok rendah
- [ ] Jadwal kerja & shift karyawan
- [ ] Perhitungan keterlambatan
- [ ] Audit log lengkap
- [ ] Manajemen penjualan/kasir

## Lisensi

Proyek ini dibuat berdasarkan PRD tanggal 25 Juli 2026.

## Support

Untuk pertanyaan atau masalah, silakan hubungi developer.
