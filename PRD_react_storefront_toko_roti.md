# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## Storefront Pelanggan Toko Roti — React + Vite

**STATUS: DRAFT SEMENTARA**

| | |
| --- | --- |
| **Nama Produk** | Storefront Pelanggan Toko Roti |
| **Versi Dokumen** | v0.1 |
| **Disusun oleh** | TBD (Pengembang) |
| **Untuk** | TBD (Pemilik Toko) |
| **Tanggal** | 25 Juli 2026 |
| **Dokumen Terkait** | PRD Sistem Manajemen Toko Roti; Design System Laravel; Design System React Storefront |

---

# 1. Ringkasan Produk (Overview)

Pelanggan membutuhkan cara yang mudah untuk menemukan produk roti, memeriksa ketersediaan, memilih cabang, memesan untuk pengantaran atau pengambilan, membayar, dan memantau pesanan tanpa harus menghubungi toko secara manual. Toko juga membutuhkan kanal penjualan digital yang konsisten dengan data produk, stok, promo, cabang, dan pesanan pada sistem Laravel.

Produk yang dibangun adalah storefront pelanggan berbasis React + Vite yang mengonsumsi API Laravel. Aplikasi mencakup katalog, pencarian, wishlist, keranjang, checkout, alamat dan pin lokasi, pickup/delivery, ongkir, voucher, Midtrans, akun pelanggan, pelacakan pesanan, ulasan, notifikasi, lokasi toko, WhatsApp, dan multi-cabang. Halaman publik penting akan diprerender agar SEO mendekati aplikasi React dengan SSR, sedangkan akun dan checkout tetap bersifat privat serta tidak diindeks.

# 2. Tujuan & Sasaran (Goals)

- Memungkinkan pelanggan menemukan dan membeli produk roti secara daring dari perangkat apa pun.
- Mengurangi pemesanan manual melalui chat dengan checkout yang jelas dan tervalidasi.
- Menyatukan informasi pelanggan, katalog, stok, promo, pembayaran, dan pesanan melalui API Laravel.
- Menyediakan pilihan pickup atau delivery dengan cabang dan jadwal yang sesuai.
- Meningkatkan visibilitas organik katalog melalui prerender, structured data, dan optimasi Core Web Vitals.
- Meningkatkan konversi dengan alur pembelian singkat, transparan, aman, dan dapat dipulihkan.
- Memberikan pelacakan pesanan dan notifikasi yang mengurangi pertanyaan status secara manual.
- Menyediakan fondasi storefront multi-cabang yang dapat dikembangkan tanpa mengganti arsitektur utama.

# 3. Pengguna & Peran (Users & Roles)

- **Pengunjung:** menjelajahi katalog, mencari produk, memilih cabang, melihat detail, lokasi, promo publik, dan menghubungi toko.
- **Pelanggan Tamu:** menambahkan produk ke keranjang dan dapat checkout sebagai tamu apabila kebijakan ini disetujui.
- **Pelanggan Terdaftar:** mengelola profil, alamat, wishlist, keranjang, pesanan, ulasan, voucher, dan preferensi notifikasi.
- **Sistem Laravel:** sumber kebenaran data produk, stok, cabang, promo, pesanan, pembayaran, dan fulfillment.
- **Midtrans:** memproses pembayaran serta mengirim webhook pembayaran ke Laravel.

# 4. Ruang Lingkup (Scope)

## 4.1 Termasuk (MVP)

- Landing page bakery dan navigasi storefront.
- Pemilihan cabang dan deteksi cabang yang melayani lokasi pelanggan.
- Katalog, kategori, pencarian, filter, sort, dan detail produk.
- Ketersediaan produk berdasarkan cabang.
- Registrasi, login, logout, lupa/reset password, dan profil pelanggan.
- Wishlist dan keranjang persisten.
- Checkout dengan data penerima, telepon, alamat, pin lokasi, catatan, pickup/delivery, jadwal, ongkir, voucher, dan ringkasan biaya.
- Pembayaran melalui Midtrans dan metode bayar di toko/COD hanya jika diizinkan.
- Konfirmasi pesanan, riwayat, detail, pembatalan sesuai kebijakan, dan pelacakan status.
- Promo/voucher, ulasan terverifikasi, notifikasi dalam aplikasi/email sesuai integrasi.
- Lokasi, jam operasional, kontak, FAQ, kebijakan, dan WhatsApp.
- Theme Light, Dark, dan System.
- SEO lanjutan untuk halaman publik melalui prerender/SSG dan metadata per route.
- Motion menggunakan GSAP + ScrollTrigger dan Framer Motion dengan pemisahan tanggung jawab.
- Analytics funnel dan monitoring error dengan persetujuan privasi yang sesuai.

