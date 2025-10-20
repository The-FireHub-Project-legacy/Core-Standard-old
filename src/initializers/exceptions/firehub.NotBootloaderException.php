<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 7.4
 * @package Core\Initializers
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Initializers\Exceptions;

use FireHub\Core\Components\Error\Exception;

/**
 * ### Failed to load bootloader exception
 * @since 1.0.0
 *
 * @method $this fromClass (mixed $class) ### Class
 */
class NotBootloaderException extends Exception {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    protected string $default_message = "Class is not a bootloader.";

}