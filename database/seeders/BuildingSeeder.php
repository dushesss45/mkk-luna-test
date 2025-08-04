<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::create([
            'address' => 'г. Москва, ул. Ленина 1, офис 3',
            'latitude' => 55.7558,
            'longitude' => 37.6176,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Тверская 10, офис 15',
            'latitude' => 55.7575,
            'longitude' => 37.6156,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Арбат 25',
            'latitude' => 55.7494,
            'longitude' => 37.5912,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Новый Арбат 32',
            'latitude' => 55.7522,
            'longitude' => 37.5716,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Покровка 22',
            'latitude' => 55.7614,
            'longitude' => 37.6466,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Мясницкая 15',
            'latitude' => 55.7604,
            'longitude' => 37.6386,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Маросейка 8',
            'latitude' => 55.7564,
            'longitude' => 37.6336,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Покровский бульвар 12',
            'latitude' => 55.7584,
            'longitude' => 37.6406,
        ]);
    }
}
