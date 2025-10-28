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
use FireHub\Core\Support\LowLevel\Func;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small, TestWith
};

/**
 * ### Test function and time low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(Func::class)]
final class FuncTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $name
     * @param bool $valid
     *
     * @return void
     */
    #[TestWith(['array_sum', true])]
    #[TestWith(['i_am_not_a_function', false])]
    public function testIsFunction (string $name, bool $valid):void {

        $this->assertSame($valid, Func::isFunction($name));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $result
     *
     * @return void
     */
    #[TestWith(['Hi!'])]
    public function testCall (mixed $result):void {

        $this->assertSame($result, Func::call(static fn() => $result));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $result
     *
     * @return void
     */
    #[TestWith(['Hi!'])]
    public function testCallWithArray (mixed $result):void {

        $this->assertSame($result, Func::callWithArray(static fn() => $result, []));

    }

    /**
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Support\Exceptions\Func\RegisterTickFailedException
     *
     * @return void
     */
    public function testRegisterFunctions ():void {

        $func = static fn() => 'x';

        $this->assertTrue(Func::registerTick($func));

        Func::unregisterTick($func);

        Func::registerShutdown($func);

    }

}