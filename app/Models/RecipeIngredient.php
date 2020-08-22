<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RecipeIngredient
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $ingredient_id
 * @property int $amount
 *
 * @property-read Ingredient $ingredient
 * @property-read Recipe $recipe
 */
class RecipeIngredient extends Model
{
    protected $table = 'luigis_recipe_ingredients';
    public $timestamps = false;
    protected $fillable = ['id', 'recipe_id', 'ingredient_id', 'amount'];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
