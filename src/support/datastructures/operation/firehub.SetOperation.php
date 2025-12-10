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
    ArrStorage, Mergeable
};
use FireHub\Core\Support\LowLevel\Arr;

/**
 * ### Set operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Mergeable
 * @template TCompareDataStructure of \FireHub\Core\Support\DataStructures\Contracts\Mergeable
 */
readonly class SetOperation {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structure.
     * </p>
     * @param TCompareDataStructure $data_structure_compare <p>
     * Instance of data structure to exclude from the data structure.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected Mergeable $data_structure,
        protected Mergeable $data_structure_compare
    ) {}

    /**
     * ### Computes the difference of data structures using values for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     * $collection2 = new Indexed(['John', 'Jane']);
     *
     * $set_operator = new SetOperation($collection, $collection2)->differenceValue();
     *
     * // ['Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::difference() To compute the difference of two arrays.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\DataStructures\Operation\Contains::value() To check if a value exists in the data
     * structure.
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function differenceValue ():Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::difference(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray()
                )
            ) : $this->data_structure->filter(fn($value)
                    => !$this->data_structure_compare->contains()->value($value));

    }

    /**
     * ### Computes the difference of data structures using values for comparison by using a callback for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     * $collection2 = new Indexed(['John', 'Jane']);
     *
     * $set_operator = new SetOperation($collection, $collection2)->differenceValueWith(
     *     fn($value_a, $value_b) => $value_a <=> $value_b
     * );
     *
     * // ['Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceFunc() To compute the difference of two arrays by using a
     * callback for comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(value-of<TDataStructure>, value-of<TDataStructure>):int<-1, 1> $value_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function differenceValueWith (callable $value_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::differenceFunc(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $value_callback
                )

            ) : $this->data_structure->filter(function ($value) use ($value_callback) {
                    foreach ($this->data_structure_compare as $data_structure_compare_value)
                        if ($value_callback($value, $data_structure_compare_value) === 0) return false;
                    return true;
                });

    }

    /**
     * ### Computes the difference of data structures using keys for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->differenceKey();
     *
     * // ['lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceKey() To compute the difference of two arrays using keys
     * for comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\DataStructures\Operation\Contains::key() To check if a key exists in the data
     * structure.
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function differenceKey ():Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::differenceKey(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray()
                )
            ) : $this->data_structure->filter(fn($value, $key = null)
                    => !$this->data_structure_compare->contains()->key($key));

    }

    /**
     * ### Computes the difference of data structures using keys for comparison by using a callback for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->differenceKeyWith(
     *     fn($key_a, $key_b) => $key_a <=> $key_b
     * );
     *
     * // ['lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceKeyFunc() To compute the difference of two arrays by using
     * keys for comparison by using a callback for data comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(key-of<TDataStructure>, key-of<TDataStructure>):int<-1, 1> $key_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function differenceKeyWith (callable $key_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::differenceKeyFunc(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $key_callback
                )

            ) : $this->data_structure->filter(function ($value, $key = null) use ($key_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_key => $data_structure_compare_value)
                    if ($key_callback($key, $data_structure_compare_key) === 0) return false;
                return true;
            });

    }

    /**
     * ### Computes the difference of data structures with additional index check
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->differenceAssoc();
     *
     * // ['lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceAssoc() To compute the difference of two arrays with
     * additional index check.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\DataStructures\Operation\Contains::pair() To check if a key and value pair exists
     * in the data structure.
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function differenceAssoc ():Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::differenceAssoc(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray()
                )
            ) : $this->data_structure->filter(fn($value, $key = null)
                    => !$this->data_structure_compare->contains()->pair($key, $value));

    }

    /**
     * ### Computes the difference of data structures with additional index check by using a callback for key comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->differenceAssocWithKey(
     *     fn($key_a, $key_b) => $key_a <=> $key_b
     * );
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceAssocFuncKey() To compute the difference of two arrays
     * with additional index check by using a callback for key comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(key-of<TDataStructure>, key-of<TDataStructure>):int<-1, 1> $key_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function differenceAssocWithKey (callable $key_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::differenceAssocFuncKey(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $key_callback
                )
            ) : $this->data_structure->filter(function ($value, $key = null) use ($key_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_key => $data_structure_compare_value)
                    if (
                        $value === $data_structure_compare_value
                        && $key_callback($key, $data_structure_compare_key) === 0
                    ) return false;
                return true;
            });

    }

    /**
     * ### Computes the difference of data structures with additional index check by using a callback for value comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->differenceAssocWithKey(
     *     fn($value_a, $value_b) => $value_a <=> $value_b
     * );
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceFunc() To compute the difference of two arrays with
     * additional index check by using a callback for value comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(value-of<TDataStructure>, value-of<TDataStructure>):int<-1, 1> $value_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function differenceAssocWithValue (callable $value_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::differenceAssocFuncValue(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $value_callback
                )
            ) : $this->data_structure->filter(function ($value, $key = null) use ($value_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_key => $data_structure_compare_value)
                    if (
                        $key === $data_structure_compare_key
                        && $value_callback($value, $data_structure_compare_value) === 0
                    ) return false;
                return true;
            });

    }

    /**
     * ### Computes the difference of data structures with additional index check by using a callback for key-value comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->differenceAssocWithKeyValue(
     *     fn($value_a, $value_b) => $value_a <=> $value_b, fn($key_a, $key_b) => $key_a <=> $key_b
     * );
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::differenceAssocFuncKeyValue() To compute the difference of two
     * arrays with additional index check by using a callback for key-value comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(value-of<TDataStructure>, value-of<TDataStructure>):int<-1, 1> $value_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     * @param callable(key-of<TDataStructure>, key-of<TDataStructure>):int<-1, 1> $key_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function differenceAssocWithKeyValue (callable $value_callback, callable $key_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::differenceAssocFuncKeyValue(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $value_callback,
                    $key_callback
                )
            ) : $this->data_structure->filter(function ($value, $key = null) use ($value_callback, $key_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_key => $data_structure_compare_value)
                    if (
                        $key === $data_structure_compare_key
                        && $value === $data_structure_compare_value
                        && $value_callback($value, $data_structure_compare_value) === 0
                        && $key_callback($key, $data_structure_compare_key) === 0
                    ) return false;
                return true;
            });

    }

    /**
     * ### Computes the intersection of data structures using values for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     * $collection2 = new Indexed(['John', 'Jane']);
     *
     * $set_operator = new SetOperation($collection, $collection2)->intersectValue();
     *
     * // ['John', 'Jane', 'Jane', 'Jane']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersect() To compute the intersection of two arrays.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\DataStructures\Operation\Contains::value() To check if a value exists in the data
     * structure.
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function intersectValue ():Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::intersect(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray()
                )
            ) : $this->data_structure->filter(fn($value)
                    => $this->data_structure_compare->contains()->value($value));

    }

    /**
     * ### Computes the intersection of data structures using values for comparison by using a callback for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     * $collection2 = new Indexed(['John', 'Jane']);
     *
     * $set_operator = new SetOperation($collection, $collection2)->intersectValueWith(
     *     fn($value_a, $value_b) => $value_a <=> $value_b
     * );
     *
     * // ['John', 'Jane', 'Jane', 'Jane']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectFunc() To compute the intersection of two arrays by using a
     * callback for comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(value-of<TDataStructure>, value-of<TDataStructure>):int<-1, 1> $value_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function intersectValueWith (callable $value_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::intersectFunc(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $value_callback
                )

            ) : $this->data_structure->filter(function ($value) use ($value_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_value)
                    if ($value_callback($value, $data_structure_compare_value) === 0) return true;
                return false;
            });

    }

    /**
     * ### Computes the intersection of data structures using keys for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->intersectKey();
     *
     * // ['firstname' => 'John', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectKey() To compute the intersection of two arrays using keys
     * for comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\DataStructures\Operation\Contains::key() To check if a key exists in the data
     * structure.
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function intersectKey ():Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::intersectKey(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray()
                )
            ) : $this->data_structure->filter(fn($value, $key = null)
                    => $this->data_structure_compare->contains()->key($key));

    }

    /**
     * ### Computes the intersection of data structures using keys for comparison by using a callback for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->intersectKeyWith(
     *     fn($key_a, $key_b) => $key_a <=> $key_b
     * );
     *
     * // ['firstname' => 'John', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectKeyFunc() To compute the intersection of two arrays by using
     * keys for comparison by using a callback for data comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(key-of<TDataStructure>, key-of<TDataStructure>):int<-1, 1> $key_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function intersectKeyWith (callable $key_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::intersectKeyFunc(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $key_callback
                )

            ) : $this->data_structure->filter(function ($value, $key = null) use ($key_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_key => $data_structure_compare_value)
                    if ($key_callback($key, $data_structure_compare_key) === 0) return true;
                return false;
            });

    }

    /**
     * ### Computes the intersection of data structures with an additional index check
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->intersectAssoc();
     *
     * // ['firstname' => 'John', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectAssoc() To compute the intersection of two arrays with
     * additional index check.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     * @uses \FireHub\Core\Support\DataStructures\Operation\Contains::pair() To check if a key and value pair exists
     * in the data structure.
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function intersectAssoc ():Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::intersectAssoc(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray()
                )
            ) : $this->data_structure->filter(fn($value, $key = null)
                    => $this->data_structure_compare->contains()->pair($key, $value));

    }

    /**
     * ### Computes the intersection of data structures with additional index check by using a callback for key comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->intersectAssocWithKey(
     *     fn($key_a, $key_b) => $key_a <=> $key_b
     * );
     *
     * // ['age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectAssocFuncKey() To compute the intersection of two arrays
     * with additional index check by using a callback for key comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(key-of<TDataStructure>, key-of<TDataStructure>):int<-1, 1> $key_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function intersectAssocWithKey (callable $key_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::intersectAssocFuncKey(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $key_callback
                )
            ) : $this->data_structure->filter(function ($value, $key = null) use ($key_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_key => $data_structure_compare_value)
                    if (
                        $value === $data_structure_compare_value
                        && $key_callback($key, $data_structure_compare_key) === 0
                    ) return true;
                return false;
            });

    }

    /**
     * ### Computes the intersection of data structures with additional index check by using a callback for value comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->intersectAssocWithKey(
     *     fn($value_a, $value_b) => $value_a <=> $value_b
     * );
     *
     * // ['age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectFunc() To compute the intersection of two arrays with
     * additional index check by using a callback for value comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(value-of<TDataStructure>, value-of<TDataStructure>):int<-1, 1> $value_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function intersectAssocWithValue (callable $value_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::intersectAssocFuncValue(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $value_callback
                )
            ) : $this->data_structure->filter(function ($value, $key = null) use ($value_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_key => $data_structure_compare_value)
                    if (
                        $key === $data_structure_compare_key
                        && $value_callback($value, $data_structure_compare_value) === 0
                    ) return true;
                return false;
            });

    }

    /**
     * ### Computes the intersection of data structures with additional index check by using a callback for key-value comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->intersectAssocWithKeyValue(
     *     fn($value_a, $value_b) => $value_a <=> $value_b, fn($key_a, $key_b) => $key_a <=> $key_b
     * );
     *
     * // ['age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create a new data structure from
     * array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Arr::intersectAssocFuncKeyValue() To compute the intersection of two
     * arrays with additional index check by using a callback for key-value comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::filter() To filter items from the data
     * structure.
     *
     * @param callable(value-of<TDataStructure>, value-of<TDataStructure>):int<-1, 1> $value_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     * @param callable(key-of<TDataStructure>, key-of<TDataStructure>):int<-1, 1> $key_callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument
     * is considered to be respectively less than, equal to, or greater than the second.
     * </p>
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function intersectAssocWithKeyValue (callable $value_callback, callable $key_callback):Mergeable {

        /** @phpstan-ignore return.type */
        return $this->data_structure instanceof ArrStorage && $this->data_structure_compare instanceof ArrStorage
            ? $this->data_structure::fromArray(
                Arr::intersectAssocFuncKeyValue(
                    $this->data_structure->storage,
                    $this->data_structure_compare->toArray(),
                    $value_callback,
                    $key_callback
                )
            ) : $this->data_structure->filter(function ($value, $key = null) use ($value_callback, $key_callback) {
                foreach ($this->data_structure_compare as $data_structure_compare_key => $data_structure_compare_value)
                    if (
                        $key === $data_structure_compare_key
                        && $value === $data_structure_compare_value
                        && $value_callback($value, $data_structure_compare_value) === 0
                        && $key_callback($key, $data_structure_compare_key) === 0
                    ) return true;
                return false;
            });

    }

    /**
     * ### Computes the symmetric difference of data structures using values for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     * $collection2 = new Indexed(['John', 'Richard', 'Marry']);
     *
     * $set_operator = new SetOperation($collection, $collection2)->symmetricDifferenceValue();
     *
     * // ['Jane', 'Jane', 'Jane', 'Marry']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\SetOperation::differenceValue() To compute the
     * difference of data structures using values for comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::union() To merge difference from both data
     * structures.
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function symmetricDifferenceValue ():Mergeable {

        /** @phpstan-ignore return.type */
        return $this->differenceValue()->union(
            $this->data_structure_compare->setOperation($this->data_structure)->differenceValue()
        );

    }

    /**
     * ### Computes the symmetric difference of data structures using keys for comparison
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\SetOperation;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     * $collection2 = new Associative(['firstname_x' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $set_operator = new SetOperation($collection, $collection2)->symmetricDifferenceKey();
     *
     * // ['firstname_x' => 'John', 'firstname' => 'John']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\SetOperation::differenceKey() To compute the
     * difference of data structures using keys for comparison.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\Mergeable::union() To merge difference from both data
     * structures.
     *
     * @return TDataStructure New data structure with applied set operator.
     */
    public function symmetricDifferenceKey ():Mergeable {

        /** @phpstan-ignore return.type */
        return $this->differenceKey()->union(
            $this->data_structure_compare->setOperation($this->data_structure)->differenceKey()
        );

    }

}