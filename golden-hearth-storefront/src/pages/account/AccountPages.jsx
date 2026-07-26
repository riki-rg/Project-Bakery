import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { Button } from '../../components/ui/Button'
import { Input } from '../../components/ui/Input'
import { AddressMapPicker } from '../../components/maps/AddressMapPicker'
import styles from './AccountPages.module.css'
import { useAuthStore } from '../../store/authStore'
import { useWishlistStore } from '../../store/wishlistStore'
import { customerApi, ordersApi } from '../../services/api'
import { formatPrice, formatDateTime } from '../../lib/utils'
import { Heart, Trash2, Package, MapPin, Plus, Edit2, Check } from 'lucide-react'
import toast from 'react-hot-toast'

// ── Profile Page ──────────────────────────────────────────────
export function ProfilePage() {
  const { user, setUser }             = useAuthStore()
  const [form, setForm]               = useState({ name: user?.name ?? '', email: user?.email ?? '', phone: user?.phone ?? '' })
  const [saving, setSaving]           = useState(false)

  const set = k => e => setForm(f => ({ ...f, [k]: e.target.value }))

  const handleSave = async (e) => {
    e.preventDefault()
    setSaving(true)
    try {
      const res = await customerApi.updateProfile({ name: form.name, phone: form.phone })
      setUser(res.data.data)
      toast.success('Profil berhasil diperbarui!')
    } catch (err) {
      toast.error(err.response?.data?.message ?? 'Gagal menyimpan perubahan.')
    } finally {
      setSaving(false)
    }
  }

  return (
    <div className={styles.section}>
      <h3 className={styles.sectionTitle}>Profil Saya</h3>
      <form onSubmit={handleSave} className={styles.form}>
        <Input label="Nama Lengkap" value={form.name} onChange={set('name')} required />
        <Input label="Email" type="email" value={form.email} disabled hint="Email tidak dapat diubah." />
        <Input label="Nomor Telepon" type="tel" value={form.phone} onChange={set('phone')} placeholder="08XXXXXXXXXX" />
        <div className={styles.formActions}>
          <Button type="submit" variant="primary" loading={saving}>Simpan Perubahan</Button>
        </div>
      </form>
    </div>
  )
}

