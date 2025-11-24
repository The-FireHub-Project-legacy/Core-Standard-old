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
use FireHub\Core\Support\Utils\PHPUtil;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small, TestWith
};

/**
 * ### Test php utility class
 * @since 1.0.0
 */
#[Small]
#[Group('utils')]
#[CoversClass(PHPUtil::class)]
final class PHPUtilTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param non-negative-int $microseconds
     *
     * @return void
     */
    #[TestWith([0])]
    public function testSleepMicroseconds (int $microseconds):void {

        PHPUtil::sleepMicroseconds($microseconds);

        $this->assertTrue(true);

    }

}