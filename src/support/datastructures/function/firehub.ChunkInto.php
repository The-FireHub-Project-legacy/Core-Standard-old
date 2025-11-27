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
 * ### Split the data structure into the given group size filling non-terminal groups first
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Chunkable
 */
readonly class ChunkInto {

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
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Function\ChunkInto;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $chunks = new ChunkInto($collection)(2);
     *
     * // [
     * //   [0, Associative(['firstname' => 'John', 'lastname' => 'Doe'])],
     * //   [1, Associative(['age' => 25, 10 => 2])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the maximum value from 1 and $size_of_group.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Chunkable::chunk() To chunk the data structure.
     *
     * @param positive-int $size_of_group <p>
     * Size of each group.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure<key-of<TDataStructure>,value-of<TDataStructure>>>
     * New chunked data structure.
     */
    public function __invoke (int $size_of_group):Lazy {

        $size_of_group = NumInt::max($size_of_group, 1);

        $counter = 0;

        return $this->data_structure->chunk(function() use (&$counter, $size_of_group) {

            return ++$counter % $size_of_group === 0;

        });

    }

}