<?php namespace App\Command;

use Illuminate\Support\Facades\App;

abstract class Command {

	/**
	 * Instance to hold command's data
	 * @var array
	 */
	protected $data;

	/**
	 * Instance to hold auth manager
	 * @var AuthManager
	 */
	protected $auth;

	/**
	 * Constructor
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
		$this->auth = App::make('Illuminate\Auth\AuthManager');
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