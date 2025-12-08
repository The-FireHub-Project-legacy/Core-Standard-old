<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @php-version 8.0
 * @package Core\Support
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Core\Support\DataStructures\Contracts;

use FireHub\Core\Support\DataStructures\Operation\ {
    Select, Skip
};

/**
 * ### Data structure that is capable of selecting part of itself
 * @since 1.0.0
 *
 * @template TKey
 * @template TValue
 *
 * @extends \FireHub\Core\Support\DataStructures\Contracts\Filterable<TKey, TValue>
 */
interface Selectable extends Filterable {

    /**
     * ### Select operations for data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Select As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\Select<$this>
     */
    public function select ():Select;

    /**
     * ### Skip operations for data structure
     * @since 1.0.0
     *
     * @uses \FireHub\Core\Support\DataStructures\Operation\Skip As return.
     *
     * @return \FireHub\Core\Support\DataStructures\Operation\Skip<$this>
     */
    public function skip ():Skip;

}