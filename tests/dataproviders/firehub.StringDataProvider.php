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
 * ### String data provider
 * @since 1.0.0
 */
final class StringDataProvider extends Base {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function empty ():array {

        return [
            []
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function boolean ():array {

        return [
            ['true'],
            ['false']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function numbers ():array {

        return [
            ['0'],
            ['-10'],
            ['2'],
            ['12.563']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function stringsSB ():array {

        return [
            ['The lazy fox jumped over the fence.']
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function stringsMB ():array {

        return [
            ['đščćž 诶杰艾玛 ЛЙ ÈßÁ カタカナ']
        ];

    }

}