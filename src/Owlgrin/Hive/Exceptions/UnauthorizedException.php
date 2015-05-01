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
	public function __construct($messages = null, $replacers = array())
	{
		$messages = is_null($messages) ? null : static::MESSAGE;

		parent::__construct($messages, $replacers, static::CODE);
	}
}