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
use FireHub\Core\Support\DataStructures\Linear\Associative;
use FireHub\Core\Support\DataStructures\Operation\SortKeys;
use FireHub\Core\Support\Enums\Sort;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test SortKeys data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Associative::class)]
#[CoversClass(SortKeys::class)]
final class SortKeysTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testAscending (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2],
            $collection->sortKeys()->ascending(Sort::BY_NUMERIC)->toArray()
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
    public function testDescending (Associative $collection):void {

        $this->assertSame(
            [10 => 2, 'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
            $collection->sortKeys()->descending(Sort::BY_NUMERIC)->toArray()
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
    public function testBy (Associative $collection):void {

        $this->assertSame(
            [10 => 2, 'age' => 25, 'firstname' => 'John', 'lastname' => 'Doe'],
            $collection->sortKeys()->by(fn($current, $next) => $current <=> $next)->toArray()
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
    public function testByPriority (Associative $collection):void {

        $this->assertSame(
            ['lastname' => 'Doe', 'firstname' => 'John', 'age' => 25, 10 => 2],
            $collection->sortKeys()->byPriority([
                'lastname', 'firstname'
            ])->toArray()
        );

    }

}