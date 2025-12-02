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
    Associative, Lazy
};
use FireHub\Core\Support\DataStructures\Operation\Find;
use FireHub\Core\Support\Enums\Status\ {
    Key, Value
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test find data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Associative::class)]
#[CoversClass(Lazy::class)]
#[CoversClass(Find::class)]
final class FindTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testKey (Associative|Lazy $collection):void {

        $this->assertSame('lastname', $collection->find()->key('Doe'));
        $this->assertSame(Key::NONE, $collection->find()->key('Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testValue (Associative|Lazy $collection):void {

        $this->assertSame('Doe', $collection->find()->value('lastname'));
        $this->assertSame(Value::NONE, $collection->find()->value('middlename'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testFirst (Associative|Lazy $collection):void {

        $this->assertSame('John', $collection->find()->first(fn($value, $key) => $value !== 'lastname'));
        $this->assertSame(Value::NONE, $collection->find()->first(fn($value, $key) => $value === 'middlename'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testFirstKey (Associative|Lazy $collection):void {

        $this->assertSame('lastname', $collection->find()->firstKey(fn($value, $key) => $value !== 'John'));
        $this->assertSame(Key::NONE, $collection->find()->firstKey(fn($value, $key) => $value === 'Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testLast (Associative|Lazy $collection):void {

        $this->assertSame(25, $collection->find()->last(fn($value, $key) => $key !== 10));
        $this->assertSame(Value::NONE, $collection->find()->last(fn($value, $key) => $key === 'middlename'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testLastKey (Associative|Lazy $collection):void {

        $this->assertSame('age', $collection->find()->lastKey(fn($value, $key) => $value !== 2));
        $this->assertSame(Key::NONE, $collection->find()->lastKey(fn($value, $key) => $value === 'Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testBefore (Associative|Lazy $collection):void {

        $this->assertSame('John', $collection->find()->before('Doe'));
        $this->assertSame(Value::NONE, $collection->find()->before('Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testBeforeWhere (Associative|Lazy $collection):void {

        $this->assertSame('John', $collection->find()->beforeWhere(fn($value, $key) => $value === 'Doe'));
        $this->assertSame(Value::NONE, $collection->find()->beforeWhere(fn($value, $key) => $value === 'Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testAfter (Associative|Lazy $collection):void {

        $this->assertSame(25, $collection->find()->after('Doe'));
        $this->assertSame(Value::NONE, $collection->find()->after('Marry'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative|\FireHub\Core\Support\DataStructures\Linear\Lazy $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    #[DataProviderExternal(DataStructureDataProvider::class, 'lazy')]
    public function testAfterWhere (Associative|Lazy $collection):void {

        $this->assertSame(25, $collection->find()->afterWhere(fn($value, $key) => $value === 'Doe'));
        $this->assertSame(Value::NONE, $collection->find()->afterWhere(fn($value, $key) => $value === 'Marry'));

    }

}