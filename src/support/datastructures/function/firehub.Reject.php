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
use FireHub\Core\Support\Enums\ControlFlowSignal;

/**
 * ### Reject items from data structure
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Filterable
 */
readonly class Reject {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Filterable As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structure.
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
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $reject = new Reject($collection)(fn($value, $key) => $value === 'Jane');
     *
     * // ['John', 'Richard', 'Richard']
     * </code>
     * You can force early break:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\Enums\ControlFlowSignal;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $reject = new Reject($collection)(function ($value, $key) {
     *     if ($value === 'Jane') return ControlFlowSignal::BREAK;
     *     return false;
     * });
     *
     * // ['John']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Filterable::filter() To filter items from
     * the data structure.
     * @uses \FireHub\Core\Support\Enums\ControlFlowSignal::BREAK To force early break.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>=):(bool|\FireHub\Core\Support\Enums\ControlFlowSignal::BREAK) $callback <p>
     * Function to call on each item in a data structure.
     * Return **true** to keep the item, **false** to remove it, or `ControlFlowSignal::BREAK` to stop iteration early.
     * </p>
     *
     * @return TDataStructure<value-of<TDataStructure>, key-of<TDataStructure>> New filtered data structure.
     */
    public function __invoke (callable $callback):Filterable {

        return $this->data_structure->filter(
            fn($value, $key = null) => ($result = $callback($value, $key)) === ControlFlowSignal::BREAK
                ? ControlFlowSignal::BREAK
                : !$result
        );

    }

}