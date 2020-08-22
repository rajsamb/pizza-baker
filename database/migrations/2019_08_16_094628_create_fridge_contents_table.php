<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFridgeContentsTable extends Migration {

	public function up()
	{
		Schema::create('luigis_fridge_contents', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('ingredient_id')->unique()->unsigned();
			$table->integer('amount')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('luigis_fridge_contents');
	}
}
