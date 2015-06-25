<?php namespace FindMeABike;

use Develpr\AlexaApp\Contracts\AmazonEchoDevice;
use FindMeABike\Domain\Divvy\EloquentStation;
use Illuminate\Database\Eloquent\Model;

class Device extends Model implements AmazonEchoDevice{

	protected $table = "alexa_devices";

	protected $hidden = array('password');

	public function getDeviceId()
	{
		return $this->device_user_id;
	}

	public function setDeviceId($deviceId)
	{
		$this->attributes['device_user_id'] = $deviceId;
	}

	public function station(){
		return $this->belongsTo(EloquentStation::class);
	}

	public function generateDeviceCode()
	{
		$this->device_code = "" . chr(65 + rand(0, 25)) . rand(0,9) . chr(65 + rand(0, 25)) . rand(0,9) . chr(65 + rand(0, 25)) . rand(0,9) . chr(65 + rand(0, 25)) . rand(0,9);
	}

} 