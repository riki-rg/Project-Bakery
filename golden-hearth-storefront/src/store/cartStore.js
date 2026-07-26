import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import { cartApi } from '../services/api'
import { useAuthStore } from './authStore'

const CART_EXPIRY_HOURS = 72

export const useCartStore = create(
  persist(
    (set, get) => ({
      items: [],
      branchId: null,
      voucher: null,
      voucherDiscount: 0,
      expiresAt: null,
      syncing: false,
      syncedCartId: null,

      addItem: async (product, quantity = 1, variantId = null) => {
        const key = `${product.id}-${variantId ?? 'none'}`
        const maxQty = product.current_stock ?? 999
        const existing = get().items.find(i => i.key === key)

        if (existing) {
          const newQty = Math.min(existing.quantity + quantity, maxQty)
          set({ items: get().items.map(i => i.key === key ? { ...i, quantity: newQty } : i) })
        } else {
          set({
            items: [
              ...get().items,
              {
                key,
                productId: product.id,
                variantId,
                name: product.name,
                price: parseFloat(product.price),
                image: product.image_path,
                quantity: Math.min(quantity, maxQty),
                maxStock: maxQty,
                slug: product.slug,
              },
            ],
            expiresAt: getExpiry(),
          })
        }

        if (useAuthStore.getState().isAuthenticated) {
          try {
            await cartApi.addItem({ product_id: product.id, quantity })
          } catch (err) {
            console.error('Add cart item error:', err)
          }
        }
      },

      updateQuantity: async (key, quantity) => {
        const { items } = get()
        if (quantity <= 0) {
          set({ items: items.filter(i => i.key !== key) })
        } else {
          set({
            items: items.map(i =>
              i.key === key ? { ...i, quantity: Math.min(quantity, i.maxStock) } : i
            ),
          })
        }
        if (useAuthStore.getState().isAuthenticated) {
          try {
            await cartApi.updateItem(key, { quantity: Math.max(quantity, 0) })
          } catch (err) {
            console.error('Update cart item error:', err)
          }
        }
      },

      removeItem: async (key) => {
        set({ items: get().items.filter(i => i.key !== key) })
        if (useAuthStore.getState().isAuthenticated) {
          try {
            await cartApi.removeItem(key)
          } catch (err) {
            console.error('Remove cart item error:', err)
          }
        }
      },

      clearCart: async () => {
        set({ items: [], voucher: null, voucherDiscount: 0, expiresAt: null, syncedCartId: null })
        if (useAuthStore.getState().isAuthenticated) {
          try {
            await cartApi.clear()
          } catch (err) {
            console.error('Clear cart error:', err)
          }
        }
      },

      setBranch: (branchId) => set({ branchId }),

      applyVoucher: (voucher, discount) => set({ voucher, voucherDiscount: discount }),
      removeVoucher: () => set({ voucher: null, voucherDiscount: 0 }),

      syncToBackend: async () => {
        const { items } = get()
        const isAuthenticated = useAuthStore.getState().isAuthenticated
        
        if (!isAuthenticated || get().syncing) return

        set({ syncing: true })
        try {
          if (items.length === 0) {
            await cartApi.clear()
            set({ syncedCartId: null })
          } else {
            for (const item of items) {
              await cartApi.addItem({ product_id: item.productId, quantity: item.quantity })
            }
            const res = await cartApi.index()
            const serverCart = res.data?.data
            set({ syncedCartId: serverCart?.cart?.id })
          }
        } catch (err) {
          console.error('Cart sync error:', err)
        } finally {
          set({ syncing: false })
        }
      },

      loadFromBackend: async () => {
        const isAuthenticated = useAuthStore.getState().isAuthenticated
        if (!isAuthenticated) return

        try {
          const res  = await cartApi.index()
          const data = res.data?.data
          const cart = data?.cart
          const items = data?.items ?? []

          set({
            syncedCartId: cart?.id,
            items: items.map(i => ({
              key: `${i.product_id}-none`,
              productId: i.product_id,
              name: i.product?.name,
              price: parseFloat(i.price_snapshot),
              image: i.product?.image_path,
              quantity: i.quantity,
              maxStock: i.product?.current_stock ?? 999,
              slug: i.product?.slug,
            })),
          })
        } catch (err) {
          console.error('Load cart error:', err)
        }
      },

      get itemCount() {
        return get().items.reduce((sum, i) => sum + i.quantity, 0)
      },

      get subtotal() {
        return get().items.reduce((sum, i) => sum + i.price * i.quantity, 0)
      },
    }),
    {
      name: 'gh-cart',
      partialize: (s) => ({
        items: s.items,
        branchId: s.branchId,
        voucher: s.voucher,
        voucherDiscount: s.voucherDiscount,
        expiresAt: s.expiresAt,
      }),
    }
  )
)

function getExpiry() {
  const d = new Date()
  d.setHours(d.getHours() + CART_EXPIRY_HOURS)
  return d.toISOString()
}
