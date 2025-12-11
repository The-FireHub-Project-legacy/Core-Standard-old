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
use FireHub\Tests\DataProviders\ArrDataProvider;
use FireHub\Core\Support\Enums\ {
    Order, Sort, String\CaseFolding
};
use FireHub\Core\Support\Exceptions\Arr\ {
    ChunkLengthTooSmallException, KeysAndValuesDiffNumberOfElemsException, OutOfRangeException, SizeInconsistentException
};
use FireHub\Core\Support\LowLevel\Arr;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test array low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Arr::class)]
#[CoversClass(Order::class)]
final class ArrTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param bool $boolean
     * @param array<array-key, mixed> $arr
     * @param mixed $result
     *
     * @return void
     */
    #[TestWith([true, [1, 2, 3], 0.5])]
    #[TestWith([true, ['x', 'y', 'z'], 'e'])]
    #[TestWith([false, ['x', 'y', 'z'], 'y'])]
    public function testAll (bool $boolean, array $arr, mixed $result):void {

        $this->assertSame($boolean, Arr::all($arr, static fn($value) => $value > $result));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $boolean
     * @param array<array-key, mixed> $arr
     * @param mixed $result
     *
     * @return void
     */
    #[TestWith([true, [1, 2, 3], 2])]
    #[TestWith([false, [1, 2, 3], 2.5])]
    #[TestWith([true, ['x', 'y', 'z'], 'y'])]
    #[TestWith([false, ['x', 'y', 'z'], 'e'])]
    public function testAny (bool $boolean, array $arr, mixed $result):void {

        $this->assertSame($boolean, Arr::any($arr, static fn($value) => $value === $result));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $boolean
     * @param array-key $key
     * @param array<array-key, mixed> $arr
     *
     * @return void
     */
    #[TestWith([true, 2, [1, 2, 3]])]
    #[TestWith([false, 3, [1, 2, 3]])]
    #[TestWith([false, 'x', [null, 2, 3]])]
    public function testKeyExist (bool $boolean, int|string $key, array $arr):void {

        $this->assertSame($boolean, Arr::keyExist($key, $arr));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $boolean
     * @param mixed $value
     * @param array<array-key, mixed> $arr
     *
     * @return void
     */
    #[TestWith([true, 2, [1, 2, 3]])]
    #[TestWith([false, 4, [1, 2, 3]])]
    #[TestWith([true, null, [null, 2, 3]])]
    public function testInArray (bool $boolean, mixed $value, array $arr):void {

        $this->assertSame($boolean, Arr::inArray($value, $arr));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $arr
     *
     * @return void
     */
    #[DataProviderExternal(ArrDataProvider::class, 'list')]
    public function testIsList (array $arr):void {

        $this->assertTrue(Arr::isList($arr));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $arr
     *
     * @return void
     */
    #[DataProviderExternal(ArrDataProvider::class, 'associative')]
    #[DataProviderExternal(ArrDataProvider::class, 'multidimensional')]
    public function testIsNotList (array $arr):void {

        $this->assertFalse(Arr::isList($arr));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\FailedSortMultiArrayException
     * @throws \FireHub\Core\Support\Exceptions\Arr\SizeInconsistentException
     *
     * @return void
     */
    #[TestWith([[[0, 10, 100, 100]], [[100, 10, 100, 0]]])]
    public function testMultiSort (array $expected, array $actual):void {

        Arr::multiSort($actual);
        $this->assertSame($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $arr
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\FailedSortMultiArrayException
     *
     * @return void
     */
    #[TestWith([[[], [1, 2]]])]
    public function testMultiSortSizeInconsistent (array $arr):void {

        $this->expectException(SizeInconsistentException::class);

        $this->assertTrue(Arr::multiSort($arr));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([['0-x', '1-x', '2-x'], [0, 1, 2]])]
    public function testWalk (array $expected, array $actual):void {

        Arr::walk($actual, static fn(&$value, $key) => $value = $key.'-x');

        $this->assertSame($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([[['a' => 'a-x', 'b' => 'b-x'], '1-x', '2-x'], [['a' => 'r','b' => 'g'], '1' => 'b', '2' => 'y']])]
    public function testWalkRecursive (array $expected, array $actual):void {

        Arr::walkRecursive($actual, static fn(&$value, $key) => $value = $key.'-x');

        $this->assertSame($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param int[] $expected
     * @param array<array-key, mixed> $arr
     *
     * @return void
     */
    #[TestWith([[1 => 1, 2 => 1], [1, 2]])]
    #[TestWith([[], []])]
    public function testCountValues (array $expected, array $arr):void {

        $this->assertSame($expected, Arr::countValues($arr));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param mixed $value
     * @param int $start_index
     * @param int<0, 2147483648> $length
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\OutOfRangeException
     *
     * @return void
     */
    #[TestWith([[1, 1, 1, 1, 1], 1, 0, 5])]
    #[TestWith([[-2 => 1, -1 => 1, 0 => 1], 1, -2, 3])]
    public function testFill (array $expected, mixed $value, int $start_index, int $length):void {

        $this->assertSame($expected, Arr::fill($value, $start_index, $length));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param int $start_index
     * @param int<0, 2147483648> $length
     *
     * @return void
     */
    #[TestWith([1, 0, -5])]
    public function testFillOutOfRangeWithNegativeNumber (mixed $value, int $start_index, int $length):void {

        $this->expectException(OutOfRangeException::class);

        Arr::fill($value, $start_index, $length);

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param int $start_index
     * @param int<0, 2147483648> $length
     *
     * @return void
     */
    #[TestWith([1, 0, PHP_INT_MAX])]
    public function testFillOutOfRangeWithBigNumber (mixed $value, int $start_index, int $length):void {

        $this->expectException(OutOfRangeException::class);

        Arr::fill($value, $start_index, $length);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $keys
     * @param mixed $value
     *
     * @return void
     */
    #[TestWith([[1 => 1, 2 => 1, 3 => 1, '' => 1], [1, 2, 3, null], 1])]
    public function testFillKeys (array $expected, array $keys, mixed $value):void {

        $this->assertSame($expected, Arr::fillKeys($keys, $value));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param \FireHub\Core\Support\Enums\String\CaseFolding $case
     *
     * @return void
     */
    #[TestWith([['ONE' => 1, 'TWO' => 2, 'THREE' => 3], ['one' => 1, 'two' => 2, 'three' => 3], CaseFolding::UPPER])]
    #[TestWith([['one' => 1, 'two' => 2, 'three' => 3], ['ONE' => 1, 'TWO' => 2, 'THREE' => 3], CaseFolding::LOWER])]
    public function testFoldKeys (array $expected, array $actual, CaseFolding $case):void {

        $this->assertSame($expected, Arr::foldKeys($actual, $case));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param positive-int $length
     * @param bool $preserve_keys
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\ChunkLengthTooSmallException
     *
     * @return void
     */
    #[TestWith([[[1, 2], [3]], ['one' => 1, 'two' => 2, 'three' => 3], 2, false])]
    #[TestWith([[['one' => 1], ['two' => 2], ['three' => 3]], ['one' => 1, 'two' => 2, 'three' => 3], 1, true])]
    public function testChunk (array $expected, array $actual, int $length, bool $preserve_keys):void {

        $this->assertSame($expected, Arr::chunk($actual, $length, $preserve_keys));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $arr
     * @param positive-int $length
     * @param bool $preserve_keys
     *
     * @return void
     */
    #[TestWith([['one' => 1, 'two' => 2, 'three' => 3], 0, false])]
    public function testChunkLengthLessThenZero (array $arr, int $length, bool $preserve_keys):void {

        $this->expectException(ChunkLengthTooSmallException::class);

        Arr::chunk($arr, $length, $preserve_keys);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array-key $key
     * @param null|array-key $index
     *
     * @return void
     */
    #[TestWith([[3 => 2, 6 => 5, 9 => 8], ['one' => [1, 2, 3], 'two' => [4, 5, 6], 'three' => [7, 8, 9]], 1, 2])]
    public function testColumn (array $expected, array $actual, int|string $key, null|int|string $index):void {

        $this->assertSame($expected, Arr::column($actual, $key, $index));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $keys
     * @param array<array-key, mixed> $values
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\KeysAndValuesDiffNumberOfElemsException
     *
     * @return void
     */
    #[TestWith([[1 => 1, 2 => 2, 3 => 3], [1, 2, 3], ['one' => 1, 'two' => 2, 'three' => 3]])]
    #[TestWith([['' => 3], [2 => '', 'x' => null, 5 => false], ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testCombine (array $expected, array $keys, array $values):void {

        $this->assertSame($expected, Arr::combine($keys, $values));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $keys
     * @param array<array-key, mixed> $values
     *
     * @return void
     */
    #[TestWith([[], ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testCombineDiffElementNumber ( array $keys, array $values):void {

        $this->expectException(KeysAndValuesDiffNumberOfElemsException::class);

        Arr::combine($keys, $values);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> ...$excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testDifference (array $expected, array $actual, array ...$excludes):void {

        $this->assertSame($expected, Arr::difference($actual, ...$excludes));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testDifferenceFunc (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::differenceFunc($actual, $excludes,
                static function ($value_a, $value_b) {
                    if ($value_a === $value_b && $value_a !== 'two') return 0;
                    return ($value_a > $value_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> ...$excludes
     *
     * @return void
     */
    #[TestWith([
        ['age' => 25],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm']
    ])]
    public function testDifferenceKey (array $expected, array $actual, array ...$excludes):void {

        $this->assertSame($expected, Arr::differenceKey($actual, ...$excludes));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'lastname' => 'Doe', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testDifferenceKeyFunc (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::differenceKeyFunc($actual, $excludes,
                static function ($key_a, $key_b) {
                    if ($key_a === $key_b && $key_a !== 'lastname') return 0;
                    return ($key_a > $key_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> ...$excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testDifferenceAssoc (array $expected, array $actual, array ...$excludes):void {

        $this->assertSame($expected, Arr::differenceAssoc($actual, ...$excludes));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'lastname' => 'Doe', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['age' => 25]
    ])]
    public function testDifferenceAssocFuncValue (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::differenceAssocFuncValue($actual, $excludes,
                static function ($value_a, $value_b) {
                    if ($value_a === $value_b && $value_a !== 'Doe') return 0;
                    return ($value_a > $value_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'lastname' => 'Doe', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testDifferenceAssocFuncKey (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::differenceAssocFuncKey($actual, $excludes,
                static function ($key_a, $key_b) {
                    if ($key_a === $key_b && $key_a !== 'lastname') return 0;
                    return ($key_a > $key_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'lastname' => 'Doe', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testDifferenceAssocFuncKeyValue (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::differenceAssocFuncKeyValue($actual, $excludes,
                static function ($value_a, $value_b) {
                    if ($value_a === $value_b && $value_a !== 'Doe') return 0;
                    return ($value_a > $value_b) ? 1 : -1;
                }, static function ($key_a, $key_b) {
                    if ($key_a === $key_b && $key_a !== 'lastname') return 0;
                    return ($key_a > $key_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> ...$excludes
     *
     * @return void
     */
    #[TestWith([
        ['lastname' => 'Doe', 'age' => 25],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testIntersect (array $expected, array $actual, array ...$excludes):void {

        $this->assertSame($expected, Arr::intersect($actual, ...$excludes));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['lastname' => 'Doe', 'age' => 25],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testIntersectFunc (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::intersectFunc($actual, $excludes,
                static function ($value_a, $value_b) {
                    if ($value_a === $value_b && $value_a !== 'two') return 0;
                    return ($value_a > $value_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm']
    ])]
    public function testIntersectKeyFunc (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::intersectKeyFunc($actual, $excludes,
                static function ($key_a, $key_b) {
                    if ($key_a === $key_b && $key_a !== 'lastname') return 0;
                    return ($key_a > $key_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> ...$excludes
     *
     * @return void
     */
    #[TestWith([
        ['firstname' => 'John', 'lastname' => 'Doe', 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['firstname' => 'Jane', 'lastname' => 'Doe', 'height' => '160cm']
    ])]
    public function testIntersectKey (array $expected, array $actual, array ...$excludes):void {

        $this->assertSame($expected, Arr::intersectKey($actual, ...$excludes));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> ...$excludes
     *
     * @return void
     */
    #[TestWith([
        ['lastname' => 'Doe', 'age' => 25],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testIntersectAssoc (array $expected, array $actual, array ...$excludes):void {

        $this->assertSame($expected, Arr::intersectAssoc($actual, ...$excludes));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['age' => 25],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testIntersectAssocFuncValue (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::intersectAssocFuncValue($actual, $excludes,
                static function ($value_a, $value_b) {
                    if ($value_a === $value_b && $value_a !== 'Doe') return 0;
                    return ($value_a > $value_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['age' => 25],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testIntersectAssocFuncKey (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::intersectAssocFuncKey($actual, $excludes,
                static function ($key_a, $key_b) {
                    if ($key_a === $key_b && $key_a !== 'lastname') return 0;
                    return ($key_a > $key_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> $excludes
     *
     * @return void
     */
    #[TestWith([
        ['age' => 25],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['lastname' => 'Doe', 'age' => 25]
    ])]
    public function testIntersectAssocFuncKeyValue (array $expected, array $actual, array $excludes):void {

        $this->assertSame(
            $expected,
            Arr::intersectAssocFuncKeyValue($actual, $excludes,
                static function ($value_a, $value_b) {
                    if ($value_a === $value_b && $value_a !== 'Doe') return 0;
                    return ($value_a > $value_b) ? 1 : -1;
                }, static function ($key_a, $key_b) {
                    if ($key_a === $key_b && $key_a !== 'lastname') return 0;
                    return ($key_a > $key_b) ? 1 : -1;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([['three' => 3], ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testFilter (array $expected, array $actual):void {

        $this->assertSame(
            $expected,
            Arr::filter($actual, static function ($value, $key) {
                    return $key !== 'one' && $value > 2;
                }
            )
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([[0 => 'foo', 2 => -1], [0 => 'foo', 1 => false, 2 => -1, 3 => null, 4 => '', 5 => '0', 6 => 0]])]
    public function testFilterWithoutCallback (array $expected, array $actual):void {

        $this->assertSame($expected, Arr::filter($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([[1 => 'one', 2 => 'two', 3 => 'three'], ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testFlip (array $expected, array $actual):void {

        $this->assertSame($expected, Arr::flip($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param mixed $filter
     *
     * @return void
     */
    #[TestWith([['one', 'two', 'three'], ['one' => 1, 'two' => 2, 'three' => 3], null])]
    #[TestWith([[''], ['' => 3], null])]
    #[TestWith([['two'], ['one' => 1, 'two' => 2, 'three' => 3], 2])]
    public function testKeys (array $expected, array $actual, mixed $filter):void {

        $this->assertSame($expected, Arr::keys($actual, $filter));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([[1, 2, 3], ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testValues (array $expected, array $actual):void {

        $this->assertSame($expected, Arr::values($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([['one' => '1-x', 'two' => '2-x', 'three' => '3-x'], ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testMap (array $expected, array $actual):void {

        $this->assertSame($expected, Arr::map($actual, static fn($value) => $value.'-x'));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> ...$actual
     *
     * @return void
     */
    #[TestWith([
        [1, 2, 3, 'one' => 1, 'two' => 2, 'three' => 3],
        [1, 2, 3],
        ['one' => 1, 'two' => 2, 'three' => 3]
    ])]
    public function testMerge (array $expected, array ...$actual):void {

        $this->assertSame($expected, Arr::merge(...$actual));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> ...$actual
     *
     * @return void
     */
    #[TestWith([
        ['one' => [1, 1], 'two' => [2, 2], 'three' => [3, 3]],
        ['one' => 1, 'two' => 2, 'three' => 3],
        ['one' => 1, 'two' => 2, 'three' => 3]
    ])]
    public function testMergeRecursive (array $expected, array ...$actual):void {

        $this->assertSame($expected, Arr::mergeRecursive(...$actual));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param int $length
     * @param mixed $value
     *
     * @return void
     */
    #[TestWith([[1, 2, 3, 'x', 'x'], [1, 2, 3], 5, 'x'])]
    #[TestWith([['x', 'x', 1, 2, 3], [1, 2, 3], -5, 'x'])]
    #[TestWith([[1, 2, 3], [1, 2, 3], 2, 'x'])]
    public function testPad (array $expected, array $actual, int $length, mixed $value):void {

        $this->assertSame($expected, Arr::pad($actual, $length, $value));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> ...$replacements
     *
     * @return void
     */
    #[TestWith([
        ['one' => 6, 'two' => 7, 'three' => 3],
        ['one' => 1, 'two' => 2, 'three' => 3],
        ['one' => 6, 'two' => 7]
    ])]
    public function testReplace (array $expected, array $actual, array ...$replacements):void {

        $this->assertSame($expected, Arr::replace($actual, ...$replacements));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param array<array-key, mixed> ...$replacements
     *
     * @return void
     */
    #[TestWith([
        ['one' => [4, 2, 3], 'two' => [4, 5, 6], 'three' => [7, 8, 9]],
        ['one' => [1, 2, 3], 'two' => [4, 5, 6], 'three' => [7, 8, 9]],
        ['one' => [4]]
    ])]
    public function testReplaceRecursive (array $expected, array $actual, array ...$replacements):void {

        $this->assertSame($expected, Arr::replaceRecursive($actual, ...$replacements));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param bool $preserve_keys
     *
     * @return void
     */
    #[TestWith([[3, 2, 1], [1, 2, 3], false])]
    #[TestWith([[2 => 3, 1 => 2, 0 => 1], [1, 2, 3], true])]
    public function testReverse (array $expected, array $actual, bool $preserve_keys):void {

        $this->assertSame($expected, Arr::reverse($actual, $preserve_keys));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param int $offset
     * @param null|int $length
     * @param bool $preserve_keys
     *
     * @return void
     */
    #[TestWith([[2, 3], [1, 2, 3], 1, null, false])]
    #[TestWith([[1 => 2, 2 => 3], [1, 2, 3], 1, null, true])]
    #[TestWith([[0 => 3], [1, 2, 3], -1, null, false])]
    public function testSlice (array $expected, array $actual, int $offset, ?int $length, bool $preserve_keys):void {

        $this->assertSame($expected, Arr::slice($actual, $offset, $length, $preserve_keys));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param int $offset
     * @param null|int $length
     * @param array<array-key, mixed> $replacement
     *
     * @return void
     */
    #[TestWith([[3], [1, 2, 3], 0, 2, []])]
    #[TestWith([[5, 3], [1, 2, 3], 0, 2, [5]])]
    #[TestWith([[1, 3], [1, 2, 3], -2, 1, []])]
    #[TestWith([[1, 3], [1, 2, 3], -2, 1, []])]
    public function testSplice (array $expected, array $actual, int $offset, ?int $length, array $replacement):void {

        Arr::splice($actual, $offset, $length, $replacement);

        $this->assertSame($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([[0 => 1, 3 => 2, 4 => 3], [1, 1, 1, 2, 3]])]
    #[TestWith([['one' => 1, 'two' => 2, 'three' => 3], ['one' => 1, 'one2' => 1, 'two' => 2, 'three' => 3]])]
    public function testUnique (array $expected, array $actual):void {

        $this->assertSame($expected, Arr::unique($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param int|float|string $start
     * @param int|float|string $end
     * @param positive-int|float $step
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\OutOfRangeException
     *
     * @return void
     */
    #[TestWith([[1, 3, 5, 7, 9], 1, 10, 2])]
    public function testRange (array $expected, int|float|string $start, int|float|string $end, int|float $step):void {

        $this->assertSame($expected, Arr::range($start, $end, $step));

    }

    /**
     * @since 1.0.0
     *
     * @param int|float|string $start
     * @param int|float|string $end
     * @param positive-int|float $step
     *
     * @return void
     */
    #[TestWith([1, 10, -2])]
    public function testRangeNegativeStep (int|float|string $start, int|float|string $end, int|float $step):void {

        $this->expectException(OutOfRangeException::class);

        Arr::range($start, $end, $step);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $actual
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\OutOfRangeException
     *
     * @return void
     */
    #[TestWith([['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testRandom (array $actual):void {

        $this->assertArrayHasKey(Arr::random($actual), $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $actual
     * @param positive-int $number
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\OutOfRangeException
     *
     * @return void
     */
    #[TestWith([['one' => 1, 'two' => 2, 'three' => 3], 2])]
    public function testRandomMultiple (array $actual, int $number):void {

        $expected = Arr::random($actual, $number);

        $this->assertIsArray($expected);
        $this->assertCount(2, $expected);

        foreach ($expected as $key)
            $this->assertArrayHasKey($key, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $actual
     * @param positive-int $number
     *
     * @return void
     */
    #[TestWith([[1, 1, 1, 2, 3], 10])]
    public function testRandomBiggerThenArray (array $actual, int $number):void {

        $this->expectException(OutOfRangeException::class);

        Arr::random($actual, $number);

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $expected
     * @param array<array-key, mixed> $actual
     * @param mixed $initial
     *
     * @return void
     */
    #[TestWith([6, [1, 2, 3], null])]
    #[TestWith([9, [1, 2, 3], 3])]
    public function testReduce (mixed $expected, array $actual, mixed $initial):void {

        $this->assertSame(
            $expected,
            Arr::reduce($actual, static fn($carry, $item) => $carry + $item, $initial)
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([[1, 2], [1, 2, 3]])]
    public function testPop (array $expected, array $actual):void {

        Arr::pop($actual);

        $this->assertSame($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param mixed ...$values
     *
     * @return void
     */
    #[TestWith([[1, 2, 3, 4], [1, 2, 3], 4])]
    public function testPush (array $expected, array $actual, mixed ...$values):void {

        Arr::push($actual, ...$values);

        $this->assertSame($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([[2, 3], [1, 2, 3]])]
    public function testShift (array $expected, array $actual):void {

        Arr::shift($actual);

        $this->assertSame($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param mixed ...$values
     *
     * @return void
     */
    #[TestWith([[0, 1, 2, 3], [1, 2, 3], 0])]
    public function testUnshift (array $expected, array $actual, mixed ...$values):void {

        Arr::unshift($actual, ...$values);

        $this->assertSame($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param null|array-key $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([1, ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testFirst (mixed $expected, array $actual):void {

        $this->assertSame($expected, Arr::first($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param null|array-key $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([3, ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testLast (mixed $expected, array $actual):void {

        $this->assertSame($expected, Arr::last($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param null|array-key $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith(['one', ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testFirstKey (null|int|string $expected, array $actual):void {

        $this->assertSame($expected, Arr::firstKey($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param null|array-key $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith(['three', ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testLastKey (null|int|string $expected, array $actual):void {

        $this->assertSame($expected, Arr::lastKey($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int|float $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([6, ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testProduct (int|float $expected, array $actual):void {

        $this->assertSame($expected, Arr::product($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int|float $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([6, ['one' => 1, 'two' => 2, 'three' => 3]])]
    public function testSum (int|float $expected, array $actual):void {

        $this->assertSame($expected, Arr::sum($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int|string|false $expected
     * @param array<array-key, mixed> $actual
     * @param mixed $value
     *
     * @return void
     */
    #[TestWith(['two', ['one' => 1, 'two' => 2, 'three' => 3], 2])]
    #[TestWith([false, ['one' => 1, 'two' => 2, 'three' => 3], 5])]
    public function testSearch (int|string|false $expected, array $actual, mixed $value):void {

        $this->assertSame($expected, Arr::search($actual, $value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([2, ['one' => 1, 'two' => 2, 'three' => 3]])]
    #[TestWith([null, ['one' => 1, 'three' => 3]])]
    public function testFind (mixed $expected, array $actual):void {

        $this->assertSame(
            $expected,
            Arr::find($actual, static fn($value, $key) => $value === 2)
        );

    }

    /**
     * @since 1.0.0
     *
     * @param null|array-key $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith(['two', ['one' => 1, 'two' => 2, 'three' => 3]])]
    #[TestWith([null, ['one' => 1, 'three' => 3]])]
    public function testFindKey (mixed $expected, array $actual):void {

        $this->assertSame(
            $expected,
            Arr::findKey($actual, static fn($value, $key) => $value === 2)
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[DataProviderExternal(ArrDataProvider::class, 'list')]
    public function testShuffle (array $actual):void {

        $expected = $actual;

        Arr::shuffle($actual);

        $this->assertEqualsCanonicalizing($expected, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param \FireHub\Core\Support\Enums\Order $order
     * @param \FireHub\Core\Support\Enums\Sort $flag
     * @param bool $preserve_keys
     *
     * @return void
     */
    #[TestWith([
        ['male', 'John', 'Doe', 25, '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'],
        Order::ASC,
        Sort::BY_REGULAR,
        false
    ])]
    #[TestWith([
        ['gender' => 'male', 'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'],
        Order::ASC,
        Sort::BY_REGULAR,
        true
    ])]
    #[TestWith([
        ['firstname' => 'John', 'lastname' => 'Doe', 'gender' => 'male', 'age' => 25, 'height' => '190cm'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'],
        Order::DESC,
        Sort::BY_NUMERIC,
        true
    ])]
    public function testSort (array $expected, array $actual, Order $order, Sort $flag, bool $preserve_keys):void {

        Arr::sort($actual, $order->reverse(), $flag, $preserve_keys);

        $this->assertSame($expected, $actual);
    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param \FireHub\Core\Support\Enums\Order $order
     * @param \FireHub\Core\Support\Enums\Sort $flag
     *
     * @return void
     */
    #[TestWith([
        ['age' => 25, 'firstname' => 'John', 'gender' => 'male', 'height' => '190cm', 'lastname' => 'Doe'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'],
        Order::ASC,
        Sort::BY_REGULAR
    ])]
    #[TestWith([
        ['lastname' => 'Doe', 'height' => '190cm', 'gender' => 'male', 'firstname' => 'John', 'age' => 25],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'],
        Order::DESC,
        Sort::BY_STRING
    ])]
    public function testSortByKeys (array $expected, array $actual, Order $order, Sort $flag):void {

        Arr::sortByKeys($actual, $order, $flag);

        $this->assertSame($expected, $actual);
    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     * @param bool $preserve_keys
     *
     * @return void
     */
    #[TestWith([
        ['190cm', 25, 'Doe', 'John', 'male'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'],
        false
    ])]
    #[TestWith([
        ['height' => '190cm', 'age' => 25, 'lastname' => 'Doe', 'firstname' => 'John', 'gender' => 'male'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male'],
        true
    ])]
    public function testSortBy (array $expected, array $actual, bool $preserve_keys):void {

        Arr::sortBy($actual, static function ($current, $next) {
            if ($current === $next) return 0;
            return ($current < $next) ? -1 : 1;
        }, $preserve_keys);

        $this->assertSame($expected, $actual);
    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([
        ['age' => 25, 'firstname' => 'John', 'gender' => 'male', 'height' => '190cm', 'lastname' => 'Doe'],
        ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 'height' => '190cm', 'gender' => 'male']
    ])]
    public function testSortKeysBy (array $expected, array $actual):void {

        Arr::sortKeysBy($actual, static function ($current, $next) {
            if ($current === $next) return 0;
            return ($current < $next) ? -1 : 1;
        });

        $this->assertSame($expected, $actual);
    }

}