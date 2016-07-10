<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlexaDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('alexa_devices', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('device_user_id');
			$table->integer('user_id')->nullable();
			$table->integer('station_id')->nullable();
			$table->string('device_code')->nullable(); //the code that can be used to tie a device to an account
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
        Schema::dropIfExists('alexa_devices');
	}

}
