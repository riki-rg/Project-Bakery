import { forwardRef } from 'react'
import styles from './Input.module.css'
import { cn } from '../../lib/utils'

export const Input = forwardRef(function Input(
  { label, error, hint, className, id, required, ...props },
  ref
) {
  const inputId = id ?? label?.toLowerCase().replace(/\s+/g, '-')
  return (
    <div className={styles.field}>
      {label && (
        <label htmlFor={inputId} className={styles.label}>
          {label}
          {required && <span className={styles.required} aria-hidden="true">*</span>}
        </label>
      )}
      <input
        ref={ref}
        id={inputId}
        className={cn(styles.input, error && styles.inputError, className)}
        aria-invalid={!!error}
        aria-describedby={error ? `${inputId}-error` : hint ? `${inputId}-hint` : undefined}
        {...props}
      />
      {hint && !error && <p id={`${inputId}-hint`} className={styles.hint}>{hint}</p>}
      {error && <p id={`${inputId}-error`} className={styles.error} role="alert">{error}</p>}
    </div>
  )
})

export const Textarea = forwardRef(function Textarea(
  { label, error, hint, className, id, required, ...props },
  ref
) {
  const inputId = id ?? label?.toLowerCase().replace(/\s+/g, '-')
  return (
    <div className={styles.field}>
      {label && (
        <label htmlFor={inputId} className={styles.label}>
          {label}
          {required && <span className={styles.required} aria-hidden="true">*</span>}
        </label>
      )}
      <textarea
        ref={ref}
        id={inputId}
        className={cn(styles.textarea, error && styles.inputError, className)}
        aria-invalid={!!error}
        {...props}
      />
      {error && <p className={styles.error} role="alert">{error}</p>}
    </div>
  )
})

export const Select = forwardRef(function Select(
  { label, error, children, className, id, required, ...props },
  ref
) {
  const inputId = id ?? label?.toLowerCase().replace(/\s+/g, '-')
  return (
    <div className={styles.field}>
      {label && (
        <label htmlFor={inputId} className={styles.label}>
          {label}
          {required && <span className={styles.required} aria-hidden="true">*</span>}
        </label>
      )}
      <div className={styles.selectWrapper}>
        <select
          ref={ref}
          id={inputId}
          className={cn(styles.select, error && styles.inputError, className)}
          aria-invalid={!!error}
          {...props}
        >
          {children}
        </select>
        <span className={styles.chevron} aria-hidden="true">▾</span>
      </div>
      {error && <p className={styles.error} role="alert">{error}</p>}
    </div>
  )
})
