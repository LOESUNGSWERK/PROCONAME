<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 09.08.17
 * Time: 00:06
 */

namespace LwModels\Core;

use LwCore\Pattern\HydratorInterface;

class HydrateOptions implements HydratorInterface
{

	/**
	 * Extract values from an object
	 *
	 * @param  Options $object
	 * @return array
	 */
	public function extract($object)
	{

	}

	/**
	 * Hydrate $object with the provided $data.
	 *
	 * @param  array $data
	 * @param  Options $object
	 * @return void
	 */
	public function hydrate(array $data, $object)
	{
		$object->setServer($data['LW_DB_SERVER']);
		$object->setPort($data['LW_DB_PORT']);
		$object->setDatabase($data['LW_DB_NAME']);
		$object->setUser($data['LW_DB_LOGIN']);
		$object->setPassword($data['LW_DB_PW']);
	}
}