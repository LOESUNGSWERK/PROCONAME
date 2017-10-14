<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 09.08.17
 * Time: 00:07
 */

namespace LwCore\Pattern;

interface HydratorInterface
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object);

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return void
     */
    public function hydrate(array $data, $object);

}