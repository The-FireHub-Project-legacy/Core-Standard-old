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
use FireHub\Core\Support\Enums\String\Encoding;
use FireHub\Core\Support\LowLevel\CharMB;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test multibyte character low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(CharMB::class)]
final class CharMBTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param non-negative-int $codepoint
     *
     * @throws \FireHub\Core\Support\Exceptions\Codepoint\CodepointToCharacterException
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'multiByte')]
    public function testIsList (string $string, int $codepoint, Encoding $encoding):void {

        $this->assertSame($string, CharMB::chr($codepoint, $encoding));

    }

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param non-negative-int $codepoint
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterToCodepointException
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'multiByte')]
    public function testOrd (string $string, int $codepoint, Encoding $encoding):void {

        $this->assertSame($codepoint, CharMB::ord($string, $encoding));

    }

}