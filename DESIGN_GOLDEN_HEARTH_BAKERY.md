# 🎨 DESIGN SYSTEM & UI SPECIFICATIONS

## Golden Hearth — Artisan Bakery E-Commerce

**STATUS: DRAFT SEMENTARA**
**VERSI:** 1.0.0

---

## 0. Aturan Dasar Pengembangan (Core Development Rules)

- **Single Source of Truth:** Dokumen ini adalah acuan mutlak. Desain visual tidak boleh melenceng dari token yang ada di sini.
- **Dilarang Nilai Magic (No Magic Numbers):** Semua ukuran font, jarak (margin/padding), dan warna harus merujuk pada variabel/token.
- **Konsistensi State:** Hover, active, dan disabled state harus merujuk pada token spesifik atau opacity standar sistem, bukan menebak warna baru.
- **Dinamis & Responsif (Fluid By Default):** Komponen harus dapat beradaptasi pada segala ukuran layar menggunakan perhitungan proporsional (`clamp`, flexbox, CSS grid) bukan semata-mata bergantung pada breakpoint statis.
- **Animasi Wajib Mulus (Smooth Animation Priority):** Tidak boleh ada perubahan state mendadak (0s transition). Setiap elemen interaktif harus bernafas.
- **Dual Theme Mandatory:** Setiap komponen wajib mendukung Light Theme (*Morning Bakery*) dan Dark Theme (*After Hours Bakery*) melalui CSS custom properties yang di-switch berdasarkan `[data-theme]` atau `prefers-color-scheme`.
- **Foto Produk Adalah Raja:** Warna canvas dan surface dirancang agar foto croissant, sourdough, pastry, dan cake menjadi pusat perhatian. Jangan menambahkan elemen visual yang bersaing dengan fotografi.

---

## 1. Ringkasan Desain (Brand Overview & Essence)

Golden Hearth adalah sistem desain untuk toko roti artisan & e-commerce yang mengusung kehangatan bakery tradisional dengan kecanggihan e-commerce modern. Inspirasi visual diambil dari momen **pagi hari di bakery artisan** (Light Theme) dan **suasana espresso bar malam hari** (Dark Theme). Palet didominasi oleh warm cream, toasted caramel, deep cocoa, berry preserve, dan honey gold — menghasilkan kesan premium yang menggugah selera tanpa terasa berlebihan.

**Karakteristik Utama:**
- **Warm & Appetizing Canvas:** Latar belakang menggunakan warm cream (bukan putih murni) pada Light Theme dan espresso brown (bukan hitam murni) pada Dark Theme, menciptakan suasana yang hangat dan menyambut.
- **Caramel-Driven CTA Hierarchy:** Semua tombol pembelian (Tambah ke Keranjang, Checkout, Bayar) menggunakan warna caramel sebagai satu-satunya warna aksi utama — membangkitkan asosiasi freshly baked.
- **Berry sebagai Signature Accent:** Warna berry preserve digunakan secara eksklusif untuk promo musiman, wishlist, dan kampanye — memberikan identitas khas yang membedakan dari bakery cokelat-krem generik.
- **Fotografi Full-Prominence:** Seluruh surface dan canvas dirancang agar foto produk (roti, pastry, cake) tampil dominan secara natural tanpa perlu filter atau overlay berat.

---

## 2. Design Tokens (Warna & Tipografi)

### 2.1 Skema Warna — Light Theme (Morning Bakery)

