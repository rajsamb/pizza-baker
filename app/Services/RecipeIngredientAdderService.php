<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RecipeIngredientAdderService
{
    /** @var Recipe */
    private $recipe;

    /**
     * @param Recipe $recipe
     */
    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    /**
     * @param string $ingredientToAdd
     * @param int $quantity
     * @return $this
     */
    public function add(string $ingredientToAdd, int $quantity = 1): self
    {
        $ingredient = Ingredient::whereName($ingredientToAdd)->first();

        if (!$ingredient) {
            throw new ModelNotFoundException('Ingredient not found for ' . $ingredientToAdd);
        }

        RecipeIngredient::updateOrCreate(
            [
                'recipe_id'     => $this->recipe->id,
                'ingredient_id' => $ingredient->id,
                'amount'        => $quantity
            ]
        );

        $this->recipe->addAmountToBasePrice($ingredient->price * $quantity);

        return $this;
    }
}
