# 🎨 DESIGN SYSTEM & UI SPECIFICATIONS

## Sistem Manajemen Toko Roti — Laravel Management Panel

**STATUS: DRAFT SEMENTARA**  
**VERSI: v0.1**  
**TANGGAL: 25 Juli 2026**

---

## 0. Aturan Dasar Pengembangan (Core Development Rules)

Dokumen ini merupakan sumber acuan tunggal untuk seluruh antarmuka panel Laravel. Semua halaman bawaan Laravel Breeze dan semua halaman operasional wajib mengikuti sistem ini. Tidak boleh ada halaman yang mempertahankan tampilan scaffold Breeze bawaan.

- **Single Source of Truth:** seluruh warna, tipografi, jarak, radius, elevation, dan motion harus memanggil token yang didefinisikan di dokumen ini.
- **No Magic Numbers:** dilarang menambahkan warna, ukuran, jarak, radius, shadow, durasi, atau easing di luar token.
- **Full Breeze Restyle:** layout guest, layout authenticated, formulir autentikasi, profile, dropdown, navigation, validation error, flash message, dan tombol bawaan Breeze wajib ditata ulang.
- **Semantic Tokens First:** komponen menggunakan token berdasarkan fungsi seperti `--color-text-main`, bukan berdasarkan nama warna mentah.
- **Fluid by Default:** ukuran teks, ruang section, dan lebar layout menggunakan `clamp()`, Grid, Flexbox, `minmax()`, dan unit relatif.
- **Accessible by Default:** fokus harus terlihat, kontras dapat dibaca, interaksi tersedia melalui keyboard, dan warna bukan satu-satunya penanda status.
- **Complete States:** setiap elemen interaktif memiliki state default, hover, focus-visible, active, disabled, loading, error, dan success yang relevan.
- **Consistent Motion:** semua transisi menggunakan token durasi dan easing; tidak menggunakan `linear` atau perubahan mendadak.
- **Progressive Enhancement:** fungsi inti tetap dapat digunakan ketika animasi dikurangi melalui `prefers-reduced-motion`.
- **Responsive Behavior:** tidak boleh ada horizontal overflow pada viewport aplikasi. Tabel kompleks harus berubah menjadi pola responsif yang telah ditentukan.
- **Data Integrity UX:** aksi destruktif, perubahan stok, koreksi absensi, dan penonaktifan akun wajib memiliki konfirmasi dan umpan balik yang jelas.
- **No Placeholder Assets:** demo memakai aset gambar roti final yang dibuat khusus dan data dummy yang realistis.

---

## 1. Ringkasan Desain (Brand Overview & Essence)

Panel dirancang seperti ruang kerja digital bakery modern: cerah, hangat, bersih, teratur, dan terasa ramah tanpa kehilangan ketegasan sebagai perangkat operasional. Warna vanilla menjadi canvas utama, putih hangat menjadi permukaan kerja, caramel menjadi aksen tindakan utama, dan cocoa dipakai untuk teks serta elemen berkontras tinggi. Nuansa visual mengingatkan pengguna pada roti yang baru matang, kayu terang, kertas pembungkus premium, dan etalase bakery yang bersih.

Antarmuka tidak dibuat dekoratif secara berlebihan. Informasi stok, kehadiran, dan tindakan operasional tetap menjadi pusat perhatian. Fotografi produk digunakan secara selektif pada katalog dan detail produk. Area administratif menggunakan struktur yang lapang, hierarchy kuat, ikon sederhana, tabel mudah dipindai, dan status berlabel teks.

**Karakteristik Utama:**

- Canvas vanilla cerah dengan surface putih hangat dan aksen caramel.
- Bentuk membulat lembut yang ramah, tetapi tetap terstruktur dan profesional.
- Tipografi display berkarakter bakery pada momen branding; tipografi sans-serif digunakan untuk data operasional.
- Kartu statistik ringan dengan border lembut, bukan shadow berat.
- Fotografi roti bernuansa natural, terang, menggugah selera, dan konsisten.
- Ikon outline sederhana dengan stroke seragam.
- Motion lembut, cepat, dan fungsional tanpa mengganggu pekerjaan.
- Kepadatan layout seimbang: lapang pada dashboard, lebih padat pada tabel data.

### 1.1 Prinsip Pengalaman

1. **Warm Welcome:** halaman masuk dan dashboard harus memberi kesan ramah sejak pandangan pertama.
2. **Operational Clarity:** angka stok, status kehadiran, dan tindakan utama harus dapat ditemukan dalam beberapa detik.
3. **Safe Actions:** pengguna memahami konsekuensi sebelum mengubah stok atau menonaktifkan data.
4. **Role-Relevant:** pemilik dan karyawan hanya melihat navigasi serta tindakan yang relevan.
5. **Calm Feedback:** keberhasilan, peringatan, dan error disampaikan tegas tanpa terasa agresif.
6. **Consistent Learning:** komponen yang sama berperilaku sama di seluruh modul.

### 1.2 Sasaran Pengguna

| Pengguna | Kebutuhan UX Utama | Penekanan Desain |
| --- | --- | --- |
| Pemilik/Pengelola | Memantau kondisi toko dan mengelola seluruh data | Dashboard kaya ringkasan, filter kuat, tindakan administratif jelas |
| Karyawan | Absensi dan mencatat stok secara cepat | Navigasi ringkas, CTA kontekstual, form minim friksi |
| Pengguna Mobile | Menjalankan tugas cepat saat berpindah tempat | Target sentuh besar, sticky action, kartu responsif |

---

## 2. Design Tokens — Warna & Tipografi

### 2.1 Skema Warna Utama

Tidak boleh menggunakan warna lain di luar tabel berikut. Variasi visual dihasilkan melalui token, opacity yang ditentukan, dan kombinasi surface/border yang tersedia.

