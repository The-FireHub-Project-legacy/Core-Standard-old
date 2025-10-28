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
    ClsObj, Obj
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test object low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(ClsObj::class)]
#[CoversClass(Obj::class)]
final class ObjTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testID (string $name):void {

        $this->assertIsInt(Obj::id(new $name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testHash (string $name):void {

        $this->assertIsString(Obj::hash(new $name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    #[DataProviderExternal(ClsDataProvider::class, 'classes')]
    public function testProperties (string $name):void {

        $this->assertSame(['var1' => 'foo'], Obj::properties(new $name));

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $name
     *
     * @return void
     */
    public function testMangledProperties ():void {

        $class = new class {
            public string $var1 = 'foo';
            public string $var2;
        };

        $this->assertSame(['var1' => 'foo'], Obj::mangledProperties($class));

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

        $this->assertSame($boolean, Obj::methodExist(new $name, $method));

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

        $this->assertSame($boolean, Obj::propertyExist(new $name, $property));

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

        $this->assertSame($boolean, Obj::ofClass(new $name, $class));

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

        $this->assertSame($boolean, Obj::subClassOf(new $name, $class));

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

        $this->assertSame(['methodOne'], Obj::methods(new $name));

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

        $this->assertSame(TestAbstractClass::class, Obj::parentClass(new $name));

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

        $this->assertSame([TestAbstractClass::class => TestAbstractClass::class], Obj::parents(new $name));

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

        $this->assertSame([TestInterface::class => TestInterface::class], Obj::implements(new $name));

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

        $this->assertSame([TestTrait::class => TestTrait::class], Obj::uses(new $name));

    }

}