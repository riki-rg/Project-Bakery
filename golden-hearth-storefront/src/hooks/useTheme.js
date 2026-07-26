import { useEffect, useState } from 'react';

/**
 * useTheme Hook - R Bread's Theme Management
 * 
 * Manages Light, Dark, and System theme preferences
 * Persists user selection to localStorage
 * 
 * @returns {Object} { theme, setTheme, effectiveTheme }
 */
export function useTheme() {
  // Get initial theme from localStorage or default to 'system'
  const getInitialTheme = () => {
    if (typeof window === 'undefined') return 'system';
    
    const stored = localStorage.getItem('rb-theme');
    if (stored && ['light', 'dark', 'system'].includes(stored)) {
      return stored;
    }
    return 'system';
  };

  const [theme, setThemeState] = useState(getInitialTheme);
  const [effectiveTheme, setEffectiveTheme] = useState('light');

  // Resolve effective theme (handles 'system' preference)
  const resolveEffectiveTheme = (selectedTheme) => {
    if (selectedTheme !== 'system') {
      return selectedTheme;
    }

    // Check system preference
    if (typeof window !== 'undefined' && window.matchMedia) {
      return window.matchMedia('(prefers-color-scheme: dark)').matches
        ? 'dark'
        : 'light';
    }

    return 'light';
  };

  // Update theme
  const setTheme = (newTheme) => {
    if (!['light', 'dark', 'system'].includes(newTheme)) {
      console.warn(`Invalid theme: ${newTheme}. Must be 'light', 'dark', or 'system'.`);
      return;
    }

    setThemeState(newTheme);
    localStorage.setItem('rb-theme', newTheme);
    
    const effective = resolveEffectiveTheme(newTheme);
    setEffectiveTheme(effective);
    document.documentElement.setAttribute('data-theme', effective);
  };

  // Initialize theme on mount
  useEffect(() => {
    const effective = resolveEffectiveTheme(theme);
    setEffectiveTheme(effective);
    document.documentElement.setAttribute('data-theme', effective);
  }, []);

  // Listen to system theme changes (only if user selected 'system')
  useEffect(() => {
    if (theme !== 'system') return;

    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    const handleChange = (e) => {
      const newEffective = e.matches ? 'dark' : 'light';
      setEffectiveTheme(newEffective);
      document.documentElement.setAttribute('data-theme', newEffective);
    };

    // Modern API
    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener('change', handleChange);
      return () => mediaQuery.removeEventListener('change', handleChange);
    }
    // Legacy API
    else {
      mediaQuery.addListener(handleChange);
      return () => mediaQuery.removeListener(handleChange);
    }
  }, [theme]);

  return {
    theme,           // 'light' | 'dark' | 'system'
    setTheme,        // Function to change theme
    effectiveTheme,  // 'light' | 'dark' (resolved)
  };
}
