<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'code'                   => 'PERTAMA',
                'name'                   => 'Diskon Pelanggan Baru',
                'description'            => 'Diskon 10% untuk pembelian pertama, maksimal Rp 15.000',
                'type'                   => 'percentage',
                'value'                  => 10,
                'max_discount'           => 15000,
                'min_spend'              => 50000,
                'quota'                  => 1000,
                'first_time_customer_only' => true,
                'starts_at'              => now(),
                'ends_at'                => now()->addMonths(6),
            ],
            [
                'code'                   => 'HEMAT50K',
                'name'                   => 'Hemat 50 Ribu',
                'description'            => 'Potongan Rp 50.000 untuk minimum pembelian Rp 200.000',
                'type'                   => 'fixed',
                'value'                  => 50000,
                'max_discount'           => null,
                'min_spend'              => 200000,
                'quota'                  => 500,
                'first_time_customer_only' => false,
                'starts_at'              => now(),
                'ends_at'                => now()->addMonths(3),
            ],
            [
                'code'                   => 'GRATISONGKIR',
                'name'                   => 'Gratis Ongkir',
                'description'            => 'Bebas biaya pengiriman untuk order di atas Rp 100.000',
                'type'                   => 'shipping',
                'value'                  => 100,
                'max_discount'           => 25000,
                'min_spend'              => 100000,
                'quota'                  => null,
                'first_time_customer_only' => false,
                'starts_at'              => now(),
                'ends_at'                => null,
            ],
        ];

        foreach ($promotions as $promo) {
            Promotion::create($promo);
        }

        $this->command->info('✓ 3 promotions seeded: PERTAMA, HEMAT50K, GRATISONGKIR');
    }
}