## 4.2 Di Luar Lingkup Awal / Fase Lanjutan

- Marketplace multi-tenant.
- Program loyalitas berbasis poin, membership bertingkat, gift card, subscription, dan pre-order berulang.
- Live courier tracking real-time bila belum tersedia dari penyedia logistik.
- Native mobile app.
- Internasionalisasi multi-bahasa dan multi-mata-uang.
- Personalisasi berbasis AI.

# 5. Asumsi & Batasan (Assumptions & Constraints)

- **Keputusan pengguna:** frontend memakai React + Vite, bukan Next.js.
- **Keputusan pengguna:** animasi memakai GSAP + ScrollTrigger dan Framer Motion.
- **Keputusan pengguna:** tersedia theme Light, Dark, dan System.
- **Keputusan pengguna:** pembayaran digital menggunakan Midtrans.
- **Asumsi teknis:** Laravel menjadi sumber kebenaran dan menyediakan REST API berversi.
- **Asumsi teknis:** autentikasi pelanggan menggunakan Laravel Sanctum atau mekanisme aman setara; keputusan final TBD.
- **Batasan SEO:** Vite SPA tidak identik dengan SSR Next.js; halaman publik wajib diprerender/SSG pada build atau melalui prerender service agar HTML awal dapat dirayapi.
- **Batasan keamanan:** Midtrans Server Key hanya berada di Laravel. React tidak menetapkan status pembayaran dari callback browser.
- **Batasan stok:** ketersediaan final diverifikasi Laravel saat checkout dan pembuatan pesanan.
- **Batasan lokasi:** geocoding, peta, zonasi, dan ongkir memerlukan penyedia eksternal yang belum dipilih.
- **Batasan animasi:** GSAP dan Framer Motion tidak boleh menulis properti transform/opacity yang sama pada elemen yang sama.
- **Asumsi mata uang:** transaksi menggunakan Rupiah Indonesia.
- **Asumsi bahasa:** antarmuka MVP menggunakan Bahasa Indonesia.

# 6. Kebutuhan Fungsional (Functional Requirements)

## 6.1 Pengunjung — Navigasi & Landing Page

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **HOME-1** | Pengunjung dapat melihat hero, kategori unggulan, produk populer, promo, keunggulan toko, cabang, testimoni, dan CTA pemesanan. | **Wajib** |
| **HOME-2** | Sistem menampilkan status cabang terpilih dan memungkinkan penggantian cabang. | **Wajib** |
| **HOME-3** | Pengunjung dapat membuka halaman Tentang, Lokasi, FAQ, Kontak, dan Kebijakan. | **Penting** |
| **HOME-4** | Pengunjung dapat membuka WhatsApp dengan pesan awal kontekstual tanpa membagikan data sensitif. | **Penting** |

## 6.2 Pengunjung — Katalog, Pencarian & Produk

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **CAT-1** | Pengguna dapat melihat produk aktif yang tersedia pada cabang terpilih. | **Wajib** |
| **CAT-2** | Pengguna dapat mencari produk berdasarkan nama dan kata kunci. | **Wajib** |
| **CAT-3** | Pengguna dapat memfilter berdasarkan kategori, harga, ketersediaan, dan atribut yang disetujui. | **Wajib** |
| **CAT-4** | Pengguna dapat mengurutkan berdasarkan relevansi, terbaru, harga, dan popularitas. | **Penting** |
| **CAT-5** | Filter, sort, pencarian, kategori, dan page direpresentasikan pada URL. | **Wajib** |
| **CAT-6** | Sistem menampilkan empty state dan saran pemulihan ketika hasil tidak ditemukan. | **Wajib** |
| **PROD-1** | Pengguna dapat melihat nama, galeri, kategori, harga, deskripsi, stok, cabang, komposisi, alergen, rating, dan ulasan produk. | **Wajib** |
| **PROD-2** | Pengguna dapat memilih varian dan jumlah jika produk mendukung varian. | **Penting** |
| **PROD-3** | Sistem mencegah jumlah melebihi batas pembelian atau stok yang diketahui. | **Wajib** |
| **PROD-4** | Pengguna dapat melihat produk terkait. | **Penting** |
| **PROD-5** | Sistem memperbarui harga dan ketersediaan ketika cabang atau varian berubah. | **Wajib** |