| Kategori | CSS Token | Hex / Value | Fungsi Utama |
| --- | --- | --- | --- |
| Brand | `--color-primary` | `#C66A2B` | CTA utama, active navigation, link penting |
| Brand | `--color-primary-hover` | `#A9521E` | Hover CTA utama |
| Brand | `--color-primary-active` | `#8B4218` | Active/pressed CTA utama |
| Brand Soft | `--color-primary-soft` | `#F8E4D4` | Background badge dan highlight caramel |
| Accent | `--color-accent` | `#E9A23B` | Sorotan statistik, dekorasi terbatas, indikator perhatian |
| Accent Soft | `--color-accent-soft` | `#FFF0CF` | Background peringatan lembut |
| Canvas | `--color-canvas` | `#FFF9F0` | Latar utama aplikasi |
| Surface | `--color-surface` | `#FFFEFC` | Kartu, panel, modal, sidebar |
| Surface Subtle | `--color-surface-subtle` | `#F8F1E7` | Header tabel, input disabled, grouped content |
| Surface Strong | `--color-surface-strong` | `#3C2418` | Area kontras tinggi dan hero autentikasi |
| Text | `--color-text-main` | `#35241C` | Heading dan body utama |
| Text | `--color-text-muted` | `#76645A` | Deskripsi, metadata, placeholder |
| Text | `--color-text-inverse` | `#FFFEFC` | Teks di atas surface gelap/primary |
| Border | `--color-border` | `#E8DACB` | Border standar dan divider |
| Border Strong | `--color-border-strong` | `#CDB8A5` | Border emphasis dan input hover |
| Success | `--color-success` | `#397A52` | Status berhasil/aktif/tersedia |
| Success Soft | `--color-success-soft` | `#E3F2E8` | Background pesan sukses |
| Warning | `--color-warning` | `#9A6512` | Status perhatian/stok rendah/terlambat |
| Warning Soft | `--color-warning-soft` | `#FFF0CF` | Background peringatan |
| Danger | `--color-danger` | `#A8433D` | Error, stok habis, aksi destruktif |
| Danger Hover | `--color-danger-hover` | `#87332F` | Hover aksi destruktif |
| Danger Soft | `--color-danger-soft` | `#F9E3E1` | Background error |
| Info | `--color-info` | `#3F6F82` | Informasi netral dan status informatif |
| Info Soft | `--color-info-soft` | `#E2F0F4` | Background informasi |
| Overlay | `--color-overlay` | `rgba(53, 36, 28, 0.56)` | Backdrop dialog/drawer |
| Focus | `--color-focus-ring` | `rgba(198, 106, 43, 0.28)` | Focus ring kontrol interaktif |

### 2.2 Aturan Kontras dan Penggunaan Warna

- Teks utama menggunakan `--color-text-main` di atas `--color-canvas`, `--color-surface`, atau `--color-surface-subtle`.
- Teks inverse hanya digunakan di atas `--color-primary`, `--color-primary-active`, atau `--color-surface-strong`.
- Status selalu menggunakan kombinasi ikon, label teks, dan warna.
- `--color-accent` tidak digunakan sebagai warna body text.
- CTA utama per halaman dibatasi satu tindakan dominan pada setiap area kerja.
- Danger hanya digunakan untuk error atau tindakan yang benar-benar destruktif.
- Border input focus tetap memakai border primary dan focus ring, bukan perubahan warna background saja.

### 2.3 Tipografi

**Font Display:** `Fraunces`, fallback: `Georgia, serif`.  
**Font UI/Body:** `Plus Jakarta Sans`, fallback: `Inter, Arial, sans-serif`.  
**Font Numeric:** `Plus Jakarta Sans` dengan `font-variant-numeric: tabular-nums`.

| Token | Formula Ukuran | Weight | Line Height | Letter Spacing | Penggunaan |
| --- | --- | --- | --- | --- | --- |
| `--type-display-lg` | `clamp(2.5rem, 5vw, 4.5rem)` | 700 | 1.05 | `-0.035em` | Hero autentikasi/brand statement |
| `--type-display-md` | `clamp(2rem, 3.5vw, 3.25rem)` | 700 | 1.1 | `-0.03em` | Empty state besar/halaman khusus |
| `--type-heading-xl` | `clamp(1.75rem, 2.5vw, 2.5rem)` | 700 | 1.15 | `-0.025em` | Judul dashboard |
| `--type-heading-lg` | `clamp(1.5rem, 2vw, 2rem)` | 700 | 1.2 | `-0.02em` | Judul halaman |
| `--type-heading-md` | `clamp(1.25rem, 1.5vw, 1.5rem)` | 700 | 1.25 | `-0.015em` | Judul section/modal |
| `--type-heading-sm` | `1.125rem` | 700 | 1.35 | `-0.01em` | Judul kartu |
| `--type-body-lg` | `1.0625rem` | 500 | 1.7 | `0` | Intro halaman |
| `--type-body-md` | `1rem` | 500 | 1.6 | `0` | Body standar |
| `--type-body-sm` | `0.875rem` | 500 | 1.55 | `0` | Metadata/tabel |
| `--type-label` | `0.875rem` | 700 | 1.3 | `0.01em` | Label form |
| `--type-caption` | `0.75rem` | 600 | 1.4 | `0.02em` | Caption/helper text |
| `--type-button` | `0.875rem` | 700 | 1 | `0.01em` | Tombol dan action |
| `--type-stat` | `clamp(1.75rem, 2.5vw, 2.5rem)` | 800 | 1 | `-0.03em` | Nilai statistik |

### 2.4 Hierarki Tipografi

- `Fraunces` hanya digunakan pada brand mark, hero autentikasi, heading dashboard utama, dan empty state penting.
- Seluruh form, navigasi, tabel, angka, dan tombol menggunakan `Plus Jakarta Sans`.
- Maksimal satu heading `h1` per halaman.
- Heading tidak boleh dipakai hanya untuk memperbesar teks; hierarchy HTML harus berurutan.
- Angka statistik dan kuantitas stok menggunakan tabular numeric agar kolom stabil.

---

## 3. Tata Letak, Jarak, Bentuk, dan Kedalaman

### 3.1 Spacing Ladder

**Base unit:** `0.25rem`.

| Token | Nilai | Penggunaan |
| --- | --- | --- |
| `--space-0` | `0` | Reset |
| `--space-1` | `0.25rem` | Jarak mikro ikon/indikator |
| `--space-2` | `0.5rem` | Gap kecil |
| `--space-3` | `0.75rem` | Gap kontrol rapat |
| `--space-4` | `1rem` | Padding kontrol standar |
| `--space-5` | `1.25rem` | Padding kartu compact |
| `--space-6` | `1.5rem` | Gap section kecil |
| `--space-8` | `2rem` | Padding kartu desktop |
| `--space-10` | `2.5rem` | Jarak section |
| `--space-12` | `3rem` | Section besar |
| `--space-16` | `4rem` | Ruang halaman |
| `--space-20` | `5rem` | Hero spacing |
| `--space-24` | `6rem` | Ruang display besar |

**Token fluid:**

- `--space-page-x: clamp(var(--space-4), 3vw, var(--space-10))`
- `--space-page-y: clamp(var(--space-6), 3vw, var(--space-12))`
- `--space-section: clamp(var(--space-8), 4vw, var(--space-16))`
- `--space-card: clamp(var(--space-4), 2vw, var(--space-8))`

### 3.2 Border Radius

