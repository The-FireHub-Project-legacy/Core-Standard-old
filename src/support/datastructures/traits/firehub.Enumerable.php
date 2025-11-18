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

use FireHub\Core\Support\Enums\JSON\ {
    Flag, Flags\Decode, Flags\Encode
};
use FireHub\Core\Support\LowLevel\ {
    DataIs, Iterator, JSON
};

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

}