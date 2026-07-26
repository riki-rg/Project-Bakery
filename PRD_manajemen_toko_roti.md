# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## Sistem Manajemen Toko Roti

**STATUS: DRAFT SEMENTARA**

| | |
| --- | --- |
| **Nama Produk** | Sistem Manajemen Toko Roti |
| **Versi Dokumen** | v0.1 |
| **Disusun oleh** | TBD (Pengembang) |
| **Untuk** | TBD (Pemilik Toko) |
| **Tanggal** | 25 Juli 2026 |
| **Dokumen Terkait** | Design System & UI Specification akan disusun setelah PRD disetujui |

---

# 1. Ringkasan Produk (Overview)

Pengelolaan toko roti membutuhkan pencatatan produk, persediaan, dan kehadiran karyawan yang terpusat. Tanpa sistem terpadu, pemilik atau pengelola toko berisiko mengalami ketidaksesuaian stok, kesulitan memantau aktivitas operasional, dan keterlambatan memperoleh informasi toko.

Produk yang akan dibangun adalah sistem manajemen toko roti berbasis Laravel untuk pemilik/pengelola dan karyawan toko. Autentikasi menggunakan Laravel Breeze. MVP berfokus pada pengelolaan pengguna, produk, stok, dan absensi. Aplikasi React untuk pelanggan akan menggunakan API yang terhubung ke sistem, tetapi pengembangannya ditempatkan pada fase lanjutan setelah panel Laravel selesai.

# 2. Tujuan & Sasaran (Goals)

- Memusatkan data operasional toko dalam satu sistem.
- Memudahkan pemilik atau pengelola dalam memantau produk, stok, dan karyawan.
- Mengurangi kesalahan pencatatan serta ketidaksesuaian stok produk.
- Mempermudah karyawan mencatat pergerakan stok dan kehadiran.
- Menyiapkan sumber data terstruktur yang kelak dapat digunakan aplikasi React pelanggan melalui API.
- Menyediakan dasar pelaporan operasional untuk pengambilan keputusan pemilik toko.

# 3. Pengguna & Peran (Users & Roles)

- **Pemilik/Pengelola:** memiliki akses pengelolaan tertinggi terhadap data toko, akun karyawan, produk, stok, absensi, dan ringkasan operasional.
- **Karyawan:** mengakses fungsi operasional sesuai izin, terutama melihat produk, mencatat perubahan stok, dan melakukan absensi.
- **Pelanggan:** menggunakan aplikasi React pada fase lanjutan untuk melihat dan berinteraksi dengan informasi atau layanan toko yang dipublikasikan melalui API.

# 4. Ruang Lingkup (Scope)

## 4.1 Termasuk (MVP)

- Autentikasi panel Laravel menggunakan Laravel Breeze.
- Hak akses terpisah untuk Pemilik/Pengelola dan Karyawan.
- Dashboard ringkasan operasional toko.
- Manajemen akun dan status karyawan.
- Manajemen kategori dan data produk roti.
- Manajemen stok serta riwayat pergerakan stok.
- Absensi masuk dan pulang karyawan.
- Riwayat dan rekap dasar stok serta absensi.
- Penyimpanan gambar produk dan penyediaan data dummy awal.
- Fondasi API untuk data yang akan digunakan aplikasi React.

## 4.2 Di Luar Lingkup Awal / Fase Lanjutan

Aplikasi React untuk pelanggan, pemesanan daring, pembayaran, pengiriman, promosi, dan fitur pelanggan lainnya berada di luar MVP panel Laravel. Detail fitur tersebut perlu dibahas pada PRD fase pelanggan.

# 5. Asumsi & Batasan (Assumptions & Constraints)

