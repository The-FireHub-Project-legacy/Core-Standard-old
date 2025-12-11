<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 7.4
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Contracts\HighLevel;

use FireHub\Core\Support\Contracts\Magic\Stringable;

/**
 * ### Value objects
 * @since 1.0.0
 *
 * @template TValue
 */
interface ValueObjects extends Stringable {

    /**
     * ### Get ValueObject value as string
     * @since 1.0.0
     *
     * @return string The string representation of the value.
     */
    public function value ():string;

}