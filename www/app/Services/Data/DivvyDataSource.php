<?php  namespace Biker\Services\Data;

use Illuminate\Config\Repository as Config;

class DivvyDataSource{

	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;

	function __construct(Config $config)
	{
		$this->config = $config;
	}

	/**
	 * @return string
	 */
	public function getJson()
	{
		$sourceUrl = $this->config->get('biker.data_source_uri');

		try{
			$json = file_get_contents($sourceUrl);
		}catch(\Exception $e){
			//todo: log this, send an email notification, etc ?
			return null;
		}

		return $json;
	}

	/**
	 * @return array
	 */
	public function getArray()
	{
		return json_decode($this->getJson(), true);
	}

} 