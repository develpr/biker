<?php  namespace Biker\Domain\Divvy;

use Biker\Domain\DataContainer;
use Illuminate\Database\Eloquent\Model;

class GenericStation extends DataContainer {

	public function availableBikes(){
		return intval($this->availableBikes);
	}

	public function availableDocks(){
		return intval($this->availableDocks);
	}

	public function totalDocks(){
		return ($this->availableBikes() + $this->availableDocks());
	}

	public function getSpokenName()
	{
		return $this->station_name;
	}

}