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

namespace FireHub\Core\Support\DataStructures\Operation;

use FireHub\Core\Support\DataStructures\Contracts\Selectable;
use FireHub\Core\Support\DataStructures\Function\Slice;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use FireHub\Core\Support\LowLevel\NumInt;

/**
 * ### Select operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Selectable
 */
readonly class Select {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Selectable As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structure.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected Selectable $data_structure
    ) {}

    /**
     * ### Select first n items from the data structure
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $chunk = new Select($collection)->first(3);
     *
     * // ['John', 'Jane', 'Jane']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Function\Slice To slice a part of the data structure.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the higher values from $number_of_items and zero.
     *
     * @return TDataStructure New data structure with a selected number of items.
     */
    public function first (int $number_of_items):Selectable {

        /** @var TDataStructure */
        return new Slice($this->data_structure)(0, NumInt::max(0, $number_of_items));

    }

    /**
     * ### Select last n items from the data structure
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $chunk = new Select($collection)->last(3);
     *
     * // ['Jane', 'Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Function\Slice To slice a part of the data structure.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the higher values from $number_of_items and zero.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::min() To get the lower values from $number_of_items and zero.
     *
     * @return TDataStructure New data structure with a selected number of items.
     */
    public function last (int $number_of_items):Selectable {

        /** @var TDataStructure */
        return new Slice($this->data_structure)(NumInt::min(0,  -$number_of_items), NumInt::max(0, $number_of_items));

    }

    /**
     * ### Select until the given callback returns true
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $chunk = new Select($collection)->until(fn($value, $key) => $value === 'Richard');
     *
     * // ['John', 'Jane', 'Jane', 'Jane']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Selectable::filter() To filter data structure items.
     * @uses \FireHub\Core\Support\Enums\ControlFlowSignal::BREAK To force early break.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>=):bool $callback <p>
     * Function to call on each item in a data structure.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function until (callable $callback):Selectable {
        return $this->data_structure->filter(fn($value, $key = null) => $callback($value, $key) === false ?:
            ControlFlowSignal::BREAK);

    }

}