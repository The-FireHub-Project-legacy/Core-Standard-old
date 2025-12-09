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

use FireHub\Core\Support\DataStructures\Linear\ {
    Associative, Fixed, Lazy
};
use FireHub\Core\Testing\Base;
use FireHub\Tests\DataProviders\DataStructureDataProvider;
use FireHub\Core\Support\DataStructures\Linear\Indexed;
use FireHub\Core\Support\DataStructures\Operation\Select;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Select data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(Associative::class)]
#[CoversClass(Fixed::class)]
#[CoversClass(Lazy::class)]
#[CoversClass(Select::class)]
final class SelectTest extends Base {

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
            ['John', 'Jane', 'Jane'],
            $collection->select()->first(3)->toArray()
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
    public function testLast (Indexed $collection):void {

        $this->assertSame(
            ['Jane', 'Richard', 'Richard'],
            $collection->select()->last(3)->toArray()
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
            ['John', 'Jane', 'Jane', 'Jane'],
            $collection->select()->until(fn($value, $key) => $value === 'Richard')->toArray()
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
            ['John', 'Jane', 'Jane', 'Jane'],
            $collection->select()->while(fn($value, $key) => $value !== 'Richard')->toArray()
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
            ['John', 'Jane', 'Richard'],
            $collection->select()->nth(2)->toArray()
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
    public function testEven (Indexed $collection):void {

        $this->assertSame(
            ['Jane', 'Jane', 'Richard'],
            $collection->select()->even()->toArray()
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
    public function testOdd (Indexed $collection):void {

        $this->assertSame(
            ['John', 'Jane', 'Richard'],
            $collection->select()->odd()->toArray()
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
    public function testUnique (Indexed $collection):void {

        $this->assertSame(
            ['John', 'Jane', 'Richard'],
            $collection->select()->unique()->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testUniqueNonArrStorage ():void {

        $collection = new Fixed(3);
        $collection[0] = 'one';
        $collection[1] = 'one';
        $collection[2] = 'three';

        $this->assertSame(
            ['one', 'three'],
            $collection->select()->unique()->toArray()
        );

        $collection = new Lazy(fn() => yield from [
            'firstname' => 'John', 'middlename' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2
        ]);

        $this->assertSame(
            [['firstname', 'John'], ['lastname', 'Doe'], ['age', 25], [10, 2]],
            $collection->select()->unique()->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[TestWith([
        new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2])
    ])]
    public function testDistinct (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2],
            $collection->select()->distinct()->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testDistinctNonArrStorage ():void {

        $collection = new Fixed(3);
        $collection[0] = 'one';
        $collection[1] = 'two';
        $collection[2] = 'three';

        $this->assertSame(
            ['one', 'two', 'three'],
            $collection->select()->distinct()->toArray()
        );

        $collection = new Lazy(fn() => yield from [
            'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2
        ]);

        $this->assertSame(
            [['firstname', 'John'], ['lastname', 'Doe'], ['age', 25], [10, 2]],
            $collection->select()->distinct()->toArray()
        );

    }

}