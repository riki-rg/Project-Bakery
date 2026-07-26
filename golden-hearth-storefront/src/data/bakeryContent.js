export const bakeryStory = {
  founded: '2018',
  tagline: 'Roti artisan yang dipanggang pelan, disajikan hangat, dan dibuat untuk dinikmati bersama.',
  intro:
    'Golden Hearth lahir dari dapur kecil dengan satu oven batu dan satu starter sourdough yang dirawat setiap hari. Kami percaya roti yang baik tidak perlu terburu-buru: adonan diberi waktu, bahan dipilih dengan teliti, dan setiap batch dipanggang dalam jumlah terbatas agar kualitasnya konsisten.',
  mission:
    'Membuat roti harian terasa spesial tanpa kehilangan kehangatan bakery lingkungan: mudah diakses, jujur bahannya, dan selalu segar.',
}

export const breadProcess = [
  {
    title: 'Starter Dirawat Harian',
    description: 'Starter sourdough kami diberi makan setiap hari untuk menjaga karakter rasa, aroma, dan tekstur alami.',
    icon: '🌾',
  },
  {
    title: 'Fermentasi Lambat',
    description: 'Adonan utama difermentasi 12-24 jam agar roti lebih aromatik, mudah dicerna, dan punya crumb yang baik.',
    icon: '⏳',
  },
  {
    title: 'Dipanggang Bertahap',
    description: 'Produksi dilakukan beberapa kali sehari agar produk populer tetap segar saat pickup maupun delivery.',
    icon: '🔥',
  },
  {
    title: 'Quality Check',
    description: 'Setiap batch dicek dari warna kerak, aroma, berat, dan tekstur sebelum masuk etalase atau dikemas.',
    icon: '✅',
  },
]

export const ingredientHighlights = [
  'Tepung protein tinggi pilihan dari mill partner lokal',
  'Mentega premium untuk pastry berlapis',
  'Gula aren asli untuk minuman dan glaze tertentu',
  'Cokelat couverture untuk filling dan ganache',
  'Tanpa pengawet tambahan pada produk harian',
]

export const branches = [
  {
    id: 1,
    name: 'Golden Hearth Kemang',
    slug: 'kemang',
    address: 'Jl. Kemang Raya No. 15, Jakarta Selatan',
    hours: '07:00 - 21:00',
    phone: '021-1234567',
    whatsapp: '6281234567890',
    service: 'Pickup, Delivery, Dine-in terbatas',
    highlight: 'Cabang pertama dengan open kitchen dan aroma sourdough setiap pagi.',
  },
  {
    id: 2,
    name: 'Golden Hearth Senopati',
    slug: 'senopati',
    address: 'Jl. Senopati No. 27, Jakarta Selatan',
    hours: '07:00 - 22:00',
    phone: '021-7654321',
    whatsapp: '6281234567890',
    service: 'Pickup, Delivery, Espresso bar',
    highlight: 'Tempat favorit untuk pastry sore dan pairing kopi.',
  },
  {
    id: 3,
    name: 'Golden Hearth BSD',
    slug: 'bsd',
    address: 'BSD City, Jl. Pahlawan Seribu, Tangerang Selatan',
    hours: '08:00 - 20:00',
    phone: '021-1122334',
    whatsapp: '6281234567890',
    service: 'Pickup dan Delivery keluarga',
    highlight: 'Cabang dengan kapasitas pre-order cake dan hampers lebih besar.',
  },
]

export const faqItems = [
  {
    question: 'Apakah semua roti dipanggang hari yang sama?',
    answer: 'Ya. Produk harian dipanggang pada hari yang sama. Produk yang tidak habis tidak dijual ulang sebagai produk segar keesokan harinya.',
  },
  {
    question: 'Apakah bisa pesan untuk pickup?',
    answer: 'Bisa. Pilih metode pickup saat checkout, lalu pilih cabang dan slot pengambilan yang tersedia.',
  },
  {
    question: 'Apakah produk mengandung alergen?',
    answer: 'Beberapa produk mengandung gluten, susu, telur, kacang, atau almond. Informasi alergen akan ditampilkan di detail produk bila tersedia.',
  },
]

export const fallbackPromotions = [
  {
    id: 'fallback-1',
    code: 'PERTAMA',
    name: 'Diskon Pelanggan Baru',
    description: 'Diskon 10% untuk pesanan pertama kamu, maksimal Rp 15.000.',
    type: 'percentage',
    min_spend: 50000,
    ends_at: null,
  },
  {
    id: 'fallback-2',
    code: 'HEMAT50K',
    name: 'Hemat 50 Ribu',
    description: 'Potongan Rp 50.000 untuk minimum pembelian Rp 200.000.',
    type: 'fixed',
    min_spend: 200000,
    ends_at: null,
  },
  {
    id: 'fallback-3',
    code: 'GRATISONGKIR',
    name: 'Gratis Ongkir',
    description: 'Bebas biaya pengiriman untuk order di atas Rp 100.000.',
    type: 'shipping',
    min_spend: 100000,
    ends_at: null,
  },
]
