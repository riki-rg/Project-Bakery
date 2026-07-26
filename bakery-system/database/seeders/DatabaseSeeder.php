<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\EmployeeProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Admin & Employee (untuk dashboard Laravel) ────────
        $owner = User::create([
            'name'      => 'Pemilik Toko',
            'email'     => 'owner@bakery.com',
            'password'  => Hash::make('password'),
            'role'      => 'owner',
            'is_active' => true,
        ]);

        $employee = User::create([
            'name'      => 'Karyawan 1',
            'email'     => 'employee@bakery.com',
            'password'  => Hash::make('password'),
            'role'      => 'employee',
            'is_active' => true,
        ]);

        EmployeeProfile::create([
            'user_id'       => $employee->id,
            'employee_code' => 'EMP001',
            'phone'         => '081234567890',
            'address'       => 'Jl. Contoh No. 123',
            'joined_at'     => now()->subMonths(3),
        ]);

        // ── Customer Test (untuk storefront React) ────────────
        Customer::create([
            'name'      => 'Pelanggan Test',
            'email'     => 'customer@test.com',
            'phone'     => '08123456789',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        // ── Categories & Products ──────────────────────────────
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        $this->command->info('✓ Database seeded successfully!');
        $this->command->newLine();
        $this->command->info('Admin Dashboard:');
        $this->command->info('  Owner:    owner@bakery.com / password');
        $this->command->info('  Employee: employee@bakery.com / password');
        $this->command->newLine();
        $this->command->info('Storefront Customer:');
        $this->command->info('  Customer: customer@test.com / password');
    }
}