| **Kategori** | **Token** | **Hex / Value** | **Fungsi / Penggunaan Utama** |
| --- | --- | --- | --- |
| **Surface** | `--color-canvas` | `#FFF9F0` | Latar belakang utama halaman (Warm Flour) |
| **Surface** | `--color-canvas-alt` | `#F8EEDC` | Section alternatif, hero area (Oat Milk) |
| **Surface** | `--color-surface` | `#FFFEFC` | Card, drawer, modal (Porcelain Cream) |
| **Surface** | `--color-surface-elevated` | `#FFF5E7` | Floating card, dropdown, tooltip (Vanilla Foam) |
| **Brand** | `--color-primary` | `#B95324` | CTA utama: Tambah ke Keranjang, Checkout, Bayar (Toasted Caramel) |
| **Brand** | `--color-primary-hover` | `#98401C` | Hover state CTA utama (Burnt Caramel) |
| **Brand** | `--color-primary-active` | `#7C3217` | Pressed/active state CTA utama (Deep Terracotta) |
| **Brand** | `--color-primary-soft` | `#F9E2D2` | Selected state, chip aktif, metode pembayaran terpilih (Caramel Cream) |
| **Brand** | `--color-secondary` | `#432A20` | Elemen brand kontras tinggi, footer, heading kuat (Cocoa Bean) |
| **Brand** | `--color-secondary-soft` | `#EDE0D7` | Area sekunder, background footer alt (Cocoa Milk) |
| **Accent** | `--color-accent` | `#D99424` | Rating bintang, highlight kecil, badge (Honey Gold) |
| **Accent** | `--color-accent-soft` | `#FFF0C7` | Background promo lembut, tooltip rating (Honey Glaze) |
| **Accent** | `--color-brand-accent` | `#9F3F54` | Promo musiman, wishlist aktif, kampanye (Berry Preserve) |
| **Accent** | `--color-brand-accent-soft` | `#F7E1E6` | Background promo, banner sale (Berry Mousse) |
| **Text** | `--color-text-main` | `#33221B` | Heading, body text, harga produk (Dark Cocoa) |
| **Text** | `--color-text-muted` | `#716057` | Metadata, deskripsi tambahan, tanggal (Roasted Taupe) |
| **Text** | `--color-text-subtle` | `#907D73` | Caption foto, placeholder input (Soft Mocha) |
| **Text** | `--color-text-inverse` | `#FFFCF7` | Teks di atas CTA gelap, tombol primary (Whipped Cream) |
| **Border** | `--color-border` | `#E8D9C9` | Border standar card, input, divider (Biscuit Edge) |
| **Border** | `--color-border-strong` | `#CBB5A1` | Input hover, divider kuat, tab border (Toast Crust) |

### 2.1b Skema Warna — Dark Theme (After Hours Bakery)

| **Kategori** | **Token** | **Hex / Value** | **Fungsi / Penggunaan Utama** |
| --- | --- | --- | --- |
| **Surface** | `--color-canvas` | `#181310` | Latar belakang utama (Espresso Night) |
| **Surface** | `--color-canvas-alt` | `#211A16` | Section alternatif (Roasted Cocoa) |
| **Surface** | `--color-surface` | `#2A211C` | Card dan panel (Dark Chocolate) |
| **Surface** | `--color-surface-elevated` | `#362A24` | Modal, dropdown, tooltip (Cocoa Foam) |
| **Brand** | `--color-primary` | `#E39A58` | CTA utama — lebih terang untuk kontras di dark bg (Golden Caramel) |
| **Brand** | `--color-primary-hover` | `#F0B173` | Hover state CTA (Light Caramel) |
| **Brand** | `--color-primary-active` | `#C97A39` | Pressed state CTA (Toasted Amber) |
| **Brand** | `--color-primary-soft` | `#4A2E20` | Selected state di dark mode (Caramel Shadow) |
| **Brand** | `--color-secondary` | `#F8EBDD` | Brand contrast di dark mode (Whipped Cream) |
| **Brand** | `--color-secondary-soft` | `#40342E` | Area sekunder (Cocoa Mist) |
| **Accent** | `--color-accent` | `#F0B94D` | Rating, highlight (Honey Glow) |
| **Accent** | `--color-accent-soft` | `#44351C` | Background promo lembut (Honey Shadow) |
| **Accent** | `--color-brand-accent` | `#E4869A` | Wishlist, promo musiman (Berry Jam) |
| **Accent** | `--color-brand-accent-soft` | `#47252E` | Background promo dark (Berry Chocolate) |
| **Text** | `--color-text-main` | `#F8EDE2` | Heading dan body (Warm Meringue) |
| **Text** | `--color-text-muted` | `#D0BEB1` | Metadata (Almond Milk) |
| **Text** | `--color-text-subtle` | `#AD998D` | Caption (Cocoa Dust) |
| **Text** | `--color-text-inverse` | `#26170F` | Teks di atas CTA terang (Espresso Ink) |
| **Border** | `--color-border` | `#493A32` | Border standar (Cocoa Border) |
| **Border** | `--color-border-strong` | `#685247` | Input hover/divider kuat (Toasted Border) |

