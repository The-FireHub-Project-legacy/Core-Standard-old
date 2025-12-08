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
    KeyMappable, Selectable
};
use FireHub\Core\Support\DataStructures\Operation\ {
    Select, Skip
};
use FireHub\Core\Support\DataStructures\Traits\Enumerable;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use Closure, Generator, Traversable;

/**
 * ### Lazy data structure type
 *
 * Lazy data structures allow you to work with large datasets while keeping memory usage low.
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @implements \FireHub\Core\Support\Contracts\HighLevel\DataStructures\Linear<TKey, TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\KeyMappable<TKey, TValue>
 * @implements \FireHub\Core\Support\DataStructures\Contracts\Selectable<TKey, TValue>
 *
 * @phpstan-consistent-constructor
 */
class Lazy implements Linear, KeyMappable, Selectable {

    /**
     * ### Enumerable data structure methods that every element meets a given criterion
     * @since 1.0.0
     *
     * @use \FireHub\Core\Support\DataStructures\Traits\Enumerable<TKey, TValue>
     */
    use Enumerable;

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @param Closure():Generator<TKey, TValue> $storage <p>
     * Underlying storage data.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected Closure $storage
    ) {}

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Lazy;
     *
     * $collection = Lazy::fromArray(['firstname', 'John'], ['lastname', 'Doe'], ['age', 25], [10, 2]);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @return static<TKey, TValue> This object created from a provider array.
     */
    public static function fromArray (array $array):static {

        /** @var array<array{TKey, TValue}> $array */
        return new static (static function () use ($array) {

            foreach ($array as $item)
                yield $item[0] => $item[1];

        });

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
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Lazy;
     *
     * $collection = new Lazy(fn() => yield from ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->toArray();
     *
     * // ['firstname', 'John'], ['lastname', 'Doe'], ['age', 25], [10, 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @return array<array{TKey, TValue}> Object as an array.
     */
    public function toArray ():array {

        $result = [];
        foreach ($this as $key => $value)
            $result[] = [$key, $value];

        return $result;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Lazy;
     *
     * $collection = new Lazy(fn() => yield from ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->transform(fn($value, $key) => $key === 'age' ? $value.'-1' : $value)
     *
     * // ['firstname', 'John'], ['lastname', 'Doe'], ['age', '25-1'], [10, 2]
     * </code>
     *
     * @since 1.0.0
     */
    public function transform (callable $callback):self {

        $storage = ($this->storage)();

        $this->storage = static function () use ($callback, $storage) {
            foreach ($storage as $key => $value)
                yield $key => $callback($value, $key);
        };

        return $this;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Lazy;
     *
     * $collection = new Lazy(fn() => yield from ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->applyToKeys(fn($value, $key) => $value === 'Doe' ? $key.'-1' : $key)
     *
     * // ['firstname', 'John'], ['lastname-1', 'Doe'], ['age', 25], [10, 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses static::transformKeys() To apply the callback to the keys of the data structure.
     */
    public function applyToKeys (callable $callback):static {

        return (clone $this)->transformKeys($callback); // @phpstan-ignore return.type

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Lazy;
     *
     * $collection = new Lazy(fn() => yield from ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->transformKeys(fn($value, $key) => $value === 'Doe' ? $key.'-1' : $key)
     *
     * // ['firstname', 'John'], ['lastname-1', 'Doe'], ['age', 25], [10, 2]
     * </code>
     *
     * @since 1.0.0
     */
    public function transformKeys (callable $callback):self {

        $storage = ($this->storage)();

        $this->storage = static function () use ($callback, $storage) {
            foreach ($storage as $key => $value)
                yield $callback($value, $key) => $value;
        };

        return $this;

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Lazy;
     *
     * $collection = new Lazy(fn() => yield from ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->filter(fn($value, $key) => $value !== 25);
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 10 => 2]
     * </code>
     * You can force early break:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Lazy;
     * use FireHub\Core\Support\Enums\ControlFlowSignal;
     *
     * $collection = new Lazy(fn() => yield from ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
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

        return new static (function () use ($callback) {

            foreach ($this as $key => $value) {

                $result = $callback($value, $key);

                if ($result === true) {
                    yield $key => $value;
                    continue;
                }

                if ($result === ControlFlowSignal::BREAK) break;

            }

        });

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function jsonSerialize ():array {

        return $this->toArray();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy::invoke() To invoke storage.
     */
    public function getIterator ():Traversable {

        return $this->invoke();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy::toArray() To get data structure an array.
     *
     * @return array<array{TKey, TValue}> An associative array of key/value pairs that represent the serialized form
     * of the object.
     */
    public function __serialize ():array {

        return $this->toArray();

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy::invoke() To invoke storage.
     *
     * @param array<array{TKey, TValue}> $data <p>
     * Serialized data.
     * </p>
     *
     * @phpstan-ignore-next-line method.childParameterType
     */
    public function __unserialize (array $data):void {

        $this->storage = static function () use ($data) {

            foreach ($data as $item)
                yield $item[0] => $item[1];

        };

        $this->invoke();

    }

    /**
     * ### Invoke storage
     * @since 1.0.0
     *
     * @return Generator<TKey, TValue> Storage data.
     */
    private function invoke ():Generator {

        yield from ($this->storage)();

    }

}