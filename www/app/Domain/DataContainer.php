<?php  namespace Biker\Domain;


class DataContainer {
	private $attributes;

	function __construct(array $attributes)
	{
		$this->attributes = $attributes;
	}

	/**
	 * Get an attribute from the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttribute($key)
	{
		$value = null;
		if (array_key_exists($key, $this->attributes)) {
			if (array_key_exists($key, $this->attributes)) {
				$value = $this->attributes[$key];
			}
		}
		return $value;
	}


	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->getAttribute($key);
	}
} 