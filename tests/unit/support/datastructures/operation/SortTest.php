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
    Indexed, Associative
};
use FireHub\Core\Support\DataStructures\Operation\Sort;
use FireHub\Core\Support\Enums\Sort as SortEnum;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test Sort data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(Associative::class)]
#[CoversClass(Sort::class)]
final class SortTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedIntUnordered')]
    public function testAscending (Indexed $collection):void {

        $this->assertSame(
            [1, 13, 2, 22, 27, 28, 29, 3, 4, 50],
            $collection->sort()->ascending(SortEnum::BY_STRING)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedIntUnordered')]
    public function testDescending (Indexed $collection):void {

        $this->assertSame(
            [50, 4, 3, 29, 28, 27, 22, 2, 13, 1],
            $collection->sort()->descending(SortEnum::BY_STRING)->toArray()
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
    public function testBy (Indexed $collection):void {

        $this->assertSame(
            ['Jane', 'Jane', 'Jane', 'John', 'Richard', 'Richard'],
            $collection->sort()->by(fn($current, $next) => $current <=> $next)->toArray()
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
    public function testByAssociative (Associative $collection):void {

        $this->assertSame(
            [10 => 2, 'age' => 25, 'lastname' => 'Doe', 'firstname' => 'John'],
            $collection->sort()->by(fn($current, $next) => $current <=> $next)->toArray()
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
    public function testByPriority (Indexed $collection):void {

        $this->assertSame(
            ['Jane', 'Jane', 'Jane', 'John', 'Richard', 'Richard'],
            $collection->sort()->byPriority([
                'Jane', 'John', 'Richard'
            ])->toArray()
        );

    }

}