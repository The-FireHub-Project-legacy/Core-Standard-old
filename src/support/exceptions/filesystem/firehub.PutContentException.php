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

namespace FireHub\Core\Support\Exceptions\FileSystem;

use FireHub\Core\Support\Exceptions\FileSystemException;

/**
 * ### Put content exception
 * @since 1.0.0
 *
 * @method $this withData (string[]|string $data) ### Data
 * @method $this withAppend (bool $append) ### Append
 * @method $this withLock (bool $lock) ### Lock
 * @method $this withCreateFile (bool $create_file) ### Create a file
 */
class PutContentException extends FileSystemException {

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    protected string $default_message = 'Cannot put content into file.';

}