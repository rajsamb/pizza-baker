<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('luigis_orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('status', 255);
		});
	}

	public function down()
	{
		Schema::drop('luigis_orders');
	}
}
