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

namespace FireHub\Tests\Unit\Support\LowLevel;

use FireHub\Core\Testing\Base;
use FireHub\Tests\DataProviders\DataDataProvider;
use FireHub\Core\Support\Enums\Data\ {
    Category, Type
};
use FireHub\Core\Support\Exceptions\Data\ {
    ArrayToStringConversionException, CannotSerializeException, SetAsResourceException, UnserializeFailedException
};
use FireHub\Core\Support\LowLevel\ {
    Data, DataIs
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test data low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Data::class)]
#[CoversClass(DataIs::class)]
#[CoversClass(Type::class)]
final class DataTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    public function testArray (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::array($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotArray (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::array($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    public function testBool (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::bool($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotBool (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::bool($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    public function testCallable (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::callable($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotCallable (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::callable($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    public function testCountable (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::countable($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotCountable (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::countable($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    public function testFloat (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::float($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotFloat (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::float($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    public function testInt (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::int($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotInt (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::int($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    public function testIterable (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::iterable($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotIterable (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::iterable($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    public function testNull (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::null($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotNull (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::null($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    public function testNumeric (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::numeric($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotNumeric (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::numeric($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    public function testObject (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::object($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotObject (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::object($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testResource (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::resource($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    public function testNotResource (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::resource($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    public function testScalar (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::scalar($value));

    }


    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotsScalar (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::scalar($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    public function testString (mixed $value, Type $type):void {

        $this->assertTrue(DataIs::string($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testNotString (mixed $value, Type $type):void {

        $this->assertFalse(DataIs::string($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    public function testGetDebugType (mixed $value, Type $type):void {

        $this->assertSame('string', Data::getDebugType($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\TypeUnknownException
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testGetType (mixed $value, Type $type):void {

        $this->assertSame($type, Data::getType($value));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\TypeUnknownException
     * @throws \FireHub\Core\Support\Exceptions\Data\ArrayToStringConversionException
     * @throws \FireHub\Core\Support\Exceptions\Data\FailedToSetTypeException
     * @throws \FireHub\Core\Support\Exceptions\Data\SetAsResourceException
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    public function testSetType (mixed $value, Type $type):void {

        $this->assertSame($value, Data::setType($value, $type));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\TypeUnknownException
     * @throws \FireHub\Core\Support\Exceptions\Data\ArrayToStringConversionException
     * @throws \FireHub\Core\Support\Exceptions\Data\FailedToSetTypeException
     * @throws \FireHub\Core\Support\Exceptions\Data\SetAsResourceException
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    public function testSetTypeStringFromArray (mixed $value, Type $type):void {

        $this->expectException(ArrayToStringConversionException::class);

        Data::setType($value, Type::T_STRING);

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\TypeUnknownException
     * @throws \FireHub\Core\Support\Exceptions\Data\ArrayToStringConversionException
     * @throws \FireHub\Core\Support\Exceptions\Data\FailedToSetTypeException
     * @throws \FireHub\Core\Support\Exceptions\Data\SetAsResourceException
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'string')]
    #[DataProviderExternal(DataDataProvider::class, 'int')]
    #[DataProviderExternal(DataDataProvider::class, 'float')]
    #[DataProviderExternal(DataDataProvider::class, 'array')]
    #[DataProviderExternal(DataDataProvider::class, 'null')]
    #[DataProviderExternal(DataDataProvider::class, 'bool')]
    #[DataProviderExternal(DataDataProvider::class, 'callable')]
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    #[DataProviderExternal(DataDataProvider::class, 'resource')]
    public function testSetTypeFromResource (mixed $value, Type $type):void {

        $this->expectException(SetAsResourceException::class);

        Data::setType($value, Type::T_RESOURCE);

    }

    /**
     * @since 1.0.0
     *
     * @param null|scalar|array<array-key, mixed>|object $value
     * @param string $result
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\CannotSerializeException
     *
     * @return void
     */
    #[TestWith([['one' => 1, 'two' => 2, 'three' => 3], 'a:3:{s:3:"one";i:1;s:3:"two";i:2;s:5:"three";i:3;}'])]
    #[TestWith(['This is long string.', 's:20:"This is long string.";'])]
    public function testSerialize (null|string|int|float|bool|array|object $value, string $result):void {

        $this->assertSame($result, Data::serialize($value));

    }

    /**
     * @since 1.0.0
     *
     * @param null|scalar|array<array-key, mixed>|object $value
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[DataProviderExternal(DataDataProvider::class, 'countable')]
    public function testSerializeAnonymousClasses (null|string|int|float|bool|array|object $value, Type $type):void {

        $this->expectException(CannotSerializeException::class);

        Data::serialize($value);

    }

    /**
     * @since 1.0.0
     *
     * @param null|scalar|array<array-key, mixed>|object $result
     * @param string $value
     *
     * @throws \FireHub\Core\Support\Exceptions\Data\UnserializeFailedException
     *
     * @return void
     */
    #[TestWith([['one' => 1, 'two' => 2, 'three' => 3], 'a:3:{s:3:"one";i:1;s:3:"two";i:2;s:5:"three";i:3;}'])]
    #[TestWith(['This is long string.', 's:20:"This is long string.";'])]
    public function testUnserialize (null|string|int|float|bool|array|object $result, string $value):void {

        $this->assertSame($result, Data::unserialize($value));

    }

    /**
     * @since 1.0.0
     *
     * @param string $value
     *
     * @return void
     */
    #[TestWith(['b:0;'])]
    #[TestWith(['N;'])]
    public function testUnserializeFalse (string $value):void {

        $this->expectException(UnserializeFailedException::class);

        Data::unserialize($value);

    }

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Enums\Data\Category $category
     * @param \FireHub\Core\Support\Enums\Data\Type $type
     *
     * @return void
     */
    #[TestWith([Category::SCALAR, Type::T_FLOAT])]
    #[TestWith([Category::COMPOUND, Type::T_OBJECT])]
    #[TestWith([Category::SPECIAL, Type::T_RESOURCE])]
    #[TestWith([Category::SCALAR, Type::T_INT])]
    #[TestWith([Category::SPECIAL, Type::T_NULL])]
    #[TestWith([Category::SCALAR, Type::T_BOOL])]
    #[TestWith([Category::COMPOUND, Type::T_ARRAY])]
    #[TestWith([Category::SCALAR, Type::T_STRING])]
    public function testCategory (Category $category, Type $type):void {

        $this->assertSame($category, $type->category());

    }

}