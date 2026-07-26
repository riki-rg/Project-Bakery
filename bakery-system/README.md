# 🍞 R Bread's Bakery Management System

> Comprehensive bakery management system built with Laravel 11, featuring inventory management, employee tracking, order processing, and analytics dashboard.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Setup](#-database-setup)
- [Running the Application](#-running-the-application)
- [Default Credentials](#-default-credentials)
- [Project Structure](#-project-structure)
- [API Documentation](#-api-documentation)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 📊 Dashboard & Analytics
- **Real-time Analytics Dashboard** with interactive charts (Chart.js)
- Stock movement visualization (7-day trends)
- Top/slow moving products analysis
- Daily/weekly/monthly statistics
- Low stock alerts & notifications

### 📦 Product Management
- Complete CRUD operations for products
- Category management with hierarchical support
- Product images & gallery
- Stock tracking (current stock, low stock threshold)
- Bulk import/export (coming soon)

### 📈 Inventory & Stock Management
- Real-time stock tracking
- Stock movement history (IN/OUT/ADJUSTMENT)
- Automatic stock updates on orders
- Low stock alerts
- Stock reports & analytics

### 🛒 Order Management
- Complete order lifecycle management
- Order status tracking (Pending → Processing → Completed)
- Customer information display
- Order history & search
- Status update workflow
- Automatic stock restoration on cancellation
- Payment tracking (Midtrans/COD integration)

### 👥 Employee Management
- Employee CRUD operations
- Role-based access control (Owner/Employee)
- Active/inactive status management
- Employee profiles with photos

### ⏰ Attendance System
- Daily check-in/check-out tracking
- Attendance history & reports
- Manual attendance correction (owner only)
- Late/absent statistics

### 🎉 Promotions & Vouchers
- Promotional campaign management
- Discount codes & vouchers
- Start/end date scheduling
- Active/inactive status toggle

### 🔐 Authentication & Authorization
- Laravel Breeze authentication
- Role-based middleware (owner/employee)
- Session management
- Password reset functionality

### 🎨 Modern UI/UX
- Custom design tokens (R Bread's brand colors)
- Responsive layout (mobile-friendly)
- Tailwind CSS styling
- Alpine.js interactivity
- Clean sidebar navigation

---

## 🛠 Tech Stack

**Backend Framework:**
- [Laravel 11.x](https://laravel.com) - PHP Framework
- [PHP 8.2+](https://php.net) - Programming Language

**Frontend:**
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript framework
- [Chart.js](https://chartjs.org) - Data visualization

**Database:**
- SQLite (Development)
- MySQL/PostgreSQL (Production ready)

**Additional Tools:**
- [Laravel Breeze](https://laravel.com/docs/11.x/starter-kits#breeze) - Authentication scaffolding
- [Vite](https://vitejs.dev) - Asset bundler

---

## 📋 Requirements

- **PHP:** >= 8.2
- **Composer:** Latest version
- **Node.js:** >= 18.x
- **NPM:** >= 9.x
- **SQLite** (dev) or **MySQL** (production)
- **Extensions:** OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/riki-rg/Project-Bakery.git
cd Project-Bakery/bakery-system
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

---

## ⚙️ Configuration

### Edit `.env` file:

```env
APP_NAME="R Bread's Bakery"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (SQLite for development)
DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite

# Or use MySQL for production
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=bakery_system
# DB_USERNAME=root
# DB_PASSWORD=

# Mail Configuration (for password reset)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@rbreadsbakery.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🗄 Database Setup

### Option A: SQLite (Development)

```bash
# Create database file
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed demo data
php artisan db:seed
```

### Option B: MySQL (Production)

```bash
# Create database
mysql -u root -p
CREATE DATABASE bakery_system;
exit;

# Update .env with MySQL credentials
# Then run:
php artisan migrate
php artisan db:seed
```

---

## 🏃 Running the Application

### Development Mode

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server (for CSS/JS)
npm run dev
```

**Access the application:**
- URL: http://127.0.0.1:8000
- Login page: http://127.0.0.1:8000/login

### Production Build

```bash
# Build assets
npm run build

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start with production server (e.g., Nginx + PHP-FPM)
```

---

## 🔑 Default Credentials

### Owner Account
```
Email: owner@bakery.com
Password: password
```

### Employee Account
```
Email: employee@bakery.com
Password: password
```

**⚠️ IMPORTANT:** Change these credentials immediately in production!

---

## 📁 Project Structure

```
bakery-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── OrderManagementController.php
│   │   │   ├── StockMovementController.php
│   │   │   ├── EmployeeController.php
│   │   │   └── AttendanceController.php
│   │   └── Middleware/
│   │       ├── OwnerMiddleware.php
│   │       └── ActiveUserMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── StockMovement.php
│   │   └── Attendance.php
│   └── Providers/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── css/
│   │   └── app.css (Tailwind + Custom tokens)
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── dashboard.blade.php
│       ├── products/
│       ├── orders/
│       ├── employees/
│       └── attendances/
├── routes/
│   ├── web.php
│   └── api.php
├── public/
├── storage/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## 📡 API Documentation

### Authentication API

```http
POST /api/v1/auth/login
POST /api/v1/auth/register
POST /api/v1/auth/logout
GET  /api/v1/auth/me
```

### Products API

```http
GET    /api/v1/products
GET    /api/v1/products/{id}
GET    /api/v1/categories
```

### Orders API (Customer)

```http
GET    /api/v1/orders
POST   /api/v1/orders
GET    /api/v1/orders/{id}
POST   /api/v1/orders/{id}/cancel
```

### Cart API

```http
GET    /api/v1/cart
POST   /api/v1/cart/items
PUT    /api/v1/cart/items/{id}
DELETE /api/v1/cart/items/{id}
```

**Full API documentation:** [View API Docs](docs/API.md) _(coming soon)_

---

## 📸 Screenshots

### Dashboard Analytics
![Dashboard](docs/screenshots/dashboard.png)

### Order Management
![Orders](docs/screenshots/orders.png)

### Product Management
![Products](docs/screenshots/products.png)

_Screenshots coming soon_

---

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=ProductTest

# Generate coverage report
php artisan test --coverage
```

---

## 🚢 Deployment

### Using Laravel Forge
1. Connect your repository
2. Configure environment variables
3. Set deployment script
4. Enable Quick Deploy

### Using Manual Deployment
```bash
# On server
git pull origin main
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔧 Maintenance

### Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Database Backup

```bash
# SQLite
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u root -p bakery_system > backup-$(date +%Y%m%d).sql
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Authors

- **Your Name** - _Initial work_ - [YourGitHub](https://github.com/riki-rg)

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Chart.js
- R Bread's Bakery Team

---

## 📞 Support

For support, email rizkimaulanahakim67@gmail.com or open an issue in this repository.

---

## 🗺️ Roadmap

- [x] Dashboard with analytics
- [x] Product & inventory management
- [x] Order management system
- [x] Employee & attendance tracking
- [ ] Production planning & batch tracking
- [ ] Email notifications
- [ ] Advanced reporting & exports
- [ ] Mobile app (PWA)
- [ ] Multi-branch support

---

**Made with ❤️ for R Bread's Bakery**
