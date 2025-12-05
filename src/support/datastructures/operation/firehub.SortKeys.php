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

use FireHub\Core\Support\DataStructures\Contracts\KeySortable;
use FireHub\Core\Support\Enums\ {
    Order as OrderEnum, Sort as SortEnum
};
use FireHub\Core\Support\LowLevel\Arr;

/**
 * ### Sort keys operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\KeySortable
 */
readonly class SortKeys {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\KeySortable As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structure.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected KeySortable $data_structure
    ) {}

    /**
     * ### Sort key of the data structure in ascending order
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SortKeys;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $contains = new SortKeys($collection)->ascending();
     *
     * // [10 => 2, 'age' => 25, 'firstname' => 'John', 'lastname' => 'Doe']
     * </code>
     * You can change the sorting behavior by providing a different sort flag:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SortKeys;
     * use FireHub\Core\Support\Enums\Sort as SortEnum;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $contains = new SortKeys($collection)->ascending(SortEnum::BY_NUMERIC);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::fromArray() To create new array storage
     * data structure from a sorted array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sortByKeys() To sort an array by key.
     * @uses \FireHub\Core\Support\Enums\Order::ASC To specify ascending order.
     *
     * @return TDataStructure New data structure keys sorted in ascending order.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public function ascending (SortEnum $flag = SortEnum::BY_REGULAR):KeySortable {

        $storage = $this->data_structure->toArray();

        Arr::sortByKeys($storage, OrderEnum::ASC, $flag);

        return $this->data_structure::fromArray($storage);

    }

    /**
     * ### Sort key of the data structure in descending order
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SortKeys;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $contains = new SortKeys($collection)->descending();
     *
     * // ['lastname' => 'Doe', 'firstname' => 'John', 'age' => 25, 10 => 2]
     * </code>
     * You can change the sorting behavior by providing a different sort flag:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SortKeys;
     * use FireHub\Core\Support\Enums\Sort as SortEnum;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $contains = new SortKeys($collection)->descending(SortEnum::BY_NUMERIC);
     *
     * // [10 => 2, 'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::fromArray() To create new array storage
     * data structure from a sorted array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sortByKeys() To sort an array by key.
     * @uses \FireHub\Core\Support\Enums\Order::DESC To specify descending order.
     *
     * @return TDataStructure New data structure keys sorted in descending order.
     *
     * @note Resets array's internal pointer to the first element.
     */
    public function descending (SortEnum $flag = SortEnum::BY_REGULAR):KeySortable {

        $storage = $this->data_structure->toArray();

        Arr::sortByKeys($storage, OrderEnum::DESC, $flag);

        return $this->data_structure::fromArray($storage);

    }

    /**
     * ### Sort keys of the data structure with a user-defined comparison function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SortKeys;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $contains = new SortKeys($collection)->by(fn($current, $next) => $current <=> $next);
     *
     * // [10 => 2, 'age' => 25, 'firstname' => 'John', 'lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Sortable::fromArray() To create new array storage
     * data structure from a sorted array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::sortKeysBy() To sort keys in an array by a user-defined comparison
     * function.
     *
     * @param callable(key-of<TDataStructure>, key-of<TDataStructure>):int<-1, 1> $callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New sorted data structure.
     */
    public function by (callable $callback):KeySortable {

        $storage = $this->data_structure->toArray();

        Arr::sortKeysBy($storage, $callback);

        return $this->data_structure::fromArray($storage);

    }

}