| Token | Nilai | Penggunaan Wajib |
| --- | --- | --- |
| `--radius-none` | `0` | Divider dan tabel penuh |
| `--radius-sm` | `0.5rem` | Badge, checkbox, control kecil |
| `--radius-md` | `0.75rem` | Input, button, menu item |
| `--radius-lg` | `1rem` | Card dan table container |
| `--radius-xl` | `1.5rem` | Modal, auth card, hero panel |
| `--radius-full` | `9999px` | Avatar, status dot, pill khusus |

### 3.3 Elevation & Depth

| Token | Value | Penggunaan |
| --- | --- | --- |
| `--shadow-none` | `none` | Default surface dan tabel |
| `--shadow-sm` | `0 0.25rem 1rem rgba(53, 36, 28, 0.06)` | Dropdown dan floating filter |
| `--shadow-md` | `0 0.75rem 2rem rgba(53, 36, 28, 0.10)` | Modal dan drawer |
| `--shadow-focus` | `0 0 0 0.25rem var(--color-focus-ring)` | Focus ring |

Aturan elevation:

- Kartu utama memakai border dan `--shadow-none` secara default.
- Shadow muncul hanya pada objek yang mengambang atau berada di atas surface lain.
- Hover kartu interaktif dapat memakai `--shadow-sm`, tetapi kartu statistik noninteraktif tidak bergerak.
- Modal dan drawer memakai `--shadow-md` dengan overlay.

### 3.4 Container & Grid

| Token | Nilai | Fungsi |
| --- | --- | --- |
| `--content-max` | `90rem` | Lebar maksimum area aplikasi |
| `--form-max` | `48rem` | Form kompleks |
| `--auth-form-max` | `30rem` | Form autentikasi |
| `--sidebar-width` | `17.5rem` | Sidebar desktop |
| `--sidebar-collapsed` | `5.25rem` | Sidebar desktop ringkas |
| `--topbar-height` | `4.5rem` | Topbar aplikasi |
| `--control-height` | `2.75rem` | Input dan button standar |
| `--control-height-lg` | `3.25rem` | CTA/form autentikasi |
| `--touch-target` | `2.75rem` | Target sentuh minimum internal |

Grid dashboard:

- Statistik: `repeat(auto-fit, minmax(min(100%, 14rem), 1fr))`.
- Konten dua kolom: `minmax(0, 1.618fr) minmax(18rem, 1fr)`.
- Form dua kolom: `repeat(2, minmax(0, 1fr))`, menjadi satu kolom pada mobile.

---

## 4. Motion & Interaction Tokens

### 4.1 Easing Curves

| Token | Value | Penggunaan |
| --- | --- | --- |
| `--ease-snappy` | `cubic-bezier(0.2, 0.8, 0.2, 1)` | Button, input, tooltip, hover |
| `--ease-smooth` | `cubic-bezier(0.4, 0, 0.2, 1)` | Modal, drawer, page section |
| `--ease-bounce` | `cubic-bezier(0.34, 1.56, 0.64, 1)` | Toast dan micro-feedback terbatas |

### 4.2 Duration Tokens

| Token | Value | Penggunaan |
| --- | --- | --- |
| `--duration-instant` | `120ms` | Press feedback |
| `--duration-fast` | `220ms` | Hover/focus kontrol |
| `--duration-base` | `320ms` | Dropdown/tooltip |
| `--duration-slow` | `480ms` | Modal/drawer |

### 4.3 Motion Rules

- Button hover: perubahan warna dan translasi maksimal menggunakan `--duration-fast` + `--ease-snappy`.
- Button active: kembali ke posisi dasar dengan `--duration-instant`.
- Modal: opacity backdrop dan transform panel menggunakan `--duration-slow` + `--ease-smooth`.
- Toast: masuk dengan `--ease-bounce`, keluar dengan `--ease-smooth`.
- Skeleton menggunakan perubahan opacity lembut, bukan gerak cepat.
- Tidak ada animasi dekoratif pada tabel saat pengguna melakukan input berulang.
- Pada `prefers-reduced-motion: reduce`, durasi diubah menjadi `--duration-instant` dan transform non-esensial dihapus.

---

## 5. Struktur Aplikasi Laravel

### 5.1 Guest Layout

Layout untuk login, lupa kata sandi, reset kata sandi, dan verifikasi email.

**Desktop:**

- Split layout dengan rasio emas `minmax(0, 1fr) minmax(0, 1.618fr)`.
- Area kiri: form autentikasi pada surface terang.
- Area kanan: panel brand `--color-surface-strong`, foto bakery, gradient overlay dari token yang tersedia, tagline, dan detail dekoratif.
- Form berada dalam container `--auth-form-max` dan vertically centered.

**Mobile:**

- Panel visual dipadatkan menjadi header brand pendek.
- Form menjadi konten utama satu kolom.
- CTA memenuhi lebar container.
- Link bantuan tetap terlihat tanpa scroll horizontal.

### 5.2 Authenticated App Shell

**Desktop:**

- Sidebar tetap di kiri dengan lebar `--sidebar-width`.
- Topbar tetap di atas area konten dengan tinggi `--topbar-height`.
- Konten memakai `--space-page-x`, `--space-page-y`, dan `--content-max`.
- Sidebar dapat diringkas ke `--sidebar-collapsed`; preferensi disimpan untuk pengguna.

**Tablet:**

- Sidebar default ringkas.
- Label muncul melalui tooltip atau drawer saat diperlukan.
- Tabel mempertahankan kolom utama dan menyembunyikan metadata sekunder ke detail row.

**Mobile:**

- Sidebar berubah menjadi navigation drawer.
- Topbar memuat menu, judul konteks, dan avatar.
- CTA utama dapat menjadi sticky action pada bagian bawah viewport bila form panjang.
- Tidak menggunakan bottom navigation kecuali jumlah modul karyawan dipangkas menjadi maksimal lima.

### 5.3 Sidebar Information Architecture

**Pemilik/Pengelola:**

1. Dashboard
2. Produk
   - Semua Produk
   - Kategori
3. Stok
   - Stok Terkini
   - Pergerakan Stok
   - Penyesuaian
4. Karyawan
5. Absensi
6. Laporan
7. Pengaturan

**Karyawan:**

1. Dashboard
2. Stok
3. Absensi Saya
4. Riwayat Aktivitas Saya
5. Profil

Navigasi yang tidak diizinkan tidak ditampilkan dan tetap dilindungi oleh authorization server-side.

### 5.4 Topbar