// ── Orders Page ───────────────────────────────────────────────
export function OrdersPage() {
  const [orders, setOrders]   = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError]     = useState(null)

  useEffect(() => {
    ordersApi.list()
      .then(res => {
        const body = res.data?.data
        setOrders(body?.data ?? body ?? [])
      })
      .catch(() => setError('Gagal memuat pesanan.'))
      .finally(() => setLoading(false))
  }, [])

  const STATUS_STYLE = {
    completed:        styles.statusSuccess,
    delivering:       styles.statusInfo,
    ready_pickup:     styles.statusInfo,
    processing:       styles.statusInfo,
    cancelled:        styles.statusDanger,
    refund:           styles.statusDanger,
    pending_payment:  styles.statusWarning,
    payment_verified: styles.statusInfo,
  }

  const STATUS_LABEL = {
    pending_payment:  'Menunggu Pembayaran',
    payment_verified: 'Pembayaran Terverifikasi',
    processing:       'Diproses',
    ready_pickup:     'Siap Diambil',
    delivering:       'Dalam Pengantaran',
    completed:        'Selesai',
    cancelled:        'Dibatalkan',
    refund:           'Refund',
  }

  if (loading) return (
    <div className={styles.section}>
      <h3 className={styles.sectionTitle}>Pesanan Saya</h3>
      {[1,2,3].map(i => <div key={i} className="skeleton" style={{ height: '80px', borderRadius: 'var(--rounded-md)', marginBottom: '1rem' }} />)}
    </div>
  )

  return (
    <div className={styles.section}>
      <h3 className={styles.sectionTitle}>Pesanan Saya</h3>
      {error && <p style={{ color: 'var(--color-danger)' }}>{error}</p>}
      {orders.length === 0 && !error ? (
        <div className={styles.empty}>
          <Package size={40} className={styles.emptyIcon} />
          <p>Belum ada pesanan</p>
          <Button as={Link} to="/katalog" variant="primary" size="sm">Mulai Belanja</Button>
        </div>
      ) : (
        <div className={styles.orderList}>
          {orders.map(order => (
            <div key={order.id} className={styles.orderCard}>
              <div className={styles.orderHeader}>
                <div>
                  <p className={styles.orderId}>{order.order_number}</p>
                  <p className={styles.orderDate}>
                    {formatDateTime(order.created_at)} · {order.items?.length ?? 0} item
                  </p>
                </div>
                <span className={`${styles.statusBadge} ${STATUS_STYLE[order.status] ?? ''}`}>
                  {STATUS_LABEL[order.status] ?? order.status}
                </span>
              </div>
              <div className={styles.orderFooter}>
                <span className={styles.orderTotal}>{formatPrice(order.grand_total)}</span>
                <Link to={`/pesanan/konfirmasi/${order.id}`} className={styles.orderLink}>Lihat Detail</Link>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}

// ── Wishlist Page ─────────────────────────────────────────────
export function WishlistPage() {
  const { items, remove } = useWishlistStore()

  return (
    <div className={styles.section}>
      <h3 className={styles.sectionTitle}>Wishlist Saya</h3>
      {items.length === 0 ? (
        <div className={styles.empty}>
          <Heart size={40} className={styles.emptyIcon} />
          <p>Wishlistmu masih kosong</p>
          <Button as={Link} to="/katalog" variant="primary" size="sm">Jelajahi Produk</Button>
        </div>
      ) : (
        <div className={styles.wishlistGrid}>
          {items.map(item => (
            <div key={item.productId} className={styles.wishlistItem}>
              <div className={styles.wishlistImage}><span>🍞</span></div>
              <div className={styles.wishlistInfo}>
                <Link to={`/produk/${item.slug}`} className={styles.wishlistName}>{item.name}</Link>
                <p className={styles.wishlistPrice}>{formatPrice(item.price)}</p>
              </div>
              <button className={styles.removeWish} onClick={() => remove(item.productId)} aria-label="Hapus">
                <Trash2 size={16} />
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}

// ── Notifications Page ────────────────────────────────────────
export function NotificationsPage() {
  return (
    <div className={styles.section}>
      <h3 className={styles.sectionTitle}>Notifikasi</h3>
      <div className={styles.empty}>
        <p style={{ color: 'var(--color-text-muted)' }}>Tidak ada notifikasi baru.</p>
      </div>
    </div>
  )
}

// ── Addresses Page ────────────────────────────────────────────
export function AddressesPage() {
  const [addresses, setAddresses] = useState([])
  const [loading, setLoading] = useState(true)
  const [showForm, setShowForm] = useState(false)
  const [editingId, setEditingId] = useState(null)
  const [showMap, setShowMap] = useState(false)
  const [form, setForm] = useState({
    label: '',
    recipient_name: '',
    phone: '',
    address_line: '',
    district: '',
    city: '',
    province: '',
    postal_code: '',
    latitude: '',
    longitude: '',
    notes: '',
  })

  useEffect(() => {
    loadAddresses()
  }, [])

  const loadAddresses = async () => {
    setLoading(true)
    try {
      const res = await customerApi.getAddresses()
      setAddresses(res.data.data || [])
    } catch (err) {
      toast.error('Gagal memuat alamat')
    } finally {
      setLoading(false)
    }
  }

  const handleLocationSelect = (locationData) => {
    setForm((f) => ({
      ...f,
      address_line: locationData.address || f.address_line,
      district: locationData.addressComponents?.district || f.district,
      city: locationData.addressComponents?.city || f.city,
      province: locationData.addressComponents?.province || f.province,
      postal_code: locationData.addressComponents?.postalCode || f.postal_code,
      latitude: locationData.lat.toString(),
      longitude: locationData.lng.toString(),
    }))
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    try {
      if (editingId) {
        await customerApi.updateAddress(editingId, form)
        toast.success('Alamat berhasil diperbarui')
      } else {
        await customerApi.createAddress(form)
        toast.success('Alamat berhasil ditambahkan')
      }
      setShowForm(false)
      setEditingId(null)
      setShowMap(false)
      resetForm()
      loadAddresses()
    } catch (err) {
      toast.error(err.response?.data?.message || 'Gagal menyimpan alamat')
    }
  }

  const handleEdit = (address) => {
    setEditingId(address.id)
    setForm({
      label: address.label || '',
      recipient_name: address.recipient_name || '',
      phone: address.phone || '',
      address_line: address.address_line || '',
      district: address.district || '',
      city: address.city || '',
      province: address.province || '',
      postal_code: address.postal_code || '',
      latitude: address.latitude || '',
      longitude: address.longitude || '',
      notes: address.notes || '',
    })
    setShowForm(true)
    setShowMap(false)
  }

  const handleDelete = async (id) => {
    if (!confirm('Hapus alamat ini?')) return
    try {
      await customerApi.deleteAddress(id)
      toast.success('Alamat berhasil dihapus')
      loadAddresses()
    } catch (err) {
      toast.error('Gagal menghapus alamat')
    }
  }

  const handleSetDefault = async (id) => {
    try {
      await customerApi.setDefaultAddress(id)
      toast.success('Alamat default diperbarui')
      loadAddresses()
    } catch (err) {
      toast.error('Gagal mengatur alamat default')
    }
  }

  const resetForm = () => {
    setForm({
      label: '',
      recipient_name: '',
      phone: '',
      address_line: '',
      district: '',
      city: '',
      province: '',
      postal_code: '',
      latitude: '',
      longitude: '',
      notes: '',
    })
  }

  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }))

  if (loading) {
    return (
      <div className={styles.section}>
        <h3 className={styles.sectionTitle}>Alamat Saya</h3>
        <div className="skeleton" style={{ height: '100px', borderRadius: 'var(--rounded-md)' }} />
      </div>
    )
  }

  return (
    <div className={styles.section}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <h3 className={styles.sectionTitle} style={{ marginBottom: 0 }}>Alamat Saya</h3>
        {!showForm && (
          <Button 
            variant="primary" 
            size="sm" 
            onClick={() => { setShowForm(true); setEditingId(null); resetForm() }}
          >
            <Plus size={16} /> Tambah Alamat
          </Button>
        )}
      </div>

      {showForm && (
        <div className={styles.addressForm}>
          <h4 style={{ marginBottom: '1rem', fontSize: 'var(--typo-title-sm)' }}>
            {editingId ? 'Edit Alamat' : 'Tambah Alamat Baru'}
          </h4>
          
          {/* Map Picker Toggle */}
          <div style={{ marginBottom: '1rem' }}>
            <Button 
              type="button" 
              variant={showMap ? 'primary' : 'outline'} 
              size="sm"
              onClick={() => setShowMap(!showMap)}
            >
              <MapPin size={16} />
              {showMap ? 'Sembunyikan Peta' : 'Pilih Lokasi di Peta'}
            </Button>
          </div>

          {/* Google Maps Picker */}
          {showMap && (
            <div style={{ marginBottom: '1.5rem' }}>
              <AddressMapPicker
                onLocationSelect={handleLocationSelect}
                initialLocation={
                  form.latitude && form.longitude
                    ? { lat: parseFloat(form.latitude), lng: parseFloat(form.longitude) }
                    : null
                }
              />
            </div>
          )}

          <form onSubmit={handleSubmit}>
            <div style={{ display: 'grid', gap: '1rem' }}>
              <Input label="Label (contoh: Rumah, Kantor)" value={form.label} onChange={set('label')} required />
              <Input label="Nama Penerima" value={form.recipient_name} onChange={set('recipient_name')} required />
              <Input label="Nomor Telepon" type="tel" value={form.phone} onChange={set('phone')} required />
              <Input label="Alamat Lengkap" value={form.address_line} onChange={set('address_line')} required />
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                <Input label="Kecamatan" value={form.district} onChange={set('district')} required />
                <Input label="Kota" value={form.city} onChange={set('city')} required />
              </div>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }}>
                <Input label="Provinsi" value={form.province} onChange={set('province')} required />
                <Input label="Kode Pos" value={form.postal_code} onChange={set('postal_code')} required />
              </div>
              <Input label="Catatan (opsional)" value={form.notes} onChange={set('notes')} />
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', marginTop: '1.5rem' }}>
              <Button type="submit" variant="primary">Simpan</Button>
              <Button 
                type="button" 
                variant="outline" 
                onClick={() => { setShowForm(false); setEditingId(null); setShowMap(false); resetForm() }}
              >
                Batal
              </Button>
            </div>
          </form>
        </div>
      )}

      {addresses.length === 0 && !showForm ? (
        <div className={styles.empty}>
          <MapPin size={40} className={styles.emptyIcon} />
          <p>Belum ada alamat tersimpan</p>
        </div>
      ) : (
        <div style={{ display: 'grid', gap: '1rem', marginTop: showForm ? '1.5rem' : 0 }}>
          {addresses.map((addr) => (
            <div key={addr.id} className={styles.addressCard}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'start', marginBottom: '0.75rem' }}>
                <div>
                  <h4 style={{ fontSize: 'var(--typo-body-md)', fontWeight: 600, marginBottom: '0.25rem' }}>
                    {addr.label}
                    {addr.is_default && (
                      <span style={{ 
                        marginLeft: '0.5rem', 
                        fontSize: 'var(--typo-label)', 
                        padding: '0.125rem 0.5rem',
                        background: 'var(--color-success-soft)',
                        color: 'var(--color-success)',
                        borderRadius: 'var(--rounded-xs)',
                        fontWeight: 600
                      }}>
                        Default
                      </span>
                    )}
                  </h4>
                  <p style={{ fontSize: 'var(--typo-body-sm)', fontWeight: 600, color: 'var(--color-text-main)' }}>
                    {addr.recipient_name} • {addr.phone}
                  </p>
                </div>
              </div>
              <p style={{ fontSize: 'var(--typo-body-sm)', color: 'var(--color-text-muted)', lineHeight: 1.6, marginBottom: '1rem' }}>
                {addr.address_line}<br />
                {addr.district}, {addr.city}, {addr.province} {addr.postal_code}
                {addr.notes && <><br /><em>Catatan: {addr.notes}</em></>}
              </p>
              <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
                {!addr.is_default && (
                  <Button variant="ghost" size="sm" onClick={() => handleSetDefault(addr.id)}>
                    <Check size={14} /> Jadikan Default
                  </Button>
                )}
                <Button variant="ghost" size="sm" onClick={() => handleEdit(addr)}>
                  <Edit2 size={14} /> Edit
                </Button>
                <Button variant="ghost" size="sm" onClick={() => handleDelete(addr.id)} style={{ color: 'var(--color-danger)' }}>
                  <Trash2 size={14} /> Hapus
                </Button>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
