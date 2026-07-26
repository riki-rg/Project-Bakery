# API Documentation - Bakery Management System

## Base URL
```
http://localhost:8000/api/v1
```

## Response Format

All API responses follow this structure:

**Success Response:**
```json
{
  "success": true,
  "data": {...}
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": {...}
}
```

## Endpoints

### 1. Get All Products

**Endpoint:** `GET /products`

**Description:** Retrieve a paginated list of active products.

**Query Parameters:**
- `category_id` (optional): Filter by category ID
- `search` (optional): Search products by name
- `page` (optional): Page number for pagination

**Example Request:**
```bash
curl -X GET "http://localhost:8000/api/v1/products?category_id=1&search=roti"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "category_id": 1,
        "name": "Roti Cokelat",
        "slug": "roti-cokelat",
        "description": "Deskripsi untuk Roti Cokelat",
        "price": "5000.00",
        "image_path": null,
        "current_stock": 50,
        "low_stock_threshold": 10,
        "is_active": true,
        "created_at": "2026-07-25T03:16:33.000000Z",
        "updated_at": "2026-07-25T03:16:33.000000Z",
        "category": {
          "id": 1,
          "name": "Roti Manis",
          "slug": "roti-manis",
          "description": "Berbagai jenis roti dengan rasa manis",
          "is_active": true
        }
      }
    ],
    "first_page_url": "http://localhost:8000/api/v1/products?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/v1/products?page=1",
    "next_page_url": null,
    "path": "http://localhost:8000/api/v1/products",
    "per_page": 15,
    "prev_page_url": null,
    "to": 10,
    "total": 10
  }
}
```

---

### 2. Get Product Detail

**Endpoint:** `GET /products/{id}`

**Description:** Retrieve details of a specific product.

**Path Parameters:**
- `id` (required): Product ID

**Example Request:**
```bash
curl -X GET "http://localhost:8000/api/v1/products/1"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "category_id": 1,
    "name": "Roti Cokelat",
    "slug": "roti-cokelat",
    "description": "Deskripsi untuk Roti Cokelat",
    "price": "5000.00",
    "image_path": null,
    "current_stock": 50,
    "low_stock_threshold": 10,
    "is_active": true,
    "created_at": "2026-07-25T03:16:33.000000Z",
    "updated_at": "2026-07-25T03:16:33.000000Z",
    "category": {
      "id": 1,
      "name": "Roti Manis",
      "slug": "roti-manis",
      "description": "Berbagai jenis roti dengan rasa manis",
      "is_active": true,
      "created_at": "2026-07-25T03:16:33.000000Z",
      "updated_at": "2026-07-25T03:16:33.000000Z"
    }
  }
}
```

**Error Response (Product Not Found):**
```json
{
  "success": false,
  "message": "Product not found"
}
```

---

### 3. Get All Categories

**Endpoint:** `GET /categories`

**Description:** Retrieve a list of all active categories with product count.

**Example Request:**
```bash
curl -X GET "http://localhost:8000/api/v1/categories"
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Roti Manis",
      "slug": "roti-manis",
      "description": "Berbagai jenis roti dengan rasa manis",
      "is_active": true,
      "created_at": "2026-07-25T03:16:33.000000Z",
      "updated_at": "2026-07-25T03:16:33.000000Z",
      "products_count": 4
    },
    {
      "id": 2,
      "name": "Roti Tawar",
      "slug": "roti-tawar",
      "description": "Roti tawar untuk sarapan",
      "is_active": true,
      "created_at": "2026-07-25T03:16:33.000000Z",
      "updated_at": "2026-07-25T03:16:33.000000Z",
      "products_count": 2
    }
  ]
}
```

---

## Usage Examples

### JavaScript/Fetch
```javascript
// Get all products
fetch('http://localhost:8000/api/v1/products')
  .then(response => response.json())
  .then(data => console.log(data));

// Get product by ID
fetch('http://localhost:8000/api/v1/products/1')
  .then(response => response.json())
  .then(data => console.log(data));

// Get categories
fetch('http://localhost:8000/api/v1/categories')
  .then(response => response.json())
  .then(data => console.log(data));
```

### React Example
```jsx
import { useState, useEffect } from 'react';

function ProductList() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('http://localhost:8000/api/v1/products')
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          setProducts(data.data.data);
        }
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Loading...</div>;

  return (
    <div>
      {products.map(product => (
        <div key={product.id}>
          <h3>{product.name}</h3>
          <p>{product.description}</p>
          <p>Rp {product.price}</p>
          <p>Stock: {product.current_stock}</p>
        </div>
      ))}
    </div>
  );
}
```

### Axios Example
```javascript
import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api/v1';

// Get all products
const getProducts = async (params = {}) => {
  try {
    const response = await axios.get(`${API_BASE_URL}/products`, { params });
    return response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
    throw error;
  }
};

// Get product by ID
const getProduct = async (id) => {
  try {
    const response = await axios.get(`${API_BASE_URL}/products/${id}`);
    return response.data;
  } catch (error) {
    console.error('Error fetching product:', error);
    throw error;
  }
};

// Get categories
const getCategories = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/categories`);
    return response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
    throw error;
  }
};

export { getProducts, getProduct, getCategories };
```

---

## Notes

1. All API endpoints return only **active** products and categories
2. Product images are served from `/storage/products/` path
3. Pagination uses Laravel's default pagination (15 items per page)
4. All timestamps are in ISO 8601 format (UTC)
5. Price is returned as string with 2 decimal places
6. Currently no authentication required for public endpoints

## Future Enhancements (Phase 2)

- Authentication with Laravel Sanctum for protected endpoints
- POST endpoints for orders
- Rate limiting
- API versioning
- More filtering options
- Image optimization and CDN integration
