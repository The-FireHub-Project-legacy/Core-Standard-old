<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 7.4
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\DataStructures\Linear;

use FireHub\Core\Support\Contracts\HighLevel\DataStructures\Linear;
use FireHub\Core\Support\DataStructures\Contracts\SequentialAccess;
use FireHub\Core\Support\DataStructures\Traits\Enumerable;
use FireHub\Core\Support\LowLevel\ {
    DataIs, Iterator, NumInt
};
use SplFixedArray;

/**
 * ### Fixed data structure type
 *
 * Fixed data structure allows only integers as keys, but it is faster and uses less memory than an array data
 * structure.
 * This data structure type must be resized manually and allows only integers within the range as indexes.
 * @since 1.0.0
 *
 * @template TValue
 *
 * @extends SplFixedArray<TValue>
 * @implements \FireHub\Core\Support\Contracts\HighLevel\DataStructures\Linear<int, ?TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\SequentialAccess<int, ?TValue>
 *
 * @phpstan-consistent-constructor
 */
class Fixed extends SplFixedArray implements Linear, SequentialAccess {

    /**
     * ### Enumerable data structure methods that every element meets a given criterion
     * @since 1.0.0
     *
     * @use \FireHub\Core\Support\DataStructures\Traits\Enumerable<int, ?TValue>
     */
    use Enumerable;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param non-negative-int $size <p>
     * The size of the fixed array.
     * </p>
     *
     * @return void
     */
    public function __construct (int $size) {

        parent::__construct($size);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = Fixed::fromArray([1 => 'one, 2 => 'two', 3 => 'three']);
     *
     * // ['one, 'two', 'three']
     * </code>
     * With preserved keys:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = Fixed::fromArray([1 => 'one, 2 => 'two', 3 => 'three']);
     *
     * // [0 => null, 1 => 'one, 2 => 'two', 3 => 'three']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DataIs::int() To check if all $array keys are integers.
     * @uses \FireHub\Core\Support\LowLevel\Iterator::count() To count $array parameter items.
     *
     * @param array<array-key, mixed> $array <p>
     * Data in form of an array from new object will be created.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Save the numeric indexes used in the original array.
     * </p>
     *
     * @return static<TValue> This object created from a provider array.
     */
    public static function fromArray (array $array, bool $preserve_keys = false):static {

        if ($preserve_keys) {

            $max = null;
            foreach ($array as $key => $value)
                if (($max === null || $key > $max) && DataIs::int($key))
                    /** @var non-negative-int $max */ $max = $key;

            $storage = new static($max + 1);

            foreach ($array as $key => $value)
                $storage[$key] = $value;

        } else {

            $storage = new static(Iterator::count($array));

            $i = 0;
            foreach ($array as $value)
                $storage[$i++] = $value;

        }

        /** @var static<TValue> */
        return $storage;

    }

    /**
     * ### Gets the size of the data structure
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->getSize();
     *
     * // 3
     * </code>
     *
     * @since 1.0.0
     *
     * @return non-negative-int Size of the data structure.
     */
    public function getSize ():int {

        return parent::getSize() > 0 ? parent::getSize() : 0;

    }

    /**
     * ### Change the size of the data structure
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->setSize(4);
     *
     * $collection->getSize();
     *
     * // 4
     * </code>
     *
     * @since 1.0.0
     *
     * @return true Always true.
     */
    public function setSize (int $size):true {

        return parent::setSize($size); // @phpstan-ignore return.type

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->toArray();
     *
     * // ['one, 'two', 'three']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Iterator::toArray() To convert data structure to array.
     *
     * @return array<int, null|TValue> Object as an array.
     */
    public function toArray ():array {

        return Iterator::toArray($this);

    }

    /**
     * {@inheritDoc}
     *
     * Removing single item:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->shift();
     *
     * // ['two', 'three']
     * </code>
     * Removing more than one item:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->shift(2);
     *
     * // ['three']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get the size of the current data structure.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::setSize() To set the size for shifted data structure.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the largest number of zero and current data structure
     * size.
     *
     * @caution This method will iterate over all items.
     */
    public function shift (int $items = 1):void {

        $size = $this->getSize();

        for ($i = $items; $i < $size; $i++)
            $this[$i - $items] = $this[$i];

        $this->setSize(NumInt::max(($size - $items), 0));

    }

    /**
     * {@inheritDoc}
     *
     * Removing single item:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->pop();
     *
     * // ['one', 'two']
     * </code>
     * Removing more than one item:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->pop(2);
     *
     * // ['one']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get the size of the current data structure.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::setSize() To set the size for the popped data structure.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the largest number of zero and current data structure
     * size.
     */
    public function pop (int $items = 1):void {

        $this->setSize(NumInt::max(($this->getSize() - $items), 0));

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->prepend('four', 'five', 'six');
     *
     * // ['four', 'five', 'six', 'one', 'two', 'three']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get size of the current data structure.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::setSize() To set new size for data structure.
     * @uses \FireHub\Core\Support\LowLevel\Iterator::count() To count $values.
     *
     * @caution This method will iterate over all items.
     */
    public function prepend (mixed ...$values):void {

        $values_size = Iterator::count($values);

        $old_size = $this->getSize();

        $this->setSize($old_size + $values_size);

        for ($i = $old_size - 1; $i >= 0; $i--)
            $this[$i + $values_size] = $this[$i];

        $i = 0;
        foreach ($values as $value)
            $this[$i++] = $value;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->append('four', 'five', 'six');
     *
     * // ['one', 'two', 'three', 'four', 'five', 'six']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get size of the current data structure.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::setSize() To set new size for data structure.
     * @uses \FireHub\Core\Support\LowLevel\Iterator::count() To count $values.
     */
    public function append (mixed ...$values):void {

        $old_size = $this->getSize();

        $this->setSize($old_size + Iterator::count($values));

        $i = 0;
        foreach ($values as $value)
            $this[$i++ + $old_size] = $value;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->head();
     *
     * // 'one'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get first item from storage.
     */
    public function head ():mixed {

        return $this->getSize() > 0 ? $this[0] : null;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->tail();
     *
     * // 'three'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get first item from storage.
     */
    public function tail ():mixed {

        $size = $this->getSize();

        return $size > 0 ? $this[$size - 1] : null;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return array<array-key, mixed> Data which can be serialized by json_encode(), which is a value of any type
     * other than a resource.
     */
    public function jsonSerialize ():array {

        return parent::jsonSerialize();

    }

}