import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { Copy, Gift, Tag, Truck } from 'lucide-react'
import toast from 'react-hot-toast'
import { Button } from '../../components/ui/Button'
import { productsApi } from '../../services/api'
import { fallbackPromotions } from '../../data/bakeryContent'
import { formatPrice } from '../../lib/utils'
import styles from './PromoPage.module.css'

function promoIcon(type) {
  if (type === 'shipping') return Truck
  if (type === 'fixed') return Tag
  return Gift
}

export default function PromoPage() {
  const [promos, setPromos] = useState(fallbackPromotions)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    productsApi.promotions()
      .then((res) => {
        const data = res.data?.data ?? []
        setPromos(data.length ? data : fallbackPromotions)
      })
      .catch(() => setPromos(fallbackPromotions))
      .finally(() => setLoading(false))
  }, [])

  const copyCode = async (code) => {
    await navigator.clipboard?.writeText(code)
    toast.success(`Kode ${code} disalin`)
  }

  return (
    <div className={styles.page}>
      <section className={styles.hero}>
        <div className="container">
          <p className={styles.eyebrow}>Promo Golden Hearth</p>
          <h1>Promo aktif untuk roti harian, pastry, cake, dan delivery.</h1>
          <p>Promo yang tampil di sini dikontrol dari dashboard Laravel. Kalau owner menyembunyikan promo, kode itu otomatis hilang dari storefront.</p>
        </div>
      </section>

      <section className="section">
        <div className={`${styles.promoGrid} container`}>
          {loading
            ? [1, 2, 3].map((i) => <div key={i} className="skeleton" style={{ height: 260, borderRadius: 'var(--rounded-lg)' }} />)
            : promos.map((promo) => {
                const Icon = promoIcon(promo.type)
                return (
                  <article key={promo.id} className={styles.promoCard}>
                    <div className={styles.iconWrap}><Icon size={28} /></div>
                    <p className={styles.type}>{promo.type === 'shipping' ? 'Gratis Ongkir' : promo.type === 'fixed' ? 'Potongan Nominal' : 'Diskon Persentase'}</p>
                    <h2>{promo.name}</h2>
                    <p className={styles.desc}>{promo.description}</p>
                    <div className={styles.meta}>
                      <span>Min. belanja {formatPrice(promo.min_spend ?? 0)}</span>
                      {promo.ends_at && <span>Berakhir {new Date(promo.ends_at).toLocaleDateString('id-ID')}</span>}
                    </div>
                    <div className={styles.codeRow}>
                      <code>{promo.code}</code>
                      <button onClick={() => copyCode(promo.code)} aria-label={`Salin kode ${promo.code}`}><Copy size={16} /></button>
                    </div>
                    <Button as={Link} to="/katalog" variant="primary" fullWidth>Belanja Sekarang</Button>
                  </article>
                )
              })}
        </div>
      </section>
    </div>
  )
}
