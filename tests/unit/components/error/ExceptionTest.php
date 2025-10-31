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

namespace FireHub\Tests\Unit\Components\Error;

use FireHub\Core\Testing\Base;
use FireHub\Core\Components\Error\Exception;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small, TestWith
};

/**
 * ### Test main exception class
 * @since 1.0.0
 */
#[Small]
#[Group('components')]
#[CoversClass(Exception::class)]
final class ExceptionTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param class-string $exception
     * @param string $message
     *
     * @return void
     */
    #[TestWith([Exception::class, 'Test'])]
    public function testMessage (string $exception, string $message):void {

        $exception = new $exception();

        $this->assertSame($message, $exception->withMessage($message)->getMessage());
        $this->assertSame('x'.$message, $exception->prependMessage('x')->getMessage());
        $this->assertSame('x'.$message.'x', $exception->appendMessage('x')->getMessage());

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $exception
     *
     * @return void
     */
    #[TestWith([Exception::class])]
    public function testCode (string $exception):void {

        $this->assertSame(100, new $exception()->withCode(100)->getCode());

    }

    /**
     * @since 1.0.0
     *
     * @param class-string $exception
     *
     * @return void
     */
    #[TestWith([Exception::class])]
    public function testMagicCallMethod (string $exception):void {

        $exception = new $exception()->nonExistingMethod(100);

        $this->assertObjectHasProperty('info', $exception);
        $this->assertSame(['nonExistingMethod' => 100], $exception->info);

    }

}