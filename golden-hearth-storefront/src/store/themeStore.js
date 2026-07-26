import { create } from 'zustand'
import { persist } from 'zustand/middleware'

const THEME_KEY = 'rb-theme-storage' // R Bread's theme storage key

export const useThemeStore = create(
  persist(
    (set, get) => ({
      theme: 'system', // 'light' | 'dark' | 'system'

      setTheme: (theme) => {
        if (!['light', 'dark', 'system'].includes(theme)) {
          console.warn(`Invalid theme: ${theme}`)
          return
        }
        set({ theme })
        applyTheme(theme)
      },

      initTheme: () => {
        const { theme } = get()
        applyTheme(theme)
      },

      getEffectiveTheme: () => {
        const { theme } = get()
        if (theme === 'system') {
          const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
          return prefersDark ? 'dark' : 'light'
        }
        return theme
      },
    }),
    {
      name: THEME_KEY,
      partialize: (s) => ({ theme: s.theme }),
    }
  )
)

function applyTheme(theme) {
  const root = document.documentElement
  
  if (theme === 'system') {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    const resolved = prefersDark ? 'dark' : 'light'
    root.setAttribute('data-theme', resolved)
    
    // Add transition class after initial paint to avoid flash
    if (!root.classList.contains('theme-transition')) {
      requestAnimationFrame(() => {
        root.classList.add('theme-transition')
      })
    }
  } else {
    root.setAttribute('data-theme', theme)
    
    if (!root.classList.contains('theme-transition')) {
      requestAnimationFrame(() => {
        root.classList.add('theme-transition')
      })
    }
  }
}

// Listen to system preference changes (only when theme is 'system')
if (typeof window !== 'undefined') {
  const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
  
  const handleChange = () => {
    const theme = useThemeStore.getState().theme
    if (theme === 'system') {
      applyTheme('system')
    }
  }

  // Modern API
  if (mediaQuery.addEventListener) {
    mediaQuery.addEventListener('change', handleChange)
  } 
  // Legacy API fallback
  else if (mediaQuery.addListener) {
    mediaQuery.addListener(handleChange)
  }
}
