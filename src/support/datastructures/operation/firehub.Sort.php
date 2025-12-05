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

use FireHub\Core\Support\DataStructures\Contracts\Sortable;
use FireHub\Core\Support\Enums\ {
    Order as OrderEnum, Sort as SortEnum
};
use FireHub\Core\Support\LowLevel\Arr;

/**
 * ### Sort operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Sortable
 */
readonly class Sort {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structure.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected Sortable $data_structure
    ) {}

    /**
     * ### Sort the data structure in ascending order
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Sort;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $contains = new Sort($collection)->ascending();
     *
     * // ['Jane', 'Jane', 'Jane', 'John', 'Richard', 'Richard']
     * </code>
     * You can change the sorting behavior by providing a different sort flag:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Sort;
     * use FireHub\Core\Support\Enums\Sort as SortEnum;
     *
     * $collection = new Indexed([1, 2, 3, 4, 13, 22, 27, 28, 29, 50]);
     *
     * $contains = new Sort($collection)->ascending(SortEnum::BY_STRING);
     *
     * // [1, 13, 2, 22, 27, 28, 29, 3, 4, 50]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::fromArray() To create new array storage
     * data structure from a sorted array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sort() To sort an array.
     * @uses \FireHub\Core\Support\Enums\Order::ASC To specify ascending order.
     *
     * @return TDataStructure New data structure sorted in ascending order.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public function ascending (SortEnum $flag = SortEnum::BY_REGULAR):Sortable {

        $storage = $this->data_structure->toArray();

        Arr::sort($storage, OrderEnum::ASC, $flag, true);

        return $this->data_structure::fromArray($storage);

    }

    /**
     * ### Sort the data structure in descending order
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Sort;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $contains = new Sort($collection)->descending();
     *
     * // ['Richard', 'Richard', 'Jane', 'Jane', 'Jane', 'John']
     * </code>
     * You can change the sorting behavior by providing a different sort flag:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Sort;
     * use FireHub\Core\Support\Enums\Sort as SortEnum;
     *
     * $collection = new Indexed([1, 2, 3, 4, 13, 22, 27, 28, 29, 50]);
     *
     * $contains = new Sort($collection)->descending(SortEnum::BY_STRING);
     *
     * // [50, 4, 3, 29, 28, 27, 22, 2, 13, 1]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::fromArray() To create new array storage
     * data structure from a sorted array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sort() To sort an array.
     * @uses \FireHub\Core\Support\Enums\Order::DESC To specify descending order.
     *
     * @return TDataStructure New data structure sorted in descending order.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public function descending (SortEnum $flag = SortEnum::BY_REGULAR):Sortable {

        $storage = $this->data_structure->toArray();

        Arr::sort($storage, OrderEnum::DESC, $flag, true);

        return $this->data_structure::fromArray($storage);

    }

    /**
     * ### Sort the data structure with a user-defined comparison function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Sort;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $contains = new Sort($collection)->by(fn($current, $next) => $current <=> $next);
     *
     * // ['Jane', 'Jane', 'Jane', 'John', 'Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::fromArray() To create new array storage
     * data structure from a sorted array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sortBy() To sort an array by a user-defined comparison function.
     *
     * @param callable(value-of<TDataStructure>, value-of<TDataStructure>):int<-1, 1> $callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New sorted data structure.
     *
     * @caution Returning non-integer values from the comparison function, such as float, will result in an internal
     * cast to int of the callback's return value.
     * So values such as 0.99 and 0.1 will both be cast to an integer value of 0, which will compare such values as
     * equal.
     */
    public function by (callable $callback):Sortable {

        $storage = $this->data_structure->toArray();

        Arr::sortBy($storage, $callback, true);

        return $this->data_structure::fromArray($storage);

    }

}