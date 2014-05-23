<?php namespace Owlgrin\Hive\Exceptions;

class UnauthorizedException extends Exception {

	/**
	 * Message
	 */
	const MESSAGE = 'hive::responses.message.unauthorized';

	/**
	 * Code
	 */
	const CODE = 401;

	/**
	 * Constructor
	 * @param mixed $messages
	 * @param array $replacers
	 */
	public function __construct($messages = self::MESSAGE, $replacers = array())
	{
		parent::__construct($messages, $replacers, self::CODE);
	}
}