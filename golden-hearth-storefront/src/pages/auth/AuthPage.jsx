import { useState } from 'react'
import { Link, useNavigate, useLocation } from 'react-router-dom'
import { motion } from 'framer-motion'
import { Eye, EyeOff } from 'lucide-react'
import { Button } from '../../components/ui/Button'
import { Input } from '../../components/ui/Input'
import styles from './AuthPage.module.css'
import { useAuthStore } from '../../store/authStore'
import toast from 'react-hot-toast'

export function LoginPage() {
  const [email, setEmail]     = useState('')
  const [password, setPassword] = useState('')
  const [showPw, setShowPw]   = useState(false)
  const [errors, setErrors]   = useState({})
  const { login, isLoading }  = useAuthStore()
  const navigate              = useNavigate()
  const location              = useLocation()
  const redirect              = new URLSearchParams(location.search).get('redirect') ?? '/'

  const handleSubmit = async (e) => {
    e.preventDefault()
    const errs = {}
    if (!email)    errs.email    = 'Email wajib diisi'
    if (!password) errs.password = 'Kata sandi wajib diisi'
    if (Object.keys(errs).length) { setErrors(errs); return }
    setErrors({})

    const res = await login(email, password)
    if (res.success) {
      toast.success('Berhasil masuk!')
      navigate(redirect, { replace: true })
    } else {
      toast.error(res.message)
    }
  }

  return (
    <AuthLayout
      title="Selamat Datang Kembali"
      subtitle="Masuk untuk melanjutkan belanja kamu"
      footer={<>Belum punya akun? <Link to="/daftar">Daftar sekarang</Link></>}
    >
      <form onSubmit={handleSubmit} className={styles.form} noValidate>
        <Input label="Email" type="email" placeholder="nama@email.com" value={email} onChange={e => setEmail(e.target.value)} error={errors.email} required autoComplete="email" />

        <div className={styles.passwordField}>
          <Input label="Kata Sandi" type={showPw ? 'text' : 'password'} placeholder="Masukkan kata sandi" value={password} onChange={e => setPassword(e.target.value)} error={errors.password} required autoComplete="current-password" />
          <button type="button" className={styles.showPw} onClick={() => setShowPw(!showPw)}>
            {showPw ? <EyeOff size={16} /> : <Eye size={16} />}
          </button>
        </div>

        <Link to="/lupa-password" className={styles.forgotLink}>Lupa kata sandi?</Link>
        <Button type="submit" variant="primary" fullWidth size="lg" loading={isLoading}>Masuk</Button>
      </form>
    </AuthLayout>
  )
}

