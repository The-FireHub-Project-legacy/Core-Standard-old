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
    ArrStorage, Chunkable, Filterable, KeyMappable, RandomAccess
};
use FireHub\Core\Support\DataStructures\Operation\Chunk;
use FireHub\Core\Support\DataStructures\Traits\Enumerable;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use FireHub\Core\Support\DataStructures\Exceptions\ {
    KeyAlreadyExistException, KeyDoesntExistException
};
use FireHub\Core\Support\LowLevel\Arr;
use ArgumentCountError, Traversable;

/**
 * ### Associative array collection type
 *
 * Collections that use named keys that you assign to them.
 * @since 1.0.0
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \FireHub\Core\Support\DataStructures\Contracts\ArrStorage<TKey, TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\Chunkable<TKey, TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\Filterable<TKey, TValue>
 * @implements \FireHub\Core\Support\Contracts\HighLevel\DataStructures\Linear<TKey, TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\KeyMappable<TKey, TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\RandomAccess<TKey, TValue>
 *
 * @phpstan-consistent-constructor
 */
class Associative implements ArrStorage, Chunkable, Filterable, Linear, KeyMappable, RandomAccess {

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
     * @var array<TKey, TValue>
     */
    protected(set) array $storage = [];

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param null|array<TKey, TValue> $storage [optional] <p>
     * Array to create underlying storage data.
     * </p>
     *
     * @return void
     */
    public function __construct (?array $storage = null) {

        $this->storage = $storage ?? [];

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = Associative::fromArray(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @return static<TKey, TValue> This object created from a provider array.
     */
    public static function fromArray (array $array):static {

        /** @var static<TKey, TValue> */
        return new static($array);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Chunk As return.
     */
    public function chunk ():Chunk {

        return new Chunk($this);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->toArray();
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @return array<TKey, TValue> Object as an array.
     */
    public function toArray ():array {

        return $this->storage;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->exist('firstname')
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetExists() As overloaded method.
     *
     * @notice This method only delegates to the array access method. Please use the array access method for large
     * data sets.
     */
    public function exist (mixed $key):bool {

        return $this->offsetExists($key);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->get('firstname');
     *
     * // John
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetGet() As overloaded method.
     *
     * @notice This method only delegates to array access method. Please use array access method for large data sets.
     */
    public function get (mixed $key):mixed {

        return $this->offsetGet($key);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->take('firstname');
     *
     * // John
     * </code>
     * If you try to get key that doesn't exist:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->take('middlename');
     *
     * >>> THROWS ERROR <<<
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetExists() As overloaded method.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetGet() As overloaded method.
     *
     * @notice This method only delegates to array access method. Please use array access method for large data sets.
     */
    public function take (mixed $key):mixed {

        /** @var TValue */
        return $this->offsetExists($key)
            ? $this->offsetGet($key)
            : throw new KeyDoesntExistException()->withKey($key);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->set('Jane', 'firstname');
     * $collection->set('female', 'gender');
     *
     * // ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'gender' => 'female']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetSet() As overloaded method.
     */
    public function set (mixed $value, mixed $key):void {

        $this->offsetSet($key, $value);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->add('female', 'gender');
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'gender' => 'female']
     * </code>
     * If you try to an existing key:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->add('Jane', 'firstname');
     *
     * >>> THROWS ERROR <<<
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetExists() As overloaded method.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetSet() As overloaded method.
     */
    public function add (mixed $value, mixed $key):void {

        !$this->offsetExists($key)
            ? $this->offsetSet($key, $value)
            : throw new KeyAlreadyExistException()->withKey($key);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->replace('Jane', 'firstname');
     *
     * // ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     * If you try to replace with a key that doesn't exist:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->replace('female', 'gender');
     *
     * >>> THROWS ERROR <<<
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetExists() As overloaded method.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetSet() As overloaded method.
     */
    public function replace (mixed $value, mixed $key):void {

        $this->offsetExists($key)
            ? $this->offsetSet($key, $value)
            : throw new KeyDoesntExistException()->withKey($key);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->remove('firstname');
     *
     * // ['lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetUnset() As overloaded method.
     */
    public function remove (mixed $key):void {

        $this->offsetUnset($key);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->delete('firstname');
     *
     * // ['lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     * If you try to delete with a key that doesn't exist:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->delete('gender');
     *
     * >>> THROWS ERROR <<<
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetExists() As overloaded method.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetUnset() As overloaded method.
     */
    public function delete (mixed $key):void {

        $this->offsetExists($key)
            ? $this->offsetUnset($key)
            : throw new KeyDoesntExistException()->withKey($key);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->pull('firstname');
     *
     * // John
     * </code>
     * If you try to delete with a key that doesn't exist:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->pull('gender');
     *
     * >>> THROWS ERROR <<<
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::take() To get an item from data structure or
     * throw an error.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::remove() To remove an item from data structure.
     */
    public function pull (mixed $key):mixed {

        $value = $this->take($key);

        $this->remove($key);

        return $value;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->transform(fn($value) => $value.'-1')
     *
     * // ['firstname' => 'John-1', 'lastname' => 'Doe-1', 'age' => '25-1', 10 => '2-1']
     * </code>
     * Transform with keys:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->transform(fn($value, $key) => $key === 'age' ? $value.'-1' : $value)
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => '25-1', 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::map() To apply the callback to the elements of the given array.
     */
    public function transform (callable $callback):self {

        try {

            $this->storage = Arr::map($this->storage, $callback);

        } catch (ArgumentCountError) {

            foreach ($this->storage as $key => $value) $this->storage[$key] = $callback($value, $key);

        }

        return $this;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->applyToKeys(fn($value, $key) => $value === 'Doe' ? $key.'-1' : $key)
     *
     * // ['firstname', 'John'], ['lastname-1', 'Doe'], ['age', 25], [10, 2]
     * </code>
     *
     * @since 1.0.0
     */
    public function applyToKeys (callable $callback):static {

        $storage = [];

        foreach ($this->storage as $key => $value) $storage[$callback($value, $key)] = $value;

        return new static($storage);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->transformKeys(fn($value, $key) => $value === 'Doe' ? $key.'-1' : $key)
     *
     * // ['firstname', 'John'], ['lastname-1', 'Doe'], ['age', 25], [10, 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses static::applyToKeys() To apply the callback to the keys of the data structure.
     * @uses static::toArray() To get data structure as an array.
     */
    public function transformKeys (callable $callback):self {

        $this->storage = $this->applyToKeys($callback)->toArray();

        return $this;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->filter(fn($value, $key) => $value !== 25);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 10 => 2]
     * </code>
     * You can force early break:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\Enums\ControlFlowSignal;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->filter(function ($value, $key) {
     *     if ($value === 25) return ControlFlowSignal::BREAK;
     *     return true;
     * });
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe']
     * </code>
     *
     * @since 1.0.0
     */
    public function filter (callable $callback):static {

        $storage = [];

        foreach ($this->storage as $key => $value) {

            $result = $callback($value, $key);

            if ($result === true) {
                $storage[$key] = $value;
                continue;
            }

            if ($result === ControlFlowSignal::BREAK) break;

        }

        return new static($storage);

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
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * isset($collection['firstname'])
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     */
    public function offsetExists (mixed $offset):bool {

        return isset($this->storage[$offset]);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection['firstname'];
     *
     * // John
     * </code>
     *
     * @since 1.0.0
     */
    public function offsetGet (mixed $offset):mixed {

        return $this->storage[$offset] ?? null;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection['firstname'] = 'Joe';
     *
     * // ['firstname' => 'Joe', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @phpstan-ignore-next-line method.childParameterTyp
     */
    public function offsetSet (mixed $offset, mixed $value):void {

        $this->storage[$offset] = $value;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * unset($collection['firstname']);
     *
     * // ['lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     */
    public function offsetUnset (mixed $offset):void {

        unset($this->storage[$offset]);

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @return array<TKey, TValue> An associative array of key/value pairs that represent the serialized form
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
     * @param array<TKey, TValue> $data <p>
     * Serialized data.
     * </p>
     */
    public function __unserialize (array $data):void {

        $this->storage = $data;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->firstname;
     *
     * // John
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetGet() As overloaded method.
     *
     * @notice This method only delegates to array access method. Please use array access method for large data sets.
     */
    public function __get (mixed $name):mixed {

        return $this->offsetGet($name);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->firstname = 'Joe';
     *
     * // ['firstname' => 'Joe', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetSet() As overloaded method.
     *
     * @notice This method only delegates to array access method. Please use array access method for large data sets.
     */
    public function __set (mixed $name, mixed $value):void {

        $this->offsetSet($name, $value);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * isset($collection->firstname)
     *
     * // true
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetExists() As overloaded method.
     *
     * @notice This method only delegates to array access method. Please use array access method for large data sets.
     */
    public function __isset (mixed $name):bool {

        return $this->offsetExists($name);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * unset($collection->firstname);
     *
     * // ['lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative::offsetUnset() As overloaded method.
     *
     * @notice This method only delegates to array access method. Please use array access method for large data sets.
     */
    public function __unset (mixed $name):void {

        $this->offsetUnset($name);

    }

}