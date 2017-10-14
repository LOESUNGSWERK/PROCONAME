<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 08.08.17
 * Time: 23:22
 */

namespace LwModels\Core;

use mysqli;

class MysqlConnector extends \LwCore\Pattern\Singelton implements ConnectorInterface
{

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @var mysqli;
	 */
	private $connection;

	/**
	 * @param Options $options
	 * @return  MysqlConnector
	 */
	public static function getInstance(Options $options)
	{
		/** @var MysqlConnector $instance */
		$instance = parent::_getInstance($options->getId());
		$instance->setOptions($options);
		return $instance;
	}

	/**
	 * @param $sql
	 * @return bool|\mysqli_result
	 */
	public function query($sql, $data = null)
	{
		$sql = $this->fillQueryWithData($sql, $data);
		return $this->getMySqlI()->query($sql);
	}

	/**
	 * @param string $sql
	 * @param [] $data
	 * @return string
	 */
	private function fillQueryWithData($sql, $data)
	{
		$return = $sql;
		if (!empty($data)) {
			foreach ($data as $needle => $replace) {
				$return = str_replace('{{' . $needle . '}}', $replace, $return);
			}
		}
		return $return;
	}

	/**
	 * @return mixed
	 */
	public function getLastAutoIncId()
	{
		return $this->getMySqlI()->insert_id;
	}

	/**
	 * @return mysqli
	 * @throws Exception
	 */
	private function getMySqlI()
	{
		if (null === $this->connection) {
			$this->connection = new mysqli(
				$this->options->getServer(),
				$this->options->getUser(),
				$this->options->getPassword(),
				$this->options->getDatabase(),
				$this->options->getPort()
			);
			if ($this->connection->connect_error) {
				throw new Exception($this->connection->connect_error, $this->connection->connect_errno);
			}
			if (!$this->connection->set_charset("utf8")) {
				throw new Exception($this->connection->error, $this->connection->errno);
			}
		}
		return $this->connection;
	}

	/**
	 * @return Options
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @param Options $options
	 */
	public function setOptions($options)
	{
		$this->options = $options;
	}


}