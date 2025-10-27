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

use FireHub\Core\Support\Enums\String\Encoding;

/**
 * ### Character data provider
 * @since 1.0.0
 */
final class CharDataProvider {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function singleByte ():array {

        return [
            ['!', 33],
            ['@', 64],
            ['a', 97]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function multiByte ():array {

        return [
            ['A', 65, Encoding::UTF_8],
            ['?', 63, Encoding::UTF_8],
            ['€', 0x20AC, Encoding::UTF_8],
            ['🐘', 128024, Encoding::UTF_8]
        ];

    }

}