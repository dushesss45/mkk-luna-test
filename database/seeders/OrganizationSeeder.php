<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrganizationPhone;
use App\Models\Activity;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем деятельности
        $food = Activity::where('name', 'Еда')->first();
        $meat = Activity::where('name', 'Мясная продукция')->first();
        $dairy = Activity::where('name', 'Молочная продукция')->first();
        $automobiles = Activity::where('name', 'Автомобили')->first();
        $trucks = Activity::where('name', 'Грузовые')->first();
        $cars = Activity::where('name', 'Легковые')->first();
        $electronics = Activity::where('name', 'Электроника')->first();

        // Организация 1 - Мясная продукция
        $org1 = Organization::create([
            'name' => 'ООО "Рога и Копыта"',
            'building_id' => 1,
        ]);

        $org1->phones()->createMany([
            ['phone' => '2-222-222'],
            ['phone' => '3-333-333'],
            ['phone' => '8-923-666-13-13'],
        ]);

        $org1->activities()->attach([$food->id, $meat->id]);

        // Организация 2 - Молочная продукция
        $org2 = Organization::create([
            'name' => 'ИП "Молочный рай"',
            'building_id' => 2,
        ]);

        $org2->phones()->createMany([
            ['phone' => '4-444-444'],
            ['phone' => '8-800-555-35-35'],
        ]);

        $org2->activities()->attach([$food->id, $dairy->id]);

        // Организация 3 - Автомобили
        $org3 = Organization::create([
            'name' => 'ООО "АвтоСервис"',
            'building_id' => 3,
        ]);

        $org3->phones()->createMany([
            ['phone' => '5-555-555'],
            ['phone' => '8-495-123-45-67'],
        ]);

        $org3->activities()->attach([$automobiles->id, $cars->id]);

        // Организация 4 - Грузовые автомобили
        $org4 = Organization::create([
            'name' => 'ООО "Грузовик-Сервис"',
            'building_id' => 4,
        ]);

        $org4->phones()->createMany([
            ['phone' => '6-666-666'],
            ['phone' => '8-495-987-65-43'],
        ]);

        $org4->activities()->attach([$automobiles->id, $trucks->id]);

        // Организация 5 - Электроника
        $org5 = Organization::create([
            'name' => 'ООО "ТехноМир"',
            'building_id' => 5,
        ]);

        $org5->phones()->createMany([
            ['phone' => '7-777-777'],
            ['phone' => '8-800-123-45-67'],
        ]);

        $org5->activities()->attach([$electronics->id]);

        // Организация 6 - Мясная и молочная продукция
        $org6 = Organization::create([
            'name' => 'ООО "Продукты-Плюс"',
            'building_id' => 6,
        ]);

        $org6->phones()->createMany([
            ['phone' => '8-888-888'],
            ['phone' => '8-495-111-22-33'],
        ]);

        $org6->activities()->attach([$food->id, $meat->id, $dairy->id]);

        // Организация 7 - Автомобили и электроника
        $org7 = Organization::create([
            'name' => 'ООО "Универсал-Сервис"',
            'building_id' => 7,
        ]);

        $org7->phones()->createMany([
            ['phone' => '9-999-999'],
            ['phone' => '8-800-999-88-77'],
        ]);

        $org7->activities()->attach([$automobiles->id, $electronics->id]);

        // Организация 8 - Только еда
        $org8 = Organization::create([
            'name' => 'ИП "Свежие продукты"',
            'building_id' => 8,
        ]);

        $org8->phones()->createMany([
            ['phone' => '1-111-111'],
            ['phone' => '8-495-777-88-99'],
        ]);

        $org8->activities()->attach([$food->id]);
    }
}
