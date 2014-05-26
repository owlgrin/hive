<?php namespace Owlgrin\Hive\Command\Bus;

use Illuminate\Foundation\Application;
use App\Command\PreparableInterface;

class SimpleBus implements BusInterface {

	protected $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	public function getHandler(Command $command)
	{
		// It converts App\Commands\Some\Path\To\SomeCommand into App\Handlers\Some\Path\To\SomeHandler;
		$handler = str_replace('Command', 'Handler', get_class($command));

		return $this->app->make($handler);
	}

	public function execute(Command $command)
	{
		if($command instanceof PreparableInterface)
		{
			$command->prepare();
		}
		return $this->getHandler($command)->handle($command);
	}
}