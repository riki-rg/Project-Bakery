# 📝 Git Commit Guide - R Bread's Bakery Project

**Date:** 26 Juli 2026  
**Ready to Commit:** ✅ YES

---

## 🎯 Quick Start - Commit Both Projects

### Option 1: Commit Backend First

```bash
# 1. Backend (bakery-system)
cd /home/rizz/Documents/bakerrr/bakery-system

# Initialize git (if not done)
git init

# Add remote
git remote add origin https://github.com/yourusername/bakery-system.git

# Check status
git status

# Add files
git add .

# Commit
git commit -m "feat: Complete bakery management system with order management and analytics"

# Push
git branch -M main
git push -u origin main
```

### Option 2: Commit Frontend

```bash
# 2. Frontend (golden-hearth-storefront)
cd /home/rizz/Documents/bakerrr/golden-hearth-storefront

# Initialize git (if not done)
git init

# Add remote
git remote add origin https://github.com/yourusername/golden-hearth-storefront.git

# Check status
git status

# Add files
git add .

# Commit
git commit -m "feat: Complete e-commerce storefront with Google Maps address picker"

# Push
git branch -M main
git push -u origin main
```

---

## 🔐 SECURITY CHECKLIST - Before Push!

### Backend (.env is ignored ✅)
- [x] `.env` in .gitignore
- [x] `database.sqlite` NOT in git
- [x] `vendor/` ignored
- [x] `node_modules/` ignored
- [x] No API keys in code

### Frontend (.env.local is ignored ✅)
- [x] `.env.local` in .gitignore (via `*.local`)
- [x] `node_modules/` ignored
- [x] `dist/` ignored
- [x] No Google Maps API key in code
- [x] API keys only in .env.local

**⚠️ NEVER COMMIT:**
- `.env` files
- `database.sqlite`
- `node_modules/`
- API keys or secrets

---

## 📋 Suggested Commit Messages

### Backend

**Initial Commit:**
```bash
git commit -m "feat: Initial commit - R Bread's Bakery Management System

- Complete product & inventory management
- Order management with status tracking
- Employee & attendance system
- Dashboard with analytics & charts
- Stock movement tracking
- Promotion & voucher management
- Role-based access control (Owner/Employee)
- RESTful API for frontend integration"
```

**If Adding Features Later:**
```bash
git commit -m "feat: Add Google Maps integration for customer addresses"
git commit -m "fix: Resolve cart error handling"
git commit -m "chore: Update README with deployment instructions"
```

### Frontend

**Initial Commit:**
```bash
git commit -m "feat: Initial commit - Golden Hearth Storefront

- Complete e-commerce functionality
- Google Maps address picker with autocomplete
- Shopping cart with persistent storage
- Order tracking & history
- User authentication & profile management
- Light/Dark theme with system detection
- Responsive design (mobile-first)
- Midtrans & COD payment integration"
```

---

## 🌿 Branch Strategy (Future)

```
main (production)
  ├── develop (development)
  │   ├── feature/customer-reviews
  │   ├── feature/notifications
  │   └── feature/production-planning
  └── hotfix/cart-error-fix
```

**For now:** Just use `main` branch for initial commit.

---

## 📦 What Will Be Committed

### Backend (~500 files)
```
✅ app/ (Controllers, Models, Middleware)
✅ database/ (migrations, seeders)
✅ resources/ (views, CSS, JS)
✅ routes/
✅ config/
✅ composer.json
✅ package.json
✅ README.md

❌ .env (ignored)
❌ vendor/ (ignored)
❌ node_modules/ (ignored)
❌ database.sqlite (ignored)
❌ storage/*.key (ignored)
```

### Frontend (~200 files)
```
✅ src/ (components, pages, stores, services)
✅ public/ (images, favicon)
✅ package.json
✅ vite.config.js
✅ tailwind.config.js
✅ README.md

❌ .env.local (ignored)
❌ node_modules/ (ignored)
❌ dist/ (ignored)
```

---

## 🚀 After First Commit

### Clone on Another Machine

```bash
# Backend
git clone https://github.com/yourusername/bakery-system.git
cd bakery-system
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run dev

# Frontend
git clone https://github.com/yourusername/golden-hearth-storefront.git
cd golden-hearth-storefront
npm install
cp .env.example .env.local
# Add your Google Maps API key to .env.local
npm run dev
```

---

## 📝 Commit Message Convention

We use [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation only
- `style`: Code style (formatting, missing semicolons, etc.)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

**Examples:**
```bash
feat(orders): Add order status management
fix(cart): Resolve item quantity update bug
docs: Update installation instructions
chore: Update dependencies
```

---

## 🔄 Daily Workflow (After Initial Commit)

```bash
# Pull latest changes
git pull origin main

# Make your changes...

# Check what changed
git status
git diff

# Add files
git add .

# Commit with message
git commit -m "feat: Add customer reviews feature"

# Push
git push origin main
```

---

## 🐛 Common Issues & Solutions

### Issue 1: "repository not found"
**Solution:** Create repository on GitHub first, then add remote:
```bash
# On GitHub: Create new repository
# Then:
git remote add origin https://github.com/yourusername/repo-name.git
```

### Issue 2: "failed to push some refs"
**Solution:** Pull first, then push:
```bash
git pull origin main --rebase
git push origin main
```

### Issue 3: "large files detected"
**Solution:** Remove from git, add to .gitignore:
```bash
git rm --cached path/to/large/file
echo "path/to/large/file" >> .gitignore
git commit -m "chore: Remove large file from tracking"
```

### Issue 4: "accidentally committed .env"
**Solution:** Remove from history:
```bash
git rm --cached .env
git commit -m "chore: Remove .env from tracking"
# If already pushed, consider regenerating secrets!
```

---

## 🎉 Ready to Commit!

Your projects are ready with:
- ✅ Complete README.md (both projects)
- ✅ Proper .gitignore files
- ✅ No secrets in code
- ✅ Clean project structure
- ✅ Documentation complete

**Go ahead and commit! 🚀**

---

## 📞 Need Help?

**Git Commands Reference:**
```bash
git status          # Check current status
git log             # View commit history
git diff            # See changes
git branch          # List branches
git remote -v       # Show remotes
```

**Undo Commands:**
```bash
git reset HEAD~1    # Undo last commit (keep changes)
git checkout .      # Discard all changes
git clean -fd       # Remove untracked files
```

---

**Happy Coding! 🍞**

