<?php namespace Owlgrin\Hive\Exceptions;

use Illuminate\Support\MessageBag;

class InvalidInputException extends Exception {

	/**
	 * Message
	 */
	const MESSAGE = 'hive::responses.message.invalid_input';

	/**
	 * Code
	 */
	const CODE = 400;

	/**
	 * Constructor
	 * @param mixed $messages
	 * @param array $replacers
	 */
	public function __construct($messages = static::MESSAGE, $replacers = array())
	{
		parent::__construct($messages, $replacers, static::CODE);
	}
}