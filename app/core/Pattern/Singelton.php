<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 14.07.17
 * Time: 00:01
 */

namespace LwCore\Pattern;


abstract class Singelton
{

	private static $instances = array();

	protected function __construct()
	{

	}

	protected function __clone()
	{

	}

	public function __wakeup()
	{
		throw new Exception("Cannot unserialize singleton");
	}

	/**
	 * @param string $id
	 * @return mixed
	 */
	protected static function _getInstance($id='1')
	{
		$cls = get_called_class(); // late-static-bound class name
		if (!isset(self::$instances[$cls][$id])) {
			self::$instances[$cls][$id] = new static;
		}
		return self::$instances[$cls][$id];
	}

}