import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import { authApi } from '../services/api'

export const useAuthStore = create(
  persist(
    (set, get) => ({
      user: null,
      token: null,
      isLoading: false,
      error: null,

      get isAuthenticated() {
        return !!get().token && !!get().user
      },

      login: async (email, password) => {
        set({ isLoading: true, error: null })
        try {
          const res = await authApi.login({ email, password })
          const { user, token } = res.data.data
          set({ user, token, isLoading: false })
          return { success: true }
        } catch (err) {
          const msg = err.response?.data?.message
            ?? err.response?.data?.errors?.email?.[0]
            ?? 'Login gagal. Periksa email dan kata sandi.'
          set({ error: msg, isLoading: false })
          return { success: false, message: msg }
        }
      },

      register: async (data) => {
        set({ isLoading: true, error: null })
        try {
          const res = await authApi.register(data)
          const { user, token } = res.data.data
          set({ user, token, isLoading: false })
          return { success: true }
        } catch (err) {
          const msg    = err.response?.data?.message ?? 'Registrasi gagal.'
          const errors = err.response?.data?.errors
          set({ error: msg, isLoading: false })
          return { success: false, message: msg, errors }
        }
      },

      logout: async () => {
        try { await authApi.logout() } catch (_) {}
        set({ user: null, token: null, error: null })
      },

      refreshUser: async () => {
        try {
          const res = await authApi.me()
          set({ user: res.data.data })
        } catch (_) {
          set({ user: null, token: null })
        }
      },

      setUser: (user) => set({ user }),
      clearError: () => set({ error: null }),
    }),
    {
      name: 'gh-auth',
      partialize: s => ({ user: s.user, token: s.token }),
    }
  )
)
