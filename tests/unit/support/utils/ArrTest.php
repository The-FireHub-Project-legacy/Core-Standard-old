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

namespace FireHub\Tests\Unit\Support\Utils;

use FireHub\Core\Testing\Base;
use FireHub\Core\Support\Utils\Arr;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small, TestWith
};

/**
 * ### Test Arr utility class
 * @since 1.0.0
 */
#[Small]
#[Group('utils')]
#[CoversClass(Arr::class)]
final class ArrTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $actual
     * @param null|positive-int $number
     * @param bool $preserve_keys
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\OutOfRangeException
     *
     * @return void
     */
    #[TestWith([[1, 2, 3], 2, false])]
    public function testRandomValue (array $actual, ?int $number = null, bool $preserve_keys = false):void {

        $this->assertContains(Arr::randomValue($actual), $actual);

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $actual
     * @param null|positive-int $number
     * @param bool $preserve_keys
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\OutOfRangeException
     *
     * @return void
     */
    #[TestWith([[1, 2, 3], 2, true])]
    public function testRandomValueMultiple (array $actual, ?int $number = null, bool $preserve_keys = false):void {

        $expected = Arr::randomValue($actual, $number, $preserve_keys);

        $this->assertIsArray($expected);
        $this->assertCount(2, $expected);

        foreach ($expected as $key => $value) {

            $this->assertArrayHasKey($key, $expected);
            $this->assertEquals($actual[$key], $value);

        }

    }

}