import { Link } from 'react-router-dom'
import { MapPin, Phone, Mail, MessageCircle } from 'lucide-react'
import styles from './Footer.module.css'

const WHATSAPP_NUMBER = import.meta.env.VITE_WHATSAPP ?? '6281234567890'

export function Footer() {
  const waLink = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent('Halo Golden Hearth, saya ingin bertanya tentang...')}`

  return (
    <footer className={styles.footer}>
      <div className={`${styles.inner} container`}>
        {/* Brand */}
        <div className={styles.brand}>
          <Link to="/" className={styles.logo}>
            <span className={styles.logoIcon}>🍞</span>
            <span className={styles.logoText}>Golden Hearth</span>
          </Link>
          <p className={styles.tagline}>
            Roti artisan yang dipanggang dengan cinta, diantarkan ke pintumu setiap hari.
          </p>
          <div className={styles.social}>
            <a href="#" className={styles.socialLink} aria-label="Instagram Golden Hearth">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
            </a>
            <a href="#" className={styles.socialLink} aria-label="Facebook Golden Hearth">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <a href={waLink} className={styles.socialLink} target="_blank" rel="noopener noreferrer" aria-label="WhatsApp Golden Hearth">
              <MessageCircle size={18} />
            </a>
          </div>
        </div>

        {/* Links */}
        <div className={styles.linkGroup}>
          <h6 className={styles.groupTitle}>Belanja</h6>
          <ul className={styles.linkList}>
            <li><Link to="/katalog">Semua Produk</Link></li>
            <li><Link to="/katalog?kategori=roti">Roti</Link></li>
            <li><Link to="/katalog?kategori=kue">Kue & Pastry</Link></li>
            <li><Link to="/promo">Promo</Link></li>
          </ul>
        </div>

        <div className={styles.linkGroup}>
          <h6 className={styles.groupTitle}>Akun</h6>
          <ul className={styles.linkList}>
            <li><Link to="/masuk">Masuk</Link></li>
            <li><Link to="/daftar">Daftar</Link></li>
            <li><Link to="/akun/pesanan">Pesanan Saya</Link></li>
            <li><Link to="/akun/wishlist">Wishlist</Link></li>
          </ul>
        </div>

        <div className={styles.linkGroup}>
          <h6 className={styles.groupTitle}>Informasi</h6>
          <ul className={styles.linkList}>
            <li><Link to="/tentang">Tentang Kami</Link></li>
            <li><Link to="/lokasi">Lokasi Cabang</Link></li>
            <li><Link to="/faq">FAQ</Link></li>
            <li><Link to="/kebijakan-privasi">Kebijakan Privasi</Link></li>
            <li><Link to="/syarat-ketentuan">Syarat & Ketentuan</Link></li>
          </ul>
        </div>

        <div className={styles.linkGroup}>
          <h6 className={styles.groupTitle}>Hubungi Kami</h6>
          <ul className={styles.contactList}>
            <li>
              <MapPin size={16} />
              <span>Jl. Bakery No.1, Jakarta Selatan</span>
            </li>
            <li>
              <Phone size={16} />
              <a href="tel:+6281234567890">+62 812 3456 7890</a>
            </li>
            <li>
              <Mail size={16} />
              <a href="mailto:halo@goldenhearth.id">halo@goldenhearth.id</a>
            </li>
            <li>
              <MessageCircle size={16} />
              <a href={waLink} target="_blank" rel="noopener noreferrer">WhatsApp Kami</a>
            </li>
          </ul>
        </div>
      </div>

      <div className={styles.bottom}>
        <div className="container">
          <p className={styles.copyright}>
            &copy; {new Date().getFullYear()} Golden Hearth Bakery. Semua hak dilindungi.
          </p>
        </div>
      </div>
    </footer>
  )
}
