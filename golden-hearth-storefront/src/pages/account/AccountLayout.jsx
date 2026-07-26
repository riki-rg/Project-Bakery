import { Link, NavLink, Outlet, useNavigate } from 'react-router-dom'
import { User, MapPin, Heart, Package, Bell, LogOut, ChevronRight } from 'lucide-react'
import styles from './AccountLayout.module.css'
import { useAuthStore } from '../../store/authStore'

const NAV = [
  { to: '/akun', label: 'Profil Saya', icon: User, end: true },
  { to: '/akun/alamat', label: 'Alamat Saya', icon: MapPin },
  { to: '/akun/pesanan', label: 'Pesanan Saya', icon: Package },
  { to: '/akun/wishlist', label: 'Wishlist', icon: Heart },
  { to: '/akun/notifikasi', label: 'Notifikasi', icon: Bell },
]

export function AccountLayout() {
  const { user, logout } = useAuthStore()
  const navigate = useNavigate()

  const handleLogout = async () => {
    await logout()
    navigate('/masuk')
  }

  return (
    <div className={styles.page}>
      <div className={`${styles.layout} container`}>
        {/* Sidebar */}
        <aside className={styles.sidebar}>
          <div className={styles.userCard}>
            <div className={styles.avatar}>{user?.name?.[0] ?? 'U'}</div>
            <div>
              <p className={styles.userName}>{user?.name ?? 'Pengguna'}</p>
              <p className={styles.userEmail}>{user?.email}</p>
            </div>
          </div>

          <nav className={styles.nav}>
            {NAV.map(({ to, label, icon: Icon, end }) => (
              <NavLink
                key={to}
                to={to}
                end={end}
                className={({ isActive }) =>
                  `${styles.navLink} ${isActive ? styles.navLinkActive : ''}`
                }
              >
                <Icon size={18} />
                {label}
                <ChevronRight size={14} className={styles.chevron} />
              </NavLink>
            ))}

            <div className={styles.divider} />

            <button className={styles.logoutBtn} onClick={handleLogout}>
              <LogOut size={18} />
              Keluar
            </button>
          </nav>
        </aside>

        {/* Content */}
        <main className={styles.content}>
          <Outlet />
        </main>
      </div>
    </div>
  )
}
