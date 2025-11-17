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

namespace FireHub\Core\Support\DataStructures\Traits;

use FireHub\Core\Support\LowLevel\Iterator;

/**
 * ### Enumerable data structure methods that every element meets a given criterion
 * @since 1.0.0
 *
 * @template-covariant TKey
 * @template-covariant TValue
 */
trait Enumerable {

    /**
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->count();
     *
     * // 6
     * </code>
     *
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Iterator::count() To count storage items.
     */
    public function count ():int {

        return Iterator::count($this);

    }

}