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
use FireHub\Core\Support\DataStructures\Linear\Associative;

/**
 * ### Separate data structure items that pass a given truth test from those that do not
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Filterable
 */
readonly class Partition {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Contracts\HighLevel\DataStructures As parameter.
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
     * use FireHub\Core\Support\DataStructures\Function\Reduce;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * [$richard, $others] = new Partition($collection)(fn($value) => $value === 'Richard');
     *
     * $richard->toArray();
     *
     * // ['Richard', 'Richard']
     *
     * $others->toArray();
     *
     * // ['John', 'Jane', 'Jane', 'Jane']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative As return.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Filterable::filter() To filter true values.
     * @uses \FireHub\Core\Support\DataStructures\Function\Reject To reject true values.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>=):(bool|\FireHub\Core\Support\Enums\ControlFlowSignal::BREAK) $callback <p>
     * Function to call on each item in a data structure.
     * Return **true** to add an item to the first result, **false** to add an item to the second result, or
     * `ControlFlowSignal::BREAK` to stop iteration early.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Associative<int, TDataStructure<key-of<TDataStructure>, value-of<TDataStructure>>>
     * New partitioned data structure.
     */
    public function __invoke (callable $callback):Associative {

        return new Associative([
            $this->data_structure->filter($callback),
            new Reject($this->data_structure)($callback)
        ]);


    }

}