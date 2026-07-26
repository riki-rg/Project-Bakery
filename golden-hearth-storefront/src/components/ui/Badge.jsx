import styles from './Badge.module.css'
import { cn } from '../../lib/utils'

/**
 * Badge — stock-available | stock-low | stock-out | promo | info | success | warning | danger
 */
export function Badge({ variant = 'info', children, className, ...props }) {
  return (
    <span className={cn(styles.badge, styles[`badge-${variant}`], className)} {...props}>
      {children}
    </span>
  )
}

/**
 * Chip — untuk filter
 */
export function Chip({ active, children, className, onClick, ...props }) {
  return (
    <button
      className={cn(styles.chip, active && styles.chipActive, className)}
      onClick={onClick}
      type="button"
      {...props}
    >
      {children}
    </button>
  )
}
