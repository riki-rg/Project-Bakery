import { Link } from 'react-router-dom'
import { motion } from 'framer-motion'
import { ArrowRight, Heart, Leaf, ShieldCheck, Clock } from 'lucide-react'
import { Button } from '../../components/ui/Button'
import { bakeryStory, breadProcess, ingredientHighlights } from '../../data/bakeryContent'
import styles from './AboutPage.module.css'

const values = [
  { icon: Clock, title: 'Tidak Terburu-buru', text: 'Roti yang baik membutuhkan waktu. Fermentasi lambat adalah bagian dari rasa kami.' },
  { icon: Leaf, title: 'Bahan Jelas', text: 'Kami memilih bahan yang bisa dijelaskan, bukan daftar panjang aditif yang membingungkan.' },
  { icon: Heart, title: 'Dibuat Harian', text: 'Produksi kecil dan rutin menjaga kualitas, aroma, dan kesegaran.' },
  { icon: ShieldCheck, title: 'Konsisten', text: 'Setiap batch dicek sebelum masuk etalase, delivery, atau pickup.' },
]

export default function AboutPage() {
  return (
    <div className={styles.page}>
      <section className={styles.hero}>
        <div className={`${styles.heroInner} container`}>
          <motion.div initial={{ opacity: 0, y: 24 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.5 }}>
            <p className={styles.eyebrow}>Sejak {bakeryStory.founded}</p>
            <h1>Roti artisan yang dibuat pelan untuk hari yang lebih hangat.</h1>
            <p className={styles.lead}>{bakeryStory.tagline}</p>
            <div className={styles.actions}>
              <Button as={Link} to="/katalog" variant="primary" size="lg">Lihat Produk <ArrowRight size={18} /></Button>
              <Button as={Link} to="/lokasi" variant="outline" size="lg">Kunjungi Cabang</Button>
            </div>
          </motion.div>
          <motion.div className={styles.heroCard} initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} transition={{ duration: 0.5, delay: 0.1 }}>
            <span className={styles.heroEmoji}>🥖</span>
            <p>Open kitchen, starter hidup, oven hangat, dan roti yang keluar bertahap sepanjang hari.</p>
          </motion.div>
        </div>
      </section>

      <section className="section">
        <div className={`${styles.storyGrid} container`}>
          <div>
            <p className={styles.eyebrow}>Cerita Kami</p>
            <h2>Dari dapur kecil ke bakery lingkungan.</h2>
          </div>
          <div className={styles.storyText}>
            <p>{bakeryStory.intro}</p>
            <p>{bakeryStory.mission}</p>
          </div>
        </div>
      </section>

      <section className={styles.altSection}>
        <div className="container">
          <div className={styles.sectionHeader}>
            <p className={styles.eyebrow}>Proses</p>
            <h2>Bagaimana roti kami dibuat</h2>
          </div>
          <div className={styles.processGrid}>
            {breadProcess.map((item, index) => (
              <motion.div key={item.title} className={styles.processCard} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: index * 0.06 }}>
                <span>{item.icon}</span>
                <h3>{item.title}</h3>
                <p>{item.description}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section">
        <div className={`${styles.valuesGrid} container`}>
          {values.map(({ icon: Icon, title, text }) => (
            <div key={title} className={styles.valueCard}>
              <Icon size={24} />
              <h3>{title}</h3>
              <p>{text}</p>
            </div>
          ))}
        </div>
      </section>

      <section className={styles.ingredientsSection}>
        <div className={`${styles.ingredientsInner} container`}>
          <div>
            <p className={styles.eyebrow}>Bahan</p>
            <h2>Yang masuk ke adonan kami.</h2>
            <p className={styles.muted}>Kami tidak mengejar daftar rasa yang terlalu ramai. Fokus kami: bahan yang tepat, teknik yang konsisten, dan roti yang enak dimakan hari itu.</p>
          </div>
          <ul className={styles.ingredientsList}>
            {ingredientHighlights.map((item) => <li key={item}>{item}</li>)}
          </ul>
        </div>
      </section>
    </div>
  )
}
