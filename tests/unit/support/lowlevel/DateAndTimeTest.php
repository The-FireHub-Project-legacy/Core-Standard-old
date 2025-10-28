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
use FireHub\Tests\DataProviders\DateTimeDataProvider;
use FireHub\Core\Support\Exceptions\DateTime\ {
    ParseFromFormatException, StringToTimestampException
};
use FireHub\Core\Support\LowLevel\DateAndTime;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small, TestWith
};

/**
 * ### Test date and time low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(DateAndTime::class)]
final class DateAndTimeTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param int<1, 32767> $year
     * @param int<1, 12> $month
     * @param int<1, 31> $day
     *
     * @return void
     */
    #[DataProviderExternal(DateTimeDataProvider::class, 'validDates')]
    public function testCheckValid (int $year, int $month, int $day):void {

        $this->assertTrue(DateAndTime::check($year, $month, $day));

    }

    /**
     * @since 1.0.0
     *
     * @param int<1, 32767> $year
     * @param int<1, 12> $month
     * @param int<1, 31> $day
     *
     * @return void
     */
    #[DataProviderExternal(DateTimeDataProvider::class, 'invalidDates')]
    public function testCheckInvalid (int $year, int $month, int $day):void {

        $this->assertFalse(DateAndTime::check($year, $month, $day));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $datetime
     *
     * @return void
     */
    #[DataProviderExternal(DateTimeDataProvider::class, 'stringToTime')]
    public function testParse (string $datetime):void {

        $this->assertEmpty(DateAndTime::parse($datetime)['errors']);

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $format
     * @param non-empty-string $datetime
     *
     * @throws \FireHub\Core\Support\Exceptions\DateTime\ParseFromFormatException
     *
     * @return void
     */
    #[TestWith(['j.n.Y H:iP', '6.1.2009 13:00+01:00'])]
    public function testParseFromFormat (string $format, string $datetime):void {

        $this->assertEmpty(DateAndTime::parseFromFormat($format, $datetime)['errors']);

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $format
     * @param non-empty-string $datetime
     *
     * @return void
     */
    #[TestWith(['Y', "\0"])]
    public function testParseFromFormatContainsNulByte (string $format, string $datetime):void {

        $this->expectException(ParseFromFormatException::class);

        DateAndTime::parseFromFormat($format, $datetime);

    }

    /**
     * @since 1.0.0
     *
     * @param string $result
     * @param string $format
     * @param null|int $timestamp
     * @param bool $gmt
     *
     * @return void
     */
    #[TestWith(['1970-01-01T01:00:00+01:00', DATE_ATOM, 0, false])]
    #[TestWith(['Thursday, 01-Jan-1970 01:00:00 CET', DATE_COOKIE, 0, false])]
    #[TestWith(['Thu, 01 Jan 1970 01:00:00 +0100', DATE_RSS, 0, false])]
    public function testFormat (string $result, string $format, ?int $timestamp, bool $gmt):void {

        $this->assertSame($result, DateAndTime::format($format, $timestamp, $gmt));

    }

    /**
     * @since 1.0.0
     *
     * @param int $result
     * @param string $format
     * @param null|int $timestamp
     *
     * @throws \FireHub\Core\Support\Exceptions\DateTime\FailedToFormatTimestampAsIntException
     *
     * @return void
     */
    #[TestWith([70, 'y', 0])]
    #[TestWith([1970, 'Y', 0])]
    #[TestWith([1, 'd', 0])]
    #[TestWith([0, 'z', 0])]
    #[TestWith([1, 'W', 0])]
    #[TestWith([4, 'w', 0])]
    #[TestWith([0, 'U', 0])]
    #[TestWith([31, 't', 0])]
    #[TestWith([0, 's', 0])]
    #[TestWith([1, 'm', 0])]
    #[TestWith([0, 'i', 0])]
    #[TestWith([1, 'H', 0])]
    #[TestWith([1, 'h', 0])]
    public function testFormatInteger (int $result, string $format, ?int $timestamp):void {

        $this->assertSame($result, DateAndTime::formatInteger($format, $timestamp));

    }

    /**
     * @since 1.0.0
     *
     * @param string $info
     * @param mixed $result
     * @param null|int $timestamp
     *
     * @return void
     */
    #[TestWith(['seconds', 0, 0])]
    #[TestWith(['minutes', 0, 0])]
    #[TestWith(['hours', 1, 0])]
    #[TestWith(['mday', 1, 0])]
    #[TestWith(['wday', 4, 0])]
    #[TestWith(['mon', 1, 0])]
    #[TestWith(['year', 1970, 0])]
    #[TestWith(['yday', 0, 0])]
    #[TestWith(['weekday', 'Thursday', 0])]
    #[TestWith(['month', 'January', 0])]
    #[TestWith(['timestamp', 0, 0])]
    #[TestWith(['timestamp', 0, 0])]
    public function testGet (string $info, mixed $result, ?int $timestamp):void {

        $this->assertSame($result, DateAndTime::get($timestamp)[$info]);

    }


    /**
     * @since 1.0.0
     *
     * @param array<string, int> $result
     * @param int $timestamp
     * @param float $latitude
     * @param float $longitude
     *
     * @return void
     */
    #[TestWith([[
        'sunrise' => 44299,
        'sunset' => 78022,
        'transit' => 61160,
        'civil_twilight_begin' => 42549,
        'civil_twilight_end' => 79772,
        'nautical_twilight_begin' => 40488,
        'nautical_twilight_end' => 81833,
        'astronomical_twilight_begin' => 38493,
        'astronomical_twilight_end' => 83828
    ], 0, 40.730610, -73.935242])]
    public function testSunInfo (array $result, int $timestamp, float $latitude, float $longitude):void {

        $this->assertSame($result, DateAndTime::sunInfo($timestamp, $latitude, $longitude));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $datetime
     *
     * @throws \FireHub\Core\Support\Exceptions\DateTime\StringToTimestampException
     *
     * @return void
     */
    #[DataProviderExternal(DateTimeDataProvider::class, 'stringToTime')]
    public function testStringToTimestamp (string $datetime):void {

        $this->assertIsInt(DateAndTime::stringToTimestamp($datetime));

    }

    /**
     * @since 1.0.0
     *
     * @param non-empty-string $datetime
     *
     * @return void
     */
    #[TestWith(['NotTime'])]
    public function testStringToTimestampNotTime (string $datetime):void {

        $this->expectException(StringToTimestampException::class);

        DateAndTime::stringToTimestamp($datetime);

    }

    /**
     * @since 1.0.0
     *
     * @param int<0, 24> $hour
     * @param null|int<0, 59> $minute
     * @param null|int<0, 59> $second
     * @param null|int $year
     * @param null|int<0, 12> $month
     * @param null|int<0, 31> $day
     * @param bool $gmt
     *
     * @throws \FireHub\Core\Support\Exceptions\TimestampException
     *
     * @return void
     */
    #[TestWith([0, 0, 0, 1970, 1, 1, false])]
    #[TestWith([0, 0, 0, 1970, 1, 1, true])]
    public function testToTimestamp (int $hour, ?int $minute, ?int $second, ?int $year, ?int $month, ?int $day, bool $gmt):void {

        $this->assertIsInt(DateAndTime::toTimestamp($hour, $minute, $second, $year, $month, $day, $gmt));

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testTime ():void {

        $this->assertIsInt(DateAndTime::time());

    }

    /**
     * @since 1.0.0
     *
     * @throws \FireHub\Core\Support\Exceptions\DateTime\FailedToGetMicrotimeException
     *
     * @return void
     */
    public function testMicrotime ():void {

        $this->assertIsInt(DateAndTime::microtime());

    }

}