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
        $customRecipe = $this->createCustomPizza($customPizzaName);

        $this->addIngredients($ingredients, $customRecipe);

        return $customRecipe;
    }

    /**
     * @param string $customPizzaName
     * @return Recipe
     */
    private function createCustomPizza(string $customPizzaName): Recipe
    {
        return Recipe::updateOrCreate([
            'name' => $customPizzaName,
            'price' => self::BASE_PIZZA_PRICE
        ]);
    }

    /**
     * @param array $ingredients
     * @param $customRecipe
     */
    private function addIngredients(array $ingredients, $customRecipe): void
    {
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
    }
}