### 2.1c Semantic Status Colors

Warna status terpisah dari warna branding agar pengguna tidak salah memahami CTA vs error vs stok.

**Light Theme:**

| **Status** | **Token Main** | **Hex Main** | **Token Soft** | **Hex Soft** | **Penggunaan** |
| --- | --- | --- | --- | --- | --- |
| Success | `--color-success` | `#347552` | `--color-success-soft` | `#E2F2E8` | Pembayaran berhasil, stok tersedia, step selesai |
| Warning | `--color-warning` | `#94620F` | `--color-warning-soft` | `#FFF0C9` | Stok rendah, pembayaran pending, info alergen |
| Danger | `--color-danger` | `#A33F3A` | `--color-danger-soft` | `#F9E2E0` | Error, stok habis, pembatalan, gagal bayar |
| Info | `--color-info` | `#3E7080` | `--color-info-soft` | `#E1F0F3` | Info pengiriman, notifikasi sistem netral |

**Dark Theme:**

| **Status** | **Token Main** | **Hex Main** | **Token Soft** | **Hex Soft** | **Penggunaan** |
| --- | --- | --- | --- | --- | --- |
| Success | `--color-success` | `#71C493` | `--color-success-soft` | `#183629` | Berhasil, tersedia, selesai |
| Warning | `--color-warning` | `#E7B85A` | `--color-warning-soft` | `#3C2D13` | Pending, stok rendah |
| Danger | `--color-danger` | `#E88983` | `--color-danger-soft` | `#422220` | Error dan pembatalan |
| Info | `--color-info` | `#83C0D2` | `--color-info-soft` | `#19333A` | Informasi netral |

> [!IMPORTANT]
> Setiap status wajib memiliki: (1) Ikon atau indikator bentuk, (2) Label teks, (3) Warna foreground dan background, (4) Pesan yang dapat dipahami tanpa melihat warnanya.

### 2.2 Tipografi (Typography)

**Font Family Utama:** `'Inter'`, fallback: `'system-ui', '-apple-system', 'Segoe UI', sans-serif`.
**Font Family Display:** `'Outfit'`, fallback: `'Inter', sans-serif`.

*(Gunakan nilai kalkulasi `clamp()` di Bab 6 untuk implementasi sesungguhnya agar dinamis)*

| **Token** | **Ukuran Default (Size)** | **Weight** | **Line Height** | **Letter Spacing** | **Penggunaan** |
| --- | --- | --- | --- | --- | --- |
| `--typo-display-lg` | 3.5rem (56px) | 700 (Bold) | 1.1 | -0.02em | Hero banner, judul halaman utama |
| `--typo-display-md` | 2.25rem (36px) | 700 (Bold) | 1.2 | -0.01em | Judul section utama (Produk Terlaris, dll) |
| `--typo-display-sm` | 1.75rem (28px) | 600 (SemiBold) | 1.25 | -0.005em | Sub-judul section |
| `--typo-title-lg` | 1.5rem (24px) | 600 (SemiBold) | 1.3 | 0 | Judul halaman (Keranjang, Checkout) |
| `--typo-title-md` | 1.25rem (20px) | 600 (SemiBold) | 1.35 | 0 | Judul card produk, nama komponen |
| `--typo-title-sm` | 1.125rem (18px) | 500 (Medium) | 1.4 | 0 | Judul sub-section, label form group |
| `--typo-body-lg` | 1.125rem (18px) | 400 (Regular) | 1.6 | 0 | Deskripsi produk detail |
| `--typo-body-md` | 1rem (16px) | 400 (Regular) | 1.6 | 0 | Paragraf teks standar |
| `--typo-body-sm` | 0.875rem (14px) | 400 (Regular) | 1.5 | 0 | Caption, metadata, info tambahan |
| `--typo-button` | 0.875rem (14px) | 600 (SemiBold) | 1.2 | 0.05em | Teks di dalam tombol (CTA) — UPPERCASE |
| `--typo-label` | 0.75rem (12px) | 500 (Medium) | 1.4 | 0.02em | Badge, tag, chip, label kecil |
| `--typo-price` | 1.5rem (24px) | 700 (Bold) | 1.2 | 0 | Harga produk utama |
| `--typo-price-sm` | 1rem (16px) | 600 (SemiBold) | 1.3 | 0 | Harga di card kecil |

