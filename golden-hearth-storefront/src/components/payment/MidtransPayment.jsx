import { useState, useEffect, useRef } from 'react'
import { Button } from '../ui/Button'
import { paymentsApi } from '../../services/api'

const MIDTRANS_CLIENT_KEY = import.meta.env.VITE_MIDTRANS_CLIENT_KEY ?? 'SB-Mid-client-xxx'
const MIDTRANS_SANDBOX_URL = 'https://app.sandbox.midtrans.com/snap/snap.js'
const MIDTRANS_PROD_URL    = 'https://app.midtrans.com/snap/snap.js'

/**
 * Hook untuk load Midtrans Snap JS script
 */
export function useMidtransSnap() {
  const loaded = useRef(false)

  useEffect(() => {
    if (loaded.current) return
    loaded.current = true

    const isProduction = import.meta.env.VITE_MIDTRANS_IS_PRODUCTION === 'true'
    const scriptUrl    = isProduction ? MIDTRANS_PROD_URL : MIDTRANS_SANDBOX_URL

    const script = document.createElement('script')
    script.src        = scriptUrl
    script.async      = true
    script.setAttribute('data-client-key', MIDTRANS_CLIENT_KEY)

    document.body.appendChild(script)

    return () => {
      document.body.removeChild(script)
    }
  }, [])
}

/**
 * Buka Midtrans Snap popup
 */
export function openSnapPopup(snapToken, callbacks = {}) {
  if (!window.snap) {
    console.error('Midtrans Snap not loaded')
    return
  }

  window.snap.pay(snapToken, {
    onSuccess: (result) => {
      console.log('Payment success:', result)
      callbacks.onSuccess?.(result)
    },
    onPending: (result) => {
      console.log('Payment pending:', result)
      callbacks.onPending?.(result)
    },
    onError: (result) => {
      console.error('Payment error:', result)
      callbacks.onError?.(result)
    },
    onClose: () => {
      console.log('Payment popup closed')
      callbacks.onClose?.()
    },
  })
}

/**
 * Component untuk handle Midtrans payment flow
 */
export function MidtransPayment({ orderId, onPaymentCreated, onPaymentStatus, fullWidth = false }) {
  const [creating, setCreating] = useState(false)
  const [error, setError]       = useState(null)

  useMidtransSnap()

  const createPayment = async () => {
    if (!orderId) return
    setCreating(true)
    setError(null)

    try {
      const res    = await paymentsApi.create(orderId)
      const data   = res.data.data
      const token  = data.snap_token

      // Open Snap popup
      openSnapPopup(token, {
        onSuccess: (result) => {
          onPaymentCreated?.(result)
          pollPaymentStatus(orderId)
        },
        onPending: (result) => {
          onPaymentStatus?.({ status: 'pending', result })
        },
        onError: (result) => {
          setError('Pembayaran gagal. Silakan coba lagi.')
          onPaymentStatus?.({ status: 'failed', result })
        },
        onClose: () => {
          // User closed popup, check status anyway
          pollPaymentStatus(orderId)
        },
      })
    } catch (err) {
      setError(err.response?.data?.message ?? 'Gagal membuat transaksi pembayaran.')
    } finally {
      setCreating(false)
    }
  }

  const pollPaymentStatus = async (oid) => {
    // Poll status setiap 2 detik, max 30 kali
    let attempts = 0
    const maxAttempts = 30

    const poll = async () => {
      if (attempts >= maxAttempts) return
      attempts++

      try {
        const res  = await paymentsApi.getStatus(oid)
        const data = res.data.data

        if (data.payment_status === 'paid') {
          onPaymentStatus?.({ status: 'paid', data })
          return
        }

        if (data.payment_status === 'failed' || data.payment_status === 'expired') {
          onPaymentStatus?.({ status: data.payment_status, data })
          return
        }

        // Still pending, continue polling
        setTimeout(poll, 2000)
      } catch (err) {
        console.error('Poll error:', err)
      }
    }

    poll()
  }

  return (
    <div>
      <Button variant="primary" size="lg" fullWidth={fullWidth} onClick={createPayment} loading={creating} disabled={!orderId}>
        Bayar dengan Midtrans
      </Button>
      {error && <p style={{ color: 'var(--color-danger)', marginTop: 'var(--spacing-sm)', fontSize: 'var(--typo-body-sm)' }}>{error}</p>}
    </div>
  )
}
