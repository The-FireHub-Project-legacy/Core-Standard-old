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
use FireHub\Tests\DataProviders\ResourcesDataProvider;
use FireHub\Core\Support\Enums\Data\ResourceType;
use FireHub\Core\Support\LowLevel\Resources;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Depends, Group, Small
};

/**
 * ### Test resources low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Resources::class)]
final class ResourcesTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param resource $actual
     *
     * @return void
     */
    #[DataProviderExternal(ResourcesDataProvider::class, 'mixed')]
    public function testID (mixed $actual, ResourceType $type):void {

        $this->assertIsInt(Resources::id($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param resource $actual
     * @param \FireHub\Core\Support\Enums\Data\ResourceType $expected
     *
     * @return void
     */
    #[DataProviderExternal(ResourcesDataProvider::class, 'mixed')]
    public function testType (mixed $actual, ResourceType $expected):void {

        $this->assertSame($expected, Resources::type($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param resource $actual
     * @param \FireHub\Core\Support\Enums\Data\ResourceType $expected
     *
     * @return void
     */
    #[DataProviderExternal(ResourcesDataProvider::class, 'mixed')]
    #[Depends('testType')]
    public function testActive (mixed $actual, ResourceType $expected):void {

        $this->assertIsArray(Resources::active($expected));

    }

}