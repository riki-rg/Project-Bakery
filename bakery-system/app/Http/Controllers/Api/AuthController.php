<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:customers,email',
            'phone'                 => 'nullable|string|max:20',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::create($validated);
        $token    = $customer->createToken('storefront')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => [
                'user'  => $customer,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * POST /api/v1/auth/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (! $customer || ! Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi salah.'],
            ]);
        }

        if (! $customer->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun kamu tidak aktif. Hubungi kami untuk bantuan.',
            ], 403);
        }

        // Hapus token lama (opsional: satu sesi per device)
        $customer->tokens()->delete();
        $token = $customer->createToken('storefront')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => [
                'user'  => $customer,
                'token' => $token,
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar.',
        ]);
    }

    /**
     * GET /api/v1/auth/me
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
        ]);
    }

    /**
     * POST /api/v1/auth/forgot-password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Implementasi kirim email reset — stub untuk sekarang
        // Password::broker('customers')->sendResetLink($request->only('email'));

        return response()->json([
            'success' => true,
            'message' => 'Jika email terdaftar, kami akan mengirimkan link reset kata sandi.',
        ]);
    }
}