- Tombol menu/collapse sidebar.
- Breadcrumb kontekstual pada desktop.
- Pencarian global ditunda sampai kebutuhannya disepakati.
- Notification center merupakan fase lanjutan.
- Avatar, nama singkat, role badge, dropdown profil, dan logout.
- Mobile hanya menampilkan elemen yang paling relevan agar tidak padat.

---

## 6. Spesifikasi Komponen

### 6.1 Brand Mark

- Kombinasi simbol roti sederhana dan wordmark.
- Wordmark menggunakan `Fraunces` dan `--color-text-main` atau `--color-text-inverse` sesuai surface.
- Versi compact hanya memakai simbol dengan label aksesibel.
- Tidak menggunakan emoji sebagai logo produksi.

### 6.2 Buttons

#### Primary Button

- Background `--color-primary`.
- Teks `--color-text-inverse`, token `--type-button`.
- Tinggi `--control-height`; varian besar `--control-height-lg`.
- Padding horizontal `--space-5`; gap ikon `--space-2`.
- Radius `--radius-md`.
- Hover `--color-primary-hover`; active `--color-primary-active`.
- Focus menggunakan `--shadow-focus`.
- Disabled menggunakan `--color-surface-subtle`, `--color-text-muted`, dan cursor nonaktif.
- Loading menampilkan spinner, mempertahankan lebar tombol, dan mencegah klik ulang.

#### Secondary Button

- Surface `--color-surface`.
- Border `--color-border`.
- Teks `--color-text-main`.
- Hover memakai `--color-surface-subtle` dan `--color-border-strong`.

#### Ghost Button

- Background transparan.
- Teks `--color-text-muted`.
- Hover memakai `--color-primary-soft` dan teks `--color-primary-active`.

#### Danger Button

- Background `--color-danger`.
- Hover `--color-danger-hover`.
- Wajib digunakan pada tahap konfirmasi terakhir, bukan sebagai tombol default daftar.

#### Icon Button

- Ukuran minimal `--touch-target`.
- Memiliki tooltip dan accessible name.
- Ikon saja tidak boleh digunakan ketika makna tindakan ambigu.

### 6.3 Form Controls

Semua input, textarea, select, date input, dan file input memiliki:

- Label permanen di atas field, bukan placeholder sebagai label.
- Tinggi standar `--control-height`; textarea memakai tinggi berdasarkan jumlah baris.
- Background `--color-surface`.
- Border `--color-border` dan radius `--radius-md`.
- Hover border `--color-border-strong`.
- Focus border `--color-primary` dan `--shadow-focus`.
- Placeholder `--color-text-muted`.
- Helper text token `--type-caption`.
- Error border `--color-danger` serta pesan dengan ikon dan teks.
- Success validation hanya ditampilkan ketika berguna.
- Disabled memakai `--color-surface-subtle` dan tetap terbaca.
- Required field diberi teks “Wajib” atau penanda yang dijelaskan, bukan asterisk tanpa konteks.

### 6.4 Password Input

- Tombol tampilkan/sembunyikan password memiliki label dinamis.
- Indikator Caps Lock bila dapat diterapkan secara stabil.
- Reset password menampilkan panduan minimum yang sesuai konfigurasi backend.
- Error tidak mengungkapkan apakah email terdaftar pada flow publik jika berisiko keamanan.

### 6.5 Checkbox, Radio, dan Switch

- Minimum target `--touch-target` termasuk label.
- Checked menggunakan `--color-primary`.
- Focus memakai `--shadow-focus`.
- Switch hanya digunakan untuk state langsung yang dapat dibatalkan, seperti aktif/nonaktif.
- Aksi berisiko tidak boleh hanya bergantung pada switch tanpa konfirmasi.

### 6.6 Search & Filter Bar

- Search di kiri, filter relevan di tengah, CTA utama di kanan pada desktop.
- Mobile: search full width dan filter dibuka melalui drawer/modal.
- Filter aktif ditampilkan sebagai chips yang dapat dihapus.
- Tombol “Reset filter” muncul jika filter berubah dari default.
- Query, filter, page, dan sort disimpan dalam URL agar dapat dibagikan dan dipulihkan.

### 6.7 Data Table

- Container surface dengan radius `--radius-lg`, border `--color-border`.
- Header memakai `--color-surface-subtle` dan token `--type-caption`.
- Row memakai `--color-surface`; hover memakai `--color-primary-soft` dengan intensitas lembut.
- Kolom angka rata kanan dan tabular numeric.
- Kolom tindakan memiliki menu overflow untuk aksi sekunder.
- Header sortable menampilkan arah sort dan label aksesibel.
- Checkbox bulk action hanya ditambahkan jika kebutuhan bulk benar-benar ada.
- Pagination memuat total data, range aktif, previous/next, dan ukuran halaman bila diperlukan.
- Empty, loading, dan error state ditampilkan di dalam container yang sama untuk mencegah layout shift.

**Responsive table:**

- Kolom prioritas tinggi tetap terlihat.
- Metadata sekunder masuk ke expandable row atau card list.
- Tidak memaksa seluruh tabel mengecil hingga tidak terbaca.
- Horizontal scroll hanya menjadi fallback terakhir dan diberi petunjuk visual.

### 6.8 Status Badge

| Status | Token Warna | Label Contoh |
| --- | --- | --- |
| Success | `--color-success`, `--color-success-soft` | Aktif, Hadir, Stok Tersedia |
| Warning | `--color-warning`, `--color-warning-soft` | Stok Rendah, Terlambat |
| Danger | `--color-danger`, `--color-danger-soft` | Tidak Aktif, Stok Habis, Gagal |
| Info | `--color-info`, `--color-info-soft` | Informasi, Disesuaikan |
| Neutral | `--color-text-muted`, `--color-surface-subtle` | Draft, Belum Pulang |

Badge menggunakan ikon atau dot + label teks. Badge tidak boleh menjadi satu-satunya kontrol interaktif.

### 6.9 Cards

- `card-standard`: surface, border, radius `--radius-lg`, padding `--space-card`.
- `card-stat`: icon tile, label, nilai token `--type-stat`, trend/context, dan optional link.
- `card-alert`: background soft status, ikon, judul, deskripsi, dan action.
- `card-product`: gambar rasio konsisten, nama, kategori, harga, stok, dan status.
- Kartu interaktif memiliki focus-visible yang sama jelasnya dengan hover.

### 6.10 Modal & Confirmation Dialog

- Menggunakan elemen dialog yang tepat dan focus trap.
- Backdrop `--color-overlay`.
- Radius `--radius-xl`, shadow `--shadow-md`.
- Judul, deskripsi dampak, content opsional, cancel, dan confirm.
- Focus awal pada tombol aman/cancel untuk tindakan destruktif.
- Escape menutup dialog jika tidak sedang memproses.
- Saat loading, dialog tidak dapat ditutup secara tidak sengaja.

