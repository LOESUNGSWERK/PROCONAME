<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 11.08.17
 * Time: 21:50
 */

namespace LwModels\Core;


interface FactoryInterface
{
	/**
	 * @return EntityInterface
	 */
	public static function getEntity();

    /**
	 * @return RepositoryInterface
	 */
	public static function getRepository();

}