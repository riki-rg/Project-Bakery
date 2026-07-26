import { useState, useEffect } from 'react'
import { Link, NavLink, useNavigate } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import {
  ShoppingCart, Heart, User, Menu, X, Sun, Moon, Monitor,
  Search, ChevronDown, MapPin, Bell
} from 'lucide-react'
import styles from './Navbar.module.css'
import { useCartStore } from '../../store/cartStore'
import { useAuthStore } from '../../store/authStore'
import { useWishlistStore } from '../../store/wishlistStore'
import { useThemeStore } from '../../store/themeStore'
import { cn } from '../../lib/utils'

const NAV_LINKS = [
  { to: '/katalog', label: 'Katalog' },
  { to: '/promo', label: 'Promo' },
  { to: '/lokasi', label: 'Lokasi' },
  { to: '/tentang', label: 'Tentang' },
]

const THEME_OPTIONS = [
  { value: 'light', label: 'Terang', icon: Sun },
  { value: 'dark', label: 'Gelap', icon: Moon },
  { value: 'system', label: 'Sistem', icon: Monitor },
]

export function Navbar() {
  const [mobileOpen, setMobileOpen] = useState(false)
  const [scrolled, setScrolled] = useState(false)
  const [themeOpen, setThemeOpen] = useState(false)
  const [searchOpen, setSearchOpen] = useState(false)
  const [searchQuery, setSearchQuery] = useState('')

  const navigate = useNavigate()
  const { items } = useCartStore()
  const { user, isAuthenticated } = useAuthStore()
  const { items: wishlist } = useWishlistStore()
  const { theme, setTheme } = useThemeStore()

  const cartCount = items.reduce((s, i) => s + i.quantity, 0)

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8)
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  const handleSearch = (e) => {
    e.preventDefault()
    if (searchQuery.trim()) {
      navigate(`/katalog?q=${encodeURIComponent(searchQuery.trim())}`)
      setSearchOpen(false)
      setSearchQuery('')
    }
  }

  const currentThemeIcon = THEME_OPTIONS.find(t => t.value === theme)?.icon ?? Monitor

  return (
    <header className={cn(styles.header, scrolled && styles.scrolled)}>
      <nav className={cn(styles.nav, 'container')}>
        {/* Logo */}
        <Link to="/" className={styles.logo}>
          <span className={styles.logoIcon}>🍞</span>
          <span className={styles.logoText}>R Bread's</span>
        </Link>

        {/* Desktop Nav Links */}
        <ul className={styles.navLinks}>
          {NAV_LINKS.map(({ to, label }) => (
            <li key={to}>
              <NavLink
                to={to}
                className={({ isActive }) =>
                  cn(styles.navLink, isActive && styles.navLinkActive)
                }
              >
                {label}
              </NavLink>
            </li>
          ))}
        </ul>

        {/* Actions */}
        <div className={styles.actions}>
          {/* Search */}
          <button
            className={styles.iconBtn}
            onClick={() => setSearchOpen(true)}
            aria-label="Cari produk"
          >
            <Search size={20} />
          </button>

          {/* Theme Switcher */}
          <div className={styles.themeDropdown}>
            <button
              className={styles.iconBtn}
              onClick={() => setThemeOpen(!themeOpen)}
              aria-label="Ganti tema"
              aria-expanded={themeOpen}
            >
              {<currentThemeIcon size={20} />}
            </button>
            <AnimatePresence>
              {themeOpen && (
                <motion.div
                  className={styles.dropdown}
                  initial={{ opacity: 0, y: -8, scale: 0.97 }}
                  animate={{ opacity: 1, y: 0, scale: 1 }}
                  exit={{ opacity: 0, y: -8, scale: 0.97 }}
                  transition={{ duration: 0.2, ease: [0.4, 0, 0.2, 1] }}
                >
                  {THEME_OPTIONS.map(({ value, label, icon: Icon }) => (
                    <button
                      key={value}
                      className={cn(styles.dropdownItem, theme === value && styles.dropdownItemActive)}
                      onClick={() => { setTheme(value); setThemeOpen(false) }}
                    >
                      <Icon size={16} />
                      {label}
                    </button>
                  ))}
                </motion.div>
              )}
            </AnimatePresence>
          </div>

          {/* Wishlist */}
          <Link to="/akun/wishlist" className={styles.iconBtn} aria-label="Wishlist">
            <Heart size={20} />
            {wishlist.length > 0 && (
              <span className={styles.badge}>{wishlist.length}</span>
            )}
          </Link>

          {/* Cart */}
          <Link to="/keranjang" className={styles.iconBtn} aria-label={`Keranjang (${cartCount} item)`}>
            <ShoppingCart size={20} />
            {cartCount > 0 && (
              <motion.span
                className={styles.badge}
                key={cartCount}
                initial={{ scale: 1.4 }}
                animate={{ scale: 1 }}
                transition={{ duration: 0.3, ease: [0.34, 1.56, 0.64, 1] }}
              >
                {cartCount > 99 ? '99+' : cartCount}
              </motion.span>
            )}
          </Link>

          {/* Auth */}
          {isAuthenticated ? (
            <Link to="/akun" className={cn(styles.iconBtn, styles.avatarBtn)} aria-label="Akun saya">
              <User size={20} />
              <span className={styles.userName}>{user?.name?.split(' ')[0]}</span>
            </Link>
          ) : (
            <Link to="/masuk" className={styles.loginBtn}>
              Masuk
            </Link>
          )}

          {/* Mobile menu toggle */}
          <button
            className={cn(styles.iconBtn, styles.mobileMenu)}
            onClick={() => setMobileOpen(!mobileOpen)}
            aria-label={mobileOpen ? 'Tutup menu' : 'Buka menu'}
          >
            {mobileOpen ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>
      </nav>

      {/* Mobile Menu */}
      <AnimatePresence>
        {mobileOpen && (
          <motion.div
            className={styles.mobileNav}
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            transition={{ duration: 0.3, ease: [0.4, 0, 0.2, 1] }}
          >
            <div className={styles.mobileNavInner}>
              {NAV_LINKS.map(({ to, label }) => (
                <NavLink
                  key={to}
                  to={to}
                  className={({ isActive }) =>
                    cn(styles.mobileNavLink, isActive && styles.mobileNavLinkActive)
                  }
                  onClick={() => setMobileOpen(false)}
                >
                  {label}
                </NavLink>
              ))}
              <div className={styles.mobileDivider} />
              {isAuthenticated ? (
                <Link to="/akun" className={styles.mobileNavLink} onClick={() => setMobileOpen(false)}>
                  Akun Saya
                </Link>
              ) : (
                <>
                  <Link to="/masuk" className={styles.mobileNavLink} onClick={() => setMobileOpen(false)}>Masuk</Link>
                  <Link to="/daftar" className={styles.mobileNavLink} onClick={() => setMobileOpen(false)}>Daftar</Link>
                </>
              )}
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Search Overlay */}
      <AnimatePresence>
        {searchOpen && (
          <motion.div
            className={styles.searchOverlay}
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={(e) => e.target === e.currentTarget && setSearchOpen(false)}
          >
            <motion.form
              className={styles.searchBox}
              onSubmit={handleSearch}
              initial={{ opacity: 0, y: -20 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -20 }}
              transition={{ duration: 0.25 }}
            >
              <Search size={20} className={styles.searchIcon} />
              <input
                type="search"
                className={styles.searchInput}
                placeholder="Cari roti, kue, pastry..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                autoFocus
              />
              <button type="button" className={styles.searchClose} onClick={() => setSearchOpen(false)}>
                <X size={20} />
              </button>
            </motion.form>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  )
}
