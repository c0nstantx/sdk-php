<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\Interfaces;

/**
 * Description of StorageInterface
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
interface StorageInterface
{
    /**
     * Save an element to storage
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function save($key, $value);

    /**
     * Find an element from storage
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function find($key);

    /**
     * Delete an element from storage
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function delete($key);
}