### 6.11 Dropdown & Popover

- Surface, border, `--shadow-sm`, radius `--radius-md`.
- Item memiliki icon opsional, label, shortcut opsional, hover, focus, disabled.
- Aksi destruktif dipisahkan divider.
- Menutup saat Escape atau klik di luar.

### 6.12 Toast, Alert, dan Flash Message

- Toast untuk hasil aksi singkat; inline alert untuk masalah yang perlu tetap terlihat.
- Success, warning, danger, dan info memakai token status.
- Pesan menjelaskan hasil dan langkah berikutnya bila gagal.
- Toast dapat ditutup dan tidak menghilang terlalu cepat saat berisi informasi penting.
- Flash message Laravel dipetakan ke komponen alert/toast yang konsisten.

### 6.13 Loading & Skeleton

- Button memakai spinner kecil dan label proses, misalnya “Menyimpan…”.
- Halaman awal memakai skeleton yang menyerupai struktur konten.
- Tabel memakai skeleton row, bukan spinner tunggal di ruang kosong.
- Operasi latar belakang dapat memakai progress bila durasinya terukur.
- Tidak menampilkan skeleton dan konten kosong secara bersamaan.

### 6.14 Empty State

- Ikon/ilustrasi bakery sederhana, judul, penjelasan, dan CTA relevan.
- Empty state hasil filter berbeda dari empty state data awal.
- Contoh: “Belum ada produk” + “Tambahkan produk pertama”.
- Tidak menggunakan teks generik “No data”.

### 6.15 Breadcrumb

- Ditampilkan pada desktop untuk halaman detail/form mendalam.
- Item terakhir bukan link dan memakai `aria-current="page"`.
- Mobile cukup menggunakan back action dan judul halaman.

### 6.16 Image Upload

- Drag-and-drop dan file picker.
- Menampilkan preview, nama, ukuran, status upload, ganti, dan hapus.
- Menjelaskan tipe, dimensi, dan ukuran maksimum setelah keputusan backend tersedia.
- Error file spesifik dan dapat diperbaiki.
- Alt text produk diturunkan dari nama produk bila gambar bersifat representatif.

### 6.17 Date & Time

- Format tampilan Bahasa Indonesia.
- Data disimpan sesuai standar backend dan timezone yang disepakati.
- Date range menyediakan preset relevan: Hari Ini, 7 Hari, 30 Hari, Bulan Ini.
- Waktu absensi tampil jelas hingga menit; detik hanya bila diperlukan audit.

---

## 7. Spesifikasi Halaman Laravel Breeze

Semua halaman berikut wajib mengganti layout, class, komponen, logo, warna, dan spacing bawaan Breeze.

### 7.1 Login

**Tujuan:** pengguna masuk dengan cepat dan memahami bahwa panel hanya untuk tim toko.

**Konten:**

- Brand mark.
- Eyebrow “Panel Operasional”.
- `h1`: “Selamat datang kembali”.
- Deskripsi singkat.
- Email.
- Password dengan visibility toggle.
- “Ingat saya”.
- Link lupa password.
- CTA “Masuk ke Dashboard”.
- Pesan validation dan session status.
- Panel visual bakery pada desktop.

**State:** default, invalid credential, validation error, loading, session expired, account inactive.

### 7.2 Lupa Kata Sandi

- Penjelasan proses singkat.
- Field email.
- CTA “Kirim Tautan Reset”.
- Back link ke login.
- Success state yang tidak membocorkan status akun secara tidak aman.

### 7.3 Reset Kata Sandi

- Email, password baru, konfirmasi password.
- Panduan password.
- CTA “Simpan Kata Sandi Baru”.
- State token tidak valid/kedaluwarsa dengan jalur meminta tautan baru.

### 7.4 Verifikasi Email

- Ilustrasi amplop bakery sederhana.
- Penjelasan email verifikasi.
- CTA kirim ulang.
- Status setelah pengiriman ulang.
- Logout sebagai secondary action.

### 7.5 Konfirmasi Kata Sandi

- Digunakan sebelum aksi sensitif.
- Konteks menjelaskan mengapa konfirmasi diperlukan.
- Password field dan CTA.
- Tidak tampil seperti halaman login baru yang membingungkan.

### 7.6 Profile & Account Settings

Halaman dibagi menjadi section cards:

1. Informasi profil.
2. Perbarui password.
3. Preferensi tampilan/sidebar bila diterapkan.
4. Hapus akun hanya bila kebijakan mengizinkan.

Aksi hapus akun ditempatkan dalam danger zone terpisah, tidak dekat tombol simpan biasa, dan menggunakan modal konfirmasi.

---

## 8. Spesifikasi Halaman Operasional

### 8.1 Dashboard Pemilik

**Header:** salam berdasarkan waktu, tanggal, deskripsi singkat, CTA tambah produk atau catat stok.

**Stat cards:**

- Total produk aktif.
- Produk stok rendah.
- Karyawan aktif.
- Kehadiran hari ini.

**Konten utama:**

- Daftar stok perlu perhatian.
- Aktivitas stok terbaru.
- Ringkasan absensi hari ini.
- Quick actions.

**State:** loading, data parsial gagal, semua stok aman, belum ada produk, belum ada karyawan.

### 8.2 Dashboard Karyawan

- CTA absensi masuk/pulang sebagai fokus utama.
- Status absensi hari ini.
- Quick action stok masuk/keluar.
- Produk stok rendah yang memerlukan perhatian.
- Aktivitas pengguna terbaru.
- Tidak menampilkan statistik administratif yang tidak diizinkan.

### 8.3 Daftar Produk

- Page header + CTA “Tambah Produk”.
- Search nama/SKU jika SKU disetujui.
- Filter kategori, status, dan kondisi stok.
- Toggle tampilan table/card bersifat opsional; default table untuk efisiensi.
- Kolom: gambar, produk, kategori, harga, stok, status, diperbarui, tindakan.
- Menu tindakan: lihat, edit, atur stok, nonaktifkan.

### 8.4 Tambah/Edit Produk

Form section:

1. Informasi dasar.
2. Kategori dan harga.
3. Gambar produk.
4. Pengaturan stok rendah.
5. Status publikasi/aktif.

- Form desktop dua kolom hanya untuk field pendek yang berkaitan.
- Deskripsi dan upload gambar memenuhi lebar.
- Sticky save bar pada form panjang.
- Unsaved changes warning sebelum keluar.
- Edit menampilkan metadata perubahan terakhir.

### 8.5 Detail Produk