> [!NOTE]
> Font `Outfit` digunakan untuk `display-lg` dan `display-md` (heading besar) agar terasa lebih premium dan khas. `Inter` digunakan untuk semua teks lainnya karena keterbacaan optimalnya di berbagai ukuran.

---

## 3. Tata Letak, Jarak, & Bentuk (Layout, Spacing, & Shapes)

### 3.1 Skala Jarak (Spacing Ladder)

- **Base Unit:** 4px
- `--spacing-2xs`: 0.25rem (4px) — gap micro, padding ikon kecil
- `--spacing-xs`: 0.5rem (8px) — gap antar chip, padding badge
- `--spacing-sm`: 0.75rem (12px) — padding tombol vertikal, gap list item
- `--spacing-md`: 1rem (16px) — padding card inner, gap form field
- `--spacing-lg`: 1.5rem (24px) — margin antar section kecil, padding card large
- `--spacing-xl`: 2rem (32px) — margin section
- `--spacing-2xl`: 3rem (48px) — jarak antar section besar
- `--spacing-3xl`: 4rem (64px) — jarak hero, padding section utama
- `--spacing-4xl`: 6rem (96px) — spacing section besar di desktop

### 3.2 Sudut / Kelengkungan (Border Radius)

| **Token** | **Nilai** | **Penggunaan Wajib** |
| --- | --- | --- |
| `--rounded-none` | 0px | — (tidak digunakan secara default) |
| `--rounded-xs` | 4px | Checkbox, badge kecil, tooltip arrow |
| `--rounded-sm` | 8px | Input form, chip, tag, small card |
| `--rounded-md` | 12px | Card produk, modal, dropdown, image container |
| `--rounded-lg` | 16px | Card besar, hero section inner, promo banner |
| `--rounded-xl` | 24px | Floating action container, large modal |
| `--rounded-full` | 9999px | Avatar, badge notifikasi, pill button, dot indicator |

> [!TIP]
> Desain Golden Hearth menggunakan sudut membulat lembut (soft rounded) untuk menciptakan kesan hangat dan approachable — sesuai karakter bakery artisan. Hindari sudut tajam (0px) pada elemen utama.

### 3.3 Kedalaman (Elevation & Depth)

- **Level 0 (Flat/Inline):** Menggunakan `--color-canvas`. Tanpa border dan shadow. Digunakan untuk elemen inline.
- **Level 1 (Card):** Background `--color-surface`, border `1px solid var(--color-border)`. Shadow: `0 1px 3px rgba(51, 34, 27, 0.06)` (Light) / tidak ada shadow (Dark — gunakan border saja).
- **Level 2 (Elevated):** Background `--color-surface-elevated`. Shadow: `0 4px 12px rgba(51, 34, 27, 0.08), 0 1px 3px rgba(51, 34, 27, 0.04)` (Light) / `0 4px 12px rgba(0, 0, 0, 0.3)` (Dark).
- **Level 3 (Overlay/Modal):** Background `--color-surface-elevated` + backdrop `rgba(51, 34, 27, 0.4)` (Light) / `rgba(0, 0, 0, 0.6)` (Dark). Shadow: `0 8px 32px rgba(51, 34, 27, 0.12)` (Light) / `0 8px 32px rgba(0, 0, 0, 0.5)` (Dark).

> [!IMPORTANT]
> **Dark Theme:** Shadow dikurangi drastis. Pemisahan antar panel lebih banyak menggunakan perbedaan `--color-surface` / `--color-surface-elevated` dan `--color-border`, bukan shadow.

---

## 4. Spesifikasi Komponen (Components Definition)

### 4.1 Buttons (Tombol)

