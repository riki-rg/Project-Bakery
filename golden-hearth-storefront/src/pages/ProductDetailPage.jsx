import { useState, useEffect } from 'react'
import { Link, useParams, useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import { ShoppingCart, Heart, Minus, Plus, Share2, ChevronRight } from 'lucide-react'
import { Button } from '../components/ui/Button'
import { Badge } from '../components/ui/Badge'
import { StarRating } from '../components/ui/StarRating'
import styles from './ProductDetailPage.module.css'
import { useCartStore } from '../store/cartStore'
import { useWishlistStore } from '../store/wishlistStore'
import { ProductCard } from '../components/sections/ProductCard'
import { productsApi } from '../services/api'
import { formatPrice, getImageUrl } from '../lib/utils'
import toast from 'react-hot-toast'

function getStockStatus(stock, threshold) {
  if (stock === 0) return { variant: 'stock-out',       label: 'Stok Habis' }
  if (stock <= threshold) return { variant: 'stock-low', label: `Sisa ${stock}` }
  return { variant: 'stock-available', label: 'Tersedia' }
}

export default function ProductDetailPage() {
  const { slug } = useParams()
  const [product, setProduct]     = useState(null)
  const [related, setRelated]     = useState([])
  const [loading, setLoading]     = useState(true)
  const [error, setError]         = useState(null)
  const [qty, setQty]             = useState(1)
  const [activeImg, setActiveImg] = useState(0)
  const [activeTab, setActiveTab] = useState('deskripsi')

  const { addItem, items }           = useCartStore()
  const { toggle, isWishlisted }     = useWishlistStore()
  const navigate                     = useNavigate()

  // Fetch product by slug
  useEffect(() => {
    setLoading(true)
    setError(null)
    setQty(1)
    setActiveImg(0)

    productsApi.detail(slug)
      .then(res => {
        const data = res.data?.data
        setProduct(data)
        // Fetch related (same category)
        if (data?.category?.id) {
          productsApi.list({ category_id: data.category.id, per_page: 4 })
            .then(r => {
              const list = r.data?.data?.data ?? r.data?.data ?? []
              setRelated(list.filter(p => p.id !== data.id).slice(0, 4))
            })
            .catch(() => {})
        }
      })
      .catch(err => {
        if (err.response?.status === 404) setError('404')
        else setError('Gagal memuat produk.')
      })
      .finally(() => setLoading(false))
  }, [slug])

  if (loading) return <LoadingSkeleton />
  if (error === '404') return <NotFound />
  if (error) return (
    <div style={{ padding: '4rem', textAlign: 'center', color: 'var(--color-danger)' }}>
      {error}
      <Button variant="ghost" onClick={() => navigate(-1)} style={{ display: 'block', margin: '1rem auto' }}>
        Kembali
      </Button>
    </div>
  )
  if (!product) return null

  const inCart     = items.some(i => i.productId === product.id)
  const wishlisted = isWishlisted(product.id)
  const outOfStock = product.current_stock === 0
  const stockStatus = getStockStatus(product.current_stock, product.low_stock_threshold ?? 5)
  const images      = product.images ?? [product.image_path]

  const handleAddToCart = () => {
    if (outOfStock) return
    addItem(product, qty)
    toast.success(`${product.name} ditambahkan ke keranjang!`)
  }

  return (
    <div className={styles.page}>
      <div className="container">
        {/* Breadcrumb */}
        <nav className={styles.breadcrumb} aria-label="Breadcrumb">
          <Link to="/">Beranda</Link>
          <ChevronRight size={14} />
          <Link to="/katalog">Katalog</Link>
          <ChevronRight size={14} />
          <Link to={`/katalog?kategori=${product.category?.slug}`}>{product.category?.name}</Link>
          <ChevronRight size={14} />
          <span aria-current="page">{product.name}</span>
        </nav>

        {/* Main Grid */}
        <div className={styles.mainGrid}>
          {/* Gallery */}
          <div className={styles.gallery}>
            <div className={styles.mainImage}>
              {images[activeImg] ? (
                <img src={getImageUrl(images[activeImg])} alt={product.name} />
              ) : (
                <div className={styles.imagePlaceholder}>🍞</div>
              )}
            </div>
            {images.length > 1 && (
              <div className={styles.thumbnails}>
                {images.map((img, i) => (
                  <button
                    key={i}
                    className={`${styles.thumbnail} ${activeImg === i ? styles.thumbnailActive : ''}`}
                    onClick={() => setActiveImg(i)}
                  >
                    {img ? <img src={getImageUrl(img)} alt={`${product.name} ${i+1}`} /> : <div className={styles.thumbnailPlaceholder}>🍞</div>}
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Info */}
          <div className={styles.info}>
            <div className={styles.categoryBadge}>{product.category?.name}</div>
            <h1 className={styles.productName}>{product.name}</h1>

            {product.avg_rating > 0 && (
              <div className={styles.ratingRow}>
                <StarRating rating={product.avg_rating} count={product.reviews_count} />
                <a href="#ulasan" className={styles.reviewLink}>Lihat ulasan</a>
              </div>
            )}

            <div className={styles.priceRow}>
              <span className={styles.price}>{formatPrice(product.price)}</span>
            </div>

            <Badge variant={stockStatus.variant}>{stockStatus.label}</Badge>

            {!outOfStock && (
              <div className={styles.qtyRow}>
                <span className={styles.qtyLabel}>Jumlah</span>
                <div className={styles.qty}>
                  <button className={styles.qtyBtn} onClick={() => setQty(q => Math.max(1, q - 1))}><Minus size={14} /></button>
                  <span className={styles.qtyNum}>{qty}</span>
                  <button className={styles.qtyBtn} onClick={() => setQty(q => Math.min(product.current_stock, q + 1))} disabled={qty >= product.current_stock}><Plus size={14} /></button>
                </div>
                <span className={styles.stockInfo}>Stok: {product.current_stock}</span>
              </div>
            )}

            <div className={styles.ctaRow}>
              <Button variant="primary" size="lg" fullWidth onClick={handleAddToCart} disabled={outOfStock}>
                <ShoppingCart size={18} />
                {inCart ? 'Tambah Lagi' : 'Tambah ke Keranjang'}
              </Button>

              <button
                className={`${styles.wishlistBtn} ${wishlisted ? styles.wishlisted : ''}`}
                onClick={() => toggle(product)}
                aria-label={wishlisted ? 'Hapus dari wishlist' : 'Tambah ke wishlist'}
              >
                <motion.div animate={{ scale: wishlisted ? [1, 1.3, 1] : 1 }} transition={{ duration: 0.4, ease: [0.34, 1.56, 0.64, 1] }}>
                  <Heart size={20} fill={wishlisted ? 'currentColor' : 'none'} />
                </motion.div>
              </button>

              <button className={styles.shareBtn} aria-label="Bagikan" onClick={() => navigator.share?.({ title: product.name, url: window.location.href })}>
                <Share2 size={18} />
              </button>
            </div>

            {product.allergens?.length > 0 && (
              <div className={styles.allergenInfo}>
                <span>⚠️</span>
                <span>Alergen: <strong>{Array.isArray(product.allergens) ? product.allergens.join(', ') : product.allergens}</strong></span>
              </div>
            )}
          </div>
        </div>

        {/* Tabs */}
        <div className={styles.tabs}>
          <div className={styles.tabList} role="tablist">
            {[
              { id: 'deskripsi', label: 'Deskripsi' },
              { id: 'komposisi', label: 'Komposisi' },
              { id: 'ulasan', label: `Ulasan (${product.reviews_count ?? product.reviews?.length ?? 0})` },
            ].map(tab => (
              <button
                key={tab.id}
                role="tab"
                aria-selected={activeTab === tab.id}
                className={`${styles.tab} ${activeTab === tab.id ? styles.tabActive : ''}`}
                onClick={() => setActiveTab(tab.id)}
              >
                {tab.label}
              </button>
            ))}
          </div>

          <div className={styles.tabPanel}>
            {activeTab === 'deskripsi' && (
              <p className={styles.description}>{product.description ?? 'Belum ada deskripsi.'}</p>
            )}
            {activeTab === 'komposisi' && (
              <p className={styles.ingredients}>{product.ingredients ?? 'Informasi komposisi belum tersedia.'}</p>
            )}
            {activeTab === 'ulasan' && (
              <div id="ulasan" className={styles.reviews}>
                {(product.reviews ?? []).length === 0 ? (
                  <p style={{ color: 'var(--color-text-muted)' }}>Belum ada ulasan untuk produk ini.</p>
                ) : (
                  product.reviews.map(r => (
                    <div key={r.id} className={styles.reviewItem}>
                      <div className={styles.reviewHeader}>
                        <div className={styles.reviewAvatar}>{r.customer?.name?.[0] ?? '?'}</div>
                        <div>
                          <p className={styles.reviewName}>{r.customer?.name ?? 'Anonim'}</p>
                          <StarRating rating={r.rating} showNumber={false} size={13} />
                        </div>
                        <span className={styles.reviewDate}>{new Date(r.created_at).toLocaleDateString('id-ID')}</span>
                      </div>
                      <p className={styles.reviewComment}>{r.content}</p>
                    </div>
                  ))
                )}
              </div>
            )}
          </div>
        </div>

        {/* Related */}
        {related.length > 0 && (
          <div className={styles.related}>
            <h3 className={styles.relatedTitle}>Produk Terkait</h3>
            <div className={styles.relatedGrid}>
              {related.map(p => <ProductCard key={p.id} product={p} />)}
            </div>
          </div>
        )}
      </div>
    </div>
  )
}

function LoadingSkeleton() {
  return (
    <div className={styles.page}>
      <div className="container">
        <div className={styles.mainGrid}>
          <div className="skeleton" style={{ aspectRatio: '1', borderRadius: 'var(--rounded-lg)' }} />
          <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
            <div className="skeleton" style={{ height: '1rem', width: '30%' }} />
            <div className="skeleton" style={{ height: '2rem', width: '80%' }} />
            <div className="skeleton" style={{ height: '1rem', width: '50%' }} />
            <div className="skeleton" style={{ height: '2rem', width: '40%' }} />
            <div className="skeleton" style={{ height: '3rem' }} />
          </div>
        </div>
      </div>
    </div>
  )
}

function NotFound() {
  return (
    <div style={{ minHeight: '60vh', display: 'flex', alignItems: 'center', justifyContent: 'center', flexDirection: 'column', gap: '1rem', padding: '2rem' }}>
      <span style={{ fontSize: '4rem' }}>🍞</span>
      <h2>Produk Tidak Ditemukan</h2>
      <Button as={Link} to="/katalog" variant="outline">Kembali ke Katalog</Button>
    </div>
  )
}
