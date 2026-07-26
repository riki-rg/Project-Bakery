# 🥖 Golden Hearth - Artisan Bakery Storefront

> Modern e-commerce platform for artisan bakery products, built with React 18, featuring Google Maps integration, real-time cart management, and seamless checkout experience.

[![React](https://img.shields.io/badge/React-18.x-61DAFB?style=flat&logo=react)](https://reactjs.org)
[![Vite](https://img.shields.io/badge/Vite-5.x-646CFF?style=flat&logo=vite)](https://vitejs.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.x-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Google Maps Setup](#-google-maps-setup)
- [Running the Application](#-running-the-application)
- [Project Structure](#-project-structure)
- [Design System](#-design-system)
- [State Management](#-state-management)
- [API Integration](#-api-integration)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 🛒 E-Commerce Core
- **Product Catalog** with search, filter, and sort
- **Shopping Cart** with real-time updates
- **Checkout Flow** with multiple payment methods (Midtrans, COD)
- **Order Tracking** with status updates
- **Wishlist** functionality

### 🗺️ Google Maps Integration
- **Interactive Address Picker** with draggable pin
- **Address Search** with Google Places Autocomplete
- **Current Location** GPS detection
- **Reverse Geocoding** for auto-fill forms
- **Coordinates Storage** for accurate delivery

### 👤 User Account Management
- **Authentication** (Login/Register/Logout)
- **Profile Management** (name, email, phone)
- **Address Book** (add/edit/delete/set default)
- **Order History** with detailed views
- **Wishlist** management

### 🎨 Modern UI/UX
- **R Bread's Brand Identity** (custom color palette)
- **Light/Dark Theme** with system auto-detect
- **Responsive Design** (mobile-first approach)
- **Smooth Animations** with Framer Motion
- **Toast Notifications** for user feedback

### 🔐 Security & Performance
- **Persistent Authentication** with Zustand
- **Token-based API** authentication
- **Optimized Images** and lazy loading
- **Code Splitting** for faster loads
- **SEO Optimized** with React Helmet

---

## 🛠 Tech Stack

**Frontend Framework:**
- [React 18](https://react.dev) - UI Library
- [Vite 5](https://vitejs.dev) - Build Tool
- [React Router 6](https://reactrouter.com) - Routing

**UI & Styling:**
- [Tailwind CSS 4](https://tailwindcss.com) - Utility-first CSS
- [Framer Motion](https://www.framer.com/motion/) - Animations
- [Lucide React](https://lucide.dev) - Icon library

**State Management:**
- [Zustand](https://zustand-demo.pmnd.rs/) - Lightweight state manager
- React Context API - Theme management

**Maps & Location:**
- [@react-google-maps/api](https://react-google-maps-api-docs.netlify.app/) - Google Maps integration

**Additional Libraries:**
- [React Hot Toast](https://react-hot-toast.com/) - Notifications
- [React Helmet Async](https://github.com/staylor/react-helmet-async) - Meta tags
- [Axios](https://axios-http.com/) - HTTP client

---

## 📋 Requirements

- **Node.js:** >= 18.x
- **NPM:** >= 9.x
- **Backend API:** Laravel backend (see [bakery-system](../bakery-system))
- **Google Maps API Key:** For address picker feature

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/riki-rg/Project-Bakery.git
cd Project-Bakery
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Environment Setup

```bash
# Copy environment template
cp .env.example .env.local
```

---

## ⚙️ Configuration

### Edit `.env.local`:

```env
# Backend API URL
VITE_API_URL=http://localhost:8000/api/v1

# Google Maps API Key
VITE_GOOGLE_MAPS_API_KEY=YOUR_GOOGLE_MAPS_API_KEY_HERE
```

**Important:** 
- Replace `YOUR_GOOGLE_MAPS_API_KEY_HERE` with your actual Google Maps API key
- See [Google Maps Setup](#-google-maps-setup) for detailed instructions

---

## 🗺️ Google Maps Setup

### Step 1: Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Project name: **Golden Hearth Bakery** (or your choice)

### Step 2: Enable Required APIs

Enable these 3 APIs:
- **Maps JavaScript API**
- **Places API**
- **Geocoding API**

Go to: https://console.cloud.google.com/google/maps-apis

### Step 3: Create API Key

1. Go to: https://console.cloud.google.com/apis/credentials
2. Click **+ CREATE CREDENTIALS** → **API key**
3. Copy the API key

### Step 4: Restrict API Key (Security)

1. Click on the API key name
2. **Application restrictions:**
   - Select: **HTTP referrers (web sites)**
   - Add referrers:
     ```
     http://localhost:5173/*
     http://localhost:*
     https://yourdomain.com/*
     ```
3. **API restrictions:**
   - Select: **Restrict key**
   - Choose:
     - Maps JavaScript API ✓
     - Places API ✓
     - Geocoding API ✓
4. Click **SAVE**

### Step 5: Add to Project

Edit `.env.local`:
```env
VITE_GOOGLE_MAPS_API_KEY=AIzaSyB...YOUR_ACTUAL_KEY
```

**📖 Full Guide:** See [GOOGLE_MAPS_SETUP_GUIDE.md](../GOOGLE_MAPS_SETUP_GUIDE.md)

---

## 🏃 Running the Application

### Development Mode

```bash
# Start dev server
npm run dev

# Server starts at: http://localhost:5173
```

### Production Build

```bash
# Build for production
npm run build

# Preview production build
npm run preview
```

### Code Quality

```bash
# Lint code
npm run lint

# Format code
npm run format
```

---

## 📁 Project Structure

```
golden-hearth-storefront/
├── public/
│   ├── favicon.svg
│   └── images/
├── src/
│   ├── components/
│   │   ├── layout/
│   │   │   ├── Navbar.jsx
│   │   │   ├── Footer.jsx
│   │   │   └── RootLayout.jsx
│   │   ├── maps/
│   │   │   ├── AddressMapPicker.jsx
│   │   │   └── AddressMapPicker.module.css
│   │   ├── payment/
│   │   │   └── MidtransPayment.jsx
│   │   ├── sections/
│   │   │   └── ProductCard.jsx
│   │   └── ui/
│   │       ├── Button.jsx
│   │       ├── Input.jsx
│   │       ├── Modal.jsx
│   │       └── Badge.jsx
│   ├── pages/
│   │   ├── HomePage.jsx
│   │   ├── CatalogPage.jsx
│   │   ├── ProductDetailPage.jsx
│   │   ├── CartPage.jsx
│   │   ├── CheckoutPage.jsx
│   │   ├── OrderConfirmPage.jsx
│   │   ├── account/
│   │   │   ├── AccountLayout.jsx
│   │   │   └── AccountPages.jsx (Profile, Orders, Addresses, Wishlist)
│   │   ├── auth/
│   │   │   └── AuthPage.jsx (Login, Register, Forgot Password)
│   │   └── static/
│   │       ├── AboutPage.jsx
│   │       ├── LocationsPage.jsx
│   │       └── PromoPage.jsx
│   ├── store/
│   │   ├── authStore.js (Zustand - Auth state)
│   │   ├── cartStore.js (Zustand - Cart state)
│   │   ├── wishlistStore.js (Zustand - Wishlist)
│   │   └── themeStore.js (Zustand - Theme)
│   ├── services/
│   │   └── api.js (Axios instance & API calls)
│   ├── lib/
│   │   └── utils.js (Helper functions)
│   ├── styles/
│   │   ├── tokens.css (Design tokens)
│   │   └── global.css (Global styles)
│   ├── data/
│   │   └── bakeryContent.js (Static content)
│   ├── App.jsx
│   ├── main.jsx
│   └── index.css
├── .env.example
├── .env.local (create this)
├── package.json
├── vite.config.js
├── tailwind.config.js
└── README.md
```

---

## 🎨 Design System

### Brand Colors (R Bread's)

```css
/* Primary - Caramel */
--rb-primary: #B95324;
--rb-primary-hover: #98401C;
--rb-primary-soft: #F9E2D2;

/* Accent - Honey Gold */
--rb-accent: #D99424;
--rb-accent-soft: #FFF0C7;

/* Semantic */
--rb-success: #347552;
--rb-warning: #94620F;
--rb-danger: #A33F3A;
--rb-info: #3E7080;
```

### Typography

```css
/* Headings */
--typo-display-lg: clamp(2rem, 5vw + 1rem, 3.5rem);
--typo-title-lg: clamp(1.25rem, 1.5vw + 0.5rem, 1.5rem);
--typo-body-md: 1rem;
--typo-label: 0.75rem;
```

### Spacing

```css
--spacing-xs: 0.5rem;    /* 8px */
--spacing-sm: 0.75rem;   /* 12px */
--spacing-md: 1rem;      /* 16px */
--spacing-lg: 1.5rem;    /* 24px */
--spacing-xl: 2rem;      /* 32px */
--spacing-2xl: 3rem;     /* 48px */
```

---

## 🔄 State Management

### Zustand Stores

**Auth Store** (`authStore.js`)
```javascript
- user: object | null
- token: string | null
- isAuthenticated: boolean
- login(email, password)
- register(data)
- logout()
- refreshUser()
```

**Cart Store** (`cartStore.js`)
```javascript
- items: array
- addItem(product, quantity)
- updateQuantity(itemId, quantity)
- removeItem(itemId)
- clearCart()
- subtotal: computed
```

**Wishlist Store** (`wishlistStore.js`)
```javascript
- items: array
- add(product)
- remove(productId)
- toggle(product)
- isInWishlist(productId)
```

**Theme Store** (`themeStore.js`)
```javascript
- theme: 'light' | 'dark' | 'system'
- setTheme(theme)
- initTheme()
```

---

## 📡 API Integration

### Base Configuration

```javascript
// src/services/api.js
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: true
})

// Auto-attach token
api.interceptors.request.use((config) => {
  const token = useAuthStore.getState().token
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})
```

### Available APIs

```javascript
// Authentication
authApi.login(email, password)
authApi.register(data)
authApi.logout()

// Products
productsApi.list(params)
productsApi.detail(id)

// Cart
cartApi.get()
cartApi.addItem(data)
cartApi.updateItem(id, data)

// Orders
ordersApi.create(data)
ordersApi.list()
ordersApi.detail(id)

// Customer
customerApi.getProfile()
customerApi.updateProfile(data)
customerApi.getAddresses()
customerApi.createAddress(data)
```

---

## 🚢 Deployment

### Deploy to Vercel

```bash
# Install Vercel CLI
npm i -g vercel

# Deploy
vercel

# Set environment variables in Vercel dashboard:
# - VITE_API_URL
# - VITE_GOOGLE_MAPS_API_KEY
```

### Deploy to Netlify

```bash
# Build command
npm run build

# Publish directory
dist

# Environment variables:
# - VITE_API_URL
# - VITE_GOOGLE_MAPS_API_KEY
```

### Docker Deployment

```dockerfile
FROM node:18-alpine as build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM nginx:alpine
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
```

---

## 🧪 Testing

### Unit Tests (Coming Soon)

```bash
# Run tests
npm run test

# Run with coverage
npm run test:coverage
```

### E2E Tests (Coming Soon)

```bash
# Run Cypress
npm run test:e2e
```

---

## 📱 PWA Support (Future)

Progressive Web App features planned:
- Offline support
- Install to home screen
- Push notifications
- Background sync

---

## 🔒 Security Best Practices

✅ **Implemented:**
- Token-based authentication
- HTTP-only cookies (backend)
- XSS protection
- CSRF protection
- API key restrictions (Google Maps)
- Environment variables for secrets

⚠️ **Production Checklist:**
- [ ] Enable HTTPS
- [ ] Configure CSP headers
- [ ] Set secure cookie flags
- [ ] Rate limiting on API
- [ ] Input validation & sanitization

---

## 🌐 Browser Support

- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## 📊 Performance

### Bundle Size

```
dist/assets/index.css   ~82 KB (13 KB gzipped)
dist/assets/index.js    ~867 KB (269 KB gzipped)
```

### Optimization Tips

- Code splitting with React.lazy()
- Image optimization (WebP, lazy loading)
- CDN for static assets
- Compression (Brotli/Gzip)

---

## 🐛 Troubleshooting

### Common Issues

**Issue: Map not loading**
- Check API key in `.env.local`
- Verify APIs enabled in Google Console
- Check browser console for errors

**Issue: Cart not persisting**
- Check localStorage permissions
- Verify Zustand persist configuration

**Issue: API calls failing**
- Verify backend is running
- Check VITE_API_URL in `.env.local`
- Check CORS settings in backend

---

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📝 License

This project is licensed under the MIT License - see [LICENSE](LICENSE) file.

---

## 👥 Authors

- **Your Name** - _Initial work_ - [YourGitHub](https://github.com/riki-rg)

---

## 🙏 Acknowledgments

- React team
- Vite team
- Tailwind CSS
- Google Maps Platform
- R Bread's Bakery

---

## 📞 Support

- **Email:** support@goldenhearth.com
- **Issues:** [GitHub Issues](https://github.com/riki-rg/golden-hearth-storefront/issues)
- **Documentation:** [Wiki](https://github.com/riki-rg/golden-hearth-storefront/wiki)

---

## 🗺️ Roadmap

- [x] Core e-commerce functionality
- [x] Google Maps address picker
- [x] Light/Dark theme
- [x] Responsive design
- [ ] Customer reviews & ratings
- [ ] Loyalty points system
- [ ] Pre-order scheduling
- [ ] Real-time notifications
- [ ] PWA support
- [ ] Mobile app (React Native)

---

**Made with ❤️ for Golden Hearth Bakery**
