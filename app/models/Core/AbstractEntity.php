<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 08.08.17
 * Time: 23:00
 */

namespace LwModels\Core;

abstract class AbstractEntity
{

	/**
	 * @var []
	 */
	protected $dataHasChanged;

	/**
	 * @var []
	 */
	protected $dataOrginal;

	/**
	 * @var []
	 */
	protected $dataNew;

	/**
	 * @var []
	 */
	protected $data;

	/**
	 * @var ConnectorInterface
	 */
	protected $connection;

	/**
	 * @param mixed $pk
	 * @return boolean
	 */
	abstract public function load($pk);

	/**
	 * @return boolean
	 */
	abstract public function find();

	/**
	 * @return boolean
	 */
	abstract public function save();

	/**
	 * @param mixed $pk
	 * @return boolean
	 */
	abstract public function delete($pk);

	/**
	 * @return boolean
	 */
	abstract public function isInsertMode();

	/**
	 * AbstractItem constructor.
	 * @param ConnectorInterface $connection
	 */
	public function __construct(ConnectorInterface $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @return ConnectorInterface
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * @param ConnectorInterface $connection
	 * @return AbstractItem
	 */
	public function setConnection($connection)
	{
		$this->connection = $connection;
		return $this;
	}


}