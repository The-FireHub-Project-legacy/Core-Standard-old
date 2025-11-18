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

namespace FireHub\Core\Support\Contracts\Magic;

/**
 * ### Serializable-convertable object that can be serialized and unserialized
 * @since 1.0.0
 */
interface SerializableConvertable extends Serializable {

    /**
     * ### Creates an object from a stored representation
     * @since 1.0.0
     *
     * @param non-empty-string $data <p>
     * The serialized string.
     * </p>
     * @param positive-int $max_depth [optional] <p>
     * The maximum depth of structures is permitted during unserialization and is intended to prevent stack overflows.
     * </p>
     *
     * @return static Object from a serialized parameter.
     */
    public static function unserialize (string $data, int $max_depth = 4096):static;

    /**
     * ### Converts from serialized data back to the object
     * @since 1.0.0
     *
     * @param array<array-key, mixed> $data <p>
     * Serialized data.
     * </p>
     *
     * @return void
     */
    public function __unserialize (array $data):void;

}