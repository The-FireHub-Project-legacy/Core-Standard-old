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

namespace FireHub\Core\Support\Enums;

/**
 * ### Control flow signals enum
 * @since 1.0.0
 */
enum ControlFlowSignal {

    /**
     * ### Brake ends execution of the current for, foreach, while, do-while or switch structure
     * @since 1.0.0
     */
    case BREAK;

}