- **`button-primary`**: Latar `--color-primary`, teks `--color-text-inverse`. Font `--typo-button` (UPPERCASE). Padding `--spacing-sm` (vert) `--spacing-lg` (horiz). Sudut `--rounded-sm`. Hover: `--color-primary-hover`. Active: `--color-primary-active`. Disabled: opacity `0.5`, cursor `not-allowed`.
- **`button-secondary`**: Latar `--color-secondary`, teks `--color-text-inverse`. Padding & font sama dengan primary. Hover: opacity `0.9`.
- **`button-outline`**: Transparan. Border `1.5px solid var(--color-border-strong)`. Teks `--color-text-main`. Hover: background `--color-primary-soft`, border `--color-primary`.
- **`button-ghost`**: Transparan tanpa border. Teks `--color-primary`. Hover: background `--color-primary-soft`. Digunakan untuk aksi minor (Hapus item, Lihat detail).
- **`button-ghost-danger`**: Transparan. Teks `--color-danger`. Hover: background `--color-danger-soft`. Khusus untuk aksi destruktif minor (Hapus dari keranjang) — **bukan** tombol merah besar.
- **`button-icon`**: Bulat/persegi kecil, padding `--spacing-xs`. Sudut `--rounded-full` atau `--rounded-sm`. Digunakan untuk tombol +/- kuantitas, close modal.

### 4.2 Cards & Surfaces (Kartu)

- **`card-product`**: Background `--color-surface`. Padding `--spacing-md`. Sudut `--rounded-md`. Border `1px solid var(--color-border)`. Gambar produk di atas (aspect-ratio `4/3` atau `1/1`), di bawahnya nama, harga, rating. Hover: shadow Level 2, `translateY(-2px)` dengan `ease-snappy`.
- **`card-promo`**: Background `--color-brand-accent-soft`. Border-left `3px solid var(--color-brand-accent)`. Padding `--spacing-lg`. Sudut `--rounded-md`.
- **`card-checkout-summary`**: Background `--color-surface-elevated`. Padding `--spacing-lg`. Sudut `--rounded-md`. Sticky di desktop.

### 4.3 Form Inputs (Input Form)

- **`text-input`**: Background `--color-canvas`. Border `1.5px solid var(--color-border)`. Teks `--typo-body-md`. Padding `--spacing-sm` `--spacing-md`. Sudut `--rounded-sm`. Focus: border `--color-primary`, box-shadow `0 0 0 3px var(--color-primary-soft)`. Error: border `--color-danger`, shadow `0 0 0 3px var(--color-danger-soft)`.
- **`select-input`**: Sama dengan `text-input` ditambah chevron icon di kanan.
- **`textarea`**: Sama dengan `text-input`, minimum height `120px`, resize vertical.

### 4.4 Badge & Chips

- **`badge-stock-available`**: Background `--color-success-soft`. Teks `--color-success`. Font `--typo-label`.
- **`badge-stock-low`**: Background `--color-warning-soft`. Teks `--color-warning`. Font `--typo-label`.
- **`badge-stock-out`**: Background `--color-danger-soft`. Teks `--color-danger`. Font `--typo-label`.
- **`badge-promo`**: Background `--color-brand-accent`. Teks `--color-text-inverse`. Font `--typo-label`. Sudut `--rounded-xs`.
- **`chip-filter`**: Background `--color-canvas`. Border `1px solid var(--color-border)`. Sudut `--rounded-full`. Aktif: background `--color-primary-soft`, border `--color-primary`, teks `--color-primary`.
- **`chip-allergen`**: Background `--color-warning-soft`. Teks `--color-warning`. Sudut `--rounded-full`. Font `--typo-label`.

### 4.5 Rating

- **`star-rating`**: Warna bintang terisi `--color-accent` (Honey Gold). Bintang kosong `--color-border`. Size `20px`. Wajib disertai angka rating (contoh: ★★★★☆ 4.2).

### 4.6 Wishlist Toggle

- **`wishlist-inactive`**: Outline heart icon, warna `--color-text-muted`. Hover: warna `--color-brand-accent`.
- **`wishlist-active`**: Filled heart icon, warna `--color-brand-accent`. Background di belakang: `--color-brand-accent-soft`. Transisi: `ease-bounce` 0.4s.

### 4.7 Navigation

- **`navbar`**: Background `--color-surface` (light) / `--color-surface` (dark). Border-bottom `1px solid var(--color-border)`. Height `64px`. Sticky top.
- **`nav-link`**: Teks `--color-text-main`. Hover: teks `--color-primary`. Active: teks `--color-primary`, border-bottom `2px solid var(--color-primary)`.
- **`cart-icon-badge`**: Background `--color-primary`. Teks `--color-text-inverse`. Sudut `--rounded-full`. Font `--typo-label`. Min-width `20px`.