- Hero ringkas: gambar, nama, kategori, harga, status.
- Stock summary dan ambang stok rendah.
- Informasi produk.
- Riwayat pergerakan stok terbaru.
- CTA edit dan catat pergerakan stok.

### 8.6 Kategori Produk

- Daftar ringkas atau table.
- Nama, jumlah produk, status, updated time, action.
- Create/edit melalui modal untuk data sederhana atau halaman khusus bila field bertambah.
- Cegah menonaktifkan kategori tanpa menjelaskan dampak ke produk terkait.

### 8.7 Stok Terkini

- Summary cards: total SKU aktif, stok aman, stok rendah, stok habis.
- Search dan filter kondisi.
- Kolom: produk, stok terkini, ambang, kondisi, terakhir berubah, tindakan.
- Stok rendah/habis diprioritaskan secara visual dan sort.

### 8.8 Catat Stok Masuk/Keluar

- Product selector dengan gambar mini dan stok terkini.
- Segmented control tipe pergerakan.
- Quantity dengan batas valid.
- Alasan/keterangan.
- Preview “stok sebelum → stok setelah”.
- Confirmation summary sebelum submit untuk perubahan signifikan.
- Success state memberi link ke riwayat transaksi.

### 8.9 Penyesuaian Stok

- Hanya untuk role berizin.
- Penjelasan bahwa aksi tercatat dalam audit.
- Stok sistem, stok fisik, selisih otomatis, alasan wajib.
- Confirmation dialog menyebut produk, nilai lama, nilai baru, dan selisih.

### 8.10 Riwayat Pergerakan Stok

- Filter produk, tipe, pelaku, dan periode.
- Kolom: waktu, produk, tipe, jumlah, sebelum, sesudah, pelaku, catatan.
- Tipe masuk/keluar/penyesuaian memiliki badge dan ikon berbeda.
- Detail row/modal untuk catatan panjang dan metadata.

### 8.11 Daftar Karyawan

- Search nama/email/kode.
- Filter status.
- Kolom: avatar/inisial, nama, kode, kontak, status, absensi terakhir, tindakan.
- CTA tambah karyawan.
- Aksi menonaktifkan memakai confirmation dialog.

### 8.12 Tambah/Edit Karyawan

- Informasi akun.
- Informasi profil kerja.
- Role dan izin yang tersedia.
- Status akun.
- Password awal dikirim atau ditetapkan sesuai keputusan keamanan yang masih TBD.
- Permission summary menjelaskan akses role yang dipilih.

### 8.13 Detail Karyawan

- Identitas dan status.
- Ringkasan kehadiran.
- Riwayat absensi.
- Aktivitas stok yang dilakukan.
- CTA edit/nonaktifkan sesuai izin.

### 8.14 Absensi Karyawan

- Clock panel dengan tanggal dan waktu lokal.
- Status hari ini.
- CTA Masuk atau Pulang hanya ditampilkan jika valid.
- Timeline hari ini.
- Pesan jelas untuk duplikasi atau status tidak lengkap.
- Riwayat pribadi di bawah panel utama.

### 8.15 Rekap Absensi Pemilik

- Summary: hadir, belum hadir, terlambat setelah aturan tersedia, data tidak lengkap.
- Filter karyawan, status, periode.
- Kolom: tanggal, karyawan, masuk, pulang, durasi, status, koreksi.
- Koreksi absensi memerlukan alasan dan audit actor.

### 8.16 Laporan

- Report cards untuk stok terkini, pergerakan stok, dan absensi.
- Filter periode konsisten.
- Summary terlebih dahulu, detail table berikutnya.
- Export button disabled dengan tooltip sampai format ekspor diterapkan.
- Print stylesheet hanya ditambahkan jika kebutuhan cetak disetujui.

### 8.17 Error Pages

**403:** akses tidak diizinkan, CTA kembali ke dashboard yang sesuai.  
**404:** halaman tidak ditemukan, CTA kembali dan link bantuan.  
**419:** sesi berakhir, CTA login kembali tanpa menyalahkan pengguna.  
**500:** terjadi gangguan, reference ID jika backend menyediakan, CTA coba lagi.

Semua error page memakai brand dan ilustrasi bakery yang ringan, bukan template error Laravel bawaan.

---

## 9. Responsivitas

| Breakpoint | Kondisi | Perubahan Utama |
| --- | --- | --- |
| Mobile | `< 40rem` | Drawer navigation, satu kolom, full-width controls, responsive cards, filter drawer |
| Tablet | `40rem–64rem` | Sidebar ringkas, grid dua kolom selektif, detail row table |
| Desktop | `> 64rem` | Sidebar penuh, toolbar horizontal, multi-column dashboard |
| Wide | `> 90rem` | Konten berhenti pada `--content-max`, ruang luar bertambah |

### 9.1 Aturan Mobile

- Tidak ada teks, input, atau tombol di bawah target sentuh minimum.
- CTA utama form dapat sticky, tetapi tidak menutupi pesan validasi.
- Modal kompleks berubah menjadi full-height sheet/drawer.
- Tabel berubah menjadi card rows atau expandable summaries.
- Aksi row ditempatkan dalam overflow menu.
- Breadcrumb disederhanakan menjadi back action.

### 9.2 Aturan Desktop

- Area kosong digunakan untuk meningkatkan scannability, bukan diisi dekorasi.
- Toolbar tidak boleh terpecah secara tidak teratur; gunakan wrapping terkontrol.
- Form panjang tetap dibatasi `--form-max` agar mudah dibaca.
- Data table dapat memakai sticky header bila panjang.

### 9.3 Container Queries

Komponen reusable seperti stat card, toolbar, dan product card menyesuaikan berdasarkan lebar container ketika dukungan teknis memungkinkan, sehingga tidak hanya bergantung pada viewport global.

---

## 10. Frontend Formulas

### 10.1 Fluid Typography

```css
:root {
  --type-display-lg: clamp(2.5rem, 5vw, 4.5rem);
  --type-display-md: clamp(2rem, 3.5vw, 3.25rem);
  --type-heading-xl: clamp(1.75rem, 2.5vw, 2.5rem);
  --type-heading-lg: clamp(1.5rem, 2vw, 2rem);
}
```

### 10.2 Fluid Page Spacing

```css
:root {
  --space-page-x: clamp(var(--space-4), 3vw, var(--space-10));
  --space-page-y: clamp(var(--space-6), 3vw, var(--space-12));
  --space-section: clamp(var(--space-8), 4vw, var(--space-16));
  --space-card: clamp(var(--space-4), 2vw, var(--space-8));
}
```

### 10.3 Golden Ratio Layout

