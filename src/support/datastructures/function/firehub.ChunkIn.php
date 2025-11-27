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

use FireHub\Core\Support\DataStructures\Contracts\Chunkable;
use FireHub\Core\Support\DataStructures\Linear\Lazy;
use FireHub\Core\Support\LowLevel\NumInt;

/**
 * ### Split the data structure into the given number of groups filling non-terminal groups first
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Chunkable
 */
readonly class ChunkIn {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Chunkable As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structures.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected Chunkable $data_structure
    ) {}

    /**
     * ### Call an object as a function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Function\ChunkIn;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
     *
     * $chunks = new ChunkIn($collection)(4);
     *
     * // [
     * //   [0, Indexed([1, 2, 3, 4])],
     * //   [1, Indexed([5, 6, 7, 8])],
     * //   [1, Indexed([9, 10])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the maximum value from 1 and $size_of_group.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::ceil() To round fractions for the number of elements in a group
     * divided by number of groups.
     * @uses \FireHub\Core\Support\DataStructures\Function\ChunkInto As a chunking function.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Chunkable::chunk() To count the number of elements in
     * a data structure.
     *
     * @param positive-int $number_of_groups <p>
     * Number of groups.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure<key-of<TDataStructure>,value-of<TDataStructure>>>
     * New chunked data structure.
     */
    public function __invoke (int $number_of_groups):Lazy {

        return new ChunkInto($this->data_structure)(
            NumInt::max(NumInt::ceil($this->data_structure->count() / $number_of_groups), 1)
        );

    }

}