import { Sun, Moon, Monitor } from 'lucide-react';
import { useTheme } from '../hooks/useTheme';
import './ThemeSwitcher.css';

/**
 * ThemeSwitcher Component - R Bread's
 * 
 * Allows users to switch between Light, Dark, and System themes
 * Visual indicator for active theme
 * Accessible with keyboard navigation
 */
export default function ThemeSwitcher() {
  const { theme, setTheme } = useTheme();

  const themes = [
    { value: 'light', label: 'Light', icon: Sun },
    { value: 'dark', label: 'Dark', icon: Moon },
    { value: 'system', label: 'System', icon: Monitor },
  ];

  return (
    <div className="theme-switcher" role="radiogroup" aria-label="Theme selection">
      {themes.map(({ value, label, icon: Icon }) => {
        const isActive = theme === value;
        
        return (
          <button
            key={value}
            type="button"
            role="radio"
            aria-checked={isActive}
            aria-label={`${label} theme`}
            className={`theme-option ${isActive ? 'active' : ''}`}
            onClick={() => setTheme(value)}
            title={`Switch to ${label} theme`}
          >
            <Icon size={18} className="theme-icon" aria-hidden="true" />
            <span className="theme-label">{label}</span>
          </button>
        );
      })}
    </div>
  );
}
