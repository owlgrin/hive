<?php namespace Owlgrin\Hive\Exceptions;

class MySQLException extends Exception {

	/**
	 * Message
	 */
	const MESSAGE = 'hive::responses.message.bad_request';

	/**
	 * Code
	 */
	const CODE = 900;

	/**
	 * Constructor
	 * @param mixed $messages
	 * @param array $replacers
	 */
	public function __construct($exception, $messages = null, $replacers = array())
	{
		$messages = is_null($messages) ? static::MESSAGE : $messages;

		switch($exception->getCode()) {
			case '23000': throw new InvalidInputException($messages, $replacers, static::CODE);
			default: throw new InternalException;
		}

		parent::__construct($messages, $replacers, static::CODE);
	}
}