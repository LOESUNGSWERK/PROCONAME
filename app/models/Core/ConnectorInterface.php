<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 08.08.17
 * Time: 23:21
 */

namespace LwModels\Core;


interface ConnectorInterface
{
	/**
	 * @param Options $options
	 * @return mixed
	 */
	public static function getInstance(Options $options);

	/**
	 * @param string $sql
	 * @param [] $data
	 * @return bool|\mysqli_result
	 */
	public function query($sql, $data);

	/**
	 * @return mixed
	 */
	public function getLastAutoIncId();

	/**
	 * @return Options
	 */
	public function getOptions();

	/**
	 * @param Options $options
	 */
	public function setOptions($options);
}