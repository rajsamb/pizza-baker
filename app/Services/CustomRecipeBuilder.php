<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $customRecipe = Recipe::updateOrCreate([
            'name' => $customPizzaName,
            'price' => self::BASE_PIZZA_PRICE
        ]);

        if (count($ingredients) > 0) {
            foreach ($ingredients as $ingredientToAdd) {
                $ingredient = Ingredient::whereName($ingredientToAdd['name'])->first();

                if (!$ingredient) {
                    // In real world, log the message or flash on a view to be displayed
                    echo "Ingredient {$ingredientToAdd['name']} not found. Preparing Recipe without this ingredient.";
                    continue;
                }

                RecipeIngredient::updateOrCreate(
                    [
                        'recipe_id' => $customRecipe->id,
                        'ingredient_id' => $ingredient->id,
                        'amount' => $ingredientToAdd['qty']
                    ]
                );

                $customRecipe->price += $ingredient->price * $ingredientToAdd['qty'];
                $customRecipe->save();
            }
        }

        return $customRecipe;
    }
}
