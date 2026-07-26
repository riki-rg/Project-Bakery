import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './styles/global.css'
import App from './App.jsx'

// Apply theme before first paint to prevent flash - R Bread's
;(function () {
  try {
    const stored = localStorage.getItem('rb-theme-storage')
    const theme = stored ? JSON.parse(stored).state.theme : 'system'
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    const resolved = theme === 'system' ? (prefersDark ? 'dark' : 'light') : theme
    document.documentElement.setAttribute('data-theme', resolved)
  } catch (_) {
    // Fallback to system preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light')
  }
})()

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
