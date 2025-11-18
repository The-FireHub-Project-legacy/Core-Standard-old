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
    Flag, Flags\Encode
};
use JsonSerializable as InternalJsonSerializable;

/**
 * ### JsonSerializable contract
 *
 * Objects implementing JsonSerializable can customize their JSON representation when encoded with json_encode().
 * @since 1.0.0
 */
interface JsonSerializable extends InternalJsonSerializable {

    /**
     * ### JSON representation of an object
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\Enums\JSON\Flag As parameter.
     * @uses \FireHub\Core\Support\Enums\JSON\Flags\Encode As parameter.
     *
     * @param positive-int $depth <p>
     * Set the maximum depth.
     * </p>
     * @param \FireHub\Core\Support\Enums\JSON\Flag|\FireHub\Core\Support\Enums\JSON\Flags\Encode ...$flags <p>
     * JSON flags.
     * </p>
     *
     * @return non-empty-string JSON encoded string.
     */
    public function toJson (int $depth = 512, Flag|Encode ...$flags):string;

    /**
     * ### Specify data which should be serialized to JSON
     *
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @since 1.0.0
     *
     * @return array<array-key, mixed> Data which can be serialized by json_encode(), which is a value of any type
     * other than a resource.
     */
    public function jsonSerialize ():array;

}