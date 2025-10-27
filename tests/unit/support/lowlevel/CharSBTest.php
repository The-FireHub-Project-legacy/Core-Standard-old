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
use FireHub\Tests\DataProviders\CharDataProvider;
use FireHub\Core\Support\LowLevel\CharSB;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test single-byte character low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(CharSB::class)]
final class CharSBTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param int<0, 255> $codepoint
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'singleByte')]
    public function testIsList (string $string, int $codepoint):void {

        $this->assertSame($string, CharSB::chr($codepoint));

    }

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param int<0, 255> $codepoint
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'singleByte')]
    public function testOrd (string $string, int $codepoint):void {

        $this->assertSame($codepoint, CharSB::ord($string));

    }

}