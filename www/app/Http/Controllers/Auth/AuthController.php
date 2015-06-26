<?php

namespace FindMeABike\Http\Controllers\Auth;

use FindMeABike\Device;
use FindMeABike\User;
use Validator;
use FindMeABike\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Config\Repository as Config;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;

	/**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Config $config)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
		$this->config = $config;
	}

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		$deviceCodeLength = intval($this->config->get('findmeabike.web.device_code_length'));
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
			'device_code' => 'sometimes|size:' . $deviceCodeLength . '|device_code'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
		/** @var Device  $device */
		$device = null;
		if(array_key_exists('device_code', $data)){
			$device = Device::where('device_code', '=', $data['device_code'])->first();
		}

        $user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);

		if( $device ){
			$device->user()->associate($user)->save();
			$device->device_code = NULL;
		}
		return $user;
    }
}
