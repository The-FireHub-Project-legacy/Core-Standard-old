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

use FireHub\Core\Support\DataStructures\Contracts\Filterable;
use FireHub\Core\Support\DataStructures\Contracts\ArrStorage;
use FireHub\Core\Support\DataStructures\Helpers\SequenceRange;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use FireHub\Core\Support\LowLevel\Arr;

/**
 * ### Get slice from data structure
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Filterable
 */
readonly class Slice {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Filterable As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structures.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected Filterable $data_structure
    ) {}

    /**
     * ### Call an object as a function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Function\Slice;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $values = new Slice($collection)(2);
     *
     * // ['Jane', 'Jane', 'Richard', 'Richard']
     * </code>
     * You can limit the number of results:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Function\Slice;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $values = new Slice($collection)(2, 2);
     *
     * // ['Jane', 'Jane']
     * </code>
     * You can put offset as negative:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Function\Slice;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $values = new Slice($collection)(-2, 3);
     *
     * // ['Richard', 'Richard']
     * </code>
     * You can put length as negative:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Function\Slice;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $values = new Slice($collection)(1, -1);
     *
     * // ['Jane', 'Jane', 'Jane', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create new array storage
     * data structure from a chunked array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::slice() To use as a slice function.
     * @uses \FireHub\Core\Support\Enums\ControlFlowSignal::BREAK To break the filtering loop.
     * @uses \FireHub\Core\Support\DataStructures\Helpers\SequenceRange::start() To get start index.
     * @uses \FireHub\Core\Support\DataStructures\Helpers\SequenceRange::end() To get end index.
     *
     * @param int $offset <p>
     * If the offset is non-negative, the sequence will start at that offset of the data structure.
     * If the offset is negative, the sequence will start that far from the end of the data structure.
     * </p>
     * @param null|int $length [optional] <p>
     * If length is given and is positive, then the sequence will have that many elements in it.
     * If length is given and is negative, then the sequence will stop that many elements from the end of the array.
     * If it is omitted, then the sequence will have everything from offset up until the end of the array.
     * </p>
     *
     * @return TDataStructure<key-of<TDataStructure>, value-of<TDataStructure>> New sliced data structure.
     */
    public function __invoke (int $offset, ?int $length = null):Filterable {

        if ($this->data_structure instanceof ArrStorage) {

            $storage = Arr::slice(
                $this->data_structure->toArray(),
                $offset,
                $length,
                true
            );

            return $this->data_structure::fromArray($storage);

        }

        $range = new SequenceRange($this->data_structure, $offset, $length);
        $start = $range->start();
        $end = $range->end();
        $position = 0;

        return $this->data_structure->filter(function () use ($start, $end, &$position) {

            if ($position++ < $start) return false;
            if ($position > $end) return ControlFlowSignal::BREAK;

            return true;

        });

    }

}