- **Keputusan pengguna:** backend dan panel pengelola dibangun dengan Laravel, sedangkan antarmuka pelanggan akan dibangun dengan React.
- **Keputusan pengguna:** autentikasi panel Laravel menggunakan Laravel Breeze.
- **Asumsi teknis:** Laravel menyediakan API untuk aplikasi React; metode autentikasi API belum diputuskan.
- **Asumsi operasional:** MVP menangani satu toko terlebih dahulu sampai kebutuhan multi-cabang dikonfirmasi.
- **Asumsi stok:** setiap perubahan jumlah stok wajib menghasilkan catatan riwayat agar dapat ditelusuri.
- **Batasan:** metode absensi, kebijakan keterlambatan, jam kerja, dan validasi lokasi belum ditentukan.
- **Batasan:** proses penjualan oleh kasir belum dinyatakan sebagai bagian MVP.
- **Batasan desain:** antarmuka harus cerah dan sesuai karakter toko roti; token visual final akan ditetapkan dalam dokumen desain terpisah.

# 6. Kebutuhan Fungsional (Functional Requirements)

## 6.1 Semua Pengguna Internal — Autentikasi & Akun

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **AUTH-1** | Pengguna internal dapat masuk menggunakan kredensial yang valid melalui autentikasi Laravel Breeze. | **Wajib** |
| **AUTH-2** | Sistem menolak kredensial tidak valid dan menampilkan pesan kesalahan yang aman. | **Wajib** |
| **AUTH-3** | Pengguna yang telah masuk dapat keluar dari sistem. | **Wajib** |
| **AUTH-4** | Sistem mengarahkan pengguna ke fitur yang sesuai dengan perannya setelah masuk. | **Wajib** |
| **AUTH-5** | Pengguna dapat memperbarui informasi profil dan kata sandinya. | **Penting** |
| **AUTH-6** | Pengguna dapat meminta pengaturan ulang kata sandi. | **Penting** |

## 6.2 Pemilik/Pengelola — Dashboard

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **DASH-1** | Pemilik dapat melihat ringkasan jumlah produk dan karyawan aktif. | **Wajib** |
| **DASH-2** | Pemilik dapat melihat daftar produk dengan stok rendah atau habis. | **Wajib** |
| **DASH-3** | Pemilik dapat melihat ringkasan kehadiran karyawan pada hari berjalan. | **Penting** |
| **DASH-4** | Pemilik dapat melihat aktivitas perubahan stok terbaru. | **Penting** |

## 6.3 Pemilik/Pengelola — Manajemen Karyawan

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **EMP-1** | Pemilik dapat membuat akun karyawan. | **Wajib** |
| **EMP-2** | Pemilik dapat melihat dan mencari daftar karyawan. | **Wajib** |
| **EMP-3** | Pemilik dapat memperbarui data karyawan. | **Wajib** |
| **EMP-4** | Pemilik dapat menonaktifkan akun karyawan tanpa menghapus riwayat operasionalnya. | **Wajib** |
| **EMP-5** | Sistem membatasi akses karyawan berdasarkan perannya. | **Wajib** |
| **EMP-6** | Pemilik dapat melihat riwayat absensi seorang karyawan. | **Penting** |

## 6.4 Pemilik/Pengelola — Kategori & Produk

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PROD-1** | Pemilik dapat membuat, melihat, memperbarui, dan menonaktifkan kategori produk. | **Wajib** |
| **PROD-2** | Pemilik dapat membuat produk beserta nama, kategori, deskripsi, harga, gambar, dan statusnya. | **Wajib** |
| **PROD-3** | Pemilik dapat melihat, mencari, dan memfilter daftar produk. | **Wajib** |
| **PROD-4** | Pemilik dapat memperbarui data produk. | **Wajib** |
| **PROD-5** | Pemilik dapat menonaktifkan produk tanpa menghapus riwayat stoknya. | **Wajib** |
| **PROD-6** | Sistem memvalidasi format, tipe, dan ukuran gambar produk. | **Penting** |
| **PROD-7** | Sistem menyediakan data dummy produk dan gambar roti untuk demonstrasi awal. | **Penting** |

