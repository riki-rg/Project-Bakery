<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * GET /api/v1/customer/profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
        ]);
    }

    /**
     * PUT /api/v1/customer/profile
     */
    public function updateProfile(Request $request)
    {
        $customer = $request->user();

        $validated = $request->validate([
            'name'                  => 'sometimes|string|max:255',
            'phone'                 => 'sometimes|nullable|string|max:20',
            'theme_preference'      => 'sometimes|in:light,dark,system',
            'notification_preferences' => 'sometimes|array',
        ]);

        $customer->update($validated);

        return response()->json([
            'success' => true,
            'data'    => $customer->fresh(),
        ]);
    }

    /**
     * PUT /api/v1/customer/password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password:sanctum',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $request->user()->update(['password' => $request->password]);

        return response()->json(['success' => true, 'message' => 'Kata sandi berhasil diubah.']);
    }

    // ── Addresses ──────────────────────────────────────────────

    /**
     * GET /api/v1/customer/addresses
     */
    public function addresses(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user()->addresses()->orderByDesc('is_primary')->get(),
        ]);
    }

    /**
     * POST /api/v1/customer/addresses
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line'   => 'required|string',
            'city'           => 'nullable|string',
            'province'       => 'nullable|string',
            'postal_code'    => 'nullable|string|max:10',
            'landmark'       => 'nullable|string',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'courier_note'   => 'nullable|string',
            'is_primary'     => 'boolean',
        ]);

        $customer = $request->user();

        // Jika set sebagai primary, reset semua yang lain
        if (! empty($validated['is_primary'])) {
            $customer->addresses()->update(['is_primary' => false]);
        }

        // Jika ini alamat pertama, jadikan primary
        if ($customer->addresses()->count() === 0) {
            $validated['is_primary'] = true;
        }

        $address = $customer->addresses()->create($validated);

        return response()->json([
            'success' => true,
            'data'    => $address,
        ], 201);
    }

    /**
     * PUT /api/v1/customer/addresses/{id}
     */
    public function updateAddress(Request $request, $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);

        $validated = $request->validate([
            'label'          => 'sometimes|string|max:50',
            'recipient_name' => 'sometimes|string|max:255',
            'phone'          => 'sometimes|string|max:20',
            'address_line'   => 'sometimes|string',
            'city'           => 'nullable|string',
            'province'       => 'nullable|string',
            'postal_code'    => 'nullable|string|max:10',
            'landmark'       => 'nullable|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'courier_note'   => 'nullable|string',
        ]);

        $address->update($validated);

        return response()->json(['success' => true, 'data' => $address->fresh()]);
    }

    /**
     * DELETE /api/v1/customer/addresses/{id}
     */
    public function deleteAddress(Request $request, $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);
        $address->delete();

        return response()->json(['success' => true, 'message' => 'Alamat dihapus.']);
    }

    /**
     * PATCH /api/v1/customer/addresses/{id}/default
     */
    public function setDefaultAddress(Request $request, $id)
    {
        $customer = $request->user();
        $customer->addresses()->update(['is_primary' => false]);
        $customer->addresses()->findOrFail($id)->update(['is_primary' => true]);

        return response()->json(['success' => true, 'message' => 'Alamat utama diperbarui.']);
    }

    // ── Wishlist ───────────────────────────────────────────────

    /**
     * GET /api/v1/customer/wishlist
     */
    public function wishlist(Request $request)
    {
        $items = $request->user()
            ->wishlists()
            ->with('product.category')
            ->get()
            ->map(fn($w) => $w->product);

        return response()->json(['success' => true, 'data' => $items]);
    }

    /**
     * POST /api/v1/customer/wishlist/toggle
     */
    public function toggleWishlist(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $customer  = $request->user();
        $productId = $request->product_id;

        $existing = Wishlist::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            Wishlist::create(['customer_id' => $customer->id, 'product_id' => $productId]);
            $status = 'added';
        }

        return response()->json(['success' => true, 'data' => ['status' => $status]]);
    }
}
