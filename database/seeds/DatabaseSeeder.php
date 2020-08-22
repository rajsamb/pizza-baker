<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	public function run()
	{
		Model::unguard();

		$this->call(IngredientTableSeeder::class);
		$this->command->info('Ingredient table seeded!');

		$this->call(RecipeTableSeeder::class);
		$this->command->info('Recipe table seeded!');

		$this->call(RecipeIngredientTableSeeder::class);
		$this->command->info('RecipeIngredient table seeded!');
	}
}
