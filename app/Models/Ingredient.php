<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Class Ingredient
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property string $name
 *
 * @property-read Collection|Recipe[] $requiredForRecipes
 * @property-read Collection|RecipeIngredient[] $recipeRequirements
 * @property-read FridgeContent $stock
 */
class Ingredient extends Model
{
    const TOMATO_ID = 1;
    const MOZZARELLA_ID = 2;
    const HAM_ID = 3;
    const PINEAPPLE_ID = 4;

    protected $table = 'luigis_ingredients';
    public $timestamps = false;
    protected $fillable = ['id', 'name'];

    public function requiredForRecipes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Recipe::class,
            RecipeIngredient::class,
            'ingredient_id',
            'id',
            'id',
            'recipe_id'
        );
    }

    public function recipeRequirements(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(FridgeContent::class);
    }
}
