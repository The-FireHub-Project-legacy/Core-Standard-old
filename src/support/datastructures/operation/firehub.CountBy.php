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

use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
use FireHub\Core\Support\DataStructures\Linear\Associative;
use FireHub\Core\Support\Debug\ValueStringifier;
use FireHub\Core\Support\Enums\ {
    Data\Type, Status\Key, Status\Value
};
use FireHub\Core\Support\LowLevel\ {
    Data, DataIs
};

/**
 * ### Count operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\Contracts\HighLevel\DataStructures
 */
readonly class CountBy {

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
        protected DataStructures $data_structure
    ) {}

    /**
     * ### Count elements by searched value
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\CountBy;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $count = new CountBy($collection)->value('Jane');
     *
     * // 3
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\CountBy::where To count elements by a user-defined function.
     * @uses \FireHub\Core\Support\Enums\Status\Key|\FireHub\Core\Support\Enums\Status\Value As mark value enum.
     *
     * @param value-of<TDataStructure> $search <p>
     * The searched key.
     * If the key is a string, the comparison is done in a case-sensitive manner.
     * </p>
     *
     * @return non-negative-int Number of elements in the data structure by searched value.
     */
    public function value (mixed $search):int {

        $result = $this->where(fn($value) => $value === $search);

        return !($result[1] instanceOf Key) && !($result[1] instanceOf Value) ? $result[1] : 0;

    }

    /**
     * ### Count elements by searched type
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\CountBy;
     * use FireHub\Core\Support\Enums\Data\Type;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $count = new CountBy($collection)->type(Type::T_STRING);
     *
     * // 2
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Data\Type As parameter.
     * @uses \FireHub\Core\Support\DataStructures\Operation\CountBy::where To count elements by a user-defined function.
     * @uses \FireHub\Core\Support\Enums\Status\Key|\FireHub\Core\Support\Enums\Status\Value As mark value enum.
     *
     * @param \FireHub\Core\Support\Enums\Data\Type $search <p>
     * The searched typed.
     * </p>
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\TypeUnknownException If a type of value is unknown.
     *
     * @return non-negative-int Number of elements in the data structure by searched type.
     */
    public function type (Type $search):int {

        $result = $this->where(fn($value) => Data::getType($value) === $search);

        return !($result[1] instanceOf Key) && !($result[1] instanceOf Value) ? $result[1] : 0;

    }

    /**
     * ### Count elements by values
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\CountBy;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $count = new CountBy($collection)->values();
     *
     * // ['Jane' => 3, 'John' => 1, 'Richard' => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative As return.
     * @uses \FireHub\Core\Support\DataStructures\Operation\CountBy::where To count elements by a user-defined function.
     * @uses \FireHub\Core\Support\Debug\ValueStringifier::stringify() To convert scalar to value-
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Associative<array-key, positive-int> Number of elements of
     * a data structure by values.
     */
    public function values ():Associative {

        return $this->where(
            fn($value) => DataIs::string($value) || DataIs::int($value)
                ? $value
                : new ValueStringifier()->stringify($value)
        );

    }

    /**
     * ### Count elements by user-defined function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\CountBy;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $count = new CountBy($collection)->where(fn($value, $key) => substr((string)$value, 0, 1));
     *
     * // ['J' => 4, 'R' => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative As return.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):(array-key|bool) $callback <p>
     * User-defined function.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Associative<array-key, positive-int> Number of elements of
     * a data structure by user-defined function.
     */
    public function where (callable $callback):Associative {

        $storage = [];

        foreach ($this->data_structure as $key => $value) {

            $callable = $callback($value, $key);

            $storage[$callable] = ($storage[$callable] ?? 0) + 1;

        }

        return new Associative($storage);

    }

}