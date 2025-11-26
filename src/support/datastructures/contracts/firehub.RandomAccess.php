<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 7.0
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\DataStructures\Contracts;

use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
use FireHub\Core\Support\Contracts\ArrayAccessible;
use FireHub\Core\Support\Contracts\Magic\Overloadable;

/**
 * ### Sequential access data structure whose elements can be accessed directly by index or key
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends \FireHub\Core\Support\Contracts\HighLevel\DataStructures<TKey, TValue>
 * @extends \FireHub\Core\Support\Contracts\ArrayAccessible<TKey, TValue>
 */
interface RandomAccess extends DataStructures, ArrayAccessible, Overloadable {

    /**
     * ### Whether the key in the data structure exist
     * @since 1.0.0
     *
     * @param TKey $key <p>
     * Key to check for.
     * </p>
     *
     * @return bool True if the key in the data structure exist, false otherwise.
     */
    public function exist (mixed $key):bool;

    /**
     * ### Get item from data structure
     * @since 1.0.0
     *
     * @param TKey $key <p>
     * Data structure key.
     * </p>
     *
     * @return null|TValue Value for key.
     */
    public function get (mixed $key):mixed;

    /**
     * ### Get item from data structure or throw an error
     * @since 1.0.0
     *
     * @param TKey $key <p>
     * Data structure key.
     * </p>
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyDoesntExistException If the key doesn't exist in
     * the data structure.
     *
     * @return TValue Value for key.
     */
    public function take (mixed $key):mixed;

    /**
     * ### Adds or replaces item from data structure
     * @since 1.0.0
     *
     * @param TValue $value <p>
     * Data structure value.
     * </p>
     * @param TKey $key <p>
     * Data structure key.
     * </p>
     *
     * @return void
     */
    public function set (mixed $value, mixed $key):void;

    /**
     * ### Adds an item to data structure or throw an error
     * @since 1.0.0
     *
     * @param TValue $value <p>
     * Data structure value.
     * </p>
     * @param TKey $key <p>
     * Data structure key.
     * </p>
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyAlreadyExistException If the key already exists in
     * the data structure.
     *
     * @return void
     */
    public function add (mixed $value, mixed $key):void;

    /**
     * ### Replaces an item in the data structure or throw an error
     * @since 1.0.0
     *
     * @param TValue $value <p>
     * Data structure value.
     * </p>
     * @param TKey $key <p>
     * Data structure key.
     * </p>
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyDoesntExistException If the key doesn't exist in
     * the data structure.
     *
     * @return void
     */
    public function replace (mixed $value, mixed $key):void;

    /**
     * ### Removes an item from the data structure
     * @since 1.0.0
     *
     * @param TKey $key <p>
     * Data structure key.
     * </p>
     *
     * @return void
     */
    public function remove (mixed $key):void;

    /**
     * ### Deletes an item from the data structure or throw an error
     * @since 1.0.0
     *
     * @param TKey $key <p>
     * Data structure key.
     * </p>
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyDoesntExistException If the key doesn't exist in
     * the data structure.
     *
     * @return void
     */
    public function delete (mixed $key):void;

    /**
     * ### Pulls an item from the data structure or throw an error
     * @since 1.0.0
     *
     * @param TKey $key <p>
     * Data structure key.
     * </p>
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyDoesntExistException If the key doesn't exist in
     * the data structure.
     *
     * @return TValue Data structure value.
     */
    public function pull (mixed $key):mixed;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param TKey $name <p>
     * Key name.
     * </p>
     */
    public function __get (mixed $name):mixed;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param TKey $name <p>
     * Key name.
     * </p>
     * @param TValue $value <p>
     * Value to set.
     * </p>
     */
    public function __set (mixed $name, mixed $value):void;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param TKey $name <p>
     * Key name.
     * </p>
     */
    public function __isset (mixed $name):bool;

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @param TKey $name <p>
     * Key name.
     * </p>
     */
    public function __unset (mixed $name):void;

}