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
    use FireHub\Core\Support\DataStructures\Operation\SetOperation;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Set operator data structure operation class
 * @since 1.0.0
 */
#[Small]
#[Group('datastructures')]
#[CoversClass(Indexed::class)]
#[CoversClass(Associative::class)]
#[CoversClass(Fixed::class)]
#[CoversClass(Lazy::class)]
#[CoversClass(SetOperation::class)]
final class SetOperatorTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\DataStructures\Linear\Indexed $collection
     *
     * @return void
     */
    #[DataProviderExternal(DataStructureDataProvider::class, 'indexedString')]
    public function testDifferenceValue (Indexed $collection):void {

        $this->assertSame(
            ['Richard', 'Richard'],
            $collection->setOperation(new Indexed(['John', 'Jane']))->differenceValue()->toArray()
        );

        $collection2 = new Fixed(2);
        $collection2[0] = 'John';
        $collection2[1] = 'Jane';

        $this->assertSame(
            ['Richard', 'Richard'],
            $collection->setOperation($collection2)->differenceValue()->toArray()
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
    public function testDifferenceValueWith (Indexed $collection):void {

        $this->assertSame(
            ['Richard', 'Richard'],
            $collection->setOperation(new Indexed(['John', 'Jane']))->differenceValueWith(
                fn($value_a, $value_b) => $value_a <=> $value_b
            )->toArray()
        );

        $collection2 = new Fixed(2);
        $collection2[0] = 'John';
        $collection2[1] = 'Jane';

        $this->assertSame(
            ['Richard', 'Richard'],
            $collection->setOperation($collection2)->differenceValueWith(
                fn($value_a, $value_b) => $value_a <=> $value_b
            )->toArray()
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
    public function testDifferenceKey (Associative $collection):void {

        $this->assertSame(
            ['lastname' => 'Doe'],
            $collection->setOperation(
                new Associative(['firstname' => 'John', 'age' => 25, 10 => 2])
            )->differenceKey()->toArray()
        );

        $this->assertSame(
            ['lastname' => 'Doe'],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname' => 'John', 'age' => 25, 10 => 2])
            )->differenceKey()->toArray()
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
    public function testDifferenceKeyWith (Associative $collection):void {

        $this->assertSame(
            ['lastname' => 'Doe'],
            $collection->setOperation(new Associative(['firstname' => 'John', 'age' => 25, 10 => 2]))->differenceKeyWith(
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
        );

        $this->assertSame(
            ['lastname' => 'Doe'],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname' => 'John', 'age' => 25, 10 => 2])
            )->differenceKeyWith(
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
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
    public function testDifferenceAssoc (Associative $collection):void {

        $this->assertSame(
            ['lastname' => 'Doe'],
            $collection->setOperation(
                new Associative(['firstname' => 'John', 'age' => 25, 10 => 2])
            )->differenceAssoc()->toArray()
        );

        $this->assertSame(
            ['lastname' => 'Doe'],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname' => 'John', 'age' => 25, 10 => 2])
            )->differenceAssoc()->toArray()
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
    public function testDifferenceAssocWithKey (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe'],
            $collection->setOperation(new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]))->differenceAssocWithKey(
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
        );

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe'],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname_x' => 'John', 'age' => 25, 10 => 2])
            )->differenceAssocWithKey(
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
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
    public function testDifferenceAssocWithValue (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe'],
            $collection->setOperation(new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]))->differenceAssocWithValue(
                fn($value_a, $value_b) => $value_a <=> $value_b
            )->toArray()
        );

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe'],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname_x' => 'John', 'age' => 25, 10 => 2])
            )->differenceAssocWithValue(
                fn($value_a, $value_b) => $value_a <=> $value_b
            )->toArray()
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
    public function testDifferenceAssocWithKeyValue (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe'],
            $collection->setOperation(new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]))->differenceAssocWithKeyValue(
                fn($value_a, $value_b) => $value_a <=> $value_b,
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
        );

        $this->assertSame(
            ['firstname' => 'John', 'lastname' => 'Doe'],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname_x' => 'John', 'age' => 25, 10 => 2])
            )->differenceAssocWithKeyValue(
                fn($value_a, $value_b) => $value_a <=> $value_b,
                fn($key_a, $key_b) => $key_a <=> $key_b
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
    public function testIntersectValue (Indexed $collection):void {

        $this->assertSame(
            ['John', 'Jane', 'Jane', 'Jane'],
            $collection->setOperation(new Indexed(['John', 'Jane']))->intersectValue()->toArray()
        );

        $collection2 = new Fixed(2);
        $collection2[0] = 'John';
        $collection2[1] = 'Jane';

        $this->assertSame(
            ['John', 'Jane', 'Jane', 'Jane'],
            $collection->setOperation($collection2)->intersectValue()->toArray()
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
    public function testIntersectValueWith (Indexed $collection):void {

        $this->assertSame(
            ['John', 'Jane', 'Jane', 'Jane'],
            $collection->setOperation(new Indexed(['John', 'Jane']))->intersectValueWith(
                fn($value_a, $value_b) => $value_a <=> $value_b
            )->toArray()
        );

        $collection2 = new Fixed(2);
        $collection2[0] = 'John';
        $collection2[1] = 'Jane';

        $this->assertSame(
            ['John', 'Jane', 'Jane', 'Jane'],
            $collection->setOperation($collection2)->intersectValueWith(
                fn($value_a, $value_b) => $value_a <=> $value_b
            )->toArray()
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
    public function testIntersectKey (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'age' => 25, 10 => 2],
            $collection->setOperation(
                new Associative(['firstname' => 'John', 'age' => 25, 10 => 2])
            )->intersectKey()->toArray()
        );

        $this->assertSame(
            ['firstname' => 'John', 'age' => 25, 10 => 2],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname' => 'John', 'age' => 25, 10 => 2])
            )->intersectKey()->toArray()
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
    public function testIntersectKeyWith (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'age' => 25, 10 => 2],
            $collection->setOperation(new Associative(['firstname' => 'John', 'age' => 25, 10 => 2]))->intersectKeyWith(
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
        );

        $this->assertSame(
            ['firstname' => 'John', 'age' => 25, 10 => 2],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname' => 'John', 'age' => 25, 10 => 2])
            )->intersectKeyWith(
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
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
    public function testIntersectAssoc (Associative $collection):void {

        $this->assertSame(
            ['firstname' => 'John', 'age' => 25, 10 => 2],
            $collection->setOperation(
                new Associative(['firstname' => 'John', 'age' => 25, 10 => 2])
            )->intersectAssoc()->toArray()
        );

        $this->assertSame(
            ['firstname' => 'John', 'age' => 25, 10 => 2],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname' => 'John', 'age' => 25, 10 => 2])
            )->intersectAssoc()->toArray()
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
    public function testIntersectAssocWithKey (Associative $collection):void {

        $this->assertSame(
            ['age' => 25, 10 => 2],
            $collection->setOperation(new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]))->intersectAssocWithKey(
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
        );

        $this->assertSame(
            ['age' => 25, 10 => 2],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname_x' => 'John', 'age' => 25, 10 => 2])
            )->intersectAssocWithKey(
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
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
    public function testIntersectAssocWithValue (Associative $collection):void {

        $this->assertSame(
            ['age' => 25, 10 => 2],
            $collection->setOperation(new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]))->intersectAssocWithValue(
                fn($value_a, $value_b) => $value_a <=> $value_b
            )->toArray()
        );

        $this->assertSame(
            ['age' => 25, 10 => 2],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname_x' => 'John', 'age' => 25, 10 => 2])
            )->intersectAssocWithValue(
                fn($value_a, $value_b) => $value_a <=> $value_b
            )->toArray()
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
    public function testIntersectAssocWithKeyValue (Associative $collection):void {

        $this->assertSame(
            ['age' => 25, 10 => 2],
            $collection->setOperation(new Associative(['firstname_x' => 'John', 'age' => 25, 10 => 2]))->intersectAssocWithKeyValue(
                fn($value_a, $value_b) => $value_a <=> $value_b,
                fn($key_a, $key_b) => $key_a <=> $key_b
            )->toArray()
        );

        $this->assertSame(
            ['age' => 25, 10 => 2],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname_x' => 'John', 'age' => 25, 10 => 2])
            )->intersectAssocWithKeyValue(
                fn($value_a, $value_b) => $value_a <=> $value_b,
                fn($key_a, $key_b) => $key_a <=> $key_b
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
    public function testSymmetricDifferenceValue (Indexed $collection):void {

        $this->assertSame(
            ['Jane', 'Jane', 'Jane', 'Marry'],
            $collection->setOperation(new Indexed(['John', 'Richard', 'Marry']))->symmetricDifferenceValue()->toArray()
        );

        $collection2 = new Fixed(3);
        $collection2[0] = 'John';
        $collection2[1] = 'Richard';
        $collection2[2] = 'Marry';

        $this->assertSame(
            ['Jane', 'Jane', 'Jane', 'Marry'],
            $collection->setOperation($collection2)->symmetricDifferenceValue()->toArray()
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
    public function testSymmetricDifferenceKey (Associative $collection):void {

        $this->assertSame(
            ['firstname_x' => 'John', 'firstname' => 'John'],
            $collection->setOperation(
                new Associative(['firstname_x' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2])
            )->symmetricDifferenceKey()->toArray()
        );

        $this->assertSame(
            ['firstname' => 'John', 'firstname_x' => 'John'],
            $collection->setOperation(
                new Lazy(fn() => yield from ['firstname_x' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2])
            )->symmetricDifferenceKey()->toArray()
        );

    }

}