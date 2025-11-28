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

use FireHub\Core\Support\Contracts\HighLevel\DataStructures;

/**
 * ### Data structure that is capable of passing through a filter
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends \FireHub\Core\Support\Contracts\HighLevel\DataStructures<TKey, TValue>
 */
interface Filterable extends DataStructures {

    /**
     * ### Filter items from data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\ControlFlowSignal To force early break.
     *
     * @param callable(TValue, TKey=):(bool|\FireHub\Core\Support\Enums\ControlFlowSignal::BREAK) $callback <p>
     * Function to call on each item in a data structure.
     * Return **true** to keep the item, **false** to remove it, or `ControlFlowSignal::BREAK` to stop iteration early.
     * </p>
     *
     * @return static<TKey, TValue> New filtered data structure.
     */
    public function filter (callable $callback):static;

}