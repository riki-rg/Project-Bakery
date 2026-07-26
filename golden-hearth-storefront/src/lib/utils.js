/**
 * Format harga ke format Rupiah
 * @param {number} amount
 * @returns {string} e.g. "Rp 25.000"
 */
export function formatPrice(amount) {
  if (amount == null) return 'Rp -'
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount)
}

/**
 * Format tanggal ke format Indonesia
 */
export function formatDate(dateStr, options = {}) {
  if (!dateStr) return '-'
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    ...options,
  }).format(new Date(dateStr))
}

/**
 * Format tanggal + waktu
 */
export function formatDateTime(dateStr) {
  if (!dateStr) return '-'
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(new Date(dateStr))
}

/**
 * Truncate text dengan ellipsis
 */
export function truncate(str, maxLength = 100) {
  if (!str) return ''
  return str.length > maxLength ? str.slice(0, maxLength) + '…' : str
}

/**
 * Generate image URL dari Laravel storage
 */
export function getImageUrl(path) {
  if (!path) return null
  if (path.startsWith('http')) return path
  const base = import.meta.env.VITE_STORAGE_URL ?? 'http://localhost:8000/storage'
  return `${base}/${path}`
}

/**
 * Debounce function
 */
export function debounce(fn, delay = 300) {
  let timer
  return (...args) => {
    clearTimeout(timer)
    timer = setTimeout(() => fn(...args), delay)
  }
}

/**
 * Class names utility (cn)
 */
export function cn(...classes) {
  return classes.filter(Boolean).join(' ')
}

/**
 * Slug ke judul yang terbaca
 */
export function slugToTitle(slug) {
  return slug
    ?.split('-')
    .map(w => w.charAt(0).toUpperCase() + w.slice(1))
    .join(' ') ?? ''
}