## 6.3 Pelanggan — Akun & Autentikasi

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **ACC-1** | Pengunjung dapat membuat akun menggunakan nama, email, telepon, dan kata sandi. | **Wajib** |
| **ACC-2** | Pelanggan dapat login, logout, meminta reset password, dan memverifikasi email. | **Wajib** |
| **ACC-3** | Pelanggan dapat memperbarui profil dan preferensi notifikasi. | **Wajib** |
| **ACC-4** | Pelanggan dapat mengelola beberapa alamat. | **Wajib** |
| **ACC-5** | Pelanggan dapat memilih alamat utama. | **Penting** |
| **ACC-6** | Pelanggan dapat meminta penghapusan akun sesuai kebijakan privasi. | **Penting** |
| **ACC-7** | Keranjang tamu dapat digabungkan secara aman setelah login. | **Penting** |
| **ACC-8** | Pengguna dapat checkout sebagai tamu jika kebijakan toko mengizinkan. | **Penting** |

## 6.4 Pelanggan — Wishlist

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **WISH-1** | Pelanggan dapat menambah dan menghapus produk dari wishlist. | **Penting** |
| **WISH-2** | Wishlist tersinkron antarperangkat untuk pelanggan login. | **Penting** |
| **WISH-3** | Pelanggan dapat memindahkan produk tersedia dari wishlist ke keranjang. | **Penting** |
| **WISH-4** | Sistem menjelaskan bila produk wishlist tidak aktif atau tidak tersedia di cabang. | **Wajib** |

## 6.5 Pengguna — Keranjang

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **CART-1** | Pengguna dapat menambah produk/varian dan jumlah ke keranjang. | **Wajib** |
| **CART-2** | Pengguna dapat mengubah jumlah, menghapus item, dan mengosongkan keranjang. | **Wajib** |
| **CART-3** | Sistem menghitung subtotal dan estimasi diskon secara konsisten. | **Wajib** |
| **CART-4** | Keranjang tamu bertahan setelah refresh dan memiliki masa berlaku yang jelas. | **Wajib** |
| **CART-5** | Keranjang pelanggan login tersinkron dengan backend. | **Penting** |
| **CART-6** | Sistem memvalidasi ulang harga, promo, stok, cabang, dan batas kuantitas sebelum checkout. | **Wajib** |
| **CART-7** | Sistem menjelaskan item yang berubah harga, habis, atau tidak tersedia di cabang. | **Wajib** |
| **CART-8** | Pergantian cabang meminta konfirmasi bila memengaruhi isi keranjang. | **Wajib** |

## 6.6 Pelanggan — Promo & Voucher

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PROM-1** | Pengguna dapat melihat promo aktif beserta syaratnya. | **Penting** |
| **PROM-2** | Pengguna dapat memasukkan dan menghapus kode voucher pada keranjang/checkout. | **Wajib** |
| **PROM-3** | Laravel memvalidasi periode, kuota, minimum belanja, cabang, produk, pelanggan, dan penggunaan voucher. | **Wajib** |
| **PROM-4** | Sistem menampilkan alasan voucher tidak dapat digunakan. | **Wajib** |
| **PROM-5** | Sistem menghitung diskon final di server dan menampilkannya pada rincian harga. | **Wajib** |

## 6.7 Pelanggan — Checkout & Data Penerima

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **CHKO-1** | Checkout menampilkan tahapan Kontak, Fulfillment, Alamat/Lokasi, Jadwal, Pembayaran, dan Tinjau Pesanan. | **Wajib** |
| **CHKO-2** | Pelanggan dapat mengisi nama penerima, nomor telepon, email, dan catatan pesanan. | **Wajib** |
| **CHKO-3** | Pelanggan dapat memilih alamat tersimpan atau membuat alamat baru. | **Wajib** |
| **CHKO-4** | Alamat mencakup label, penerima, telepon, baris alamat, wilayah administratif, kode pos, landmark, dan instruksi kurir. | **Wajib** |
| **CHKO-5** | Pelanggan dapat menetapkan pin latitude/longitude pada peta dan menyesuaikannya. | **Wajib** |
| **CHKO-6** | Sistem memvalidasi kelengkapan dan cakupan layanan alamat. | **Wajib** |
| **CHKO-7** | Checkout menyimpan progres non-sensitif agar dapat dipulihkan. | **Penting** |
| **CHKO-8** | Sistem mencegah submit ganda menggunakan idempotency key. | **Wajib** |
| **CHKO-9** | Sebelum order dibuat, Laravel memvalidasi ulang stok, harga, promo, ongkir, slot, dan cabang. | **Wajib** |
| **CHKO-10** | Pelanggan wajib menyetujui syarat pembelian dan kebijakan privasi. | **Wajib** |

