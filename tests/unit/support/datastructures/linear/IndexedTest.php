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

use FireHub\Core\Support\Enums\ValueStatus;
use FireHub\Core\Testing\Base;
use FireHub\Tests\DataProviders\DataStructureDataProvider;
use FireHub\Core\Support\DataStructures\Linear\Indexed;
use FireHub\Core\Support\DataStructures\Function\ {
    Combine, Reject
};
use FireHub\Core\Support\Enums\ControlFlowSignal;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test indexed data structure class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(Combine::class)]
#[CoversClass(Reject::class)]
final class IndexedTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testFromArray (Indexed $collection):void {

        $this->assertEquals(
            $collection,
            Indexed::fromArray(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard'])
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\Arr\KeysAndValuesDiffNumberOfElemsException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedInt')]
    public function testCombine (Indexed $collection):void {

        $this->assertEquals(
            [
                1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
                5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight',
                9 => 'nine', 10 => 'ten'
            ],
            $collection->combine(
                new Indexed(['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'])
            )->toArray()
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
    public function testReject (Indexed $collection):void {

        $this->assertEquals(
            ['John', 'Richard', 'Richard'],
            new Reject($collection)(fn($value, $key) => $value === 'Jane')->toArray()
        );

        $this->assertEquals(
            ['John'],
            new Reject($collection)(function ($value, $key) {
                if ($value === 'Jane') return ControlFlowSignal::BREAK;
                return false;
            })->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException
     * @throws \FireHub\Core\Support\Exceptions\JSON\DecodingException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testJson (Indexed $collection):void {

        $json = $collection->toJson();

        $this->assertSame('["John","Jane","Jane","Jane","Richard","Richard"]', $json);
        $this->assertEquals($collection, Indexed::fromJson($json));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\CannotSerializeException
     * @throws \FireHub\Core\Support\Exceptions\Data\UnserializeFailedException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testSerialize (Indexed $collection):void {

        $this->assertEquals($collection, Indexed::unserialize($collection->serialize()));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedInt')]
    public function testCount (Indexed $collection):void {

        $this->assertSame(10, $collection->count());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testCount2 (Indexed $collection):void {

        $this->assertSame(6, $collection->count());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testToArray (Indexed $collection):void {

        $this->assertSame(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard'], $collection->toArray());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testShift (Indexed $collection):void {

        $tested_collection = new Indexed(['Jane', 'Jane', 'Jane', 'Richard', 'Richard']);
        $collection->shift();

        $this->assertEquals($tested_collection, $collection);

        $tested_collection = new Indexed();

        $collection->shift(6);

        $this->assertEquals($tested_collection, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testPop (Indexed $collection):void {

        $tested_collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard']);

        $collection->pop();

        $this->assertEquals($tested_collection, $collection);

        $tested_collection = new Indexed();

        $collection->pop(6);

        $this->assertEquals($tested_collection, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testPrepend (Indexed $collection):void {

        $tested_collection = new Indexed(['Johnie', 'Janie', 'Baby', 'John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard']);

        $collection->prepend('Johnie', 'Janie', 'Baby');

        $this->assertEquals($tested_collection, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testAppend (Indexed $collection):void {

        $tested_collection = new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard', 'Johnie', 'Janie', 'Baby']);

        $collection->append('Johnie', 'Janie', 'Baby');

        $this->assertEquals($tested_collection, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testHead (Indexed $collection):void {

        $this->assertEquals('John', $collection->head());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    public function testHeadEmpty (Indexed $collection):void {

        $this->assertEquals(ValueStatus::NONE, $collection->head());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testTail (Indexed $collection):void {

        $this->assertEquals('Richard', $collection->tail());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    public function testTailEmpty (Indexed $collection):void {

        $this->assertEquals(ValueStatus::NONE, $collection->tail());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedInt')]
    public function testTransform (Indexed $collection):void {

        $this->assertEquals(
            [2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
            $collection->transform(fn($value) => $value + 1)->toArray()
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
    public function testTransformWithKeys (Indexed $collection):void {

        $this->assertEquals(
            [2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
            $collection->transform(fn($value, $key) => $value + 1)->toArray()
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
    public function testFilter (Indexed $collection):void {

        $this->assertEquals(
            ['John', 'Richard', 'Richard'],
            $collection->filter(fn($value, $key) => $value !== 'Jane')->toArray()
        );

        $this->assertEquals(
            ['John'],
            $collection->filter(function ($value, $key) {
                if ($value === 'Jane') return ControlFlowSignal::BREAK;
                return true;
            })->toArray()
        );

    }

}