<?php

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientTableSeeder extends Seeder
{

    public function run()
    {
        Ingredient::updateOrCreate(
            ['id' => Ingredient::TOMATO_ID],
            ['name' => 'Tomato']
        );

        Ingredient::updateOrCreate(
            ['id' => Ingredient::MOZZARELLA_ID],
            ['name' => 'Mozzarella']
        );

        Ingredient::updateOrCreate(
            ['id' => Ingredient::HAM_ID],
            ['name' => 'Ham']
        );

        Ingredient::updateOrCreate(
            ['id' => Ingredient::PINEAPPLE_ID],
            ['name' => 'Pineapple']
        );
    }
}
