<?php

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipeTableSeeder extends Seeder
{

    public function run()
    {
        Recipe::updateOrCreate(
            ['id' => Recipe::MARGHERITA_ID],
            [
                'name'  => 'Margherita',
                'price' => 6.99
            ]
        );

        Recipe::updateOrCreate(
            ['id' => Recipe::HAWAIIAN_ID],
            [
                'name'  => 'Hawaiian',
                'price' => 8.99
            ]
        );
    }
}
