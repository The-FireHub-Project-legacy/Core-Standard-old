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
use FireHub\Tests\DataProviders\FloatDataProvider;
use FireHub\Core\Support\Enums\Number\ {
    LogBase, Round
};
use FireHub\Core\Support\LowLevel\ {
    Num, NumFloat
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test float number low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Num::class)]
#[CoversClass(NumFloat::class)]
#[CoversClass(LogBase::class)]
#[CoversClass(Round::class)]
final class NumFloatTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param float $float
     *
     * @return void
     */
    #[DataProviderExternal(FloatDataProvider::class, 'positive')]
    #[DataProviderExternal(FloatDataProvider::class, 'negative')]
    #[DataProviderExternal(FloatDataProvider::class, 'null')]
    public function testAbsolute (float $float):void {

        $this->assertIsFloat(NumFloat::absolute($float));

    }

    /**
     * @since 1.0.0
     *
     * @param int $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([5, 4.3])]
    #[TestWith([10, 9.999])]
    #[TestWith([-3, -3.14])]
    public function testCeil (int $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::ceil($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([4, 4.3])]
    #[TestWith([9, 9.999])]
    #[TestWith([-4, -3.14])]
    public function testFloor (int $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::floor($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param int|float $expected
     * @param float $actual
     * @param int $precision
     * @param \FireHub\Core\Support\Enums\Number\Round $round
     *
     * @return void
     */
    #[TestWith([2, 1.5, 0, Round::HALF_AWAY_FROM_ZERO])]
    #[TestWith([1, 0.5, 0, Round::HALF_AWAY_FROM_ZERO])]
    #[TestWith([0, 0.49, 0, Round::HALF_AWAY_FROM_ZERO])]
    #[TestWith([-0.4, -0.35, 1, Round::HALF_AWAY_FROM_ZERO])]
    #[TestWith([0.46, 0.455, 2, Round::HALF_AWAY_FROM_ZERO])]
    #[TestWith([1, 1.5, 0, Round::HALF_TOWARDS_ZERO])]
    #[TestWith([0, 0.5, 0, Round::HALF_TOWARDS_ZERO])]
    #[TestWith([0, 0.49, 0, Round::HALF_TOWARDS_ZERO])]
    #[TestWith([-0.3, -0.35, 1, Round::HALF_TOWARDS_ZERO])]
    #[TestWith([0.45, 0.455, 2, Round::HALF_TOWARDS_ZERO])]
    #[TestWith([1, 1.5, 0, Round::HALF_ODD])]
    #[TestWith([1, 0.5, 0, Round::HALF_ODD])]
    #[TestWith([0, 0.49, 0, Round::HALF_ODD])]
    #[TestWith([-0.3, -0.35, 1, Round::HALF_ODD])]
    #[TestWith([0.45, 0.455, 2, Round::HALF_ODD])]
    #[TestWith([2, 1.5, 0, Round::HALF_EVEN])]
    #[TestWith([0, 0.5, 0, Round::HALF_EVEN])]
    #[TestWith([0, 0.49, 0, Round::HALF_EVEN])]
    #[TestWith([-0.4, -0.35, 1, Round::HALF_EVEN])]
    #[TestWith([0.46, 0.455, 2, Round::HALF_EVEN])]
    #[TestWith([1, 1.5, 0, Round::TOWARDS_ZERO])]
    #[TestWith([0, 0.5, 0, Round::TOWARDS_ZERO])]
    #[TestWith([0, 0.49, 0, Round::TOWARDS_ZERO])]
    #[TestWith([-0.3, -0.35, 1, Round::TOWARDS_ZERO])]
    #[TestWith([0.45, 0.455, 2, Round::TOWARDS_ZERO])]
    #[TestWith([2, 1.5, 0, Round::AWAY_FROM_ZERO])]
    #[TestWith([1, 0.5, 0, Round::AWAY_FROM_ZERO])]
    #[TestWith([1, 0.49, 0, Round::AWAY_FROM_ZERO])]
    #[TestWith([-0.4, -0.35, 1, Round::AWAY_FROM_ZERO])]
    #[TestWith([0.46, 0.455, 2, Round::AWAY_FROM_ZERO])]
    #[TestWith([1, 1.5, 0, Round::NEGATIVE_INFINITY])]
    #[TestWith([0, 0.5, 0, Round::NEGATIVE_INFINITY])]
    #[TestWith([0, 0.49, 0, Round::NEGATIVE_INFINITY])]
    #[TestWith([-0.4, -0.35, 1, Round::NEGATIVE_INFINITY])]
    #[TestWith([0.45, 0.455, 2, Round::NEGATIVE_INFINITY])]
    public function testRound (int|float $expected, float $actual, int $precision, Round $round):void {

        $this->assertSame($expected, NumFloat::round($actual, $precision, $round));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     * @param \FireHub\Core\Support\Enums\Number\LogBase $base
     *
     * @return void
     */
    #[TestWith([0.28893129185221283, 1.335, LogBase::E])]
    #[TestWith([0.788324982905575, 1.335, LogBase::LOG2E])]
    #[TestWith([-0.34642692079720505, 1.335, LogBase::LOG10E])]
    #[TestWith([-0.7883249829055747, 1.335, LogBase::LN2])]
    #[TestWith([0.346426920797205, 1.335, LogBase::LN10])]
    public function testLog (float $expected, float $actual, LogBase $base):void {

        $this->assertSame($expected, NumFloat::log($actual, $base));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.8480118911208606, 1.335])]
    public function testLog1p (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::log1p($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.125481265700594, 1.335])]
    public function testLog10 (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::log10($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([4.23544, [2.345, 4.23544, 4.1214]])]
    public function testMax (float $expected, array $actual):void {

        $this->assertSame($expected, NumFloat::max(...$actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param array<array-key, mixed> $actual
     *
     * @return void
     */
    #[TestWith([2.345, [2.345, 4.23544, 4.1214]])]
    public function testMin (float $expected, array $actual):void {

        $this->assertSame($expected, NumFloat::min(...$actual));

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
    #[TestWith([0.1, 10, -1])]
    public function testPower (float|int $expected, float|int $base, float|int $exponent):void {

        $this->assertSame($expected, NumFloat::power($base, $exponent));

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
    #[TestWith(['45656,560', 45656.56, 3, ',', ''])]
    public function testFormat (string $expected, float $actual, int $decimals, string $decimal_separator, string $thousands_separator):void {

        $this->assertSame($expected, NumFloat::format($actual, $decimals, $decimal_separator, $thousands_separator));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.4031710572106902, 23.1])]
    public function testDegreesToRadian (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::degreesToRadian($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([23.099939426289396, 0.40317])]
    public function testRadianToDegrees (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::radianToDegrees($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([298.8674009670603, 5.7])]
    public function testExponent (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::exponent($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([4839126178.743089, 22.3])]
    public function testExponent1 (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::exponent1($actual));

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
    #[TestWith([2.6832815729997477, 1.2, 2.4])]
    public function testHypotenuseLength (float $expected, int|float $x, int|float $y):void {

        $this->assertSame($expected, NumFloat::hypotenuseLength($x, $y));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([3.03315017762062, 9.2])]
    public function testSquareRoot (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::squareRoot($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $result
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([true, 10.5])]
    #[TestWith([false, INF])]
    #[TestWith([false, NAN])]
    public function testIsFinite (bool $result, float $actual):void {

        $this->assertSame($result, NumFloat::isFinite($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $result
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([true, INF])]
    #[TestWith([false, 10.5])]
    public function testIsInfinite (bool $result, float $actual):void {

        $this->assertSame($result, NumFloat::isInfinite($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $result
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([true, NAN])]
    #[TestWith([false, 10.5])]
    public function testIsNan (bool $result, float $actual):void {

        $this->assertSame($result, NumFloat::isNan($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $result
     * @param float $dividend
     * @param float $divisor
     *
     * @return void
     */
    #[TestWith([2.0, 4, 2])]
    #[TestWith([INF, 1.0, 0.0])]
    #[TestWith([-INF, -1.0, 0.0])]
    #[TestWith([4.384615384615385, 5.7, 1.3])]
    public function testDivide (float $result, float $dividend, float $divisor):void {

        $this->assertSame($result, NumFloat::divide($dividend, $divisor));

    }

    /**
     * @since 1.0.0
     *
     * @param float $result
     * @param float $dividend
     * @param float $divisor
     *
     * @return void
     */
    #[TestWith([0.5, 5.7, 1.3])]
    public function testRemainder (float $result, float $dividend, float $divisor):void {

        $this->assertSame($result, NumFloat::remainder($dividend, $divisor));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([-1.0, M_PI])]
    public function testCosine (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::cosine($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([1.0471975511965979, 0.5])]
    public function testCosineArc (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::cosineArc($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([1.1276259652063807, 0.5])]
    public function testCosineHyperbolic (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::cosineHyperbolic($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.4435682543851153, 1.1])]
    public function testCosineInverseHyperbolic (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::cosineInverseHyperbolic($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.479425538604203, 0.5])]
    public function testSine (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::sine($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([1.5707963267948966, 1])]
    public function testSineArc (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::sineArc($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([1.1752011936438014, 1])]
    public function testSineHyperbolic (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::sineHyperbolic($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.881373587019543, 1])]
    public function testSineHyperbolicInverse (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::sineHyperbolicInverse($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([1.5574077246549023, 1])]
    public function testTangent (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::tangent($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.7853981633974483, 1])]
    public function testTangentArc (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::tangentArc($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $x
     * @param float $y
     *
     * @return void
     */
    #[TestWith([0.7853981633974483, 1, 1])]
    public function testTangentArc2 (float $expected, float $x, float $y):void {

        $this->assertSame($expected, NumFloat::tangentArc2($x, $y));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.7615941559557649, 1])]
    public function testTangentHyperbolic (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::tangentHyperbolic($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param float $expected
     * @param float $actual
     *
     * @return void
     */
    #[TestWith([0.5493061443340549, 0.5])]
    public function testTangentInverseHyperbolic (float $expected, float $actual):void {

        $this->assertSame($expected, NumFloat::tangentInverseHyperbolic($actual));

    }

}