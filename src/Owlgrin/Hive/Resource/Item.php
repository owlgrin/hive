<?php namespace Owlgrin\Hive\Resource;

use ArrayAccess;
use Dingo\Api\Transformer\TransformableInterface;

class Item implements ArrayAccess, TransformableInterface {

	/**
	 * Instance of data
	 * @var mixed
	 */
	protected $data;

	/**
	 * Instance of transformer
	 * @var Transformer
	 */
	protected $transformer;

	/**
	 * Constructor
	 * @param mixed $data
	 * @param Transformer $transformer
	 */
	public function __construct($data, $transformer)
	{
		$this->data = $data;
		$this->transformer = $transformer;
	}

	/**
	 * Method to get data
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Method to get transformer
	 * @return Transformer
	 */
	public function getTransformer()
	{
		return $this->transformer;
	}

	/**
	 * Determine if the given attribute exists.
	 * @param  mixed  $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->$offset);
	}

	/**
	 * Get the value for a given offset.
	 * @param  mixed  $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->$offset;
	}

	/**
	 * Set the value for a given offset.
	 * @param  mixed  $offset
	 * @param  mixed  $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->$offset = $value;
	}

	/**
	 * Unset the value for a given offset.
	 * @param  mixed  $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->$offset);
	}

	/**
	 * Method to convert to an array
	 * @return array
	 */
	public function toArray()
	{
		return (array) $this->data;
	}

	/**
	 * Method to convert to JSON
	 * @param  integer $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Magic method to set keys
	 * @param string $key
	 * @param string $value
	 */
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * Magic method to get keys
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return array_get($this->data, $key);
	}

	/**
	 * Method to convert to string
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}
}