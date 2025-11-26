<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.4
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\DataStructures\Linear;

use FireHub\Core\Support\Contracts\HighLevel\DataStructures\Linear;
use FireHub\Core\Support\DataStructures\Contracts\ {
    ArrStorage, SequentialAccess
};
use FireHub\Core\Support\DataStructures\Traits\Enumerable;
use FireHub\Core\Support\LowLevel\Arr;
use Traversable;

/**
 * ### Indexed array collection type
 *
 * Collections which have numerical indexes in an ordered sequential manner (starting from 0 and ending with n-1).
 * @since 1.0.0
 *
 * @template TValue
 *
 * @implements \FireHub\Core\Support\DataStructures\Contracts\ArrStorage<int, TValue>
 * @implements \FireHub\Core\Support\Contracts\HighLevel\DataStructures\Linear<int, TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\SequentialAccess<int, TValue>
 *
 * @phpstan-consistent-constructor
 */
class Indexed implements ArrStorage, Linear, SequentialAccess {

    /**
     * ### Enumerable data structure methods that every element meets a given criterion
     * @since 1.0.0
     *
     * @use \FireHub\Core\Support\DataStructures\Traits\Enumerable<int, TValue>
     */
    use Enumerable;

    /**
     * ### Underlying storage data
     * @since 1.0.0
     *
     * @var list<TValue>
     */
    protected(set) array $storage = [];

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::values() To help with removing keys from an array.
     *
     * @param null|array<array-key, TValue> $storage [optional] <p>
     * Array to create underlying storage data.
     * </p>
     *
     * @return void
     *
     * @caution This collection will reindex the provided array if it is not already numerically indexed.
     */
    public function __construct (?array $storage = null) {

        $this->storage = Arr::values($storage ?? []);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = Indexed::fromArray(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * // ['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @return static<TValue> This object created from a provider array.
     */
    public static function fromArray (array $array):static {

        /** @var static<TValue> */
        return new static($array);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->toArray();
     *
     * // ['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @return list<TValue> Object as an array.
     */
    public function toArray ():array {

        return $this->storage;

    }

    /**
     * {@inheritDoc}
     *
     * Removing a single item:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->shift();
     *
     * // ['Jane', 'Jane', 'Jane', 'Richard', 'Richard']
     * </code>
     * Removing more than one item:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->shift(3);
     *
     * // ['Jane', 'Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::shift() To remove an item at the beginning of the data structure if
     * $items value is 5 or less.
     * @uses \FireHub\Core\Support\LowLevel\Arr::splice() To remove an item at the beginning of the data structure if
     * $items value is more than 5.
     */
    public function shift (int $items = 1):void {

        if ($items <= 5)
            while ($items-- > 0)
                Arr::shift($this->storage);

        else Arr::splice($this->storage, 0, $items);

    }

    /**
     * {@inheritDoc}
     *
     * Removing single item:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->pop();
     *
     * // ['John', 'Jane', 'Jane', 'Jane', 'Richard']
     * </code>
     * Removing more than one item:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->pop(3);
     *
     * // ['John', 'Jane', 'Jane']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::pop() To remove an item at the end of the data structure if $items
     * value is 5 or less.
     * @uses \FireHub\Core\Support\LowLevel\Arr::splice() To remove an item at the end of the data structure if $items
     * value is more than 5.
     */
    public function pop (int $items = 1):void {

        if ($items <= 5)
            while ($items-- > 0)
                Arr::pop($this->storage);

        else Arr::splice($this->storage, -$items);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->prepend('Johnie', 'Janie', 'Baby');
     *
     * // ['Johnie', 'Janie', 'Baby', 'John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::values() To get only values from $values parameter.
     */
    public function prepend (mixed ...$values):void {

        $this->storage = Arr::values([...$values, ...$this->storage]);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->append('Johnie', 'Janie', 'Baby');
     *
     * // ['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard', 'Johnie', 'Janie', 'Baby']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::values() To get only values from $values parameter.
     */
    public function append (mixed ...$values):void {

        $this->storage = Arr::values([...$this->storage, ...$values]);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->head();
     *
     * // 'John'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::first() To get first item from storage.
     */
    public function head ():mixed {

        return Arr::first($this->storage);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->tail();
     *
     * // 'Richard'
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::last() To get last item from storage.
     */
    public function tail ():mixed {

        return Arr::last($this->storage);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5]);
     *
     * $collection->transform(fn($value) => $value + 1);
     *
     * // [2, 3, 4, 5, 6]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::map() To apply the callback to the elements of the given array.
     *
     * @param callable(TValue):TValue $callback <p>
     * A callable to run for each element in a data structure.
     * </p>
     */
    public function transform (callable $callback):self {

        $this->storage = Arr::map($this->storage, $callback);

        return $this;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function jsonSerialize ():array {

        return $this->storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function getIterator ():Traversable {

        yield from $this->storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return array<TValue> An associative array of key/value pairs that represent the serialized form
     * of the object.
     */
    public function __serialize ():array {

        return $this->storage;

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::values() To help with removing keys from an $data.
     *
     * @param array<TValue> $data <p>
     * Serialized data.
     * </p>
     */
    public function __unserialize (array $data):void {

        $this->storage = Arr::values($data);

    }

}