### 4.8 Theme Switcher

- **Ikon:** Matahari (☀️) untuk Light, Bulan (🌙) untuk Dark, Perangkat (💻) untuk System.
- **Pilihan aktif:** Background `--color-primary-soft`, border `--color-primary`.
- **Label teks wajib tersedia** — jangan hanya mengandalkan ikon.

---

## 5. Responsivitas (Responsive Behavior)

| **Breakpoint** | **Kondisi** | **Perubahan Utama** |
| --- | --- | --- |
| **Mobile** | `< 640px` | Layout single column. Grid produk 1-2 kolom. Navbar menjadi hamburger menu. CTA Checkout sticky di bottom. Font display menggunakan nilai minimum `clamp()`. Card produk horizontal stack. |
| **Tablet** | `640 – 1024px` | Grid produk 2-3 kolom. Sidebar filter menjadi modal overlay / bottom sheet. Checkout form dan summary stack vertikal. Margin section menyesuaikan. |
| **Desktop** | `> 1024px` | Grid produk 3-4 kolom. Sidebar filter visible di kiri. Checkout: form kiri (62%) + summary kanan (38%) menggunakan golden ratio. Hero section full-width dengan overlay teks. Font display menggunakan nilai maksimum `clamp()`. |

---

## 6. Frontend Formulas (Dinamis & Smooth Animation)

### 6.1 Fluid Typography & Spacing (Rumus Layout Dinamis)

Gunakan fungsi `clamp()` agar ukuran membesar secara proporsional dengan layar, tanpa melompat-lompat antar breakpoint.

- **Rumus Dasar CSS Clamp:** `clamp([ukuran-minimum], [ukuran-ideal-berbasis-vw], [ukuran-maksimum])`

| **Token** | **Implementasi Fluid** |
| --- | --- |
| `--typo-display-lg` | `font-size: clamp(2rem, 5vw + 1rem, 3.5rem);` |
| `--typo-display-md` | `font-size: clamp(1.5rem, 3vw + 0.75rem, 2.25rem);` |
| `--typo-display-sm` | `font-size: clamp(1.25rem, 2.5vw + 0.5rem, 1.75rem);` |
| `--typo-title-lg` | `font-size: clamp(1.25rem, 1.5vw + 0.5rem, 1.5rem);` |
| `--typo-title-md` | `font-size: clamp(1.125rem, 1.2vw + 0.5rem, 1.25rem);` |
| `--typo-body-lg` | `font-size: clamp(1rem, 0.5vw + 0.875rem, 1.125rem);` |
| `--typo-price` | `font-size: clamp(1.25rem, 1.5vw + 0.5rem, 1.5rem);` |
| Section Padding | `padding: clamp(2rem, 6vw, 6rem) 0;` |
| Container Max | `max-width: min(1200px, 90vw);` |

### 6.2 Sistem Animasi & Transisi (Smooth Easing Curves)

DILARANG menggunakan `linear` atau `ease-in-out` bawaan yang terlihat kaku. Selalu gunakan kurva cubic-bezier khusus.

- **`ease-snappy` (Cepat, Menggigit, Presisi):** `cubic-bezier(0.2, 0.8, 0.2, 1)`
  - *Penggunaan:* Hover tombol, checkbox, dropdown open, tooltip appear, card hover lift.
  - *Durasi Ideal:* `0.2s` – `0.3s`

- **`ease-smooth` (Lembut, Sinematik, Mengalir):** `cubic-bezier(0.4, 0, 0.2, 1)`
  - *Penggunaan:* Modal open/close, sidebar slide, page transition, image fade-in, accordion expand.
  - *Durasi Ideal:* `0.4s` – `0.6s`

- **`ease-bounce` (Sedikit Memantul, Organik):** `cubic-bezier(0.34, 1.56, 0.64, 1)`
  - *Penggunaan:* Wishlist heart pop, add-to-cart animation, notification pop-up, badge count update.
  - *Durasi Ideal:* `0.4s` – `0.5s`

