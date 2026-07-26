import styles from './Button.module.css'
import { cn } from '../../lib/utils'

/**
 * Button Component — Golden Hearth Design System
 * variants: primary | secondary | outline | ghost | ghost-danger | icon
 * sizes: sm | md | lg
 */
export function Button({
  variant = 'primary',
  size = 'md',
  children,
  className,
  disabled,
  loading,
  fullWidth,
  as: Tag = 'button',
  ...props
}) {
  return (
    <Tag
      className={cn(
        styles.btn,
        styles[`btn-${variant}`],
        styles[`btn-${size}`],
        fullWidth && styles.fullWidth,
        (disabled || loading) && styles.disabled,
        className
      )}
      disabled={Tag === 'button' ? disabled || loading : undefined}
      aria-disabled={disabled || loading}
      {...props}
    >
      {loading && <span className={styles.spinner} aria-hidden="true" />}
      {children}
    </Tag>
  )
}
