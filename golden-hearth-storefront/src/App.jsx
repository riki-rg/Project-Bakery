import { createBrowserRouter, RouterProvider } from 'react-router-dom'
import { HelmetProvider } from 'react-helmet-async'
import { Toaster } from 'react-hot-toast'
import { RootLayout } from './components/layout/RootLayout'
import { useThemeStore } from './store/themeStore'
import { useEffect } from 'react'

// Pages
import HomePage from './pages/HomePage'
import CatalogPage from './pages/CatalogPage'
import ProductDetailPage from './pages/ProductDetailPage'
import CartPage from './pages/CartPage'
import CheckoutPage from './pages/CheckoutPage'
import OrderConfirmPage from './pages/OrderConfirmPage'
import { LoginPage, RegisterPage, ForgotPasswordPage } from './pages/auth/AuthPage'
import { AccountLayout } from './pages/account/AccountLayout'
import { ProfilePage, OrdersPage, WishlistPage, NotificationsPage, AddressesPage } from './pages/account/AccountPages'
import AboutPage from './pages/static/AboutPage'
import LocationsPage from './pages/static/LocationsPage'
import PromoPage from './pages/static/PromoPage'

const router = createBrowserRouter([
  {
    path: '/',
    element: <RootLayout />,
    children: [
      { index: true, element: <HomePage /> },
      { path: 'katalog', element: <CatalogPage /> },
      { path: 'produk/:slug', element: <ProductDetailPage /> },
      { path: 'keranjang', element: <CartPage /> },
      { path: 'checkout', element: <CheckoutPage /> },
      { path: 'pesanan/konfirmasi/:id', element: <OrderConfirmPage /> },
      {
        path: 'akun',
        element: <AccountLayout />,
        children: [
          { index: true, element: <ProfilePage /> },
          { path: 'pesanan', element: <OrdersPage /> },
          { path: 'wishlist', element: <WishlistPage /> },
          { path: 'notifikasi', element: <NotificationsPage /> },
          { path: 'alamat', element: <AddressesPage /> },
        ],
      },
      { path: 'promo', element: <PromoPage /> },
      { path: 'tentang', element: <AboutPage /> },
      { path: 'lokasi', element: <LocationsPage /> },
      {
        path: 'faq',
        element: (
          <div style={{ padding: 'var(--section-padding) 0' }}>
            <div className="container">
              <h1>FAQ</h1>
              <p style={{ color: 'var(--color-text-muted)', marginTop: '1rem' }}>Halaman FAQ akan segera hadir.</p>
            </div>
          </div>
        ),
      },
      {
        path: '*',
        element: (
          <div style={{ minHeight: '60vh', display: 'flex', alignItems: 'center', justifyContent: 'center', flexDirection: 'column', gap: '1rem', padding: '2rem' }}>
            <span style={{ fontSize: '4rem' }}>🍞</span>
            <h2 style={{ fontFamily: 'Outfit, sans-serif' }}>404 — Halaman Tidak Ditemukan</h2>
            <a href="/" style={{ color: 'var(--color-primary)' }}>← Kembali ke Beranda</a>
          </div>
        ),
      },
    ],
  },
  // Auth — no navbar/footer
  { path: '/masuk', element: <LoginPage /> },
  { path: '/daftar', element: <RegisterPage /> },
  { path: '/lupa-password', element: <ForgotPasswordPage /> },
])

export default function App() {
  const { initTheme } = useThemeStore()

  useEffect(() => {
    initTheme()
  }, [])

  return (
    <HelmetProvider>
      <RouterProvider router={router} />
      <Toaster
        position="top-right"
        toastOptions={{
          style: {
            background: 'var(--color-surface-elevated)',
            color: 'var(--color-text-main)',
            border: '1px solid var(--color-border)',
            borderRadius: 'var(--rounded-sm)',
            fontFamily: 'Inter, sans-serif',
            fontSize: 'var(--typo-body-sm)',
          },
        }}
      />
    </HelmetProvider>
  )
}
