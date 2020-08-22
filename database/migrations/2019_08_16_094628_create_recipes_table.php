<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecipesTable extends Migration {

	public function up()
	{
		Schema::create('luigis_recipes', function(Blueprint $table) {
			$table->increments('id', true);
			$table->string('name', 255);
			$table->float('price');
		});
	}

	public function down()
	{
		Schema::drop('luigis_recipes');
	}
}
