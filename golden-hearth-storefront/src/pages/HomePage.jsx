import { useState, useEffect, useRef } from 'react'
import { Link } from 'react-router-dom'
import { motion } from 'framer-motion'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { ArrowRight, Star, ShoppingBag, Truck, Clock, ShieldCheck, MapPin, MessageCircle } from 'lucide-react'
import { Button } from '../components/ui/Button'
import { Badge } from '../components/ui/Badge'
import { StarRating } from '../components/ui/StarRating'
import { ProductCard } from '../components/sections/ProductCard'
import styles from './HomePage.module.css'
import { productsApi } from '../services/api'
import { formatPrice } from '../lib/utils'

gsap.registerPlugin(ScrollTrigger)

const CATEGORY_EMOJI = {
  'roti': '🍞',
  'pastry': '🥐',
  'kue': '🎂',
  'cookie': '🍪',
  'minuman': '☕',
}

const TESTIMONIALS = [
  { id: 1, name: 'Dian Kusuma', rating: 5, text: 'Sourdough terbaik yang pernah saya coba! Teksturnya sempurna dan aromanya luar biasa.', date: '2 hari lalu' },
  { id: 2, name: 'Budi Santoso', rating: 5, text: 'Pengiriman cepat, roti masih hangat waktu sampai. Croissantnya flaky banget!', date: '5 hari lalu' },
  { id: 3, name: 'Sarah Maharani', rating: 4, text: 'Langgganan tiap minggu. Kualitasnya konsisten dan packagingnya cantik banget.', date: '1 minggu lalu' },
]

const BRANCHES = [
  { id: 1, name: 'Golden Hearth Kemang', address: 'Jl. Kemang Raya No. 15', hours: '07:00 – 21:00', phone: '021-1234567' },
  { id: 2, name: 'Golden Hearth Senopati', address: 'Jl. Senopati No. 27', hours: '07:00 – 21:00', phone: '021-7654321' },
  { id: 3, name: 'Golden Hearth BSD', address: 'BSD City, Jl. Pahlawan Seribu', hours: '08:00 – 20:00', phone: '021-1122334' },
]

const staggerContainer = {
  hidden: { opacity: 0 },
  show: { opacity: 1, transition: { staggerChildren: 0.1 } },
}

const fadeUp = {
  hidden: { opacity: 0, y: 24 },
  show: { opacity: 1, y: 0, transition: { duration: 0.5, ease: [0.4, 0, 0.2, 1] } },
}

