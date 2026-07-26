<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            // Roti
            ['category' => 'roti', 'name' => 'Sourdough Klasik',      'price' => 55000, 'stock' => 15, 'threshold' => 5, 'desc' => 'Sourdough klasik dengan fermentasi 24 jam menggunakan starter pilihan. Kerak renyah di luar, tekstur chewy dan lembut di dalam.'],
            ['category' => 'roti', 'name' => 'Brioche Susu',          'price' => 42000, 'stock' => 20, 'threshold' => 5, 'desc' => 'Roti brioche lembut dengan kandungan susu dan mentega tinggi. Sempurna untuk sarapan atau sandwich.'],
            ['category' => 'roti', 'name' => 'Banana Bread',          'price' => 48000, 'stock' => 12, 'threshold' => 3, 'desc' => 'Banana bread moist dengan pisang matang dan kacang walnut. Tanpa pengawet.'],
            ['category' => 'roti', 'name' => 'Focaccia Rosemary',     'price' => 45000, 'stock' => 10, 'threshold' => 3, 'desc' => 'Focaccia Italia dengan rosemary segar, minyak zaitun extra virgin, dan sea salt.'],
            ['category' => 'roti', 'name' => 'Rye Bread',             'price' => 52000, 'stock' => 8,  'threshold' => 3, 'desc' => 'Roti rye dengan kandungan serat tinggi. Rasa khas dan tekstur padat.'],
            ['category' => 'roti', 'name' => 'Baguette Tradisional',  'price' => 38000, 'stock' => 18, 'threshold' => 5, 'desc' => 'Baguette Prancis dengan kerak renyah dan interior airy. Dipanggang setiap pagi.'],

            // Pastry
            ['category' => 'pastry', 'name' => 'Croissant Mentega',   'price' => 28000, 'stock' => 25, 'threshold' => 8, 'desc' => 'Croissant klasik dengan 64 layer mentega Prancis. Flaky dan buttery.'],
            ['category' => 'pastry', 'name' => 'Pain au Chocolat',    'price' => 32000, 'stock' => 20, 'threshold' => 6, 'desc' => 'Pastry berlapis dengan cokelat Belgium dark chocolate di dalamnya.'],
            ['category' => 'pastry', 'name' => 'Almond Croissant',    'price' => 35000, 'stock' => 15, 'threshold' => 5, 'desc' => 'Croissant dengan frangipane almond dan taburan almond slices di atasnya.'],
            ['category' => 'pastry', 'name' => 'Cinnamon Roll',       'price' => 35000, 'stock' => 12, 'threshold' => 4, 'desc' => 'Cinnamon roll lembut dengan cream cheese frosting. Hangat dan harum kayu manis.'],
            ['category' => 'pastry', 'name' => 'Eclair Cokelat',      'price' => 25000, 'stock' => 18, 'threshold' => 5, 'desc' => 'Eclair dengan choux pastry, pastry cream vanilla, dan dark chocolate glaze.'],
            ['category' => 'pastry', 'name' => 'Danish Keju',         'price' => 30000, 'stock' => 14, 'threshold' => 4, 'desc' => 'Danish pastry dengan cream cheese filling dan taburan gula halus.'],

            // Kue
            ['category' => 'kue', 'name' => 'Red Velvet Cake',        'price' => 185000, 'stock' => 6,  'threshold' => 2, 'desc' => 'Red velvet cake lembut dengan cream cheese frosting. Ukuran 18cm, 8 potong.'],
            ['category' => 'kue', 'name' => 'Chiffon Pandan',         'price' => 75000,  'stock' => 10, 'threshold' => 3, 'desc' => 'Chiffon cake pandan lembut dan harum. Tekstur fluffy seperti kapas.'],
            ['category' => 'kue', 'name' => 'Mille Crepe',            'price' => 125000, 'stock' => 5,  'threshold' => 2, 'desc' => 'Mille crepe dengan 20 layer crepe tipis dan pastry cream premium.'],
            ['category' => 'kue', 'name' => 'Cheesecake New York',    'price' => 165000, 'stock' => 7,  'threshold' => 2, 'desc' => 'New York style cheesecake yang creamy dengan graham cracker base.'],
            ['category' => 'kue', 'name' => 'Tiramisu',               'price' => 95000,  'stock' => 8,  'threshold' => 3, 'desc' => 'Tiramisu klasik dengan ladyfinger, mascarpone, espresso, dan cocoa powder.'],
            ['category' => 'kue', 'name' => 'Opera Cake',             'price' => 135000, 'stock' => 4,  'threshold' => 2, 'desc' => 'Opera cake Prancis dengan layer almond sponge, coffee buttercream, dan chocolate ganache.'],

            // Cookie
            ['category' => 'cookie', 'name' => 'Chocolate Chip Cookie', 'price' => 85000, 'stock' => 30, 'threshold' => 10, 'desc' => 'Cookie klasik dengan chocolate chips Belgium. Isi 12 pcs per box.'],
            ['category' => 'cookie', 'name' => 'Butter Cookies',        'price' => 75000, 'stock' => 25, 'threshold' => 8,  'desc' => 'Butter cookies renyah dengan mentega premium. Isi 15 pcs per box.'],
            ['category' => 'cookie', 'name' => 'Oatmeal Raisin Cookie', 'price' => 78000, 'stock' => 20, 'threshold' => 6,  'desc' => 'Cookie sehat dengan oat dan kismis. Isi 12 pcs per box.'],
            ['category' => 'cookie', 'name' => 'Macaron Assorted',      'price' => 120000,'stock' => 12, 'threshold' => 4,  'desc' => 'Macaron Prancis dengan 6 rasa berbeda. Isi 12 pcs per box.'],

            // Minuman
            ['category' => 'minuman', 'name' => 'Kopi Susu Gula Aren',  'price' => 28000, 'stock' => 50, 'threshold' => 15, 'desc' => 'Kopi susu dengan gula aren asli. Manis alami dan creamy.'],
            ['category' => 'minuman', 'name' => 'Matcha Latte',         'price' => 32000, 'stock' => 40, 'threshold' => 12, 'desc' => 'Matcha latte dengan matcha premium dari Jepang. Creamy dan tidak pahit.'],
            ['category' => 'minuman', 'name' => 'Hot Chocolate',        'price' => 30000, 'stock' => 35, 'threshold' => 10, 'desc' => 'Hot chocolate dengan dark chocolate couverture. Rich dan indulgent.'],
            ['category' => 'minuman', 'name' => 'Fresh Orange Juice',   'price' => 25000, 'stock' => 45, 'threshold' => 15, 'desc' => 'Jus jeruk segar tanpa gula tambahan. Diperas saat order.'],
        ];

        foreach ($products as $p) {
            $category = $categories[$p['category']] ?? null;
            if (! $category) continue;

            Product::create([
                'category_id'           => $category->id,
                'name'                  => $p['name'],
                'slug'                  => Str::slug($p['name']),
                'description'           => $p['desc'],
                'price'                 => $p['price'],
                'image_path'            => null, // nanti bisa ditambah manual atau via upload
                'current_stock'         => $p['stock'],
                'low_stock_threshold'   => $p['threshold'],
                'is_active'             => true,
            ]);
        }
    }
}
