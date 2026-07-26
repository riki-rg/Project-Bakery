import { Star } from 'lucide-react'
import styles from './StarRating.module.css'

export function StarRating({ rating = 0, count, size = 16, showNumber = true }) {
  const stars = Array.from({ length: 5 }, (_, i) => i + 1)

  return (
    <div className={styles.wrapper} role="img" aria-label={`Rating ${rating} dari 5`}>
      <div className={styles.stars}>
        {stars.map((s) => (
          <Star
            key={s}
            size={size}
            className={s <= Math.round(rating) ? styles.starFilled : styles.starEmpty}
            fill={s <= Math.round(rating) ? 'currentColor' : 'none'}
          />
        ))}
      </div>
      {showNumber && (
        <span className={styles.number}>
          {rating.toFixed(1)}
          {count != null && <span className={styles.count}> ({count})</span>}
        </span>
      )}
    </div>
  )
}
