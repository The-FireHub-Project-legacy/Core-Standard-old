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

use FireHub\Core\Support\Enums\JSON\ {
    Flag, Flags\Decode
};

/**
 * ### JsonSerializable-convertable object that can be created from and converted to JSON
 * @since 1.0.0
 */
interface JsonSerializeConvertable extends JsonSerializable {

    /**
     * ### Get object from JSON
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\JSON\Flag As parameter.
     * @uses \FireHub\Core\Support\Enums\JSON\Flags\Decode As parameter.
     *
     * @param string $json <p>
     * The JSON string being decoded.
     * </p>
     * @param positive-int $depth [optional] <p>
     * Set the maximum depth.
     * </p>
     * @param \FireHub\Core\Support\Enums\JSON\Flag|\FireHub\Core\Support\Enums\JSON\Flags\Decode ...$flags <p>
     * JSON flags.
     * </p>
     *
     * @return static This object from JSON encoded parameter.
     */
    public static function fromJson (string $json, int $depth = 512, Flag|Decode ...$flags):static;

}