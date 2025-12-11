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

namespace FireHub\Core\Support\DataStructures\Function;

use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
use FireHub\Core\Support\DataStructures\Contracts\ArrStorage;
use FireHub\Core\Support\LowLevel\Arr;

/**
 * ### Reduces the data structure to a single value, passing the result of each iteration into the later iteration
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\Contracts\HighLevel\DataStructures
 */
readonly class Reduce {

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
     * ### Call an object as a function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Function\Reduce;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $values = new Reduce($collection)(fn($carry, $value) => $carry.'-'.$value);
     *
     * // '-John-Jane-Jane-Jane-Richard-Richard'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::reduce() To use as a reduce function.
     *
     * @template TReturn
     *
     * @param callable(null|TReturn, value-of<TDataStructure>):TReturn $callback <p>
     * The callable function.
     * </p>
     * @param null|TReturn $initial [optional] <p>
     * If the optional initial is available, it will be used at the beginning of the process,
     * or as a final result in case the array is empty.
     * </p>
     *
     * @return null|TReturn Invoke result.
     */
    public function __invoke (callable $callback, mixed $initial = null):mixed {

        if ($this->data_structure instanceof ArrStorage)
            return Arr::reduce($this->data_structure->toArray(), $callback, $initial);

        foreach ($this->data_structure as $value)
            $initial = $callback($initial, $value);

        return $initial;

    }

}