import { useState, useEffect } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import { Minus, Plus, Trash2, ShoppingCart, ArrowLeft, Tag, ChevronRight } from 'lucide-react'
import { Button } from '../components/ui/Button'
import styles from './CartPage.module.css'
import { useCartStore } from '../store/cartStore'
import { useAuthStore } from '../store/authStore'
import { cartApi } from '../services/api'
import { formatPrice, getImageUrl } from '../lib/utils'
import toast from 'react-hot-toast'

export default function CartPage() {
  const { items, updateQuantity, removeItem, clearCart, voucher, voucherDiscount, applyVoucher, removeVoucher, subtotal, itemCount } = useCartStore()
  const { isAuthenticated } = useAuthStore()
  const navigate = useNavigate()
  const [voucherInput, setVoucherInput] = useState('')
  const [voucherLoading, setVoucherLoading] = useState(false)
  const [voucherError, setVoucherError] = useState('')

  const total = Math.max(0, subtotal - voucherDiscount)

  useEffect(() => {
    if (isAuthenticated && items.length > 0) {
      cartApi.validate().catch(() => {})
    }
  }, [isAuthenticated, items.length])

  const handleVoucher = async () => {
    if (!voucherInput.trim()) return
    setVoucherLoading(true)
    setVoucherError('')
    try {
      const res = await cartApi.applyVoucher(voucherInput.trim())
      if (res.data?.success) {
        applyVoucher(voucherInput.trim().toUpperCase(), res.data.data.discount)
        setVoucherInput('')
        toast.success('Voucher berhasil diterapkan!')
      }
    } catch (err) {
      setVoucherError(err.response?.data?.message ?? 'Kode voucher tidak valid.')
    } finally {
      setVoucherLoading(false)
    }
  }

  const handleUpdateQty = async (key, qty) => {
    await updateQuantity(key, qty)
  }

  const handleRemove = async (key) => {
    await removeItem(key)
  }

  const handleClear = async () => {
    if (!confirm('Yakin ingin mengosongkan keranjang?')) return
    await clearCart()
  }

  if (items.length === 0) {
    return (
      <div className={styles.empty}>
        <div className="container">
          <motion.div className={styles.emptyContent} initial={{ opacity: 0, y: 30 }} animate={{ opacity: 1, y: 0 }}>
            <span className={styles.emptyEmoji}>🛒</span>
            <h2>Keranjangmu Kosong</h2>
            <p>Tambahkan produk favoritmu ke keranjang untuk mulai belanja.</p>
            <Button as={Link} to="/katalog" variant="primary" size="lg">
              <ShoppingCart size={18} />
              Mulai Belanja
            </Button>
          </motion.div>
        </div>
      </div>
    )
  }

  return (
    <div className={styles.page}>
      <div className={`${styles.layout} container`}>
        <div className={styles.itemsSection}>
          <div className={styles.sectionHeader}>
            <h2 className={styles.pageTitle}>Keranjang</h2>
            <button className={styles.clearBtn} onClick={handleClear}>
              <Trash2 size={14} />
              Kosongkan
            </button>
          </div>

          <div className={styles.itemList}>
            {items.map((item) => (
              <motion.div key={item.key} className={styles.item} layout exit={{ opacity: 0, x: -20 }} transition={{ duration: 0.3 }}>
                <div className={styles.itemImage}>
                  {item.image ? <img src={getImageUrl(item.image)} alt={item.name} /> : <span>🍞</span>}
                </div>
                <div className={styles.itemInfo}>
                  <p className={styles.itemName}>{item.name}</p>
                  <p className={styles.itemPrice}>{formatPrice(item.price)}</p>
                </div>
                <div className={styles.itemActions}>
                  <div className={styles.qty}>
                    <button className={styles.qtyBtn} onClick={() => handleUpdateQty(item.key, item.quantity - 1)}><Minus size={14} /></button>
                    <span className={styles.qtyNum}>{item.quantity}</span>
                    <button className={styles.qtyBtn} onClick={() => handleUpdateQty(item.key, item.quantity + 1)} disabled={item.quantity >= item.maxStock}><Plus size={14} /></button>
                  </div>
                  <span className={styles.itemSubtotal}>{formatPrice(item.price * item.quantity)}</span>
                  <button className={styles.removeBtn} onClick={() => handleRemove(item.key)}><Trash2 size={16} /></button>
                </div>
              </motion.div>
            ))}
          </div>

          <Link to="/katalog" className={styles.continueShopping}>
            <ArrowLeft size={16} /> Lanjut Belanja
          </Link>
        </div>

        <div className={styles.summary}>
          <div className={styles.summaryCard}>
            <h4 className={styles.summaryTitle}>Ringkasan Pesanan</h4>

            {voucher ? (
              <div className={styles.voucherApplied}>
                <div><Tag size={14} /><span className={styles.voucherCode}>{voucher}</span></div>
                <button className={styles.removeVoucher} onClick={removeVoucher}><Trash2 size={14} /></button>
              </div>
            ) : (
              <div className={styles.voucherForm}>
                <input
                  type="text"
                  className={styles.voucherInput}
                  placeholder="Masukkan kode voucher"
                  value={voucherInput}
                  onChange={(e) => setVoucherInput(e.target.value.toUpperCase())}
                  onKeyDown={(e) => e.key === 'Enter' && handleVoucher()}
                />
                <Button variant="outline" size="sm" onClick={handleVoucher} loading={voucherLoading} disabled={!voucherInput.trim()}>
                  Terapkan
                </Button>
                {voucherError && <p className={styles.voucherError}>{voucherError}</p>}
              </div>
            )}

            <div className={styles.divider} />

            <div className={styles.priceRows}>
              <div className={styles.priceRow}><span>Subtotal ({itemCount} item)</span><span>{formatPrice(subtotal)}</span></div>
              {voucherDiscount > 0 && (
                <div className={`${styles.priceRow} ${styles.discount}`}><span>Diskon Voucher</span><span>-{formatPrice(voucherDiscount)}</span></div>
              )}
              <div className={styles.priceRow}><span>Ongkos Kirim</span><span className="text-muted">Dihitung saat checkout</span></div>
            </div>

            <div className={styles.divider} />

            <div className={styles.totalRow}>
              <span>Total</span>
              <span className={styles.totalAmount}>{formatPrice(total)}</span>
            </div>

            <Button
              variant="primary"
              fullWidth
              size="lg"
              onClick={() => navigate(isAuthenticated ? '/checkout' : '/masuk?redirect=/checkout')}
            >
              Lanjut ke Checkout <ChevronRight size={18} />
            </Button>

            <p className={styles.note}>Ongkos kirim dan pajak dihitung saat checkout</p>
          </div>
        </div>
      </div>
    </div>
  )
}
