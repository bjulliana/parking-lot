<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParkingSpaceTable extends Migration {

	public function up()
	{
		Schema::create('parking_space', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->boolean('occupied')->default(false);
		});
	}

	public function down()
	{
		Schema::drop('parking_space');
	}
}
