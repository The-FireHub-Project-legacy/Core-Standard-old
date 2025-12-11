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

namespace FireHub\Core\Support\Utils;

use FireHub\Core\Support\LowLevel\ {
    Arr as ArrLowLevel, DataIs
};

/**
 * ### Arr utility class
 * @since 1.0.0
 */
final class Arr {

    /**
     * ### Pick one or more random values out of the array
     *
     * <code>
     * use FireHub\Core\Support\Utils\Arr;
     *
     * Arr::randomValue([1, 2, 3]);
     *
     * // 2 - (generated randomly)
     * </code>
     * You can use more than one value:
     * <code>
     * use FireHub\Core\Support\Utils\Arr;
     *
     * Arr::randomValue([1, 2, 3], 2);
     *
     * // [2, 1] - (generated randomly)
     * </code>
     *
     * @since 1.0.0
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param non-empty-array<TKey, TValue> $array <p>
     * Array from we're picking random items.
     * </p>
     * @param positive-int $number [optional] <p>
     * Specifies how many entries you want to pick.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from an original array or not.
     * </p>
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\OutOfRangeException If $array is empty, or if $number is out of
     * range.
     *
     * @return ($preserve_keys is true ? ($number is int<2, max> ? array<TKey, TValue> :TValue) : ($number is int<2, max> ? list<TValue> :TValue))
     * If you're picking only one entry, return the value for a random entry.
     * Otherwise, it returns an array of values for the random entries.
     */
    public static function randomValue (array $array, int $number = 1, bool $preserve_keys = false):mixed {

        $keys = ArrLowLevel::random($array, $number);

        if (!DataIs::array($keys))
            return $array[$keys]; // @phpstan-ignore offsetAccess.notFound

        $items = [];
        foreach ($keys as $key)
            // @phpstan-ignore-next-line
            $preserve_keys ? $items[$key] = $array[$key] : $items[] = $array[$key];

        return $items;

    }

}