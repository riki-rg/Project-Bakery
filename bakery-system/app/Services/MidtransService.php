<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey     = config('services.midtrans.server_key');
        Config::$clientKey     = config('services.midtrans.client_key');
        Config::$isProduction  = config('services.midtrans.is_production', false);
        Config::$isSanitized   = config('services.midtrans.is_sanitized', true);
        Config::$is3ds         = config('services.midtrans.is_3ds', true);
    }

    /**
     * Buat transaksi Snap dan dapatkan token + redirect_url
     */
    public function createSnapTransaction(array $params): array
    {
        $snapToken = Snap::getSnapToken($params);
        
        return [
            'token'        => $snapToken,
            'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}",
        ];
    }

    /**
     * Verifikasi signature dari webhook Midtrans
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $serverKey, string $receivedSignature): bool
    {
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * Ambil status transaksi dari Midtrans
     */
    public function getTransactionStatus(string $orderId): object
    {
        return Transaction::status($orderId);
    }
}