## 6.8 Pelanggan — Pickup, Delivery, Cabang & Jadwal

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **FULF-1** | Pelanggan dapat memilih pickup atau delivery. | **Wajib** |
| **FULF-2** | Sistem merekomendasikan cabang berdasarkan lokasi, ketersediaan, jam buka, dan cakupan layanan. | **Wajib** |
| **FULF-3** | Pelanggan dapat memilih cabang lain jika masih memenuhi persyaratan. | **Penting** |
| **FULF-4** | Sistem menampilkan alamat, jarak, jam buka, kontak, dan petunjuk cabang. | **Wajib** |
| **FULF-5** | Pelanggan dapat memilih slot pickup/delivery yang tersedia. | **Wajib** |
| **FULF-6** | Sistem menolak slot penuh, lampau, atau di luar jam operasional. | **Wajib** |
| **FULF-7** | Sistem menghitung ongkir di Laravel berdasarkan penyedia dan aturan yang disepakati. | **Wajib** |
| **FULF-8** | Sistem menampilkan estimasi waktu, ongkir, dan keterbatasan layanan sebelum pembayaran. | **Wajib** |
| **FULF-9** | Sistem meminta izin browser sebelum mengambil geolocation dan menyediakan input manual sebagai fallback. | **Wajib** |

## 6.9 Pelanggan — Pembayaran Midtrans

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PAY-1** | Laravel membuat order internal dan transaksi Midtrans menggunakan Server Key di server. | **Wajib** |
| **PAY-2** | React membuka pengalaman pembayaran Midtrans menggunakan token/client configuration yang diberikan Laravel. | **Wajib** |
| **PAY-3** | Pelanggan dapat memilih metode pembayaran yang diaktifkan toko melalui Midtrans. | **Wajib** |
| **PAY-4** | Status pembayaran final hanya diperbarui setelah Laravel memverifikasi webhook Midtrans. | **Wajib** |
| **PAY-5** | Sistem menangani status pending, paid/settlement, failed/deny, expired, cancel, refund, dan chargeback bila relevan. | **Wajib** |
| **PAY-6** | Halaman hasil browser tidak mengklaim pembayaran berhasil sebelum status server terkonfirmasi. | **Wajib** |
| **PAY-7** | Pelanggan dapat melanjutkan pembayaran yang masih pending selama belum kedaluwarsa. | **Wajib** |
| **PAY-8** | Sistem menampilkan instruksi pembayaran dan batas waktu bila metode memerlukannya. | **Wajib** |
| **PAY-9** | Webhook diverifikasi signature/status-nya, idempotent, dan dicatat untuk audit. | **Wajib** |
| **PAY-10** | Metode bayar di toko/COD hanya muncul berdasarkan fulfillment, cabang, nominal, dan kebijakan. | **Penting** |

## 6.10 Pelanggan — Pesanan & Pelacakan

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **ORD-1** | Pelanggan melihat konfirmasi yang berisi nomor order, item, biaya, fulfillment, alamat/cabang, jadwal, dan status pembayaran. | **Wajib** |
| **ORD-2** | Pelanggan login dapat melihat riwayat dan detail pesanan. | **Wajib** |
| **ORD-3** | Pelanggan tamu dapat membuka pesanan melalui mekanisme aman yang disepakati. | **Penting** |
| **ORD-4** | Sistem menampilkan timeline status order. | **Wajib** |
| **ORD-5** | Pelanggan dapat membatalkan pesanan hanya pada status dan periode yang diizinkan. | **Penting** |
| **ORD-6** | Pelanggan dapat memesan ulang item yang masih tersedia. | **Penting** |
| **ORD-7** | Sistem menjelaskan perubahan/substitusi/refund sesuai kebijakan toko. | **Penting** |

Status bisnis minimum: `Menunggu Pembayaran`, `Pembayaran Diverifikasi`, `Diproses`, `Siap Diambil`, `Dalam Pengantaran`, `Selesai`, `Dibatalkan`, dan `Refund`.

## 6.11 Pelanggan — Ulasan

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **REV-1** | Pelanggan dapat memberi rating dan ulasan hanya untuk produk dari order selesai. | **Penting** |
| **REV-2** | Sistem mencegah ulasan ganda untuk item order yang sama. | **Wajib** |
| **REV-3** | Pengguna dapat melihat rating agregat dan ulasan yang dipublikasikan. | **Penting** |
| **REV-4** | Pelanggan dapat mengedit atau menghapus ulasannya sesuai kebijakan. | **Penting** |
| **REV-5** | Ulasan memiliki status moderasi dan tidak mempublikasikan konten berbahaya secara otomatis. | **Penting** |

## 6.12 Pelanggan — Notifikasi

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **NOTF-1** | Sistem mengirim notifikasi untuk order dibuat, pembayaran, perubahan status, siap diambil/dikirim, selesai, batal, dan refund. | **Wajib** |
| **NOTF-2** | Pelanggan dapat melihat notification center dalam aplikasi. | **Penting** |
| **NOTF-3** | Pelanggan dapat menandai notifikasi dibaca. | **Penting** |
| **NOTF-4** | Pelanggan dapat mengatur kanal notifikasi non-esensial. | **Penting** |
| **NOTF-5** | Notifikasi transaksional tetap dikirim sesuai kebutuhan operasional dan kebijakan. | **Wajib** |

