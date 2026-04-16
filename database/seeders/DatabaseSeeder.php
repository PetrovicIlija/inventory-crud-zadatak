<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $elektronika = Category::create(['name' => 'Elektronika']);
        $namjestaj    = Category::create(['name' => 'Namještaj']);
        $uredskiMat   = Category::create(['name' => 'Uredski materijal']);

        Article::create([
            'category_id' => $elektronika->id,
            'name'        => 'Laptop Dell XPS 15',
            'quantity'    => 5,
            'price'       => 1299.99,
        ]);

        Article::create([
            'category_id' => $elektronika->id,
            'name'        => 'Monitor LG 27"',
            'quantity'    => 12,
            'price'       => 349.00,
        ]);

        Article::create([
            'category_id' => $elektronika->id,
            'name'        => 'Tipkovnica Logitech MX Keys',
            'quantity'    => 20,
            'price'       => 119.90,
        ]);

        Article::create([
            'category_id' => $namjestaj->id,
            'name'        => 'Radni stol 160x80',
            'quantity'    => 3,
            'price'       => 450.00,
        ]);

        Article::create([
            'category_id' => $namjestaj->id,
            'name'        => 'Ergonomska stolica',
            'quantity'    => 7,
            'price'       => 280.00,
        ]);

        Article::create([
            'category_id' => $uredskiMat->id,
            'name'        => 'Papir A4 500 listova',
            'quantity'    => 100,
            'price'       => 5.50,
        ]);

        Article::create([
            'category_id' => $uredskiMat->id,
            'name'        => 'Kemijska olovka (pak. 10)',
            'quantity'    => 50,
            'price'       => 3.20,
        ]);
    }
}
