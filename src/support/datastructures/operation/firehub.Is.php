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
use FireHub\Core\Support\LowLevel\ {
    Arr, Data, DataIs, Iterables
};
use Traversable;

/**
 * ### Check is operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\Contracts\HighLevel\DataStructures
 */
readonly class Is {

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
     * ### Check if a data structure is empty
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     * use FireHub\Core\Support\LowLevel\DataIs;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->empty();
     *
     * // false
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Contracts\HighLevel\DataStructures::count() To count elements in a data structure.
     *
     * @return bool True if a data structure is empty, false otherwise.
     */
    public function empty ():bool {

        return $this->data_structure->count() === 0;

    }

    /**
     * ### Check if a data structure is not empty
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->notEmpty();
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Is::empty() To check if the data structure is empty.
     *
     * @return bool True if a data structure is not empty, false otherwise.
     */
    public function notEmpty ():bool {

        return !$this->empty();

    }

    /**
     * ### Check if a data structure consists of all unique elements
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->unique();
     *
     * // false
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Contracts\HighLevel\DataStructures::count() To count elements in a data structure.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::toArray() To convert data structure to array
     * if data structure is an array storage.
     * @uses \FireHub\Core\Support\LowLevel\Iterables::count() To count elements in an array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::unique() To get unique values from an array.
     * @uses \FireHub\Core\Support\LowLevel\Data::serialize() To serialize data structure values.
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\CannotSerializeException If try to serialize an anonymous class,
     * function, or resource.
     *
     * @return bool True if a data structure consists of all unique elements, false otherwise.
     */
    public function unique ():bool {

        if ($this->data_structure instanceof ArrStorage)
            return $this->data_structure->count() === Iterables::count(Arr::unique($this->data_structure->toArray()));

        $serialized = [];
        foreach ($this->data_structure as $value)
            $serialized[] = Data::serialize($value); // @phpstan-ignore argument.type

        return Iterables::count($serialized) === Iterables::count(Arr::unique($serialized));

    }

    /**
     * ### Check if a data structure has numerical indexes in an ordered sequential manner
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->sequential();
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @return bool True if a data structure has numerical indexes in an ordered sequential manner, false otherwise.
     */
    public function sequential ():bool {

        $expected = 0;
        foreach ($this->data_structure as $key => $value)
            if ($key !== $expected++) return false;

        return true;

    }

    /**
     * ### Check if a data structure is associative
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->associative();
     *
     * // false
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::int To check if a value is an integer.
     *
     * @return bool True if a data structure is associative, false otherwise.
     */
    public function associative ():bool {

        $i = 0;
        foreach ($this->data_structure as $key => $value)
            if ($key !== $i++ || !DataIs::int($key)) return true; // @phpstan-ignore staticMethod.alreadyNarrowedType

        return false;

    }

    /**
     * ### Check if a data structure is homogeneous
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->homogeneous();
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @uses Data::getDebugType() To get the type of value.
     *
     * @return bool True if a data structure is homogeneous, false otherwise.
     */
    public function homogeneous ():bool {

        $first = true; $expected_type = null;
        foreach ($this->data_structure as $value) {

            $type = Data::getDebugType($value);

            if ($first) {

                $expected_type = $type;
                $first = false;
                continue;

            }

            if ($type !== $expected_type) return false;

        }

        return true;

    }

    /**
     * ### Check if a data structure is heterogeneous
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->heterogeneous();
     *
     * // false
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Is::homogeneous() To check if a data structure is
     * homogeneous.
     *
     * @return bool True if a data structure is heterogeneous, false otherwise.
     */
    public function heterogeneous ():bool {

        return !$this->homogeneous();

    }

    /**
     * ### Check if a data structure is class homogeneous
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed([new Indexed, new Indexed, new Indexed]);
     *
     * $is = new Is($collection)->classHomogeneous(Indexed::class);
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::object() To check if a value is an object.
     *
     * @param class-string $class <p>
     * Class name to check against.
     * </p>
     *
     * @return bool True if a data structure is class homogeneous, false otherwise.
     */
    public function classHomogeneous (string $class):bool {

        foreach ($this->data_structure as $value)
            if (!DataIs::object($value) || $value::class !== $class)
                return false;

        return true;

    }

    /**
     * ### Check if data structure items are all instance of the provided class
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     * use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
     *
     * $collection = new Indexed([new Indexed, new Indexed, new Fixed]);
     *
     * $is = new Is($collection)->allInstanceOf(DataStructures::class);
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @param class-string $class <p>
     * Class name to check against.
     * </p>
     *
     * @return bool True if data structure items are all instance of the provided class, false otherwise.
     */
    public function allInstanceOf (string $class):bool {

        foreach ($this->data_structure as $value)
            if (!$value instanceof $class)
                return false;

        return true;

    }

    /**
     * ### Check if a data structure is flat
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->flat();
     *
     * // false
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::array() To check if a value is an array.
     *
     * @return bool True if a data structure is flat, false otherwise.
     */
    public function flat ():bool {

        foreach ($this->data_structure as $value)
            if ($value instanceof Traversable || DataIs::array($value))
                return false;

        return true;

    }

    /**
     * ### Check if a data structure is multidimensional
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->multidimensional();
     *
     * // false
     * </code>
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Is::flat() To check if a data structure is flat.
     *
     * @since 1.0.0
     *
     * @return bool True if a data structure is multidimensional, false otherwise.
     */
    public function multidimensional ():bool {

        return !$this->flat();

    }

    /**
     * ### Check if a data structure is truthy
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->truthy();
     *
     * // false
     * </code>
     *
     * @since 1.0.0
     *
     * @return bool True if a data structure is truthy, false otherwise.
     */
    public function truthy ():bool {

        foreach ($this->data_structure as $value)
            if (!$value) return false;

        return true;

    }

    /**
     * ### Check if a data structure is pure
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Is;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $is = new Is($collection)->pure();
     *
     * // false
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::scalar() To check if a value is scalar.
     *
     * @return bool True if a data structure is pure, false otherwise.
     */
    public function pure ():bool {

        foreach ($this->data_structure as $value) {
            if ($value !== null && !DataIs::scalar($value))
                return false;
        }
        return true;

    }

}