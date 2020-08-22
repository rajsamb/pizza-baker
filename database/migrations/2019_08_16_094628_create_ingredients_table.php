<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIngredientsTable extends Migration {

	public function up()
	{
		Schema::create('luigis_ingredients', function(Blueprint $table) {
			$table->increments('id', true);
			$table->string('name', 255);
		});
	}

	public function down()
	{
		Schema::drop('luigis_ingredients');
	}
}
