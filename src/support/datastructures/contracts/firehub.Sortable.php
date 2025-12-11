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

use FireHub\Core\Support\DataStructures\Operation\Sort;

/**
 * ### Sortable data structures can have their values sorted
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends \FireHub\Core\Support\DataStructures\Contracts\ArrStorage<TKey, TValue>
 */
interface Sortable extends ArrStorage {

    /**
     * ### Sort the data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Sort As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\Sort<$this>
     */
    public function sort ():Sort;

}