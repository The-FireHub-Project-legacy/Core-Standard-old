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

namespace FireHub\Core\Support\Contracts\HighLevel;

use FireHub\Core\Support\Contracts\ {
    ArrayConvertable, Countable, JsonSerializeConvertable
};
use FireHub\Core\Support\Contracts\Iterator\IteratorAggregate;
use FireHub\Core\Support\Contracts\Magic\SerializableConvertable;
use FireHub\Core\Support\DataStructures\Linear\Indexed;
use FireHub\Core\Support\DataStructures\Operation\CountBy;

use const FireHub\Core\Support\Constants\Number\MAX;

/**
 * ### Data structures
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends \FireHub\Core\Support\Contracts\Iterator\IteratorAggregate<TKey, TValue>
 */
interface DataStructures extends ArrayConvertable, Countable, IteratorAggregate, JsonSerializeConvertable, SerializableConvertable {

    /**
     * ### Count operations for data structures
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\CountBy As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\CountBy<$this> Count operation class.
     */
    public function countBy ():CountBy;

    /**
     * ### Call a user-generated function on each item in the data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Constants\Number\MAX As default limit.
     *
     * @param callable(TValue, TKey):(false|void) $callback <p>
     * Function to call on each item in a data structure.
     * </p>
     * @param positive-int $limit [optional] <p>
     * Maximum number of elements that is allowed to be iterated.
     * </p>
     *
     * @return static This data structure.
     */
    public function each (callable $callback, int $limit = MAX):static;

    /**
     * ### Execute the given callback when the first argument given to the method evaluates to true
     * @since 1.0.0
     *
     * @param bool $condition <p>
     * Condition to meet.
     * </p>
     * @param callable(static):mixed $condition_meet <p>
     * Callback if the condition is met.
     * </p>
     * @param null|callable(static):mixed $condition_not_meet [optional] <p>
     * Callback if the condition is not met.
     * </p>
     *
     * @return static This data structure.
     */
    public function when (bool $condition, callable $condition_meet, ?callable $condition_not_meet = null):static;

    /**
     * ### Execute the given callback unless the first argument given to the method evaluates to true
     * @since 1.0.0
     *
     * @param bool $condition <p>
     * Condition to meet.
     * </p>
     * @param callable(static):mixed $condition_meet <p>
     * Callback if the condition is met.
     * </p>
     * @param null|callable(static):mixed $condition_not_meet [optional] <p>
     * Callback if the condition is not met.
     * </p>
     *
     * @return static This data structure.
     */
    public function unless (bool $condition, callable $condition_meet, ?callable $condition_not_meet = null):static;

    /**
     * ### Get keys from the data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Indexed As return.
     *
     * @param null|callable(TValue, TKey):bool $callback [optional] <p>
     * If specified, then only keys where the user function is true are returned.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Indexed<TKey> Keys from the data structure.
     */
    public function keys (?callable $callback = null):Indexed;

    /**
     * ### Get values from the data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Indexed As return.
     *
     * @param null|callable(TValue, TKey):bool $callback [optional] <p>
     * If specified, then only values where the user function is true are returned.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Indexed<TValue> Values from the data structure.
     */
    public function values (?callable $callback = null):Indexed;

}