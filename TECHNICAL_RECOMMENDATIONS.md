# 📋 REKOMENDASI TEKNIS - R BREAD'S PROJECT

**Prepared for:** R Bread's Development Team  
**Date:** 25 Juli 2026  
**Status:** Technical Recommendations

---

## 🎯 RINGKASAN IMPLEMENTASI YANG SUDAH SELESAI

✅ **Color Palette Unification**
- Backend dan Frontend sekarang menggunakan unified color system
- Primary color aligned: `#B95324` (R Bread's Caramel)
- Accent color aligned: `#D99424` (Honey Gold)
- Semantic status colors unified across platforms

✅ **Theme Switcher Implementation**
- Component: `ThemeSwitcher.jsx` dan `ThemeSwitcherZustand.jsx`
- Hook: `useTheme.js` (standalone)
- Store: `themeStore.js` (Zustand integration - updated)
- Smooth transitions dengan CSS classes
- Support Light/Dark/System preferences

✅ **Brand Name Update**
- Logo di Navbar: "Golden Hearth" → "R Bread's"
- Color tokens updated dengan naming `--rb-*`
- Theme storage key: `rb-theme-storage`

---

## 🚀 REKOMENDASI IMPLEMENTASI SELANJUTNYA

### 1️⃣ **PRERENDER/SSG untuk SEO (Priority: HIGH)**

#### **Problem Statement:**
- React + Vite adalah SPA (Single Page Application)
- Search engine crawlers mendapat HTML kosong di initial load
- SEO performance buruk untuk halaman katalog/produk
- PRD menyebutkan: "halaman publik wajib diprerender"

#### **✅ REKOMENDASI TERBAIK: `vite-plugin-ssr` (Vike)**

**Alasan Dipilih:**
1. **Vite-native** - Built specifically untuk Vite ecosystem
2. **File-based routing** - Mirip Next.js, developer-friendly
3. **Selective SSG/SSR** - Bisa pilih halaman mana yang di-render
4. **React Server Components ready** - Future-proof
5. **Production-proven** - Digunakan oleh banyak production apps

**Implementation Plan:**

```bash
# Install dependencies
cd /home/rizz/Documents/bakerrr/golden-hearth-storefront
npm install vike vite-plugin-ssr
```

**File Structure yang Dibutuhkan:**
```
golden-hearth-storefront/
├── pages/
│   ├── index/
│   │   ├── +Page.jsx          # HomePage
│   │   └── +onBeforeRender.js # SSG data fetching
│   ├── katalog/
│   │   ├── +Page.jsx          # CatalogPage
│   │   └── +onBeforeRender.js # Products data
│   ├── produk/
│   │   └── @slug/
│   │       ├── +Page.jsx      # ProductDetailPage
│   │       └── +onBeforeRender.js # Product detail
│   └── _default/
│       ├── +Head.jsx          # Meta tags
│       └── +Layout.jsx        # Root layout
└── renderer/
    ├── +onRenderHtml.jsx      # Server-side render
    └── +onRenderClient.jsx    # Client hydration
```

**Benefit:**
- ✅ SEO-friendly HTML untuk Google/Bing
- ✅ Faster First Contentful Paint (FCP)
- ✅ Structured data (JSON-LD) di-render langsung
- ✅ Social media preview (OG tags) work properly

**Alternative Options (jika Vike terlalu kompleks):**

**Option B: `vite-plugin-ssg` (Simple Static Site Generation)**
- Lebih sederhana, less feature-rich
- Good for pure static sites
- `npm install vite-plugin-ssg`

**Option C: `prerender-spa-plugin`**
- Puppeteer-based prerendering
- Generate static HTML post-build
- Tidak perlu rewrite routing logic

**⚠️ CATATAN PENTING:**
- Halaman privat (checkout, akun) tetap **TIDAK di-prerender**
- Add `<meta name="robots" content="noindex">` di halaman privat
- API calls di SSG harus ke production API atau mock data

---

### 2️⃣ **API AUTHENTICATION dengan Laravel Sanctum (Priority: HIGH)**

#### **Problem Statement:**
- PRD menyebutkan: "Sanctum atau mekanisme aman setara; keputusan final TBD"
- Frontend perlu authenticate user untuk wishlist, cart sync, checkout, orders
- Backend API belum verified untuk Sanctum readiness

#### **✅ REKOMENDASI: Laravel Sanctum SPA Authentication**

**Why Sanctum for SPA:**
1. **Built for SPAs** - Official Laravel solution
2. **CSRF Protection** - Secure by default
3. **HttpOnly Cookies** - Token tidak di localStorage (safer)
4. **Simple Setup** - Native Laravel integration

**Implementation Checklist:**

**Backend (Laravel):**
```bash
# 1. Verify Sanctum installed
cd /home/rizz/Documents/bakerrr/bakery-system
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# 2. Check config/sanctum.php
# Pastikan 'stateful' domains include frontend domain
# Example: ['localhost:5173', 'localhost:3000', 'rbread.com']

# 3. Check config/cors.php
# 'paths' => ['api/*', 'sanctum/csrf-cookie'],
# 'allowed_origins' => ['http://localhost:5173'],
# 'supports_credentials' => true,

# 4. Check app/Http/Kernel.php
# Pastikan \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class ada

# 5. Create API routes di routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $req) => $req->user());
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'add']);
    // ... etc
});
```

**Frontend (React):**
```javascript
// services/api.js
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000',
  withCredentials: true, // PENTING: Include cookies
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
});

// CSRF token setup
export async function initCsrf() {
  await api.get('/sanctum/csrf-cookie');
}

// Login flow
export async function login(email, password) {
  await initCsrf(); // Get CSRF token first
  const response = await api.post('/login', { email, password });
  return response.data;
}

// Authenticated requests
export async function getCart() {
  const response = await api.get('/api/v1/cart');
  return response.data;
}
```

**⚠️ DOMAIN CONSIDERATION:**
- **Option A (Same Domain):** API dan Frontend di subdomain sama
  - API: `api.rbread.com`
  - Frontend: `rbread.com`
  - ✅ Sanctum cookies work seamlessly
  
- **Option B (Different Domain):** API dan Frontend terpisah
  - API: `api.rbread.com`
  - Frontend: `app.rbread.com`
  - ⚠️ Perlu CORS config lebih kompleks
  - ⚠️ Cookies must be SameSite=None; Secure

**Recommendation:** Deploy di same top-level domain untuk simplicity.

---

### 3️⃣ **MULTI-BRANCH ARCHITECTURE (Priority: MEDIUM-HIGH)**

#### **Problem Statement:**
- Backend: "MVP menangani satu toko sampai kebutuhan multi-cabang dikonfirmasi"
- Frontend: Multi-cabang sudah masuk MVP (branch selection, serviceability)
- **Gap:** Database schema backend belum support multi-branch

#### **✅ REKOMENDASI: Phased Multi-Branch Implementation**

**Phase 1: Database Schema Update (Immediate)**

```sql
-- Migration: add_branch_support.php

-- 1. Create branches table
CREATE TABLE branches (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    address TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    phone VARCHAR(20),
    whatsapp VARCHAR(20),
    opening_hours JSON,
    service_radius INT, -- in meters
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- 2. Add branch_id to products table
ALTER TABLE products ADD COLUMN branch_id BIGINT UNSIGNED NULL;
ALTER TABLE products ADD CONSTRAINT fk_products_branch 
    FOREIGN KEY (branch_id) REFERENCES branches(id);

-- 3. Create branch_product_stock pivot (RECOMMENDED)
CREATE TABLE branch_product_stock (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    branch_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    stock_available INT DEFAULT 0,
    low_stock_threshold INT DEFAULT 5,
    price_override DECIMAL(10, 2) NULL, -- Optional: harga bisa beda per cabang
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_branch_product (branch_id, product_id),
    FOREIGN KEY (branch_id) REFERENCES branches(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- 4. Update stock_movements table
ALTER TABLE stock_movements ADD COLUMN branch_id BIGINT UNSIGNED NULL;
ALTER TABLE stock_movements ADD CONSTRAINT fk_stock_movements_branch 
    FOREIGN KEY (branch_id) REFERENCES branches(id);

-- 5. Add branch_id to employees
ALTER TABLE employee_profiles ADD COLUMN branch_id BIGINT UNSIGNED NULL;
ALTER TABLE employee_profiles ADD CONSTRAINT fk_employees_branch 
    FOREIGN KEY (branch_id) REFERENCES branches(id);
```

**Phase 2: Backend Logic Update**

```php
// app/Models/Branch.php
class Branch extends Model {
    protected $fillable = [
        'name', 'slug', 'address', 'latitude', 'longitude',
        'phone', 'whatsapp', 'opening_hours', 'service_radius', 'is_active'
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'branch_product_stock')
                    ->withPivot('stock_available', 'price_override', 'is_available');
    }

    public function employees() {
        return $this->hasMany(EmployeeProfile::class);
    }
}

// app/Models/Product.php - Update relationships
public function branches() {
    return $this->belongsToMany(Branch::class, 'branch_product_stock')
                ->withPivot('stock_available', 'price_override', 'is_available');
}

public function getStockForBranch($branchId) {
    $pivot = $this->branches()->where('branch_id', $branchId)->first()?->pivot;
    return $pivot?->stock_available ?? 0;
}
```

**Phase 3: API Endpoints**

```php
// routes/api.php
Route::get('/branches', [BranchController::class, 'index']);
Route::get('/branches/{slug}', [BranchController::class, 'show']);
Route::get('/branches/{slug}/products', [BranchController::class, 'products']);
Route::post('/branches/serviceability', [BranchController::class, 'checkServiceability']);
```

**Phase 4: Seeder Update**

```php
// database/seeders/BranchSeeder.php
Branch::create([
    'name' => 'R Bread\'s Pusat',
    'slug' => 'pusat',
    'address' => 'Jl. Roti No. 123, Jakarta',
    'latitude' => -6.200000,
    'longitude' => 106.816666,
    'phone' => '021-12345678',
    'whatsapp' => '628123456789',
    'opening_hours' => [
        'monday' => ['open' => '07:00', 'close' => '21:00'],
        'tuesday' => ['open' => '07:00', 'close' => '21:00'],
        // ... etc
    ],
    'service_radius' => 5000, // 5km
    'is_active' => true,
]);

// Seed branch_product_stock
$branch = Branch::first();
$products = Product::all();
foreach ($products as $product) {
    $branch->products()->attach($product->id, [
        'stock_available' => rand(10, 50),
        'low_stock_threshold' => 5,
        'is_available' => true,
    ]);
}
```

**⚠️ MIGRATION STRATEGY:**

**Option A: Fresh Migration (Recommended untuk development)**
```bash
php artisan migrate:fresh --seed
```

**Option B: Incremental Migration (Production-safe)**
```bash
# 1. Backup database first
php artisan db:backup

# 2. Run new migrations
php artisan migrate

# 3. Seed default branch
php artisan db:seed --class=BranchSeeder

# 4. Migrate existing data
php artisan migrate:existing-products-to-branch
```

**Data Migration Script:**
```php
// app/Console/Commands/MigrateProductsToBranch.php
public function handle() {
    $defaultBranch = Branch::first();
    $products = Product::all();
    
    foreach ($products as $product) {
        DB::table('branch_product_stock')->insert([
            'branch_id' => $defaultBranch->id,
            'product_id' => $product->id,
            'stock_available' => $product->current_stock ?? 0,
            'low_stock_threshold' => $product->low_stock_threshold ?? 5,
            'is_available' => $product->is_active,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    $this->info('Products migrated to branch system successfully!');
}
```

---

## 📝 SUMMARY & NEXT STEPS

### ✅ **Completed Today:**
1. Color palette unification (Backend + Frontend)
2. Theme switcher implementation (Light/Dark/System)
3. Brand name update to "R Bread's"

### 🎯 **Immediate Action Items (Priority Order):**

| Priority | Task | Estimated Time | Blocker? |
|----------|------|----------------|----------|
| **P0** | Verify Laravel Sanctum setup & test auth flow | 2-4 hours | ⚠️ YES - Frontend perlu auth |
| **P1** | Implement multi-branch database schema | 3-5 hours | ⚠️ YES - Frontend expect branches |
| **P1** | Create Branch API endpoints | 2-3 hours | ⚠️ YES - Frontend perlu data |
| **P2** | Implement Vike/vite-plugin-ssr untuk SEO | 6-8 hours | NO - Bisa parallel |
| **P3** | Update all docs with "R Bread's" branding | 1-2 hours | NO - Non-blocking |
| **P4** | WCAG contrast verification | 1 hour | NO - QA task |

### 🚨 **Critical Path:**
```
Sanctum Auth → Multi-Branch DB → Branch API → Frontend Integration
```

---

## 🔧 QUICK START COMMANDS

```bash
# Backend - Sanctum verification
cd /home/rizz/Documents/bakerrr/bakery-system
php artisan route:list | grep sanctum
php artisan config:cache

# Backend - Multi-branch migration
php artisan make:migration add_multi_branch_support
php artisan make:model Branch
php artisan make:controller Api/BranchController
php artisan migrate
php artisan db:seed --class=BranchSeeder

# Frontend - Install SSG
cd /home/rizz/Documents/bakerrr/golden-hearth-storefront
npm install vike
# OR simpler option:
npm install vite-plugin-ssg

# Frontend - Test theme switcher
npm run dev
# Navigate to http://localhost:5173 and test theme switcher
```

---

**Prepared by:** AI Development Assistant  
**For:** R Bread's Development Team  
**Next Review:** After Sanctum & Multi-branch implementation
