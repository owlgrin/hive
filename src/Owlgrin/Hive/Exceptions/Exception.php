<?php namespace Owlgrin\Hive\Exceptions;

use Exception as BaseException;
use Illuminate\Support\Facades\Lang;

abstract class Exception extends BaseException {

	const MESSAGE = 'hive::responses.message.bad_request';
	const CODE = 400;

	public function __construct($message = self::MESSAGE, $code = self::CODE, BaseException $previous = null)
	{
		parent::__construct(Lang::get($message), $code, $previous);
	}
}