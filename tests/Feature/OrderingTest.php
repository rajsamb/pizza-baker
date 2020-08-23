<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderRecipe;
use App\Models\Recipe;
use App\Services\CustomRecipeBuilder;
use App\Services\RecipeIngredientAdderService;
use App\Utilities\Luigis;
use App\Utilities\Pizza;
use BadFunctionCallException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderingTest extends TestCase
{
    /** @var Luigis */
    private $luigis;

    /**
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->setUp();
        $this->luigis = new Luigis();
    }

    public function testMargherita(): void
    {
        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        try {
            // 1) Create the order
            $order = Order::create(['status' => Order::STATUS_PENDING]);

            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => Recipe::MARGHERITA_ID,
            ]);

            $this->assertCount(1, $order->recipes);
            $this->assertEquals(Recipe::MARGHERITA_ID, $order->recipes->first()->id);
            $this->assertEquals(6.99, $order->getPriceAttribute());

            // 2) Deliver the order
            $pizzas = $this->luigis->deliver($order);

            $this->assertCount(1, $pizzas);

            // 3) Verify the order
            /** @var Pizza $pizza */
            $pizza = $pizzas->first();
            $this->assertEquals('Margherita', $pizza->getName());
            $this->assertEquals(Pizza::STATUS_COOKED, $pizza->getStatus());

            // 4) Eat the pizza
            $pizza->eatSlice();

            $this->assertEquals(7, $pizza->getSlicesRemaining());
            $this->assertEquals(Pizza::STATUS_PARTLY_EATEN, $pizza->getStatus());

            while ($pizza->getSlicesRemaining()) {
                $pizza->eatSlice();
            }

            $this->assertEquals(Pizza::STATUS_ALL_EATEN, $pizza->getStatus());

            // 5) Verify can't eat an eaten pizza
            $gotException = 'no exception thrown';
            try {
                $pizza->eatSlice();
            } catch (BadFunctionCallException $e) {
                $gotException = 'exception was thrown';
            }

            $this->assertEquals('exception was thrown', $gotException);

        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }

    public function testMargheritaAndHawaiian(): void
    {
        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        try {
            // 1) Create the order
            $order = Order::create(['status' => Order::STATUS_PENDING]);

            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => Recipe::MARGHERITA_ID,
            ]);

            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => Recipe::HAWAIIAN_ID,
            ]);

            $this->assertCount(2, $order->recipes);
            $this->assertEquals(Recipe::MARGHERITA_ID, $order->recipes->first()->id);
            $this->assertEquals(Recipe::HAWAIIAN_ID, $order->recipes->skip(1)->first()->id);
            $this->assertEquals(15.98, $order->getPriceAttribute());

            // 2) Deliver the order
            $pizzas = $this->luigis->deliver($order);

            $this->assertCount(2, $pizzas);
        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }

    public function testAddingIngredientCostMoney(): void
    {
        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        try {
            // 1) Create the order
            $order = Order::create(['status' => Order::STATUS_PENDING]);

            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => Recipe::MARGHERITA_ID,
            ]);

            // 2) Add additional ingredients after order
            $recipeIngredientAdderService = new RecipeIngredientAdderService($order->recipes->first());
            $recipeIngredientAdderService
                ->add('Mozzarella')
                ->add('Tomato', 2);

            $this->assertCount(1, $order->recipes);
            $this->assertEquals(Recipe::MARGHERITA_ID, $order->recipes->first()->id);
            $this->assertEquals(7.99, $order->getPriceAttribute());
        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }

    public function testAddingInvalidIngredientThrowModelNotFoundException(): void
    {
        $this->expectException(ModelNotFoundException::class);

        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        try {
            // 1) Create the order
            $order = Order::create(['status' => Order::STATUS_PENDING]);

            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => Recipe::MARGHERITA_ID,
            ]);

            // 2) Add ingredients that doesn't exist on the order
            $recipeIngredientAdderService = new RecipeIngredientAdderService($order->recipes->first());
            $recipeIngredientAdderService->add('Olive', 5);
        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }

    public function testCustomerCanMakeCustomPizzas(): void
    {
        $customRecipeBuilder = new CustomRecipeBuilder();
        $customRecipe = $customRecipeBuilder->build(
            'UltimatePizza',
            [
                [
                    'name' => 'Mozzarella',
                    'qty' => 2
                ],
                [
                    'name' => 'Tomato',
                    'qty' => 4
                ],
                [
                    'name' => 'Ham',
                    'qty' => 2
                ],
                [
                    'name' => 'Pineapple',
                    'qty' => 3
                ]
            ]
        );

        // 1) Create the order
        $order = Order::create(['status' => Order::STATUS_PENDING]);

        OrderRecipe::create([
            'order_id' => $order->id,
            'recipe_id' => $customRecipe->id
        ]);

        $this->assertCount(1, $order->recipes);
        $this->assertEquals($customRecipe->id, $order->recipes->first()->id);
        $this->assertEquals(10.20, $order->getPriceAttribute());
    }

    public function testAddingInvalidIngredientOliveOnCustomPizzaWillSkipTheIngredient(): void
    {
        $customRecipeBuilder = new CustomRecipeBuilder();
        $customRecipe = $customRecipeBuilder->build(
            'UltimatePizza',
            [
                [
                    'name' => 'Mozzarella',
                    'qty' => 2
                ],
                [
                    'name' => 'Tomato',
                    'qty' => 4
                ],
                [
                    'name' => 'Ham',
                    'qty' => 2
                ],
                [
                    'name' => 'Pineapple',
                    'qty' => 3
                ],
                [
                    'name' => 'Olive',
                    'qty' => 10
                ]
            ]
        );

        // 1) Create the order
        $order = Order::create(['status' => Order::STATUS_PENDING]);

        OrderRecipe::create([
            'order_id' => $order->id,
            'recipe_id' => $customRecipe->id
        ]);

        $this->assertCount(1, $order->recipes);
        $this->assertEquals($customRecipe->id, $order->recipes->first()->id);
        $this->assertEquals(10.20, $order->getPriceAttribute());
    }
}