## 6.5 Pemilik dan Karyawan — Manajemen Stok

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **STOK-1** | Pengguna berizin dapat melihat jumlah stok terkini setiap produk. | **Wajib** |
| **STOK-2** | Pengguna berizin dapat mencatat stok masuk beserta jumlah dan keterangannya. | **Wajib** |
| **STOK-3** | Pengguna berizin dapat mencatat stok keluar beserta jumlah dan alasannya. | **Wajib** |
| **STOK-4** | Sistem memperbarui jumlah stok berdasarkan transaksi stok yang berhasil dicatat. | **Wajib** |
| **STOK-5** | Sistem mencegah stok menjadi negatif. | **Wajib** |
| **STOK-6** | Sistem menyimpan pelaku, waktu, tipe, jumlah, dan catatan setiap pergerakan stok. | **Wajib** |
| **STOK-7** | Pemilik dapat menentukan ambang stok rendah per produk. | **Penting** |
| **STOK-8** | Pengguna dapat mencari dan memfilter riwayat pergerakan stok berdasarkan produk, tipe, pelaku, dan periode. | **Penting** |
| **STOK-9** | Pemilik dapat melakukan penyesuaian stok dengan alasan yang wajib diisi. | **Penting** |

## 6.6 Karyawan — Absensi

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **ATT-1** | Karyawan aktif dapat mencatat waktu masuk. | **Wajib** |
| **ATT-2** | Karyawan yang telah masuk dapat mencatat waktu pulang pada hari yang sama. | **Wajib** |
| **ATT-3** | Sistem mencegah absensi masuk atau pulang ganda pada tanggal yang sama. | **Wajib** |
| **ATT-4** | Karyawan dapat melihat riwayat absensinya sendiri. | **Penting** |
| **ATT-5** | Pemilik dapat melihat dan memfilter rekap absensi seluruh karyawan. | **Wajib** |
| **ATT-6** | Pemilik dapat mengoreksi data absensi dengan alasan yang tercatat. | **Penting** |
| **ATT-7** | Sistem dapat menghitung status tepat waktu, terlambat, atau tidak lengkap setelah aturan jam kerja ditentukan. | **Fase 2** |

## 6.7 Pemilik/Pengelola — Laporan Dasar

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **RPT-1** | Pemilik dapat melihat laporan stok terkini. | **Penting** |
| **RPT-2** | Pemilik dapat melihat rekap pergerakan stok berdasarkan periode. | **Penting** |
| **RPT-3** | Pemilik dapat melihat rekap absensi berdasarkan karyawan dan periode. | **Penting** |
| **RPT-4** | Pemilik dapat mengekspor laporan ke format yang disepakati. | **Fase 2** |

## 6.8 Sistem — API untuk Aplikasi Pelanggan

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **API-1** | Sistem menyediakan fondasi endpoint API berversi untuk integrasi aplikasi React. | **Penting** |
| **API-2** | API hanya mempublikasikan produk aktif dan data yang diizinkan untuk pelanggan. | **Fase 2** |
| **API-3** | API mengembalikan respons dan kesalahan dalam format JSON yang konsisten. | **Fase 2** |
| **API-4** | Sistem membatasi akses endpoint privat menggunakan mekanisme autentikasi API yang disepakati. | **Fase 2** |

# 7. Alur Pengguna Utama (Key User Flows)

## 7.1 Pemilik Masuk dan Memantau Toko

1. Pemilik membuka halaman masuk panel Laravel.
2. Pemilik memasukkan kredensial.
3. Sistem memvalidasi kredensial dan peran pengguna.
4. Sistem mengarahkan pemilik ke dashboard.
5. Pemilik melihat ringkasan produk, stok rendah, karyawan, dan kehadiran.

## 7.2 Pemilik Menambahkan Produk

1. Pemilik membuka modul produk dan memilih tambah produk.
2. Pemilik mengisi informasi produk serta mengunggah gambar.
3. Sistem memvalidasi data dan gambar.
4. Sistem menyimpan produk dengan status yang dipilih.
5. Produk muncul pada daftar produk dan siap menerima catatan stok.

## 7.3 Karyawan Mencatat Stok Masuk atau Keluar

1. Karyawan membuka modul stok.
2. Karyawan memilih produk dan tipe transaksi stok.
3. Karyawan memasukkan jumlah dan keterangan atau alasan.
4. Sistem memvalidasi izin dan ketersediaan stok.
5. Sistem memperbarui stok dan menyimpan riwayat transaksi secara atomik.
6. Jika stok keluar melebihi stok tersedia, sistem menolak transaksi dan menampilkan alasan penolakan.

