<?php namespace Owlgrin\Hive;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

use Owlgrin\Hive\Response\Responses;

class HiveServiceProvider extends ServiceProvider {

	use Responses;

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('owlgrin/hive');

		require __DIR__ . '/helpers.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerExceptions();
		$this->registerCommands();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	protected function registerExceptions()
	{
		// Most general exception. Often thrown when something bad goes
		$this->app->error(function(\Exception $e)
		{
			return $this->respondInternalError();
		});

		// The base exception for Hive. General and custom
		$this->app->error(function(Exceptions\InternalException $e)
		{
			return $this->respondInternalError($e->getMessage(), $e->getCode());
		});

		$this->app->error(function(Exceptions\BadRequestException $e)
		{
			return $this->respondBadRequest($e->getMessage(), $e->getCode());
		});

		$this->app->error(function(Exceptions\UnauthorizedException $e)
		{
			return $this->respondUnauthorized($e->getMessage(), $e->getCode());
		});

		$this->app->error(function(Exceptions\ForbiddenException $e)
		{
			return $this->respondForbidden($e->getMessage(), $e->getCode());
		});

		$this->app->error(function(Exceptions\NotFoundException $e)
		{
			return $this->respondNotFound($e->getMessage(), $e->getCode());
		});

		$this->app->error(function(Exceptions\InvalidInputException $e)
		{
			return $this->respondInvalidInput($e->getMessage(), $e->getCode());
		});
	}

	protected function registerCommands()
	{
		$this->app->bind('Owlgrin\Hive\Command\Bus\BusInterface', function($app)
		{
			return $this->app->make(Config::get('hive::bus'));
		});
	}

}
