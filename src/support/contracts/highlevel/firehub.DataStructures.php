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
use FireHub\Core\Support\DataStructures\Linear\ {
    Indexed, Associative, Lazy
};
use FireHub\Core\Support\DataStructures\Operation\ {
    Contains, CountBy, Ensure, Find, Is
};

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
     * ### Contains operations for data structures
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Contains As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\Contains<$this> Contains operation class.
     */
    public function contains ():Contains;

    /**
     * ### Ensure operations for data structures
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Ensure As return.
     *
         * @return \FireHub\Core\Support\DataStructures\Operation\Ensure<$this> Ensure operation class.
     */
    public function ensure ():Ensure;

    /**
     * ### Find operations for data structures
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Find As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\Find<$this> Find operation class.
     */
    public function find ():Find;

    /**
     * ### Check is operations for data structures
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Is As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\Is<$this> Check is operation class.
     */
    public function is ():Is;

    /**
     * ### Call a user-generated function on each item in the data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Constants\Number\MAX As default limit.
     * @uses \FireHub\Core\Support\Enums\ControlFlowSignal As signal.
     *
     * @param callable(TValue, TKey):(void|\FireHub\Core\Support\Enums\ControlFlowSignal::BREAK) $callback <p>
     * Function to call on each item in a data structure.
     * Return **void** or `ControlFlowSignal::BREAK` to stop iteration early.
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
     * ### Creates a new data structure with applied callback to the elements of the data structure
     * @since 1.0.0
     *
     * @param callable(TValue, TKey=):TValue $callback <p>
     * A callable to run for each element in a data structure.
     * </p>
     *
     * @return static<TKey, TValue> New data structure containing the results of applying the callback function to
     * the corresponding values of a data structure.
     */
    public function apply (callable $callback):static;

    /**
     * ### Applies the callback to the elements of the data structure
     * @since 1.0.0
     *
     * @param callable(TValue, TKey=):TValue $callback <p>
     * A callable to run for each element in a data structure.
     * </p>
     *
     * @return $this The same data structure with applied callback to the corresponding values of a data structure.
     */
    public function transform (callable $callback):self;

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

    /**
     * ### Combines the values of the data structure, as keys, with the values of another data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Associative As return.
     *
     * @template TCombinedKey
     * @template TCombinedValue
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures<TCombinedKey, TCombinedValue> $data_structure <p>
     * Data structure to be used for values.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Associative<TValue&array-key, TCombinedValue> New combined data
     * structure.
     */
    public function combine (DataStructures $data_structure):Associative;

    /**
     * ### Throttle the lazy data structure such that each value is returned after the specified number of seconds
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     *
     * @param positive-int $microseconds <p>
     * Number of microseconds to throttle each value.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<TKey, TValue> New Lazy data structure
     * with throttle from the current data structure.
     */
    public function throttle (int $microseconds):Lazy;

    /**
     * ### Reduces the data structure to a single value, passing the result of each iteration into the later iteration
     * @since 1.0.0
     *
     * @template TReturn
     *
     * @param callable(null|TReturn, TValue):TReturn $callback <p>
     * The callable function.
     * </p>
     * @param null|TReturn $initial [optional] <p>
     * If the optional initial is available, it will be used at the beginning of the process,
     * or as a final result in case the array is empty.
     * </p>
     *
     * @return null|TReturn Resulting value or null if the array is empty and the initial is not passed.
     */
    public function reduce (callable $callback, mixed $initial = null):mixed;

}