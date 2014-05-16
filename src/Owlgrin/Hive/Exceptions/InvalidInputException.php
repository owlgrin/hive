<?php namespace Owlgrin\Hive\Exceptions;

use Illuminate\Support\MessageBag;

class InvalidInputException extends Exception {

	/**
	 * Constructor
	 * @param Illuminate\Support\MessageBag $messageBag
	 */
	public function __construct(MessageBag $messageBag)
	{
		parent::__construct($messageBag->first(), 400);
	}
}