## 6.13 Pengguna — Theme & Preferensi Tampilan

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **THEM-1** | Pengguna dapat memilih tema Light, Dark, atau System. | **Wajib** |
| **THEM-2** | Preferensi tema disimpan lokal dan disinkron ke profil bila pengguna login. | **Penting** |
| **THEM-3** | Tema diterapkan sebelum paint pertama untuk mencegah flash tema salah. | **Wajib** |
| **THEM-4** | Semua tema memenuhi kontras dan memiliki token semantik lengkap. | **Wajib** |

## 6.14 Sistem — SEO & Social Discovery

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **SEO-1** | Route publik penting diprerender menjadi HTML saat build/deploy menggunakan data yang aman dipublikasikan. | **Wajib** |
| **SEO-2** | Setiap route publik memiliki title, meta description, canonical, robots, Open Graph, dan Twitter Card unik. | **Wajib** |
| **SEO-3** | Sistem menghasilkan sitemap index dan sitemap produk/kategori/halaman yang diperbarui saat konten berubah. | **Wajib** |
| **SEO-4** | Halaman produk menggunakan JSON-LD Product, Offer, AggregateRating hanya jika data valid. | **Wajib** |
| **SEO-5** | Bisnis/cabang menggunakan Bakery atau LocalBusiness JSON-LD dengan data yang konsisten. | **Wajib** |
| **SEO-6** | BreadcrumbList dan WebSite/SearchAction digunakan bila implementasi sesuai perilaku nyata. | **Penting** |
| **SEO-7** | Route akun, wishlist privat, cart, checkout, payment, dan order diberi `noindex`. | **Wajib** |
| **SEO-8** | Filter kombinatorial tidak menciptakan index bloat; canonical dan robots ditetapkan berdasarkan strategi. | **Wajib** |
| **SEO-9** | Server mengembalikan status HTTP benar untuk 200, redirect, 404, dan 5xx; tidak memakai soft 404. | **Wajib** |
| **SEO-10** | Hasil prerender dapat digunakan tanpa JavaScript untuk konten utama dan link crawlable. | **Wajib** |

## 6.15 Sistem — Animasi & Motion

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **MOT-1** | GSAP + ScrollTrigger menangani storytelling scroll, hero sequence, pinned section, dan reveal kompleks. | **Penting** |
| **MOT-2** | Framer Motion menangani route transition, modal, drawer, cart, filter, layout transition, dan state React. | **Wajib** |
| **MOT-3** | Satu elemen tidak dikontrol GSAP dan Framer Motion pada properti transform/opacity yang sama. | **Wajib** |
| **MOT-4** | Motion dekoratif dinonaktifkan atau disederhanakan pada `prefers-reduced-motion`. | **Wajib** |
| **MOT-5** | Animasi tidak menunda LCP, mengganggu scrolling, atau memblokir interaksi. | **Wajib** |

# 7. Alur Pengguna Utama (Key User Flows)

## 7.1 Menemukan Produk dan Menambahkan ke Keranjang

1. Pengguna membuka landing page yang sudah diprerender.
2. Sistem meminta atau menggunakan cabang pilihan sebelumnya.
3. Pengguna mencari atau memfilter produk.
4. Pengguna membuka detail dan memilih varian/jumlah.
5. Sistem menampilkan harga dan stok cabang.
6. Pengguna menambahkan item; mini-cart memberi feedback.
7. Laravel memvalidasi data ketika cart disinkronkan.

## 7.2 Checkout Delivery dan Midtrans

1. Pengguna membuka keranjang dan memilih checkout.
2. Sistem memvalidasi ulang item, stok, harga, dan cabang.
3. Pengguna login, melanjutkan sebagai tamu jika diizinkan, atau membuat akun.
4. Pengguna mengisi kontak dan memilih delivery.
5. Pengguna memilih alamat atau membuat alamat baru serta menetapkan pin lokasi.
6. Laravel memeriksa cakupan layanan dan menghitung ongkir.
7. Pengguna memilih slot delivery, menerapkan voucher, dan meninjau rincian biaya.
8. Pengguna menyetujui syarat dan memilih Midtrans.
9. Laravel memvalidasi ulang checkout, membuat order idempotent, mengunci/reservasi stok sesuai kebijakan, dan membuat transaksi Midtrans.
10. React membuka pembayaran menggunakan token yang aman.
11. Midtrans mengirim webhook ke Laravel; Laravel memverifikasi dan memperbarui status.
12. React mengambil status terbaru dan menampilkan konfirmasi yang akurat.
13. Sistem mengirim notifikasi order dan pembayaran.

