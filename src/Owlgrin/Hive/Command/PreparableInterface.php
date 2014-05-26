<?php Owlgrin\Hive\Command;

interface PreparableInterface {
	/**
	 * Method to prepare before executing
	 * @return void
	 */
	public function prepare();
}