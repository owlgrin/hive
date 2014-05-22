<?php namespace Owlgrin\Hive\Exceptions;

use Exception as BaseException;
use Illuminate\Support\Facades\Lang;

abstract class Exception extends BaseException {

	const MESSAGE = 'hive::responses.message.bad_request';

	const CODE = 400;

	public function __construct($message = null, $replacers = array())
	{
		$message = Lang::get($this->getMessage($messages));

		$message = $this->replacePlaceholders($message, $replacers);

		parent::__construct($message, self::CODE);
	}

	protected function getMessage($messages)
	{
		if($messages instanceof MessageBag)
		{
			return $messageBag->first();
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

	protected function replacePlaceholders($message, $replacers)
	{
		foreach($replacers as $placeholder => $replacer)
		{
			$message = str_replace(":$placeholder", $replacer, $message);
		}

		return $message;
	}
}