## 7.3 Checkout Pickup

1. Pengguna memilih pickup.
2. Sistem menampilkan cabang, jam buka, stok, dan slot tersedia.
3. Pengguna memilih cabang serta jadwal pickup.
4. Ongkir menjadi nol; biaya lain dijelaskan bila ada.
5. Pengguna membayar melalui Midtrans atau bayar di toko jika diizinkan.
6. Setelah order diproses, pelanggan menerima status “Siap Diambil”.
7. Pelanggan mengambil pesanan dengan menunjukkan nomor/kode order.

## 7.4 Pembayaran Pending/Gagal

1. Pembayaran menghasilkan status pending, gagal, dibatalkan, atau kedaluwarsa.
2. Halaman hasil mengambil status server, bukan mempercayai callback browser.
3. Pending menampilkan instruksi dan batas waktu.
4. Gagal/cancel menampilkan alasan aman serta opsi mencoba kembali.
5. Expired meminta transaksi baru bila order masih dapat dibayar.
6. Sistem mencegah order atau pengurangan stok ganda.

## 7.5 Perubahan Cabang dengan Keranjang Terisi

1. Pengguna memilih cabang baru.
2. Sistem memeriksa seluruh item pada cabang tujuan.
3. Sistem menampilkan item tersedia, berubah harga, atau tidak tersedia.
4. Pengguna mengonfirmasi penyesuaian.
5. Keranjang diperbarui hanya setelah persetujuan.

## 7.6 Pelacakan dan Ulasan

1. Pelanggan membuka riwayat/detail order.
2. Sistem menampilkan timeline status dan fulfillment.
3. Setelah status “Selesai”, CTA ulasan muncul pada item yang memenuhi syarat.
4. Pelanggan mengirim rating dan teks.
5. Laravel memvalidasi kepemilikan dan menyimpan ulasan untuk moderasi/publikasi.

# 8. Model Data (High-Level)

| **Entitas** | **Field Utama** | **Keterangan** |
| --- | --- | --- |
| **Customer** | id, name, email, phone, password_hash, email_verified_at, theme_preference, notification_preferences, is_active | Akun pelanggan. |
| **CustomerAddress** | id, customer_id, label, recipient_name, phone, address_line, province, city, district, subdistrict, postal_code, landmark, latitude, longitude, courier_note, is_primary | Alamat pengiriman. |
| **Branch** | id, name, slug, address, latitude, longitude, phone, whatsapp, opening_hours, service_radius, is_active | Cabang toko. |
| **BranchProduct** | id, branch_id, product_id, price_override, stock_available, max_order_quantity, is_available | Ketersediaan per cabang. |
| **Product** | id, category_id, name, slug, description, base_price, image_set, ingredients, allergens, is_active, seo_title, seo_description | Data katalog. |
| **ProductVariant** | id, product_id, name, sku, price_delta, is_active | Varian opsional. |
| **Cart** | id, customer_id, guest_token, branch_id, expires_at | Keranjang pengguna. |
| **CartItem** | id, cart_id, product_id, variant_id, quantity, price_snapshot | Item keranjang. |
| **WishlistItem** | id, customer_id, product_id, created_at | Wishlist. |
| **Promotion** | id, code, type, value, max_discount, min_spend, quota, starts_at, ends_at, rules, is_active | Promo/voucher. |
| **DeliveryQuote** | id, branch_id, address_snapshot, provider, service, distance, fee, eta, expires_at | Estimasi ongkir. |
| **Order** | id, order_number, customer_id, guest_contact, branch_id, fulfillment_type, status, payment_status, recipient_snapshot, address_snapshot, schedule_at, subtotal, discount, delivery_fee, service_fee, tax, grand_total, notes, idempotency_key, created_at | Pesanan dan snapshot final. |
| **OrderItem** | id, order_id, product_id, variant_id, product_snapshot, quantity, unit_price, subtotal | Snapshot produk saat order. |
| **Payment** | id, order_id, provider, provider_order_id, method, amount, status, token_reference, expires_at, paid_at, metadata | Pembayaran Midtrans tanpa menyimpan data kartu. |
| **PaymentEvent** | id, payment_id, provider_event_id, signature_valid, status, payload_reference, processed_at | Audit webhook idempotent. |
| **OrderStatusHistory** | id, order_id, from_status, to_status, actor_type, actor_id, note, created_at | Timeline pesanan. |
| **Review** | id, customer_id, order_item_id, product_id, rating, content, status, published_at | Ulasan pembelian terverifikasi. |
| **Notification** | id, customer_id, type, title, body, action_url, read_at, created_at | Notifikasi dalam aplikasi. |
| **DeliveryZone** | id, branch_id, geometry_or_rule, minimum_order, fee_rule, is_active | Zona layanan. |
| **LoyaltyAccount** | [id], [customer_id], [points_balance], [tier] | Fase lanjutan. |

