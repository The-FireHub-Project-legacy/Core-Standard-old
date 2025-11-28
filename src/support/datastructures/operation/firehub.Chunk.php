<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.2
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\DataStructures\Operation;

use FireHub\Core\Support\DataStructures\Contracts\ArrStorage;
use FireHub\Core\Support\DataStructures\Linear\Lazy;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use FireHub\Core\Support\LowLevel\ {
    Arr, Iterables, NumInt
};

/**
 * ### Chunk operations for data structures
 * @since 1.0.0
 *
 * @template TDataStructure of \FireHub\Core\Support\DataStructures\Contracts\ArrStorage
 */
readonly class Chunk {

    /**
     * ### Constructor
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage As parameter.
     *
     * @param TDataStructure $data_structure <p>
     * Instance of data structure.
     * </p>
     *
     * @return void
     */
    public function __construct (
        protected ArrStorage $data_structure
    ) {}

    /**
     * ### Split data structure into chunks until the user function returns true
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Chunk;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $chunk = new Chunk($collection)->when(fn($value, $key) => $value === 25);
     *
     * // [
     * //   [0, Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25])],
     * //   [1, Associative([10 => 2])]
     * // ]
     * </code>
     * You can force early break:
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Associative;
     * use FireHub\Core\Support\DataStructures\Operation\Chunk;
     * use FireHub\Core\Support\Enums\ControlFlowSignal;
     *
     * $collection = new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2]);
     *
     * $chunk = new Chunk($collection)->when(function ($value, $key) {
     *     if ($value === 2) return ControlFlowSignal::BREAK;
     *     return $value === 'Doe';
     * });
     *
     * // [
     * //   [0, Associative(['firstname' => 'John', 'lastname' => 'Doe'])],
     * //   [1, Associative(['age' => 25])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create new array storage
     * data structure from a chunked array.
     * @uses \FireHub\Core\Support\Enums\ControlFlowSignal To force early break.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     *
     * @param callable(value-of<TDataStructure>, key-of<TDataStructure>):(bool|\FireHub\Core\Support\Enums\ControlFlowSignal::BREAK) $callback <p>
     * Function to chunk by.
     * Return **true** to chunk at the current value, **false** to keep value at the current chunk,
     * or `ControlFlowSignal::BREAK` to stop iteration early.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure> New chunked data structure.
     */
    public function when (callable $callback):Lazy {

        return new Lazy(function () use ($callback) {

            $chunks = [];
            foreach ($this->data_structure as $key => $value) {

                /** @var array-key $key */
                $result = $callback($value, $key);

                if ($result === ControlFlowSignal::BREAK) break;

                $chunks[$key] = $value;
                if ($result === true) {

                    yield $this->data_structure::fromArray($chunks);

                    $chunks = [];

                }

            }

            if ($chunks) yield $this->data_structure::fromArray($chunks);

        });

    }

    /**
     * ### Split the data structure into the given group size filling non-terminal groups first
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Chunk;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
     *
     * $chunk = new Chunk($collection)->byStep(3);
     *
     * // [
     * //   [0, Indexed([1, 2, 3, 4])],
     * //   [1, Indexed([5, 6, 7, 8])],
     * //   [1, Indexed([9, 10])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Chunk::when() To split the data structure into chunks until
     * the user function returns true.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the maximum value from 1 and $size_of_group.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     *
     * @param positive-int $size_of_group <p>
     * Size of each group.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure> New chunked data structure.
     */
    public function byStep (int $size_of_group):Lazy {

        $size_of_group = NumInt::max($size_of_group, 1);

        $counter = 0;

        return $this->when(function() use (&$counter, $size_of_group) {

            return ++$counter % $size_of_group === 0;

        });

    }

    /**
     * ### Split the data structure into the given group size filling non-terminal groups first
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Chunk;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
     *
     * $chunk = new Chunk($collection)->in(4);
     *
     * // [
     * //   [0, Indexed([1, 2, 3])],
     * //   [1, Indexed([4, 5, 6])],
     * //   [2, Indexed([7, 8, 9])],
     * //   [3, Indexed([10])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Chunk::byStep() To split the data structure into the
     * given group size filling non-terminal groups first.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the maximum value from 1 and $size_of_group.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::ceil() To round fractions for the number of elements in a group
     * divided by number of groups.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::count() To count elements in the data structure.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     *
     * @param positive-int $number_of_groups <p>
     * Number of groups.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure> New chunked data structure.
     */
    public function in (int $number_of_groups):Lazy {

        return $this->byStep(
            NumInt::max(NumInt::ceil($this->data_structure->count() / $number_of_groups), 1)
        );

    }

    /**
     * ### Split the data structure into the given number of equal groups
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Chunk;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
     *
     * $chunk = new Chunk($collection)->split(4);
     *
     * // [
     * //   [0, Indexed([1, 2, 3])],
     * //   [1, Indexed([4, 5, 6])],
     * //   [2, Indexed([7, 8])],
     * //   [3, Indexed([9, 10])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the maximum value from 1 and $size_of_group.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::divide() To divide the number of elements in a data structure
     * with the number of elements in a group.
     * @uses \FireHub\Core\Support\LowLevel\Arr::slice() To slice the data structure into chunks.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::count() To count elements in the data structure.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create new array storage
     * data structure from a chunked array.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     *
     * @param positive-int $number_of_groups <p>
     * Number of groups.
     * </p>
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure> New chunked data structure.
     */
    public function split (int $number_of_groups):Lazy {

        $number_of_groups = NumInt::max($number_of_groups, 1);

        return new Lazy(function () use ($number_of_groups) {

            $group_size = NumInt::divide($this->data_structure->count(), $number_of_groups);

            $remain = $this->data_structure->count() % $number_of_groups;

            $offset = 0;

            for ($counter = 0; $counter < $number_of_groups; $counter++) {

                $size = $group_size + ($counter < $remain ? 1 : 0);

                yield $this->data_structure::fromArray(
                    Arr::slice($this->data_structure->storage, $offset, $size, true)
                );

                $offset += $size;

            }

        });

    }

    /**
     * ### Split the data structure into the numbers when the value is changed
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Chunk;
     *
     * $collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
     *
     * $chunk = new Chunk($collection)->byValueChange(4);
     *
     * // [
     * //   [0, Indexed(['John'])],
     * //   [1, Indexed(['Jane', 'Jane', 'Jane'])],
     * //   [2, Indexed(['Richard', 'Richard'])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create new array storage
     * data structure from a chunked array.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure> New chunked data structure.
     */
    public function byValueChange ():Lazy {

        return new Lazy(function () {

            $chunk = []; $last = null; $first = true;
            foreach ($this->data_structure as $key => $value) {

                if (!$first && $value !== $last) {

                    yield $this->data_structure::fromArray($chunk);

                    $chunk = [];

                }

                /** @var array-key $key */
                $first = false; $last = $value; $chunk[$key] = $value;

            }

            if ($chunk) yield $this->data_structure::fromArray($chunk);

        });

    }

    /**
     * ### Split the data structure into the numbers when the value is changed
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Chunk;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
     *
     * $chunk = new Chunk($collection)->byWidth(5, 3, 2);
     *
     * // [
     * //   [0, Indexed([1, 2, 3, 4, 5])],
     * //   [1, Indexed([6, 7, 8])],
     * //   [1, Indexed([9, 10])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @param positive-int $width <p>
     * Width of the first chunk.
     * Every non-positive value inside the $widths parameter will be converted to 1.
     * </p>
     * @param positive-int ...$widths [optional] <p>
     * Widths of each chunk.
     * Every non-positive value inside the $widths parameter will be converted to 1.
     * </p>
     *
     * @uses \FireHub\Core\Support\LowLevel\Arr::map() To map the widths to positive integers.
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the maximum value from 1 and $width.
     * @uses \FireHub\Core\Support\LowLevel\Iterables::count() To count elements in the $widths parameter.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create new array storage
     * data structure from a chunked array.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure> New chunked data structure.
     */
    public function byWidth (int $width, int ...$widths):Lazy {

        /** @var non-empty-list<positive-int> $widths */
        $widths = Arr::map([$width, ...$widths], static fn($w) => NumInt::max(1, $w));

        return new Lazy(function () use ($widths) {

            $width_index = 0; $size = $widths[$width_index]; $count = 0; $chunk = [];
            foreach ($this->data_structure as $key => $value) {

                /** @var array-key $key */
                $chunk[$key] = $value; $count++;
                if ($count === $size) {

                    yield $this->data_structure::fromArray($chunk);

                    $chunk = []; $count = 0;
                    $width_index = ($width_index + 1) % Iterables::count($widths);
                    $size = $widths[$width_index]; // @phpstan-ignore offsetAccess.notFound

                }

            }

            if ($chunk) yield $this->data_structure::fromArray($chunk);

        });

    }

    /**
     * ### Split the data structure into the numbers when the value is changed
     *
     * <code>
     * use FireHub\Core\Support\DataStructures\Linear\Indexed;
     * use FireHub\Core\Support\DataStructures\Operation\Chunk;
     *
     * $collection = new Indexed([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
     *
     * $chunk = new Chunk($collection)->sliding(3, 1);
     *
     * // [
     * //   [0, Indexed([1, 2, 3])],
     * //   [1, Indexed([2, 3, 4])],
     * //   [2, Indexed([3, 4, 5])],
     * //   [3, Indexed([4, 5, 6])],
     * //   [4, Indexed([5, 6, 7])],
     * //   [5, Indexed([6, 7, 8])]
     * //   [6, Indexed([7, 8, 9])],
     * //   [7, Indexed([8, 9, 10])]
     * // ]
     * </code>
     *
     * @since 1.0.0
     *
     * @param positive-int $size <p>
     * Size of each chunk.
     * Every non-positive value inside the $widths parameter will be converted to 1.
     * </p>
     * @param positive-int $step [optional] <p>
     * Step between chunks.
     * Every non-positive value inside the $widths parameter will be converted to 1.
     * </p>
     *
     * @uses \FireHub\Core\Support\LowLevel\NumInt::max() To get the maximum value from 1 and $width.
     * @uses \FireHub\Core\Support\LowLevel\Iterables::count() To count elements in the $values parameter.
     * @uses \FireHub\Core\Support\LowLevel\Arr::combine() To combine keys and values into a new array.
     * @uses \FireHub\Core\Support\LowLevel\Arr::slice() To extract a slice of the array.
     * @uses \FireHub\Core\Support\DataStructures\Contracts\ArrStorage::fromArray() To create new array storage
     * data structure from a chunked array.
     * @uses \FireHub\Core\Support\DataStructures\Linear\Lazy As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Linear\Lazy<int, TDataStructure> New chunked data structure.
     *
     * @caution The remaining elements that cannot slide will be ignored.
     */
    public function sliding (int $size, int $step = 1):Lazy {

        $size = NumInt::max($size, 1);
        $step = NumInt::max($step, 1);

        return new Lazy(function () use ($size, $step) {

            $keys = []; $values = [];
            foreach ($this->data_structure as $key => $value) {

                /** @var list<array-key> $keys */
                $keys[] = $key; $values[] = $value;
                if (Iterables::count($values) >= $size) {

                    yield $this->data_structure::fromArray(Arr::combine(
                        Arr::slice($keys, 0, $size),
                        Arr::slice($values, 0, $size)
                    ));

                    $keys = Arr::slice($keys, $step);

                    $values = Arr::slice($values, $step);

                }

            }

        });
    }


}