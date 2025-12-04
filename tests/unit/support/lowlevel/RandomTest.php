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
use FireHub\Core\Support\Exceptions\Random\ {
    MaxLessThenMinException, NumberGreaterThenMaxException, NumberLessThenMinException
};
use FireHub\Core\Support\LowLevel\Random;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small, TestWith
};

/**
 * ### Test random generated value low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Random::class)]
final class RandomTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param int $min
     * @param null|int $max
     *
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\Random\MaxLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberGreaterThenMaxException
     *
     * @return void
     */
    #[TestWith([0, 100])]
    public function testNumber (int $min = 0, ?int $max = null):void {

        $actual = Random::number($min, $max);

        $this->assertIsInt($actual);
        $this->assertGreaterThanOrEqual($min, $actual);
        $this->assertLessThanOrEqual($max, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param int $min
     * @param null|int $max
     *
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\Random\MaxLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberGreaterThenMaxException
     *
     * @return void
     */
    #[TestWith([-1])]
    public function testNumberLessThenMin (int $min = 0, ?int $max = null):void {

        $this->expectException(NumberLessThenMinException::class);

        Random::number($min, $max);

    }

    /**
     * @since 1.0.0
     *
     * @param int $min
     * @param null|int $max
     *
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\Random\MaxLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberGreaterThenMaxException
     *
     * @return void
     */
    #[TestWith([10, 5])]
    public function testNumberMaxLessThenMin (int $min = 0, ?int $max = null):void {

        $this->expectException(MaxLessThenMinException::class);

        Random::number($min, $max);

    }

    /**
     * @since 1.0.0
     *
     * @param int $min
     * @param null|int $max
     *
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\Random\MaxLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberGreaterThenMaxException
     *
     * @return void
     */
    #[TestWith([0, PHP_INT_MAX])]
    public function testNumberGreaterThenMax (int $min = 0, ?int $max = null):void {

        $this->expectException(NumberGreaterThenMaxException::class);

        Random::number($min, $max);

    }

    /**
     * @since 1.0.0
     *
     * @param int $min
     * @param int $max
     *
     * @throws \FireHub\Core\Support\Exceptions\Random\MaxLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\RandomException
     *
     * @return void
     */
    #[TestWith([0, 100])]
    public function testSecureNumber (int $min, int $max):void {

        $actual = Random::secureNumber($min, $max);

        $this->assertIsInt($actual);
        $this->assertGreaterThanOrEqual($min, $actual);
        $this->assertLessThanOrEqual($max, $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param int $min
     * @param int $max
     *
     * @throws \FireHub\Core\Support\Exceptions\Random\MaxLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\RandomException
     *
     * @return void
     */
    #[TestWith([10, 5])]
    public function testSecureNumberMaxLessThenMin (int $min, int $max):void {

        $this->expectException(MaxLessThenMinException::class);

        Random::secureNumber($min, $max);

    }

    /**
     * @since 1.0.0
     *
     * @param int $length
     *
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\RandomException
     *
     * @return void
     */
    #[TestWith([20])]
    public function testBytes (int $length):void {

        $actual = Random::bytes($length);

        $this->assertIsString($actual);

    }

    /**
     * @since 1.0.0
     *
     * @param int $length
     *
     * @throws \FireHub\Core\Support\Exceptions\Random\NumberLessThenMinException
     * @throws \FireHub\Core\Support\Exceptions\RandomException
     *
     * @return void
     */
    #[TestWith([0])]
    public function testBytesLengthLessThenMin (int $length):void {

        $this->expectException(NumberLessThenMinException::class);

        Random::bytes($length);

    }

}