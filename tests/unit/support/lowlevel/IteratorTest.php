<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package Core\Test
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Tests\Unit\Support\LowLevel;

use FireHub\Core\Testing\Base;
use FireHub\Tests\DataProviders\IteratorDataProvider;
use FireHub\Core\Support\LowLevel\ {
    Iterables, Iterator
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};
use Countable, SplFixedArray;

/**
 * ### Test iterables low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Iterables::class)]
#[CoversClass(Iterator::class)]
final class IteratorTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed<|Countable $arr
     * @param non-negative-int $count
     *
     * @return void
     */
    #[DataProviderExternal(IteratorDataProvider::class, 'listArray')]
    #[DataProviderExternal(IteratorDataProvider::class, 'associativeArray')]
    #[DataProviderExternal(IteratorDataProvider::class, 'multidimensionalArray')]
    #[DataProviderExternal(IteratorDataProvider::class, 'iterator')]
    public function testCount (array|Countable $arr, int $count):void {

        $this->assertSame($count, Iterables::count($arr));
        $this->assertSame($count, Iterator::count($arr));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed<|Countable $arr
     * @param non-negative-int $count
     *
     * @return void
     */
    #[TestWith([[1, 2, 3], 3])]
    #[TestWith([['one' => [1, 2, 3], 'two' => [4, 5, 6], 'three' => [7, 8, 9]], 12])]
    public function testCountRecursive (array|Countable $arr, int $count):void {

        $this->assertSame($count, Iterables::count($arr, true));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $arr
     *
     * @return void
     */
    #[TestWith([['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testInternalPointers (array $arr):void {

        Iterables::reset($arr);

        $this->assertSame(1, Iterables::current($arr));

        $this->assertSame(2, Iterables::next($arr));

        $this->assertSame(1, Iterables::prev($arr));

        $this->assertSame(3, Iterables::end($arr));

        $this->assertSame('three', Iterables::key($arr));

    }

    /**
     * @since 1.0.0
     *
     * @param iterable $arr
     * @param non-negative-int $count
     *
     * @return void
     */
    #[DataProviderExternal(IteratorDataProvider::class, 'listArray')]
    #[DataProviderExternal(IteratorDataProvider::class, 'associativeArray')]
    #[DataProviderExternal(IteratorDataProvider::class, 'multidimensionalArray')]
    #[DataProviderExternal(IteratorDataProvider::class, 'iterator')]
    public function testToArray (iterable $arr, int $count):void {

        $this->assertIsArray(Iterator::toArray($arr));

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testApply ():void {

        $iterator = new SplFixedArray(3);
        $iterator[0] = 1;
        $iterator[1] = 2;
        $iterator[2] = 3;

        Iterator::apply($iterator, function (...$param) use ($iterator) {
            foreach ($param as $key => $value) {
                $iterator[$key] = $value + 1;
            }
            return true;

        },$iterator->toArray());

        $this->assertSame([2, 3, 4], $iterator->toArray());

    }

}