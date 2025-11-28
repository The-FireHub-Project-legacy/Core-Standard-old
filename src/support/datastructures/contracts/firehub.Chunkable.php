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

use FireHub\Core\Support\DataStructures\Operation\Chunk;

/**
 * ### Data structure that is capable of being chunked into smaller pieces
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends \FireHub\Core\Support\DataStructures\Contracts\ArrStorage<TKey, TValue>
 */
interface Chunkable extends ArrStorage {

    /**
     * ### Chunk operations for data structures
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Chunk As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\Chunk<$this> Chunk operation class.
     */
    public function chunk ():Chunk;

}