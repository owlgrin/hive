<?php namespace Owlgrin\Hive\Exceptions;

class NotFoundException extends Exception {

	/**
	 * 	Message
	 */
	const MESSAGE = 'hive::responses.message.not_found';

	/**
	 * Code
	 */
	const CODE = 404;

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