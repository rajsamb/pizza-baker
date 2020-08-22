<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class FridgeContent
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $ingredient_id
 * @property int $amount
 *
 * @property-read Ingredient $ingredient
 */
class FridgeContent extends Model
{
    protected $table = 'luigis_fridge_contents';
    public $timestamps = true;
    protected $fillable = ['ingredient_id', 'amount'];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
