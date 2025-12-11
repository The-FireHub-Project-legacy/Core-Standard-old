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

namespace FireHub\Tests\Unit\Support\Debug;

use FireHub\Core\Testing\Base;
use FireHub\Core\Support\Debug\ValueStringifier;
use PHPUnit\Framework\Attributes\ {
    CoversClass, Group, DataProvider, Small
};
use stdClass, Stringable;

/**
 * ### Test value stringifier debug class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(ValueStringifier::class)]
final class ValueStringifierTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param null|string $expected
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException
     *
     * @return void
     */
    #[DataProvider('scalarDataProvider')]
    public function testStringify (mixed $actual, ?string $expected):void {

        $this->assertSame($expected, new ValueStringifier()->stringify($actual));

    }


    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param null|string $expected
     *
     * @return void
     */
    #[DataProvider('scalarDataProvider')]
    public function testScalar (mixed $actual, ?string $expected):void {

        $this->assertSame($expected, new ValueStringifier()->scalar($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param null|string $expected
     *
     * @throws \FireHub\Core\Support\Exceptions\JSON\EncodingException
     *
     * @return void
     */
    #[DataProvider('arrayDataProvider')]
    public function testArr (mixed $actual, ?string $expected):void {

        $this->assertSame($expected, new ValueStringifier()->arr($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param null|string $expected
     *
     * @return void
     */
    #[DataProvider('stringableDataProvider')]
    public function testStringable (mixed $actual, ?string $expected):void {

        $this->assertSame($expected, new ValueStringifier()->stringable($actual));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param null|string $expected
     *
     * @return void
     */
    #[DataProvider('objectDataProvider')]
    public function testObject (mixed $actual, ?string $expected):void {

        $this->assertSame($expected, new ValueStringifier()->object($actual, false));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param null|string $expected
     *
     * @return void
     */
    #[DataProvider('objectDataProvider')]
    public function testObjectDetailed (mixed $actual, ?string $expected):void {

        $this->assertMatchesRegularExpression(
            '/^object\(stdClass\)\[\d+]$/',
            new ValueStringifier()->object($actual)
        );

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param null|string $expected
     *
     * @return void
     */
    #[DataProvider('resourceDataProvider')]
    public function testResource (mixed $actual, ?string $expected):void {

        $this->assertSame($expected, new ValueStringifier()->resource($actual, false));

    }

    /**
     * @since 1.0.0
     *
     * @param mixed $actual
     * @param null|string $expected
     *
     * @return void
     */
    #[DataProvider('resourceDataProvider')]
    public function testResourceDetailed (mixed $actual, ?string $expected):void {

        $this->assertMatchesRegularExpression(
            '/^resource\(\d+, stream\)$/',
            new ValueStringifier()->resource($actual)
        );

    }

    /**
     * @since 1.0.0
     *
     * @return array<array-key, mixed>
     */
    public static function scalarDataProvider ():array {

        return [
            [true, 'true'],
            [false, 'false'],
            [null, 'null'],
            [10, '10'],
            ['FireHub', 'FireHub']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array<array-key, mixed>
     */
    public static function arrayDataProvider ():array {

        return [
            [[1,2,3], '[1,2,3]'],
            [['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2], '{"firstname":"John","lastname":"Doe","age":25,"10":2}']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array<array-key, mixed>
     */
    public static function stringableDataProvider ():array {

        return [
            [new class implements Stringable {public function __toString():string {return 'hi!';}}, 'hi!']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array<array-key, mixed>
     */
    public static function objectDataProvider ():array {

        return [
            [new stdClass, 'stdClass']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array<array-key, mixed>
     */
    public static function objectDetailedDataProvider ():array {

        return [
            [new stdClass, 'object(stdClass)[74]']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array<array-key, mixed>
     */
    public static function resourceDataProvider ():array {

        return [
            [fopen('php://stdout', 'wb'), 'resource']
        ];


    }

    /**
     * @since 1.0.0
     *
     * @return array<array-key, mixed>
     */
    public static function resourceDetailedDataProvider ():array {

        return [
            [fopen('php://stdout', 'wb'), 'resource(7, stream)']
        ];


    }

}