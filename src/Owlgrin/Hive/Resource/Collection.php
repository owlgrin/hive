<?php namespace Owlgrin\Hive\Resource;

use Illuminate\Support\Collection as IlluminateCollection;
use Owlgrin\Hive\Resource\Item;

class Collection extends IlluminateCollection {

	/**
	 * Constructor
	 * @param mixed $data
	 * @param Transformer $transformer
	 */
	public function __construct($data, $transformer)
	{
		parent::__construct($this->makeCollection($data, $transformer));
	}

	/**
	 * Method to make collection
	 * @param  mixed $data
	 * @param  Transformer $transformer
	 * @return array
	 */
	protected function makeCollection($data, $transformer)
	{
		$collection = array();

		foreach($data as $key => $value)
		{
			$collection[$key] = new Item($value, $transformer);
		}

		return $collection;
	}
}