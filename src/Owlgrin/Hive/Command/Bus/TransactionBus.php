<?php namespace Owlgrin\Hive\Command\Bus;

use Illuminate\Database\DatabaseManager as Database;

use Owlgrin\Hive\Command\PreparableInterface;
use Owlgrin\Hive\Command\Bus\SimpleBus;
use Owlgrin\Hive\Exceptions;

use PDOException;

class TransactionBus implements BusInterface {

	/**
	 * Instance to hold simple bus
	 * @var SimpleBus
	 */
	protected $decoratedBus;

	/**
	 * Instace to hold database manager
	 *
	 * @var DatabaseManager
	 */
	protected $db;

	/**
	 * Constructor
	 * @param Application $app
	 */
	public function __construct(SimpleBus $decoratedBus, Database $db)
	{
		$this->decoratedBus = $decoratedBus;
		$this->db = $db;
	}

	/**
	 * Method to get handler for the command
	 * @param  $command
	 * @return Handler
	 */
	public function getHandler($command)
	{
		// not required as it is just a decorator around another bus
	}

	/**
	 * Method to execute the command
	 * @param  $command
	 * @return mixed
	 */
	public function execute($command)
	{
		try
		{
			$this->db->beginTransaction();

			$response = $this->decoratedBus->execute($command);

			$this->db->commit();

			return $response;
		}
		catch(PDOException $e)
		{
			$this->db->rollback();

			throw new Exceptions\InternalException($e->getMessage());
		}
	}
}