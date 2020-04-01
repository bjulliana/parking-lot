<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketTable extends Migration {

	public function up()
	{
		Schema::create('ticket', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('number');
			$table->integer('space_id')->unsigned();
			$table->float('cost')->nullable();
			$table->boolean('paid')->default(false);
		});
	}

	public function down()
	{
		Schema::drop('ticket');
	}
}
