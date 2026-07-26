import { useState, useEffect, useCallback } from 'react'
import { useSearchParams } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import { Search, SlidersHorizontal, X } from 'lucide-react'
import { ProductCard } from '../components/sections/ProductCard'
import { Chip } from '../components/ui/Badge'
import { Button } from '../components/ui/Button'
import styles from './CatalogPage.module.css'
import { productsApi } from '../services/api'
import { debounce } from '../lib/utils'

const SORT_OPTIONS = [
  { value: 'terbaru',    label: 'Terbaru' },
  { value: 'harga-asc',  label: 'Harga: Terendah' },
  { value: 'harga-desc', label: 'Harga: Tertinggi' },
]

const PRICE_RANGES = [
  { label: 'Semua Harga', min: null, max: null },
  { label: 'Di bawah Rp 30.000', min: null, max: 30000 },
  { label: 'Rp 30.000 – 75.000', min: 30000, max: 75000 },
  { label: 'Di atas Rp 75.000', min: 75000, max: null },
]

const fadeUp = {
  hidden: { opacity: 0, y: 16 },
  show:   { opacity: 1, y: 0, transition: { duration: 0.4, ease: [0.4, 0, 0.2, 1] } },
}
const stagger = {
  hidden: { opacity: 0 },
  show:   { opacity: 1, transition: { staggerChildren: 0.07 } },
}

