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

use FireHub\Core\Support\Contracts\HighLevel\DataStructures;

/**
 * ### Data structure whose items can be reversed
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends \FireHub\Core\Support\Contracts\HighLevel\DataStructures<TKey, TValue>
 */
interface Reversible extends DataStructures {

    /**
     * ### Reverse items from data structure
     * @since 1.0.0
     *
     * @return static<TKey, TValue> New data structure with reversed items.
     */
    public function reverse ():static;

    /**
     * ### Reverse items in-place in the data structure
     * @since 1.0.0
     *
     * @return $this Data structure with reversed items.
     */
    public function reverseInPlace ():static;

}