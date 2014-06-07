<?php namespace Owlgrin\Hive\Response;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;

trait Responses {
	protected $httpStatus = 200;

	public static $TYPE_BAD_REQUEST = 'bad_request';
	public static $TYPE_UNAUTHORIZED = 'unauthorized';
	public static $TYPE_FORBIDDEN = 'forbidden';
	public static $TYPE_NOT_FOUND = 'not_found';
	public static $TYPE_INVALID_INPUT = 'invalid_input';
	public static $TYPE_INTERNAL_ERROR = 'internal_error';

	public static $CODE_BAD_REQUEST = 400;
	public static $CODE_UNAUTHORIZED = 401;
	public static $CODE_FORBIDDEN = 403;
	public static $CODE_NOT_FOUND = 404;
	public static $CODE_INVALID_INPUT = 400;
	public static $CODE_INTERNAL_ERROR = 500;

	public static $MSG_BAD_REQUEST = 'responses.message.bad_request';
	public static $MSG_UNAUTHORIZED = 'responses.message.unauthorized';
	public static $MSG_FORBIDDEN = 'responses.message.forbidden';
	public static $MSG_NOT_FOUND = 'responses.message.not_found';
	public static $MSG_INVALID_INPUT = 'responses.message.invalid_input';
	public static $MSG_INTERNAL_ERROR = 'responses.message.internal_error';

	protected function setHttpStatus($status)
	{
		$this->httpStatus = $status;
		return $this;
	}

	protected function getHttpStatus()
	{
		return $this->httpStatus;
	}

	protected function respondNoContent()
	{
		return $this->setHttpStatus(204)->respondString('');
	}

	protected function respondWithData(array $data)
	{
		return $this->setHttpStatus(200)->respondArray($data);
	}

	protected function respondBadRequest($message = null, $code = null)
	{
		return $this->setHttpStatus(400)
			->respondError(
				$message ?: static::$MSG_BAD_REQUEST,
				$code ?: static::$CODE_BAD_REQUEST,
				static::$TYPE_BAD_REQUEST
			);
	}

	protected function respondUnauthorized($message = null, $code = null)
	{
		return $this->setHttpStatus(401)
			->respondError(
				$message ?: static::$MSG_UNAUTHORIZED,
				$code ?: static::$CODE_UNAUTHORIZED,
				static::$TYPE_UNAUTHORIZED
			);
	}

	protected function respondForbidden($message = null, $code = null)
	{
		return $this->setHttpStatus(403)
			->respondError(
				$message ?: static::$MSG_FORBIDDEN,
				$code ?: static::$CODE_FORBIDDEN,
				static::$TYPE_FORBIDDEN
			);
	}

	protected function respondNotFound($message = null, $code = null)
	{
		return $this->setHttpStatus(404)
			->respondError(
				$message ?: static::$MSG_NOT_FOUND,
				$code ?: static::$CODE_NOT_FOUND,
				static::$TYPE_NOT_FOUND
			);
	}

	protected function respondInvalidInput($message = null, $code = null)
	{
		return $this->setHttpStatus(400)
			->respondError(
				$message ?: static::$MSG_INVALID_INPUT,
				$code ?: static::$CODE_INVALID_INPUT,
				static::$TYPE_INVALID_INPUT
			);
	}

	protected function respondInternalError($message = null, $code = null)
	{
		return $this->setHttpStatus(500)
			->respondError(
				$message ?: static::$MSG_INTERNAL_ERROR,
				$code ?: static::$CODE_INTERNAL_ERROR,
				static::$TYPE_INTERNAL_ERROR
			);
	}

	protected function respondError($message = null, $code = null, $type = null)
	{
		// If no message was passed, we will default it to the bad request message
		if(is_null($message)) $message = static::$MSG_BAD_REQUEST;

		// If the message is found in user's language file
		if(Lang::has($message))
		{
			$message = Lang::get($message);
		}
		// If not found in user's language file, we will default it to ours
		else if(Lang::has("hive::$message"))
		{
			$message = Lang::get("hive::$message");
		}

		return $this->respondArray([
			'error' => [
				'code'    => $code ?: static::$CODE_BAD_REQUEST,
				'type'    => $type ?: static::$TYPE_BAD_REQUEST,
				'message' => $message
			]
		]);
	}

	private function respondArray(array $data, array $headers = [])
	{
		$default = Config::get('hive::response.headers');
		return Response::make($data, $this->httpStatus, array_merge($default, $headers));
	}

	private function respondString($content, array $headers = [])
	{
		$default = Config::get('hive::response.headers');
		return Response::make($content, $this->httpStatus, array_merge($default, $headers));
	}
}