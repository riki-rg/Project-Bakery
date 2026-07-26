import { useEffect, useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import { Check, ChevronRight, ChevronLeft, MapPin, Package, CreditCard, ShoppingBag } from 'lucide-react'
import { Button } from '../components/ui/Button'
import { Input, Select, Textarea } from '../components/ui/Input'
import styles from './CheckoutPage.module.css'
import { useCartStore } from '../store/cartStore'
import { useAuthStore } from '../store/authStore'
import { ordersApi, customerApi } from '../services/api'
import { MidtransPayment } from '../components/payment/MidtransPayment'
import { formatPrice } from '../lib/utils'
import toast from 'react-hot-toast'
import { v4 as uuidv4 } from 'uuid'

const STEPS = [
  { id: 1, label: 'Kontak', icon: ShoppingBag },
  { id: 2, label: 'Pengiriman', icon: MapPin },
  { id: 3, label: 'Pembayaran', icon: CreditCard },
  { id: 4, label: 'Konfirmasi', icon: Check },
]

export default function CheckoutPage() {
  const [step, setStep] = useState(1)
  const { items, voucher, voucherDiscount, clearCart } = useCartStore()
  const { user } = useAuthStore()
  const navigate = useNavigate()

  const [contact, setContact] = useState({
    name: user?.name ?? '',
    email: user?.email ?? '',
    phone: '',
    notes: '',
  })

  const [fulfillment, setFulfillment] = useState('delivery') // 'delivery' | 'pickup'
  const [address, setAddress] = useState({
    label: '',
    recipient: '',
    phone: '',
    address_line: '',
    city: '',
    postal_code: '',
    notes: '',
  })

  const [paymentMethod, setPaymentMethod] = useState('midtrans')
  const [agreed, setAgreed] = useState(false)
  const [placing, setPlacing] = useState(false)
  const [createdOrder, setCreatedOrder] = useState(null)

  const cartSubtotal = items.reduce((s, i) => s + i.price * i.quantity, 0)
  const discount = voucherDiscount ?? 0
  const deliveryFee = fulfillment === 'delivery' ? 15000 : 0
  const total = cartSubtotal - discount + deliveryFee

  const next = () => setStep(s => Math.min(s + 1, 4))
  const prev = () => setStep(s => Math.max(s - 1, 1))

  const handlePlaceOrder = async () => {
    setPlacing(true)
    try {
      const idempotencyKey = uuidv4()
      const payload = {
        idempotency_key:   idempotencyKey,
        fulfillment_type:  fulfillment,
        recipient_name:    contact.name,
        recipient_email:   contact.email,
        recipient_phone:   contact.phone,
        notes:             contact.notes || undefined,
        payment_method:    paymentMethod,
        agree_terms:       agreed ? '1' : undefined,
        voucher_code:      voucher || undefined,
        // alamat hanya jika delivery
        ...(fulfillment === 'delivery' ? {
          address_line: address.address_line,
          city:         address.city || undefined,
          postal_code:  address.postal_code || undefined,
        } : {}),
      }

      const res   = await ordersApi.create(payload)
      const order = res.data.data
      setCreatedOrder(order)

      if (paymentMethod !== 'midtrans') {
        clearCart()
        navigate(`/pesanan/konfirmasi/${order.id}`)
      }
    } catch (err) {
      const msg = err.response?.data?.message ?? 'Gagal membuat pesanan. Coba lagi.'
      toast.error(msg)
    } finally {
      setPlacing(false)
    }
  }

  if (items.length === 0) {
    return (
      <div className={styles.empty}>
        <div className="container">
          <div className={styles.emptyContent}>
            <span>🛒</span>
            <h3>Keranjang kosong</h3>
            <Button as={Link} to="/katalog" variant="primary">Mulai Belanja</Button>
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className={styles.page}>
      <div className={`${styles.layout} container`}>
        {/* ── Form ──────────────────────────────────────────────── */}
        <div className={styles.formSection}>
          {/* Step Indicator */}
          <div className={styles.stepIndicator} role="list" aria-label="Langkah checkout">
            {STEPS.map((s, i) => (
              <div key={s.id} className={styles.stepItem} role="listitem">
                <button
                  className={`${styles.stepBtn} ${step >= s.id ? styles.stepActive : ''} ${step > s.id ? styles.stepDone : ''}`}
                  onClick={() => step > s.id && setStep(s.id)}
                  disabled={step <= s.id}
                  aria-current={step === s.id ? 'step' : undefined}
                >
                  {step > s.id ? <Check size={16} /> : <s.icon size={16} />}
                </button>
                <span className={`${styles.stepLabel} ${step >= s.id ? styles.stepLabelActive : ''}`}>{s.label}</span>
                {i < STEPS.length - 1 && <div className={`${styles.stepLine} ${step > s.id ? styles.stepLineDone : ''}`} />}
              </div>
            ))}
          </div>

          {/* Step Content */}
          <AnimatePresence mode="wait">
            <motion.div
              key={step}
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -20 }}
              transition={{ duration: 0.3, ease: [0.4, 0, 0.2, 1] }}
              className={styles.stepContent}
            >
              {step === 1 && <StepContact contact={contact} setContact={setContact} onNext={next} />}
              {step === 2 && <StepFulfillment fulfillment={fulfillment} setFulfillment={setFulfillment} address={address} setAddress={setAddress} onNext={next} onPrev={prev} />}
              {step === 3 && <StepPayment paymentMethod={paymentMethod} setPaymentMethod={setPaymentMethod} agreed={agreed} setAgreed={setAgreed} onNext={next} onPrev={prev} />}
              {step === 4 && (
                <StepReview
                  contact={contact}
                  fulfillment={fulfillment}
                  address={address}
                  paymentMethod={paymentMethod}
                  items={items}
                  onPrev={prev}
                  onPlace={handlePlaceOrder}
                  placing={placing}
                  agreed={agreed}
                  createdOrder={createdOrder}
                  clearCart={clearCart}
                  navigate={navigate}
                />
              )}
            </motion.div>
          </AnimatePresence>
        </div>

        {/* ── Order Summary ──────────────────────────────────────── */}
        <div className={styles.summarySection}>
          <div className={styles.summaryCard}>
            <h4 className={styles.summaryTitle}>Ringkasan Pesanan</h4>
            <div className={styles.summaryItems}>
              {items.map((item) => (
                <div key={item.key} className={styles.summaryItem}>
                  <span className={styles.summaryItemName}>
                    {item.name}
                    <span className={styles.summaryItemQty}> ×{item.quantity}</span>
                  </span>
                  <span>{formatPrice(item.price * item.quantity)}</span>
                </div>
              ))}
            </div>

            <div className={styles.divider} />

            <div className={styles.priceRows}>
              <div className={styles.priceRow}>
                <span>Subtotal</span>
                <span>{formatPrice(cartSubtotal)}</span>
              </div>
              {discount > 0 && (
                <div className={`${styles.priceRow} ${styles.discount}`}>
                  <span>Diskon ({voucher})</span>
                  <span>-{formatPrice(discount)}</span>
                </div>
              )}
              <div className={styles.priceRow}>
                <span>Ongkos Kirim</span>
                <span>{fulfillment === 'pickup' ? 'Gratis' : formatPrice(deliveryFee)}</span>
              </div>
            </div>

            <div className={styles.divider} />

            <div className={styles.totalRow}>
              <span>Total</span>
              <span className={styles.totalAmount}>{formatPrice(total)}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

function StepContact({ contact, setContact, onNext }) {
  const set = (k) => (e) => setContact(c => ({ ...c, [k]: e.target.value }))
  return (
    <div className={styles.stepInner}>
      <h3 className={styles.stepTitle}>Data Penerima</h3>
      <div className={styles.fieldGrid}>
        <Input label="Nama Lengkap" type="text" value={contact.name} onChange={set('name')} required />
        <Input label="Email" type="email" value={contact.email} onChange={set('email')} required />
        <Input label="Nomor Telepon" type="tel" value={contact.phone} onChange={set('phone')} required />
      </div>
      <Textarea label="Catatan Pesanan (opsional)" value={contact.notes} onChange={set('notes')} placeholder="Instruksi khusus untuk toko..." />
      <div className={styles.stepActions}>
        <Button variant="primary" onClick={onNext} disabled={!contact.name || !contact.email}>
          Lanjut <ChevronRight size={16} />
        </Button>
      </div>
    </div>
  )
}

function StepFulfillment({ fulfillment, setFulfillment, address, setAddress, onNext, onPrev }) {
  const [savedAddresses, setSavedAddresses] = useState([])
  const [loadingAddresses, setLoadingAddresses] = useState(false)
  const [showNewAddress, setShowNewAddress] = useState(false)
  const { isAuthenticated } = useAuthStore()

  useEffect(() => {
    if (isAuthenticated) {
      setLoadingAddresses(true)
      customerApi.addresses()
        .then(res => setSavedAddresses(res.data?.data ?? []))
        .catch(() => {})
        .finally(() => setLoadingAddresses(false))
    }
  }, [isAuthenticated])

  const set = (k) => (e) => setAddress(a => ({ ...a, [k]: e.target.value }))

  const selectAddress = (addr) => {
    setAddress({
      id: addr.id,
      recipient: addr.recipient_name,
      phone: addr.phone,
      address_line: addr.address_line,
      city: addr.city,
      postal_code: addr.postal_code,
      notes: addr.courier_note || '',
      label: addr.label,
    })
    setShowNewAddress(false)
  }

  return (
    <div className={styles.stepInner}>
      <h3 className={styles.stepTitle}>Metode Pengiriman</h3>

      <div className={styles.fulfillmentOptions}>
        {[
          { value: 'delivery', label: 'Antar ke Rumah', desc: 'Dikirim ke alamatmu dalam 1-2 jam', icon: '🚚' },
          { value: 'pickup', label: 'Ambil di Toko', desc: 'Ambil langsung di cabang pilihanmu', icon: '🏪' },
        ].map(opt => (
          <label key={opt.value} className={`${styles.fulfillmentOption} ${fulfillment === opt.value ? styles.fulfillmentActive : ''}`}>
            <input type="radio" name="fulfillment" value={opt.value} checked={fulfillment === opt.value} onChange={() => setFulfillment(opt.value)} className="sr-only" />
            <span className={styles.fulfillmentIcon}>{opt.icon}</span>
            <div>
              <p className={styles.fulfillmentLabel}>{opt.label}</p>
              <p className={styles.fulfillmentDesc}>{opt.desc}</p>
            </div>
            {fulfillment === opt.value && <Check size={18} className={styles.fulfillmentCheck} />}
          </label>
        ))}
      </div>

      {fulfillment === 'delivery' && (
        <div className={styles.addressForm}>
          <h5 className={styles.addressTitle}>Alamat Pengiriman</h5>

          {isAuthenticated && savedAddresses.length > 0 && !showNewAddress && (
            <div className={styles.savedAddresses}>
              {savedAddresses.map(addr => (
                <button
                  key={addr.id}
                  className={`${styles.savedAddressBtn} ${address.id === addr.id ? styles.savedAddressActive : ''}`}
                  onClick={() => selectAddress(addr)}
                >
                  <span className={styles.addressLabel}>{addr.label}</span>
                  <p className={styles.addressText}>{addr.recipient_name} · {addr.phone}</p>
                  <p className={styles.addressDetail}>{addr.address_line}, {addr.city}</p>
                </button>
              ))}
              <Button variant="outline" size="sm" onClick={() => setShowNewAddress(true)}>
                + Tambah Alamat Baru
              </Button>
            </div>
          )}

          {(showNewAddress || !isAuthenticated || savedAddresses.length === 0) && (
            <>
              <div className={styles.fieldGrid}>
                <Input label="Nama Penerima" value={address.recipient} onChange={set('recipient')} required />
                <Input label="Telepon Penerima" type="tel" value={address.phone} onChange={set('phone')} required />
              </div>
              <Textarea label="Alamat Lengkap" value={address.address_line} onChange={set('address_line')} placeholder="Nama jalan, nomor rumah, RT/RW..." required />
              <div className={styles.fieldGrid}>
                <Input label="Kota" value={address.city} onChange={set('city')} required />
                <Input label="Kode Pos" value={address.postal_code} onChange={set('postal_code')} required />
              </div>
              <Input label="Patokan (opsional)" value={address.notes} onChange={set('notes')} placeholder="Dekat masjid, di seberang minimarket..." />
            </>
          )}
        </div>
      )}

      {fulfillment === 'pickup' && (
        <div className={styles.pickupInfo}>
          <p className={styles.pickupLabel}>Pilih Cabang</p>
          {['Golden Hearth Kemang', 'Golden Hearth Senopati', 'Golden Hearth BSD'].map(b => (
            <label key={b} className={styles.branchOption}>
              <input type="radio" name="branch" /> {b}
            </label>
          ))}
        </div>
      )}

      <div className={styles.stepActions}>
        <Button variant="outline" onClick={onPrev}><ChevronLeft size={16} /> Kembali</Button>
        <Button variant="primary" onClick={onNext}>Lanjut <ChevronRight size={16} /></Button>
      </div>
    </div>
  )
}

function StepPayment({ paymentMethod, setPaymentMethod, agreed, setAgreed, onNext, onPrev }) {
  return (
    <div className={styles.stepInner}>
      <h3 className={styles.stepTitle}>Metode Pembayaran</h3>

      <div className={styles.paymentOptions}>
        {[
          { value: 'midtrans', label: 'Bayar via Midtrans', desc: 'Transfer Bank, GoPay, OVO, QRIS, Kartu Kredit & lainnya', icon: '💳' },
          { value: 'cod', label: 'Bayar di Tempat (COD)', desc: 'Hanya untuk pesanan pickup atau area tertentu', icon: '💵' },
        ].map(opt => (
          <label key={opt.value} className={`${styles.paymentOption} ${paymentMethod === opt.value ? styles.paymentActive : ''}`}>
            <input type="radio" name="payment" value={opt.value} checked={paymentMethod === opt.value} onChange={() => setPaymentMethod(opt.value)} className="sr-only" />
            <span className={styles.paymentIcon}>{opt.icon}</span>
            <div>
              <p className={styles.paymentLabel}>{opt.label}</p>
              <p className={styles.paymentDesc}>{opt.desc}</p>
            </div>
            {paymentMethod === opt.value && <Check size={18} className={styles.paymentCheck} />}
          </label>
        ))}
      </div>

      <label className={styles.agreeCheck}>
        <input type="checkbox" checked={agreed} onChange={(e) => setAgreed(e.target.checked)} />
        <span>
          Saya setuju dengan <Link to="/syarat-ketentuan">Syarat & Ketentuan</Link> dan <Link to="/kebijakan-privasi">Kebijakan Privasi</Link> Golden Hearth.
        </span>
      </label>

      <div className={styles.stepActions}>
        <Button variant="outline" onClick={onPrev}><ChevronLeft size={16} /> Kembali</Button>
        <Button variant="primary" onClick={onNext} disabled={!agreed}>Tinjau Pesanan <ChevronRight size={16} /></Button>
      </div>
    </div>
  )
}

function StepReview({ contact, fulfillment, address, paymentMethod, items, onPrev, onPlace, placing, agreed, createdOrder, clearCart, navigate }) {
  return (
    <div className={styles.stepInner}>
      <h3 className={styles.stepTitle}>Tinjau Pesanan</h3>

      <div className={styles.reviewSection}>
        <h6>Data Penerima</h6>
        <p>{contact.name} · {contact.email} · {contact.phone}</p>
      </div>

      <div className={styles.reviewSection}>
        <h6>{fulfillment === 'delivery' ? 'Alamat Pengiriman' : 'Metode'}</h6>
        <p>{fulfillment === 'delivery' ? `${address.address_line}, ${address.city} ${address.postal_code}` : 'Ambil di Toko'}</p>
      </div>

      <div className={styles.reviewSection}>
        <h6>Pembayaran</h6>
        <p>{paymentMethod === 'midtrans' ? 'Via Midtrans (Transfer/GoPay/QRIS/dll)' : 'Bayar di Tempat'}</p>
      </div>

      <div className={styles.stepActions}>
        <Button variant="outline" onClick={onPrev}><ChevronLeft size={16} /> Kembali</Button>
        {!createdOrder && (
          <Button variant="primary" onClick={onPlace} loading={placing} disabled={!agreed}>
            <Package size={16} />
            Buat Pesanan
          </Button>
        )}
      </div>

      {createdOrder && paymentMethod === 'midtrans' && (
        <div className={styles.reviewSection}>
          <h6>Pembayaran Midtrans</h6>
          <p>Pesanan sudah dibuat. Lanjutkan pembayaran melalui popup Midtrans. Status final tetap diverifikasi server.</p>
          <div style={{ marginTop: 'var(--spacing-md)' }}>
            <MidtransPayment
              orderId={createdOrder.id}
              fullWidth
              onPaymentStatus={({ status }) => {
                if (status === 'paid') {
                  clearCart()
                  navigate(`/pesanan/konfirmasi/${createdOrder.id}`)
                }
                if (status === 'pending') {
                  toast.success('Pembayaran masih pending. Cek status pesanan secara berkala.')
                  navigate(`/pesanan/konfirmasi/${createdOrder.id}`)
                }
                if (status === 'failed' || status === 'expired') {
                  toast.error('Pembayaran gagal atau kedaluwarsa. Silakan coba lagi dari detail pesanan.')
                }
              }}
            />
          </div>
        </div>
      )}
    </div>
  )
}
