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

namespace FireHub\Tests\Unit\Support\ValueObjects\Text;

use FireHub\Core\Testing\Base;
use FireHub\Tests\DataProviders\CharDataProvider;
use FireHub\Core\Support\ValueObjects\Text\Char;
use FireHub\Core\Support\Enums\String\Encoding;
use FireHub\Core\Support\Exceptions\Char\CharacterLengthException;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test Char value object class
 * @since 1.0.0
 */
#[Small]
#[Group('valueobjects')]
#[CoversClass(Char::class)]
final class CharTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param non-negative-int $codepoint
     * @param Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterLengthException
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'multiByte')]
    public function testWithEncoding (string $string, int $codepoint, Encoding $encoding):void {

        $this->assertSame(Encoding::UTF_16, new Char($string, $encoding)->withEncoding(Encoding::UTF_16)->encoding());

    }

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param non-negative-int $codepoint
     * @param Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterLengthException
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'multiByte')]
    public function testValue (string $string, int $codepoint, Encoding $encoding):void {

        $this->assertSame($string, new Char($string)->value());

    }

    /**
     * @since 1.0.0
     *
     * @param string $string
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterLengthException
     *
     * @return void
     */
    #[TestWith([''])]
    #[TestWith(['FF'])]
    public function testStringLengthNotOne (string $string):void {

        $this->expectException(CharacterLengthException::class);

        new Char($string);

    }

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param non-negative-int $codepoint
     * @param Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterLengthException
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'multiByte')]
    public function testEncoding (string $string, int $codepoint, Encoding $encoding):void {

        $this->assertSame($encoding, new Char($string, $encoding)->encoding());

    }

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param non-negative-int $codepoint
     * @param Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterLengthException
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'multiByte')]
    public function testEquals (string $string, int $codepoint, Encoding $encoding):void {

        $this->assertTrue(new Char($string, $encoding)->equals($string));
        $this->assertFalse(new Char($string, $encoding)->equals('x'));

    }

    /**
     * @since 1.0.0
     *
     * @param string $string
     * @param non-negative-int $codepoint
     * @param Encoding $encoding
     *
     * @throws \FireHub\Core\Support\Exceptions\Char\CharacterLengthException
     *
     * @return void
     */
    #[DataProviderExternal(CharDataProvider::class, 'multiByte')]
    public function testMagicToString(string $string, int $codepoint, Encoding $encoding): void
    {
        $char = new Char($string);

        $this->assertSame($char->value(), (string)$char);
    }

}