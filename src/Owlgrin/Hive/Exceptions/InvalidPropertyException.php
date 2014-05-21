<?php namespace Owlgrin\Hive\Exceptions;

class InvalidPropertyException extends Exception {

	/**
	 * Constructor
	 * @param string $message
	 */
	public function __construct($message)
	{
		parent::__construct($message, 501);
	}
}