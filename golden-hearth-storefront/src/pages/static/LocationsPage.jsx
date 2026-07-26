import { MapPin, Clock, Phone, MessageCircle, Navigation } from 'lucide-react'
import { Button } from '../../components/ui/Button'
import { branches, faqItems } from '../../data/bakeryContent'
import styles from './LocationsPage.module.css'

export default function LocationsPage() {
  return (
    <div className={styles.page}>
      <section className={styles.hero}>
        <div className="container">
          <p className={styles.eyebrow}>Lokasi Cabang</p>
          <h1>Ambil roti hangat di cabang terdekat, atau pesan untuk diantar.</h1>
          <p>Setiap cabang punya jadwal produksi dan stok harian. Pilih cabang yang paling dekat untuk pickup atau delivery lebih cepat.</p>
        </div>
      </section>

      <section className="section">
        <div className={`${styles.branchGrid} container`}>
          {branches.map((branch) => {
            const wa = `https://wa.me/${branch.whatsapp}?text=${encodeURIComponent(`Halo Golden Hearth, saya ingin bertanya tentang cabang ${branch.name}`)}`
            const maps = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(branch.address)}`
            return (
              <article key={branch.id} className={styles.branchCard}>
                <div className={styles.mapPreview}><MapPin size={42} /></div>
                <div className={styles.branchBody}>
                  <h2>{branch.name}</h2>
                  <p className={styles.highlight}>{branch.highlight}</p>
                  <div className={styles.infoList}>
                    <p><MapPin size={16} /> {branch.address}</p>
                    <p><Clock size={16} /> {branch.hours}</p>
                    <p><Phone size={16} /> {branch.phone}</p>
                    <p><Navigation size={16} /> {branch.service}</p>
                  </div>
                  <div className={styles.actions}>
                    <Button as="a" href={maps} target="_blank" rel="noreferrer" variant="outline">Buka Maps</Button>
                    <Button as="a" href={wa} target="_blank" rel="noreferrer" variant="primary"><MessageCircle size={16} /> WhatsApp</Button>
                  </div>
                </div>
              </article>
            )
          })}
        </div>
      </section>

      <section className={styles.faqSection}>
        <div className="container">
          <div className={styles.sectionHeader}>
            <p className={styles.eyebrow}>Pertanyaan Umum</p>
            <h2>Sebelum datang ke cabang</h2>
          </div>
          <div className={styles.faqGrid}>
            {faqItems.map((item) => (
              <div key={item.question} className={styles.faqCard}>
                <h3>{item.question}</h3>
                <p>{item.answer}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  )
}
