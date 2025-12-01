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
use FireHub\Core\Support\Contracts\HighLevel\DataStructures;
use FireHub\Core\Support\DataStructures\Linear\ {
    Indexed, Fixed
};
use FireHub\Core\Support\DataStructures\Operation\Is;
use FireHub\Core\Support\Enums\Data\Type;
use FireHub\Core\Support\LowLevel\DataIs;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test check is a data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(Fixed::class)]
#[CoversClass(Is::class)]
#[CoversClass(Type::class)]
#[CoversClass(DataIs::class)]
final class IsTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testEmpty (Indexed $collection):void {

        $this->assertFalse($collection->is()->empty());
        $this->assertTrue($collection->is()->notEmpty());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    public function testNotEmpty (Indexed $collection):void {

        $this->assertTrue($collection->is()->empty());
        $this->assertFalse($collection->is()->notEmpty());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed|\FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\CannotSerializeException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testUnique (Indexed|Fixed $collection):void {

        $this->assertTrue($collection->is()->unique());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\CannotSerializeException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testNotUnique (Indexed $collection):void {

        $this->assertFalse($collection->is()->unique());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testSequential (DataStructures $collection):void {

        $this->assertTrue($collection->is()->sequential());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testNotSequential (DataStructures $collection):void {

        $this->assertFalse($collection->is()->sequential());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testHomogeneous (DataStructures $collection):void {

        $this->assertTrue($collection->is()->homogeneous());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testHeterogeneous (DataStructures $collection):void {

        $this->assertTrue($collection->is()->heterogeneous());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[TestWith([new Indexed([new Indexed(), new Indexed(), new Indexed()])])]
    public function testClassHomogeneous (DataStructures $collection):void {

        $this->assertTrue($collection->is()->classHomogeneous(Indexed::class));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[TestWith([new Indexed([new Indexed(), new Indexed(), new Fixed(1)])])]
    public function testNotClassHomogeneous (DataStructures $collection):void {

        $this->assertFalse($collection->is()->classHomogeneous(Indexed::class));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testFlat (DataStructures $collection):void {

        $this->assertTrue($collection->is()->flat());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedMixed')]
    public function testNotFlat (DataStructures $collection):void {

        $this->assertFalse($collection->is()->flat());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedMixed')]
    public function testMultidimensional (DataStructures $collection):void {

        $this->assertTrue($collection->is()->multidimensional());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testNotMultidimensional (DataStructures $collection):void {

        $this->assertFalse($collection->is()->multidimensional());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixedEmpty')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testPure (DataStructures $collection):void {

        $this->assertTrue($collection->is()->pure());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Contracts\HighLevel\DataStructures $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedMixed')]
    public function testNotPure (DataStructures $collection):void {

        $this->assertFalse($collection->is()->pure());

    }

}