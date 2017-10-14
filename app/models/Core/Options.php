<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 08.08.17
 * Time: 23:23
 */

namespace LwModels\Core;


class Options
{

	/**
	 * @var string
	 */
	private $server;

	/**
	 * @var string
	 */
	private $database;

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var integer
	 */
	private $port;


	public function getId()
	{
		return md5(
			$this->getServer() .
			$this->getPort() .
			$this->getUser() .
			$this->getPassword() .
			$this->getDatabase()
		);
	}

	/**
	 * @return string
	 */
	public function getServer()
	{
		if (null === $this->server) {
			$this->server = LW_DB_SERVER;
		}
		return $this->server;
	}

	/**
	 * @param string $server
	 */
	public function setServer($server)
	{
		$this->server = $server;
	}

	/**
	 * @return string
	 */
	public function getDatabase()
	{
		if (null === $this->database) {
			$this->database = LW_DB_NAME;
		}
		return $this->database;
	}

	/**
	 * @param string $database
	 */
	public function setDatabase($database)
	{
		$this->database = $database;
	}

	/**
	 * @return string
	 */
	public function getUser()
	{
		if (null === $this->user) {
			$this->user = LW_DB_LOGIN;
		}
		return $this->user;
	}

	/**
	 * @param string $user
	 */
	public function setUser($user)
	{

		$this->user = $user;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		if (null === $this->password) {
			$this->password = LW_DB_PW;
		}
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return integer
	 */
	public function getPort()
	{
		if (null === $this->port) {
			$this->port = LW_DB_PORT;
		}
		return $this->port;
	}

	/**
	 * @param integer $port
	 */
	public function setPort($port)
	{
		$this->port = $port;
	}

}