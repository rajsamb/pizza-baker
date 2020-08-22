<?php

namespace App\Utilities;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Recipe;
use Illuminate\Support\Collection;

class Luigis
{
    /** @var Fridge */
    private $fridge;

    /** @var Oven */
    private $oven;

    public function __construct(Oven $oven = null)
    {
        $this->fridge = new Fridge();
        $this->oven = $oven ? $oven : new ElectricOven();
    }

    public function restockFridge(): void
    {
        /** @var Ingredient $ingredient */
        foreach (Ingredient::all() as $ingredient) {
            $this->fridge->add($ingredient, 10);
        }
    }

    // todo create this function (returns a collection of cooked pizzas)
    /**
     * @param Order $order
     * @return Pizza[]|Collection
     */
    public function deliver(Order $order): Collection
    {
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
        foreach ($recipe->ingredients as $ingredient) {
            // 1) Check fridge has enough of each ingredient
            if (!$this->fridge->has($ingredient, 1)) {
                // 2) restockFridge if needed
                $this->restockFridge();
            }

            // 3) take ingredients from the fridge
            $this->fridge->take($ingredient, 1);
        }

        // 4) create new Pizza
        return new Pizza($recipe);
    }

    /**
     * @param Pizza $pizza
     */
    private function cook(Pizza &$pizza): void
    {
        $this->oven->bake($pizza);
    }
}
