<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderRecipesTable extends Migration {

	public function up()
	{
		Schema::create('luigis_order_recipes', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->integer('recipe_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('luigis_order_recipes');
	}
}