export function RegisterPage() {
  const [form, setForm]       = useState({ name: '', email: '', phone: '', password: '', password_confirmation: '' })
  const [showPw, setShowPw]   = useState(false)
  const [errors, setErrors]   = useState({})
  const { register, isLoading } = useAuthStore()
  const navigate              = useNavigate()

  const set = k => e => setForm(f => ({ ...f, [k]: e.target.value }))

  const handleSubmit = async (e) => {
    e.preventDefault()
    const errs = {}
    if (!form.name)  errs.name  = 'Nama wajib diisi'
    if (!form.email) errs.email = 'Email wajib diisi'
    if (!form.phone) errs.phone = 'Nomor telepon wajib diisi'
    if (!form.password || form.password.length < 8) errs.password = 'Minimal 8 karakter'
    if (form.password !== form.password_confirmation) errs.password_confirmation = 'Kata sandi tidak cocok'
    if (Object.keys(errs).length) { setErrors(errs); return }
    setErrors({})

    const res = await register(form)
    if (res.success) {
      toast.success('Akun berhasil dibuat!')
      navigate('/')
    } else {
      if (res.errors) setErrors(res.errors)
      else toast.error(res.message)
    }
  }

  return (
    <AuthLayout
      title="Buat Akun Baru"
      subtitle="Daftar untuk mulai berbelanja di Golden Hearth"
      footer={<>Sudah punya akun? <Link to="/masuk">Masuk</Link></>}
    >
      <form onSubmit={handleSubmit} className={styles.form} noValidate>
        <Input label="Nama Lengkap" type="text" placeholder="John Doe" value={form.name} onChange={set('name')} error={errors.name} required autoComplete="name" />
        <Input label="Email" type="email" placeholder="nama@email.com" value={form.email} onChange={set('email')} error={errors.email} required autoComplete="email" />
        <Input label="Nomor Telepon" type="tel" placeholder="08XXXXXXXXXX" value={form.phone} onChange={set('phone')} error={errors.phone} required autoComplete="tel" />

        <div className={styles.passwordField}>
          <Input label="Kata Sandi" type={showPw ? 'text' : 'password'} placeholder="Minimal 8 karakter" value={form.password} onChange={set('password')} error={errors.password} required autoComplete="new-password" />
          <button type="button" className={styles.showPw} onClick={() => setShowPw(!showPw)}>
            {showPw ? <EyeOff size={16} /> : <Eye size={16} />}
          </button>
        </div>

        <Input label="Konfirmasi Kata Sandi" type={showPw ? 'text' : 'password'} placeholder="Ulangi kata sandi" value={form.password_confirmation} onChange={set('password_confirmation')} error={errors.password_confirmation} required autoComplete="new-password" />

        <p className={styles.terms}>
          Dengan mendaftar, kamu setuju dengan <Link to="/syarat-ketentuan">Syarat & Ketentuan</Link> dan <Link to="/kebijakan-privasi">Kebijakan Privasi</Link> kami.
        </p>

        <Button type="submit" variant="primary" fullWidth size="lg" loading={isLoading}>Buat Akun</Button>
      </form>
    </AuthLayout>
  )
}

export function ForgotPasswordPage() {
  const [email, setEmail]   = useState('')
  const [sent, setSent]     = useState(false)
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e) => {
    e.preventDefault()
    if (!email) return
    setLoading(true)
    try {
      const { authApi } = await import('../../services/api')
      await authApi.forgotPassword(email)
      setSent(true)
    } catch (_) {
      setSent(true) // Selalu tampilkan pesan sukses (security)
    } finally {
      setLoading(false)
    }
  }

  return (
    <AuthLayout
      title="Lupa Kata Sandi?"
      subtitle={sent ? 'Cek email kamu untuk instruksi reset kata sandi.' : 'Masukkan email kamu dan kami akan mengirimkan link reset.'}
      footer={<Link to="/masuk">← Kembali ke halaman masuk</Link>}
    >
      {!sent ? (
        <form onSubmit={handleSubmit} className={styles.form}>
          <Input label="Email" type="email" placeholder="nama@email.com" value={email} onChange={e => setEmail(e.target.value)} required />
          <Button type="submit" variant="primary" fullWidth size="lg" loading={loading}>Kirim Link Reset</Button>
        </form>
      ) : (
        <div className={styles.sentState}>
          <span className={styles.sentEmoji}>📧</span>
          <p>Jika <strong>{email}</strong> terdaftar, kami akan mengirimkan instruksi reset.</p>
          <Button variant="outline" fullWidth onClick={() => setSent(false)}>Coba Email Lain</Button>
        </div>
      )}
    </AuthLayout>
  )
}

function AuthLayout({ title, subtitle, children, footer }) {
  return (
    <div className={styles.page}>
      <motion.div
        className={styles.card}
        initial={{ opacity: 0, y: 24 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.45, ease: [0.4, 0, 0.2, 1] }}
      >
        <div className={styles.logoRow}>
          <Link to="/" className={styles.logo}>
            <span>🍞</span>
            <span>Golden Hearth</span>
          </Link>
        </div>
        <h2 className={styles.title}>{title}</h2>
        {subtitle && <p className={styles.subtitle}>{subtitle}</p>}
        {children}
        {footer && <p className={styles.footer}>{footer}</p>}
      </motion.div>
    </div>
  )
}
