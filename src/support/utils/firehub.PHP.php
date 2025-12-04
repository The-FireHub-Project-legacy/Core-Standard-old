<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 7.0
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Utils;

use FireHub\Core\Support\LowLevel\PHP as PHPLowLevel;

use function FireHub\Core\Support\Helpers\DataTime\splitMicroseconds;

/**
 * ### PHP utility class
 * @since 1.0.0
 */
final class PHP {

    /**
     * ### Delays program execution for the given number of microseconds
     *
     * <code>
     * use FireHub\Core\Support\Utils\PHP;
     *
     * PHP::sleepMicroseconds(4_451_000);
     * </code>
     *
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Helpers\DataTime\splitMicroseconds() To split $microseconds into seconds
     * and microseconds.
     * @uses \FireHub\Core\Support\LowLevel\PHP::sleep() To sleep for a number of seconds from $microseconds.
     * @uses \FireHub\Core\Support\LowLevel\PHP::microsleep() To sleep for a number of microseconds from remaining from
     * $microseconds.
     *
     * @param non-negative-int $microseconds <p>
     * Halt time in microseconds.
     * </p>
     *
     * @return void
     */
    public static function sleepMicroseconds (int $microseconds):void {

        [$seconds, $remaining] = splitMicroseconds($microseconds);

        if ($seconds > 0) PHPLowLevel::sleep($seconds);

        if ($remaining > 0 && $remaining < 1_000_000) PHPLowLevel::microsleep($remaining);

    }

}