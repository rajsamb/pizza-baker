<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * Class Order
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property string $status
 *
 * @property Collection|Recipe[] $recipes
 */
class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_COOKING = 'cooking';
    const STATUS_READY = 'ready';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUSES = [
        self::STATUS_PREPARING,
        self::STATUS_PREPARING,
        self::STATUS_COOKING,
        self::STATUS_READY,
        self::STATUS_DELIVERED,
        self::STATUS_CANCELLED,
    ];

    protected $table = 'luigis_orders';
    public $timestamps = true;

    protected $fillable = ['status'];

    public function recipes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Recipe::class,
            OrderRecipe::class,
            'order_id',
            'id',
            'id',
            'recipe_id'
        );
    }

    /**
     * @return float
     */
    public function getPriceAttribute(): float
    {
        $orderPrice = 0.00;
        $allRecipes = $this->recipes->all();

        foreach ($allRecipes as $recipe) {
            $orderPrice += $recipe->price;
        }

        return $orderPrice;
    }
}
