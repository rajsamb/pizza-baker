<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class OrderRecipe
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $order_id
 * @property int $recipe_id
 */
class OrderRecipe extends Pivot
{
    protected $table = 'luigis_order_recipes';
    public $timestamps = false;
    protected $fillable = ['order_id', 'recipe_id'];
}
