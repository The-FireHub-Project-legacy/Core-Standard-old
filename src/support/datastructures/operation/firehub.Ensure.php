<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.2
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\DataStructures\Operation;

use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
use FireHub\Core\Support\DataStructures\Contracts\ArrStorage;
use FireHub\Core\Support\LowLevel\Arr;

/**
 * ### Ensure operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\Contracts\HighLevel\DataStructures
 */
readonly class Ensure {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Contracts\HighLevel\DataStructures As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structure.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected DataStructures $data_structure
    ) {}

    /**
     * ### Verify that all items of a data structure pass a given truth test
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Contains;
     * use FireHub\Core\Support\LowLevel\DataIs;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $ensure = new Ensure($collection)->all(fn($value, $key) => DataIs::string($value));
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::all() To check all array elements satisfies a callback function.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback <p>
     * User-defined function.
     * </p>
     *
     * @return bool True if all items in the data structure pass a given truth test, false otherwise.
     */
    public function all (callable $callback):bool {

        if ($this->data_structure instanceof ArrStorage)
            return Arr::all($this->data_structure->toArray(), $callback);

        foreach ($this->data_structure as $storage_key => $storage_value)
            if ($callback($storage_value, $storage_key) === false) return false;

        return true;

    }

    /**
     * ### Verify that none of the items of a data structure passes a given truth test
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Contains;
     * use FireHub\Core\Support\LowLevel\DataIs;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $ensure = new Ensure($collection)->none(fn($value, $key) => DataIs::int($value));
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::any() To check if at least one array element satisfies a callback
     * function.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback <p>
     * User-defined function.
     * </p>
     *
     * @return bool True if none of the items in the data structure passes a given truth test, false otherwise.
     */
    public function none (callable $callback):bool {

        if ($this->data_structure instanceof ArrStorage)
            return !Arr::any($this->data_structure->toArray(), $callback);

        foreach ($this->data_structure as $storage_key => $storage_value)
            if ($callback($storage_value, $storage_key) === true) return false;

        return true;

    }

}