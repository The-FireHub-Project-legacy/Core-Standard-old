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

namespace FireHub\Core\Support\Helpers\PHP;

use const FireHub\Core\Support\Constants\Number\SIZE;

/**
 * ### Check if using a 64bit version of PHP
 *
 * <code>
 * use function FireHub\Core\Support\Helpers\PHP\is64bit;
 *
 * is64bit();
 *
 * // true
 * </code>
 *
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\Constants\Number\SIZE To get the size of an integer in bytes in this build of PHP.
 *
 * @return bool True if using the 64bit version of PHP, otherwise false.
 *
 * @api
 */
function is64bit ():bool {

    return SIZE === 8;

}

/**
 * ### Check if using a 32bit version of PHP
 *
 * <code>
 * use function FireHub\Core\Support\Helpers\PHP\is32bit;
 *
 * is32bit();
 *
 * // false
 * </code>
 *
 * @since 1.0.0
 *
 * @uses \FireHub\Core\Support\Constants\Number\SIZE To get the size of an integer in bytes in this build of PHP.
 *
 * @return bool True if using the 32bit version of PHP, otherwise false.
 *
 * @api
 */
function is32bit ():bool {

    return SIZE === 4;

}