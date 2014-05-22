<?php namespace Owlgrin\Hive\Exceptions;

class BadRequestException extends Exception {

	const MESSAGE = 'hive::responses.messages.bad_request';

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