# Testing Guide - Bakery Management System

## Quick Testing Checklist

### 1. Initial Setup Test
```bash
cd /home/rizz/Documents/bakerrr/bakery-system
php artisan serve
```
Open browser to: http://localhost:8000

Expected: Should redirect to login page

---

## 2. Authentication Testing

### Test Owner Login
1. Navigate to http://localhost:8000/login
2. Enter credentials:
   - Email: `owner@bakery.com`
   - Password: `password`
3. Click Login

**Expected Result:**
- ✅ Redirected to dashboard
- ✅ See summary cards (Total Products, Total Employees)
- ✅ See low stock alerts (if any)
- ✅ See today's attendance
- ✅ See recent stock movements

### Test Employee Login
1. Logout from owner account
2. Login with:
   - Email: `employee@bakery.com`
   - Password: `password`

**Expected Result:**
- ✅ Redirected to dashboard
- ✅ See summary cards
- ✅ See recent stock movements
- ✅ NO access to employee management menu (owner only)

---

## 3. Owner Features Testing

### A. Employee Management
**Login as:** owner@bakery.com

#### Test: View Employees
1. Navigate to `/employees`
2. **Expected:** See list of employees with status

#### Test: Create Employee
1. Click "Tambah Karyawan"
2. Fill form:
   - Name: Test Employee
   - Email: test@test.com
   - Password: password
   - Confirm Password: password
   - Employee Code: EMP002
   - Phone: 081234567891
   - Address: Test Address
   - Joined Date: Today
3. Click "Simpan"

**Expected Result:**
- ✅ Success message shown
- ✅ New employee appears in list
- ✅ Employee is active by default

#### Test: Deactivate Employee
1. Find the test employee
2. Click "Nonaktifkan"
3. Confirm action

**Expected Result:**
- ✅ Success message
- ✅ Status changes to "Tidak Aktif" (red badge)
- ✅ Employee cannot login anymore

#### Test: Deactivated Employee Cannot Login
1. Logout
2. Try to login with test@test.com / password

**Expected Result:**
- ✅ Login rejected or immediately logged out
- ✅ Error message: "Your account has been deactivated"

---

### B. Category Management

#### Test: View Categories
1. Login as owner
2. Navigate to `/categories`
3. **Expected:** See 4 default categories

#### Test: Create Category
1. Click "Tambah Kategori"
2. Fill form:
   - Name: Roti Spesial
   - Description: Koleksi roti spesial
3. Click "Simpan"

**Expected Result:**
- ✅ Success message
- ✅ New category in list with slug "roti-spesial"

---

### C. Product Management

#### Test: View Products
1. Navigate to `/products`
2. **Expected:** See 10 default products in grid layout

#### Test: Filter Products
1. Use search box: type "roti"
2. Select category: "Roti Manis"
3. Click "Filter"

**Expected Result:**
- ✅ Only products matching criteria shown

#### Test: Create Product
1. Click "Tambah Produk"
2. Fill form:
   - Category: Roti Manis
   - Name: Roti Uji Coba
   - Description: Test product
   - Price: 7000
   - Low Stock Threshold: 15
   - Is Active: Checked
3. Click "Simpan"

**Expected Result:**
- ✅ Success message
- ✅ New product appears in list
- ✅ Current stock = 0 (default)

#### Test: Upload Product Image
1. Edit a product
2. Upload image (must be jpeg/png/jpg/gif, max 2MB)
3. Click "Simpan"

**Expected Result:**
- ✅ Image uploaded to storage/app/public/products
- ✅ Image displayed on product card

---

## 4. Stock Management Testing

### Test: Record Stock In
**Login as:** owner or employee

1. Navigate to `/stock-movements/create`
2. Fill form:
   - Product: Select "Roti Uji Coba" (stock = 0)
   - Type: Stok Masuk
   - Quantity: 100
   - Note: Produksi pagi
3. Click "Simpan"

