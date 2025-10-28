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
USE FireHub\Tests\DataProviders\DateTimeDataProvider;
use FireHub\Core\Support\Enums\DateTime\Zone;
use FireHub\Core\Support\LowLevel\DateAndTimeZone;
use PHPUnit\Framework\Attributes\ {
    CoversClass, DataProviderExternal, Group, Small
};

/**
 * ### Test timezone low-level proxy class
 * @since 1.0.0
 */
#[Small]
#[Group('lowlevel')]
#[CoversClass(DateAndTimeZone::class)]
final class DateAndTimeZoneTest extends Base {

    /**
     * @since 1.0.0
     *
     * @param \FireHub\Core\Support\Enums\DateTime\Zone $zone
     *
     * @throws \FireHub\Core\Support\Exceptions\TimeZone\FailedToSetException
     * @throws \FireHub\Core\Support\Exceptions\TimeZone\FailedToGetException
     *
     * @return void
     */
    #[DataProviderExternal(DateTimeDataProvider::class, 'timezones')]
    public function testSetDefaultTimezone (Zone $zone):void {

        $this->assertTrue(DateAndTimeZone::setDefaultTimezone($zone));
        $this->assertSame($zone, DateAndTimeZone::getDefaultTimezone());

    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function testAbbreviationList ():void {

        $this->assertIsArray(DateAndTimeZone::abbreviationList());

    }

}