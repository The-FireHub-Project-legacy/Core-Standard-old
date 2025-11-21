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
use FireHub\Core\Support\DataStructures\Linear\Associative;
use FireHub\Core\Support\LowLevel\Arr;

/**
 * ### Combines the values of the data structure, as keys, with the values of another data structure
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\Contracts\HighLevel\DataStructures<mixed, array-key>
 */
readonly class Combine {

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
     * use FireHub\Core\Support\DataStructures\Function\Combine;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5]);
     * $collection2 = new Indexed(['one', 'two', 'three', 'four', 'five']);
     *
     * $combined = new Combine($collection)($collection2);
     *
     * // [1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::combine() To create an array by using one array for keys and
     * another for its values.
     * @uses \FireHub\Core\Support\Contracts\HighLevel\DataStructures::values() To get values from the data structure.
     * @uses \FireHub\Core\Support\Contracts\HighLevel\DataStructures::toArray() To convert data structure to array.
     *
     * @template TCombinedValue
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures<mixed, TCombinedValue> $data_structure <p>
     * Data structure to be used for values.
     * </p>
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\KeysAndValuesDiffNumberOfElemsException If arguments $keys and
     * $values don't have the same number of elements.
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Associative<value-of<TDataStructure>&array-key, TCombinedValue>
     * New combined data structure.
     */
    public function __invoke (DataStructures $data_structure):Associative {

        return new Associative(
            Arr::combine(
                $this->data_structure->values()->toArray(),
                $data_structure->values()->toArray()
            )
        );

    }

}