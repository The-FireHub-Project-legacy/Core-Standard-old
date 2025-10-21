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
use FireHub\Core\Support\LowLevel\Arr;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test array low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Arr::class)]
final class ArrTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param array[] $arr
     *
     * @return void
     */
    #[DataProviderExternal(ArrDataProvider::class, 'mixed')]
    public function testAll (array $arr):void {

        $this->assertTrue(Arr::all($arr, static fn($value) => $value < 4));

    }

}