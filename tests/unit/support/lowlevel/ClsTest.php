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
use FireHub\Tests\DataProviders\ {
    ClsDataProvider, TestAbstractClass, TestClass, TestInterface, TestTrait
};
use FireHub\Core\Support\Exceptions\Cls\NotFoundException;
use FireHub\Core\Support\LowLevel\ {
    Cls, ClsObj
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test class low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(ClsObj::class)]
#[CoversClass(Cls::class)]
final class ClsTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testIsClass (string $name):void {

        $this->assertTrue(Cls::isClass($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testIsNotClass (string $name):void {

        $this->assertFalse(Cls::isClass($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'interfaces')]
    public function testIsInterface (string $name):void {

        $this->assertTrue(Cls::isInterface($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testIsNotInterface (string $name):void {

        $this->assertFalse(Cls::isInterface($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'enums')]
    public function testIsEnum (string $name):void {

        $this->assertTrue(Cls::isEnum($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testIsNotEnum (string $name):void {

        $this->assertFalse(Cls::isEnum($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'traits')]
    public function testIsTrait (string $name):void {

        $this->assertTrue(Cls::isTrait($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testIsNotTrait (string $name):void {

        $this->assertFalse(Cls::isTrait($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $class
     * @param class-string $alias
     *
     * @throws \FireHub\Core\Support\Exceptions\Cls\FailedToCreateAliasException
     *
     * @return void
     */
    #[TestWith([Cls::class, 'NewCls'])]
    public function testAlias (string $class, string $alias):void {

        Cls::alias($class, $alias);

        $this->assertInstanceOf(\NewCls::class, new Cls());

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @throws \FireHub\Core\Support\Exceptions\Cls\NotFoundException
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testProperties (string $name):void {

        $this->assertSame(['var1' => 'foo', 'var3' => null], Cls::properties($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testPropertiesNotFound (string $name):void {

        $this->expectException(NotFoundException::class);

        Cls::properties($name);

    }

    /**
     * @since 1.0.0
     *
     * @param bool $boolean
     * @param class-string $name
     * @param non-empty-string $method
     *
     * @return void
     */
    #[TestWith([true, TestClass::class, 'methodOne'])]
    #[TestWith([false, TestClass::class, 'xxx'])]
    public function testMethodExist (bool $boolean, string $name, string $method):void {

        $this->assertSame($boolean, Cls::methodExist($name, $method));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $boolean
     * @param class-string $name
     * @param non-empty-string $property
     *
     * @return void
     */
    #[TestWith([true, TestClass::class, 'var1'])]
    #[TestWith([false, TestClass::class, 'xxx'])]
    public function testPropertyExist (bool $boolean, string $name, string $property):void {

        $this->assertSame($boolean, Cls::propertyExist($name, $property));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $boolean
     * @param class-string $name
     * @param class-string $class
     *
     * @return void
     */
    #[TestWith([true, TestClass::class, TestInterface::class])]
    #[TestWith([false, TestClass::class, 'xxx'])]
    public function testOfClass (bool $boolean, string $name, string $class):void {

        $this->assertSame($boolean, Cls::ofClass($name, $class));

    }

    /**
     * @since 1.0.0
     *
     * @param bool $boolean
     * @param class-string $name
     * @param class-string $class
     *
     * @return void
     */
    #[TestWith([true, TestClass::class, TestAbstractClass::class])]
    #[TestWith([false, TestClass::class, 'xxx'])]
    public function testSubClassOf (bool $boolean, string $name, string $class):void {

        $this->assertSame($boolean, Cls::subClassOf($name, $class));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @throws \FireHub\Core\Support\Exceptions\Cls\NotFoundException
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testMethods (string $name):void {

        $this->assertSame(['methodOne'], Cls::methods($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testMethodsNotFound (string $name):void {

        $this->expectException(NotFoundException::class);

        Cls::methods($name);

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testParentClass (string $name):void {

        $this->assertSame(TestAbstractClass::class, Cls::parentClass($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @throws \FireHub\Core\Support\Exceptions\Cls\NotFoundException
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testParents (string $name):void {

        $this->assertSame([TestAbstractClass::class => TestAbstractClass::class], Cls::parents($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testParentsNotFound (string $name):void {

        $this->expectException(NotFoundException::class);

        Cls::parents($name, false);

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @throws \FireHub\Core\Support\Exceptions\Cls\NotFoundException
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testImplements (string $name):void {

        $this->assertSame([TestInterface::class => TestInterface::class], Cls::implements($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testImplementsNotFound (string $name):void {

        $this->expectException(NotFoundException::class);

        Cls::implements($name, false);

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @throws \FireHub\Core\Support\Exceptions\Cls\NotFoundException
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testUses (string $name):void {

        $this->assertSame([TestTrait::class => TestTrait::class], Cls::uses($name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'nonExisting')]
    public function testUsesNotFound (string $name):void {

        $this->expectException(NotFoundException::class);

        Cls::uses($name, false);

    }

}