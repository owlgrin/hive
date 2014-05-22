<?php namespace Owlgrin\Hive\Exceptions;

class InvalidPropertyException extends Exception {

	const MESSAGE = 'hive::responses.messages.invalid_input';

	const CODE = 400;

	/**
	 * Constructor
	 * @param mixed $messages
	 */
	public function __construct($messages = self::MESSAGE, $replacers = array())
	{
		parent::__construct($messages, $replacers);
	}
}