**Variabel CSS:**
```css
:root {
  --ease-snappy: cubic-bezier(0.2, 0.8, 0.2, 1);
  --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
  --ease-bounce: cubic-bezier(0.34, 1.56, 0.64, 1);
  --duration-fast: 0.2s;
  --duration-normal: 0.3s;
  --duration-slow: 0.5s;
}
```

### 6.3 Golden Ratio Layout (Proporsi Emas)

Untuk layout asimetris (teks + gambar, form + summary), gunakan rasio mendekati 1 : 1.618.

- **Checkout layout:** `grid-template-columns: 5fr 8fr;` pada summary kanan, atau `8fr 5fr` pada form kiri + summary kanan.
- **Detail produk:** `grid-template-columns: 1fr 1.618fr;` (galeri kiri, info kanan).
- **Hero section:** Teks menempati ~38% lebar, gambar menempati ~62%.

---

## 7. Penerapan Warna Berdasarkan Halaman

### 7.1 Landing Page
- Canvas: `--color-canvas` (Warm Flour).
- Hero: kombinasi `--color-canvas-alt`, fotografi roti, dan teks `--color-text-main`.
- CTA utama: `--color-primary` (Toasted Caramel).
- CTA sekunder: transparent + border `--color-secondary`.
- Promo seasonal: `--color-brand-accent` (Berry Preserve).
- Rating: `--color-accent` (Honey Gold).

### 7.2 Katalog
- Background netral agar foto produk dominan.
- Filter aktif memakai `--color-primary-soft`.
- Harga: `--color-text-main`.
- Diskon: `--color-brand-accent` — **bukan** danger.
- Stok tersedia → `--color-success` / `--color-success-soft`.
- Stok rendah → `--color-warning` / `--color-warning-soft`.
- Stok habis → `--color-danger` / `--color-danger-soft`.

### 7.3 Detail Produk
- Galeri di atas `--color-surface-elevated`.
- CTA "Tambah ke Keranjang": `--color-primary`.
- Wishlist: outline berry → aktif: berry soft.
- Info alergen: warning atau info sesuai tingkat kepentingan.

### 7.4 Keranjang
- Primary **hanya** untuk "Lanjut ke Checkout".
- Tombol +/−: `--color-secondary` atau `button-icon`.
- Hapus item: `button-ghost-danger` — **bukan** tombol merah besar.
- Voucher berhasil: `--color-success-soft`.
- Perubahan harga: `--color-warning-soft`.

### 7.5 Checkout
- Background dominan cream/surface — lebih tenang dari landing page.
- Primary **hanya** untuk melanjutkan tahap dan membayar.
- Step aktif: `--color-primary`.
- Step selesai: `--color-success`.
- Field error: `--color-danger` + `--color-danger-soft` ring.
- Ringkasan order: `--color-surface-elevated`.
- Metode pembayaran terpilih: `--color-primary-soft` + border `--color-primary`.
- **DILARANG** animasi scroll dramatis selama checkout.

### 7.6 Halaman Pembayaran
- Menunggu Pembayaran → `--color-warning`.
- Pembayaran Diproses → `--color-info`.
- Pembayaran Berhasil → `--color-success`.
- Pembayaran Gagal → `--color-danger`.

> [!CAUTION]
> Jangan menampilkan status success hanya berdasarkan callback browser; tetap menunggu konfirmasi backend (Laravel).

### 7.7 Theme Switcher
- Ikon: ☀️ Light, 🌙 Dark, 💻 System.
- Pilihan aktif: `--color-primary-soft` + border `--color-primary`.
- Label teks wajib tersedia.

---

## 8. Distribusi Warna (Color Distribution)

| **Proporsi** | **Kelompok** | **Penggunaan** |
| ---: | --- | --- |
| 60% | Canvas & surface | Cream/light atau espresso/dark — area kosong dan breathing room |
| 25% | Cocoa & neutral surfaces | Struktur, heading, footer, panel, navigasi |
| 10% | Primary caramel | CTA, active state, link hover, focus ring |
| 5% | Honey, berry, & status | Promo, rating, feedback, badge |

**Hierarki warna aksen — TIDAK BOLEH saling bersaing:**

