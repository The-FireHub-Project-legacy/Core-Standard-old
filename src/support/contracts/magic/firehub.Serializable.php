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
 * ### Serializable contract
 *
 * Contract allows serialization for objects.
 * @since 1.0.0
 */
interface Serializable {

    /**
     * ### Generates storable representation of an object
     * @since 1.0.0
     *
     * @return string String containing a byte-stream representation of an object that can be stored anywhere.
     */
    public function serialize ():string;

    /**
     * ### Construct and return an associative array of key/value pairs that represent the serialized form of the object
     * @since 1.0.0
     *
     * @return array<array-key, mixed> An associative array of key/value pairs that represent the serialized form
     * of the object.
     */
    public function __serialize ():array;

}