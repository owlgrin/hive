appe<?php namespace Owlgrin\Hive\Exceptions;

use Illuminate\Support\MessageBag;

class InvalidInputException extends Exception {

	const MESSAGE = 'responses.messages.invalid_input';

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