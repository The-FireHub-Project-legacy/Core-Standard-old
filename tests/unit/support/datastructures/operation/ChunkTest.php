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
use FireHub\Core\Support\DataStructures\Contracts\ArrStorage;
use FireHub\Core\Support\DataStructures\Linear\ {
    Indexed, Associative
};
use FireHub\Core\Support\DataStructures\Operation\Chunk;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test Chunk data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Associative::class)]
#[CoversClass(Chunk::class)]
final class ChunkTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Contracts\ArrStorage $data_structure
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testWhen (ArrStorage $data_structure):void {

        $this->assertEquals(
            [
                [0, new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25])],
                [1, new Associative([10 => 2])]
            ],
            $data_structure->chunk()->when(fn($value, $key) => $value === 25)->toArray()
        );

        $this->assertEquals(
            [
                [0, new Associative(['firstname' => 'John', 'lastname' => 'Doe'])],
                [1, new Associative(['age' => 25])]
            ],
            $data_structure->chunk()->when(function ($value, $key) {
                if ($value === 2) return ControlFlowSignal::BREAK;
                return $value === 'Doe';
            })->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedInt')]
    public function testByStep (Indexed $collection):void {

        $this->assertEquals(
            [
                [0, new Indexed([1, 2, 3, 4])],
                [1, new Indexed([5, 6, 7, 8])],
                [2, new Indexed([9, 10])]
            ],
            $collection->chunk()->byStep(4)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedInt')]
    public function testIn (Indexed $collection):void {

        $this->assertEquals(
            [
                [0, new Indexed([1, 2, 3])],
                [1, new Indexed([4, 5, 6])],
                [2, new Indexed([7, 8, 9])],
                [3, new Indexed([10])]
            ],
            $collection->chunk()->in(4)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedInt')]
    public function testSplit (Indexed $collection):void {

        $this->assertEquals(
            [
                [0, new Indexed([1, 2, 3])],
                [1, new Indexed([4, 5, 6])],
                [2, new Indexed([7, 8])],
                [3, new Indexed([9, 10])]
            ],
            $collection->chunk()->split(4)->toArray()
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
    public function testByValueChange (Indexed $collection):void {

        $this->assertEquals(
            [
                [0, new Indexed(['John'])],
                [1, new Indexed(['Jane', 'Jane', 'Jane'])],
                [2, new Indexed(['Richard', 'Richard'])]
            ],
            $collection->chunk()->byValueChange()->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedInt')]
    public function testByWidth (Indexed $collection):void {

        $this->assertEquals(
            [
                [0, new Indexed([1, 2, 3, 4, 5])],
                [1, new Indexed([6, 7, 8])],
                [2, new Indexed([9, 10])]
            ],
            $collection->chunk()->byWidth(5, 3, 2)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedInt')]
    public function testSliding (Indexed $collection):void {

        $this->assertEquals(
            [
                [0, new Indexed([1, 2, 3])],
                [1, new Indexed([2, 3, 4])],
                [2, new Indexed([3, 4, 5])],
                [3, new Indexed([4, 5, 6])],
                [4, new Indexed([5, 6, 7])],
                [5, new Indexed([6, 7, 8])],
                [6, new Indexed([7, 8, 9])],
                [7, new Indexed([8, 9, 10])],
            ],
            $collection->chunk()->sliding(3, 1)->toArray()
        );

    }

}