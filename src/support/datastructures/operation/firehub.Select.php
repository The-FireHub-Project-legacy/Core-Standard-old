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

use FireHub\Core\Support\DataStructures\Contracts\ {
    ArrStorage, Selectable
};
use FireHub\Core\Support\DataStructures\Function\Slice;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use FireHub\Core\Support\LowLevel\ {
    Arr, Data, NumInt
};

use FireHub\Core\Support\Debug\ValueStringifier;

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
     * $select = new Select($collection)->first(3);
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
     * $select = new Select($collection)->last(3);
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

        return $this->data_structure->filter(
            fn($value, $key = null) => $callback($value, $key) === false ?: ControlFlowSignal::BREAK
        );

    }

    /**
     * ### Select while the given callback returns true
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $select = new Select($collection)->while(fn($value, $key) => $value !== 'Richard');
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
    public function while (callable $callback):Selectable {

        return $this->data_structure->filter(
            fn($value, $key = null) => !$callback($value, $key) ? ControlFlowSignal::BREAK : true
        );

    }

    /**
     * ### Select every n-th element
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $select = new Select($collection)->nth(2);
     *
     * // ['John', 'Jane', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Selectable::filter() To filter data structure items.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the higher values from $step and one.
     *
     * @param positive-int $step <p>
     * N-th step.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function nth (int $step):Selectable {

        return $this->data_structure->filter(function () use ($step, &$counter) {

            return ($counter++ % (NumInt::max(1, $step)) === 0);

        });

    }

    /**
     * ### Select even elements
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $select = new Select($collection)->even();
     *
     * // ['Jane', 'Jane', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Skip::first() To skip the first element.
     * @uses \FireHub\Core\Support\DataStructures\Operation\Select::nth() To skip every n-th element.
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function even ():Selectable {

        return new self(new Skip($this->data_structure)->first(1))->nth(2);

    }

    /**
     * ### Select odd elements
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $select = new Select($collection)->odd();
     *
     * // ['John', 'Jane', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Select::nth() To select every n-th element.
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function odd ():Selectable {

        return $this->nth(2);

    }

    /**
     * ### Select distinct key and value pair elements
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $select = new Select($collection)->unique();
     *
     * // ['John', 'Jane', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\Utils\Arr::unique() To removes duplicate values from an array if data structure
     * is ArrStorage type.
     * @uses \FireHub\Core\Support\LowLevel\Arr::inArray() To check if a value exists in an array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Selectable::filter() To filter items from the data
     * structure.
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function unique ():Selectable {

        if ($this->data_structure instanceof ArrStorage)
            return new $this->data_structure(Arr::unique($this->data_structure->toArray()));

        $map = [];
        return $this->data_structure->filter(function ($value, $key = null) use (&$map) {

            if (!Arr::inArray($value, $map)) {

                $map[] = $value;

                return true;

            }

            return false;

        });

    }

    /**
     * ### Select distinct key and value pair elements
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $select = new Select($collection)->distinct();
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Selectable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\LowLevel\Data::serialize() To create a hash from a key and value pair.
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function distinct ():Selectable {

        $map = [];
        return $this->data_structure->filter(function ($value, $key = null) use (&$map) {

            $hash = Data::serialize([$key, $value]);

            if (!isset($map[$hash])) $map[$hash] = true;

            return true;

        });

    }

    /**
     * ### Select duplicated elements
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $select = new Select($collection)->duplicates();
     *
     * // ['Jane', 'Jane', 'Jane', 'Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\CountBy::values() To count elements by values.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Selectable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::get() To get value from an associative array.
     * @uses \FireHub\Core\Support\Debug\ValueStringifier::stringify() To stringify value.
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function duplicates ():Selectable {

        $map = $this->data_structure->countBy()->values();

        return $this->data_structure->filter(fn($value) => $map->get(new ValueStringifier()->stringify($value)) > 1);

    }

    /**
     * ### Select items with specific keys
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $select = new Select($collection)->only(['lastname']);
     *
     * // ['lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Selectable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\LowLevel\Arr::inArray() To check if a value exists in an array.
     *
     * @param list<key-of<TDataStructure>> $keys <p>
     * List of keys to filter.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function only (array $keys):Selectable {

        return $this->data_structure->filter(fn($value, $key = null) => Arr::inArray($key, $keys));

    }

    /**
     * ### Select items without specific keys
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Select;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $select = new Select($collection)->except(['lastname']);
     *
     * // ['firstname' => 'John', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Selectable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\LowLevel\Arr::inArray() To check if a value exists in an array.
     *
     * @param list<key-of<TDataStructure>> $keys <p>
     * List of keys to filter.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Contracts\Selectable<key-of<TDataStructure>, value-of<TDataStructure>>
     * New data structure with a selected number of items.
     */
    public function except (array $keys):Selectable {

        return $this->data_structure->filter(fn($value, $key = null) => !Arr::inArray($key, $keys));

    }

}