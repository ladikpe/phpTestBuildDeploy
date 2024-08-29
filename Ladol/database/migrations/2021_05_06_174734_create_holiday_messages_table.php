<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidayMessagesTable extends Migration
{
	/**
 * Run the migrations.
 *
 * @return void
 */
	public function up()
	{
    //
		Schema::create('holiday_messages', function(Blueprint $table){
			$table->increments('id');	
				$table->integer('company_id');
				$table->integer('holiday_id');
				$table->integer('a_id');
				$table->string('message');
 				$table->timestamps();
			});
		}

		/**
 * Reverse the migrations.
 *
 * @return void
 */
		public function down()
		{
    //
		}
};
		