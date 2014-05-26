<?php namespace Owlgrin\Hive\Command;

interface HandlerInterface {
	/**
	 * Method to handle a command
	 * @param  $command
	 * @return mixed
	 */
	public function handle($command);
}