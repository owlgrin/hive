<?php namespace Owlgrin\Hive\Response;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;

trait Responses {
	protected $httpStatus = 200;

	protected $responseTypes = [
		'bad_request'    => 'bad_request',
		'unauthorized'   => 'unauthorized',
		'forbidden'      => 'forbidden',
		'not_found'      => 'not_found',
		'invalid_input'  => 'invalid_input',
		'internal_error' => 'internal_error'
	];

	protected $responseCodes = [
		'bad_request'    => 400,
		'unauthorized'   => 401,
		'forbidden'      => 403,
		'not_found'      => 404,
		'invalid_input'  => 400,
		'internal_error' => 500
	];

	protected $responseMessages = [
		'bad_request'    => 'responses.message.bad_request',
		'unauthorized'   => 'responses.message.unauthorized',
		'forbidden'      => 'responses.message.forbidden',
		'not_found'      => 'responses.message.not_found',
		'invalid_input'  => 'responses.message.invalid_input',
		'internal_error' => 'responses.message.internal_error'
	];


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
		return $this->respondArray([
			'error' => [
				'code'    => $code ?: static::$CODE_BAD_REQUEST,
				'type'    => $type ?: static::$TYPE_BAD_REQUEST,
				'message' => Lang::get('hive::' . $message ?: static::$MSG_BAD_REQUEST)
			]
		]);
	}

	private function respondArray(array $data, array $headers = [])
	{
		return Response::make($data, $this->httpStatus, $headers);
	}
}