<?php namespace Owlgrin\Hive\Command\Bus;

use Illuminate\Foundation\Application;
use Owlgrin\Hive\Command\PreparableInterface;

class SimpleBus implements BusInterface {

	/**
	 * Instance to hold app
	 * @var Application
	 */
	protected $app;

	/**
	 * Constructor
	 * @param Application $app 
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Method to get handler for the command
	 * @param  $command
	 * @return Handler
	 */
	public function getHandler($command)
	{
		// It converts App\Commands\Some\Path\To\SomeCommand into App\Handlers\Some\Path\To\SomeHandler;
		$handler = str_replace('Command', 'Handler', get_class($command));

		return $this->app->make($handler);
	}

	/**
	 * Method to execute the command
	 * @param  $command 
	 * @return mixed           
	 */
	public function execute($command)
	{
		if($command instanceof PreparableInterface)
		{
			$command->prepare();
		}
		return $this->getHandler($command)->handle($command);
	}
}