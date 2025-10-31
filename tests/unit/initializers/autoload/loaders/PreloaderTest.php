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

namespace FireHub\Tests\Unit\Initializers\Autoload\Loaders;

use FireHub\Core\Testing\Base;
use FireHub\Core\Initializers\Autoload\Loaders\Preloader;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, Small, TestWith
};

/**
 * ### Test preloader loader
 * @since 1.0.0
 */
#[Small]
#[Group('initializers')]
#[CoversClass(Preloader::class)]
final class PreloaderTest extends Base {

    /**
     * @since 1.0.0
     *
     * @var \FireHub\Core\Initializers\Autoload\Loaders\Preloader
     */
    private Preloader $preloader;

    /**
     * @since 1.0.0
     *
     * @var string
     */
    private string $class_prefix = 'firehub.';

    /**
     * @since 1.0.0
     *
     * @return void
     */
    protected function setUp ():void {

        $this->preloader = new Preloader($this->class_prefix);

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    #[TestWith([\FireHub\Core\Initializers\Autoload\Loaders\Preloader::class])]
    #[TestWith(['NonExistingClass'])]
    public function testInvokeRequiresFile (string $class):void {

        ($this->preloader)($class);

        $this->assertTrue(true);

    }

}