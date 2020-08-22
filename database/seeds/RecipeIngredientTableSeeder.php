<?php

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Seeder;
use App\Models\RecipeIngredient;

class RecipeIngredientTableSeeder extends Seeder
{
    public function run()
    {
        // Margherita
        RecipeIngredient::updateOrCreate(
            ['id' => 1],
            [
                'recipe_id'     => Recipe::MARGHERITA_ID,
                'ingredient_id' => Ingredient::TOMATO_ID,
                'amount'        => 2
            ]
        );
        RecipeIngredient::updateOrCreate(
            ['id' => 2],
            [
                'recipe_id'     => Recipe::MARGHERITA_ID,
                'ingredient_id' => Ingredient::MOZZARELLA_ID,
                'amount'        => 2
            ]
        );

        // Hawaiian
        RecipeIngredient::updateOrCreate(
            ['id' => 3],
            [
                'recipe_id'     => Recipe::HAWAIIAN_ID,
                'ingredient_id' => Ingredient::TOMATO_ID,
                'amount'        => 2
            ]
        );
        RecipeIngredient::updateOrCreate(
            ['id' => 4],
            [
                'recipe_id'     => Recipe::HAWAIIAN_ID,
                'ingredient_id' => Ingredient::MOZZARELLA_ID,
                'amount'        => 2
            ]
        );
        RecipeIngredient::updateOrCreate(
            ['id' => 5],
            [
                'recipe_id'     => Recipe::HAWAIIAN_ID,
                'ingredient_id' => Ingredient::HAM_ID,
                'amount'        => 1
            ]
        );
        RecipeIngredient::updateOrCreate(
            ['id' => 6],
            [
                'recipe_id'     => Recipe::HAWAIIAN_ID,
                'ingredient_id' => Ingredient::PINEAPPLE_ID,
                'amount'        => 1
            ]
        );
    }
}
