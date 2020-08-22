<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecipeIngredientsTable extends Migration {

	public function up()
	{
		Schema::create('luigis_recipe_ingredients', function(Blueprint $table) {
			$table->increments('id', true);
			$table->integer('recipe_id')->unsigned();
			$table->integer('ingredient_id')->unsigned();
			$table->integer('amount')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('luigis_recipe_ingredients');
	}
}