| **Warna** | **Peran Eksklusif** | **Contoh Penggunaan** |
| --- | --- | --- |
| Caramel (Primary) | Tindakan pembelian | Tambah ke Keranjang, Checkout, Bayar |
| Berry (Brand Accent) | Wishlist & kampanye/promo | Sale banner, wishlist heart, diskon |
| Honey (Accent) | Rating & highlight minor | Bintang rating, "Bestseller" badge |
| Success/Warning/Danger | Status sistem | Stok, pembayaran, validasi form |

---

## 9. Do's and Don'ts (Konkret & Tidak Abstrak)

### ✅ Lakukan (DO)

1. **Gunakan Token & Rumus:** Pastikan semua className/style merujuk ke token yang ada. Gunakan `clamp()` untuk font dan jarak besar agar antarmuka responsif tanpa kaku.
2. **Uppercase CTA:** Teks tombol `--typo-button` WAJIB UPPERCASE karena letter-spacing diatur `0.05em`.
3. **Transisi di Setiap Hover:** Setiap elemen yang bisa di-klik HARUS memiliki transisi visual: `transition: all 0.3s var(--ease-snappy);`
4. **Foto Produk Natural:** Biarkan foto produk tampil di atas surface yang hangat dan netral. Jangan overlay warna kuat di atas foto.
5. **Verifikasi Kontras WCAG 2.2 AA:** Minimum 4.5:1 untuk teks normal, 3:1 untuk teks besar dan komponen UI.
6. **Dark Theme = Surface + Border:** Pada dark mode, pisahkan elemen menggunakan surface dan border, bukan shadow.
7. **Status Selalu Accessible:** Setiap status wajib punya ikon + label teks + warna. Tidak boleh hanya mengandalkan warna.

### ❌ DILARANG (DON'T)

1. **Dilarang Ngarang Warna:** Jangan menggunakan `#EEEEEE`, `#000000`, atau putih murni `#FFFFFF` sebagai canvas/surface. Gunakan warm cream (`#FFF9F0`) dan espresso (`#181310`).
2. **Dilarang Animasi Patah:** Jangan membuat transisi 0 detik atau linear. Selalu gunakan easing curves dari Bab 6.2.
3. **Dilarang Font Size Statis di Layar Lebar:** Jangan hardcode `font-size: 56px`. Gunakan fluid clamp dari Bab 6.1.
4. **Dilarang Berry Bersaing dengan Caramel:** Berry hanya untuk wishlist dan promo. CTA pembelian selalu caramel.
5. **Dilarang Tombol Merah Besar untuk Hapus:** Aksi hapus item menggunakan `button-ghost-danger` — teks kecil, bukan tombol prominent.
6. **Dilarang Success hanya dari Warna:** Status harus punya ikon + label — banyak pengguna buta warna.
7. **Dilarang Animasi Dramatis di Checkout:** Checkout harus tenang dan fokus. Tidak ada parallax, scroll animation, atau auto-play video.

---

## 10. WCAG Compliance Checklist

Sebelum palet dinyatakan final, verifikasi semua pasangan berikut terhadap **WCAG 2.2 AA**:

| **Elemen** | **Foreground** | **Background** | **Target Rasio** | **Status** |
| --- | --- | --- | --- | --- |
| Body text (Light) | `#33221B` | `#FFF9F0` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Body text (Dark) | `#F8EDE2` | `#181310` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Muted text (Light) | `#716057` | `#FFF9F0` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Muted text (Dark) | `#D0BEB1` | `#181310` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Button primary (Light) | `#FFFCF7` | `#B95324` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Button primary (Dark) | `#26170F` | `#E39A58` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Subtle text (Light) | `#907D73` | `#FFF9F0` | ≥ 3:1 (large text) | 🔲 Perlu diverifikasi |
| Subtle text (Dark) | `#AD998D` | `#181310` | ≥ 3:1 (large text) | 🔲 Perlu diverifikasi |
| Success badge (Light) | `#347552` | `#E2F2E8` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Danger badge (Light) | `#A33F3A` | `#F9E2E0` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Warning badge (Light) | `#94620F` | `#FFF0C9` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |
| Berry promo (Light) | `#9F3F54` | `#F7E1E6` | ≥ 4.5:1 | 🔲 Perlu diverifikasi |

> [!WARNING]
> Gunakan [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/) atau [Color Safe](https://colorsafe.co/) untuk memverifikasi setiap pasangan di atas sebelum status dokumen diubah menjadi FINAL.
