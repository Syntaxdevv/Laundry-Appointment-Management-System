<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Wash & Fold', 'price' => 35.00],
            ['name' => 'Wash, Dry & Fold', 'price' => 45.00],
            ['name' => 'Dry Cleaning', 'price' => 80.00],
            ['name' => 'Comforter / Blanket', 'price' => 60.00],
            ['name' => 'Ironing', 'price' => 25.00],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                ['price' => $service['price']]
            );
        }
    }
}
