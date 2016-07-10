<?php

namespace FindMeABike\Http\Middleware;

use Closure;
use Illuminate\Redis\Database as Redis;

class DivvyData
{
	/**
	 * @var \Illuminate\Redis\Database
	 */
	private $redis;

	function __construct(Redis $redis)
	{
		$this->redis = $redis;
	}


	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		return $next($request);
    }
}
