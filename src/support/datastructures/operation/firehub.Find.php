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
use FireHub\Core\Support\DataStructures\Contracts\ArrStorage;
use FireHub\Core\Support\Enums\Status\ {
    Key, Value
};
use FireHub\Core\Support\LowLevel\Arr;

/**
 * ### Find operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\Contracts\HighLevel\DataStructures
 */
readonly class Find {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Contracts\HighLevel\DataStructures As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structures.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected DataStructures $data_structure
    ) {}

    /**
     * ### Searches the data structure for a given value and returns the first corresponding key if successful
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->key('Doe');
     *
     * // 'lastname'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::search() As ArrayableStorage method.
     * @uses \FireHub\Core\Support\DataStructures\Operation\Find::firstKey() To search for the first key satisfying
     * a callback function.
     * @uses \FireHub\Core\Support\Enums\Status\Key::NONE To represent a non-existing key.
     *
     * @param value-of<TDataStructure> $for_value <p>
     * The searched value.
     * If the value is a string, the comparison is done in a case-sensitive manner.
     * </p>
     *
     * @return key-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Key::NONE The key if it is found in the data
     * structure, Key::NONE otherwise. If a value is found in a data structure more than once, the first matching key
     * is returned.
     */
    public function key (mixed $for_value):mixed {

        if ($this->data_structure instanceof ArrStorage) {

            $result = Arr::search($this->data_structure->toArray(), $for_value);

            return $result !== false ? $result : Key::NONE;

        }

        return $this->firstKey(fn($value, $key) => $value === $for_value);

    }

    /**
     * ### Searches the data structure for a given key and returns the first corresponding value if successful
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->value('lastname');
     *
     * // 'Doe'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Find::first() To search for the first value satisfying
     * a callback function.
     *
     * @param key-of<TDataStructure> $for_key <p>
     * The searched key.
     * If the key is a string, the comparison is done in a case-sensitive manner.
     * </p>
     *
     * @return value-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Value::NONE The value if it is found in the
     * data structure, Value::NONE otherwise. If a key is found in a data structure more than once, the first matching
     * value is returned.
     */
    public function value (mixed $for_key):mixed {

        return $this->first(fn($value, $key) => $key === $for_key);

    }

    /**
     * ### Searches for the first value satisfying a callback function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->first(fn($value, $key) => $key !== 'firstname');
     *
     * // 'John'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::find() As ArrayableStorage method.
     * @uses \FireHub\Core\Support\Enums\Status\Value::NONE To represent a non-existing key.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback <p>
     * The callback function to call to check each element.
     * </p>
     *
     * @return value-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Value::NONE The value if it is found in the
     * data structure, Value::NONE otherwise. If a callback is valid in a data structure more than once, the first
     * matching value is returned.
     */
    public function first (callable $callback):mixed {

        if ($this->data_structure instanceof ArrStorage)
            return Arr::find($this->data_structure->toArray(), $callback) ?? Value::NONE;

        foreach ($this->data_structure as $storage_key => $storage_value)
            if ($callback($storage_value, $storage_key)) return $storage_value;

        return Value::NONE;

    }

    /**
     * ### Searches for the first key satisfying a callback function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->firstKey(fn($value, $key) => $value !== 'John');
     *
     * // 'lastname'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::findKey() As ArrayableStorage method.
     * @uses \FireHub\Core\Support\Enums\Status\Key::NONE To represent a non-existing key.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback <p>
     * The callback function to call to check each element.
     * </p>
     *
     * @return key-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Key::NONE The key if it is found in the data
     * structure, Key::NONE otherwise. If a callback is valid in a data structure more than once, the first matching
     * key is returned.
     */
    public function firstKey (callable $callback):mixed {

        if ($this->data_structure instanceof ArrStorage)
            return Arr::findKey($this->data_structure->toArray(), $callback) ?? Key::NONE;

        foreach ($this->data_structure as $storage_key => $storage_value)
            if ($callback($storage_value, $storage_key)) return $storage_key;

        return Key::NONE;

    }

    /**
     * ### Searches for the last value satisfying a callback function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->last(fn($value, $key) => $key !== 10);
     *
     * // 25
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Status\Value::NONE To represent a non-existing key.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback <p>
     * The callback function to call to check each element.
     * </p>
     *
     * @return value-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Value::NONE The value if it is found in the
     * data structure, Value::NONE otherwise. If a callback is valid in a data structure more than once, the last
     * matching value is returned.
     */
    public function last (callable $callback):mixed {

        $result = null;
        foreach ($this->data_structure as $storage_key => $storage_value)
            if ($callback($storage_value, $storage_key)) $result = $storage_value;

        return $result ?? Value::NONE;

    }

    /**
     * ### Searches for the last key satisfying a callback function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->lastKey(fn($value, $key) => $value !== 2);
     *
     * // 'age'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Status\Key::NONE To represent a non-existing key.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback <p>
     * The callback function to call to check each element.
     * </p>
     *
     * @return key-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Key::NONE The key if it is found in the data
     * structure, Key::NONE otherwise. If a callback is valid in a data structure more than once, the last matching
     * key is returned.
     */
    public function lastKey (callable $callback):mixed {

        $result = null;
        foreach ($this->data_structure as $storage_key => $storage_value)
            if ($callback($storage_value, $storage_key)) $result = $storage_key;

        return $result ?? Key::NONE;

    }

    /**
     * ### Searches for the first value before satisfying a callback function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->before('Doe');
     *
     * // 'John'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Find::beforeWhere() To search for the first value before
     * satisfying a callback function.
     *
     * @param value-of<TDataStructure> $search <p>
     * The searched value.
     * If the value is a string, the comparison is done in a case-sensitive manner.
     * </p>
     *
     * @return value-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Value::NONE The value after if it is found
     * in the data structure, Value::NONE otherwise. If a callback is valid in a data structure more than before,
     * the first matching value is returned.
     */
    public function before (mixed $search):mixed {

        return $this->beforeWhere(fn($value) => $search === $value);

    }

    /**
     * ### Searches for the first value before satisfying a callback function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->beforeWhere(fn($value, $key) => $value === 'Doe');
     *
     * // 'John'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Status\Value::NONE To represent a non-existing key.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback <p>
     * The callback function to call to check each element.
     * </p>
     *
     * @return value-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Value::NONE The value before if it is
     * found in the data structure, Value::NONE otherwise. If a callback is valid in a data structure more than once,
     * the first matching value is returned.
     */
    public function beforeWhere (callable $callback):mixed {

        $found = Value::NONE; $matched = false;
        foreach ($this->data_structure as $storage_key => $storage_value) {

            if ($callback($storage_value, $storage_key)) {

                $matched = true;
                break;

            }

            $found = $storage_value;

        }

        return $matched ? $found : Value::NONE;

    }

    /**
     * ### Searches for the first value after satisfying a callback function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->after('Doe');
     *
     * // 25
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Find::afterWhere() To search for the first value after
     * satisfying a callback function.
     *
     * @param value-of<TDataStructure> $search <p>
     * The searched value.
     * If the value is a string, the comparison is done in a case-sensitive manner.
     * </p>
     *
     * @return value-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Value::NONE The value after if it is found
     * in the data structure, Value::NONE otherwise. If a callback is valid in a data structure more than after,
     * the first matching value is returned.
     */
    public function after (mixed $search):mixed {

        return $this->afterWhere(fn($value) => $search === $value);

    }

    /**
     * ### Searches for the first value after satisfying a callback function
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Find;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $is = new Find($collection)->afterWhere(fn($value, $key) => $value === 'Doe');
     *
     * // 25
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\Status\Value::NONE To represent a non-existing key.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):bool $callback <p>
     * The callback function to call to check each element.
     * </p>
     *
     * @return value-of<TDataStructure>|\FireHub\Core\Support\Enums\Status\Value::NONE The value after if it is
     * found in the data structure, Value::NONE otherwise. If a callback is valid in a data structure more than once,
     * the first matching value is returned.
     */
    public function afterWhere (callable $callback):mixed {

        $found = false;
        foreach ($this->data_structure as $storage_key => $storage_value) {

            if ($found) return $storage_value;

            if ($callback($storage_value, $storage_key)) $found = true;

        }

        return Value::NONE;

    }

}