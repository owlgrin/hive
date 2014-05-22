<?php namespace Owlgrin\Hive\Exceptions;

class UnauthorizedException extends Exception {

	const MESSAGE = 'hive::responses.messages.unauthorized';

	const CODE = 401;

	/**
	 * Constructor
	 * @param mixed $messages
	 */
	public function __construct($messages = self::MESSAGE, $replacers = array())
	{
		parent::__construct($messages, $replacers);
	}
}