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

namespace FireHub\Core\Support\Helpers;

use FireHub\Core\Support\LowLevel\NumInt;
use FireHub\Core\Support\Exceptions\Number\ {
    ArithmeticException, DivideByZeroException
};

/**
 * ### Split microseconds into seconds and microseconds
 *
 * <code>
 * use function FireHub\Core\Support\Helpers\splitMicroseconds;
 *
 * splitMicroseconds(4_451_000);
 *
 * // [4, 451_000]
 * </code>
 *
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\LowLevel\NumInt::divide() To divide microseconds with 1_000_000.
 *
 * @param int $microseconds <p>
 * Number of microseconds to split.
 * </p>
 *
 * @return (
 *  $microseconds is negative-int
 *  ? array{negative-int, negative-int}
 *  : ($microseconds is positive-int
 *      ? array{positive-int, positive-int}
 *      : array{0, 0})
 * ) Seconds and microseconds.
 *
 * @api
 */
function splitMicroseconds (int $microseconds):array {

    try {

        /** @var array{negative-int, negative-int}|array{positive-int, positive-int}|array{0, 0} */
        return [
            NumInt::divide($microseconds, 1_000_000),
            $microseconds % 1_000_000
        ];

    } catch (ArithmeticException|DivideByZeroException) {

        return [0, 0];

    }

}