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

namespace FireHub\Tests\DataProviders;

/**
 * ### Class data provider
 * @since 1.0.0
 */
final class ClsDataProvider {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function nonExisting ():array {

        return [
            ['NonExistingClass']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function classes ():array {

        return [
            [TestClass::class]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function interfaces ():array {

        return [
            [TestInterface::class]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function enums ():array {

        return [
            [TestEnum::class]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function traits ():array {

        return [
            [TestTrait::class]
        ];

    }

}

class TestAbstractClass {}

class TestClass extends TestAbstractClass implements TestInterface {
    use TestTrait;
    public string $var1 = 'foo';
    protected string $var2 = 'bar';
    public string $var3;

    public function methodOne ():void {}

    protected function methodTwo ():void {}
}

interface TestInterface {}

trait TestTrait {}

enum TestEnum {}