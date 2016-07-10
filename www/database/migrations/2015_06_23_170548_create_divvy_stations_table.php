<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivvyStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('divvy_stations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('station_id')->nullable();
			$table->string('station_name')->nullable();
			$table->integer('available_docks')->nullable();
			$table->integer('total_docks')->nullable();
			$table->integer('available_bikes')->nullable();
			$table->double('latitude')->nullable();
			$table->double('longitude')->nullable();
			$table->string('status_value')->nullable();
			$table->string('status_key')->nullable();
			$table->string('street_address_1')->nullable();
			$table->string('street_address_2')->nullable();
			$table->string('city')->nullable();
			$table->string('postal_code')->nullable();
			$table->string('location')->nullable();
			$table->string('altitude')->nullable();
			$table->boolean('test_station')->nullable();
			$table->string('last_communication_time')->nullable();
			$table->string('landmark')->nullable();
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
		Schema::drop('divvy_stations');
	}
}
