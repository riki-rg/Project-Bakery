import { create } from 'zustand'
import { persist } from 'zustand/middleware'

export const useWishlistStore = create(
  persist(
    (set, get) => ({
      items: [], // [{ productId, name, price, image }]

      toggle: (product) => {
        const { items } = get()
        const exists = items.find(i => i.productId === product.id)
        if (exists) {
          set({ items: items.filter(i => i.productId !== product.id) })
        } else {
          set({
            items: [
              ...items,
              {
                productId: product.id,
                name: product.name,
                price: parseFloat(product.price),
                image: product.image_path,
                slug: product.slug,
              },
            ],
          })
        }
      },

      isWishlisted: (productId) => {
        return get().items.some(i => i.productId === productId)
      },

      remove: (productId) => {
        set({ items: get().items.filter(i => i.productId !== productId) })
      },

      clear: () => set({ items: [] }),
    }),
    {
      name: 'gh-wishlist',
      partialize: (s) => ({ items: s.items }),
    }
  )
)
