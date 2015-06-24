<?php namespace FindMeABike\Contracts;


interface Station {

	public function availableBikes();

	public function availableDocks();

	public function totalDocks();

	public function getSpokenName();

	public function getFullSpokenStatus();

} 