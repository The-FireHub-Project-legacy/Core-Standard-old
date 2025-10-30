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
use FireHub\Tests\DataProviders\IntDataProvider;
use FireHub\Core\Support\Enums\Number\ {
    LogBase, Round
};
use FireHub\Core\Support\Exceptions\Number\ {
    ArithmeticException, DivideByZeroException
};
use FireHub\Core\Support\LowLevel\ {
    Num, NumInt
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, DependsExternal, Group, Small, TestWith
};

/**
 * ### Test integer number low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Num::class)]
#[CoversClass(NumInt::class)]
#[CoversClass(LogBase::class)]
#[CoversClass(Round::class)]
final class NumIntTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param int $int
     *
     * @return void
     */
    #[DataProviderExternal(IntDataProvider::class, 'positive')]
    #[DataProviderExternal(IntDataProvider::class, 'negative')]
    #[DataProviderExternal(IntDataProvider::class, 'null')]
    public function testAbsolute (int $int):void {

        $this->assertIsInt(NumInt::absolute($int));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param int $actual
     * @param \FireHub\Core\Support\Enums\Number\LogBase $base
     *
     * @return void
     */
    #[TestWith([2.302585092994046, 10, LogBase::E])]
    #[TestWith([6.282411788757109, 10, LogBase::LOG2E])]
    #[TestWith([-2.7607859935346917, 10, LogBase::LOG10E])]
    #[TestWith([-6.282411788757108, 10, LogBase::LN2])]
    #[TestWith([2.7607859935346912, 10, LogBase::LN10])]
    #[DependsExternal(NumFloatTest::class, 'testRound')]
    public function testLog (float $expected, int $actual, LogBase $base):void {

        $this->assertSame(NumInt::round($expected, 5), NumInt::round(NumInt::log($actual, $base), 5));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param int $actual
     *
     * @return void
     */
    #[TestWith([2.3978952727983707, 10])]
    #[DependsExternal(NumFloatTest::class, 'testRound')]
    public function testLog1p (float $expected, int $actual):void {

        $this->assertSame(NumInt::round($expected, 5), NumInt::round(NumInt::log1p($actual), 5));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param int $actual
     *
     * @return void
     */
    #[TestWith([1.0, 10])]
    public function testLog10 (float $expected, int $actual):void {

        $this->assertSame($expected, NumInt::log10($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([8, [2, 6, 8]])]
    public function testMax (int $expected, array $actual):void {

        $this->assertSame($expected, NumInt::max(...$actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([2, [2, 6, 8]])]
    public function testMin (int $expected, array $actual):void {

        $this->assertSame($expected, NumInt::min(...$actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float|int $expected
     * @param float|int $base
     * @param float|int $exponent
     *
     * @return void
     */
    #[TestWith([256, 2, 8])]
    public function testPower (float|int $expected, float|int $base, float|int $exponent):void {

        $this->assertSame($expected, NumInt::power($base, $exponent));

    }

    /**
     * @since 1.0.0
     *
     * @param string $expected
     * @param float $actual
     * @param non-negative-int $decimals
     * @param string $decimal_separator
     * @param string $thousands_separator
     *
     * @return void
     */
    #[TestWith(['5,000', 5000, 0, '.', ','])]
    #[TestWith(['456', 456, 0, ',', '.'])]
    public function testFormat (string $expected, float $actual, int $decimals, string $decimal_separator, string $thousands_separator):void {

        $this->assertSame($expected, NumInt::format($actual, $decimals, $decimal_separator, $thousands_separator));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param int $actual
     *
     * @return void
     */
    #[TestWith([0.7853981633974483, 45])]
    #[DependsExternal(NumFloatTest::class, 'testRound')]
    public function testDegreesToRadian (float $expected, int $actual):void {

        $this->assertSame(NumInt::round($expected, 5), NumInt::round(NumInt::degreesToRadian($actual), 5));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([45.0, 0.7853981633974483])]
    #[DependsExternal(NumFloatTest::class, 'testRound')]
    public function testRadianToDegrees (float $expected, float $actual):void {

        $this->assertSame(NumInt::round($expected, 5), NumInt::round(NumInt::radianToDegrees($actual), 5));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param int $actual
     *
     * @return void
     */
    #[TestWith([9744803446.2489, 23])]
    #[DependsExternal(NumFloatTest::class, 'testRound')]
    public function testExponent (float $expected, int $actual):void {

        $this->assertSame(NumInt::round($expected, 5), NumInt::round(NumInt::exponent($actual), 5));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param int $actual
     *
     * @return void
     */
    #[TestWith([9744803445.248903, 23])]
    #[DependsExternal(NumFloatTest::class, 'testRound')]
    public function testExponent1 (float $expected, int $actual):void {

        $this->assertSame(NumInt::round($expected, 5), NumInt::round(NumInt::exponent1($actual), 5));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param int|float $x
     * @param int|float $y
     *
     * @return void
     */
    #[TestWith([2.23606797749979, 1, 2])]
    #[DependsExternal(NumFloatTest::class, 'testRound')]
    public function testHypotenuseLength (float $expected, int|float $x, int|float $y):void {

        $this->assertSame(NumInt::round($expected, 5), NumInt::round(NumInt::hypotenuseLength($x, $y), 5));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param int $actual
     *
     * @return void
     */
    #[TestWith([3.0, 9])]
    public function testSquareRoot (float $expected, int $actual):void {

        $this->assertSame($expected, NumInt::squareRoot($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int $expected
     * @param int $dividend
     * @param non-zero-int $divisor
     *
     * @throws \FireHub\Core\Support\Exceptions\Number\ArithmeticException
     * @throws \FireHub\Core\Support\Exceptions\Number\DivideByZeroException
     *
     * @return void
     */
    #[TestWith([1, 3, 2])]
    #[TestWith([-1, -3, 2])]
    #[TestWith([-1, 3, -2])]
    #[TestWith([1, -3, -2])]
    #[TestWith([0, PHP_INT_MAX, PHP_INT_MIN])]
    #[TestWith([-1, PHP_INT_MIN, PHP_INT_MAX])]
    public function testDivide (int $expected, int $dividend, int $divisor):void {

        $this->assertSame($expected, NumInt::divide($dividend, $divisor));

    }

    /**
     * @since 1.0.0
     *
     * @param int $dividend
     * @param non-zero-int $divisor
     *
     * @throws \FireHub\Core\Support\Exceptions\Number\ArithmeticException
     * @throws \FireHub\Core\Support\Exceptions\Number\DivideByZeroException
     *
     * @return void
     */
    #[TestWith([PHP_INT_MIN, -1])]
    public function testDivideError (int $dividend, int $divisor):void {

        $this->expectException(ArithmeticException::class);

        NumInt::divide($dividend, $divisor);

    }

    /**
     * @since 1.0.0
     *
     * @param int $dividend
     * @param non-zero-int $divisor
     *
     * @throws \FireHub\Core\Support\Exceptions\Number\ArithmeticException
     * @throws \FireHub\Core\Support\Exceptions\Number\DivideByZeroException
     *
     * @return void
     */
    #[TestWith([1, 0])]
    public function testDivideByZero (int $dividend, int $divisor):void {

        $this->expectException(DivideByZeroException::class);

        NumInt::divide($dividend, $divisor);

    }

}