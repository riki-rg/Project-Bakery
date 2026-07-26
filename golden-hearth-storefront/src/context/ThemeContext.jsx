import { createContext, useContext } from 'react';
import { useTheme } from '../hooks/useTheme';

/**
 * ThemeContext - R Bread's
 * 
 * Provides theme state to all components in the app
 * Wraps the application with theme management
 */
const ThemeContext = createContext(null);

export function ThemeProvider({ children }) {
  const themeState = useTheme();

  return (
    <ThemeContext.Provider value={themeState}>
      {children}
    </ThemeContext.Provider>
  );
}

export function useThemeContext() {
  const context = useContext(ThemeContext);
  
  if (!context) {
    throw new Error('useThemeContext must be used within ThemeProvider');
  }
  
  return context;
}
