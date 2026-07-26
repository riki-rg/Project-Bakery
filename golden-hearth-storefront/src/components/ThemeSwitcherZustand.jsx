/* ============================================================
   ThemeSwitcher Component (Standalone)
   R Bread's - Alternative standalone implementation
   ============================================================ */

import { Sun, Moon, Monitor } from 'lucide-react';
import { useThemeStore } from '../../store/themeStore';
import './ThemeSwitcher.css';

/**
 * ThemeSwitcher Component - Zustand Store Version
 * 
 * Uses existing useThemeStore for compatibility with current app
 * Can be used in Navbar or standalone
 */
export function ThemeSwitcherZustand({ variant = 'default' }) {
  const { theme, setTheme } = useThemeStore();

  const themes = [
    { value: 'light', label: 'Terang', icon: Sun },
    { value: 'dark', label: 'Gelap', icon: Moon },
    { value: 'system', label: 'Sistem', icon: Monitor },
  ];

  return (
    <div 
      className={`theme-switcher ${variant === 'compact' ? 'compact' : ''}`}
      role="radiogroup" 
      aria-label="Pilihan tema"
    >
      {themes.map(({ value, label, icon: Icon }) => {
        const isActive = theme === value;
        
        return (
          <button
            key={value}
            type="button"
            role="radio"
            aria-checked={isActive}
            aria-label={`Tema ${label}`}
            className={`theme-option ${isActive ? 'active' : ''}`}
            onClick={() => setTheme(value)}
            title={`Ganti ke tema ${label}`}
          >
            <Icon size={18} className="theme-icon" aria-hidden="true" />
            <span className="theme-label">{label}</span>
          </button>
        );
      })}
    </div>
  );
}
