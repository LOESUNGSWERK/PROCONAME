<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 11.08.17
 * Time: 22:20
 */

namespace LwModels\Core;

abstract class AbstractRepository implements \Iterator
{
	/**
	 * AbstractItem constructor.
	 * @param ConnectorInterface $connection
	 */
	public function __construct(ConnectorInterface $connection)
	{
		$this->connection = $connection;
	}

}