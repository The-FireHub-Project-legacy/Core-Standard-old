<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.1
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\Enums\Status;

/**
 * ### Value status enum
 * @since 1.0.0
 */
enum Value {

    /**
     * ### Value doesn't exist
     * @since 1.0.0
     */
    case NONE;

    /**
     * ### Value is explicitly empty
     * @since 1.0.0
     */
    case ABSENT;

    /**
     * ### Value used to exist, but it's not valid anymore
     * @since 1.0.0
     */
    case EXPIRED;

}