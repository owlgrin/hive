<?php namespace Owlgrin\Hive\Exceptions;

class NotFoundException extends Exception {

	const MESSAGE = 'hive::responses.messages.not_found';

	const CODE = 404;

	/**
	 * Constructor
	 * @param mixed $messages
	 */
	public function __construct($messages = self::MESSAGE, $replacers = array())
	{
		parent::__construct($messages, $replacers);
	}
}