## 7.4 Karyawan Melakukan Absensi

1. Karyawan masuk ke panel menggunakan akunnya.
2. Karyawan membuka modul absensi.
3. Karyawan memilih aksi masuk; sistem menyimpan waktu masuk.
4. Pada akhir kerja, karyawan memilih aksi pulang; sistem menyimpan waktu pulang.
5. Jika aksi yang sama telah dicatat, sistem menolak duplikasi.

## 7.5 Pemilik Menonaktifkan Karyawan

1. Pemilik membuka daftar karyawan.
2. Pemilik memilih karyawan aktif dan menjalankan aksi nonaktifkan.
3. Sistem meminta konfirmasi.
4. Sistem mengubah status akun menjadi "Tidak Aktif" tanpa menghapus riwayatnya.
5. Karyawan tersebut tidak dapat masuk kembali ke panel.

# 8. Model Data (High-Level)

| **Entitas** | **Field Utama** | **Keterangan** |
| --- | --- | --- |
| **User** | id, name, email, password, role, is_active, created_at, updated_at | Akun Pemilik/Pengelola dan Karyawan. |
| **EmployeeProfile** | id, user_id, employee_code, phone, address, joined_at | Informasi tambahan karyawan; rincian final masih TBD. |
| **Category** | id, name, slug, description, is_active, created_at, updated_at | Kelompok produk roti. |
| **Product** | id, category_id, name, slug, description, price, image_path, current_stock, low_stock_threshold, is_active, created_at, updated_at | Produk yang dikelola toko. |
| **StockMovement** | id, product_id, user_id, type, quantity, stock_before, stock_after, note, occurred_at | Audit stok masuk, keluar, dan penyesuaian. |
| **Attendance** | id, user_id, attendance_date, check_in_at, check_out_at, status, correction_note, corrected_by | Catatan kehadiran harian karyawan. |
| **WorkSchedule** | [id], [user_id], [work_date], [start_time], [end_time] | Jadwal kerja untuk perhitungan keterlambatan pada fase lanjutan. |
| **AuditLog** | [id], [user_id], [action], [entity_type], [entity_id], [metadata], [created_at] | Jejak perubahan sensitif pada fase lanjutan. |

**Catatan:** field dalam [tanda kurung siku] merupakan bagian dari fitur usulan/Fase Lanjutan (Bab 11). Struktur basis data final perlu disesuaikan dengan aturan bisnis yang disepakati.

# 9. Kebutuhan Non-Fungsional (Non-Functional Requirements)

- **Keamanan:** seluruh halaman internal hanya dapat diakses setelah autentikasi dan wajib menerapkan otorisasi berbasis peran.
- **Perlindungan data:** kata sandi wajib disimpan dengan hashing standar Laravel; formulir harus terlindungi dari CSRF dan input harus divalidasi di server.
- **Integritas stok:** pembaruan jumlah dan pencatatan riwayat stok harus berlangsung dalam satu transaksi basis data.
- **Auditabilitas:** data historis stok dan absensi tidak boleh hilang akibat penonaktifan akun atau produk.
- **Responsivitas:** panel dapat digunakan pada desktop, tablet, dan ponsel tanpa kehilangan fungsi utama.
- **Aksesibilitas:** formulir, navigasi, status, dan pesan kesalahan harus dapat dipahami serta digunakan dengan keyboard.
- **Performa:** daftar produk, stok, karyawan, dan absensi harus menggunakan pagination dan pencarian yang efisien.
- **Konsistensi API:** endpoint masa depan harus berversi dan memiliki struktur respons serta kesalahan yang konsisten.
- **Desain visual:** antarmuka menggunakan nuansa cerah, hangat, bersih, dan relevan dengan toko roti; implementasi wajib mengikuti token pada spesifikasi desain yang akan disusun.
- **Pemulihan data:** strategi pencadangan basis data dan berkas gambar harus ditentukan sebelum produksi.

# 10. Integrasi Pihak Ketiga