```css
.auth-shell {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1.618fr);
}

.dashboard-primary-grid {
  display: grid;
  grid-template-columns: minmax(0, 1.618fr) minmax(18rem, 1fr);
}
```

Pada viewport sempit, layout berubah menjadi satu kolom melalui breakpoint token, bukan dipaksa mempertahankan rasio.

### 10.4 Auto-Fit Grid

```css
.stat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(min(100%, 14rem), 1fr));
  gap: var(--space-4);
}
```

### 10.5 Motion

```css
.interactive-control {
  transition:
    color var(--duration-fast) var(--ease-snappy),
    background-color var(--duration-fast) var(--ease-snappy),
    border-color var(--duration-fast) var(--ease-snappy),
    box-shadow var(--duration-fast) var(--ease-snappy),
    transform var(--duration-fast) var(--ease-snappy);
}
```

Tidak menggunakan `transition: all` pada implementasi final agar perubahan properti tetap terkontrol.

---

## 11. Accessibility Specification

- Kontras teks dan kontrol menargetkan WCAG 2.2 AA.
- Semua fungsi dapat digunakan melalui keyboard.
- Focus-visible tidak pernah dihapus tanpa pengganti.
- Skip link tersedia menuju konten utama.
- Sidebar, drawer, modal, dropdown, tab, dan tooltip memakai semantics/ARIA yang sesuai.
- Form error terhubung ke field menggunakan `aria-describedby` dan ringkasan error tersedia bila form panjang.
- Status async diumumkan melalui live region dengan tingkat urgensi yang tepat.
- Ikon dekoratif disembunyikan dari accessibility tree.
- Icon button memiliki accessible name.
- Heading hierarchy berurutan dan hanya ada satu `h1` per halaman.
- Tabel memakai caption atau heading kontekstual serta header scope yang benar.
- Target pointer memenuhi ukuran minimum yang ditentukan.
- Grafik, bila ditambahkan, wajib memiliki ringkasan tekstual atau tabel data.
- Motion mengikuti `prefers-reduced-motion`.
- Zoom browser sampai 200% tidak menyebabkan kehilangan fungsi atau konten.

---

## 12. Data Visualization

Pada MVP, visualisasi digunakan secara hemat.

- Angka utama selalu disediakan dalam bentuk teks, tidak hanya grafik.
- Grafik tidak menggunakan warna sebagai satu-satunya pembeda.
- Palette grafik mengambil warna yang sudah tersedia: primary, accent, success, info, warning, dan danger.
- Tooltip dapat dibuka keyboard bila grafik interaktif.
- Dashboard memprioritaskan cards dan list untuk data operasional cepat.
- Grafik tren hanya ditambahkan ketika data historis dan kebutuhan bisnis sudah disepakati.

---

## 13. Asset & Dummy Data Specification

### 13.1 Gaya Fotografi Produk

- Foto produk tunggal atau kelompok kecil di atas permukaan stone/kayu terang.
- Pencahayaan natural lembut dari samping.
- Warna hangat tetapi makanan tetap realistis.
- Latar bersih dengan props minimal seperti linen, tepung, atau cooling rack.
- Tidak menampilkan logo merek pihak ketiga.
- Tidak ada teks tertanam di gambar.
- Produk berada di safe area agar aman di-crop.
- Rasio master `4:3`; turunan thumbnail memakai crop konsisten.

### 13.2 Daftar Aset Dummy yang Dibuat Saat Implementasi

| ID Aset | Isi | Rasio | Penggunaan |
| --- | --- | --- | --- |
| `bakery-auth-hero` | Etalase bakery cerah dengan roti artisan | 3:2 | Panel autentikasi desktop |
| `product-croissant` | Croissant butter berlapis | 4:3 | Produk dummy |
| `product-sourdough` | Sourdough boule dengan scoring | 4:3 | Produk dummy |
| `product-cinnamon-roll` | Cinnamon roll dengan glaze | 4:3 | Produk dummy |
| `product-chocolate-bun` | Roti cokelat lembut | 4:3 | Produk dummy |
| `product-baguette` | Baguette artisan | 4:3 | Produk dummy |
| `product-milk-bread` | Roti susu lembut | 4:3 | Produk dummy |
| `product-danish-berry` | Danish buah beri | 4:3 | Produk dummy |
| `product-cheese-bread` | Roti keju panggang | 4:3 | Produk dummy |
| `empty-products` | Ilustrasi keranjang roti kosong | 1:1 | Empty state |
| `error-bakery` | Ilustrasi oven/roti sederhana | 1:1 | Error pages |

### 13.3 Data Dummy Produk

| Nama | Kategori | Harga Dummy | Stok Awal | Ambang Rendah | Status |
| --- | --- | ---: | ---: | ---: | --- |
| Butter Croissant | Pastry | Rp18.000 | 24 | 8 | Aktif |
| Rustic Sourdough | Artisan Bread | Rp42.000 | 10 | 4 | Aktif |
| Cinnamon Cloud Roll | Sweet Bread | Rp22.000 | 16 | 6 | Aktif |
| Chocolate Soft Bun | Sweet Bread | Rp16.000 | 20 | 7 | Aktif |
| Classic Baguette | Artisan Bread | Rp28.000 | 12 | 5 | Aktif |
| Hokkaido Milk Bread | Loaf | Rp38.000 | 8 | 4 | Aktif |
| Berry Custard Danish | Pastry | Rp24.000 | 14 | 5 | Aktif |
| Golden Cheese Bread | Savory Bread | Rp20.000 | 18 | 6 | Aktif |

Harga, kategori, stok, dan nama tersebut bersifat demonstrasi dan harus dapat diubah melalui seeder sebelum data produksi digunakan.

### 13.4 Dummy Operational Data

- Minimal satu akun Pemilik/Pengelola.
- Minimal empat akun Karyawan dengan status aktif/nonaktif bervariasi.
- Riwayat stok masuk, keluar, dan penyesuaian dalam rentang tiga puluh hari.
- Produk dengan kondisi stok aman, rendah, dan habis.
- Absensi lengkap, belum pulang, serta data koreksi untuk demonstrasi state.
- Seeder tidak menggunakan kredensial produksi dan password dummy harus didokumentasikan hanya untuk lingkungan lokal.

---

## 14. Content & Microcopy Guidelines

