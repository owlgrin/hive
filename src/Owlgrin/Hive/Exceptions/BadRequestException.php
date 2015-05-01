<?php namespace Owlgrin\Hive\Exceptions;

class BadRequestException extends Exception {

	/**
	 * Message
	 */
	const MESSAGE = 'hive::responses.message.bad_request';

	/**
	 * Code
	 */
	const CODE = 400;

	/**
	 * Constructor
	 * @param mixed $messages
	 * @param array $replacers
	 */
	public function __construct($messages = null, $replacers = array())
	{
		$messages = is_null($messages) ? static::MESSAGE : $messages;

		parent::__construct($messages, $replacers, static::CODE);
	}
}