| **Layanan** | **Fungsi** | **Catatan** |
| --- | --- | --- |
| **Laravel Breeze** | Autentikasi dasar panel internal | Wajib untuk MVP sesuai keputusan pengguna. |
| **Penyimpanan Berkas** | Menyimpan gambar produk | Penyedia lokal atau cloud belum diputuskan. |
| **Layanan Email** | Pengaturan ulang kata sandi dan notifikasi akun | Penyedia belum diputuskan. |
| **Aplikasi React** | Antarmuka pelanggan yang mengonsumsi API Laravel | Fase lanjutan. |

# 11. Fitur Usulan / Fase Lanjutan

- **Aplikasi React Pelanggan.** Menampilkan katalog dan layanan toko kepada pelanggan melalui API Laravel.
- **Pemesanan Daring.** Pelanggan memilih produk, membuat pesanan, dan menerima status pesanan.
- **Pembayaran Digital.** Integrasi payment gateway setelah metode dan penyedia disepakati.
- **Manajemen Penjualan/Kasir.** Mencatat transaksi penjualan dan mengurangi stok secara otomatis.
- **Jadwal Kerja.** Mengatur shift karyawan dan menghitung keterlambatan berdasarkan jadwal.
- **Notifikasi Stok.** Memberi peringatan otomatis saat stok rendah atau habis.
- **Ekspor Laporan.** Menghasilkan berkas laporan dalam format seperti PDF atau spreadsheet.
- **Multi-Cabang.** Memisahkan produk, stok, karyawan, dan laporan berdasarkan cabang.
- **Audit Log Lengkap.** Mencatat perubahan sensitif untuk penelusuran administratif.

# 12. Pertanyaan Terbuka / TBD

- Apa nama resmi toko dan nama produk digital yang akan digunakan?
- Apakah sistem MVP hanya untuk satu toko atau harus mendukung beberapa cabang?
- Apakah selain Pemilik dan Karyawan diperlukan peran Admin, Manajer, Kasir, atau Gudang?
- Tindakan stok apa saja yang boleh dilakukan karyawan tanpa persetujuan pemilik?
- Apakah stok dihitung sebagai produk jadi, bahan baku, atau keduanya?
- Apakah produk memiliki varian, satuan, SKU, tanggal kedaluwarsa, atau batch produksi?
- Apakah transaksi kasir dan penjualan termasuk MVP?
- Bagaimana aturan jam kerja, shift, keterlambatan, izin, cuti, dan absensi tidak lengkap?
- Apakah absensi cukup dengan tombol masuk/pulang atau membutuhkan lokasi, foto, QR, maupun perangkat khusus?
- Laporan apa saja yang paling penting bagi pemilik pada rilis pertama?
- Format ekspor laporan apa yang diperlukan?
- Apakah gambar produk disimpan lokal atau menggunakan layanan cloud?
- Database, versi Laravel, versi PHP, dan lingkungan hosting apa yang akan digunakan?
- Apakah panel Laravel memakai Blade sepenuhnya atau teknologi frontend tambahan?
- Mekanisme autentikasi API untuk React akan menggunakan Laravel Sanctum atau pilihan lain?
- Fitur pelanggan apa saja yang akan masuk ke aplikasi React: katalog, pemesanan, pembayaran, akun, ulasan, atau pelacakan pesanan?
- Apakah data dummy hanya diperlukan untuk produk atau juga karyawan, stok, dan absensi?
- Siapa nama pengembang, nama klien, target waktu rilis, dan anggaran proyek?

# 13. Glosarium

- **MVP:** versi awal produk dengan kemampuan minimum yang wajib tersedia agar dapat digunakan.
- **Laravel Breeze:** paket scaffolding autentikasi minimal untuk aplikasi Laravel.
- **Stok Masuk:** penambahan kuantitas produk ke persediaan.
- **Stok Keluar:** pengurangan kuantitas produk dari persediaan.
- **Penyesuaian Stok:** koreksi kuantitas berdasarkan hasil pemeriksaan fisik atau alasan operasional.
- **API:** antarmuka yang memungkinkan aplikasi React bertukar data dengan sistem Laravel.
- **TBD:** keputusan atau informasi yang masih perlu ditentukan.

---

*Dokumen ini merupakan draft sementara dan dapat berubah seiring pembahasan lebih lanjut dengan klien.*
