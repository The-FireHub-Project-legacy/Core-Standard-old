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
    Indexed, Associative, Fixed
};
use FireHub\Core\Support\DataStructures\Operation\Contains;
use FireHub\Core\Support\Enums\Data\Type;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test Contains data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(Fixed::class)]
#[CoversClass(Contains::class)]
#[CoversClass(Type::class)]
final class ContainsTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testKey (Associative $collection):void {

        $this->assertTrue($collection->contains()->key('firstname'));
        $this->assertFalse($collection->contains()->key('middlename'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testKeyNotArrStorage (Fixed $collection):void {

        $this->assertTrue($collection->contains()->key(0));
        $this->assertFalse($collection->contains()->key(3));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testValue (Associative $collection):void {

        $this->assertTrue($collection->contains()->value('John'));
        $this->assertFalse($collection->contains()->value('Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testValueNotArrStorage (Fixed $collection):void {

        $this->assertTrue($collection->contains()->value('two'));
        $this->assertFalse($collection->contains()->value('four'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testKeyOrValue (Associative $collection):void {

        $this->assertTrue($collection->contains()->keyOrValue('Doe'));
        $this->assertTrue($collection->contains()->keyOrValue('lastname'));
        $this->assertFalse($collection->contains()->keyOrValue('Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testPair (Associative $collection):void {

        $this->assertTrue($collection->contains()->pair('firstname', 'John'));
        $this->assertFalse($collection->contains()->pair('firstname', 'Marry'));
        $this->assertFalse($collection->contains()->pair('middlename', 'John'));
        $this->assertFalse($collection->contains()->pair('middlename', 'Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\TypeUnknownException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testKeyType (Associative $collection):void {

        $this->assertTrue($collection->contains()->keyType(Type::T_STRING));
        $this->assertFalse($collection->contains()->keyType(Type::T_FLOAT));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\TypeUnknownException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testValueType (Associative $collection):void {

        $this->assertTrue($collection->contains()->valueType(Type::T_STRING));
        $this->assertFalse($collection->contains()->valueType(Type::T_FLOAT));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testWhere (Indexed $collection):void {

        $this->assertTrue($collection->contains()->where(fn($value, $key) => $value === 'John'));
        $this->assertFalse($collection->contains()->where(fn($value, $key) => $value === 'Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testWhereNotArrStorage (Fixed $collection):void {

        $this->assertTrue($collection->contains()->where(fn($value, $key) => $value === 'two'));
        $this->assertFalse($collection->contains()->where(fn($value, $key) => $value === 'four'));

    }

}