**Catatan:** seluruh harga, alamat, produk, dan biaya pada Order harus berupa snapshot agar histori tidak berubah saat data master diperbarui. Field dalam kurung siku adalah fase lanjutan.

# 9. Kontrak API Tingkat Tinggi

- Base path: `/api/v1`.
- JSON konsisten: `data`, `meta`, `links`, dan `errors` sesuai jenis respons.
- Error field form memiliki kode stabil dan mapping per field.
- Pagination, filter, include, dan sort memiliki konvensi seragam.
- Endpoint mutasi checkout/order menerima idempotency key.
- Auth privat memakai cookie HttpOnly + CSRF bila arsitektur domain memungkinkan; token di localStorage tidak direkomendasikan.
- CORS dibatasi pada origin storefront resmi.
- Rate limit berbeda untuk auth, search, checkout, promo, dan payment status.
- Waktu menggunakan ISO 8601 dan timezone eksplisit.
- Nominal uang dikirim sebagai integer unit terkecil atau format yang disepakati, bukan floating point.
- API tidak mengirim Server Key, signature secret, password hash, atau payload sensitif.

Kelompok endpoint minimum:

| Kelompok | Contoh Cakupan |
| --- | --- |
| Public | config, navigation, pages, branches, categories, products, reviews, promos |
| Auth | register, login, logout, forgot/reset, verify, me |
| Customer | profile, addresses, wishlist, preferences, notifications |
| Cart | cart, items, merge, validation |
| Checkout | serviceability, slots, delivery quote, promo validation, preview |
| Orders | create, list, detail, cancel, reorder, guest lookup |
| Payments | create Midtrans transaction, status, retry; webhook hanya backend/provider |
| Reviews | eligible items, create, update, delete |

# 10. Kebutuhan Non-Fungsional

- **SEO:** halaman publik penting memiliki HTML prerender, metadata unik, structured data valid, sitemap, canonical, status HTTP benar, dan internal link crawlable.
- **Performa:** target p75 LCP ≤ 2,5 detik, INP ≤ 200 ms, CLS ≤ 0,1 pada perangkat dan jaringan target yang disepakati.
- **Bundle:** route-level code splitting; GSAP/ScrollTrigger hanya dimuat pada halaman yang memerlukan; modul checkout tidak masuk bundle landing jika tidak dibutuhkan.
- **Gambar:** responsive `srcset`, dimensi eksplisit, format modern, lazy-load di bawah fold, hero menggunakan prioritas tepat.
- **Keamanan:** HTTPS, CSP yang kompatibel Midtrans, validasi server, CSRF, sanitasi output, rate limiting, dan dependency review.
- **Privasi:** geolocation selalu meminta izin; hanya data yang diperlukan dikumpulkan; analytics dan marketing mengikuti persetujuan yang berlaku.
- **Aksesibilitas:** target WCAG 2.2 AA, keyboard lengkap, focus-visible, semantics, error yang terhubung ke field, target sentuh memadai, reduced motion.
- **Resiliensi:** retry hanya untuk request aman/idempotent, state pembayaran selalu direkonsiliasi ke server, error boundary per area penting.
- **Kompatibilitas:** browser modern yang disepakati; fitur baru menggunakan feature detection dan graceful degradation.
- **Observability:** error frontend, kegagalan API, funnel checkout, Web Vitals, dan payment reconciliation dapat dipantau tanpa merekam data sensitif.
- **Konsistensi tema:** Light, Dark, dan System memiliki token lengkap tanpa flash tema.

# 11. Analytics & Keberhasilan Produk

Event minimum tanpa data sensitif:

- `view_home`, `view_product_list`, `search_product`, `apply_filter`, `view_product`.
- `add_to_wishlist`, `add_to_cart`, `update_cart`, `begin_checkout`.
- `select_fulfillment`, `address_serviceability_checked`, `delivery_quote_received`.
- `apply_voucher`, `checkout_validation_failed`, `payment_started`, `payment_pending`, `purchase_confirmed`, `payment_failed`.
- `view_order`, `reorder`, `submit_review`, `contact_whatsapp`.

KPI awal yang perlu baseline/target:

- Conversion rate visitor → order terbayar.
- Product view → add-to-cart rate.
- Cart → checkout dan checkout → paid completion rate.
- Abandonment per tahap checkout.
- Persentase pembayaran gagal/pending/expired.
- Search zero-result rate.
- Repeat purchase rate.
- Persentase order pickup vs delivery.
- Core Web Vitals p75.
- Organic impressions dan indexed product pages.

