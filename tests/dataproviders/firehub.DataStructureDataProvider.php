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
use FireHub\Core\Support\DataStructures\Linear\ {
    Indexed, Associative, Fixed, Lazy
};

/**
 * ### DataStructure data provider
 * @since 1.0.0
 */
final class DataStructureDataProvider extends Base {

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function indexedInt ():array {

        return [
            [new Indexed([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function indexedString ():array {

        return [
            [new Indexed(['John', 'Jane', 'Jane', 'Jane', 'Richard', 'Richard'])]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function indexedMixed ():array {

        return [
            [new Indexed(['John', 10, 12.5, null, false, true, [1, 2, 3]])]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function indexedEmpty ():array {

        return [
            [new Indexed([])]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function associative ():array {

        return [
            [new Associative(['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2])]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function associativeEmpty ():array {

        return [
            [new Associative([])]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function fixed ():array {

        $one = new Fixed(3);
        $one[0] = 'one';
        $one[1] = 'two';
        $one[2] = 'three';

        return [
            [$one]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function fixedEmpty ():array {

        $one = new Fixed(0);

        return [
            [$one]
        ];

    }

    /**
     * @since 1.0.0
     *
     * @return array[]
     */
    public static function lazy ():array {

        return [
            [new Lazy(fn() => yield from ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25, 10 => 2])]
        ];

    }

}