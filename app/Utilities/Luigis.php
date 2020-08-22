<?php

namespace App\Utilities;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Recipe;
use App\Services\PizzaBakeMessageBuilder;
use Illuminate\Support\Collection;

class Luigis
{
    /** @var Fridge */
    private $fridge;

    /** @var Oven */
    private $oven;

    /** @var int */
    private $defaultRestockQuantity = 10;

    /**
     * @param Oven|null $oven
     */
    public function __construct(Oven $oven = null)
    {
        $this->fridge = new Fridge();
        $this->oven = $oven ? $oven : new ElectricOven(new PizzaBakeMessageBuilder());
    }

    /**
     * @return void
     */
    public function restockFridge(): void
    {
        /** @var Ingredient $ingredient */
        foreach (Ingredient::all() as $ingredient) {
            $this->fridge->add($ingredient, $this->defaultRestockQuantity);
        }
    }

    /**
     * Returns a collection of cooked pizzas
     *
     * @param Order $order
     * @return Pizza[]|Collection
     */
    public function deliver(Order $order): Collection
    {
        $this->oven->heatUp();

        return collect($order->recipes->all())->map(function (Recipe $recipe) {
            $pizza = $this->prepare($recipe);
            $this->cook($pizza);
            return $pizza;
        });
    }

    /**
     * You can only create a new Pizza if you first take all the
     * ingredients required by the recipe from the fridge
     *
     * @param Recipe $recipe
     * @return Pizza
     */
    private function prepare(Recipe $recipe): Pizza
    {
        foreach ($recipe->ingredientRequirements as $ingredientRequirements) {
            $ingredient = Ingredient::find($ingredientRequirements->ingredient_id);

            // 1) Check fridge has enough of each ingredient
            if (!$this->fridge->has($ingredient, $ingredientRequirements->amount)) {
                // 2) restockFridge if needed
                $this->restockFridge();
            }

            // 3) take ingredients from the fridge
            $this->fridge->take($ingredient, $ingredientRequirements->amount);
        }

        // 4) create new Pizza
        return new Pizza($recipe);
    }

    /**
     * @param Pizza $pizza
     */
    private function cook(Pizza $pizza): void
    {
        $this->oven->bake($pizza);
    }
}
