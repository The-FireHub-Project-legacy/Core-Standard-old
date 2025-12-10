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
use FireHub\Core\Support\DataStructures\Linear\Indexed;
use FireHub\Core\Support\DataStructures\Function\ {
    Combine, Partition, Reduce, Reject, Slice, Splice
};
use FireHub\Core\Support\Enums\ {
    ControlFlowSignal, Status\Key
};
use FireHub\Core\Support\DataStructures\Exceptions\OutOfRangeException;
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
#[CoversClass(Partition::class)]
#[CoversClass(Reduce::class)]
#[CoversClass(Reject::class)]
#[CoversClass(Slice::class)]
#[CoversClass(Splice::class)]
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
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testSlice (Indexed $collection):void {

        $this->assertSame(
            ['Jane', 'Jane', 'Richard', 'Richard'],
            new Slice($collection)(2)->toArray()
        );

        $this->assertSame(
            ['Jane', 'Jane'],
            new Slice($collection)(2, 2)->toArray()
        );

        $this->assertEquals(
            new Indexed(['Richard', 'Richard']),
            new Slice($collection)(-2, 3)
        );

        $this->assertEquals(
            new Indexed(['Jane', 'Jane', 'Jane', 'Richard']),
            new Slice($collection)(1, -1)
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
    public function testSplice (Indexed $collection):void {

        $this->assertSame(
            ['John', 'Jane'],
            new Splice($collection)(2)->toArray()
        );

        $this->assertSame(
            ['John', 'Jane', 'Richard', 'Richard'],
            new Splice($collection)(2, 2)->toArray()
        );

        $this->assertEquals(
            new Indexed(['John', 'Jane', 'Jane', 'Jane']),
            new Splice($collection)(-2, 3)
        );

        $this->assertEquals(
            new Indexed(['John', 'Richard']),
            new Splice($collection)(1, -1)
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
    public function testReduce (Indexed $collection):void {

        $this->assertSame(
            '-John-Jane-Jane-Jane-Richard-Richard',
            $collection->reduce(fn($carry, $value) => $carry.'-'.$value)
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
    public function testPartition (Indexed $collection):void {

        $this->assertEquals([
            new Indexed(['Richard', 'Richard']),
            new Indexed(['John', 'Jane', 'Jane', 'Jane'])
        ],
            new Partition($collection)(fn($value) => $value === 'Richard')->toArray()
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

        $this->assertEquals(Key::NONE, $collection->head());

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
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\OutOfRangeException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testRandomValue (Indexed $collection):void {

        $this->assertContains($collection->random(), $collection->toArray());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\OutOfRangeException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testRandomMultiple (Indexed $collection):void {

        $expected = $collection->random(2);

        $this->assertIsArray($expected->toArray());
        $this->assertCount(2, $expected);

        foreach ($expected as $key => $value) {

            $this->assertArrayHasKey($key, $collection->toArray());

        }

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\OutOfRangeException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedEmpty')]
    public function testRandomEmpty (Indexed $collection):void {

        $this->expectException(OutOfRangeException::class);

        $collection->random(2);

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

        $this->assertEquals(Key::NONE, $collection->tail());

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

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testUnion (Indexed $collection):void {

        $this->assertSame(
            ['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard', 'Johnie', 'Janie'],
            $collection->union(new Indexed(['Johnie', 'Janie']))->toArray()
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
    public function testReverse (Indexed $collection):void {

        $this->assertSame(
            ['Richard', 'Richard', 'Jane', 'Jane', 'Jane', 'John'],
            $collection->reverse()->toArray()
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
    public function testShuffle (Indexed $collection):void {

        $this->assertEqualsCanonicalizing(
            $collection->toArray(),
            $collection->shuffle()->toArray()
        );

    }

}