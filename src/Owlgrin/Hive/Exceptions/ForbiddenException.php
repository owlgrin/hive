<?php namespace Owlgrin\Hive\Exceptions;

class ForbiddenException extends Exception {

	/**
	 * Message
	 */
	const MESSAGE = 'hive::responses.message.forbidden';

	/**
	 * Code
	 */
	const CODE = 403;

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