**Expected Result:**
- ✅ Success message
- ✅ Product stock updated to 100
- ✅ Movement recorded in history
- ✅ Shows: stock_before=0, stock_after=100

### Test: Record Stock Out
1. Create another movement:
   - Product: Roti Uji Coba
   - Type: Stok Keluar
   - Quantity: 30
   - Note: Terjual
2. Click "Simpan"

**Expected Result:**
- ✅ Stock updated to 70
- ✅ Movement recorded with stock_before=100, stock_after=70

### Test: Prevent Negative Stock
1. Try to create stock out:
   - Product: Roti Uji Coba (current: 70)
   - Type: Stok Keluar
   - Quantity: 100
2. Click "Simpan"

**Expected Result:**
- ✅ Error message: "Insufficient stock. Available: 70"
- ✅ Stock NOT changed
- ✅ No movement recorded

### Test: Stock Adjustment
1. Create movement:
   - Product: Roti Uji Coba
   - Type: Penyesuaian
   - Quantity: 50
   - Note: Stock opname hasil cek fisik
2. Click "Simpan"

**Expected Result:**
- ✅ Stock set to exactly 50 (not added or subtracted)
- ✅ Movement recorded with type "adjustment"

### Test: Filter Stock History
1. Navigate to `/stock-movements`
2. Test filters:
   - Select product
   - Select type (in/out/adjustment)
   - Date range
3. Click "Filter"

**Expected Result:**
- ✅ Only matching movements shown

---

## 5. Attendance Testing

### Test: Employee Check-In
**Login as:** employee@bakery.com

1. Navigate to `/my-attendance`
2. Click "Check In" button

**Expected Result:**
- ✅ Success message
- ✅ Check-in time recorded
- ✅ Today's date shown in attendance list

### Test: Prevent Duplicate Check-In
1. Click "Check In" again

**Expected Result:**
- ✅ Error message: "You have already checked in today"
- ✅ No duplicate record created

### Test: Employee Check-Out
1. Click "Check Out" button

**Expected Result:**
- ✅ Success message
- ✅ Check-out time recorded
- ✅ Attendance record updated

### Test: Owner View All Attendances
**Login as:** owner@bakery.com

1. Navigate to `/attendances`
2. **Expected:** See all employee attendances

### Test: Filter Attendances
1. Select employee
2. Select date range
3. Click "Filter"

**Expected Result:**
- ✅ Only matching records shown

### Test: Correct Attendance (Owner Only)
1. Find an attendance record
2. Click "Koreksi"
3. Modify times:
   - Check-in: 08:00
   - Check-out: 17:00
   - Status: Present
   - Correction Note: Manual correction
4. Click "Simpan"

**Expected Result:**
- ✅ Times updated
- ✅ Correction note saved
- ✅ corrected_by = owner's user_id

---

## 6. Dashboard Testing

### Test: Low Stock Alert
**Login as:** owner@bakery.com

1. Create a product with low stock:
   - Name: Test Low Stock
   - Low Stock Threshold: 20
   - Record stock in: 15 units
2. Visit dashboard

**Expected Result:**
- ✅ Product appears in "Produk Stok Rendah/Habis" section
- ✅ Yellow badge for low stock

### Test: Out of Stock Alert
1. Create stock out to make stock = 0
2. Refresh dashboard

**Expected Result:**
- ✅ Product appears with red badge
- ✅ Shows 0 stock

---

## 7. API Testing

### Test: Get All Products
```bash
curl -X GET "http://localhost:8000/api/v1/products"
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [...]
  }
}
```

### Test: Get Product by ID
```bash
curl -X GET "http://localhost:8000/api/v1/products/1"
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Roti Cokelat",
    ...
  }
}
```

### Test: Get Categories
```bash
curl -X GET "http://localhost:8000/api/v1/categories"
```

