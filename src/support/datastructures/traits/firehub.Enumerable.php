<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 7.0
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\DataStructures\Traits;

use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
use FireHub\Core\Support\DataStructures\Linear\ {
    Indexed, Associative, Lazy
};
use FireHub\Core\Support\DataStructures\Operation\CountBy;
use FireHub\Core\Support\DataStructures\Function\ {
    Combine, Keys, Values
};
use FireHub\Core\Support\Utils\PHPUtil;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use FireHub\Core\Support\Enums\JSON\ {
    Flag, Flags\Decode, Flags\Encode
};
use FireHub\Core\Support\Exceptions\Data\UnserializeFailedException;
use FireHub\Core\Support\LowLevel\ {
    Data, DataIs, DateAndTime, Iterator, JSON
};

use const FireHub\Core\Support\Constants\Number\MAX;

/**
 * ### Enumerable data structure methods that every element meets a given criterion
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 */
trait Enumerable {

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * Associative::fromJson('{"firstname":"John","lastname":"Doe","age":25,"10":2}');
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\DecodingException If JSON decoding throws an error.
     *
     * @note All string data for $json parameter must be UTF-8 encoded.
     * @note Method already includes Flag::JSON_THROW_ON_ERROR.
     */
    public static function fromJson (string $json, int $depth = 512, Flag|Decode ...$flags):static {

        return static::fromArray( // @phpstan-ignore return.type
            DataIs::array($data = JSON::decode($json, true, $depth, ...$flags))
                ? $data : []
        );

    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function countBy ():CountBy {

        return new CountBy($this);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $collection->count();
     *
     * // 6
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Iterator::count() To count storage items.
     */
    public function count ():int {

        return Iterator::count($this);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->each(fn($value, $key) => print($key.': '.$value.', '));
     *
     * // firstname: John, lastname: Doe, age: 25, 10: 2,
     * </code>
     * You can limit the number of elements:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->each(fn($value, $key) => print($key.': '.$value.', '), limit: 2);
     *
     * // firstname: John, lastname: Doe,
     * </code>
     * You can also stop at any time with returning false:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->each(fn($value, $key) => $value === 25 ? false : print($key.': '.$value.', '));
     *
     * // firstname: John, lastname: Doe,
     * </code>
     *
     * @since 1.0.0
     */
    public function each (callable $callback, int $limit = MAX):static {

        $counter = 0;

        foreach ($this as $key => $value)
            if ($counter++ >= $limit || $callback($value, $key) === ControlFlowSignal::BREAK) break;

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
     * $collection->when(
     *     true,
     *     fn($collection) => $collection['middlename'] = 'Marry',
     *     fn($collection) => $collection['middlename'] = 'Jenny'
     * );
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'middlename' => 'Marry']
     * </code>
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->when(
     *     false,
     *     fn($collection) => $collection['middlename'] = 'Marry',
     *     fn($collection) => $collection['middlename'] = 'Jenny'
     * );
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'middlename' => 'Jenny']
     * </code>
     *
     * @since 1.0.0
     */
    public function when (bool $condition, callable $condition_meet, ?callable $condition_not_meet = null):static {

        if ($condition)
            $condition_meet($this);
        else if ($condition_not_meet !== null)
            $condition_not_meet($this);

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
     * $collection->unless(
     *     true,
     *     fn($collection) => $collection['middlename'] = 'Marry',
     *     fn($collection) => $collection['middlename'] = 'Jenny'
     * );
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'middlename' => 'Jenny']
     * </code>
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->unless(
     *     false,
     *     fn($collection) => $collection['middlename'] = 'Marry',
     *     fn($collection) => $collection['middlename'] = 'Jenny'
     * );
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'middlename' => 'Marry']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Traits\Enumerable::when() As opposite od unless.
     */
    public function unless (bool $condition, callable $condition_meet, ?callable $condition_not_meet = null):static {

        $this->when(!$condition, $condition_meet, $condition_not_meet);

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
     * $collection->apply(fn($value) => $value.'-1')
     *
     * // ['firstname' => 'John-1', 'lastname' => 'Doe-1', 'age' => '25-1', 10 => '2-1']
     * </code>
     * Transform with keys:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->apply(fn($value, $key) => $key === 'age' ? $value.'-1' : $value)
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => '25-1', 10 => 2]
     * </code>
     *
     * @since 1.0.0
     */
    public function apply (callable $callback):static {

        return (clone $this)->transform($callback); // @phpstan-ignore return.type

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $keys = $collection->keys();
     *
     * // ['firstname', 'lastname', 'age', 10]
     * </code>
     * You can use function to filter keys based on a callback result:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $keys = $collection->keys(fn($value, $key) => $value !== 25);
     *
     * // ['firstname', 'lastname', 10]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Function\Keys As function.
     */
    public function keys (?callable $callback = null):Indexed {

        return new Keys($this)($callback);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $values = $collection->values();
     *
     * // ['John', 'Doe', 25, 2]
     * </code>
     * You can use function to filter values based on a callback result:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $values = $collection->values(fn($value, $key) => $key !== 'age');
     *
     * // ['John', 'Doe', 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Function\Values As function.
     */
    public function values (?callable $callback = null):Indexed {

        return new Values($this)($callback);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5]);
     * $collection2 = new Indexed(['one', 'two', 'three', 'four', 'five']);
     *
     * $combined = $collection->combine($collection2);
     *
     * // [1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five']
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Function\Combine As function.
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\KeysAndValuesDiffNumberOfElemsException If arguments $keys and
     * $values don't have the same number of elements.
     */
    public function combine (DataStructures $data_structure):Associative {

        return new Combine($this)($data_structure);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5]);
     *
     * $combined = $collection->throttle(1_000_000);
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::time() To get current Unix timestamp.
     * @uses \FireHub\Core\Support\LowLevel\DateAndTime::microtime() To get current Unix microseconds.
     * @uses \FireHub\Core\Support\Utils\PHPUtil::sleepMicroseconds() To sleep for a number of $microseconds.
     *
     * @todo Get current time with Zwick::now() method.
     */
    public function throttle (int $microseconds):Lazy {

        return new Lazy(function () use ($microseconds) {

            $interval = $microseconds / 1_000_000;
            $next = DateAndTime::time() + DateAndTime::microtime() / 1_000_000 + $interval;

            foreach ($this as $key => $value) {

                yield $key => $value;

                $now = DateAndTime::time() + DateAndTime::microtime() / 1_000_000;

                if ($now < $next)
                    PHPUtil::sleepMicroseconds($microseconds);

                $next += $interval;

            }

        });

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->toJson();
     *
     * // {"firstname":"John","lastname":"Doe","age":25,"10":2}
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\JSON::encode() As JSON representation of an object.
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException If JSON encoding throws an error.
     *
     * @note All string data must be UTF-8 encoded.
     * @note Method already includes Flag::JSON_THROW_ON_ERROR.
     */
    public function toJson (int $depth = 512, Flag|Encode ...$flags):string {

        return JSON::encode($this, $depth, ...$flags);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $collection->serialize();
     *
     * // O:54:"FireHub\Core\Support\DataStructures\Linear\Associative":4:{s:9:"firstname";s:4:"John";s:8:"lastname";s:3:"Doe";s:3:"age";i:25;i:10;i:2;}
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::serialize() To generate a storable representation of an object.
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\CannotSerializeException If try to serialize an anonymous class,
     * function, or resource.
     *
     * @warning When [[Data#serialize()]] serializes objects, the leading backslash is not included in the class name of
     * namespaced classes for maximum compatibility.
     * @note This is a binary string that may include null bytes and needs to be stored and handled as such.
     * For example, [[Data#serialize()]] output should generally be stored in a BLOB field in a database, rather than
     * a CHAR or TEXT field.
     */
    public function serialize ():string {

        return Data::serialize($this);

    }

    /**
     * {@inheritDoc}
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     *
     * Associative::unserialize('O:54:"FireHub\Core\Support\DataStructures\Linear\Associative":4:{s:9:"firstname";s:4:"John";s:8:"lastname";s:3:"Doe";s:3:"age";i:25;i:10;i:2;}');
     *
     * // ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\Data::unserialize() To create an object from a stored representation.
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\UnserializeFailedException If unserialized data is not
     * of the right class.
     *
     */
    public static function unserialize (string $data, int $max_depth = 4096):static {

        return ($data = Data::unserialize($data, true, $max_depth)) instanceof static
            ? $data : throw new UnserializeFailedException;

    }

}