import { Link } from 'react-router-dom'
import { motion } from 'framer-motion'
import { Heart, ShoppingCart, Star } from 'lucide-react'
import styles from './ProductCard.module.css'
import { Badge } from '../ui/Badge'
import { Button } from '../ui/Button'
import { StarRating } from '../ui/StarRating'
import { useCartStore } from '../../store/cartStore'
import { useWishlistStore } from '../../store/wishlistStore'
import { formatPrice, getImageUrl, cn } from '../../lib/utils'

function getStockBadge(stock) {
  if (stock === 0) return <Badge variant="stock-out">Habis</Badge>
  if (stock <= 5) return <Badge variant="stock-low">Stok Rendah</Badge>
  return null
}

export function ProductCard({ product }) {
  const { addItem, items } = useCartStore()
  const { toggle, isWishlisted } = useWishlistStore()
  const inCart = items.some(i => i.productId === product.id)
  const wishlisted = isWishlisted(product.id)
  const outOfStock = product.current_stock === 0

  const handleAddToCart = (e) => {
    e.preventDefault()
    if (outOfStock) return
    addItem(product, 1)
  }

  const handleWishlist = (e) => {
    e.preventDefault()
    toggle(product)
  }

  return (
    <Link to={`/produk/${product.slug}`} className={styles.card}>
      {/* Image */}
      <div className={styles.imageWrapper}>
        {product.image_path ? (
          <img
            src={getImageUrl(product.image_path)}
            alt={product.name}
            className={styles.image}
            loading="lazy"
          />
        ) : (
          <div className={styles.imagePlaceholder}>🍞</div>
        )}

        {/* Overlay Badges */}
        <div className={styles.topLeft}>
          {product.badge && <Badge variant="promo">{product.badge}</Badge>}
          {getStockBadge(product.current_stock)}
        </div>

        {/* Wishlist */}
        <button
          className={cn(styles.wishlistBtn, wishlisted && styles.wishlistActive)}
          onClick={handleWishlist}
          aria-label={wishlisted ? 'Hapus dari wishlist' : 'Tambah ke wishlist'}
        >
          <motion.div
            animate={{ scale: wishlisted ? [1, 1.3, 1] : 1 }}
            transition={{ duration: 0.4, ease: [0.34, 1.56, 0.64, 1] }}
          >
            <Heart size={16} fill={wishlisted ? 'currentColor' : 'none'} />
          </motion.div>
        </button>

        {/* Quick Add to Cart */}
        <div className={styles.quickAdd}>
          <button
            className={cn(styles.addBtn, inCart && styles.inCart)}
            onClick={handleAddToCart}
            disabled={outOfStock}
            aria-label={`Tambah ${product.name} ke keranjang`}
          >
            <ShoppingCart size={16} />
            {inCart ? 'Ditambahkan' : 'Tambah'}
          </button>
        </div>
      </div>

      {/* Info */}
      <div className={styles.info}>
        <p className={styles.category}>{product.category?.name}</p>
        <h3 className={styles.name}>{product.name}</h3>

        {product.avg_rating > 0 && (
          <StarRating
            rating={product.avg_rating}
            count={product.reviews_count}
            size={13}
          />
        )}

        <div className={styles.priceRow}>
          <span className={styles.price}>{formatPrice(product.price)}</span>
        </div>
      </div>
    </Link>
  )
}