**Expected Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Roti Manis",
      "products_count": 4
    }
  ]
}
```

### Test: API Filter
```bash
curl -X GET "http://localhost:8000/api/v1/products?category_id=1&search=cokelat"
```

**Expected Result:**
- ✅ Only products in category 1 with "cokelat" in name

---

## 8. Security Testing

### Test: Owner-Only Routes Protection
**Login as:** employee@bakery.com

1. Try to access: `/employees`

**Expected Result:**
- ✅ 403 Forbidden error
- ✅ Message: "Unauthorized action"

### Test: Unauthenticated Access
1. Logout
2. Try to access: `/dashboard`

**Expected Result:**
- ✅ Redirected to login page

### Test: CSRF Protection
1. Try to submit a form without CSRF token

**Expected Result:**
- ✅ 419 Page Expired error

---

## 9. Validation Testing

### Test: Duplicate Email
1. Try to create employee with existing email

**Expected Result:**
- ✅ Validation error: "The email has already been taken"

### Test: Invalid Image Upload
1. Try to upload non-image file (e.g., .txt)

**Expected Result:**
- ✅ Validation error: "The image must be an image"

### Test: Image Size Limit
1. Try to upload image > 2MB

**Expected Result:**
- ✅ Validation error: "The image must not be greater than 2048 kilobytes"

### Test: Required Fields
1. Submit empty form

**Expected Result:**
- ✅ Multiple validation errors for required fields

---

## 10. Database Integrity Testing

### Test: Cascade Delete Protection
1. Try to delete a category that has products

**Expected Result:**
- ✅ Products remain linked or deletion prevented
- ✅ Or soft delete (is_active = false)

### Test: Stock Movement Audit Trail
1. Check stock_movements table after any stock change

**Expected Result:**
- ✅ Record exists with correct user_id
- ✅ stock_before and stock_after are accurate
- ✅ occurred_at timestamp is correct

---

## 11. Performance Testing

### Test: Pagination
1. Navigate to any list page (products, employees, etc)
2. Check if pagination appears
3. Click next/previous page

**Expected Result:**
- ✅ Maximum 15 items per page (configurable)
- ✅ Pagination links work correctly

---

## Automated Testing Commands

### Run Laravel Tests (if test cases created)
```bash
php artisan test
```

### Check Routes
```bash
php artisan route:list
```

### Check Database Status
```bash
php artisan migrate:status
```

### Clear All Caches
```bash
php artisan optimize:clear
```

---

## Common Issues & Solutions

### Issue: 404 on routes
**Solution:** 
```bash
php artisan route:cache
php artisan config:clear
```

### Issue: Images not displaying
**Solution:**
```bash
php artisan storage:link
```

### Issue: Permission denied on storage
**Solution:**
```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: Database locked (SQLite)
**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
# Restart php artisan serve
```

---

## Test Summary Checklist

- [ ] Owner can login
- [ ] Employee can login
- [ ] Owner can manage employees
- [ ] Owner can manage categories
- [ ] Owner can manage products
- [ ] Both can manage stock
- [ ] Stock validation works (no negative)
- [ ] Employee can check-in/out
- [ ] Owner can view all attendances
- [ ] Owner can correct attendances
- [ ] Dashboard shows correct data
- [ ] Low stock alerts appear
- [ ] API endpoints return data
- [ ] API only shows active products
- [ ] Deactivated users cannot login
- [ ] Employee cannot access owner routes
- [ ] Image upload works
- [ ] Validation errors display correctly
- [ ] Pagination works on all lists
- [ ] Search and filters work

---

## Performance Expectations

- **Page Load:** < 2 seconds
- **Database Queries:** Optimized with eager loading
- **API Response:** < 500ms
- **Image Upload:** < 5 seconds for 2MB image

---

## Browser Compatibility

Tested on:
- Chrome (recommended)
- Firefox
- Safari
- Edge

Mobile responsive: Yes

---

**Last Updated:** 2026-07-25T03:43:25Z  
**Version:** 1.0.0 (MVP)
