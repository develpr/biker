<?php  namespace Biker\Validators;


use Biker\Device;

class DeviceCodeValidator {

	/**
	 * @var \Biker\Device
	 */
	private $device;

	function __construct(Device $device)
	{
		$this->device = $device;
	}


	public function validate($attribute, $value, array $parameters){
		return count($this->device->where('device_code', '=', $value)->get()) == 1;
	}
} 