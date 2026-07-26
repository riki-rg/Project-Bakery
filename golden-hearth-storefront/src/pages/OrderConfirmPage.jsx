import { Link, useParams } from 'react-router-dom'
import { motion } from 'framer-motion'
import { CheckCircle, Package, Clock, MapPin, ArrowRight, MessageCircle } from 'lucide-react'
import { Button } from '../components/ui/Button'
import styles from './OrderConfirmPage.module.css'

const STATUS_TIMELINE = [
  { status: 'Menunggu Pembayaran', done: true, time: '10:00' },
  { status: 'Pembayaran Diverifikasi', done: true, time: '10:02' },
  { status: 'Sedang Diproses', done: true, time: '10:15' },
  { status: 'Dalam Pengantaran', done: false, time: null },
  { status: 'Selesai', done: false, time: null },
]

export default function OrderConfirmPage() {
  const { id } = useParams()

  return (
    <div className={styles.page}>
      <div className={`${styles.inner} container`}>
        {/* Header */}
        <motion.div
          className={styles.header}
          initial={{ opacity: 0, scale: 0.9 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.5, ease: [0.34, 1.56, 0.64, 1] }}
        >
          <div className={styles.successIcon}>
            <CheckCircle size={48} />
          </div>
          <h2 className={styles.title}>Pesanan Berhasil Dibuat!</h2>
          <p className={styles.subtitle}>Nomor pesanan kamu: <strong className={styles.orderNumber}>{id}</strong></p>
          <p className={styles.note}>Kami sedang memproses pesananmu. Status akan diperbarui secara real-time.</p>
        </motion.div>

        <div className={styles.grid}>
          {/* Order Details */}
          <div className={styles.detailCard}>
            <h5 className={styles.cardTitle}>Detail Pesanan</h5>

            <div className={styles.infoRow}>
              <Package size={16} />
              <div>
                <p className={styles.infoLabel}>Item</p>
                <p className={styles.infoValue}>Sourdough Klasik ×2, Croissant Mentega ×1</p>
              </div>
            </div>

            <div className={styles.infoRow}>
              <MapPin size={16} />
              <div>
                <p className={styles.infoLabel}>Pengiriman ke</p>
                <p className={styles.infoValue}>Jl. Merdeka No.5, Jakarta Selatan 12345</p>
              </div>
            </div>

            <div className={styles.infoRow}>
              <Clock size={16} />
              <div>
                <p className={styles.infoLabel}>Estimasi Tiba</p>
                <p className={styles.infoValue}>60 – 90 menit</p>
              </div>
            </div>

            <div className={styles.divider} />

            <div className={styles.priceRows}>
              <div className={styles.priceRow}><span>Subtotal</span><span>Rp 138.000</span></div>
              <div className={styles.priceRow}><span>Ongkos Kirim</span><span>Rp 15.000</span></div>
              <div className={`${styles.priceRow} ${styles.total}`}><span>Total</span><span>Rp 153.000</span></div>
            </div>
          </div>

          {/* Timeline */}
          <div className={styles.timelineCard}>
            <h5 className={styles.cardTitle}>Status Pesanan</h5>
            <div className={styles.timeline}>
              {STATUS_TIMELINE.map((item, i) => (
                <div key={i} className={`${styles.timelineItem} ${item.done ? styles.timelineDone : ''}`}>
                  <div className={styles.timelineDot}>
                    {item.done && <CheckCircle size={14} />}
                  </div>
                  {i < STATUS_TIMELINE.length - 1 && (
                    <div className={`${styles.timelineConnector} ${item.done && STATUS_TIMELINE[i+1].done ? styles.timelineConnectorDone : ''}`} />
                  )}
                  <div className={styles.timelineContent}>
                    <p className={styles.timelineStatus}>{item.status}</p>
                    {item.time && <p className={styles.timelineTime}>{item.time}</p>}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Actions */}
        <div className={styles.actions}>
          <Button as={Link} to="/akun/pesanan" variant="outline">
            Lihat Semua Pesanan
          </Button>
          <Button as={Link} to="/katalog" variant="primary">
            Lanjut Belanja <ArrowRight size={16} />
          </Button>
          <Button
            as="a"
            href={`https://wa.me/${import.meta.env.VITE_WHATSAPP ?? '6281234567890'}?text=${encodeURIComponent(`Halo, saya ingin cek status pesanan ${id}`)}`}
            target="_blank"
            rel="noopener noreferrer"
            variant="ghost"
          >
            <MessageCircle size={16} />
            Hubungi via WhatsApp
          </Button>
        </div>
      </div>
    </div>
  )
}
