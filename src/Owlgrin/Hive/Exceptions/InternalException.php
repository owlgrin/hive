<?php namespace Owlgrin\Hive\Exceptions;

class InternalException extends Exception {

	/**
	 * Message
	 */
	const MESSAGE = 'hive::responses.message.internal_error';

	/**
	 * Code
	 */
	const CODE = 500;

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