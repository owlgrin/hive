<?php namespace Owlgrin\Hive\Exceptions;

use Exception as BaseException;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Lang;

abstract class Exception extends BaseException {

	/**
	 * Default message
	 */
	const MESSAGE = 'hive::responses.message.bad_request';

	/**
	 * Default code
	 */
	const CODE = 400;

	/**
	 * Constructor
	 * @param MessageBag|string|null $messages
	 * @param array  $replacers
	 * @param number $code
	 */
	public function __construct($messages = null, $replacers = array(), $code = self::CODE)
	{
		$message = Lang::get($this->fetchMessage($messages));

		$message = $this->replacePlaceholders($message, $replacers);

		parent::__construct($message, $code);
	}

	/**
	 * Method to fetch message depending upon it's type
	 * @param  MessageBag|string $messages
	 * @return string
	 */
	protected function fetchMessage($messages)
	{
		if($messages instanceof MessageBag)
		{
			return $messages->first();
		}
		else if(is_string($messages))
		{
			return $messages;
		}
		else
		{
			return self::MESSAGE;
		}
	}

	/**
	 * Method to replace placeholders, if any
	 * @param  string $message
	 * @param  array $replacers
	 * @return string
	 */
	protected function replacePlaceholders($message, array $replacers)
	{
		foreach($replacers as $placeholder => $replacer)
		{
			$message = str_replace(":$placeholder", $replacer, $message);
		}

		return $message;
	}
}