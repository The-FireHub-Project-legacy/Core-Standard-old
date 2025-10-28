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

namespace FireHub\Tests\DataProviders;

use FireHub\Core\Testing\Base;
use FireHub\Core\Support\Enums\DateTime\Zone;

/**
 * ### Date and time data provider
 * @since 1.0.0
 */
final class DateTimeDataProvider extends Base {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function validDates ():array {

        return [
            [1, 1, 1],
            [2024, 12, 31],
            [1000, 10, 6]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function invalidDates ():array {

        return [
            [0, -1, -1],
            [1, 1, 32],
            [1, 13, 12]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function stringToTime ():array {

        return [
            ['now'],
            ['10 September 2000'],
            ['+1 day'],
            ['+1 week'],
            ['+1 week 2 days 4 hours 2 seconds'],
            ['next Thursday'],
            ['last Monday']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function timezones ():array {

        $zones = [];
        foreach (Zone::cases() as $zone)
            $zones[][] = $zone;

        return $zones;

    }

}