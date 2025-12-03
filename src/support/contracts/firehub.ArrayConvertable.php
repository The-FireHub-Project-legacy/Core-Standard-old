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

namespace FireHub\Core\Support\Contracts;

/**
 * ### Array-convertible object that can be created from and converted to an array
 * @since 1.0.0
 */
interface ArrayConvertable extends Arrayable {

    /**
     * ### Get object from an array
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $array <p>
     * Data in form of an array from a new object will be created.
     * </p>
     *
     * @return static This object created from a provider array.
     */
    public static function fromArray (array $array):static;

}