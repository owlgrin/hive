<?php namespace Owlgrin\Hive\Exceptions;

class InternalException extends Exception {

	/**
	 * Message
	 */
	const MESSAGE = 'hive::responses.message.internal_error';

	/**
	 * Code
	 */
	const CODE = 500;

	/**
	 * Original Exception that this internal exception wraps.
	 */
	protected $original;

	/**
	 * Constructor
	 * @param mixed $messages
	 * @param array $replacers
	 */
	public function __construct($messages = null, $replacers = array(), $original = null)
	{
		$messages = is_null($messages) ? static::MESSAGE : $messages;

		$this->original = $original;

		parent::__construct($messages, $replacers, static::CODE);
	}

	/**
	 * Returns the original exception that this internal exception wraps.
	 *
	 * @return mixed The original exception that was thrown by the System.
	 */
	public function getOriginal()
	{
		return $this->original;
	}
}
