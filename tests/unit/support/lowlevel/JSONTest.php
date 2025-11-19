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
use FireHub\Tests\DataProviders\JSONDataProvider;
use FireHub\Core\Support\Enums\JSON\ {
    Flag, Flags\Decode, Flags\Encode
};
use FireHub\Core\Support\Exceptions\JSON\ {
    DecodingException, EncodingException
};
use FireHub\Core\Support\LowLevel\JSON;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test JSON low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(JSON::class)]
final class JSONTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param string $expected
     * @param \FireHub\Core\Support\Enums\JSON\Flag|\FireHub\Core\Support\Enums\JSON\Flags\Encode ...$flags
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException
     *
     * @return void
     */
    #[DataProviderExternal(JSONDataProvider::class, 'encode')]
    public function testEncode (mixed $actual, string $expected, Flag|Encode ...$flags):void {

        $this->assertSame($expected, JSON::encode($actual, 512, ...$flags));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException
     *
     * @return void
     */
    #[TestWith(["\xB1\x31"])]
    public function testEncodeException (mixed $actual):void {

        $this->expectException(EncodingException::class);

        JSON::encode($actual);

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $expected
     * @param string $actual
     * @param \FireHub\Core\Support\Enums\JSON\Flag|\FireHub\Core\Support\Enums\JSON\Flags\Decode ...$flags
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\DecodingException
     *
     * @return void
     */
    #[DataProviderExternal(JSONDataProvider::class, 'decode')]
    public function testDecode (mixed $expected, string $actual, Flag|Decode ...$flags):void {

        $this->assertSame($expected, JSON::decode($actual, true, 512, ...$flags));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\DecodingException
     *
     * @return void
     */
    #[TestWith(["\xB1\x31"])]
    public function testDecodeException (mixed $actual):void {

        $this->expectException(DecodingException::class);

        JSON::decode($actual);

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $expected
     * @param string $actual
     * @param \FireHub\Core\Support\Enums\JSON\Flag|\FireHub\Core\Support\Enums\JSON\Flags\Decode ...$flags
     *
     * @return void
     */
    #[DataProviderExternal(JSONDataProvider::class, 'decode')]
    public function testValidate (mixed $expected, string $actual, Flag|Decode ...$flags):void {

        $this->assertTrue(JSON::validate($actual));

    }

}