<?php  namespace FindMeABike\Services\Data;

use FindMeABike\Contracts\Repositories\DivvyStationRepository;
use Illuminate\Redis\Database as Redis;
use Illuminate\Config\Repository as Config;

class DivvyImporter {

	const DIVVY_STATION_TABLE = 'divvy_stations';
	const LAST_RETRIEVED_REDIS_KEY = "divvyDataLastRetrieved";
	const DATA_REDIS_KEY = "divvyDataJson";

	/**
	 * @var \Predis\ClientInterface
	 */
	private $redis;
	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;
	/**
	 * @var \FindMeABike\Services\Data\DivvyDataSource
	 */
	private $divvyDataSource;
	/**
	 * @var \FindMeABike\Contracts\Repositories\DivvyStationRepository
	 */
	private $stationRepository;

	function __construct(DivvyDataSource $divvyDataSource, Redis $redis, Config $config, DivvyStationRepository $stationRepository)
	{
		$this->redis = $redis;
		$this->config = $config;
		$this->divvyDataSource = $divvyDataSource;
		$this->stationRepository = $stationRepository;
	}

	/**
	 * @return string
	 */
	public function refreshData()
	{
		if( ! $this->oldData() )
			return;

		$data = $this->divvyDataSource->getArray();

		//todo: log this, send an email notification, etc ?
		if( ! $data )
			return;

		$now = time();
		$this->stationRepository->import($data['stationBeanList']);
		$this->redis->set(self::LAST_RETRIEVED_REDIS_KEY, $now);
	}

	private function fetchDivvyJson(){
		return $this->redis->get(self::DATA_REDIS_KEY);
	}

	/**
	 * @return bool
	 */
	public function oldData(){
		$threshold = intval($this->config->get('findmeabike.data_age_threshold'));
		$age = $this->redis->get(self::LAST_RETRIEVED_REDIS_KEY);

		return($threshold === 0 ||  (time() - $age) > $threshold );
	}

} 