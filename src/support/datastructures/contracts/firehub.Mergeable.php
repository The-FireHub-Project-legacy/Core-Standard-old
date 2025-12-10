<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.0
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\DataStructures\Contracts;

use FireHub\Core\Support\Contracts\HighLevel\DataStructures\Linear;
use FireHub\Core\Support\DataStructures\Operation\SetOperation;

/**
 * ### Data structure that can be merged with other linear data structures
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends \FireHub\Core\Support\DataStructures\Contracts\Filterable<TKey, TValue>
 */
interface Mergeable extends Filterable {

    /**
     * ### Merge a data structure with another data structure
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures\Linear<TKey, TValue> ...$data_structures <p>
     * Data structures to merge with.
     * </p>
     *
     * @return static<TKey, TValue> New merged data structure.
     */
    public function union (Linear ...$data_structures):static;

    /**
     * ### Set operation of the data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\SetOperation As return.
     *
     * @param self<TKey, TValue> $compare <p>
     * Instance of data structure to exclude from the data structure.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\SetOperation<$this, self<TKey, TValue>>
     */
    public function setOperation (self $compare):SetOperation;

}