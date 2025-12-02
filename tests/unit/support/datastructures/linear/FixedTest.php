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
use FireHub\Core\Support\DataStructures\Linear\Fixed;
use FireHub\Core\Support\DataStructures\Function\Reduce;
use FireHub\Core\Support\Enums\ControlFlowSignal;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test fixed data structure class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Fixed::class)]
#[CoversClass(Reduce::class)]
final class FixedTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testFromArray (Fixed $collection):void {

        $this->assertEquals(
            $collection,
            Fixed::fromArray(['one', 'two', 'three'])
        );

    }

    /**
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $expected
     *
     * @return void
     */
    #[TestWith([[0 => null, 1 => 'one', 2 => 'two', 3 => 'three']])]
    public function testFromArrayWithKeys (array $expected):void {

        $this->assertEquals(
            $expected,
            Fixed::fromArray([1 => 'one', 2 => 'two', 3 => 'three'], true)->toArray()
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException
     * @throws \FireHub\Core\Support\Exceptions\JSON\DecodingException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testJson (Fixed $collection):void {

        $json = $collection->toJson();

        $this->assertSame('["one","two","three"]', $json);
        $this->assertEquals($collection, Fixed::fromJson($json));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\CannotSerializeException
     * @throws \FireHub\Core\Support\Exceptions\Data\UnserializeFailedException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testSerialize (Fixed $collection):void {

        $this->assertEquals($collection, Fixed::unserialize($collection->serialize()));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testCount (Fixed $collection):void {

        $this->assertSame(3, $collection->count());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testReduce (Fixed $collection):void {

        $this->assertSame(
            '-one-two-three',
            $collection->reduce(fn($carry, $value) => $carry.'-'.$value)
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testSize (Fixed $collection):void {

        $this->assertTrue($collection->setSize(4));
        $this->assertSame(4, $collection->getSize());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testToArray (Fixed $collection):void {

        $this->assertSame(['one', 'two', 'three'], $collection->toArray());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testShift (Fixed $collection):void {

        $expected = new Fixed(2);
        $expected[0] = 'two';
        $expected[1] = 'three';

        $collection->shift();

        $this->assertEquals($expected, $collection);

        $expected = new Fixed(0);

        $collection->shift(2);

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testPop (Fixed $collection):void {

        $expected = new Fixed(2);
        $expected[0] = 'one';
        $expected[1] = 'two';

        $collection->pop();

        $this->assertEquals($expected, $collection);

        $expected = new Fixed(0);

        $collection->pop(2);

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testPrepend (Fixed $collection):void {

        $expected = new Fixed(6);
        $expected[0] = 'four';
        $expected[1] = 'five';
        $expected[2] = 'six';
        $expected[3] = 'one';
        $expected[4] = 'two';
        $expected[5] = 'three';

        $collection->prepend('four', 'five', 'six');

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testAppend (Fixed $collection):void {

        $expected = new Fixed(6);
        $expected[0] = 'one';
        $expected[1] = 'two';
        $expected[2] = 'three';
        $expected[3] = 'four';
        $expected[4] = 'five';
        $expected[5] = 'six';

        $collection->append('four', 'five', 'six');

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testHead (Fixed $collection):void {

        $this->assertEquals('one', $collection->head());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixedEmpty')]
    public function testHeadEmpty (Fixed $collection):void {

        $this->assertEquals(null, $collection->head());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testTail (Fixed $collection):void {

        $this->assertEquals('three', $collection->tail());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixedEmpty')]
    public function testTailEmpty (Fixed $collection):void {

        $this->assertEquals(null, $collection->tail());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testTransform (Fixed $collection):void {

        $expected = new Fixed(3);
        $expected[0] = 'one-1';
        $expected[1] = 'two-1';
        $expected[2] = 'three-1';

        $this->assertEquals($expected, $collection->transform(fn($value) => $value.'-1'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Fixed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'fixed')]
    public function testFilter (Fixed $collection):void {

        $expected = new Fixed(2);
        $expected[0] = 'one';
        $expected[1] = 'two';

        $this->assertEquals(
            $expected,
            $collection->filter(fn($value, $key) => $value !== 'three')
        );

        $this->assertEquals(
            $expected,
            $collection->filter(function ($value, $key) {
                if ($value === 'three') return ControlFlowSignal::BREAK;
                return true;
            })
        );

    }

}