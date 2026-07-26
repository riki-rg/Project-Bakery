<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Roti',      'slug' => 'roti',      'description' => 'Roti artisan dengan fermentasi alami', 'is_active' => true],
            ['name' => 'Pastry',    'slug' => 'pastry',    'description' => 'Pastry renyah dan lezat',               'is_active' => true],
            ['name' => 'Kue',       'slug' => 'kue',       'description' => 'Kue premium untuk segala acara',       'is_active' => true],
            ['name' => 'Cookie',    'slug' => 'cookie',    'description' => 'Cookie renyah dengan berbagai rasa',   'is_active' => true],
            ['name' => 'Minuman',   'slug' => 'minuman',   'description' => 'Minuman segar untuk menemani',         'is_active' => true],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