export default function HomePage() {
  const heroRef = useRef(null)
  const featuresRef = useRef(null)
  
  const [categories, setCategories] = useState([])
  const [products, setProducts]     = useState([])
  const [promo, setPromo]           = useState(null)
  const [loading, setLoading]       = useState(true)

  useEffect(() => {
    Promise.all([
      productsApi.categories(),
      productsApi.list({ per_page: 8, sort: 'terbaru' }),
      productsApi.promotions()
    ])
      .then(([catRes, prodRes, promoRes]) => {
        const cats  = catRes.data?.data ?? []
        const prods = prodRes.data?.data?.data ?? prodRes.data?.data ?? []
        const promos = promoRes.data?.data ?? []
        
        setCategories(cats.map(c => ({
          ...c,
          emoji: CATEGORY_EMOJI[c.slug] ?? '🍞',
          count: c.products_count ?? 0,
        })))
        setProducts(prods)
        if (promos.length > 0) setPromo(promos[0])
      })
      .catch(err => console.error('Failed to load homepage data:', err))
      .finally(() => setLoading(false))
  }, [])

  useEffect(() => {
    // GSAP ScrollTrigger for features reveal
    const ctx = gsap.context(() => {
      gsap.from('.gsap-feature-card', {
        scrollTrigger: {
          trigger: featuresRef.current,
          start: 'top 80%',
        },
        y: 40,
        opacity: 0,
        duration: 0.6,
        stagger: 0.12,
        ease: 'power2.out',
      })
    }, featuresRef)

    return () => ctx.revert()
  }, [])

  return (
    <div className={styles.page}>
      {/* ── Hero ──────────────────────────────────────────────── */}
      <section className={styles.hero} ref={heroRef} aria-label="Hero">
        <div className={`${styles.heroInner} container`}>
          <motion.div
            className={styles.heroContent}
            initial={{ opacity: 0, x: -40 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.7, ease: [0.4, 0, 0.2, 1] }}
          >
            <Badge variant="promo" className={styles.heroBadge}>
              ✨ Baru Dipanggang Setiap Pagi
            </Badge>
            <h1 className={styles.heroTitle}>
              Roti Artisan<br />
              <span className={styles.heroAccent}>Hangat & Lezat</span><br />
              Diantarkan ke Pintumu
            </h1>
            <p className={styles.heroDesc}>
              Dipanggang setiap pagi dengan bahan premium pilihan. Lebih dari 50 jenis roti, pastry, dan kue — siap diantar atau diambil di cabang terdekat.
            </p>
            <div className={styles.heroCta}>
              <Button as={Link} to="/katalog" variant="primary" size="lg">
                <ShoppingBag size={18} />
                Pesan Sekarang
              </Button>
              <Button as={Link} to="/katalog" variant="outline" size="lg">
                Lihat Katalog
                <ArrowRight size={18} />
              </Button>
            </div>
            <div className={styles.heroStats}>
              <div className={styles.stat}>
                <span className={styles.statNumber}>50+</span>
                <span className={styles.statLabel}>Jenis Produk</span>
              </div>
              <div className={styles.statDivider} />
              <div className={styles.stat}>
                <span className={styles.statNumber}>3</span>
                <span className={styles.statLabel}>Cabang</span>
              </div>
              <div className={styles.statDivider} />
              <div className={styles.stat}>
                <StarRating rating={4.9} count={500} size={14} />
              </div>
            </div>
          </motion.div>

          <motion.div
            className={styles.heroImageWrapper}
            initial={{ opacity: 0, scale: 0.95 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.7, delay: 0.15, ease: [0.4, 0, 0.2, 1] }}
          >
            <div className={styles.heroImagePlaceholder}>
              <span className={styles.heroEmoji}>🍞</span>
              <p>Foto Hero Produk</p>
            </div>
            <div className={styles.heroFloat1}>
              <span>🥐</span> Croissant Mentega
            </div>
            <div className={styles.heroFloat2}>
              <Star size={14} fill="currentColor" />
              4.9 · 500+ ulasan
            </div>
          </motion.div>
        </div>
      </section>

      {/* ── Categories ────────────────────────────────────────── */}
      <section className={`${styles.section} ${styles.sectionAlt}`} aria-label="Kategori Produk">
        <div className="container">
          <motion.div
            className={styles.sectionHeader}
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
          >
            <h2>Temukan Favoritmu</h2>
            <p className="text-muted">Jelajahi koleksi roti dan pastry artisan kami</p>
          </motion.div>

          {loading ? (
            <div className={styles.categoryGrid}>
              {[1,2,3,4,5].map(i => <div key={i} className="skeleton" style={{ height: '140px', borderRadius: 'var(--rounded-lg)' }} />)}
            </div>
          ) : (
            <motion.div
              className={styles.categoryGrid}
              variants={staggerContainer}
              initial="hidden"
              whileInView="show"
              viewport={{ once: true }}
            >
              {categories.map((cat) => (
                <motion.div key={cat.id} variants={fadeUp}>
                  <Link to={`/katalog?kategori=${cat.slug}`} className={styles.categoryCard}>
                    <span className={styles.categoryEmoji}>{cat.emoji}</span>
                    <span className={styles.categoryName}>{cat.name}</span>
                    <span className={styles.categoryCount}>{cat.count} produk</span>
                  </Link>
                </motion.div>
              ))}
            </motion.div>
          )}
        </div>
      </section>

      {/* ── Popular Products ───────────────────────────────────── */}
      <section className={styles.section} aria-label="Produk Populer">
        <div className="container">
          <div className={styles.sectionHeaderRow}>
            <div>
              <h2>Produk Terlaris</h2>
              <p className="text-muted">Pilihan favorit pelanggan kami setiap harinya</p>
            </div>
            <Button as={Link} to="/katalog" variant="outline" size="sm">
              Lihat Semua <ArrowRight size={16} />
            </Button>
          </div>

          {loading ? (
            <div className={styles.productGrid}>
              {[1,2,3,4,5,6,7,8].map(i => <div key={i} className="skeleton" style={{ height: '320px', borderRadius: 'var(--rounded-md)' }} />)}
            </div>
          ) : (
            <motion.div
              className={styles.productGrid}
              variants={staggerContainer}
              initial="hidden"
              whileInView="show"
              viewport={{ once: true, margin: '-50px' }}
            >
              {products.map((product) => (
                <motion.div key={product.id} variants={fadeUp}>
                  <ProductCard product={product} />
                </motion.div>
              ))}
            </motion.div>
          )}
        </div>
      </section>

      {/* ── Promo Banner ──────────────────────────────────────── */}
      {promo && (
        <section className={`${styles.section} ${styles.promoBanner}`} aria-label="Promo">
          <div className="container">
            <motion.div
              className={styles.promoCard}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6 }}
            >
              <div className={styles.promoContent}>
                <Badge variant="promo">🎉 Promo Spesial</Badge>
                <h2 className={styles.promoTitle}>{promo.name}</h2>
                <p className={styles.promoDesc}>{promo.description} Gunakan kode <strong>{promo.code}</strong> saat checkout.</p>
                <Button as={Link} to="/katalog" variant="secondary" size="lg">
                  Klaim Sekarang <ArrowRight size={18} />
                </Button>
              </div>
              <div className={styles.promoImagePlaceholder}>
                <span>🎁</span>
              </div>
            </motion.div>
          </div>
        </section>
      )}

      {/* ── Features / Keunggulan ─────────────────────────────── */}
      <section className={`${styles.section} ${styles.sectionAlt}`} ref={featuresRef} aria-label="Keunggulan Kami">
        <div className="container">
          <motion.div
            className={styles.sectionHeader}
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
          >
            <h2>Mengapa Golden Hearth?</h2>
            <p className="text-muted">Kami berkomitmen memberikan pengalaman terbaik</p>
          </motion.div>

          <div className={styles.featuresGrid}>
            {[
              { icon: Clock, title: 'Dipanggang Setiap Pagi', desc: 'Roti segar keluar dari oven setiap hari mulai pukul 06.00. Tidak ada sisa kemarin.' },
              { icon: Truck, title: 'Antar ke Pintumu', desc: 'Pengiriman cepat ke area Jakarta dan sekitarnya. Estimasi 1–2 jam setelah konfirmasi.' },
              { icon: ShieldCheck, title: 'Bahan Premium Pilihan', desc: 'Tepung terigu protein tinggi, mentega Prancis, dan bahan-bahan alami tanpa pengawet.' },
              { icon: Star, title: 'Jaminan Kepuasan', desc: 'Tidak puas? Kami ganti atau refund 100%. Kepuasanmu adalah prioritas kami.' },
            ].map(({ icon: Icon, title, desc }, i) => (
              <div key={i} className={`${styles.featureCard} gsap-feature-card`}>
                <div className={styles.featureIcon}>
                  <Icon size={24} />
                </div>
                <h5 className={styles.featureTitle}>{title}</h5>
                <p className={styles.featureDesc}>{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── Testimonials ──────────────────────────────────────── */}
      <section className={styles.section} aria-label="Ulasan Pelanggan">
        <div className="container">
          <motion.div
            className={styles.sectionHeader}
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
          >
            <h2>Kata Pelanggan Kami</h2>
            <p className="text-muted">Ribuan pelanggan sudah merasakan kelezatannya</p>
          </motion.div>

          <motion.div
            className={styles.testimonialGrid}
            variants={staggerContainer}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true }}
          >
            {TESTIMONIALS.map((t) => (
              <motion.div key={t.id} variants={fadeUp} className={styles.testimonialCard}>
                <StarRating rating={t.rating} showNumber={false} />
                <p className={styles.testimonialText}>"{t.text}"</p>
                <div className={styles.testimonialMeta}>
                  <div className={styles.testimonialAvatar}>{t.name[0]}</div>
                  <div>
                    <p className={styles.testimonialName}>{t.name}</p>
                    <p className="text-subtle" style={{ fontSize: 'var(--typo-label)' }}>{t.date}</p>
                  </div>
                </div>
              </motion.div>
            ))}
          </motion.div>
        </div>
      </section>

      {/* ── Branches ──────────────────────────────────────────── */}
      <section className={`${styles.section} ${styles.sectionAlt}`} aria-label="Cabang Kami">
        <div className="container">
          <div className={styles.sectionHeaderRow}>
            <div>
              <h2>Cabang Kami</h2>
              <p className="text-muted">Kunjungi langsung atau pesan untuk pengambilan</p>
            </div>
            <Button as={Link} to="/lokasi" variant="outline" size="sm">
              <MapPin size={16} />
              Lihat Peta
            </Button>
          </div>

          <motion.div
            className={styles.branchGrid}
            variants={staggerContainer}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true }}
          >
            {BRANCHES.map((branch) => (
              <motion.div key={branch.id} variants={fadeUp} className={styles.branchCard}>
                <div className={styles.branchHeader}>
                  <MapPin size={18} className="text-primary" />
                  <h5 className={styles.branchName}>{branch.name}</h5>
                </div>
                <p className={styles.branchAddress}>{branch.address}</p>
                <div className={styles.branchMeta}>
                  <span><Clock size={14} /> {branch.hours}</span>
                </div>
                <Button
                  as="a"
                  href={`https://wa.me/${import.meta.env.VITE_WHATSAPP ?? '6281234567890'}?text=${encodeURIComponent(`Halo, saya ingin info tentang cabang ${branch.name}`)}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  variant="outline"
                  size="sm"
                  fullWidth
                >
                  <MessageCircle size={15} />
                  Hubungi Cabang
                </Button>
              </motion.div>
            ))}
          </motion.div>
        </div>
      </section>

      {/* ── CTA Bottom ────────────────────────────────────────── */}
      <section className={styles.ctaBottom} aria-label="Ajakan bertindak">
        <div className="container">
          <motion.div
            className={styles.ctaContent}
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
          >
            <h2>Siap Merasakan<br />Kelezatannya?</h2>
            <p>Pesan sekarang dan nikmati roti artisan segar langsung ke rumahmu.</p>
            <Button as={Link} to="/katalog" variant="primary" size="lg">
              <ShoppingBag size={18} />
              Mulai Belanja
            </Button>
          </motion.div>
        </div>
      </section>
    </div>
  )
}
