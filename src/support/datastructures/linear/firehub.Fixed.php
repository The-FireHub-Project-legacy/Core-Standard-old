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
use FireHub\Core\Support\DataStructures\Contracts\ {
    Randomble, ReversibleInPlace, Selectable, SequentialAccess, ShuffleableInPlace
};
use FireHub\Core\Support\DataStructures\Operation\ {
    Select, Skip
};
use FireHub\Core\Support\DataStructures\Traits\Enumerable;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use FireHub\Core\Support\DataStructures\Exceptions\ {
    DataStructureException, OutOfRangeException, ShuffleException
};
use FireHub\Core\Support\Exceptions\ {
    RandomException, Arr\OutOfRangeException as ArrOutOfRangeException
};
use FireHub\Core\Support\LowLevel\ {
    Arr, DataIs, Iterator, NumInt, Random
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
 * @implements \FireHub\Core\Support\DataStructures\Contracts\Randomble<int, ?TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\ReversibleInPlace<int, ?TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\Selectable<int, ?TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\SequentialAccess<int, ?TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\ShuffleableInPlace<int, ?TValue>
 *
 * @phpstan-consistent-constructor
 */
class Fixed extends SplFixedArray implements Linear, Randomble, ReversibleInPlace, Selectable, SequentialAccess, ShuffleableInPlace {

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
     * Data in form of an array from a new object will be created.
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
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Select As return.
     */
    public function select ():Select {

        return new Select($this);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Skip As return.
     */
    public function skip ():Skip {

        return new Skip($this);

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
     * Removing a single item:
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
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::setSize() To set the size for the shifted data structure.
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
     * Removing a single item:
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
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get the size of the current data structure.
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
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get the size of the current data structure.
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
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get the first item from storage.
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
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get the first item from storage.
     */
    public function tail ():mixed {

        $size = $this->getSize();

        return $size > 0 ? $this[$size - 1] : null;

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
     * $collection->random();
     *
     * // 'two' - (generated randomly)
     * </code>
     * You can use more than one value:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->random(2);
     *
     * // Fixed['three', 'two'] - (generated randomly)
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::count To get the count of items in the data structure.
     * @uses \FireHub\Core\Support\LowLevel\Random::number() To get a random index from the data structure.
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\OutOfRangeException If the data structure is empty, or
     * $number is out of range.
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\DataStructureException If the method failed because
     * random number generation failed.
     * @uses \FireHub\Core\Support\LowLevel\Arr::random() To get random indexes from the data structure.
     * @uses \FireHub\Core\Support\LowLevel\Arr::range() To get a range of indexes from the data structure.
     * @uses \FireHub\Core\Support\LowLevel\DataIs::array() To check if the returned indexes are in array form.
     */
    public function random (int $number = 1):mixed {

        $count = $this->count();

        if ($count === 0)
            new OutOfRangeException()->withMessage('Data structure is empty.');

        if ($number < 1 || $number > $count)
            throw new OutOfRangeException()->withMessage('Provided number is out of range.');

        try {

            if ($number === 1) return $this[Random::number(0, $count - 1)];

            /** @var non-empty-list<int> $range */
            $range = Arr::range(0, $count - 1);

            $indexes = Arr::random($range, $number);

            if (!DataIs::array($indexes)) $indexes = [$indexes];

            $storage = new static($number);

            $position = 0;
            foreach ($indexes as $i)
                $storage[$position++] = $this[$i];

            return $storage;

        } catch (ArrOutOfRangeException|RandomException) {

            throw new DataStructureException()->withMessage('Random failed because random number generation failed.');

        }

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
     * $collection->transform(fn($value) => $value.'-1')
     *
     * // ['one-1', 'two-1', 'three-1']
     * </code>
     *
     * @since 1.0.0
     */
    public function transform (callable $callback):self {

        foreach ($this as $key => $value) $this[$key] = $callback($value, $key);

        return $this;

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
     * $collection->filter(fn($value, $key) => $value !== 'three');
     *
     * // ['one', 'two']
     * </code>
     * You can force early break:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Fixed;
     * use FireHub\Core\Support\Enums\ControlFlowSignal;
     *
     * $collection = new Fixed(3);
     *
     * $collection[0] = 'one';
     * $collection[1] = 'two';
     * $collection[2] = 'three';
     *
     * $collection->filter(function ($value, $key) {
     *     if ($value === 'three') return ControlFlowSignal::BREAK;
     *     return true;
     * });
     *
     * // ['one', 'two']
     * </code>
     *
     * @since 1.0.0
     */
    public function filter (callable $callback):static {

        $storage = new static($this->getSize());

        $counter = 0;
        foreach ($this as $key => $value) {

            $result = $callback($value, $key);

            if ($result === true) {
                $storage[$counter++] = $value;
                continue;
            }

            if ($result === ControlFlowSignal::BREAK) break;

        }

        $storage->setSize($counter);

        return $storage;

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
     * $collection->reverseInPlace();
     *
     * // ['three', 'three', 'one']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get the size of the current data structure.
     */
    public function reverseInPlace ():static {

        $size = $this->getSize();

        for ($i = 0, $j = $size - 1; $i < $j; $i++, $j--) {

            $temp = $this[$i];
            $this[$i] = $this[$j];
            $this[$j] = $temp;

        }

        return $this;

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
     * $collection->shuffle();
     *
     * // ['one', 'three', 'one'] - (generated randomly)
     * </code>
     *
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\ShuffleException If shuffle failed because random number
     * generation failed.
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::getSize() To get the size of the current data structure.
     * @uses \FireHub\Core\Support\LowLevel\Random::number() To get a random number for swapping items.
     */
    public function shuffleInPlace ():static {

        $size = $this->getSize();

        for ($i = $size - 1; $i > 0; $i--) {

            try {

                $j = Random::number(0, $i);

            } catch (RandomException) {

                throw new ShuffleException()->withMessage('Shuffle failed because random number generation failed.');

            }

            $temp = $this[$i];
            $this[$i] = $this[$j];
            $this[$j] = $temp;

        }

        return $this;

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

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Fixed::jsonSerialize() To get data as an array.
     */
    public function __serialize ():array {

        return $this->jsonSerialize();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Iterables::count() To count array parameter items.
     *
     * @param array<array-key, null|TValue> $data <p>
     * Serialized data.
     * </p>
     */
    public function __unserialize (array $data):void {

        $this->setSize(Iterator::count($data));

        $i = 0;
        foreach ($data as $item)
            $this[$i++] = $item;

    }

}