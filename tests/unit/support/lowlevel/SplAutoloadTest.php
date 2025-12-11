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
use FireHub\Core\Support\LowLevel\SplAutoload;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small, TestWith
};

/**
 * ### Test SPL Autoload low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(SplAutoload::class)]
final class SplAutoloadTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param bool $expected
     * @param bool $prepend
     *
     * @throws \FireHub\Core\Support\Exceptions\Autoload\RegisterAutoloaderException
     * @throws \FireHub\Core\Support\Exceptions\Autoload\UnregisterAutoloaderException
     *
     * @return void
     */
    #[TestWith([true, false])]
    public function testRegisterUnregister (bool $expected, bool $prepend = false):void {

        $callback = static function ($string) {};

        $this->assertSame($expected, SplAutoload::register($callback, $prepend));

        $this->assertSame($expected, SplAutoload::unregister($callback));

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testFunctions ():void {

        $this->assertIsList(SplAutoload::functions());

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $class
     *
     * @return void
     */
    #[TestWith(['SplAutoload'])]
    public function testLoad (string $class):void {

        SplAutoload::load($class);

        $this->assertTrue(true);

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testExtensions ():void {

        $this->assertIsString(SplAutoload::extensions());

    }

}