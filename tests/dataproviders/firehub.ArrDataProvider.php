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
 * ### Array data provider
 * @since 1.0.0
 */
final class ArrDataProvider {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function list ():array {

        return [
            [[1, 2, 3]],
            [[10.2, 200, -3]],
            [['x', 'y', 'z']],
            [['', 'test']]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function associative ():array {

        return [
            [['one' => 1, 'two' => 2, 'three' => 3]],
            [['ONE' => 1, 'TWO' => 2, 'THREE' => 3]],
            [[2 => '', 'x' => null, 5 => false]]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function multidimensional ():array {

        return [
            [['one' => [1, 2, 3], 'two' => [4, 5, 6], 'three' => [7, 8, 9]]],
            [[1 => [1 => null, 2 => false], 2 => [1 => true, 'x' => false]]]
        ];

    }

}