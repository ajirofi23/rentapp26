<?php

namespace Database\Seeders;

use App\Models\Toy;
use Illuminate\Database\Seeder;

class ToySeeder extends Seeder
{
    public function run(): void
    {
        // Mobil Besar (B) - 20k
        for ($i = 1; $i <= 5; $i++) {
            Toy::create([
                'code' => 'B' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'category' => 'B',
                'price' => 20000,
            ]);
        }

        // Mobil Kecil (K) - 15k
        for ($i = 1; $i <= 5; $i++) {
            Toy::create([
                'code' => 'K' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'category' => 'K',
                'price' => 15000,
            ]);
        }

        // Motor (M) - 15k
        for ($i = 1; $i <= 5; $i++) {
            Toy::create([
                'code' => 'M' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'category' => 'M',
                'price' => 15000,
            ]);
        }
    }
}
