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

/**
 * ### Constant data provider
 * @since 1.0.0
 */
final class ConstantDataProvider {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function types ():array {

        return [
            ['testString', 'test'],
            ['testInt', 10],
            ['testBool', false],
            ['testNull', null],
            ['testArray', ['x']]
        ];

    }

}