<?php namespace Owlgrin\Hive\Validation;

use Illuminate\Validation\Factory as IlluminateValidator;
use Illuminate\Foundation\Application;
use Illuminate\Support\MessageBag;

abstract class Runner {

	/**
	 * Instance of the application
	 * @var Application
	 */
	protected $app;

	/**
	 * Instance of validator
	 * @var  IlluminateValidator
	 */
	protected $validator;

	/**
	 * Object to handle errors
	 * @var MessageBag
	 */
	protected $errors;

	/**
	 * To determine which rules to be applied to the data
	 * @var string
	 */
	protected $when = 'default';

	/**
	 * The default rule set (empty by default)
	 * @var array
	 */
	protected static $default = [];

	/**
	 * Constructor
	 * @param Application $app
	 */
	public function __construct(Application $app, IlluminateValidator $validator)
	{
		$this->app = $app;
		$this->validator = $validator;
	}

	/**
	 * Method to set the rules to be used
	 * @param  string $when
	 * @return Validator
	 */
	public function when($when = 'default')
	{
		$this->when = $when;

		return $this;
	}

	/**
	 * Method to determine if the validation failed
	 * @param  array  $data
	 * @return boolean
	 */
	public function isInvalid($data)
	{
		return ! $this->isValid($data);
	}

	/**
	 * Method to determine if the validation passed
	 * @param  array  $data
	 * @return boolean
	 */
	public function isValid($data)
	{
		// We will use Illuminate's validator to validate our data with
		// given set of rules.
		$v = $this->validator->make($data, $this->getRules(), $this->getCustomMessages());
		
		// Setting up errors, if any
		$this->errors = $v->errors();

		// Refreshing the stage to 'default' for further calls
		$this->when();

		// true when zero errors, else false
		return count($this->errors->all()) === 0;
	}

	/**
	 * Method to get customer messages, if any
	 * @return array
	 */
	protected function getCustomMessages()
	{
		if(isset(static::${'messagesWhen' . studly_case($this->when)}))
		{
			return static::${'messagesWhen' . studly_case($this->when)};
		}

		return array();
	}

	/**
	 * Method to get rules
	 * @return array
	 */
	protected function getRules()
	{
		if(isset(static::${$this->when}))
		{
			return static::${$this->when};
		}

		return array();
	}

	/**
	 * Method to return the errors
	 * @return MessageBag
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Method to set errors with fresh instance of MessageBag
	 * @return void
	 */
	public function cleanErrors()
	{
		$this->errors = new MessageBag;
	}
}