<?php namespace Biker;

use Develpr\AlexaApp\Contracts\AmazonEchoDevice;
use Biker\Domain\Divvy\EloquentStation;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Device
 * @package Biker
 * @property station EloquentStation | null;
 * @property user User | null;
 */
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

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function generateDeviceCode()
    {
        $deviceCodeLength = intval(config('findmeabike.web.device_code_length'));
        $generatedDeviceCode = '';

        for($i = 0; $i <= $deviceCodeLength; $i++){
            $generatedDeviceCode .= ($i % 2 === 1) ? chr(65 + rand(0, 25)) :  rand(0,9);
        }

        $this->device_code = $generatedDeviceCode;
        return $this;
    }

}