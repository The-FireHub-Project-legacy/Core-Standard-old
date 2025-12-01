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

namespace FireHub\Tests\Unit\Support\DataStructures\Linear;

use FireHub\Core\Testing\Base;
use FireHub\Tests\DataProviders\DataStructureDataProvider;
use FireHub\Core\Support\DataStructures\Linear\ {
    Indexed, Fixed
};
use FireHub\Core\Support\DataStructures\Operation\Ensure;
use FireHub\Core\Support\Enums\Data\Type;
use FireHub\Core\Support\LowLevel\DataIs;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test Ensure data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(Fixed::class)]
#[CoversClass(Ensure::class)]
#[CoversClass(Type::class)]
#[CoversClass(DataIs::class)]
final class EnsureTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testAll (Indexed $collection):void {

        $this->assertTrue($collection->ensure()->all(fn($value, $key) => DataIs::string($value)));
        $this->assertFalse($collection->ensure()->all(fn($value, $key) => DataIs::int($value)));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testAllNotArrStorage (Fixed $collection):void {

        $this->assertTrue($collection->ensure()->all(fn($value, $key) => DataIs::string($value)));
        $this->assertFalse($collection->ensure()->all(fn($value, $key) => DataIs::int($value)));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testNone (Indexed $collection):void {

        $this->assertTrue($collection->ensure()->none(fn($value, $key) => DataIs::int($value)));
        $this->assertFalse($collection->ensure()->none(fn($value, $key) => DataIs::string($value)));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testNoneNotArrStorage (Fixed $collection):void {

        $this->assertTrue($collection->ensure()->none(fn($value, $key) => DataIs::int($value)));
        $this->assertFalse($collection->ensure()->none(fn($value, $key) => DataIs::string($value)));

    }

}