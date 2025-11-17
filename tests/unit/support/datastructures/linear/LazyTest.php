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
use FireHub\Core\Support\DataStructures\Linear\Lazy;
use FireHub\Tests\DataProviders\DataStructureDataProvider;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test lazy data structure class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Lazy::class)]
final class LazyTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testCount (Lazy $collection):void {

        $this->assertSame(4, $collection->count());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testToArray (Lazy $collection):void {

        $this->assertSame(
            [
                ['firstname', 'John'], ['lastname', 'Doe'], ['age', 25], [10, 2]
            ],
            $collection->toArray()
        );

    }

}