<?php

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientTableSeeder extends Seeder
{
    public function run()
    {
        Ingredient::updateOrCreate(
            ['id' => Ingredient::TOMATO_ID],
            ['name' => 'Tomato', 'price' => 0.20]
        );

        Ingredient::updateOrCreate(
            ['id' => Ingredient::MOZZARELLA_ID],
            ['name' => 'Mozzarella', 'price' => 0.60]
        );

        Ingredient::updateOrCreate(
            ['id' => Ingredient::HAM_ID],
            ['name' => 'Ham', 'price' => 1.00]
        );

        Ingredient::updateOrCreate(
            ['id' => Ingredient::PINEAPPLE_ID],
            ['name' => 'Pineapple', 'price' => 0.40]
        );
    }
}
