<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем корневые деятельности
        $food = Activity::create([
            'name' => 'Еда',
            'level' => 1,
        ]);

        $automobiles = Activity::create([
            'name' => 'Автомобили',
            'level' => 1,
        ]);

        // Создаем поддеятельности для Еды
        $meat = Activity::create([
            'name' => 'Мясная продукция',
            'parent_id' => $food->id,
            'level' => 2,
        ]);

        $dairy = Activity::create([
            'name' => 'Молочная продукция',
            'parent_id' => $food->id,
            'level' => 2,
        ]);

        // Создаем поддеятельности для Автомобилей
        $trucks = Activity::create([
            'name' => 'Грузовые',
            'parent_id' => $automobiles->id,
            'level' => 2,
        ]);

        $cars = Activity::create([
            'name' => 'Легковые',
            'parent_id' => $automobiles->id,
            'level' => 2,
        ]);

        // Создаем поддеятельности для Легковых автомобилей
        Activity::create([
            'name' => 'Запчасти',
            'parent_id' => $cars->id,
            'level' => 3,
        ]);

        Activity::create([
            'name' => 'Аксессуары',
            'parent_id' => $cars->id,
            'level' => 3,
        ]);

        // Дополнительные деятельности
        $electronics = Activity::create([
            'name' => 'Электроника',
            'level' => 1,
        ]);

        Activity::create([
            'name' => 'Компьютеры',
            'parent_id' => $electronics->id,
            'level' => 2,
        ]);

        Activity::create([
            'name' => 'Телефоны',
            'parent_id' => $electronics->id,
            'level' => 2,
        ]);
    }
}
