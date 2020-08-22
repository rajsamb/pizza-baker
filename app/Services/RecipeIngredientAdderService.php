<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;

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
    public function add(string $ingredientToAdd, int $quantity): self
    {
        $ingredient = Ingredient::whereName($ingredientToAdd)->first();

        $lastRowId = RecipeIngredient::latest('id')->first()->id;

        RecipeIngredient::updateOrCreate(
            [
                'id' => $lastRowId + 1,
                'recipe_id'     => $this->recipe->id,
                'ingredient_id' => $ingredient->id,
                'amount'        => $quantity
            ]
        );

        $this->recipe->addAmountToBasePrice($ingredient->price * $quantity);

        return $this;
    }
}
