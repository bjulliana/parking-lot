<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('ticket', function(Blueprint $table) {
			$table->foreign('space_id')->references('id')->on('parking_space')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('ticket', function(Blueprint $table) {
			$table->dropForeign('ticket_space_id_foreign');
		});
	}
}
