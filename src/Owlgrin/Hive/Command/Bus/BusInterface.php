<?php namespace Owlgrin\Hive\Command\Bus;

interface CommandBusInterface {
	/**
	 * Get the instance of the command
	 * @param  Command $command
	 * @return Handler
	 */
	public function getHandler($command);

	/**
	 * Executed the command through Handler
	 * @param  Command $command
	 * @return mixed
	 */
	public function execute($command);
}