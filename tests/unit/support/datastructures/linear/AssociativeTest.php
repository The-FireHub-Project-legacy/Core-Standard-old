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
use FireHub\Core\Support\DataStructures\Exceptions\ {
    KeyAlreadyExistException, KeyDoesntExistException
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test associative data structure class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Associative::class)]
final class AssociativeTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testFromArray (Associative $collection):void {

        $this->assertEquals(
            $collection,
            Associative::fromArray(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2])
        );

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException
     * @throws \FireHub\Core\Support\Exceptions\JSON\DecodingException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testJson (Associative $collection):void {

        $json = $collection->toJson();

        $this->assertSame('{"firstname":"John","lastname":"Doe","age":25,"10":2}', $json);
        $this->assertEquals($collection, Associative::fromJson($json));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testCount (Associative $collection):void {

        $this->assertSame(4, $collection->count());

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testToArray (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2],
            $collection->toArray()
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
    public function testExist (Associative $collection):void {

        $this->assertTrue($collection->exist('firstname'));
        $this->assertFalse($collection->exist('middlename'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testGet (Associative $collection):void {

        $this->assertSame('John', $collection->get('firstname'));
        $this->assertNull($collection->get('middlename'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyDoesntExistException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testTake (Associative $collection):void {

        $this->assertSame('John', $collection->take('firstname'));
        $this->assertNull($collection->get('middlename'));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testTakeException (Associative $collection):void {

        $this->expectException(KeyDoesntExistException::class);

        $collection->take('middlename');

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testSet (Associative $collection):void {

        $expected = new Associative(
            ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'gender' => 'female']
        );

        $collection->set('Jane', 'firstname');
        $collection->set('female', 'gender');

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyAlreadyExistException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testAdd (Associative $collection):void {

        $expected = new Associative(
            ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'gender' => 'female']
        );

        $collection->add('female', 'gender');

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testAddException (Associative $collection):void {

        $this->expectException(KeyAlreadyExistException::class);

        $collection->add('Jane', 'firstname');

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyDoesntExistException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testReplace (Associative $collection):void {

        $expected = new Associative(
            ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 25, 10 => 2]
        );

        $collection->replace('Jane', 'firstname');

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testReplaceException (Associative $collection):void {

        $this->expectException(KeyDoesntExistException::class);

        $collection->replace('female', 'gender');

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testRemove (Associative $collection):void {

        $expected = new Associative(
            ['lastname' => 'Doe', 'age' => 25, 10 => 2]
        );

        $collection->remove('firstname');

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyDoesntExistException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testDelete (Associative $collection):void {

        $expected = new Associative(
            ['lastname' => 'Doe', 'age' => 25, 10 => 2]
        );

        $collection->delete('firstname');

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testDeleteException (Associative $collection):void {

        $this->expectException(KeyDoesntExistException::class);

        $collection->delete('gender');

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @throws \FireHub\Core\Support\DataStructures\Exceptions\KeyDoesntExistException
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testPull (Associative $collection):void {

        $expected = new Associative(
            ['lastname' => 'Doe', 'age' => 25, 10 => 2]
        );

        $pull = $collection->pull('firstname');

        $this->assertEquals($expected, $collection);
        $this->assertEquals('John', $pull);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testPullException (Associative $collection):void {

        $this->expectException(KeyDoesntExistException::class);

        $collection->pull('gender');

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testOffsetExist (Associative $collection):void {

        $this->assertTrue(isset($collection['firstname']));
        $this->assertFalse(isset($collection['middlename']));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testOffsetGet (Associative $collection):void {

        $this->assertSame('John', $collection['firstname']);
        $this->assertNull($collection['middlename']);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testOffsetSet (Associative $collection):void {

        $expected = new Associative(
            ['firstname' => 'Joe', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'middlename' => 'Marry']
        );

        $collection['firstname'] = 'Joe';
        $collection['middlename'] = 'Marry';

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testOffsetUnset (Associative $collection):void {

        $expected = new Associative(['lastname' => 'Doe', 'age' => 25, 10 => 2]);

        unset($collection['firstname'], $collection['middlename']);

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testMagicIsset (Associative $collection):void {

        $this->assertTrue(isset($collection->firstname));
        $this->assertFalse(isset($collection->middlename));

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testMagicGet (Associative $collection):void {

        $this->assertSame('John', $collection->firstname);
        $this->assertNull($collection->middlename);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testMagicSet (Associative $collection):void {

        $expected = new Associative(
            ['firstname' => 'Joe', 'lastname' => 'Doe', 'age' => 25, 10 => 2, 'middlename' => 'Marry']
        );

        $collection->firstname = 'Joe';
        $collection->middlename = 'Marry';

        $this->assertEquals($expected, $collection);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Associative $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'associative')]
    public function testMagicUnset (Associative $collection):void {

        $expected = new Associative(['lastname' => 'Doe', 'age' => 25, 10 => 2]);

        unset($collection->firstname, $collection->middlename);

        $this->assertEquals($expected, $collection);

    }

}