- Bahasa utama: Bahasa Indonesia yang jelas, singkat, dan ramah.
- Gunakan kata kerja pada CTA: “Tambah Produk”, “Simpan Perubahan”, “Catat Stok Masuk”.
- Hindari CTA ambigu seperti “OK”, “Submit”, atau “Ya” tanpa konteks.
- Error menjelaskan apa yang salah dan cara memperbaikinya.
- Konfirmasi destruktif menyebut objek dan dampaknya.
- Gunakan sentence case, bukan seluruh teks uppercase.
- Angka uang menggunakan format Rupiah Indonesia.
- Tanggal menggunakan format yang mudah dipahami pengguna Indonesia.
- Istilah stok konsisten: “Stok Masuk”, “Stok Keluar”, “Penyesuaian Stok”.

**Contoh:**

- Baik: “Stok tidak mencukupi. Tersedia 6 item, sedangkan Anda mencoba mengeluarkan 8 item.”
- Hindari: “Error: invalid quantity.”
- Baik: “Nonaktifkan akun Dimas? Dimas tidak dapat masuk, tetapi riwayat aktivitasnya tetap tersimpan.”
- Hindari: “Are you sure?”

---

## 15. Do’s and Don’ts

### ✅ Lakukan

1. Gunakan token untuk seluruh nilai visual.
2. Gunakan surface terang dan aksen caramel secara terkendali.
3. Pertahankan satu CTA dominan per area kerja.
4. Tampilkan stok sebelum dan sesudah pada perubahan kuantitas.
5. Gunakan label teks pada semua status.
6. Buat focus state setara jelasnya dengan hover.
7. Gunakan `clamp()` untuk typography dan spacing fluid.
8. Tampilkan loading state yang mempertahankan layout.
9. Pastikan semua halaman Breeze tampil sebagai bagian dari produk yang sama.
10. Gunakan aset bakery final yang konsisten, bukan placeholder.

### ❌ Dilarang

1. Jangan mempertahankan logo, card, warna abu-abu, dan layout default Laravel Breeze.
2. Jangan menambahkan HEX, pixel, radius, shadow, atau easing baru di luar token.
3. Jangan menggunakan warna status tanpa ikon atau teks.
4. Jangan menyembunyikan label input di dalam placeholder.
5. Jangan menggunakan modal untuk setiap tindakan ringan.
6. Jangan membuat tabel desktop diperkecil sampai tidak terbaca pada mobile.
7. Jangan memakai animasi `linear`, parallax berat, atau motion dekoratif berlebihan.
8. Jangan menggunakan danger color untuk tindakan non-destruktif.
9. Jangan menampilkan menu yang tidak dapat diakses role pengguna.
10. Jangan menghapus riwayat stok/absensi ketika data induk dinonaktifkan.

---

## 16. Laravel Breeze Restyle Checklist

| Area | Wajib Diubah | Kriteria Selesai |
| --- | --- | --- |
| Logo aplikasi | Ya | Brand mark bakery menggantikan logo Breeze |
| Guest layout | Ya | Split auth layout responsif dan branded |
| App layout | Ya | Sidebar/topbar baru, tidak memakai navigation default |
| Login | Ya | Seluruh komponen dan state mengikuti design system |
| Forgot password | Ya | Visual dan microcopy konsisten |
| Reset password | Ya | Password control dan state lengkap |
| Verify email | Ya | Branded status view dan resend action |
| Confirm password | Ya | Dialog/page sensitif yang jelas |
| Profile | Ya | Section cards dan danger zone |
| Navigation dropdown | Ya | Komponen dropdown baru |
| Mobile navigation | Ya | Drawer yang aksesibel |
| Buttons | Ya | Primary, secondary, ghost, danger, loading |
| Form inputs | Ya | Label, helper, focus, error, disabled |
| Validation errors | Ya | Inline + summary bila diperlukan |
| Session status | Ya | Alert/toast konsisten |
| Pagination | Ya | Komponen custom sesuai token |
| Error pages | Ya | 403, 404, 419, 500 branded |
| Email templates | Penting | Warna, logo, dan typography email diselaraskan |
| Browser metadata | Ya | Title, description internal, favicon, theme color |

---

## 17. Quality Gate Sebelum Implementasi Dianggap Selesai

### Visual Consistency

- Tidak ada warna, font, radius, shadow, atau spacing di luar token.
- Tidak ada komponen Breeze default yang tersisa.
- Semua halaman menggunakan app shell atau guest shell yang benar.
- Tampilan konsisten pada state normal, kosong, loading, error, dan success.

### Functional UX

- Role Pemilik dan Karyawan memiliki navigasi berbeda dan benar.
- Setiap aksi stok menampilkan dampak kuantitas.
- Aksi destruktif memiliki confirmation dialog.
- Filter dapat direset dan dipertahankan melalui URL.
- Form mencegah submit berulang saat loading.

### Responsive

- Diuji pada mobile, tablet, desktop, dan wide screen.
- Tidak ada horizontal overflow yang tidak disengaja.
- Semua target sentuh memenuhi token minimum.
- Tabel memiliki strategi mobile yang dapat digunakan.

### Accessibility

- Navigasi keyboard end-to-end berhasil.
- Focus tidak terperangkap kecuali dalam modal aktif.
- Kontras lulus target WCAG 2.2 AA.
- Form error diumumkan dan terhubung ke field.
- Reduced motion didukung.

### Performance

- Font dimuat secara efisien dan memakai fallback yang stabil.
- Gambar produk memiliki dimensi, lazy loading di bawah fold, dan format modern yang sesuai.
- Hero autentikasi dioptimalkan agar tidak memperlambat render form.
- Tidak ada library animasi berat bila CSS mencukupi.
- Pagination digunakan untuk daftar besar.

---

## 18. Pertanyaan Terbuka / TBD Desain

- Apa nama brand resmi dan apakah logo toko sudah tersedia?
- Apakah palet caramel–vanilla–cocoa ini disetujui sebagai arah final?
- Apakah panel Laravel menggunakan Blade standar, Blade + Alpine.js, Livewire, atau frontend lain?
- Apakah dark mode diperlukan atau panel hanya memakai tema cerah?
- Apakah sidebar dapat diringkas oleh pengguna atau selalu tetap?
- Apakah foto karyawan digunakan atau cukup avatar inisial?
- Berapa ukuran dan tipe file maksimum untuk gambar produk?
- Apakah email transaksional Laravel juga harus didesain ulang pada MVP?
- Apakah laporan perlu layout khusus cetak?
- Apakah tabel membutuhkan bulk action?
- Apakah aplikasi mendukung Bahasa Indonesia saja?
- Timezone operasional toko apa yang digunakan?
- Apakah desain React pelanggan kelak memakai identitas visual yang sama atau varian yang lebih editorial?

---

*Dokumen ini merupakan draft sementara. Nilai dan aturan yang telah ditentukan menjadi acuan implementasi setelah arah visual dan pertanyaan TBD disetujui.*
