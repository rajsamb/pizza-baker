<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;

class CustomRecipeBuilder
{
    private const BASE_PIZZA_PRICE = 5.00;

    /**
     * @param string $customPizzaName
     * @param array $ingredients
     * @return Recipe
     */
    public function build(string $customPizzaName, array $ingredients): Recipe
    {
        $receipe = Recipe::updateOrCreate([
            'name' => $customPizzaName,
            'price' => self::BASE_PIZZA_PRICE
        ]);

        if (count($ingredients) > 0) {
            foreach ($ingredients as $ingredientToAdd) {
                $ingredient = Ingredient::whereName($ingredientToAdd['name'])->first();

                $lastRowId = RecipeIngredient::latest('id')->first()->id;

                RecipeIngredient::updateOrCreate(
                    [
                        'id' => $lastRowId + 1,
                        'recipe_id' => $receipe->id,
                        'ingredient_id' => $ingredient->id,
                        'amount' => $ingredientToAdd['qty']
                    ]
                );

                $receipe->price += $ingredient->price * $ingredientToAdd['qty'];
                $receipe->save();
            }
        }

        return $receipe;
    }
}
