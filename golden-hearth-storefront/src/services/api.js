import axios from 'axios'
import { useAuthStore } from '../store/authStore'

const BASE_URL = import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api/v1'

const api = axios.create({
  baseURL: BASE_URL,
  headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
  withCredentials: true,
})

// Attach token
api.interceptors.request.use((config) => {
  const token = useAuthStore.getState().token
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

// Handle 401
api.interceptors.response.use(
  (res) => res,
  (err) => {
    if (err.response?.status === 401) {
      useAuthStore.getState().logout()
    }
    return Promise.reject(err)
  }
)

// ── Auth ─────────────────────────────────────────────────────
export const authApi = {
  login: (data) => api.post('/auth/login', data),
  register: (data) => api.post('/auth/register', data),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
  forgotPassword: (email) => api.post('/auth/forgot-password', { email }),
  resetPassword: (data) => api.post('/auth/reset-password', data),
}

// ── Products (public) ─────────────────────────────────────────
export const productsApi = {
  list: (params) => api.get('/products', { params }),
  detail: (id) => api.get(`/products/${id}`),
  categories: () => api.get('/categories'),
  reviews: (productId, params) => api.get(`/products/${productId}/reviews`, { params }),
  promotions: () => api.get('/promotions'),
}

// ── Branches ──────────────────────────────────────────────────
export const branchesApi = {
  list: () => api.get('/branches'),
  detail: (id) => api.get(`/branches/${id}`),
}

// ── Customer ──────────────────────────────────────────────────
export const customerApi = {
  getProfile: () => api.get('/customer/profile'),
  updateProfile: (data) => api.put('/customer/profile', data),
  getAddresses: () => api.get('/customer/addresses'),
  createAddress: (data) => api.post('/customer/addresses', data),
  updateAddress: (id, data) => api.put(`/customer/addresses/${id}`, data),
  deleteAddress: (id) => api.delete(`/customer/addresses/${id}`),
  setDefaultAddress: (id) => api.patch(`/customer/addresses/${id}/default`),
  getWishlist: () => api.get('/customer/wishlist'),
  toggleWishlist: (productId) => api.post('/customer/wishlist/toggle', { product_id: productId }),
  getNotifications: () => api.get('/customer/notifications'),
  markNotificationsRead: () => api.patch('/customer/notifications/read-all'),
}

// ── Cart ──────────────────────────────────────────────────────
export const cartApi = {
  index: () => api.get('/cart'),
  get: () => api.get('/cart'),
  addItem: (data) => api.post('/cart/items', data),
  updateItem: (id, data) => api.put(`/cart/items/${id}`, data),
  removeItem: (id) => api.delete(`/cart/items/${id}`),
  clear: () => api.delete('/cart'),
  validate: (data) => api.post('/cart/validate', data),
  applyVoucher: (code) => api.post('/cart/voucher', { code }),
}

// ── Checkout ──────────────────────────────────────────────────
export const checkoutApi = {
  checkServiceability: (data) => api.post('/checkout/serviceability', data),
  getSlots: (params) => api.get('/checkout/slots', { params }),
  getDeliveryQuote: (data) => api.post('/checkout/delivery-quote', data),
  validatePromo: (data) => api.post('/checkout/promo-validate', data),
  preview: (data) => api.post('/checkout/preview', data),
}

// ── Orders ────────────────────────────────────────────────────
export const ordersApi = {
  create: (data) => api.post('/orders', data),
  list: (params) => api.get('/orders', { params }),
  detail: (id) => api.get(`/orders/${id}`),
  cancel: (id, reason) => api.post(`/orders/${id}/cancel`, { reason }),
  reorder: (id) => api.post(`/orders/${id}/reorder`),
  guestLookup: (orderNumber, email) => api.post('/orders/guest-lookup', { order_number: orderNumber, email }),
}

// ── Payments ──────────────────────────────────────────────────
export const paymentsApi = {
  create: (orderId) => api.post(`/payments/${orderId}/create`),
  getStatus: (orderId) => api.get(`/payments/${orderId}/status`),
}

// ── Reviews ───────────────────────────────────────────────────
export const reviewsApi = {
  eligibleItems: () => api.get('/reviews/eligible'),
  create: (data) => api.post('/reviews', data),
  update: (id, data) => api.put(`/reviews/${id}`, data),
  delete: (id) => api.delete(`/reviews/${id}`),
}

export default api
