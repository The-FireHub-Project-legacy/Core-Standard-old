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

namespace FireHub\Tests\Smoke;

use FireHub\Core\Testing\Base;
use FireHub\Core\FireHub;
use FireHub\Core\Initializers\FireHubConfigurator;
use FireHub\Core\Kernel\ {
    Console, Http
};
use FireHub\Core\Initializers\Autoload;
use FireHub\Core\Initializers\Autoload\Loaders\ {
    Preloader, Psr4
};
use FireHub\Core\Initializers\Kernel;
use FireHub\Core\Initializers\Bootloaders\ {
    RegisterAutoloaders, RegisterConstants, RegisterHelpers
};
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small
};

/**
 * ### Test simple smoke
 * @since 1.0.0
 */
#[Small]
#[Group('smoke')]
#[CoversClass(FireHub::class)]
#[CoversClass(FireHubConfigurator::class)]
#[CoversClass(Console::class)]
#[CoversClass(Http::class)]
#[CoversClass(Autoload::class)]
#[CoversClass(Preloader::class)]
#[CoversClass(Psr4::class)]
#[CoversClass(RegisterAutoloaders::class)]
#[CoversClass(RegisterConstants::class)]
#[CoversClass(RegisterHelpers::class)]
#[CoversClass(Kernel::class)]
final class SmokeTest extends Base {

    /**
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Initializers\Exceptions\FailedToLoadBootloaderException
     * @throws \FireHub\Core\Initializers\Exceptions\NotBootloaderException
     * @throws \FireHub\Core\Initializers\Exceptions\NotKernelException
     *
     * @return void
     */
    public function testHttpSmoke ():void {

        $firehub = new FireHubConfigurator(__DIR__)
            ->withBootloaders([
                //
            ])
            ->withKernel(Http::class)
            ->create()
            ->boot();

        $this->assertIsString($firehub);

    }

    /**
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Initializers\Exceptions\FailedToLoadBootloaderException
     * @throws \FireHub\Core\Initializers\Exceptions\NotBootloaderException
     * @throws \FireHub\Core\Initializers\Exceptions\NotKernelException
     *
     * @return void
     */
    public function testConsoleSmoke ():void {

        $firehub = new FireHubConfigurator(__DIR__)
            ->withBootloaders([
                //
            ])
            ->withKernel(Console::class)
            ->create()
            ->boot();

        $this->assertIsString($firehub);

    }

}