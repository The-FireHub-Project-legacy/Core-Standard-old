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

namespace FireHub\Core\Support\Exceptions\PHP;

use FireHub\Core\Support\Exceptions\PHPException;

/**
 * ### Failed to set configuration option exception
 * @since 1.0.0
 *
 * @method $this fromOption (string $option) ### Configuration option
 * @method $this withValue (null|int|float|string|bool $value) ### Configuration option value
 */
class FailedToSetConfigurationOption extends PHPException {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    protected string $default_message = 'Failed to set configuration option.';

}