export default function CatalogPage() {
  const [searchParams, setSearchParams] = useSearchParams()
  const [filterOpen, setFilterOpen]       = useState(false)
  const [search, setSearch]               = useState(searchParams.get('q') ?? '')
  const [categorySlug, setCategorySlug]   = useState(searchParams.get('kategori') ?? '')
  const [sort, setSort]                   = useState(searchParams.get('sort') ?? 'terbaru')
  const [priceRange, setPriceRange]       = useState(0)
  const [inStockOnly, setInStockOnly]     = useState(false)

  // Data dari API
  const [categories, setCategories]   = useState([])
  const [products, setProducts]       = useState([])
  const [productsMeta, setProductsMeta] = useState(null)
  const [loadingCats, setLoadingCats] = useState(true)
  const [loadingProds, setLoadingProds] = useState(true)
  const [errorProds, setErrorProds]   = useState(null)

  // Load categories
  useEffect(() => {
    productsApi.categories()
      .then(res => {
        const data = res.data?.data ?? []
        setCategories([{ id: 0, name: 'Semua', slug: '' }, ...data])
      })
      .finally(() => setLoadingCats(false))
  }, [])

  // Load products
  const fetchProducts = useCallback(async (page = 1) => {
    setLoadingProds(true)
    setErrorProds(null)
    try {
      const range = PRICE_RANGES[priceRange]
      const params = {
        search:        search || undefined,
        category_slug: categorySlug || undefined,
        sort,
        in_stock:      inStockOnly ? 1 : undefined,
        min_price:     range.min ?? undefined,
        max_price:     range.max ?? undefined,
        per_page:      12,
        page,
      }
      const res  = await productsApi.list(params)
      const body = res.data?.data
      setProducts(body?.data ?? body ?? [])
      if (body?.current_page) {
        setProductsMeta({
          current_page: body.current_page,
          last_page:    body.last_page,
          total:        body.total,
          from:         body.from,
          to:           body.to,
        })
      }
    } catch (err) {
      setErrorProds('Gagal memuat produk. Coba lagi.')
    } finally {
      setLoadingProds(false)
    }
  }, [search, categorySlug, sort, priceRange, inStockOnly])

  useEffect(() => { fetchProducts(1) }, [fetchProducts])

  // Sync URL params
  useEffect(() => {
    const p = {}
    if (search) p.q = search
    if (categorySlug) p.kategori = categorySlug
    if (sort !== 'terbaru') p.sort = sort
    setSearchParams(p, { replace: true })
  }, [search, categorySlug, sort])

  const debouncedSearch = useCallback(debounce(v => setSearch(v), 400), [])

  const activeFiltersCount = [categorySlug, priceRange > 0, inStockOnly].filter(Boolean).length

  const clearFilters = () => {
    setCategorySlug('')
    setPriceRange(0)
    setInStockOnly(false)
    setSearch('')
  }

  return (
    <div className={styles.page}>
      <div className={styles.pageHeader}>
        <div className="container">
          <h1 className={styles.pageTitle}>Katalog Produk</h1>
          <p className={styles.pageSubtitle}>
            {loadingProds ? 'Memuat...' : `${productsMeta?.total ?? products.length} produk tersedia`}
          </p>
        </div>
      </div>

      <div className={`${styles.layout} container`}>
        {/* Sidebar Filter */}
        <aside className={styles.sidebar}>
          <FilterPanel
            categories={categories}
            category={categorySlug}
            setCategory={setCategorySlug}
            priceRange={priceRange}
            setPriceRange={setPriceRange}
            inStockOnly={inStockOnly}
            setInStockOnly={setInStockOnly}
            onClear={clearFilters}
            activeCount={activeFiltersCount}
          />
        </aside>

        <main className={styles.main}>
          {/* Toolbar */}
          <div className={styles.toolbar}>
            <div className={styles.searchBar}>
              <Search size={16} className={styles.searchIcon} />
              <input
                type="search"
                className={styles.searchInput}
                placeholder="Cari produk..."
                defaultValue={search}
                onChange={e => debouncedSearch(e.target.value)}
              />
              {search && (
                <button className={styles.searchClear} onClick={() => setSearch('')}>
                  <X size={14} />
                </button>
              )}
            </div>

            <div className={styles.categoryChips}>
              {categories.map(cat => (
                <Chip key={cat.id} active={categorySlug === cat.slug} onClick={() => setCategorySlug(cat.slug)}>
                  {cat.name}
                </Chip>
              ))}
            </div>

            <div className={styles.toolbarRight}>
              <select
                className={styles.sortSelect}
                value={sort}
                onChange={e => setSort(e.target.value)}
                aria-label="Urutkan"
              >
                {SORT_OPTIONS.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}
              </select>

              <Button variant="outline" size="sm" onClick={() => setFilterOpen(true)} className={styles.mobileFilterBtn}>
                <SlidersHorizontal size={16} />
                Filter
                {activeFiltersCount > 0 && <span className={styles.filterBadge}>{activeFiltersCount}</span>}
              </Button>
            </div>
          </div>

          {/* Active Filter Pills */}
          {activeFiltersCount > 0 && (
            <div className={styles.activeFilters}>
              {categorySlug && (
                <span className={styles.filterPill}>
                  {categories.find(c => c.slug === categorySlug)?.name}
                  <button onClick={() => setCategorySlug('')}><X size={12} /></button>
                </span>
              )}
              {priceRange > 0 && (
                <span className={styles.filterPill}>
                  {PRICE_RANGES[priceRange].label}
                  <button onClick={() => setPriceRange(0)}><X size={12} /></button>
                </span>
              )}
              {inStockOnly && (
                <span className={styles.filterPill}>
                  Stok Tersedia
                  <button onClick={() => setInStockOnly(false)}><X size={12} /></button>
                </span>
              )}
              <button className={styles.clearAll} onClick={clearFilters}>Hapus Semua</button>
            </div>
          )}

          {/* Error */}
          {errorProds && (
            <div style={{ padding: '2rem', textAlign: 'center', color: 'var(--color-danger)' }}>
              {errorProds}
              <Button variant="ghost" onClick={fetchProducts} style={{ marginTop: '1rem' }}>Coba Lagi</Button>
            </div>
          )}

          {/* Products */}
          {loadingProds ? (
            <div className={styles.productGrid}>
              {Array.from({ length: 8 }).map((_, i) => (
                <div key={i} className="skeleton" style={{ height: '280px', borderRadius: 'var(--rounded-md)' }} />
              ))}
            </div>
          ) : products.length === 0 ? (
            <EmptyState onClear={clearFilters} />
          ) : (
            <>
              <motion.div
                className={styles.productGrid}
                variants={stagger}
                initial="hidden"
                animate="show"
                key={`${search}-${categorySlug}-${sort}-${priceRange}`}
              >
                {products.map(product => (
                  <motion.div key={product.id} variants={fadeUp}>
                    <ProductCard product={product} />
                  </motion.div>
                ))}
              </motion.div>

              {productsMeta && productsMeta.last_page > 1 && (
                <div className={styles.pagination}>
                  <Button
                    variant="outline"
                    size="sm"
                    disabled={productsMeta.current_page <= 1}
                    onClick={() => fetchProducts(productsMeta.current_page - 1)}
                  >
                    ← Sebelumnya
                  </Button>
                  <span className={styles.pageInfo}>
                    Hal {productsMeta.current_page} dari {productsMeta.last_page} ({productsMeta.total} produk)
                  </span>
                  <Button
                    variant="outline"
                    size="sm"
                    disabled={productsMeta.current_page >= productsMeta.last_page}
                    onClick={() => fetchProducts(productsMeta.current_page + 1)}
                  >
                    Selanjutnya →
                  </Button>
                </div>
              )}
            </>
          )}
        </main>
      </div>

      {/* Mobile Filter Drawer */}
      <AnimatePresence>
        {filterOpen && (
          <motion.div
            className={styles.filterOverlay}
            initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
            onClick={e => e.target === e.currentTarget && setFilterOpen(false)}
          >
            <motion.div
              className={styles.filterDrawer}
              initial={{ x: '100%' }} animate={{ x: 0 }} exit={{ x: '100%' }}
              transition={{ duration: 0.35, ease: [0.4, 0, 0.2, 1] }}
            >
              <div className={styles.drawerHeader}>
                <h5>Filter Produk</h5>
                <button onClick={() => setFilterOpen(false)}><X size={20} /></button>
              </div>
              <div className={styles.drawerBody}>
                <FilterPanel
                  categories={categories}
                  category={categorySlug}
                  setCategory={v => { setCategorySlug(v); setFilterOpen(false) }}
                  priceRange={priceRange}
                  setPriceRange={setPriceRange}
                  inStockOnly={inStockOnly}
                  setInStockOnly={setInStockOnly}
                  onClear={clearFilters}
                  activeCount={activeFiltersCount}
                />
              </div>
              <div className={styles.drawerFooter}>
                <Button variant="primary" fullWidth onClick={() => setFilterOpen(false)}>
                  Tampilkan {products.length} Produk
                </Button>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  )
}

function FilterPanel({ categories, category, setCategory, priceRange, setPriceRange, inStockOnly, setInStockOnly, onClear, activeCount }) {
  return (
    <div className={styles.filterPanel}>
      <div className={styles.filterHeader}>
        <h5>Filter</h5>
        {activeCount > 0 && <button className={styles.clearAllBtn} onClick={onClear}>Hapus</button>}
      </div>

      <div className={styles.filterSection}>
        <p className={styles.filterLabel}>Kategori</p>
        {categories.map(cat => (
          <button
            key={cat.id}
            className={`${styles.filterOption} ${category === cat.slug ? styles.filterOptionActive : ''}`}
            onClick={() => setCategory(cat.slug)}
          >
            {cat.name}
            {category === cat.slug && <span className={styles.checkmark}>✓</span>}
          </button>
        ))}
      </div>

      <div className={styles.filterSection}>
        <p className={styles.filterLabel}>Rentang Harga</p>
        {PRICE_RANGES.map((r, i) => (
          <button
            key={i}
            className={`${styles.filterOption} ${priceRange === i ? styles.filterOptionActive : ''}`}
            onClick={() => setPriceRange(i)}
          >
            {r.label}
            {priceRange === i && <span className={styles.checkmark}>✓</span>}
          </button>
        ))}
      </div>

      <div className={styles.filterSection}>
        <label className={styles.checkboxOption}>
          <input type="checkbox" checked={inStockOnly} onChange={e => setInStockOnly(e.target.checked)} />
          <span>Hanya yang Tersedia</span>
        </label>
      </div>
    </div>
  )
}

function EmptyState({ onClear }) {
  return (
    <motion.div className={styles.emptyState} initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
      <span className={styles.emptyEmoji}>🔍</span>
      <h4>Produk Tidak Ditemukan</h4>
      <p>Coba ubah kata kunci atau filter pencarian.</p>
      <Button variant="outline" onClick={onClear}>Reset Filter</Button>
    </motion.div>
  )
}