# 12. Fitur Usulan / Fase Lanjutan

- **Loyalty & Membership.** Poin, tier, hadiah ulang tahun, dan reward.
- **Gift Card.** Pembelian dan penggunaan saldo hadiah.
- **Subscription.** Jadwal langganan roti mingguan/bulanan.
- **Live Courier Tracking.** Posisi kurir bila penyedia mendukung.
- **Web Push.** Notifikasi status dan promo setelah izin eksplisit.
- **Personalization.** Rekomendasi berdasarkan perilaku yang mematuhi privasi.
- **Multi-language.** Bahasa tambahan dan strategi hreflang.
- **PWA Offline.** Offline shell dan katalog cache dengan aturan kedaluwarsa yang aman.

# 13. Kriteria Penerimaan Rilis MVP

- Pengguna dapat menyelesaikan order pickup dan delivery dari katalog sampai konfirmasi.
- Harga, stok, promo, ongkir, cabang, dan slot divalidasi ulang di Laravel.
- Midtrans sandbox berhasil untuk success, pending, deny/fail, cancel, dan expire.
- Webhook terverifikasi, idempotent, dan menjadi sumber status pembayaran final.
- Refresh/return dari Midtrans tidak membuat order ganda.
- Route publik utama memiliki HTML prerender dan konten inti tanpa menunggu JavaScript.
- Product/Bakery/Breadcrumb structured data lulus validasi untuk data yang tersedia.
- Route privat dan checkout tidak terindeks.
- Theme Light/Dark/System bekerja tanpa flash dan memenuhi kontras.
- Alur checkout dapat digunakan keyboard serta error diumumkan dengan benar.
- Reduced motion menyederhanakan GSAP dan Framer Motion.
- Tidak ada konflik transform antara GSAP dan Framer Motion.
- Target Core Web Vitals diuji pada halaman home, katalog, produk, cart, dan checkout.
- Seluruh gambar dummy produk dan state telah tersedia tanpa placeholder.

# 14. Pertanyaan Terbuka / TBD

- Apa nama brand, domain, logo, dan detail legal toko?
- Apakah checkout tamu diizinkan?
- Metode Midtrans apa saja yang diaktifkan dan apakah memakai Snap atau Core API?
- Apakah bayar di toko/COD diizinkan, dan apa batasannya?
- Penyedia peta/geocoding dan logistik/ongkir apa yang digunakan?
- Apakah delivery dilakukan armada toko atau layanan pihak ketiga?
- Bagaimana zona layanan, minimum order, ongkir, biaya layanan, dan pajak dihitung?
- Bagaimana kebijakan reservasi stok selama pembayaran pending dan berapa lama?
- Kapan pelanggan boleh membatalkan, dan bagaimana refund diproses?
- Apakah harga/katalog berbeda per cabang?
- Apakah produk memiliki varian, add-on, tanggal tersedia, alergen, dan batas pesanan?
- Apakah pre-order dan same-day order tersedia?
- Bagaimana kapasitas slot pickup/delivery ditentukan?
- Kanal notifikasi MVP: email, WhatsApp, SMS, atau in-app?
- Apakah WhatsApp menggunakan deep link saja atau Business API?
- Tool prerender/SSG dan strategi rebuild saat katalog berubah belum dipilih.
- Hosting/CDN React dan domain API Laravel belum ditentukan.
- Apakah API dan storefront berada pada satu top-level domain agar cookie Sanctum mudah digunakan?
- Provider analytics, consent management, error monitoring, dan kebijakan retensi data belum dipilih.
- Apakah dark theme bakery disetujui atau hanya Light/System?
- Target browser dan perangkat minimum belum ditetapkan.

# 15. Glosarium

- **Prerender/SSG:** pembuatan HTML halaman publik sebelum request pengguna agar konten langsung tersedia bagi browser dan crawler.
- **Midtrans:** penyedia payment gateway untuk memproses metode pembayaran.
- **Webhook:** notifikasi server-ke-server dari Midtrans ke Laravel mengenai perubahan transaksi.
- **Idempotency Key:** kunci yang mencegah request berulang membuat order/transaksi ganda.
- **Fulfillment:** cara pesanan diterima pelanggan, yaitu pickup atau delivery.
- **Serviceability:** pemeriksaan apakah alamat dapat dilayani oleh cabang.
- **Structured Data:** data JSON-LD yang membantu mesin pencari memahami produk dan bisnis.
- **Core Web Vitals:** metrik pengalaman halaman seperti LCP, INP, dan CLS.
- **ScrollTrigger:** plugin GSAP untuk animasi yang dikendalikan posisi scroll.

---

*Dokumen ini merupakan draft sementara dan dapat berubah seiring pembahasan lebih lanjut dengan klien.*
