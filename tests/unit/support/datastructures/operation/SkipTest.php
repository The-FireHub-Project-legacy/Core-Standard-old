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
    Indexed, Associative, Fixed, Lazy
};
use FireHub\Core\Support\DataStructures\Operation\Skip;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Skip data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(Associative::class)]
#[CoversClass(Fixed::class)]
#[CoversClass(Lazy::class)]
#[CoversClass(Skip::class)]
final class SkipTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testFirst (Indexed $collection):void {

        $this->assertSame(
            ['Jane', 'Richard', 'Richard'],
            $collection->skip()->first(3)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testFirstAssociative (Associative $collection):void {

        $this->assertSame(
            [10 => 2],
            $collection->skip()->first(3)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testFirstFixed (Fixed $collection):void {

        $this->assertSame(
            ['three'],
            $collection->skip()->first(2)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testFirstLazy (Lazy $collection):void {

        $this->assertSame(
            [[10, 2]],
            $collection->skip()->first(3)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testUntil (Indexed $collection):void {

        $this->assertSame(
            ['Richard', 'Richard'],
            $collection->skip()->until(fn($value, $key) => $value === 'Richard')->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testWhile (Indexed $collection):void {

        $this->assertSame(
            ['Richard', 'Richard'],
            $collection->skip()->while(fn($value, $key) => $value !== 'Richard')->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testNth (Indexed $collection):void {

        $this->assertSame(
            ['John', 'Jane', 'Jane', 'Richard'],
            $collection->skip()->nth(3)->toArray()
        );

    }

}