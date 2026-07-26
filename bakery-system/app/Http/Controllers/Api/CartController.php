<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Ambil atau buat cart untuk customer/tamu
     */
    private function resolveCart(Request $request): Cart
    {
        $customer = $request->user();

        if ($customer) {
            return Cart::firstOrCreate(
                ['customer_id' => $customer->id],
                ['expires_at'  => now()->addDays(7)]
            );
        }

        // Tamu — gunakan guest_token dari header X-Guest-Token
        $guestToken = $request->header('X-Guest-Token');

        if ($guestToken) {
            $cart = Cart::where('guest_token', $guestToken)->first();
            if ($cart) return $cart;
        }

        // Buat cart baru untuk tamu
        return Cart::create([
            'guest_token' => Str::uuid(),
            'expires_at'  => now()->addDays(3),
        ]);
    }

    /**
     * GET /api/v1/cart
     */
    public function index(Request $request)
    {
        $cart = $this->resolveCart($request);
        $cart->load('items.product.category');

        return response()->json([
            'success' => true,
            'data'    => [
                'cart'       => $cart,
                'guest_token' => $cart->guest_token,
                'items'      => $cart->items,
                'subtotal'   => $cart->subtotal,
            ],
        ]);
    }

    /**
     * POST /api/v1/cart/items  — tambah/update item
     */
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::where('is_active', true)->findOrFail($validated['product_id']);

        if ($product->current_stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak cukup. Tersedia: {$product->current_stock}.",
            ], 422);
        }

        $cart = $this->resolveCart($request);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $newQty = $item->quantity + $validated['quantity'];
            if ($newQty > $product->current_stock) {
                $newQty = $product->current_stock;
            }
            $item->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'cart_id'        => $cart->id,
                'product_id'     => $product->id,
                'quantity'       => $validated['quantity'],
                'price_snapshot' => $product->price,
            ]);
        }

        $cart->load('items.product.category');

        return response()->json([
            'success'     => true,
            'data'        => ['cart' => $cart, 'subtotal' => $cart->subtotal],
            'guest_token' => $cart->guest_token,
        ]);
    }

    /**
     * PUT /api/v1/cart/items/{itemId}  — update quantity
     */
    public function updateItem(Request $request, $itemId)
    {
        $validated = $request->validate(['quantity' => 'required|integer|min:0']);
        $cart      = $this->resolveCart($request);
        $productId = is_string($itemId) && str_contains($itemId, '-') ? explode('-', $itemId)[0] : $itemId;
        $item      = CartItem::where('cart_id', $cart->id)
            ->where(function ($query) use ($itemId, $productId) {
                $query->where('id', $itemId)->orWhere('product_id', $productId);
            })
            ->firstOrFail();

        if ($validated['quantity'] === 0) {
            $item->delete();
        } else {
            $product = $item->product;
            $qty     = min($validated['quantity'], $product->current_stock);
            $item->update(['quantity' => $qty]);
        }

        $cart->load('items.product.category');

        return response()->json([
            'success' => true,
            'data'    => ['cart' => $cart, 'subtotal' => $cart->subtotal],
        ]);
    }

    /**
     * DELETE /api/v1/cart/items/{itemId}
     */
    public function removeItem(Request $request, $itemId)
    {
        $cart = $this->resolveCart($request);
        $productId = is_string($itemId) && str_contains($itemId, '-') ? explode('-', $itemId)[0] : $itemId;
        CartItem::where('cart_id', $cart->id)
            ->where(function ($query) use ($itemId, $productId) {
                $query->where('id', $itemId)->orWhere('product_id', $productId);
            })
            ->firstOrFail()
            ->delete();
        $cart->load('items.product.category');

        return response()->json([
            'success' => true,
            'data'    => ['cart' => $cart, 'subtotal' => $cart->subtotal],
        ]);
    }

    /**
     * DELETE /api/v1/cart  — kosongkan keranjang
     */
    public function clear(Request $request)
    {
        $cart = $this->resolveCart($request);
        $cart->items()->delete();

        return response()->json(['success' => true, 'message' => 'Keranjang dikosongkan.']);
    }

    /**
     * POST /api/v1/cart/validate
     * Validasi ulang stok dan harga sebelum checkout
     */
    public function validate(Request $request)
    {
        $cart   = $this->resolveCart($request);
        $issues = [];

        foreach ($cart->items()->with('product')->get() as $item) {
            $product = $item->product;

            if (! $product || ! $product->is_active) {
                $issues[] = ['item_id' => $item->id, 'type' => 'unavailable', 'message' => "{$item->product?->name} sudah tidak tersedia."];
                continue;
            }

            if ($product->current_stock < $item->quantity) {
                $issues[] = ['item_id' => $item->id, 'type' => 'stock', 'message' => "Stok {$product->name} hanya tersisa {$product->current_stock}."];
            }

            if ((float) $product->price !== (float) $item->price_snapshot) {
                $issues[] = ['item_id' => $item->id, 'type' => 'price_changed', 'message' => "Harga {$product->name} berubah menjadi Rp " . number_format($product->price, 0, ',', '.') . '.'];
                $item->update(['price_snapshot' => $product->price]);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'valid'    => empty($issues),
                'issues'   => $issues,
                'subtotal' => $cart->fresh()->subtotal,
            ],
        ]);
    }

    /**
     * POST /api/v1/cart/voucher  — apply voucher dari database
     */
    public function applyVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $code   = strtoupper(trim($request->code));
        $promo  = \App\Models\Promotion::where('code', $code)->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak ditemukan.',
            ], 422);
        }

        if (! $promo->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah tidak berlaku.',
            ], 422);
        }

        $cart     = $this->resolveCart($request);
        $subtotal  = $cart->subtotal;

        if ($subtotal < $promo->min_spend) {
            return response()->json([
                'success' => false,
                'message' => "Minimum pembelian Rp " . number_format($promo->min_spend, 0, ',', '.') . " untuk menggunakan voucher ini.",
            ], 422);
        }

        // Check first time customer
        if ($promo->first_time_customer_only) {
            $customer = $request->user();
            if ($customer && \App\Models\Order::where('customer_id', $customer->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher ini hanya untuk pelanggan baru.',
                ], 422);
            }
        }

        $discount = $promo->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'data'    => [
                'code'     => $promo->code,
                'name'     => $promo->name,
                'type'     => $promo->type,
                'discount' => $discount,
                'message'  => 'Voucher berhasil diterapkan!',
            ],
        ]);
    }
}
