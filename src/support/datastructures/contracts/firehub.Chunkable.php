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
use FireHub\Core\Support\DataStructures\Linear\Lazy;

/**
 * ### Data structure that is capable of being chunked into smaller pieces
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends \FireHub\Core\Support\Contracts\HighLevel\DataStructures<TKey, TValue>
 */
interface Chunkable extends DataStructures {

    /**
     * ### Split data structure into chunks by user function
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     * @uses \FireHub\Core\Support\Enums\ControlFlowSignal As signal.
     *
     * @param callable(TValue, TKey):(bool|\FireHub\Core\Support\Enums\ControlFlowSignal::BREAK) $callback <p>
     * Function to chunk by.
     * Return **true** to chunk at the current value, **false** to keep value at the current chunk,
     * or `ControlFlowSignal::BREAK` to stop iteration early.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, static<TKey, TValue>>
     * New chunked data structure.
     */
    public function chunk (callable $callback):Lazy;

}