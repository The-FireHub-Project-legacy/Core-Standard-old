<?php declare(strict_types = 1);

/**
 * This file is part of the FireHub Web Application Framework package
 *
 * @author Danijel Galić <danijel.galic@outlook.com>
 * @copyright 2025 FireHub Web Application Framework
 * @license <https://opensource.org/licenses/OSL-3.0> OSL Open Source License version 3
 *
 * @package Core\Test
 *
 * @version GIT: $Id$ Blob checksum.
 */

namespace FireHub\Tests\DataProviders;

use FireHub\Core\Testing\Base;

/**
 * ### Float numbers data provider
 * @since 1.0.0
 */
final class FloatDataProvider extends Base {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function positive ():array {

        return [
            [0.435435], [45.223], [43253.43543]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function negative ():array {

        return [
            [-0.3532545], [-1.2], [-10.567]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function null ():array {

        return [
            [0], [-0], [0.0000], [-0.00]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array
     */
    public static function notRoundFloats ():array {

        return [
            [0.5], [0.9], [-0.49]
        ];

    }

}