<?php namespace Owlgrin\Hive\Command;

use Illuminate\Support\Facades\App;

abstract class Command {

	/**
	 * Instance to hold command's data
	 * @var array
	 */
	protected $data;

	/**
	 * Instance to hold the instance of application
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Constructor
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
		$this->app = App::make('Illuminate\Foundation\Application');
	}

	/**
	 * Method to get the data
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Magic method to get data using key
	 * @param  string $property
	 * @return mixed
	 */
	public function __get($key)
	{
		return array_get($this->data, $key, null);
	}
}